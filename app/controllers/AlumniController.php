<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AlumniModel.php';

class AlumniController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new AlumniModel();
    }

    /**
     * Halaman publik alumni
     */
    public function index($params = []) {
        $data = $this->model->getAll();
        $this->view('alumni/index', ['alumni' => $data]);
    }

    /**
     * Detail alumni publik
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/alumni');
            return;
        }
        
        $alumni = $this->model->getById($id);
        if (!$alumni) {
            $this->redirect('/alumni');
            return;
        }
        
        $this->view('alumni/detail', ['alumni' => $alumni]);
    }

    /**
     * Halaman admin alumni
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/alumni/index', ['alumni' => $data]);
    }

    /**
     * Form create admin
     */
    public function create($params = []) {
        $this->view('admin/alumni/form', ['alumni' => null, 'action' => 'create']);
    }

    /**
     * Form edit admin
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/alumni');
            return;
        }
        
        $alumni = $this->model->getById($id);
        if (!$alumni) {
            $this->setFlash('error', 'Data alumni tidak ditemukan');
            $this->redirect('/admin/alumni');
            return;
        }
        
        $this->view('admin/alumni/form', ['alumni' => $alumni, 'action' => 'edit']);
    }

    /**
     * API endpoints
     */
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data alumni retrieved successfully');
    }

    public function apiShow($params) {
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
                // Get base path from request - for dynamic folder naming
                $scriptPath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
                $input['foto'] = $scriptPath . '/storage/uploads/' . $filename;
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
                // Get base path from request - for dynamic folder naming
                $scriptPath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
                $input['foto'] = $scriptPath . '/storage/uploads/' . $filename;
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
