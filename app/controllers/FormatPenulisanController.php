<?php

/**
 * FormatPenulisanController - Kelola Format dan Panduan Penulisan Laporan
 * 
 * Menangani:
 * - Tampilan publik format penulisan untuk mahasiswa praktikum
 * - Dashboard admin untuk manajemen format penulisan
 * - API endpoints untuk CRUD format penulisan
 * - Upload dokumen template (PDF/DOC/DOCX)
 * - Kategori: pedoman, template, contoh
 * - File management dengan unique naming
 * 
 * Database Table: format_penulisan
 * - Primary Key: id_format
 * - Key Fields: judul, kategori, icon, warna, deskripsi, urutan
 * - File: uploads/format_penulisan/
 * 
 * Models:
 * - FormatPenulisanModel: Database operations
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/FormatPenulisanModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class FormatPenulisanController extends Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var FormatPenulisanModel Model untuk data format penulisan */
    private $model;
    
    /** @var string Upload directory untuk file format penulisan */
    private $uploadDir = '/public/assets/uploads/format_penulisan/';
    
    /** @var array Kolom yang diizinkan untuk CRUD */
    private $allowedColumns = ['judul', 'icon', 'warna', 'deskripsi', 'urutan', 
                              'tanggal_update', 'file', 'kategori', 'link_external'];

    
    // =========================================================================
    // BAGIAN 2: KONSTRUKTOR
    // =========================================================================
    
    /**
     * Inisialisasi FormatPenulisanController dengan model
     */
    public function __construct() {
        $this->model = new FormatPenulisanModel();
    }

    
    // =========================================================================
    // BAGIAN 3: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Index - Tampilkan halaman publik format penulisan
     * 
     * Menampilkan daftar lengkap format penulisan untuk mahasiswa.
     * Format dipilih berdasarkan kategori dan urutan.
     * 
     * Flow:
     * 1. Fetch semua data format dari database
     * 2. Urutkan berdasarkan field urutan
     * 3. Pass ke view praktikum/format_penulisan
     * 4. View menampilkan cards dengan icon, warna, dan informasi
     * 
     * Data untuk view:
     * - informasi: Array data format penulisan lengkap
     * 
     * @param array $params Parameter dari router (tidak digunakan)
     * @return void Menampilkan view publik
     */
    public function index($params = []) {
        // LANGKAH 1: Fetch semua data format dari database
        $data = $this->model->getAllFormat();
        
        // LANGKAH 2: Pass ke view dengan key 'informasi'
        $this->view('praktikum/format_penulisan', [
            'informasi' => $data
        ]);
    }

    /**
     * Admin Index - Halaman dashboard admin format penulisan
     * 
     * Menampilkan interface admin untuk manajemen format penulisan.
     * Admin dapat melakukan create, read, update, delete melalui interface ini.
     * Interface menggunakan AJAX untuk call API endpoints.
     * 
     * View Components:
     * - Data table dengan data format penulisan dari API
     * - Form modal untuk create/edit
     * - File upload handler
     * - Delete confirmation dialog
     * 
     * @param array $params Parameter dari router (tidak digunakan)
     * @return void Menampilkan view admin
     */
    public function adminIndex($params = []) {
        // LANGKAH 1: Tampilkan view admin dashboard
        // Data dimuat via AJAX call ke apiIndex()
        $this->view('admin/formatpenulisan/index');
    }

    
    // =========================================================================
    // BAGIAN 4: API ENDPOINTS (READ)
    // =========================================================================
    
    /**
     * API Index - Get semua data format penulisan
     * 
     * HTTP Method: GET
     * Endpoint: /api/format-penulisan
     * 
     * Response Format:
     * {
     *   "status": "success",
     *   "data": [
     *     {
     *       "id_format": 1,
     *       "judul": "Panduan Penulisan Laporan",
     *       "kategori": "pedoman",
     *       "icon": "ri-file-text-line",
     *       "warna": "#3498db",
     *       "deskripsi": "Panduan lengkap penulisan laporan praktikum",
     *       "file": "format_panduan_penulisan.pdf",
     *       "urutan": 1,
     *       "tanggal_update": "2024-01-15"
     *     }
     *   ],
     *   "message": "Data Format Penulisan retrieved successfully"
     * }
     * 
     * @return void Output JSON response via $this->success()
     */
    public function apiIndex() {
        // LANGKAH 1: Fetch semua format penulisan
        $data = $this->model->getAllFormat();
        
        // LANGKAH 2: Return dengan success response
        $this->success($data, 'Data Format Penulisan berhasil diambil');
    }

    /**
     * API Show - Get single data format penulisan
     * 
     * HTTP Method: GET
     * Endpoint: /api/format-penulisan/{id}
     * 
     * Parameter:
     * - id: ID format penulisan (wajib ada, error 400 jika kosong)
     * 
     * Response pada success (200):
     * {
     *   "status": "success",
     *   "data": { id_format, judul, kategori, ... },
     *   "message": "Data retrieved successfully"
     * }
     * 
     * Response pada error:
     * - 400: ID tidak ditemukan
     * - 404: Data tidak ditemukan di database
     * 
     * @param array $params Parameter dengan key 'id'
     * @return void Output JSON response
     */
    public function apiShow($params) {
        // LANGKAH 1: Validasi ID parameter
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        // LANGKAH 2: Fetch data dari database
        $data = $this->model->getById($id, 'id_format');
        if (!$data) {
            $this->error('Data format penulisan tidak ditemukan', null, 404);
            return;
        }

        // LANGKAH 3: Return success response
        $this->success($data, 'Data berhasil diambil');
    }

    
    // =========================================================================
    // BAGIAN 5: API ENDPOINTS (CREATE)
    // =========================================================================
    
    /**
     * Store - Create data format penulisan baru
     * 
     * HTTP Method: POST
     * Endpoint: /api/format-penulisan
     * Content-Type: multipart/form-data (untuk file upload)
     * 
     * Required Fields:
     * - judul: Judul format penulisan (string, max 100 chars)
     * - kategori: Kategori ('pedoman', 'template', 'contoh')
     * 
     * Optional Fields:
     * - icon: Icon CSS class (default: 'ri-file-text-line')
     * - warna: Warna hex (#3498db)
     * - deskripsi: Deskripsi (wajib jika kategori='pedoman')
     * - urutan: Urutan tampil (integer, default: 0)
     * - file: File upload (PDF/DOC/DOCX)
     * - link_external: Link eksternal sebagai alternatif file
     * 
     * Process Flow:
     * 1. Ambil input dari $_POST atau JSON body
     * 2. Validasi field required (judul, kategori)
     * 3. Validasi kategori-specific (deskripsi wajib untuk 'pedoman')
     * 4. Handle file upload jika ada (generate unique filename)
     * 5. Filter kolom yang diizinkan
     * 6. Insert ke database
     * 7. Return ID baru (200) atau error (400/500)
     * 
     * Error Responses:
     * - 400: Field required kosong atau validation error
     * - 500: Database insert failed atau exception
     * 
     * Success Response (200):
     * {
     *   "status": "success",
     *   "data": { "id": 5 },
     *   "message": "Data created successfully"
     * }
     * 
     * @return void Output JSON response
     */
    public function store() {
        try {
            // LANGKAH 1: Ambil input (POST form atau JSON)
            $input = $_POST;
            if (empty($input)) {
                $input = $this->getJson() ?? [];
            }

            // LANGKAH 2: Validasi field required
            $required = ['judul', 'kategori'];
            $missing = $this->validateRequired($input, $required);
            if (!empty($missing)) {
                $this->error('Field wajib diisi: ' . implode(', ', $missing), null, 400);
                return;
            }

            // LANGKAH 3: Validasi kategori-specific
            if ($input['kategori'] === 'pedoman' && empty($input['deskripsi'])) {
                $this->error('Deskripsi wajib diisi untuk kategori Pedoman', null, 400);
                return;
            }

            // LANGKAH 4: Set timestamp update
            $input['tanggal_update'] = date('Y-m-d');

            // LANGKAH 5: Handle file upload jika ada
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = ROOT_PROJECT . $this->uploadDir;
                
                // Buat directory jika belum ada
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Generate unique filename
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $fileName = Helper::generateFilename('format', $input['judul'], $fileExtension);
                
                // Move file ke upload directory
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath . $fileName)) {
                    $input['file'] = $fileName;
                }
            }

            // LANGKAH 6: Filter kolom yang diizinkan
            $dataToSave = [];
            foreach ($this->allowedColumns as $col) {
                if (isset($input[$col])) {
                    $dataToSave[$col] = $input[$col];
                }
            }

            // LANGKAH 7: Insert ke database
            $result = $this->model->insert($dataToSave);
            
            if ($result) {
                $this->success(
                    ['id' => $this->model->getLastInsertId()], 
                    'Data berhasil dibuat', 
                    200
                );
                return;
            }
            
            // Insert gagal
            $this->error('Gagal membuat data format penulisan', null, 500);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    
    // =========================================================================
    // BAGIAN 6: API ENDPOINTS (UPDATE & DELETE)
    // =========================================================================
    
    /**
     * Update - Update data format penulisan existing
     * 
     * HTTP Method: PUT
     * Endpoint: /api/format-penulisan/{id}
     * Content-Type: multipart/form-data (untuk file upload)
     * 
     * Parameter:
     * - id: ID format penulisan (wajib ada)
     * 
     * Update Fields:
     * - judul, kategori, icon, warna, deskripsi, urutan
     * - file: Upload file baru (optional, akan replace file lama)
     * - link_external: Link eksternal
     * 
     * Process Flow:
     * 1. Validasi ID ada dan data existing ditemukan
     * 2. Ambil input baru (POST/JSON)
     * 3. Set timestamp update
     * 4. Handle file upload (delete file lama jika ada, upload file baru)
     * 5. Filter kolom yang diizinkan
     * 6. Update database
     * 7. Return success atau error
     * 
     * Error Responses:
     * - 400: ID tidak ditemukan
     * - 404: Data tidak ditemukan di database
     * - 500: Update gagal atau exception
     * 
     * Success Response (200):
     * {
     *   "status": "success",
     *   "data": [],
     *   "message": "Data updated successfully"
     * }
     * 
     * @param array $params Parameter dengan key 'id'
     * @return void Output JSON response
     */
    public function update($params) {
        try {
            // LANGKAH 1: Validasi ID dan cek data existing
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID tidak ditemukan', null, 400);
                return;
            }

            $existing = $this->model->getById($id, 'id_format');
            if (!$existing) {
                $this->error('Data format penulisan tidak ditemukan', null, 404);
                return;
            }

            // LANGKAH 2: Ambil input (POST form atau JSON)
            $input = $_POST;
            if (empty($input)) {
                $input = $this->getJson() ?? [];
            }

            // LANGKAH 3: Set timestamp update
            $input['tanggal_update'] = date('Y-m-d');

            // LANGKAH 4: Handle file upload (replace jika ada file baru)
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = ROOT_PROJECT . $this->uploadDir;
                
                // Buat directory jika belum ada
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Generate unique filename
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $judul = $input['judul'] ?? $existing['judul'];
                $fileName = Helper::generateFilename('format', $judul, $fileExtension);

                // Move file baru
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath . $fileName)) {
                    // Hapus file lama jika ada
                    if (!empty($existing['file'])) {
                        $oldFilePath = $uploadPath . $existing['file'];
                        if (file_exists($oldFilePath)) {
                            @unlink($oldFilePath);
                        }
                    }
                    $input['file'] = $fileName;
                }
            }

            // LANGKAH 5: Filter kolom yang diizinkan
            $dataToUpdate = [];
            foreach ($this->allowedColumns as $col) {
                if (isset($input[$col])) {
                    $dataToUpdate[$col] = $input[$col];
                }
            }

            // LANGKAH 6: Update database
            $result = $this->model->update($id, $dataToUpdate, 'id_format');
            
            if ($result) {
                $this->success([], 'Data berhasil diupdate');
                return;
            }
            
            // Update gagal
            $this->error('Gagal mengupdate data format penulisan', null, 500);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Delete - Hapus data format penulisan
     * 
     * HTTP Method: DELETE
     * Endpoint: /api/format-penulisan/{id}
     * 
     * Parameter:
     * - id: ID format penulisan (wajib ada)
     * 
     * Process Flow:
     * 1. Validasi ID ada
     * 2. Delete file terkait jika ada
     * 3. Delete record dari database
     * 4. Return success atau error
     * 
     * Error Responses:
     * - 400: ID tidak ditemukan
     * - 500: Delete gagal
     * 
     * Success Response (200):
     * {
     *   "status": "success",
     *   "data": [],
     *   "message": "Data deleted successfully"
     * }
     * 
     * @param array $params Parameter dengan key 'id'
     * @return void Output JSON response
     */
    public function delete($params) {
        try {
            // LANGKAH 1: Validasi ID
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID tidak ditemukan', null, 400);
                return;
            }

            // LANGKAH 2: Cek data existing dan hapus file jika ada
            $existing = $this->model->getById($id, 'id_format');
            if ($existing && !empty($existing['file'])) {
                $filePath = ROOT_PROJECT . $this->uploadDir . $existing['file'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            // LANGKAH 3: Delete dari database
            $result = $this->model->delete($id, 'id_format');
            
            if ($result) {
                $this->success([], 'Data berhasil dihapus');
                return;
            }
            
            // Delete gagal
            $this->error('Gagal menghapus data format penulisan', null, 500);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }
}
