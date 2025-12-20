<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/JadwalPraktikumModel.php';
require_once __DIR__ . '/../models/MatakuliahModel.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JadwalPraktikumController extends Controller {
    private $model;
    private $matakuliahModel;
    private $laboratoriumModel;

    public function __construct() {
        $this->model = new \JadwalPraktikumModel();
        $this->matakuliahModel = new \MatakuliahModel();
        $this->laboratoriumModel = new \LaboratoriumModel();
    }

    /**
     * Halaman publik jadwal
     */
    public function index($params = []) {
        $data = $this->model->getAll();
        $this->view('praktikum/jadwal', ['jadwal' => $data]);
    }

    /**
     * Halaman admin jadwal
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/jadwal/index', ['jadwal' => $data]);
    }

    /**
     * Form create admin
     */
    public function create($params = []) {
        $matakuliah = $this->matakuliahModel->getAll();
        $laboratorium = $this->laboratoriumModel->getAll();
        $this->view('admin/jadwal/form', [
            'jadwal' => null, 
            'action' => 'create',
            'matakuliah' => $matakuliah,
            'laboratorium' => $laboratorium
        ]);
    }

    /**
     * Form edit admin
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/jadwal');
            return;
        }
        
        $jadwal = $this->model->getById($id, 'idJadwal');
        if (!$jadwal) {
            $this->setFlash('error', 'Data jadwal tidak ditemukan');
            $this->redirect('/admin/jadwal');
            return;
        }
        
        $matakuliah = $this->matakuliahModel->getAll();
        $laboratorium = $this->laboratoriumModel->getAll();
        $this->view('admin/jadwal/form', [
            'jadwal' => $jadwal, 
            'action' => 'edit',
            'matakuliah' => $matakuliah,
            'laboratorium' => $laboratorium
        ]);
    }

    /**
     * Upload form
     */
    public function uploadForm($params = []) {
        $this->view('admin/jadwal/upload');
    }

    /**
     * CSV Upload form
     */
    public function csvUploadForm($params = []) {
        $this->view('admin/jadwal/csv-upload');
    }

    /**
     * API endpoints
     */
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Jadwal Praktikum retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID jadwal tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idJadwal');
        if (!$data) {
            $this->error('Jadwal tidak ditemukan', null, 404);
        }

        $this->success($data, 'Jadwal retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['idMatakuliah', 'idLaboratorium'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Jadwal created successfully', 201);
        }
        $this->error('Failed to create jadwal', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID jadwal tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idJadwal');
        if (!$existing) {
            $this->error('Jadwal tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idJadwal');
        
        if ($result) {
            $this->success([], 'Jadwal updated successfully');
        }
        $this->error('Failed to update jadwal', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID jadwal tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idJadwal');
        if (!$existing) {
            $this->error('Jadwal tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idJadwal');
        
        if ($result) {
            $this->success([], 'Jadwal deleted successfully');
        }
        $this->error('Failed to delete jadwal', null, 500);
    }

    public function uploadExcel() {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        try {
            // Log the start of upload process
            error_log("Upload Excel method called");
            
            // Check if file was uploaded
            if (!isset($_FILES['excel_file'])) {
                error_log("No file uploaded - _FILES not set");
                $this->error('Tidak ada file yang dikirim', null, 400);
                return;
            }

            $uploadError = $_FILES['excel_file']['error'];
            if ($uploadError !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize)',
                    UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
                    UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
                    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang dipilih',
                    UPLOAD_ERR_NO_TMP_DIR => 'Direktori temporary tidak ditemukan',
                    UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP'
                ];
                
                $errorMsg = $errorMessages[$uploadError] ?? 'Error upload tidak diketahui';
                error_log("Upload error: $uploadError - $errorMsg");
                $this->error($errorMsg, null, 400);
                return;
            }

            $uploadedFile = $_FILES['excel_file']['tmp_name'];
            $fileName = $_FILES['excel_file']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            error_log("Processing file: $fileName (extension: $fileExtension)");

            // Validate file type
            if (!in_array(strtolower($fileExtension), ['xlsx', 'xls'])) {
                error_log("Invalid file extension: $fileExtension");
                $this->error('File harus berformat Excel (.xlsx atau .xls)', null, 400);
                return;
            }

            // Validate file exists and is readable
            if (!file_exists($uploadedFile) || !is_readable($uploadedFile)) {
                error_log("File not readable: $uploadedFile");
                $this->error('File tidak dapat dibaca', null, 400);
                return;
            }

            error_log("Loading spreadsheet...");
            // Load spreadsheet
            $spreadsheet = IOFactory::load($uploadedFile);
            $worksheet = $spreadsheet->getActiveSheet();
            error_log("Spreadsheet loaded successfully");
            
            $data = [];
            $errors = [];
            $successCount = 0;
            $skipCount = 0;

            // Get all mata kuliah and laboratorium for reference
            $matakuliahs = $this->matakuliahModel->getAll();
            $laboratoriums = $this->laboratoriumModel->getAll();

            // Create mapping arrays for easier lookup
            $matakuliahMap = [];
            foreach ($matakuliahs as $mk) {
                $matakuliahMap[strtolower($mk['namaMatakuliah'])] = $mk['idMatakuliah'];
                $matakuliahMap[strtolower($mk['kodeMatakuliah'] ?? '')] = $mk['idMatakuliah'];
            }

            $laboratoriumMap = [];
            foreach ($laboratoriums as $lab) {
                $laboratoriumMap[strtolower($lab['nama'])] = $lab['idLaboratorium'];
            }

            // Process each row (skip header row)
            $rowIndex = 1;
            foreach ($worksheet->getRowIterator(2) as $row) {
                $rowIndex++;
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getFormattedValue();
                }

                // Skip empty rows
                if (empty(array_filter($rowData))) {
                    $skipCount++;
                    continue;
                }

                // Expected columns: Matakuliah, Laboratorium, Hari, Waktu Mulai, Waktu Selesai, Kelas, Status
                if (count($rowData) < 7) {
                    $errors[] = "Baris $rowIndex: Data tidak lengkap (minimal 7 kolom diperlukan)";
                    continue;
                }

                $matakuliah = trim($rowData[0]);
                $laboratorium = trim($rowData[1]);
                $hari = trim($rowData[2]);
                $waktuMulai = trim($rowData[3]);
                $waktuSelesai = trim($rowData[4]);
                $kelas = trim($rowData[5]);
                $status = trim($rowData[6]);

                // Validate and get IDs
                $idMatakuliah = $matakuliahMap[strtolower($matakuliah)] ?? null;
                $idLaboratorium = $laboratoriumMap[strtolower($laboratorium)] ?? null;

                if (!$idMatakuliah) {
                    $errors[] = "Baris $rowIndex: Mata kuliah '$matakuliah' tidak ditemukan";
                    continue;
                }

                if (!$idLaboratorium) {
                    $errors[] = "Baris $rowIndex: Laboratorium '$laboratorium' tidak ditemukan";
                    continue;
                }

                // Validate time format
                if (!$this->isValidTime($waktuMulai)) {
                    $errors[] = "Baris $rowIndex: Format waktu mulai tidak valid '$waktuMulai' (gunakan format HH:MM)";
                    continue;
                }

                if (!$this->isValidTime($waktuSelesai)) {
                    $errors[] = "Baris $rowIndex: Format waktu selesai tidak valid '$waktuSelesai' (gunakan format HH:MM)";
                    continue;
                }

                // Validate day
                $validDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                if (!in_array(strtolower($hari), $validDays)) {
                    $errors[] = "Baris $rowIndex: Hari tidak valid '$hari'";
                    continue;
                }

                // Prepare data for insertion
                $jadwalData = [
                    'idMatakuliah' => $idMatakuliah,
                    'idLaboratorium' => $idLaboratorium,
                    'hari' => ucfirst(strtolower($hari)),
                    'waktuMulai' => $waktuMulai,
                    'waktuSelesai' => $waktuSelesai,
                    'kelas' => $kelas,
                    'status' => $status ?: 'Aktif'
                ];

                // Check for duplicate
                if ($this->isDuplicateSchedule($jadwalData)) {
                    $errors[] = "Baris $rowIndex: Jadwal sudah ada untuk {$matakuliah} di {$laboratorium} pada {$hari} {$waktuMulai}-{$waktuSelesai}";
                    continue;
                }

                // Insert data
                $result = $this->model->insert($jadwalData);
                if ($result) {
                    $successCount++;
                } else {
                    $errors[] = "Baris $rowIndex: Gagal menyimpan data";
                }
            }

            // Prepare response
            $response = [
                'total_processed' => $rowIndex - 1,
                'success_count' => $successCount,
                'skip_count' => $skipCount,
                'error_count' => count($errors),
                'errors' => $errors
            ];

            if ($successCount > 0) {
                $this->success($response, "Berhasil mengimpor $successCount jadwal praktikum");
            } else {
                $this->error('Tidak ada data yang berhasil diimpor', $response, 400);
            }

        } catch (Exception $e) {
            error_log("Exception in uploadExcel: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $this->error('Error memproses file Excel: ' . $e->getMessage(), null, 500);
        }
    }

    private function isValidTime($time) {
        return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time);
    }

    private function isDuplicateSchedule($data) {
        $query = "SELECT COUNT(*) as count FROM jadwalpraktikum 
                  WHERE idMatakuliah = ? AND idLaboratorium = ? AND hari = ? 
                  AND waktuMulai = ? AND waktuSelesai = ? AND kelas = ?";
        
        $result = $this->model->db->prepare($query);
        $result->bind_param("iissss", 
            $data['idMatakuliah'], 
            $data['idLaboratorium'], 
            $data['hari'], 
            $data['waktuMulai'], 
            $data['waktuSelesai'], 
            $data['kelas']
        );
        $result->execute();
        $count = $result->get_result()->fetch_assoc()['count'];
        
        return $count > 0;
    }

    public function downloadTemplate() {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                'A1' => 'Mata Kuliah',
                'B1' => 'Laboratorium', 
                'C1' => 'Hari',
                'D1' => 'Waktu Mulai',
                'E1' => 'Waktu Selesai',
                'F1' => 'Kelas',
                'G1' => 'Status'
            ];

            foreach ($headers as $cell => $header) {
                $sheet->setCellValue($cell, $header);
            }

            // Add sample data with REAL database values
            $sheet->setCellValue('A2', 'Microcontroller');
            $sheet->setCellValue('B2', 'Microcontroller');
            $sheet->setCellValue('C2', 'Senin');
            $sheet->setCellValue('D2', '08:00');
            $sheet->setCellValue('E2', '10:00');
            $sheet->setCellValue('F2', 'A');
            $sheet->setCellValue('G2', 'Aktif');

            $sheet->setCellValue('A3', 'Struktur Data');
            $sheet->setCellValue('B3', 'Data Science');
            $sheet->setCellValue('C3', 'Selasa');
            $sheet->setCellValue('D3', '10:00');
            $sheet->setCellValue('E3', '12:00');
            $sheet->setCellValue('F3', 'B');
            $sheet->setCellValue('G3', 'Aktif');

            // Style headers
            $headerRange = 'A1:G1';
            $sheet->getStyle($headerRange)->getFont()->setBold(true);
            $sheet->getStyle($headerRange)->getFill()
                  ->setFillType(Fill::FILL_SOLID)
                  ->getStartColor()->setARGB('FFCCCCCC');

            // Auto-size columns
            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Add instructions in separate sheet with DATABASE VALUES
            $instructionSheet = $spreadsheet->createSheet();
            $instructionSheet->setTitle('Petunjuk');
            
            $instructions = [
                'A1' => 'PETUNJUK PENGGUNAAN TEMPLATE JADWAL PRAKTIKUM',
                'A3' => '1. Mata Kuliah: Nama mata kuliah yang sudah terdaftar di sistem',
                'A4' => '2. Laboratorium: Nama laboratorium yang sudah terdaftar di sistem', 
                'A5' => '3. Hari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, atau Minggu',
                'A6' => '4. Waktu Mulai: Format HH:MM (contoh: 08:00)',
                'A7' => '5. Waktu Selesai: Format HH:MM (contoh: 10:00)',
                'A8' => '6. Kelas: Kelas praktikum (contoh: A, B, C)',
                'A9' => '7. Status: Aktif atau Nonaktif',
                'A11' => 'DAFTAR MATA KULIAH YANG TERDAFTAR:',
                'A13' => 'DAFTAR LABORATORIUM YANG TERDAFTAR:',
            ];

            foreach ($instructions as $cell => $instruction) {
                $instructionSheet->setCellValue($cell, $instruction);
            }

            // Add list of available mata kuliah
            $matakuliahs = $this->matakuliahModel->getAll();
            $row = 12;
            foreach ($matakuliahs as $mk) {
                $instructionSheet->setCellValue('A' . $row, '- ' . $mk['namaMatakuliah']);
                $row++;
            }

            // Add list of available laboratorium
            $row = 14;
            $laboratoriums = $this->laboratoriumModel->getAll();
            foreach ($laboratoriums as $lab) {
                $instructionSheet->setCellValue('A' . $row, '- ' . $lab['nama']);
                $row++;
            }

            $row += 2;
            $instructionSheet->setCellValue('A' . $row, 'CATATAN:');
            $row++;
            $instructionSheet->setCellValue('A' . $row, '- Pastikan nama mata kuliah dan laboratorium PERSIS seperti daftar di atas');
            $row++;
            $instructionSheet->setCellValue('A' . $row, '- Jangan mengubah nama kolom di baris pertama');
            $row++;
            $instructionSheet->setCellValue('A' . $row, '- Hapus baris contoh (baris 2 dan 3) sebelum mengupload');
            $row++;
            $instructionSheet->setCellValue('A' . $row, '- File harus berformat .xlsx atau .xls');

            $instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $instructionSheet->getStyle('A11')->getFont()->setBold(true);
            $instructionSheet->getStyle('A13')->getFont()->setBold(true);
            $instructionSheet->getStyle('A' . ($row - 4))->getFont()->setBold(true);
            
            foreach (range('A', 'A') as $col) {
                $instructionSheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Set active sheet back to first sheet
            $spreadsheet->setActiveSheetIndex(0);

            // Output file
            $writer = new Xlsx($spreadsheet);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="template_jadwal_praktikum.xlsx"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (Exception $e) {
            $this->error('Error membuat template: ' . $e->getMessage(), null, 500);
        }
    }
}
?>
