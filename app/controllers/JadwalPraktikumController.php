<?php

require_once ROOT_PROJECT . '/app/services/JadwalPraktikumService.php';

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
     * Admin: List Dashboard Jadwal.
     */
    public function adminIndex(): void {
        $this->view('admin/jadwal/index');
    }

    /**
     * Admin: Proses Import Excel (API).
     */
    public function uploadExcel(): void {
        $this->cleanBuffers();
        header('Content-Type: application/json');

        try {
            if (!isset($_FILES['excel_file'])) {
                throw new Exception("File tidak diunggah.");
            }

            $stats = $this->service->importFromExcel($_FILES['excel_file']['tmp_name']);
            
            $msg = "Berhasil impor {$stats['success']} data.";
            if ($stats['duplicate'] > 0) $msg .= " ({$stats['duplicate']} duplikat diabaikan).";
            if ($stats['invalid'] > 0) $msg .= " ({$stats['invalid']} lab tidak dikenal).";

            $this->success($stats, $msg, 201);
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, 400);
        }
    }

    /**
     * Admin: Hapus satu jadwal.
     */
    public function delete(array $params): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->setFlash('error', 'ID tidak valid');
            $this->redirect('/admin/jadwal');
            return;
        }

        if ($this->model->delete($id)) {
            $this->setFlash('success', 'Jadwal dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus');
        }
        $this->redirect('/admin/jadwal');
    }

    /**
     * Admin: Hapus banyak jadwal sekaligus (API).
     */
    public function deleteMultiple(): void {
        $ids = $_POST['ids'] ?? [];
        if (empty($ids)) {
            $this->error('Tidak ada jadwal yang dipilih');
            return;
        }

        foreach ($ids as $id) {
            $this->model->delete($id);
        }
        $this->success(null, 'Jadwal yang dipilih telah dihapus');
    }

    /**
     * Helper: Membersihkan output buffer untuk respon JSON bersih.
     */
    private function cleanBuffers(): void {
        while (ob_get_level()) { ob_end_clean(); }
    }
}