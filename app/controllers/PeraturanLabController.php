<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/PeraturanLabModel.php';

class PeraturanLabController extends Controller {
    private $model;
    public function __construct() {
        $this->model = new \PeraturanLabModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data peraturan lab retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        $data = $this->model->getById($id);
        if (!$data) $this->error('Data tidak ditemukan', null, 404);
        $this->success($data, 'Peraturan lab retrieved successfully');
    }

    public function store() {
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
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $filename = 'peraturan_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                $scriptPath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
                $input['gambar'] = $scriptPath . '/storage/uploads/' . $filename;
            }
        }
        $result = $this->model->insert($input);
        if ($result) $this->success([], 'Peraturan lab created', 201);
        $this->error('Failed to create peraturan lab', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        
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
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $filename = 'peraturan_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                $scriptPath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
                $input['gambar'] = $scriptPath . '/storage/uploads/' . $filename;
            }
        }
        $result = $this->model->update($id, $input);
        if ($result) $this->success([], 'Peraturan lab updated');
        $this->error('Failed to update peraturan lab', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        $result = $this->model->delete($id);
        if ($result) $this->success([], 'Peraturan lab deleted');
        $this->error('Failed to delete peraturan lab', null, 500);
    }
}
?>
