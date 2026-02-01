<?php

/**
 * JadwalPraktikumController - Kelola Jadwal Praktikum Laboratorium
 * 
 * Menangani:
 * - Tampilan publik jadwal praktikum
 * - Dashboard admin dengan CRUD jadwal
 * - Import jadwal dari file Excel (Jadwal Lab.xlsx)
 * - Upload CSV untuk bulk insert
 * - API endpoints untuk get, store, update, delete jadwal
 * - Mapping otomatis master data (mata kuliah, laboratorium, asisten)
 * - Deteksi duplikasi jadwal sebelum insert
 * - Validasi lab dan asisten existence
 * 
 * Database Table: jadwalpraktikum
 * - Primary Key: id
 * - Key Fields: idMatakuliah, idLaboratorium, kelas, hari, waktuMulai, waktuSelesai
 * - Related: asisten1, asisten2 (dari tabel asisten)
 * 
 * Excel Import Format (Jadwal Lab.xlsx):
 * - Column C: Dosen nama
 * - Column D: Mata kuliah nama
 * - Column F: Kelas
 * - Column G: Frekuensi
 * - Column H: Ruangan/Lab nama
 * - Column I: Hari
 * - Column J: Jam (format: 07:00 - 09:30)
 * - Column L: Asisten 1
 * - Column M: Asisten 2
 * 
 * Models:
 * - JadwalPraktikumModel: Database operations jadwal praktikum
 * 
 * Dependencies:
 * - PhpOffice\PhpSpreadsheet: Library Excel parsing
 */

