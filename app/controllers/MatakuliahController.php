<?php

/**
 * MatakuliahController
 * * Mengelola semua operasi terkait data Matakuliah, baik untuk tampilan Admin 
 * maupun kebutuhan integrasi API (JSON).
 * * @package App\Controllers
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/MatakuliahModel.php';

class MatakuliahController extends Controller 
{
    /** @var MatakuliahModel $model Instance Model Matakuliah */
    private $model;

    /**
     * Inisialisasi dependensi Model Matakuliah
     */
    public function __construct() 
    {
        $this->model = new MatakuliahModel();
    }

    // =========================================================================
    // ADMIN VIEW METHODS (Menampilkan Halaman HTML)
    // =========================================================================

    /**
     * Menampilkan daftar matakuliah di Dashboard Admin.
     * * @return void
     */
    public function adminIndex(): void 
    {
        $data = [
            'judul'      => 'Kelola Matakuliah',
            'matakuliah' => $this->model->getAll()
        ];
        $this->view('admin/matakuliah/index', $data);
    }

    /**
     * Menampilkan form untuk menambah matakuliah baru.
     * * @return void
     */
    public function create(): void 
    {
        $data = [
            'judul'      => 'Tambah Matakuliah',
            'matakuliah' => null,
            'action'     => 'create'
        ];
        $this->view('admin/matakuliah/form', $data);
    }

    /**
     * Menampilkan form untuk mengedit matakuliah yang sudah ada.
     * * @param array $params Parameter dari URL (seperti ['id' => 1])
     * @return void
     */
    public function edit(array $params = []): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->redirect('/admin/matakuliah');
            return;
        }

        $matakuliah = $this->model->getById($id, 'idMatakuliah');

        if (!$matakuliah) {
            $this->setFlash('error', 'Data matakuliah tidak ditemukan.');
            $this->redirect('/admin/matakuliah');
            return;
        }

        $data = [
            'judul'      => 'Edit Matakuliah',
            'matakuliah' => $matakuliah,
            'action'     => 'edit'
        ];
        $this->view('admin/matakuliah/form', $data);
    }

    // =========================================================================
    // API ENDPOINTS (Menangani Request Data JSON)
    // =========================================================================

    /**
     * API: Mengambil semua data matakuliah.
     * * @return void
     */
    public function apiIndex(): void 
    {
        $data = $this->model->getAll();
        $this->success($data, 'Data Matakuliah retrieved successfully');
    }

    /**
     * API: Mengambil detail satu matakuliah berdasarkan ID.
     * * @param array $params
     * @return void
     */
    public function apiShow(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID matakuliah diperlukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'idMatakuliah');
        
        if (!$data) {
            $this->error('Matakuliah tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Matakuliah retrieved successfully');
    }

    /**
     * Menyimpan matakuliah baru ke database (Proses POST).
     * * @return void
     */
    public function store(): void 
    {
        $input = $this->getJson(); // Mengambil data input (biasanya JSON AJAX)
        unset($input['_method'], $input['idMatakuliah']);
        
        // 1. Validasi Input Wajib
        $required = ['kodeMatakuliah', 'namaMatakuliah'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Data tidak lengkap: ' . implode(', ', $missing), null, 400);
            return;
        }

        // 2. Cek Duplikasi Kode Matakuliah
        $existing = $this->model->getMatakuliahByKode($input['kodeMatakuliah']);
        if ($existing) {
            $this->error('Kode matakuliah "' . $input['kodeMatakuliah'] . '" sudah terdaftar', null, 400);
            return;
        }

        // 3. Proses Simpan
        if ($this->model->insert($input)) {
            $this->success(
                ['id' => $this->model->getLastInsertId()], 
                'Matakuliah berhasil ditambahkan', 
                201
            );
        } else {
            $this->error('Gagal menyimpan matakuliah', null, 500);
        }
    }

    /**
     * Memperbarui data matakuliah yang sudah ada (Proses PUT/POST).
     * * @param array $params
     * @return void
     */
    public function update(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id || !$this->model->getById($id, 'idMatakuliah')) {
            $this->error('Matakuliah tidak ditemukan atau ID tidak valid', null, 404);
            return;
        }

        $input = $this->getJson();
        unset($input['_method'], $input['idMatakuliah']);
        
        if ($this->model->update($id, $input, 'idMatakuliah')) {
            $this->success([], 'Matakuliah updated successfully');
        } else {
            $this->error('Terjadi kesalahan saat memperbarui data', null, 500);
        }
    }

    /**
     * Menghapus data matakuliah.
     * * @param array $params
     * @return void
     */
    public function delete(array $params): void 
    {
        $id = $params['id'] ?? null;

        if (!$id || !$this->model->getById($id, 'idMatakuliah')) {
            $this->error('Matakuliah tidak ditemukan', null, 404);
            return;
        }

        if ($this->model->delete($id, 'idMatakuliah')) {
            $this->success([], 'Matakuliah deleted successfully');
        } else {
            $this->error('Gagal menghapus matakuliah', null, 500);
        }
    }

    /**
     * Mengambil daftar asisten yang mengampu matakuliah tertentu.
     * * @param array $params
     * @return void
     */
    public function asisten(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID matakuliah diperlukan', null, 400);
            return;
        }

        $data = $this->model->getMatakuliahWithAsisten($id);
        $this->success($data, 'Asisten matakuliah retrieved successfully');
    }

    // =========================================================================
    // ALIAS METHODS (Backwards Compatibility)
    // =========================================================================

    public function index() { return $this->apiIndex(); }
    public function show($params) { return $this->apiShow($params); }
}