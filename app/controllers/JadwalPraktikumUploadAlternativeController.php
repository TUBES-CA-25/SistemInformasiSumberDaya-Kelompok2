<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/JadwalPraktikumModel.php';
require_once __DIR__ . '/../models/MatakuliahModel.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';

use Exception;

/**
 * Controller alternatif untuk upload tanpa PhpSpreadsheet (untuk mengatasi masalah ZIP)
 */
class JadwalPraktikumUploadAlternativeController extends Controller {
    private $model;
    private $matakuliahModel;
    private $laboratoriumModel;

    public function __construct() {
        $this->model = new \JadwalPraktikumModel();
        $this->matakuliahModel = new \MatakuliahModel();
        $this->laboratoriumModel = new \LaboratoriumModel();
    }

    public function uploadCSV() {
        try {
            // Check if file was uploaded
            if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
                $this->error('File tidak valid atau tidak ditemukan', null, 400);
                return;
            }

            $uploadedFile = $_FILES['csv_file']['tmp_name'];
            $fileName = $_FILES['csv_file']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Validate file type
            if (strtolower($fileExtension) !== 'csv') {
                $this->error('File harus berformat CSV (.csv)', null, 400);
                return;
            }

            // Read CSV file
            $csvData = [];
            if (($handle = fopen($uploadedFile, 'r')) !== FALSE) {
                $header = fgetcsv($handle, 1000, ','); // Read header
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if (count($data) >= 7) { // Ensure we have all required columns
                        $csvData[] = array_combine($header, $data);
                    }
                }
                fclose($handle);
            }

            if (empty($csvData)) {
                $this->error('File CSV kosong atau format tidak valid', null, 400);
                return;
            }

            // Process data
            $successCount = 0;
            $errors = [];
            
            // Get reference data
            $matakuliahs = $this->matakuliahModel->getAll();
            $laboratoriums = $this->laboratoriumModel->getAll();

            $matakuliahMap = [];
            foreach ($matakuliahs as $mk) {
                $matakuliahMap[strtolower($mk['namaMatakuliah'])] = $mk['idMatakuliah'];
                // Juga map berdasarkan kode mata kuliah jika ada
                if (isset($mk['kodeMatakuliah']) && !empty($mk['kodeMatakuliah'])) {
                    $matakuliahMap[strtolower($mk['kodeMatakuliah'])] = $mk['idMatakuliah'];
                }
            }

            $laboratoriumMap = [];
            foreach ($laboratoriums as $lab) {
                $laboratoriumMap[strtolower($lab['nama'])] = $lab['idLaboratorium'];
            }

            foreach ($csvData as $index => $row) {
                $rowNum = $index + 2; // Account for header row
                
                $matakuliah = trim($row['Mata Kuliah'] ?? '');
                $laboratorium = trim($row['Laboratorium'] ?? '');
                $hari = trim($row['Hari'] ?? '');
                $waktuMulai = trim($row['Waktu Mulai'] ?? '');
                $waktuSelesai = trim($row['Waktu Selesai'] ?? '');
                $kelas = trim($row['Kelas'] ?? '');
                $status = trim($row['Status'] ?? 'Aktif');

                // Validate required fields
                if (empty($matakuliah) || empty($laboratorium) || empty($hari) || empty($waktuMulai) || empty($waktuSelesai)) {
                    $errors[] = "Baris $rowNum: Data tidak lengkap";
                    continue;
                }

                // Get IDs
                $idMatakuliah = $matakuliahMap[strtolower($matakuliah)] ?? null;
                $idLaboratorium = $laboratoriumMap[strtolower($laboratorium)] ?? null;

                if (!$idMatakuliah) {
                    // Coba cari dengan partial match
                    foreach ($matakuliahMap as $key => $id) {
                        if (strpos(strtolower($key), strtolower($matakuliah)) !== false || 
                            strpos(strtolower($matakuliah), strtolower($key)) !== false) {
                            $idMatakuliah = $id;
                            break;
                        }
                    }
                }

                if (!$idMatakuliah) {
                    $availableMK = implode(', ', array_keys($matakuliahMap));
                    $errors[] = "Baris $rowNum: Mata kuliah '$matakuliah' tidak ditemukan. Tersedia: $availableMK";
                    continue;
                }

                if (!$idLaboratorium) {
                    $errors[] = "Baris $rowNum: Laboratorium '$laboratorium' tidak ditemukan";
                    continue;
                }

                // Validate time format
                if (!$this->isValidTime($waktuMulai)) {
                    $errors[] = "Baris $rowNum: Format waktu mulai tidak valid '$waktuMulai'";
                    continue;
                }

                if (!$this->isValidTime($waktuSelesai)) {
                    $errors[] = "Baris $rowNum: Format waktu selesai tidak valid '$waktuSelesai'";
                    continue;
                }

                // Prepare data
                $jadwalData = [
                    'idMatakuliah' => $idMatakuliah,
                    'idLaboratorium' => $idLaboratorium,
                    'hari' => ucfirst(strtolower($hari)),
                    'waktuMulai' => $waktuMulai,
                    'waktuSelesai' => $waktuSelesai,
                    'kelas' => $kelas,
                    'status' => $status
                ];

                // Insert data
                $result = $this->model->insert($jadwalData);
                if ($result) {
                    $successCount++;
                } else {
                    $errors[] = "Baris $rowNum: Gagal menyimpan data";
                }
            }

            // Response
            $response = [
                'total_processed' => count($csvData),
                'success_count' => $successCount,
                'error_count' => count($errors),
                'errors' => $errors
            ];

            if ($successCount > 0) {
                $this->success($response, "Berhasil mengimpor $successCount jadwal praktikum");
            } else {
                $this->error('Tidak ada data yang berhasil diimpor', $response, 400);
            }

        } catch (Exception $e) {
            $this->error('Error memproses file CSV: ' . $e->getMessage(), null, 500);
        }
    }

    public function downloadCSVTemplate() {
        $filename = 'template_jadwal_praktikum.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV
        $headers = ['Mata Kuliah', 'Laboratorium', 'Hari', 'Waktu Mulai', 'Waktu Selesai', 'Kelas', 'Status'];
        fputcsv($output, $headers);
        
        // Sample data dengan mata kuliah yang sesuai database
        $samples = [
            ['Pemrograman Berorientasi Objek', 'Lab Komputer 1', 'Senin', '08:00', '10:00', 'A', 'Aktif'],
            ['Basis Data II', 'Lab Komputer 2', 'Selasa', '10:00', '12:00', 'B', 'Aktif'],
            ['Algoritma Pemrograman', 'Lab Komputer 1', 'Rabu', '13:00', '15:00', 'A', 'Aktif'],
            ['Jaringan Komputer', 'Lab Komputer 3', 'Kamis', '08:00', '10:00', 'C', 'Aktif'],
            ['Pemrograman Mobile', 'Lab Komputer 2', 'Jumat', '10:00', '12:00', 'B', 'Aktif']
        ];
        
        foreach ($samples as $sample) {
            fputcsv($output, $sample);
        }
        
        fclose($output);
        exit;
    }

    private function isValidTime($time) {
        return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time);
    }
}
?>