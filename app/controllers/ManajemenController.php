<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class ManajemenController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new ManajemenModel();
    }

    /**
     * Halaman admin manajemen
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/manajemen/index', ['manajemen' => $data]);
    }

    /**
     * Form create admin
     */
    public function create($params = []) {
        $this->view('admin/manajemen/form', ['manajemen' => null, 'action' => 'create']);
    }

    /**
     * Form edit admin
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/manajemen');
            return;
        }
        
        $manajemen = $this->model->getById($id, 'idManajemen');
        if (!$manajemen) {
            $this->setFlash('error', 'Data manajemen tidak ditemukan');
            $this->redirect('/admin/manajemen');
            return;
        }
        
        $this->view('admin/manajemen/form', ['manajemen' => $manajemen, 'action' => 'edit']);
    }

    /**
     * API endpoints
     */
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Manajemen retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID manajemen tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'idManajemen');
        if (!$data) {
            $this->error('Manajemen tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Manajemen retrieved successfully');
    }

    // Legacy index/show for backwards compatibility
    public function index() {
        return $this->apiIndex();
    }

    public function show($params) {
        return $this->apiShow($params);
    }

    public function store() {
        try {
            // Check if multipart/form-data (file upload)
            if (isset($_FILES['foto'])) {
                $input = [
                    'nama' => $_POST['nama'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'nidn' => $_POST['nidn'] ?? null,
                    'jabatan' => $_POST['jabatan'] ?? '',
                    'tentang' => $_POST['tentang'] ?? ''
                ];

                $required = ['nama', 'email', 'jabatan'];
                $missing = $this->validateRequired($input, $required);
                if (!empty($missing)) {
                    $this->error('Field required: ' . implode(', ', $missing), null, 400);
                }

                // Process file upload (store in public assets so images are web-accessible)
                $subFolder = 'manajemen/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $file = $_FILES['foto'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = Helper::generateFilename('manajemen', $input['nama'], $ext);
                    $target = $uploadDir . $filename;
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $input['foto'] = $subFolder . $filename;
                    } else {
                        $input['foto'] = '';
                    }
                } else {
                    $input['foto'] = '';
                }

                $result = $this->model->insert($input);
                if ($result) {
                    $this->success(['id' => $this->model->getLastInsertId()], 'Manajemen created successfully', 201);
                }
                $this->error('Failed to create manajemen', null, 500);
            } else {
                // Fallback: JSON atau POST tanpa file
                $input = $this->getJson() ?? $_POST;
                $required = ['nama', 'email', 'jabatan'];
                $missing = $this->validateRequired($input, $required);
                if (!empty($missing)) {
                    $this->error('Field required: ' . implode(', ', $missing), null, 400);
                }
                $result = $this->model->insert($input);
                if ($result) {
                    $this->success(['id' => $this->model->getLastInsertId()], 'Manajemen created successfully', 201);
                }
                $this->error('Failed to create manajemen', null, 500);
            }
        } catch (\Exception $e) {
            error_log('ERROR in ManajemenController::store - ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID manajemen tidak ditemukan', null, 400);
            }

            $existing = $this->model->getById($id, 'idManajemen');
            if (!$existing) {
                $this->error('Manajemen tidak ditemukan', null, 404);
            }

            // Check if multipart/form-data (file upload)
            if (isset($_FILES['foto'])) {
                $input = [
                    'nama' => $_POST['nama'] ?? $existing['nama'],
                    'email' => $_POST['email'] ?? $existing['email'],
                    'nidn' => $_POST['nidn'] ?? $existing['nidn'],
                    'jabatan' => $_POST['jabatan'] ?? $existing['jabatan'],
                    'tentang' => $_POST['tentang'] ?? $existing['tentang'] ?? ''
                ];
            } else {
                $input = $_POST;
                if (empty($input)) {
                    $input = $this->getJson() ?? [];
                }
                if (empty($input['nama'])) $input['nama'] = $existing['nama'];
                if (empty($input['email'])) $input['email'] = $existing['email'] ?? '';
                if (empty($input['jabatan'])) $input['jabatan'] = $existing['jabatan'];
            }

            // Handle file upload
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $subFolder = 'manajemen/';
                $baseUploadPath = dirname(__DIR__, 2) . '/public/assets/uploads/';
                $uploadDir = $baseUploadPath . $subFolder;
                
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }
                
                // Delete old foto if exists
                if (isset($existing['foto']) && !empty($existing['foto'])) {
                    $oldFilePath = $existing['foto'];
                    // Cek di root atau subfolder
                    $fullOldPath = $baseUploadPath . $oldFilePath;
                    if (file_exists($fullOldPath) && is_file($fullOldPath)) {
                        @unlink($fullOldPath);
                    }
                }
                
                // Upload new foto
                $file = $_FILES['foto'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $filename = Helper::generateFilename('manajemen', $input['nama'], $ext);
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $input['foto'] = $subFolder . $filename;
                } else {
                    $this->error('Gagal upload foto ke ' . $subFolder, null, 500);
                    return;
                }
            }

            $result = $this->model->update($id, $input, 'idManajemen');
            
            if ($result) {
                $this->success([], 'Manajemen updated successfully');
            }
            $this->error('Failed to update manajemen', null, 500);
        } catch (\Exception $e) {
            error_log('ERROR in ManajemenController::update - ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID manajemen tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idManajemen');
        if (!$existing) {
            $this->error('Manajemen tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idManajemen');
        
        if ($result) {
            $this->success([], 'Manajemen deleted successfully');
        }
        $this->error('Failed to delete manajemen', null, 500);
    }

    /**
     * Public Display Methods (from KepalaLabController)
     */
    
    /**
     * Halaman publik daftar kepala lab
     */
    public function kepalaIndex($params = []) {
        $all = $this->model->getAll();
        $data = ['manajemen' => $all];
        $this->view('sumberdaya/kepala', $data);
    }
    
    /**
     * Detail profil pimpinan/laboran (legacy view, MVC route)
     */
    public function kepalaDetail($params = []) {
        if (!empty($params['id'])) {
            $_GET['id'] = $params['id'];
        }
        $this->view('sumberdaya/detail-asisten');
    }
}
