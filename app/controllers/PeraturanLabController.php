<?php

/**
 * PeraturanLabController
 * Diperbarui untuk menggabungkan data Peraturan dan Sanksi dalam satu tampilan publik.
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/PeraturanLabModel.php';
// 1. Import Model Sanksi
require_once ROOT_PROJECT . '/app/models/SanksiLabModel.php'; 
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class PeraturanLabController extends Controller 
{
    private $model;
    private $sanksiModel; // Properti untuk model sanksi

    public function __construct() 
    {
        $this->model = new \PeraturanLabModel();
        // 2. Inisialisasi Model Sanksi
        $this->sanksiModel = new \SanksiLabModel(); 
    }

    // =========================================================================
    // WEB METHODS (MENAMPILKAN HALAMAN HTML)
    // =========================================================================

    /**
     * Menampilkan halaman publik Tata Tertib & Sanksi.
     */
    public function index(): void 
    {
        // 3. Ambil data mentah dari kedua model
        $rules_raw = $this->model->getAll();
        $sanksi_raw = $this->sanksiModel->getAll();
        
        // 4. Proses URL Gambar agar tidak pecah (menggunakan helper processMediaUrls)
        $rules_data = $this->processMediaUrls($rules_raw);
        $sanksi_data = $this->processMediaUrls($sanksi_raw);

        // 5. Kirim ke View dengan data lengkap
        $this->view('praktikum/tatatertib', [
            'judul'  => 'Tata Tertib & Sanksi - Laboratorium FIKOM',
            'rules'  => $rules_data,
            'sanksi' => $sanksi_data // Data sanksi sekarang terisi
        ]);
    }

    /**
     * Helper untuk memproses URL Gambar (Pastikan kolom di DB bernama 'gambar')
     */
    private function processMediaUrls(array $dataset): array {
        if (empty($dataset)) return [];
        
        $baseUrl = defined('PUBLIC_URL') ? rtrim(PUBLIC_URL, '/') : '';

        foreach ($dataset as &$row) {
            $imgName = $row['gambar'] ?? '';
            $row['img_url'] = ''; 

            if (!empty($imgName)) {
                $physicalPath = ROOT_PROJECT . '/public/assets/uploads/' . $imgName;
                if (file_exists($physicalPath)) {
                    $row['img_url'] = $baseUrl . '/assets/uploads/' . $imgName;
                }
            }
        }
        return $dataset;
    }

    // =========================================================================
    // API ENDPOINTS (RESPON JSON)
    // =========================================================================

    /**
     * API: Digunakan oleh AJAX atau sistem eksternal.
     */
    public function apiIndex(): void 
    {
        $data = $this->model->getAll();
        $this->success($data, 'Data peraturan lab retrieved successfully');
    }

    // =========================================================================
    // ADMIN VIEW METHODS
    // =========================================================================

    public function adminIndex(): void 
    {
        // Mengambil data untuk tabel admin
        $data = $this->model->getAll();
        $this->view('admin/peraturan_sanksi/index', [
            'peraturan' => $data
        ]);
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