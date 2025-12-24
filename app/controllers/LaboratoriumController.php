<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';
require_once __DIR__ . '/../models/AsistenModel.php';

class LaboratoriumController extends Controller {
    private $model;
    private $asistenModel;

    public function __construct() {
        $this->model = new \LaboratoriumModel();
        $this->asistenModel = new \AsistenModel();
    }

    // API methods
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Laboratorium retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'idLaboratorium');
        if (!$data) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Laboratorium retrieved successfully');
    }

    // Admin view methods
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/laboratorium/index', ['laboratorium' => $data]);
    }

    public function detail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $lab = $this->model->getById($id, 'idLaboratorium');
        if (!$lab) {
            $this->setFlash('error', 'Data laboratorium tidak ditemukan');
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $this->view('admin/laboratorium/detail', ['laboratorium' => $lab]);
    }

    public function create($params = []) {
        $this->view('admin/laboratorium/form', ['laboratorium' => null, 'action' => 'create']);
    }

    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $lab = $this->model->getById($id, 'idLaboratorium');
        if (!$lab) {
            $this->setFlash('error', 'Data laboratorium tidak ditemukan');
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $this->view('admin/laboratorium/form', ['laboratorium' => $lab, 'action' => 'edit']);
    }

    // Web view methods
    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Laboratorium retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idLaboratorium');
        if (!$data) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
        }

        $this->success($data, 'Laboratorium retrieved successfully');
    }

    public function store() {
        try {
            // Ambil data dari POST (multipart/form-data)
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'jumlahPc' => $_POST['jumlahPc'] ?? 0,
                'idKordinatorAsisten' => $_POST['idKordinatorAsisten'] ?? null
            ];
            
            // Validasi field wajib
            if (empty($input['nama'])) {
                $this->error('Nama laboratorium wajib diisi', null, 400);
                return;
            }
            
            // Handle file upload jika ada
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                    return;
                }
                
                $filename = 'lab_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                    $input['gambar'] = $filename;
                } else {
                    $this->error('Gagal mengupload file', null, 500);
                    return;
                }
            }
            
            $result = $this->model->insert($input);
            if ($result) {
                $this->success(['id' => $this->model->getLastInsertId()], 'Laboratorium berhasil ditambahkan', 201);
            } else {
                $error_msg = 'Gagal menambahkan laboratorium';
                if ($this->model->db) {
                    $error_msg .= ' - DB Error: ' . $this->model->db->error;
                }
                $this->error($error_msg, null, 500);
            }
        } catch (Exception $e) {
            error_log('Laboratorium store error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID laboratorium tidak ditemukan', null, 400);
                return;
            }

            $existing = $this->model->getById($id, 'idLaboratorium');
            if (!$existing) {
                $this->error('Laboratorium tidak ditemukan', null, 404);
                return;
            }

            // Ambil data dari POST (multipart/form-data)
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'jumlahPc' => $_POST['jumlahPc'] ?? 0,
                'idKordinatorAsisten' => $_POST['idKordinatorAsisten'] ?? null
            ];
            
            // Validasi field wajib
            if (empty($input['nama'])) {
                $this->error('Nama laboratorium wajib diisi', null, 400);
                return;
            }
            
            // Handle file upload jika ada file baru
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                    return;
                }
                
                // Delete old file if exists
                if (!empty($existing['gambar'])) {
                    $oldFile = $uploadDir . $existing['gambar'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                
                $filename = 'lab_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                    $input['gambar'] = $filename;
                } else {
                    $this->error('Gagal mengupload file', null, 500);
                    return;
                }
            }
            
            $result = $this->model->update($id, $input, 'idLaboratorium');
            
            if ($result) {
                $this->success([], 'Laboratorium berhasil diupdate', 200);
            } else {
                $this->error('Gagal mengupdate laboratorium', null, 500);
            }
        } catch (Exception $e) {
            error_log('Laboratorium update error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idLaboratorium');
        if (!$existing) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idLaboratorium');
        
        if ($result) {
            $this->success([], 'Laboratorium deleted successfully');
        }
        $this->error('Failed to delete laboratorium', null, 500);
    }
}
?>
