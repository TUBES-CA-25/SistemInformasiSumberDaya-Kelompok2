<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/AlumniModel.php';

class AlumniController extends Controller {
    private $model;
    public function __construct() {
        $this->model = new \AlumniModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data alumni retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        $data = $this->model->getById($id);
        if (!$data) $this->error('Data tidak ditemukan', null, 404);
        $this->success($data, 'Alumni retrieved successfully');
    }

    public function store() {
        // Cek apakah request multipart/form-data (upload file)
        if (isset($_FILES['foto'])) {
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'angkatan' => $_POST['angkatan'] ?? '',
                'divisi' => $_POST['divisi'] ?? '',
                'pekerjaan' => $_POST['pekerjaan'] ?? '',
                'perusahaan' => $_POST['perusahaan'] ?? '',
                'kesan_pesan' => $_POST['kesan_pesan'] ?? '',
                'tahun_lulus' => $_POST['tahun_lulus'] ?? '',
                'keahlian' => $_POST['keahlian'] ?? '',
                'linkedin' => $_POST['linkedin'] ?? '',
                'portfolio' => $_POST['portfolio'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];
        } else {
            // Coba ambil dari $_POST dulu, jika kosong ambil dari JSON
            $input = $_POST;
            if (empty($input)) {
                $input = $this->getJson() ?? [];
            }
        }
        
        // Validasi field wajib
        if (empty($input['nama']) || empty($input['angkatan'])) {
            $this->error('Field nama dan angkatan wajib diisi', null, 400);
        }
        
        // Optional: handle file upload for foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $filename = 'alumni_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                $input['foto'] = '/SistemManagementSumberDaya/storage/uploads/' . $filename;
            }
        }
        $result = $this->model->insert($input);
        if ($result) $this->success([], 'Alumni created', 201);
        $this->error('Failed to create alumni', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        
        // Cek apakah request multipart/form-data (upload file)
        if (isset($_FILES['foto'])) {
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'angkatan' => $_POST['angkatan'] ?? '',
                'divisi' => $_POST['divisi'] ?? '',
                'pekerjaan' => $_POST['pekerjaan'] ?? '',
                'perusahaan' => $_POST['perusahaan'] ?? '',
                'kesan_pesan' => $_POST['kesan_pesan'] ?? '',
                'tahun_lulus' => $_POST['tahun_lulus'] ?? '',
                'keahlian' => $_POST['keahlian'] ?? '',
                'linkedin' => $_POST['linkedin'] ?? '',
                'portfolio' => $_POST['portfolio'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];
        } else {
            // Coba ambil dari $_POST dulu, jika kosong ambil dari JSON
            $input = $_POST;
            if (empty($input)) {
                $input = $this->getJson() ?? [];
            }
        }
        
        // Validasi field wajib
        if (empty($input['nama']) || empty($input['angkatan'])) {
            $this->error('Field nama dan angkatan wajib diisi', null, 400);
        }
        
        // Optional: handle file upload for foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $filename = 'alumni_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                $input['foto'] = '/SistemManagementSumberDaya/storage/uploads/' . $filename;
            }
        }
        $result = $this->model->update($id, $input);
        if ($result) $this->success([], 'Alumni updated');
        $this->error('Failed to update alumni', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        $result = $this->model->delete($id);
        if ($result) $this->success([], 'Alumni deleted');
        $this->error('Failed to delete alumni', null, 500);
    }
}
?>
