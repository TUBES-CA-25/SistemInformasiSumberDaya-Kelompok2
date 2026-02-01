<?php

require_once ROOT_PROJECT . '/app/Services/FasilitasService.php';
require_once ROOT_PROJECT . '/app/models/FasilitasModel.php';

/**
 * FasilitasController - Orchestrator Manajemen Fasilitas & Lab
 */
class FasilitasController extends Controller {
    private $service;
    private $model;

    public function __construct() {
        $this->service = new FasilitasService();
        $this->model = new FasilitasModel();
    }

    // --- VIEW ROUTES ---

    /**
     * Tampilan publik daftar laboratorium/fasilitas
     */
    public function index(): void {
        $labs = $this->service->getFasilitasWithThumbnails();
        $this->view('fasilitas/laboratorium', [
            'judul' => 'Fasilitas Laboratorium',
            'laboratorium' => $labs
        ]);
    }

    /**
     * Tampilan publik detail fasilitas
     */
    public function detail($params = []): void {
        $id = $params['id'] ?? $_GET['id'] ?? null;
        $data = $this->service->getFullDetail($id);

        if (!$data) {
            $this->redirect('index.php?page=laboratorium');
            return;
        }

        $data['judul'] = 'Detail ' . $data['lab']['nama'];
        $data['back_link'] = 'index.php?page=laboratorium';
        
        $this->view('fasilitas/detail', $data);
    }

    // --- API & CRUD ROUTES ---

    public function apiIndex(): void {
        $this->success($this->model->getAll(), 'Data retrieved successfully');
    }

    public function store(): void {
        $input = $this->getJson() ?? $_POST;
        if (empty($input['nama'])) {
            $this->error('Nama wajib diisi');
            return;
        }

        if ($this->model->insert($input)) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Fasilitas berhasil ditambahkan', 201);
        } else {
            $this->error('Gagal menyimpan ke database');
        }
    }

    public function delete($params): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID diperlukan');
            return;
        }

        // Pengecekan relasi sebelum hapus (Pastikan method ini ada di FasilitasModel)
        if (method_exists($this->model, 'hasJadwal') && $this->model->hasJadwal($id)) {
            $this->error('Fasilitas tidak bisa dihapus karena masih digunakan dalam jadwal.');
            return;
        }

        if ($this->model->delete($id, 'idLaboratorium')) {
            $this->success([], 'Fasilitas berhasil dihapus');
        } else {
            $this->error('Gagal menghapus data');
        }
    }

    /**
     * API: Hapus Gambar Galeri
     */
    public function deleteImage($params): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID gambar tidak ditemukan');
            return;
        }

        try {
            $this->service->deleteImageWithLogic($id);
            $this->success(['id' => $id], 'Gambar berhasil dihapus');
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, 500);
        }
    }

    // --- ADMIN ROUTES ---

    /**
     * Admin: Daftar Fasilitas
     */
    public function adminIndex(): void {
        $labs = $this->model->getAll();
        $this->view('admin/fasilitas/index', [
            'judul' => 'Kelola Fasilitas Laboratorium',
            'laboratorium' => $labs
        ]);
    }

    /**
     * Admin: Form Create Fasilitas
     */
    public function create($params = []): void {
        $this->view('admin/fasilitas/form', [
            'judul' => 'Tambah Fasilitas Laboratorium',
            'action' => 'create',
            'data' => null
        ]);
    }

    /**
     * Admin: Form Edit Fasilitas
     */
    public function edit($params = []): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->setFlash('error', 'ID tidak ditemukan');
            $this->redirect('/admin/informasi-lab');
            return;
        }

        $lab = $this->model->getById($id, 'idLaboratorium');
        if (!$lab) {
            $this->setFlash('error', 'Data fasilitas tidak ditemukan');
            $this->redirect('/admin/informasi-lab');
            return;
        }

        $this->view('admin/fasilitas/form', [
            'judul' => 'Edit Fasilitas Laboratorium',
            'action' => 'edit',
            'data' => $lab
        ]);
    }

    /**
     * Admin: Update Fasilitas
     */
    public function update($params = []): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak ditemukan');
            return;
        }

        $input = $this->getJson() ?? $_POST;
        if (empty($input['nama'])) {
            $this->error('Nama wajib diisi');
            return;
        }

        if ($this->model->update($id, $input, 'idLaboratorium')) {
            $this->success([], 'Fasilitas berhasil diperbarui');
        } else {
            $this->error('Gagal mengupdate data');
        }
    }

    /**
     * Admin: Detail Fasilitas
     */
    public function adminDetail($params = []): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/informasi-lab');
            return;
        }

        $data = $this->service->getFullDetail($id);
        if (!$data) {
            $this->setFlash('error', 'Data fasilitas tidak ditemukan');
            $this->redirect('/admin/informasi-lab');
            return;
        }

        $data['judul'] = 'Detail ' . $data['lab']['nama'];
        $this->view('admin/fasilitas/detail', $data);
    }}