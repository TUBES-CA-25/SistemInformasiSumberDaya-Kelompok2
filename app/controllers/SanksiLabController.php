<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/SanksiLabModel.php';

class SanksiLabController extends Controller {
    private $model;
    public function __construct() {
        $this->model = new \SanksiLabModel();
    }

    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data sanksi lab retrieved successfully');
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
        $this->success($data, 'Sanksi lab retrieved successfully');
    }

    // Legacy methods
    public function index() {
        return $this->apiIndex();
    }

    public function show($params) {
        return $this->apiShow($params);
    }

    public function store() {
        // Check if this is actually an update (PUT override)
        if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
            // Extract ID from somewhere - bisa dari POST juga
            // Tapi biasanya untuk update dengan file, we'll handle di routing
            // Untuk sekarang, return error karena ID tidak ada di body
            $this->error('ID harus diberikan di URL untuk update', null, 400);
        }
        
        // Cek apakah request multipart/form-data (upload file)
        if (isset($_FILES['gambar'])) {
            $input = [
                'judul' => $_POST['judul'] ?? '',
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
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $file = $_FILES['gambar'];
            error_log('DEBUG STORE: File upload - name: ' . $file['name'] . ', size: ' . $file['size'] . ', tmp: ' . $file['tmp_name']);
            
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $filename = 'sanksi_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $target = $uploadDir . $filename;
            
            error_log('DEBUG STORE: Target path: ' . $target);
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                error_log('DEBUG STORE: File upload SUCCESS');
                // Simpan hanya nama file, path akan diatur di view
                $input['gambar'] = $filename;
            } else {
                error_log('DEBUG STORE: File upload FAILED');
                $this->error('Gagal upload gambar. Pastikan folder storage/uploads dapat ditulis.', null, 500);
            }
        } else if (isset($_FILES['gambar'])) {
            error_log('DEBUG STORE: File error code: ' . $_FILES['gambar']['error']);
        }
        $result = $this->model->insert($input);
        if ($result) $this->success([], 'Sanksi lab created', 201);
        $this->error('Failed to create sanksi lab', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        
        error_log('=== UPDATE DEBUG ===');
        error_log('ID: ' . $id);
        error_log('POST keys: ' . json_encode(array_keys($_POST)));
        error_log('FILES keys: ' . json_encode(array_keys($_FILES)));
        
        // Ambil data lama dulu
        $oldData = $this->model->getById($id);
        if (!$oldData) $this->error('Data tidak ditemukan', null, 404);
        
        // Cek apakah request multipart/form-data (upload file)
        if (isset($_FILES['gambar'])) {
            error_log('File gambar terdeteksi, size: ' . $_FILES['gambar']['size']);
            $input = [
                'judul' => $_POST['judul'] ?? $oldData['judul'],
                'deskripsi' => $_POST['deskripsi'] ?? $oldData['deskripsi'],
                'urutan' => $_POST['urutan'] ?? $oldData['urutan'] ?? 0
            ];
        } else {
            error_log('File gambar TIDAK terdeteksi');
            // Ambil dari $_POST (karena sekarang gunakan POST untuk update dengan file)
            $input = [
                'judul' => $_POST['judul'] ?? $oldData['judul'],
                'deskripsi' => $_POST['deskripsi'] ?? $oldData['deskripsi'],
                'urutan' => $_POST['urutan'] ?? $oldData['urutan'] ?? 0
            ];
        }
        
        // Validasi field wajib
        if (empty($input['judul']) || empty($input['deskripsi'])) {
            $this->error('Field judul dan deskripsi wajib diisi', null, 400);
        }
        
        // Optional: handle file upload for gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            // Hapus gambar lama jika ada
            if (isset($oldData['gambar']) && !empty($oldData['gambar'])) {
                $oldImagePath = $uploadDir . $oldData['gambar'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Upload gambar baru
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $filename = 'sanksi_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $target = $uploadDir . $filename;
            
            error_log('DEBUG UPDATE: Target path: ' . $target);
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                error_log('DEBUG UPDATE: File upload SUCCESS');
                // Simpan hanya nama file, path akan diatur di view
                $input['gambar'] = $filename;
            } else {
                error_log('DEBUG UPDATE: File upload FAILED');
                $this->error('Gagal upload gambar. Pastikan folder storage/uploads dapat ditulis.', null, 500);
            }
        }
        $result = $this->model->update($id, $input);
        if ($result) $this->success([], 'Sanksi lab updated');
        $this->error('Failed to update sanksi lab', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        $result = $this->model->delete($id);
        if ($result) $this->success([], 'Sanksi lab deleted');
        $this->error('Failed to delete sanksi lab', null, 500);
    }
}
?>
