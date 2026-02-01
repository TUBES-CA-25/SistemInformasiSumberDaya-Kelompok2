<?php

require_once ROOT_PROJECT . '/app/services/LaboratoriumService.php';

/**
 * LaboratoriumController - Orchestrator Layer
 * Bertugas menerima input dari user dan menentukan view mana yang ditampilkan.
 */
class LaboratoriumController extends Controller {
    private $service;

    public function __construct() {
        $this->service = new LaboratoriumService();
    }

    /**
     * Tampilan Publik: Daftar Laboratorium
     */
    public function index(): void {
        $labs = $this->service->getListForPublic();
        $this->view('fasilitas/laboratorium', [
            'judul' => 'Fasilitas Laboratorium',
            'laboratorium' => $labs
        ]);
    }

    /**
     * Tampilan Publik: Detail Laboratorium
     */
    public function detail(array $params = []): void {
        $id = $params['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('index.php?page=laboratorium');
            return;
        }

        $data = $this->service->getDetailedData((int)$id);
        if (!$data) {
            $this->redirect('index.php?page=laboratorium');
            return;
        }

        $data['judul'] = 'Detail ' . ($data['laboratorium']['nama'] ?? 'Fasilitas');
        $data['back_link'] = $this->getBackLink($data['laboratorium']['jenis'] ?? '');

        $this->view('fasilitas/detail', $data);
    }

    /**
     * Admin: Hapus Gambar (Thumbnail Auto-replace) via AJAX
     */
    public function deleteImage(array $params): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID gambar tidak ditemukan', null, 400);
            return;
        }

        try {
            $this->service->deleteImageWithLogic((int)$id);
            $this->success(['id' => $id], 'Gambar berhasil dihapus dan thumbnail diperbarui');
        } catch (Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage(), null, 500);
        }
    }

    // --- Private Helper ---
    
    private function getBackLink(string $jenis): string {
        return (stripos($jenis, 'riset') !== false) ? 'index.php?page=riset' : 'index.php?page=laboratorium';
    }
}