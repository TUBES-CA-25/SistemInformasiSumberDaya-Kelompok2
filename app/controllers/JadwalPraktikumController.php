<?php

require_once ROOT_PROJECT . '/app/models/JadwalPraktikumModel.php';
require_once ROOT_PROJECT . '/app/Services/JadwalPraktikumService.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * JadwalPraktikumController - Pengendali Alur Jadwal Praktikum
 * * Bertanggung jawab menerima input dari user (Web/API) dan memberikan output.
 * Logika pemrosesan data didelegasikan ke JadwalPraktikumService.
 */
class JadwalPraktikumController extends Controller {
    private $model;
    private $service;

    public function __construct() {
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
    public function show($params = []): void {
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
        $input = $_POST;
        
        // Validasi
        if (empty($input['idMatakuliah']) || empty($input['idLaboratorium'])) {
            $this->setFlash('error', 'Field wajib diisi');
            $this->redirect('/admin/jadwal/create');
            return;
        }

        $input['status'] = $input['status'] ?? 'Aktif';
        
        if ($this->model->insert($input)) {
            $this->setFlash('success', 'Jadwal berhasil dibuat');
            $this->redirect('/admin/jadwal');
        } else {
            $this->setFlash('error', 'Gagal membuat jadwal');
            $this->redirect('/admin/jadwal/create');
        }
    }

    /**
     * Admin: Update Jadwal.
     */
    public function update($params = []): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak valid', null, 400);
            return;
        }

        $input = $_POST;
        if ($this->model->update($id, $input)) {
            $this->success([], 'Jadwal berhasil diupdate');
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
     * Helper: Membersihkan output buffer untuk respon JSON bersih.
     */
    private function cleanBuffers(): void {
        while (ob_get_level()) { ob_end_clean(); }
    }
}