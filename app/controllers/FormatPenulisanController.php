<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/FormatPenulisanModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class FormatPenulisanController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new FormatPenulisanModel();
    }

    /**
     * Halaman publik format penulisan
     */
    public function index($params = []) {
        $data = $this->model->getAllFormat();
        
        $this->view('praktikum/format_penulisan', [
            'informasi' => $data
        ]);
    }

    /**
     * Halaman admin format penulisan
     */
    public function adminIndex($params = []) {
        $this->view('admin/formatpenulisan/index');
    }

    /**
     * API: Get all data
     */
    public function apiIndex() {
        $data = $this->model->getAllFormat();
        $this->success($data, 'Data Format Penulisan retrieved successfully');
    }

    /**
     * API: Get single data
     */
    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'id_format');
        if (!$data) {
            $this->error('Data tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Data retrieved successfully');
    }

    /**
     * API: Store data
     */
    public function store() {
        try {
            $input = $_POST;
            if (empty($input)) {
                $input = $this->getJson() ?? [];
            }

            // Validasi Dasar
            $required = ['judul', 'kategori'];
            $missing = $this->validateRequired($input, $required);
            if (!empty($missing)) {
                $this->error('Field required: ' . implode(', ', $missing), null, 400);
            }

            // Validasi Spesifik Kategori
            if ($input['kategori'] === 'pedoman' && empty($input['deskripsi'])) {
                $this->error('Deskripsi wajib diisi untuk kategori Pedoman', null, 400);
            }

            $input['tanggal_update'] = date('Y-m-d');

            // Handle File Upload
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = ROOT_PROJECT . '/public/assets/uploads/format_penulisan/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $fileName = Helper::generateFilename('format', $input['judul'], $fileExtension);
                
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
                    $input['file'] = $fileName;
                }
            }

            // Filter data
            $allowedColumns = ['judul', 'icon', 'warna', 'deskripsi', 'urutan', 'tanggal_update', 'file', 'kategori', 'link_external'];
            $dataToSave = [];
            
            foreach ($allowedColumns as $col) {
                if (isset($input[$col])) {
                    $dataToSave[$col] = $input[$col];
                }
            }

            $result = $this->model->insert($dataToSave);
            
            if ($result) {
                $this->success(['id' => $this->model->getLastInsertId()], 'Data created successfully', 200);
            }
            $this->error('Failed to create data', null, 500);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * API: Update data
     */
    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) $this->error('ID tidak ditemukan', null, 400);

            $existing = $this->model->getById($id, 'id_format');
            if (!$existing) $this->error('Data tidak ditemukan', null, 404);

            $input = $_POST;
            if (empty($input)) $input = $this->getJson() ?? [];

            // Jika method adalah PUT via FormData (menggunakan _method)
            // PHP biasanya mengisi $_POST jika menggunakan multipart/form-data
            
            $input['tanggal_update'] = date('Y-m-d');

            // Handle File Upload
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = ROOT_PROJECT . '/public/assets/uploads/format_penulisan/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $fileName = Helper::generateFilename('format', $input['judul'] ?? $existing['judul'], $fileExtension);

                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
                    // Hapus file lama jika ada
                    if (!empty($existing['file']) && file_exists($uploadDir . $existing['file'])) {
                        @unlink($uploadDir . $existing['file']);
                    }
                    $input['file'] = $fileName;
                }
            }

            // Filter data
            $allowedColumns = ['judul', 'icon', 'warna', 'deskripsi', 'urutan', 'tanggal_update', 'file', 'kategori', 'link_external'];
            $dataToUpdate = [];

            foreach ($allowedColumns as $col) {
                if (isset($input[$col])) {
                    $dataToUpdate[$col] = $input[$col];
                }
            }

            $result = $this->model->update($id, $dataToUpdate, 'id_format');
            
            if ($result) {
                $this->success([], 'Data updated successfully');
            }
            $this->error('Failed to update data', null, 500);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * API: Delete data
     */
    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);

        $result = $this->model->delete($id, 'id_format');
        if ($result) {
            $this->success([], 'Data deleted successfully');
        }
        $this->error('Failed to delete data', null, 500);
    }
}
