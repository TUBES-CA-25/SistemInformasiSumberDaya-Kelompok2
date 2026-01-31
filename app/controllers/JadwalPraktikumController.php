<?php
// Proteksi awal: Bersihkan semua buffer karakter sampah
while (ob_get_level()) { ob_end_clean(); } 

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/JadwalPraktikumModel.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class JadwalPraktikumController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \JadwalPraktikumModel();
    }

    public function uploadExcel() {
        // Bersihkan semua output buffer
        while (ob_get_level()) { ob_end_clean(); }
        
        // Set header response JSON yang ketat
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
        // Set error handler untuk catch PHP errors
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            http_response_code(500);
            die(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'PHP Error: ' . $errstr
            ]));
        });

        try {
            if (!isset($_FILES['excel_file'])) throw new Exception("File tidak ditemukan.");

            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $success = 0;
            $duplicate = 0;
            $invalidLab = 0;

            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) { $rowData[] = $cell->getFormattedValue(); }

                if (empty(array_filter($rowData))) continue;

                // MAPPING BERDASARKAN FILE EXCEL ANDA (Jadwal Lab.xlsx)
                $dosenNama = trim($rowData[2] ?? '');  // Kolom C (Dosen)
                $mkNama    = trim($rowData[3] ?? '');  // Kolom D (Mata Kuliah)
                $kelas     = trim($rowData[5] ?? '');  // Kolom F (Kelas)
                $freq      = trim($rowData[6] ?? '');  // Kolom G (Frekuensi)
                $labNama   = trim($rowData[7] ?? '');  // Kolom H (Ruangan)
                $hari      = trim($rowData[8] ?? '');  // Kolom I (Hari)
                $jamFull   = str_replace('.', ':', trim($rowData[9] ?? '')); // Kolom J (Jam)
                $asisten1  = trim($rowData[11] ?? ''); // Kolom L (Asisten 1)
                $asisten2  = trim($rowData[12] ?? ''); // Kolom M (Asisten 2)

                // Split jam (Contoh: 07:00 - 09:30)
                $parts = explode('-', $jamFull);
                $start = trim($parts[0] ?? '00:00');
                $end   = isset($parts[1]) ? trim($parts[1]) : $start;

                // ANTI-0 IMPOR: Cari atau buat otomatis data master
                $idMK = $this->findOrCreateMaster('matakuliah', 'namaMatakuliah', $mkNama);
                $idLab = $this->findExistingMaster('laboratorium', 'nama', $labNama);

                if (!$idLab) {
                    // Skip jika lab tidak ada di database
                    $invalidLab++;
                    continue;
                }

                // Mapping Asisten: Cari ID berdasarkan Nama (jika string)
                $idAsisten1 = $this->findAsistenIdByName($asisten1);
                $idAsisten2 = $this->findAsistenIdByName($asisten2);

                if ($idMK && $idLab) {
                    // CEK DUPLIKASI sebelum insert
                    if ($this->model->checkDuplicate($idMK, $kelas, ucfirst(strtolower($hari)), $start, $end, $idLab)) {
                        // Data sudah ada, skip
                        $duplicate++;
                        continue;
                    }

                    $this->model->insert([
                        'idMatakuliah'   => $idMK,
                        'kelas'          => $kelas,
                        'idLaboratorium' => $idLab,
                        'hari'           => ucfirst(strtolower($hari)),
                        'waktuMulai'     => $start,
                        'waktuSelesai'   => $end,
                        'dosen'          => $dosenNama,
                        'asisten1'       => !empty($idAsisten1) ? (int)$idAsisten1 : null,
                        'asisten2'       => !empty($idAsisten2) ? (int)$idAsisten2 : null,
                        'frekuensi'      => $freq,
                        'status'         => 'Aktif'
                    ]);
                    $success++;
                }
            }
            
            $message = "Berhasil mengimpor $success data.";
            if ($duplicate > 0) {
                $message .= " ($duplicate data duplikat dilewati)";
            }
            if ($invalidLab > 0) {
                $message .= " ($invalidLab baris dilewati karena lab tidak terdaftar)";
            }
            echo json_encode(['status' => 'success', 'code' => 201, 'message' => $message]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'code' => 400, 'message' => $e->getMessage()]);
        } finally {
            restore_error_handler();
        }
        exit;
    }

    private function findOrCreateMaster($table, $column, $name) {
        if (empty($name)) return null;
        $db = $this->model->db;
        $stmt = $db->prepare("SELECT * FROM $table WHERE LCASE(TRIM($column)) = LCASE(TRIM(?)) LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        if ($res) return $res[array_key_first($res)];
        
        // Buat baru jika tidak ditemukan
        $db->query("INSERT INTO $table ($column) VALUES ('".addslashes($name)."')");
        return $db->insert_id;
    }

    private function findExistingMaster($table, $column, $name) {
        if (empty($name)) return null;
        $db = $this->model->db;
        $stmt = $db->prepare("SELECT * FROM $table WHERE LCASE(TRIM($column)) = LCASE(TRIM(?)) LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res[array_key_first($res)] : null;
    }
    
    // Helper Cari ID Asisten by Nama (Smart Search)
    private function findAsistenIdByName($name) {
        if (empty($name) || is_numeric($name)) return $name; 
        
        $db = $this->model->db;
        $cleanName = trim($name);

        // 1. Cek Exact Match (Case Insensitive)
        $stmt = $db->prepare("SELECT idAsisten, nama FROM asisten WHERE LCASE(TRIM(nama)) = LCASE(?) LIMIT 1");
        $stmt->bind_param("s", $cleanName);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res) return $res['idAsisten'];
        
        // 2. Cek Partial Match (LIKE %nama%) - Untuk kasus "Tazkirah" vs "tazkirah" atau beda spasi
        $likeName = "%" . $cleanName . "%";
        $stmt = $db->prepare("SELECT idAsisten, nama FROM asisten WHERE nama LIKE ? LIMIT 1");
        $stmt->bind_param("s", $likeName);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res) return $res['idAsisten'];

        // 3. Cek Reverse Match untuk Gelar (Misal DB: "Berlian Septiani", Excel: "Berlian Septiani, S.Kom")
        // Ambil kata pertama saja sebagai kunci pencarian dasar
        $parts = explode(' ', $cleanName);
        if (count($parts) >= 1) {
            $firstName = $parts[0];
            if (strlen($firstName) > 3) { // Minimal 3 huruf untuk menghindari salah match nama pendek
                 $likeFirst = $firstName . "%";
                 $stmt = $db->prepare("SELECT idAsisten, nama FROM asisten WHERE nama LIKE ? LIMIT 5");
                 $stmt->bind_param("s", $likeFirst);
                 $stmt->execute();
                 $result = $stmt->get_result();
                 
                 // Loop hasil pencarian (misal ketemu 3 Berlian), cari yang paling mirip
                 while ($row = $result->fetch_assoc()) {
                     // Cek kemiripan string menggunakan similar_text atau strpos
                     if (stripos($cleanName, $row['nama']) !== false || stripos($row['nama'], $cleanName) !== false) {
                         return $row['idAsisten'];
                     }
                 }
            }
        }
        
        // Jika tetap tidak ketemu, log errornya biar ketahuan
        error_log("Gagal menemukan ID Asisten untuk nama: " . $name);
        return null;
    }

    public function apiIndex() {
        // Bersihkan semua output buffer sebelumnya
        while (ob_get_level()) { ob_end_clean(); }
        
        // Set header response JSON yang ketat
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        
        // Set error handler untuk catch PHP errors
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            http_response_code(500);
            die(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'PHP Error: ' . $errstr . ' on line ' . $errline
            ]));
        });
        
        // Set exception handler
        set_exception_handler(function($exception) {
            http_response_code(500);
            die(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]));
        });
        
        try {
            // Check database connection
            if (!$this->model->db || !$this->model->db->ping()) {
                throw new Exception('Database connection failed');
            }
            
            $data = $this->model->getAll();
            if ($data === false) {
                throw new Exception('Database query failed: ' . $this->model->db->error);
            }
            
            if (!is_array($data)) {
                throw new Exception('Invalid data format from database');
            }
            
            // Output JSON
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage()
            ]);
        } finally {
            // Restore handlers
            restore_error_handler();
            restore_exception_handler();
        }
        exit;
    }

    public function show($params = []) {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                throw new Exception('ID jadwal tidak ditemukan');
            }

            $jadwal = $this->model->getById($id);
            if (!$jadwal) {
                throw new Exception('Jadwal tidak ditemukan');
            }

            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'data' => $jadwal
            ]);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'code' => 404,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }


    public function delete($params = []) {
        // Bersihkan semua output buffer
        while (ob_get_level()) { ob_end_clean(); }
        
        // Set header response JSON yang ketat
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
        // Set error handler untuk catch PHP errors
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            http_response_code(500);
            die(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'PHP Error: ' . $errstr
            ]));
        });

        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                throw new Exception('ID jadwal tidak ditemukan');
            }

            // Cek apakah jadwal ada
            $jadwal = $this->model->getById($id);
            if (!$jadwal) {
                throw new Exception('Jadwal tidak ditemukan');
            }

            // Delete jadwal
            $result = $this->model->delete($id);
            if ($result) {
                echo json_encode(['status' => 'success', 'code' => 200, 'message' => 'Jadwal berhasil dihapus']);
            } else {
                throw new Exception('Gagal menghapus jadwal dari database');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        } finally {
            restore_error_handler();
        }
        exit;
    }

    public function deleteMultiple($params = []) {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json; charset=utf-8');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            if (empty($ids)) throw new Exception('Tidak ada data yang dipilih');

            if ($this->model->deleteMultiple($ids)) {
                echo json_encode(['status' => 'success', 'code' => 200, 'message' => count($ids) . ' jadwal berhasil dihapus']);
            } else {
                throw new Exception('Gagal menghapus data');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function create($params = []) {
        // Bersihkan semua output buffer
        while (ob_get_level()) { ob_end_clean(); }
        
        // Set header response JSON yang ketat
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            http_response_code(500);
            die(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'PHP Error: ' . $errstr
            ]));
        });

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Validasi input - pastikan input bukan null
            if (!$input || !is_array($input)) {
                throw new Exception('Data tidak valid atau format JSON salah');
            }
            
            // Validasi required fields
            $required = ['idMatakuliah', 'idLaboratorium', 'hari', 'kelas', 'waktuMulai', 'waktuSelesai'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    throw new Exception("Field '{$field}' wajib diisi");
                }
            }

            $data = [
                'idMatakuliah' => (int)$input['idMatakuliah'],
                'kelas' => trim($input['kelas']),
                'idLaboratorium' => (int)$input['idLaboratorium'],
                'hari' => trim($input['hari']),
                'waktuMulai' => trim($input['waktuMulai']),
                'waktuSelesai' => trim($input['waktuSelesai']),
                'dosen' => trim($input['dosen'] ?? ''),
                'asisten1' => (!empty($input['idAsisten1']) && is_numeric($input['idAsisten1'])) ? (int)$input['idAsisten1'] : ((!empty($input['asisten1']) && is_numeric($input['asisten1'])) ? (int)$input['asisten1'] : null),
                'asisten2' => (!empty($input['idAsisten2']) && is_numeric($input['idAsisten2'])) ? (int)$input['idAsisten2'] : ((!empty($input['asisten2']) && is_numeric($input['asisten2'])) ? (int)$input['asisten2'] : null),
                'frekuensi' => trim($input['frekuensi'] ?? '1'),
                'status' => trim($input['status'] ?? 'Aktif')
            ];

            // Cek duplikasi
            if ($this->model->checkDuplicate($data['idMatakuliah'], $data['kelas'], $data['hari'], 
                                             $data['waktuMulai'], $data['waktuSelesai'], $data['idLaboratorium'])) {
                throw new Exception('Jadwal dengan kombinasi MK, Kelas, Hari, Jam, dan Lab sudah ada');
            }

            if ($this->model->insert($data)) {
                http_response_code(201);
                echo json_encode([
                    'status' => 'success',
                    'code' => 201,
                    'message' => 'Jadwal berhasil ditambahkan'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('Gagal menyimpan ke database: ' . $this->model->db->error);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        } finally {
            restore_error_handler();
        }
        exit;
    }

    public function update($params = []) {
        // Bersihkan semua output buffer
        while (ob_get_level()) { ob_end_clean(); }
        
        // Set header response JSON yang ketat
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            http_response_code(500);
            die(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'PHP Error: ' . $errstr
            ]));
        });

        try {
            $id = $params['id'] ?? null;
            if (!$id) throw new Exception('ID jadwal tidak ditemukan');

            $input = json_decode(file_get_contents('php://input'), true);
            
            // Validasi input - pastikan input bukan null
            if (!$input || !is_array($input)) {
                throw new Exception('Data tidak valid atau format JSON salah');
            }
            
            // Validasi required fields
            $required = ['idMatakuliah', 'idLaboratorium', 'hari', 'kelas', 'waktuMulai', 'waktuSelesai'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    throw new Exception("Field '{$field}' wajib diisi");
                }
            }

            $data = [
                'idMatakuliah' => (int)$input['idMatakuliah'],
                'kelas' => trim($input['kelas']),
                'idLaboratorium' => (int)$input['idLaboratorium'],
                'hari' => trim($input['hari']),
                'waktuMulai' => trim($input['waktuMulai']),
                'waktuSelesai' => trim($input['waktuSelesai']),
                'dosen' => trim($input['dosen'] ?? ''),
                'asisten1' => (!empty($input['idAsisten1']) && is_numeric($input['idAsisten1'])) ? (int)$input['idAsisten1'] : ((!empty($input['asisten1']) && is_numeric($input['asisten1'])) ? (int)$input['asisten1'] : null),
                'asisten2' => (!empty($input['idAsisten2']) && is_numeric($input['idAsisten2'])) ? (int)$input['idAsisten2'] : ((!empty($input['asisten2']) && is_numeric($input['asisten2'])) ? (int)$input['asisten2'] : null),
                'frekuensi' => trim($input['frekuensi'] ?? '1'),
                'status' => trim($input['status'] ?? 'Aktif')
            ];

            // Update query
            $query = "UPDATE jadwalpraktikum SET 
                      idMatakuliah = ?, kelas = ?, idLaboratorium = ?, 
                      hari = ?, waktuMulai = ?, waktuSelesai = ?, 
                      dosen = ?, asisten1 = ?, asisten2 = ?, 
                      frekuensi = ?, status = ?
                      WHERE idJadwal = ?";
            
            $stmt = $this->model->db->prepare($query);
            // i=idMK, s=kelas, i=idLab, s=hari, s=mulai, s=selesai, s=dosen, i=asisten1, i=asisten2, s=freq, s=status, i=id
            $stmt->bind_param("isissssiissi",
                $data['idMatakuliah'], $data['kelas'], $data['idLaboratorium'],
                $data['hari'], $data['waktuMulai'], $data['waktuSelesai'],
                $data['dosen'], $data['asisten1'], $data['asisten2'],
                $data['frekuensi'], $data['status'], $id
            );

            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Jadwal berhasil diperbarui'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('Gagal update database: ' . $this->model->db->error);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        } finally {
            restore_error_handler();
        }
        exit;
    }

    // Public method untuk halaman jadwal praktikum publik
    public function index() {
        // 1. Tentukan hari ini (untuk default tampilan awal)
        $hari_inggris = date('l');
        $map_hari = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $hari_ini = $map_hari[$hari_inggris] ?? 'Senin';

        // 2. Ambil SEMUA data (jangan difilter di sini)
        $data['judul'] = 'Jadwal Praktikum';
        $data['hari_ini'] = $hari_ini; // Kirim info hari ini ke view
        $data['jadwal'] = $this->model->getAll(); // Pastikan model pakai getAll()

        $this->view('praktikum/jadwal', $data);
    }

    // Admin method untuk dashboard jadwal praktikum
    public function adminIndex() {
        $data['judul'] = 'Kelola Jadwal Praktikum';
        $data['jadwal'] = $this->model->getAll();
        $this->view('admin/jadwal/index', $data);
    }

    // Form untuk upload jadwal
    public function uploadForm() {
        $data['judul'] = 'Upload Jadwal Praktikum';
        $this->view('admin/jadwal/upload', $data);
    }

    // Form untuk edit jadwal
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            header('Location: ' . PUBLIC_URL . '/admin/jadwal');
            exit;
        }
        $data['judul'] = 'Edit Jadwal Praktikum';
        $data['jadwal'] = $this->model->getById($id);
        $this->view('admin/jadwal/edit', $data);
    }

    // Store jadwal baru (form submission)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . PUBLIC_URL . '/admin/jadwal');
            exit;
        }
        
        $data = [
            'idMatakuliah' => $_POST['idMatakuliah'] ?? null,
            'kelas' => $_POST['kelas'] ?? null,
            'idLaboratorium' => $_POST['idLaboratorium'] ?? null,
            'hari' => $_POST['hari'] ?? null,
            'waktuMulai' => $_POST['waktuMulai'] ?? null,
            'waktuSelesai' => $_POST['waktuSelesai'] ?? null,
            'dosen' => $_POST['dosen'] ?? null,
            'asisten1' => $_POST['idAsisten1'] ?? $_POST['asisten1'] ?? null,
            'asisten2' => $_POST['idAsisten2'] ?? $_POST['asisten2'] ?? null,
            'frekuensi' => $_POST['frekuensi'] ?? null,
            'status' => 'Aktif'
        ];
        
        if ($this->model->insert($data)) {
            header('Location: ' . PUBLIC_URL . '/admin/jadwal');
        } else {
            header('Location: ' . PUBLIC_URL . '/admin/jadwal?error=Gagal menyimpan data');
        }
        exit;
    }

    // Process upload jadwal
    public function uploadProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'])) {
            header('Location: ' . PUBLIC_URL . '/admin/jadwal/upload');
            exit;
        }

        try {
            $file = $_FILES['file'];
            $spreadsheet = IOFactory::load($file['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();

            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getFormattedValue();
                }

                if (empty(array_filter($rowData))) continue;

                $data = [
                    'idMatakuliah' => $rowData[0] ?? null,
                    'kelas' => $rowData[1] ?? null,
                    'idLaboratorium' => $rowData[2] ?? null,
                    'hari' => $rowData[3] ?? null,
                    'waktuMulai' => $rowData[4] ?? null,
                    'waktuSelesai' => $rowData[5] ?? null,
                    'dosen' => $rowData[6] ?? null,
                    'asisten1' => $rowData[7] ?? null,
                    'asisten2' => $rowData[8] ?? null,
                    'frekuensi' => $rowData[9] ?? null,
                    'status' => 'Aktif'
                ];
                $this->model->insert($data);
            }
            
            header('Location: ' . PUBLIC_URL . '/admin/jadwal');
        } catch (Exception $e) {
            header('Location: ' . PUBLIC_URL . '/admin/jadwal/upload?error=' . urlencode($e->getMessage()));
        }
        exit;
    }
}
