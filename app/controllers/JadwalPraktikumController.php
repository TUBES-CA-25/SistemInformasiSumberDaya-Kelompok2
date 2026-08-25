<?php

// Pastikan Model dan Service dimuat dengan path absolut
require_once ROOT_PROJECT . '/app/models/JadwalPraktikumModel.php';
require_once ROOT_PROJECT . '/app/services/JadwalPraktikumService.php';

/**
 * Karena index.php sudah memuat Controller.php secara global, 
 * kita tidak perlu require_once CORE_PATH . '/Controller.php' lagi di sini.
 * Ini mencegah error "Failed to open stream" jika path CORE_PATH berubah.
 */

use PhpOffice\PhpSpreadsheet\IOFactory;

class JadwalPraktikumController extends Controller {
    private $model;
    private $service;

    public function __construct() {
        // Pastikan kelas induk sudah terdeteksi
        $this->model = new JadwalPraktikumModel();
        $this->service = new JadwalPraktikumService();
    }

    /**
     * Tampilan Publik: Jadwal Praktikum.
     */
    public function index(): void {
        $this->view('praktikum/jadwal', ['jadwal' => $this->model->getAll()]);
    }

    /**
     * API: Get semua jadwal dalam JSON.
     */
    public function apiIndex(): void {
        $this->success($this->model->getAll(), 'Data jadwal berhasil diambil');
    }

    /**
     * API: Get detail jadwal berdasarkan ID.
     */
    public function apiShow($params = []): void {
        $this->cleanBuffers();
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak valid', null, 400);
            return;
        }

        $jadwal = $this->model->getById($id);
        if (!$jadwal) {
            $this->error('Jadwal tidak ditemukan', null, 404);
            return;
        }