// Proteksi awal: Bersihkan semua output buffer sebelum mulai
while (ob_get_level()) { ob_end_clean(); }

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/JadwalPraktikumModel.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class JadwalPraktikumController extends Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var JadwalPraktikumModel Model untuk jadwal praktikum */
    private $model;

    
    // =========================================================================
    // BAGIAN 2: KONSTRUKTOR
    // =========================================================================
    
    /**
     * Inisialisasi JadwalPraktikumController dengan model
     */
    public function __construct() {
        $this->model = new JadwalPraktikumModel();
    }

    
    // =========================================================================
    // BAGIAN 3: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Index - Tampilkan halaman publik jadwal praktikum
     * 
     * Menampilkan jadwal praktikum untuk mahasiswa/publik.
     * 
     * @return void Menampilkan view jadwal publik
     */
    public function index() {
        $data = $this->model->getAll();
        $this->view('praktikum/jadwal', ['jadwal' => $data]);
    }

    
    // =========================================================================
    // BAGIAN 4: RUTE ADMIN - HALAMAN DAN FORM
    // =========================================================================
    
    /**
     * Admin Index - Dashboard admin jadwal praktikum
     * 
     * Menampilkan data table semua jadwal untuk admin.
     * 
     * @param array $params Parameter dari router (tidak digunakan)
     * @return void Menampilkan view admin index
     */
    public function adminIndex($params = []) {
        $this->view('admin/jadwal/index');
    }

    /**
     * Create - Form create jadwal baru (admin)
     * 
     * Menampilkan form kosong untuk input jadwal baru.
     * 
     * @param array $params Parameter dari router (tidak digunakan)
     * @return void Menampilkan view form create
     */
    public function create($params = []) {
        $this->view('admin/jadwal/form', ['jadwal' => null, 'action' => 'create']);
    }

    /**
     * Edit - Form edit jadwal (admin)
     * 
     * Menampilkan form dengan data existing untuk edit.
     * 
     * @param array $params Parameter dengan key 'id'
     * @return void Menampilkan view form edit atau redirect
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/jadwal');
            return;
        }
        
        $jadwal = $this->model->getById($id);
        if (!$jadwal) {
            $this->setFlash('error', 'Data jadwal tidak ditemukan');
            $this->redirect('/admin/jadwal');
            return;
        }
        
        $this->view('admin/jadwal/form', ['jadwal' => $jadwal, 'action' => 'edit']);
    }

    /**
     * Upload Form - Tampilkan form upload Excel
     * 
     * Menampilkan form untuk upload file Excel jadwal.
     * 
     * @param array $params Parameter dari router (tidak digunakan)
     * @return void Menampilkan view upload form
     */
    public function uploadForm($params = []) {
        $this->view('admin/jadwal/upload');
    }

    
    // =========================================================================
    // BAGIAN 5: RUTE CRUD - STORE, UPDATE, DELETE
    // =========================================================================
    
    /**
     * Store - Create jadwal baru
     * 
     * Insert jadwal baru ke database dari form create.
     * 
     * Flow:
     * 1. Validasi input required fields
     * 2. Insert ke database
     * 3. Redirect dengan success message
     * 
     * @return void Redirect ke admin jadwal list
     */
    public function store() {
        try {
            $input = $_POST;
            
            // Validasi required fields
            $required = ['idMatakuliah', 'idLaboratorium', 'kelas', 'hari', 'waktuMulai', 'waktuSelesai'];
            $missing = [];
            
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    $missing[] = $field;
                }
            }
            
            if (!empty($missing)) {
                $this->setFlash('error', 'Field required: ' . implode(', ', $missing));
                $this->redirect('/admin/jadwal/create');
                return;
            }
            
            // Set default status
            $input['status'] = 'Aktif';
            
            // Insert ke database
            $result = $this->model->insert($input);
            
            if ($result) {
                $this->setFlash('success', 'Jadwal berhasil dibuat');
                $this->redirect('/admin/jadwal');
                return;
            }
            
            $this->setFlash('error', 'Gagal membuat jadwal');
            $this->redirect('/admin/jadwal/create');
        } catch (Exception $e) {
            $this->setFlash('error', 'Error: ' . $e->getMessage());
            $this->redirect('/admin/jadwal/create');
        }
    }

    /**
     * Update - Update jadwal existing
     * 
     * Update jadwal dari form edit.
     * 
     * Flow:
     * 1. Validasi ID ada
     * 2. Validasi jadwal exists
     * 3. Update database
     * 4. Redirect dengan success message
     * 
     * @param array $params Parameter dengan key 'id'
     * @return void Redirect ke admin jadwal list
     */
    public function update($params = []) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->setFlash('error', 'ID jadwal tidak ditemukan');
                $this->redirect('/admin/jadwal');
                return;
            }
            
            $existing = $this->model->getById($id);
            if (!$existing) {
                $this->setFlash('error', 'Data jadwal tidak ditemukan');
                $this->redirect('/admin/jadwal');
                return;
            }
            
            $input = $_POST;
            
            // Validasi required fields
            $required = ['idMatakuliah', 'idLaboratorium', 'kelas', 'hari', 'waktuMulai', 'waktuSelesai'];
            $missing = [];
            
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    $missing[] = $field;
                }
            }
            
            if (!empty($missing)) {
                $this->setFlash('error', 'Field required: ' . implode(', ', $missing));
                $this->redirect('/admin/jadwal/' . $id . '/edit');
                return;
            }
            
            // Update database
            $result = $this->model->update($id, $input);
            
            if ($result) {
                $this->setFlash('success', 'Jadwal berhasil diupdate');
                $this->redirect('/admin/jadwal');
                return;
            }
            
            $this->setFlash('error', 'Gagal mengupdate jadwal');
            $this->redirect('/admin/jadwal/' . $id . '/edit');
        } catch (Exception $e) {
            $this->setFlash('error', 'Error: ' . $e->getMessage());
            $this->redirect('/admin/jadwal');
        }
    }

    /**
     * Delete - Hapus jadwal
     * 
     * Hapus satu jadwal dari database.
     * 
     * Flow:
     * 1. Validasi ID ada
     * 2. Validasi jadwal exists
     * 3. Delete dari database
     * 4. Redirect dengan success message
     * 
     * @param array $params Parameter dengan key 'id'
     * @return void Redirect ke admin jadwal list
     */
    public function delete($params = []) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->setFlash('error', 'ID jadwal tidak ditemukan');
                $this->redirect('/admin/jadwal');
                return;
            }
            
            $existing = $this->model->getById($id);
            if (!$existing) {
                $this->setFlash('error', 'Data jadwal tidak ditemukan');
                $this->redirect('/admin/jadwal');
                return;
            }
            
            // Delete dari database
            $result = $this->model->delete($id);
            
            if ($result) {
                $this->setFlash('success', 'Jadwal berhasil dihapus');
                $this->redirect('/admin/jadwal');
                return;
            }
            
            $this->setFlash('error', 'Gagal menghapus jadwal');
            $this->redirect('/admin/jadwal');
        } catch (Exception $e) {
            $this->setFlash('error', 'Error: ' . $e->getMessage());
            $this->redirect('/admin/jadwal');
        }
    }

    /**
     * Delete Multiple - Hapus banyak jadwal sekaligus
     * 
     * Hapus multiple jadwal dari checkbox selection.
     * 
     * @param array $params Parameter dari router (tidak digunakan)
     * @return void JSON response
     */
    public function deleteMultiple($params = []) {
        try {
            $ids = $_POST['ids'] ?? [];
            
            if (empty($ids)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tidak ada jadwal yang dipilih'
                ]);
                return;
            }
            
            foreach ($ids as $id) {
                if (!empty($id)) {
                    $this->model->delete($id);
                }
            }
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Jadwal berhasil dihapus'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    
    // =========================================================================
    // BAGIAN 6: EXCEL UPLOAD
    // =========================================================================
    
    /**
     * Upload Excel - Upload dan import jadwal dari Excel file
     * 
     * Import jadwal dari file Excel (Jadwal Lab.xlsx) dengan smart mapping.
     * 
     * Process Flow:
     * 1. Validasi file upload ada
     * 2. Load Excel file menggunakan PhpSpreadsheet
     * 3. Parse setiap baris (skip header)
     * 4. Extract data: dosen, mata kuliah, kelas, lab, hari, jam, asisten
     * 5. Find atau create master data (mata kuliah, lab)
     * 6. Find asisten ID by smart name search
     * 7. Cek duplikasi sebelum insert
     * 8. Insert jadwal ke database
     * 9. Return summary (success, duplicate, invalid_lab)
     * 
     * Response:
     * {
     *   "status": "success",
     *   "code": 201,
     *   "message": "Berhasil mengimpor X data. (Y data duplikat dilewati)..."
     * }
     * 
     * @return void Output JSON response (di-set di method)
     */
    public function uploadExcel() {
        // LANGKAH 1: Bersihkan semua output buffer
        while (ob_get_level()) { ob_end_clean(); }
        
        // LANGKAH 2: Set header response JSON yang ketat
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
        // LANGKAH 3: Set error handler untuk catch PHP errors
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            http_response_code(500);
            die(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'PHP Error: ' . $errstr
            ]));
        });

        try {
            // LANGKAH 4: Validasi file upload
            if (!isset($_FILES['excel_file'])) {
                throw new Exception("File tidak ditemukan.");
            }

            // LANGKAH 5: Load Excel file
            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Counter untuk statistik
            $success = 0;
            $duplicate = 0;
            $invalidLab = 0;

            // LANGKAH 6: Loop setiap baris (start dari baris 2, skip header)
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) { 
                    $rowData[] = $cell->getFormattedValue(); 
                }

                // Skip empty rows
                if (empty(array_filter($rowData))) continue;

                // LANGKAH 7: Extract data dari kolom sesuai Excel format
                // Format: A=No, B=Tgl, C=Dosen, D=MK, E=SKS, F=Kelas, G=Frek, H=Lab, I=Hari, J=Jam, K=Ruang, L=Asisten1, M=Asisten2
                $dosenNama = trim($rowData[2] ?? '');  // Kolom C (Dosen)
                $mkNama    = trim($rowData[3] ?? '');  // Kolom D (Mata Kuliah)
                $kelas     = trim($rowData[5] ?? '');  // Kolom F (Kelas)
                $freq      = trim($rowData[6] ?? '');  // Kolom G (Frekuensi)
                $labNama   = trim($rowData[7] ?? '');  // Kolom H (Ruangan)
                $hari      = trim($rowData[8] ?? '');  // Kolom I (Hari)
                $jamFull   = str_replace('.', ':', trim($rowData[9] ?? '')); // Kolom J (Jam)
                $asisten1  = trim($rowData[11] ?? ''); // Kolom L (Asisten 1)
                $asisten2  = trim($rowData[12] ?? ''); // Kolom M (Asisten 2)

                // LANGKAH 8: Parse jam (Split format: "07:00 - 09:30")
                $parts = explode('-', $jamFull);
                $start = trim($parts[0] ?? '00:00');
                $end   = isset($parts[1]) ? trim($parts[1]) : $start;

                // LANGKAH 9: Find atau create master data (mata kuliah)
                // Strategy: Jika tidak ada, create otomatis untuk anti-import blank
                $idMK = $this->findOrCreateMaster('matakuliah', 'namaMatakuliah', $mkNama);
                
                // LANGKAH 10: Find existing master data (laboratorium) - JANGAN CREATE
                // Strategy: Hanya find existing, jangan create baru
                $idLab = $this->findExistingMaster('laboratorium', 'nama', $labNama);

                if (!$idLab) {
                    // Skip jika lab tidak ada di database
                    $invalidLab++;
                    continue;
                }

                // LANGKAH 11: Find asisten ID by smart name search
                $idAsisten1 = $this->findAsistenIdByName($asisten1);
                $idAsisten2 = $this->findAsistenIdByName($asisten2);

                if ($idMK && $idLab) {
                    // LANGKAH 12: CEK DUPLIKASI sebelum insert
                    if ($this->model->checkDuplicate($idMK, $kelas, ucfirst(strtolower($hari)), $start, $end, $idLab)) {
                        // Data sudah ada, skip
                        $duplicate++;
                        continue;
                    }

                    // LANGKAH 13: INSERT jadwal ke database
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
            
            // LANGKAH 14: Build response message dengan summary
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

    /**
     * Upload Process - Legacy upload form processor
     * 
     * Deprecated: Gunakan uploadExcel() untuk import.
     * Method ini digunakan jika ada form HTML upload manual.
     * 
     * @return void Redirect dengan flash message
     */
    public function uploadProcess() {
        // Legacy method - dapat dihapus jika tidak digunakan
        $this->redirect('/admin/jadwal');
    }

    /**
     * API Index - Get semua jadwal dalam format JSON
     * 
     * Endpoint publik untuk retrieve seluruh data jadwal.
     * 
     * Flow:
     * 1. Bersihkan output buffer
     * 2. Set JSON header
     * 3. Get data dari model
     * 4. Return JSON dengan 200 status
     * 
     * Response Success (200):
     * {
     *   "status": "success",
     *   "code": 200,
     *   "data": [
     *     {"id": 1, "idMatakuliah": 1, "idLaboratorium": 1, ...},
     *     ...
     *   ]
     * }
     * 
     * @return void Output JSON response
     */
    public function apiIndex() {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json');
        
        try {
            $data = $this->model->getAll();
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Show - Get detail jadwal (API)
     * 
     * Flow:
     * 1. Ambil ID dari parameter URL
     * 2. Validasi ID ada
     * 3. Get jadwal dari database
     * 4. Return JSON response
     * 
     * @param array $params Parameter dengan key 'id'
     * @return void Output JSON response
     */
    public function show($params = []) {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json');
        
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'ID jadwal tidak valid'
                ]);
                exit;
            }
            
            $jadwal = $this->model->getById($id);
            if (!$jadwal) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Data jadwal tidak ditemukan'
                ]);
                exit;
            }
            
            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'data' => $jadwal
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    
    // =========================================================================
    // BAGIAN 7: PRIVATE HELPER METHODS
    // =========================================================================
    
    /**
     * Find Or Create Master - Cari atau buat data master
     * 
     * Strategi: Cari data dengan exact match (case insensitive).
     * Jika tidak ditemukan, create otomatis untuk mencegah blank value di import.
     * 
     * Use Case: Saat import Excel, jika mata kuliah tidak ada di database,
     * method ini membuat entry baru secara otomatis.
     * 
     * @param string $table Nama table (matakuliah, laboratorium, dll)
     * @param string $column Kolom pencarian (namaMatakuliah, nama, dll)
     * @param string $name Nilai yang dicari/dibuat
     * @return int|null ID dari record
     */
    private function findOrCreateMaster($table, $column, $name) {
        if (empty($name)) return null;
        
        $db = $this->model->db;
        $stmt = $db->prepare("SELECT * FROM $table WHERE LCASE(TRIM($column)) = LCASE(TRIM(?)) LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        if ($res) return $res[array_key_first($res)];
        
        $db->query("INSERT INTO $table ($column) VALUES ('".addslashes($name)."')");
        return $db->insert_id;
    }

    /**
     * Find Existing Master - Cari data master tanpa create
     * 
     * Hanya cari existing, TIDAK buat baru.
     * 
     * @param string $table Nama table
     * @param string $column Kolom pencarian
     * @param string $name Nilai yang dicari
     * @return int|null ID jika ditemukan, null jika tidak
     */
    private function findExistingMaster($table, $column, $name) {
        if (empty($name)) return null;
        
        $db = $this->model->db;
        $stmt = $db->prepare("SELECT * FROM $table WHERE LCASE(TRIM($column)) = LCASE(TRIM(?)) LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        return $res ? $res[array_key_first($res)] : null;
    }
    
    /**
     * Find Asisten ID By Name - Smart search nama asisten
     * 
     * 3-Level Search Algorithm:
     * 1. Exact Match: "Berlian Septiani" = "Berlian Septiani"
     * 2. Partial Match: "Berlian Septiani" LIKE "%Berlian Septiani%"
     * 3. First Word + Similarity: Extract first word, LIKE "Berlian%", verify similarity
     * 
     * @param string $name Nama asisten dari Excel
     * @return int|string|null ID asisten atau null jika tidak ditemukan
     */
    private function findAsistenIdByName($name) {
        if (empty($name) || is_numeric($name)) return $name; 
        
        $db = $this->model->db;
        $cleanName = trim($name);

        // LEVEL 1: EXACT MATCH
        $stmt = $db->prepare("SELECT idAsisten FROM asisten WHERE LCASE(TRIM(nama)) = LCASE(?) LIMIT 1");
        $stmt->bind_param("s", $cleanName);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res) return $res['idAsisten'];
        
        // LEVEL 2: PARTIAL MATCH
        $likeName = "%" . $cleanName . "%";
        $stmt = $db->prepare("SELECT idAsisten FROM asisten WHERE nama LIKE ? LIMIT 1");
        $stmt->bind_param("s", $likeName);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res) return $res['idAsisten'];

        // LEVEL 3: FIRST WORD + SIMILARITY
        $parts = explode(' ', $cleanName);
        if (count($parts) >= 1) {
            $firstName = $parts[0];
            if (strlen($firstName) > 3) {
                $likeFirst = $firstName . "%";
                $stmt = $db->prepare("SELECT idAsisten, nama FROM asisten WHERE nama LIKE ? LIMIT 5");
                $stmt->bind_param("s", $likeFirst);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    if (stripos($cleanName, $row['nama']) !== false || 
                        stripos($row['nama'], $cleanName) !== false) {
                        return $row['idAsisten'];
                    }
                }
            }
        }
        
        error_log("Gagal menemukan ID Asisten untuk nama: " . $name);
        return null;
    }
}
