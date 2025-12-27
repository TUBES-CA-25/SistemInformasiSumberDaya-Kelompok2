<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';

class AsistenController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new AsistenModel();
    }

    /**
     * Halaman publik asisten
     */
    public function index($params = []) {
        $data = $this->model->getAll();
        $this->view('sumberdaya/asisten', ['asisten' => $data]);
    }

    /**
     * Halaman detail asisten
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;
        $this->view('sumberdaya/detail-asisten', ['id' => $id]);
    }

    /**
     * Halaman admin asisten
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/asisten/index', ['asisten' => $data]);
    }

    /**
     * Form create admin
     */
    public function create($params = []) {
        $this->view('admin/asisten/form', ['asisten' => null, 'action' => 'create']);
    }

    /**
     * Form edit admin
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/asisten');
            return;
        }
        
        $asisten = $this->model->getById($id, 'idAsisten');
        if (!$asisten) {
            $this->setFlash('error', 'Data asisten tidak ditemukan');
            $this->redirect('/admin/asisten');
            return;
        }
        
        $this->view('admin/asisten/form', ['asisten' => $asisten, 'action' => 'edit']);
    }

    /**
     * Pilih koordinator
     */
    public function pilihKoordinator($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/asisten/pilih-koordinator', ['asisten' => $data]);
    }

    /**
     * API endpoints
     */
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Asisten retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idAsisten');
        if (!$data) {
            $this->error('Asisten tidak ditemukan', null, 404);
        }
        
        $this->success($data, 'Asisten retrieved successfully');
    }

    public function store() {
        try {
            // Check if this is a file upload (multipart/form-data)
            if (isset($_FILES['foto'])) {
                $input = [
                    'nama' => $_POST['nama'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'jurusan' => $_POST['jurusan'] ?? '',
                    'bio' => $_POST['bio'] ?? '',
                    'skills' => isset($_POST['skills']) ? $_POST['skills'] : '[]', // Expecting JSON string or will process later
                    'statusAktif' => $_POST['statusAktif'] ?? 'Asisten',
                    'isKoordinator' => $_POST['isKoordinator'] ?? '0'
                ];

                // Jika skills dikirim sebagai string dipisah koma, ubah ke JSON array
                if (!empty($input['skills']) && !is_array($input['skills']) && $input['skills'][0] !== '[') {
                    $skillsArray = array_map('trim', explode(',', $input['skills']));
                    $input['skills'] = json_encode($skillsArray);
                } elseif (is_array($input['skills'])) {
                    $input['skills'] = json_encode($input['skills']);
                }

                $required = ['nama', 'email'];
                $missing = $this->validateRequired($input, $required);
                if (!empty($missing)) {
                    $this->error('Field required: ' . implode(', ', $missing), null, 400);
                    return;
                }

                // Process file upload
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $file = $_FILES['foto'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (!in_array($ext, $allowedExts)) {
                        $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                        return;
                    }
                    
                    $filename = 'asisten_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $destination = $uploadDir . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $input['foto'] = $filename;
                    } else {
                        $this->error('Gagal mengupload file', null, 500);
                        return;
                    }
                }
            } else {
                // Regular JSON input
                $input = $this->getJson();
                $required = ['nama', 'email'];
                $missing = $this->validateRequired($input, $required);
                
                if (!empty($missing)) {
                    $this->error('Field required: ' . implode(', ', $missing), null, 400);
                    return;
                }
            }

            $result = $this->model->insert($input);
            if ($result) {
                $this->success(['id' => $this->model->getLastInsertId()], 'Asisten berhasil ditambahkan', 201);
            } else {
                $this->error('Gagal menambahkan asisten', null, 500);
            }
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID asisten tidak ditemukan', null, 400);
                return;
            }

            $asisten = $this->model->getById($id, 'idAsisten');
            if (!$asisten) {
                $this->error('Asisten tidak ditemukan', null, 404);
                return;
            }

            // Check if this is a file upload (multipart/form-data)
            if (isset($_FILES['foto'])) {
                $input = [
                    'nama' => $_POST['nama'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'jurusan' => $_POST['jurusan'] ?? '',
                    'bio' => $_POST['bio'] ?? '',
                    'skills' => isset($_POST['skills']) ? $_POST['skills'] : '[]',
                    'statusAktif' => $_POST['statusAktif'] ?? 'Asisten',
                    'isKoordinator' => $_POST['isKoordinator'] ?? '0'
                ];

                // Jika skills dikirim sebagai string dipisah koma, ubah ke JSON array
                if (!empty($input['skills']) && !is_array($input['skills']) && $input['skills'][0] !== '[') {
                    $skillsArray = array_map('trim', explode(',', $input['skills']));
                    $input['skills'] = json_encode($skillsArray);
                } elseif (is_array($input['skills'])) {
                    $input['skills'] = json_encode($input['skills']);
                }

                // Process file upload if new file provided
                $file = $_FILES['foto'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (!in_array($ext, $allowedExts)) {
                        $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                        return;
                    }
                    
                    // Delete old file if exists
                    if (!empty($asisten['foto'])) {
                        $oldFile = $uploadDir . $asisten['foto'];
                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                    
                    $filename = 'asisten_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $destination = $uploadDir . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $input['foto'] = $filename;
                    } else {
                        $this->error('Gagal mengupload file', null, 500);
                        return;
                    }
                }
            } else {
                // Regular JSON input
                $input = $this->getJson();
            }

            $result = $this->model->update($id, $input, 'idAsisten');
            if ($result) {
                $this->success(null, 'Asisten berhasil diupdate', 200);
            } else {
                $this->error('Gagal mengupdate asisten', null, 500);
            }
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
            return;
        }

        $asisten = $this->model->getById($id, 'idAsisten');
        if (!$asisten) {
            $this->error('Asisten tidak ditemukan', null, 404);
            return;
        }

        // Delete foto file if exists
        if (!empty($asisten['foto'])) {
            $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
            $fotoPath = $uploadDir . $asisten['foto'];
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }

        $result = $this->model->delete($id, 'idAsisten');
        if ($result) {
            $this->success(null, 'Asisten berhasil dihapus', 200);
        } else {
            $this->error('Gagal menghapus asisten', null, 500);
        }
    }

    /**
     * Set koordinator - unset all, then set selected one
     */
    public function setKoordinator($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID asisten tidak ditemukan', null, 400);
                return;
            }

            $asisten = $this->model->getById($id, 'idAsisten');
            if (!$asisten) {
                $this->error('Asisten tidak ditemukan', null, 404);
                return;
            }

            // Step 1: Reset semua koordinator
            $this->model->resetAllKoordinator();

            // Step 2: Set asisten yang dipilih menjadi koordinator
            $result = $this->model->update($id, ['isKoordinator' => 1], 'idAsisten');
            
            if ($result) {
                $this->success(null, 'Koordinator berhasil diperbarui', 200);
            } else {
                $this->error('Gagal mengupdate koordinator', null, 500);
            }
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }
}
?>
