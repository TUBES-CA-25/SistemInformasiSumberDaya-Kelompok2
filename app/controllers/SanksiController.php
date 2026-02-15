<?php

/**
 * SanksiController
 * * Kelas ini bertanggung jawab untuk mengelola data sanksi laboratorium,
 * menyediakan akses data melalui API, serta menangani operasi CRUD untuk admin.
 * * @package App\Controllers
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/SanksiLabModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class SanksiController extends Controller 
{
    /** @var SanksiLabModel Instance model sanksi lab */
    private $model;

    /**
     * Inisialisasi model sanksi laboratorium.
     */
    public function __construct() 
    {
        $this->model = new \SanksiLabModel();
    }

    // =========================================================================
    // API METHODS (JSON Responses)
    // =========================================================================

    /**
     * API: Mengambil semua data sanksi laboratorium.
     * * @return void
     */
    public function apiIndex(): void 
    {
        $data = $this->model->getAll();
        $this->success($data, 'Data sanksi lab retrieved successfully');
    }

    /**
     * API: Mengambil detail satu data sanksi berdasarkan ID.
     * * @param array $params Parameter URL yang mengandung ID
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

        $this->success($data, 'Sanksi lab retrieved successfully');
    }

    /**
     * Alias method untuk apiIndex (kompatibilitas rute lama).
     */
    public function index() { return $this->apiIndex(); }

    /**
     * Alias method untuk apiShow (kompatibilitas rute lama).
     */
    public function show($params) { return $this->apiShow($params); }

    // =========================================================================
    // ADMIN ACTIONS (CRUD Operations)
    // =========================================================================

    /**
     * Menyimpan data sanksi baru ke database.
     * * @return void
     */
    public function store(): void 
    {
        try {
            // Mapping dan validasi input dari request POST
            $input = $this->getValidatedInput($_POST);

            if ($this->model->insert($input)) {
                $this->success([], 'Sanksi lab created', 201);
                return;
            }
            
            $this->error('Failed to create sanksi lab', null, 500);
        } catch (Exception $e) {
            error_log('SANKSI STORE EXCEPTION: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Memperbarui data sanksi yang sudah ada.
     * * @param array $params Parameter URL yang mengandung ID
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
            // Ambil input dari POST, jika kosong gunakan data lama (Fallback)
            $input = [
                'judul'          => !empty($_POST['judul']) ? trim($_POST['judul']) : $oldData['judul'],
                'deskripsi'      => !empty($_POST['deskripsi']) ? trim($_POST['deskripsi']) : $oldData['deskripsi'],
                'display_format' => $_POST['display_format'] ?? ($oldData['display_format'] ?? 'list')
            ];

            // Validasi field wajib pasca-fallback
            if (empty($input['judul']) || empty($input['deskripsi'])) {
                throw new Exception('Field judul dan deskripsi wajib diisi');
            }

            if ($this->model->update($id, $input)) {
                $this->success([], 'Sanksi lab updated');
                return;
            }
            
            $this->error('Failed to update sanksi lab', null, 500);
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, 400);
        }
    }

    /**
     * Menghapus data sanksi dari database.
     * * @param array $params Parameter URL yang mengandung ID
     * @return void
     */
    public function delete(array $params): void 
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        if ($this->model->delete($id)) {
            $this->success([], 'Sanksi lab deleted');
            return;
        }
        
        $this->error('Failed to delete sanksi lab', null, 500);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Memvalidasi input mentah dan mengembalikan array data yang siap diproses.
     * * @param array $rawData Data dari $_POST
     * @return array Data terfilter
     * @throws Exception Jika validasi gagal
     */
    private function getValidatedInput(array $rawData): array 
    {
        $judul = trim($rawData['judul'] ?? '');
        $deskripsi = trim($rawData['deskripsi'] ?? '');

        if (empty($judul) || empty($deskripsi)) {
            throw new Exception('Field judul dan deskripsi wajib diisi');
        }

        return [
            'judul'          => $judul,
            'deskripsi'      => $deskripsi,
            'display_format' => $rawData['display_format'] ?? 'list'
        ];
    }
}