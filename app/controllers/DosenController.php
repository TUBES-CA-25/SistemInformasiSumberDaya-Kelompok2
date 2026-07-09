<?php

/**
 * DosenController
 * * Mengelola data Dosen (Lektor/Dosen Pengampu) untuk admin panel dan API.
 * * @package App\Controllers
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/DosenModel.php';

class DosenController extends Controller 
{
    /** @var DosenModel $model Instance Model Dosen */
    private $model;

    /**
     * Inisialisasi dependensi Model Dosen
     */
    public function __construct() 
    {
        $this->model = new DosenModel();
    }

    // =========================================================================
    // ADMIN VIEW METHODS (Menampilkan Halaman HTML)
    // =========================================================================

    /**
     * Menampilkan daftar dosen di Dashboard Admin.
     * * @return void
     */
    public function adminIndex(): void 
    {
        $data = [
            'judul' => 'Kelola Dosen',
            'dosen' => $this->model->getAll()
        ];
        $this->view('admin/dosen/index', $data);
    }

    // =========================================================================
    // API ENDPOINTS (Menangani Request Data JSON)
    // =========================================================================

    /**
     * API: Mengambil semua data dosen.
     * * @return void
     */
    public function apiIndex(): void 
    {
        $data = $this->model->getAll();
        $this->success($data, 'Data Dosen retrieved successfully');
    }

    /**
     * API: Mengambil detail satu dosen berdasarkan ID.
     * * @param array $params
     * @return void
     */
    public function apiShow(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID dosen diperlukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'idDosen');
        
        if (!$data) {
            $this->error('Dosen tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Dosen retrieved successfully');
    }

    /**
     * Menyimpan dosen baru ke database (Proses POST).
     * * @return void
     */
    public function store(): void 
    {
        $input = $this->getJson() ?? $_POST;
        unset($input['_method'], $input['idDosen']);
        
        // 1. Validasi Input Wajib
        $required = ['nama'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Data tidak lengkap: ' . implode(', ', $missing), null, 400);
            return;
        }

        // 2. Cek Duplikasi NIP (jika diisi)
        if (!empty($input['nip'])) {
            $existing = $this->model->getByColumn('nip', $input['nip']);
            if ($existing) {
                $this->error('NIP Dosen "' . $input['nip'] . '" sudah terdaftar', null, 400);
                return;
            }
        }

        // 3. Proses Simpan
        $dataToInsert = [
            'nip' => !empty($input['nip']) ? $input['nip'] : null,
            'nama' => $input['nama'],
            'email' => !empty($input['email']) ? $input['email'] : null,
            'status' => $input['status'] ?? 'Aktif'
        ];

        if ($this->model->insert($dataToInsert)) {
            $this->success(
                ['idDosen' => $this->model->getLastInsertId()], 
                'Dosen berhasil ditambahkan', 
                201
            );
        } else {
            $this->error('Gagal menyimpan dosen', null, 500);
        }
    }

    /**
     * Memperbarui data dosen yang sudah ada (Proses PUT/POST).
     * * @param array $params
     * @return void
     */
    public function update(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id || !$this->model->getById($id, 'idDosen')) {
            $this->error('Dosen tidak ditemukan atau ID tidak valid', null, 404);
            return;
        }

        $input = $this->getJson() ?? $_POST;
        unset($input['_method'], $input['idDosen']);
        
        // 1. Validasi Input Wajib
        $required = ['nama'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Data tidak lengkap: ' . implode(', ', $missing), null, 400);
            return;
        }

        // 2. Cek Duplikasi NIP (jika diisi dan tidak sama dengan dirinya sendiri)
        if (!empty($input['nip'])) {
            $existing = $this->model->getByColumn('nip', $input['nip']);
            if ($existing && $existing['idDosen'] != $id) {
                $this->error('NIP Dosen "' . $input['nip'] . '" sudah digunakan oleh dosen lain', null, 400);
                return;
            }
        }

        $dataToUpdate = [
            'nip' => !empty($input['nip']) ? $input['nip'] : null,
            'nama' => $input['nama'],
            'email' => !empty($input['email']) ? $input['email'] : null,
            'status' => $input['status'] ?? 'Aktif'
        ];

        if ($this->model->update($id, $dataToUpdate, 'idDosen')) {
            $this->success([], 'Dosen updated successfully');
        } else {
            $this->error('Terjadi kesalahan saat memperbarui data', null, 500);
        }
    }

    /**
     * Menghapus data dosen.
     * * @param array $params
     * @return void
     */
    public function delete(array $params): void 
    {
        $id = $params['id'] ?? null;

        if (!$id || !$this->model->getById($id, 'idDosen')) {
            $this->error('Dosen tidak ditemukan', null, 404);
            return;
        }

        if ($this->model->delete($id, 'idDosen')) {
            $this->success([], 'Dosen deleted successfully');
        } else {
            $this->error('Gagal menghapus dosen', null, 500);
        }
    }

    public function index() { return $this->apiIndex(); }
    public function show($params) { return $this->apiShow($params); }
}
