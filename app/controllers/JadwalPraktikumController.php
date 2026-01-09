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
                $idLab = $this->findOrCreateMaster('laboratorium', 'nama', $labNama);

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
                        'asisten1'       => $asisten1,
                        'asisten2'       => $asisten2,
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
        
        // Buat baru jika tidak ditemukan (Menghindari gagal impor karena data master kosong)
        $db->query("INSERT INTO $table ($column) VALUES ('".addslashes($name)."')");
        return $db->insert_id;
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
}