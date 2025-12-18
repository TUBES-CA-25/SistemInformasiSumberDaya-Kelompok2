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
        
        $alumni = $this->model->getById($id, 'id');
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
        
        $alumni = $this->model->getById($id, 'id');
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
        $data = $this->model->getById($id, 'id');
        if (!$data) $this->error('Data tidak ditemukan', null, 404);
        $this->success($data, 'Alumni retrieved successfully');
    }

    public function store() {
        try {
            // Ambil data dari POST (multipart/form-data atau regular)
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
            
            // Validasi field wajib
            if (empty($input['nama']) || empty($input['angkatan'])) {
                $this->error('Field nama dan angkatan wajib diisi', null, 400);
                return;
            }
            
            // Handle file upload jika ada
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                    return;
                }
                
                $filename = 'alumni_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $input['foto'] = $filename;
                } else {
                    $this->error('Gagal mengupload file', null, 500);
                    return;
                }
            }
            
            $result = $this->model->insert($input);
            if ($result) {
                $this->success(['id' => $this->model->getLastInsertId()], 'Alumni berhasil ditambahkan', 201);
            } else {
                $this->error('Gagal menambahkan alumni', null, 500);
            }
        } catch (Exception $e) {
            error_log('Alumni store error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID tidak ditemukan', null, 400);
                return;
            }
            
            // Get existing data for old file deletion
            $alumni = $this->model->getById($id, 'id');
            if (!$alumni) {
                $this->error('Alumni tidak ditemukan', null, 404);
                return;
            }
            
            // Ambil data dari POST (multipart/form-data atau regular)
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
            
            // Validasi field wajib
            if (empty($input['nama']) || empty($input['angkatan'])) {
                $this->error('Field nama dan angkatan wajib diisi', null, 400);
                return;
            }
            
            // Handle file upload jika ada file baru
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                    return;
                }
                
                // Delete old file if exists
                if (!empty($alumni['foto'])) {
                    $oldFile = $uploadDir . $alumni['foto'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                
                $filename = 'alumni_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $input['foto'] = $filename;
                } else {
                    $this->error('Gagal mengupload file', null, 500);
                    return;
                }
            }
            
            $result = $this->model->update($id, $input, 'id');
            if ($result) {
                $this->success([], 'Alumni berhasil diupdate', 200);
            } else {
                $this->error('Gagal mengupdate data alumni', null, 500);
            }
        } catch (Exception $e) {
            error_log('Alumni update error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        $result = $this->model->delete($id, 'id');
        if ($result) $this->success([], 'Alumni deleted');
        $this->error('Failed to delete alumni', null, 500);
    }
}
?>
