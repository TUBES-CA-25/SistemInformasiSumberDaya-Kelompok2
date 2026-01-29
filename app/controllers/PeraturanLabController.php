<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/PeraturanLabModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class PeraturanLabController extends Controller {
    private $model;
    public function __construct() {
        $this->model = new \PeraturanLabModel();
    }

    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data peraturan lab retrieved successfully');
    }

    public function apiShow($params) {
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

    // Legacy methods
    public function index() {
        return $this->apiIndex();
    }

    public function show($params) {
        return $this->apiShow($params);
    }

    public function adminIndex($params = []) {
        $this->view('admin/peraturan_sanksi/index');
    }

    public function create($params = []) {
        $this->view('admin/peraturan_sanksi/form', ['action' => 'create']);
    }

    public function edit($params = []) {
        $this->view('admin/peraturan_sanksi/form', ['action' => 'edit', 'id' => $params['id'] ?? null]);
    }

    public function store() {
        try {
            // Log request data
            error_log('PERATURAN STORE - REQUEST METHOD: ' . $_SERVER['REQUEST_METHOD']);
            error_log('PERATURAN STORE - POST: ' . json_encode($_POST));
            error_log('PERATURAN STORE - FILES: ' . json_encode(array_keys($_FILES ?? [])));
            
            // Filter only database fields, ignore 'tipe' and 'id' (used for routing only)
            $input = [
                'judul' => $_POST['judul'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'display_format' => $_POST['display_format'] ?? 'list'
            ];
            
            error_log('PERATURAN STORE - INPUT: ' . json_encode($input));
            
            // Validasi field wajib
            if (empty($input['judul']) || empty($input['deskripsi'])) {
                $this->error('Field judul dan deskripsi wajib diisi', null, 400);
            }
            
            // Set default display_format jika kosong
            if (empty($input['display_format'])) {
                $input['display_format'] = 'list';
            }
            
            // Optional: handle file upload for gambar
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $subFolder = 'peraturan/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $filename = Helper::generateFilename('peraturan', $input['judul'], $ext);
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                    $input['gambar'] = $subFolder . $filename;
                }
            }
            
            error_log('PERATURAN STORE - FINAL INPUT: ' . json_encode($input));
            $result = $this->model->insert($input);
            error_log('PERATURAN STORE - INSERT RESULT: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            if ($result) {
                $this->success([], 'Peraturan lab created', 201);
            }
            
            $this->error('Failed to create peraturan lab', null, 500);
        } catch (Exception $e) {
            error_log('PERATURAN STORE - EXCEPTION: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        
        $oldData = $this->model->getById($id);
        if (!$oldData) $this->error('Data tidak ditemukan', null, 404);
        
        // Ambil input data dari $_POST (FormData akan tersimpan di sini untuk POST)
        $input = $_POST ?? [];
        
        // Debug logging
        error_log('PERATURAN UPDATE - ID: ' . $id . ', POST keys: ' . json_encode(array_keys($input)) . ', FILES: ' . json_encode(array_keys($_FILES ?? [])));
        error_log('PERATURAN UPDATE - POST values: ' . json_encode($input));
        
        // Validasi field wajib - gunakan data baru jika ada, jika tidak gunakan data lama
        $judul = !empty($input['judul']) ? trim($input['judul']) : $oldData['judul'];
        $kategori = !empty($input['kategori']) ? $input['kategori'] : ($oldData['kategori'] ?? 'Larangan Umum');
        $deskripsi = !empty($input['deskripsi']) ? trim($input['deskripsi']) : $oldData['deskripsi'];
        $urutan = !empty($input['urutan']) ? intval($input['urutan']) : ($oldData['urutan'] ?? 0);
        $displayFormat = !empty($input['display_format']) ? $input['display_format'] : ($oldData['display_format'] ?? 'list');
        
        if (empty($judul) || empty($deskripsi)) {
            $this->error('Field judul dan deskripsi wajib diisi', null, 400);
        }
        
        $input = [
            'judul' => $judul,
            'kategori' => $kategori,
            'deskripsi' => $deskripsi,
            'urutan' => $urutan,
            'display_format' => $displayFormat
        ];
        
        error_log('PERATURAN UPDATE - Final input: ' . json_encode($input));
        
        // Optional: handle file upload for gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $subFolder = 'peraturan/';
            $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            // Hapus gambar lama jika ada
            if (isset($oldData['gambar']) && !empty($oldData['gambar'])) {
                $oldFile = basename($oldData['gambar']);
                $oldImagePath = $uploadDir . $oldFile;
                $legacyPath_sub = dirname(__DIR__, 2) . '/storage/uploads/peraturan/' . $oldFile;
                $legacyPath_root = dirname(__DIR__, 2) . '/storage/uploads/' . $oldFile;
                
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                } elseif (file_exists($legacyPath_sub)) {
                    @unlink($legacyPath_sub);
                } elseif (file_exists($legacyPath_root)) {
                    @unlink($legacyPath_root);
                }
            }
            
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $filename = Helper::generateFilename('peraturan', $judul, $ext);
            $target = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                $input['gambar'] = $subFolder . $filename;
            }
        }
        $result = $this->model->update($id, $input);
        if ($result) $this->success([], 'Peraturan lab updated');
        $this->error('Failed to update peraturan lab', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        
        $oldData = $this->model->getById($id);
        if ($oldData && !empty($oldData['gambar'])) {
            $oldFile = basename($oldData['gambar']);
            $paths = [
                dirname(__DIR__, 2) . '/storage/uploads/peraturan/' . $oldFile,
                dirname(__DIR__, 2) . '/storage/uploads/' . $oldFile
            ];
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }
        
        $result = $this->model->delete($id);
        if ($result) $this->success([], 'Peraturan lab deleted');
        $this->error('Failed to delete peraturan lab', null, 500);
    }
}
