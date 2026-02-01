<?php

/**
 * PeraturanLabController
 * * Mengelola aturan dan tata tertib laboratorium. 
 * Menyediakan fungsionalitas untuk antarmuka Admin (HTML) dan integrasi sistem (API JSON).
 * * @package App\Controllers
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/PeraturanLabModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class PeraturanLabController extends Controller 
{
    /** @var PeraturanLabModel Instance model untuk akses database */
    private $model;

    /**
     * Inisialisasi dependensi controller.
     */
    public function __construct() 
    {
        $this->model = new \PeraturanLabModel();
    }

    // =========================================================================
    // API ENDPOINTS (RESPON JSON)
    // =========================================================================

    /**
     * API: Mengambil semua daftar peraturan laboratorium.
     * * @return void Mengirimkan JSON response ke klien.
     */
    public function apiIndex(): void 
    {
        $data = $this->model->getAll();
        $this->success($data, 'Data peraturan lab retrieved successfully');
    }

    /**
     * API: Mengambil detail satu peraturan berdasarkan ID.
     * * @param array $params Parameter rute yang mengandung ['id' => value].
     * @return void
     */
    public function apiShow(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id);
        
        if (!$data) {
            $this->error('Data tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Peraturan lab retrieved successfully');
    }

    /**
     * Alias untuk API Index demi kompatibilitas rute lama.
     */
    public function index() { return $this->apiIndex(); }

    /**
     * Alias untuk API Show demi kompatibilitas rute lama.
     */
    public function show($params) { return $this->apiShow($params); }


    // =========================================================================
    // ADMIN VIEW METHODS (RENDERING HALAMAN)
    // =========================================================================

    /**
     * Menampilkan halaman daftar peraturan di dashboard admin.
     */
    public function adminIndex(): void 
    {
        $this->view('admin/peraturan_sanksi/index');
    }

    /**
     * Menampilkan form pembuatan peraturan baru.
     */
    public function create(): void 
    {
        $this->view('admin/peraturan_sanksi/form', ['action' => 'create']);
    }

    /**
     * Menampilkan form edit peraturan yang sudah ada.
     */
    public function edit(array $params = []): void 
    {
        $this->view('admin/peraturan_sanksi/form', [
            'action' => 'edit', 
            'id'     => $params['id'] ?? null
        ]);
    }


    // =========================================================================
    // DATABASE ACTIONS (CRUD)
    // =========================================================================

    /**
     * Menyimpan peraturan lab baru ke database.
     * * @return void
     */
    public function store(): void 
    {
        try {
            // Mapping input dari request POST
            $input = $this->getValidatedInput($_POST);

            if ($this->model->insert($input)) {
                $this->success([], 'Peraturan lab created successfully', 201);
                return;
            }
            
            $this->error('Failed to create peraturan lab', null, 500);
        } catch (Exception $e) {
            error_log("PERATURAN_STORE_ERROR: " . $e->getMessage());
            $this->error($e->getMessage(), null, 400);
        }
    }

    /**
     * Memperbarui data peraturan yang sudah ada.
     * * @param array $params Parameter rute yang mengandung ['id' => value].
     * @return void
     */
    public function update(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        $oldData = $this->model->getById($id);
        if (!$oldData) {
            $this->error('Data tidak ditemukan', null, 404);
            return;
        }

        try {
            // Gunakan data POST baru, jika kosong gunakan data lama dari DB
            $input = [
                'judul'          => !empty($_POST['judul']) ? trim($_POST['judul']) : $oldData['judul'],
                'deskripsi'      => !empty($_POST['deskripsi']) ? trim($_POST['deskripsi']) : $oldData['deskripsi'],
                'kategori'       => $_POST['kategori'] ?? ($oldData['kategori'] ?? 'Larangan Umum'),
                'urutan'         => isset($_POST['urutan']) ? intval($_POST['urutan']) : ($oldData['urutan'] ?? 0),
                'display_format' => $_POST['display_format'] ?? ($oldData['display_format'] ?? 'list')
            ];

            // Validasi data akhir
            if (empty($input['judul']) || empty($input['deskripsi'])) {
                throw new Exception('Judul dan deskripsi tidak boleh kosong');
            }

            if ($this->model->update($id, $input)) {
                $this->success([], 'Peraturan lab updated successfully');
                return;
            }
            
            $this->error('Failed to update peraturan lab', null, 500);
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, 400);
        }
    }

    /**
     * Menghapus peraturan lab dari database.
     * * @param array $params Parameter rute yang mengandung ['id' => value].
     * @return void
     */
    public function delete(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id || !$this->model->getById($id)) {
            $this->error('Data tidak ditemukan atau ID tidak valid', null, 404);
            return;
        }

        if ($this->model->delete($id)) {
            $this->success([], 'Peraturan lab deleted successfully');
            return;
        }
        
        $this->error('Failed to delete peraturan lab', null, 500);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Memvalidasi dan membersihkan input POST sebelum dikirim ke model.
     * * @param array $source Sumber data (biasanya $_POST).
     * @return array Data yang sudah dibersihkan.
     * @throws Exception Jika validasi gagal.
     */
    private function getValidatedInput(array $source): array 
    {
        $judul     = trim($source['judul'] ?? '');
        $deskripsi = trim($source['deskripsi'] ?? '');

        if (empty($judul) || empty($deskripsi)) {
            throw new Exception('Field judul dan deskripsi wajib diisi');
        }

        return [
            'judul'          => $judul,
            'deskripsi'      => $deskripsi,
            'display_format' => $source['display_format'] ?? 'list',
            'kategori'       => $source['kategori'] ?? 'Larangan Umum',
            'urutan'         => intval($source['urutan'] ?? 0)
        ];
    }
}