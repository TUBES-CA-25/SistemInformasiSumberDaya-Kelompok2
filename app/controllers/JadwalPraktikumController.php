<?php

// Pastikan Model dan Service dimuat dengan path absolut
require_once ROOT_PROJECT . '/app/models/JadwalPraktikumModel.php';
require_once ROOT_PROJECT . '/app/Services/JadwalPraktikumService.php';

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
            'dosen'          => $input['dosen'] ?? null,
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
            'dosen'          => $input['dosen'] ?? null,
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
        $file = ROOT_PROJECT . '/public/assets/templates/template_jadwal.xlsx';
        
        if (!file_exists($file)) {
            // Kita buat dummy download jika file belum ada, atau error
            $this->error('Template file tidak ditemukan');
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
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