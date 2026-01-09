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
        $this->view('admin/peraturan/index');
    }

    public function create($params = []) {
        $this->view('admin/peraturan/form', ['action' => 'create']);
    }

    public function edit($params = []) {
        $this->view('admin/peraturan/form', ['action' => 'edit', 'id' => $params['id'] ?? null]);
    }

    public function store() {
        // Cek apakah request multipart/form-data (upload file)
        if (isset($_FILES['gambar'])) {
            $input = [
                'judul' => $_POST['judul'] ?? '',
                'kategori' => $_POST['kategori'] ?? 'Larangan Umum',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'urutan' => $_POST['urutan'] ?? 0
            ];
        } else {
            // Coba ambil dari $_POST dulu, jika kosong ambil dari JSON
            $input = $_POST;
            if (empty($input)) {
                $input = $this->getJson() ?? [];
            }
        }
        
        // Validasi field wajib
        if (empty($input['judul']) || empty($input['deskripsi'])) {
            $this->error('Field judul dan deskripsi wajib diisi', null, 400);
        }
        
        // Optional: handle file upload for gambar
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
        $result = $this->model->insert($input);
        if ($result) $this->success([], 'Peraturan lab created', 201);
        $this->error('Failed to create peraturan lab', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        
        $oldData = $this->model->getById($id);
        
        // Cek apakah request multipart/form-data (upload file)
        if (isset($_FILES['gambar'])) {
            $input = [
                'judul' => $_POST['judul'] ?? ($oldData['judul'] ?? ''),
                'kategori' => $_POST['kategori'] ?? ($oldData['kategori'] ?? 'Larangan Umum'),
                'deskripsi' => $_POST['deskripsi'] ?? ($oldData['deskripsi'] ?? ''),
                'urutan' => $_POST['urutan'] ?? ($oldData['urutan'] ?? 0)
            ];
        } else {
            // Coba ambil dari $_POST dulu, jika kosong ambil dari JSON
            $input = $_POST;
            if (empty($input)) {
                $input = $this->getJson() ?? [];
            }
        }
        
        // Validasi field wajib - gunakan data baru jika ada, jika tidak gunakan data lama
        $judul = !empty($input['judul']) ? $input['judul'] : $oldData['judul'];
        $deskripsi = !empty($input['deskripsi']) ? $input['deskripsi'] : $oldData['deskripsi'];
        
        if (empty($judul) || empty($deskripsi)) {
            $this->error('Field judul dan deskripsi wajib diisi', null, 400);
        }
        
        $input['judul'] = $judul;
        $input['deskripsi'] = $deskripsi;
        
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