        $this->success($jadwal, 'Detail jadwal berhasil diambil');
    }

    /**
     * Admin: List Dashboard Jadwal.
     */
    public function adminIndex(): void {
        $this->view('admin/jadwal/index');
    }

    /**
     * Admin: Form Create Jadwal.
     */
    public function create($params = []): void {
        $this->view('admin/jadwal/form', [
            'action' => 'create',
            'jadwal' => null
        ]);
    }

    /**
     * Admin: Form Edit Jadwal.
     */
    public function edit($params = []): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->setFlash('error', 'ID tidak ditemukan');
            $this->redirect('/admin/jadwal');
            return;
        }

        $jadwal = $this->model->getById($id);
        if (!$jadwal) {
            $this->setFlash('error', 'Jadwal tidak ditemukan');
            $this->redirect('/admin/jadwal');
            return;
        }

        $this->view('admin/jadwal/form', [
            'action' => 'edit',
            'jadwal' => $jadwal
        ]);
    }

    /**
     * Admin: Store/Create Jadwal baru.
     */
    public function store(): void {
        $this->cleanBuffers();
        $input = $this->getJson() ?? $_POST;
        
        // Pemetaan field dari form ke kolom database
        $data = [
            'idMatakuliah'   => $input['idMatakuliah'] ?? null,
            'idLaboratorium' => $input['idLaboratorium'] ?? null,
            'hari'           => $input['hari'] ?? null,
            'kelas'          => strtoupper($input['kelas'] ?? ''),
            'waktuMulai'     => $input['waktuMulai'] ?? null,
            'waktuSelesai'   => $input['waktuSelesai'] ?? null,
            'frekuensi'      => $input['frekuensi'] ?? null,
            'idDosen'        => !empty($input['idDosen']) ? $input['idDosen'] : null,
            'asisten1'       => !empty($input['idAsisten1']) ? $input['idAsisten1'] : null,
            'asisten2'       => !empty($input['idAsisten2']) ? $input['idAsisten2'] : null,
            'status'         => $input['status'] ?? 'Aktif'
        ];
        
        // Validasi minimal
        if (empty($data['idMatakuliah']) || empty($data['idLaboratorium'])) {
            $this->error('Mata kuliah dan Laboratorium wajib diisi');
            return;
        }
        
        if ($this->model->insert($data)) {
            $this->success($this->model->getLastInsertId(), 'Jadwal berhasil ditambahkan', 201);
        } else {
            $this->error('Gagal membuat jadwal');
        }
    }

    /**
     * Admin: Update Jadwal.
     */
    public function update($params = []): void {
        $this->cleanBuffers();
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak valid', null, 400);
            return;
        }

        $input = $this->getJson() ?? $_POST;
        
        // Pemetaan field dari form ke kolom database
        $data = [
            'idMatakuliah'   => $input['idMatakuliah'] ?? null,
            'idLaboratorium' => $input['idLaboratorium'] ?? null,
            'hari'           => $input['hari'] ?? null,
            'kelas'          => strtoupper($input['kelas'] ?? ''),
            'waktuMulai'     => $input['waktuMulai'] ?? null,
            'waktuSelesai'   => $input['waktuSelesai'] ?? null,
            'frekuensi'      => $input['frekuensi'] ?? null,
            'idDosen'        => !empty($input['idDosen']) ? $input['idDosen'] : null,
            'asisten1'       => !empty($input['idAsisten1']) ? $input['idAsisten1'] : null,
            'asisten2'       => !empty($input['idAsisten2']) ? $input['idAsisten2'] : null,
            'status'         => $input['status'] ?? 'Aktif'
        ];
        
        if ($this->model->update($id, $data, 'idJadwal')) {
            $this->success([], 'Jadwal berhasil diperbarui');
        } else {
            $this->error('Gagal mengupdate jadwal');
        }
    }

    /**
     * Admin: Form Upload Excel.
     */
    public function uploadForm($params = []): void {
        $this->view('admin/jadwal/upload', [
            'judul' => 'Upload Jadwal dari Excel'
        ]);
    }

    /**
     * Admin: Process Upload File (CSV/Excel).
     */
    public function uploadProcess(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/jadwal/upload');
            return;
        }

        try {
            $file = $_FILES['file'] ?? null;
            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload gagal');
            }

            // Pengecekan file extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                throw new Exception("Format file .$extension tidak didukung. Gunakan Excel (.xlsx, .xls) atau CSV (.csv).");
            }

            $stats = $this->service->importFromExcel($file['tmp_name']);
            
            $msg = "Berhasil impor {$stats['success']} data.";
            if ($stats['duplicate'] > 0) $msg .= " ({$stats['duplicate']} duplikat diabaikan).";
            if ($stats['invalid'] > 0) $msg .= " ({$stats['invalid']} lab tidak dikenal).";

            $this->setFlash('success', $msg);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }

        $this->redirect('/admin/jadwal');
    }

    /**
     * Admin: Form CSV Upload.
     */
    public function csvUploadForm($params = []): void {
        $this->view('admin/jadwal/csv-upload', [
            'judul' => 'Upload Jadwal dari CSV'
        ]);
    }

    /**
     * Admin: Process CSV Upload.
     */
    public function csvUploadProcess(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/jadwal/csv-upload');
            return;
        }

        try {
            $file = $_FILES['file'] ?? null;
            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload gagal');
            }

            // Pengecekan file extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($extension !== 'csv') {
                throw new Exception("Format file .$extension tidak didukung. Gunakan file CSV (.csv).");
            }

            // Process CSV similar to Excel
            $stats = $this->service->importFromExcel($file['tmp_name']);
            
            $msg = "Berhasil impor {$stats['success']} data.";
            if ($stats['duplicate'] > 0) $msg .= " ({$stats['duplicate']} duplikat diabaikan).";
            if ($stats['invalid'] > 0) $msg .= " ({$stats['invalid']} lab tidak dikenal).";

            $this->setFlash('success', $msg);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }

        $this->redirect('/admin/jadwal');
    }

    /**
     * Admin: Delete Jadwal.
     */
    public function delete($params = []): void {
        $this->cleanBuffers();
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak valid', null, 400);
            return;
        }

        if ($this->model->delete($id)) {
            $this->success([], 'Jadwal berhasil dihapus');
        } else {
            $this->error('Gagal menghapus jadwal');
        }
    }

    /**
     * Admin: Delete Multiple Jadwal.
     */
    public function deleteMultiple(): void {
        $this->cleanBuffers();
        $data = $this->getJson();
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            $this->error('Tidak ada data yang dipilih', null, 400);
            return;
        }

        if ($this->model->deleteMultiple($ids)) {
            $this->success([], count($ids) . ' jadwal berhasil dihapus');
        } else {
            $this->error('Gagal menghapus beberapa data');
        }
    }

    /**
     * API: Handle Excel/CSV Upload
     */
    public function uploadApi(): void {
        $this->cleanBuffers();
        try {
            $file = $_FILES['excel_file'] ?? null;
            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload gagal');
            }

            // Pengecekan file extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                throw new Exception("Format file .$extension tidak didukung. Gunakan Excel (.xlsx, .xls) atau CSV (.csv).");
            }

            $stats = $this->service->importFromExcel($file['tmp_name']);
            
            $msg = "Berhasil impor {$stats['success']} data.";
            if ($stats['duplicate'] > 0) $msg .= " ({$stats['duplicate']} duplikat diabaikan).";
            if ($stats['invalid'] > 0) $msg .= " ({$stats['invalid']} lab tidak dikenal).";

            $this->success($stats, $msg);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * API: Download Template Excel
     */
    public function downloadTemplate(): void {
        $this->cleanBuffers();
        $dir = ROOT_PROJECT . '/public/assets/templates';
        $file = $dir . '/template_jadwal.xlsx';
        
        if (!file_exists($file)) {
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            try {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Jadwal Praktikum');
                $headers = ['No', 'Kode MK', 'Nama Dosen', 'Mata Kuliah', 'SKS', 'Kelas', 'Frekuensi', 'Laboratorium', 'Hari', 'Jam', 'Prodi', 'Asisten 1', 'Asisten 2'];
                $colIndex = 'A';
                foreach ($headers as $h) {
                    $sheet->setCellValue($colIndex . '1', $h);
                    $colIndex++;
                }
                $sampleData = [
                    [1, 'IF101', 'Dr. Ir. Ahmad Hidayat, M.T.', 'Pemrograman Web', 3, 'A', '1', 'Laboratorium Komputer 1', 'Senin', '08:00 - 10:30', 'Teknik Informatika', 'Aan Maulana Sampe', 'Andi Ahsan Ashuri'],
                    [2, 'SI202', 'Siti Nurhaliza, S.Kom., M.Cs.', 'Basis Data', 3, 'B', '2', 'Laboratorium Komputer 2', 'Selasa', '10:00 - 12:30', 'Sistem Informasi', 'Wahyu Kadri Rahmat Suat', 'Farah Tsabitaputri Az Zahra']
                ];
                $r = 2;
                foreach ($sampleData as $row) {
                    $c = 'A';
                    foreach ($row as $v) {
                        $sheet->setCellValue($c . $r, $v);
                        $c++;
                    }
                    $r++;
                }
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save($file);
            } catch (Exception $e) {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="template_jadwal_praktikum.csv"');
                $output = fopen('php://output', 'w');
                fputcsv($output, ['No', 'Kode MK', 'Nama Dosen', 'Mata Kuliah', 'SKS', 'Kelas', 'Frekuensi', 'Laboratorium', 'Hari', 'Jam', 'Prodi', 'Asisten 1', 'Asisten 2']);
                fputcsv($output, [1, 'IF101', 'Dr. Ir. Ahmad Hidayat, M.T.', 'Pemrograman Web', 3, 'A', '1', 'Laboratorium Komputer 1', 'Senin', '08:00 - 10:30', 'Teknik Informatika', 'Aan Maulana Sampe', 'Andi Ahsan Ashuri']);
                fclose($output);
                exit;
            }
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="template_jadwal_praktikum.xlsx"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    /**
     * Helper: Membersihkan output buffer untuk respon JSON bersih.
     */
    private function cleanBuffers(): void {
        while (ob_get_level()) { ob_end_clean(); }
    }
}