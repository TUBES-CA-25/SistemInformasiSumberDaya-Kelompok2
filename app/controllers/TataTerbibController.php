<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/TataTerbibModel.php';

class TataTerbibController extends Controller {
    private $model;
    private $upload_dir = '/public/uploads/tata-tertib/';

    public function __construct() {
        $this->model = new \TataTerbibModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Tata Tertib retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tata tertib tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idTataTerib');
        if (!$data) {
            $this->error('Tata Tertib tidak ditemukan', null, 404);
        }

        $this->success($data, 'Tata Tertib retrieved successfully');
    }

    public function store() {
        try {
            // Cek apakah request multipart/form-data (upload file)
            if (isset($_FILES['gambar'])) {
                $input = [
                    'namaFile' => $_POST['namaFile'] ?? '',
                    'uraFile' => $_POST['uraFile'] ?? ''
                ];
            } else {
                // Coba ambil dari $_POST dulu, jika kosong ambil dari JSON
                $input = $_POST;
                if (empty($input)) {
                    $input = $this->getJson() ?? [];
                }
            }
            
            // Validasi field wajib
            if (empty($input['namaFile'])) {
                $this->error('Field namaFile wajib diisi', null, 400);
            }
            
            // Optional: handle file upload for gambar
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $filename = 'peraturan_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                    // Simpan hanya nama file
                    $input['gambar'] = $filename;
                } else {
                    $this->error('Gagal upload gambar', null, 500);
                }
            }
            
            $result = $this->model->insert($input);
            if ($result) {
                $this->success(['id' => $this->model->getLastInsertId()], 'Tata Tertib created successfully', 201);
            }
            $this->error('Failed to create tata tertib', null, 500);
        } catch (Exception $e) {
            error_log('ERROR in TataTerbibController::store - ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID tata tertib tidak ditemukan', null, 400);
            }

            $existing = $this->model->getById($id, 'idTataTerib');
            if (!$existing) {
                $this->error('Tata Tertib tidak ditemukan', null, 404);
            }

            // Cek apakah request multipart/form-data (upload file)
            if (isset($_FILES['gambar'])) {
                $input = [
                    'namaFile' => $_POST['namaFile'] ?? $existing['namaFile'],
                    'uraFile' => $_POST['uraFile'] ?? $existing['uraFile']
                ];
            } else {
                // Ambil dari $_POST (karena sekarang gunakan POST untuk update dengan file)
                $input = [
                    'namaFile' => $_POST['namaFile'] ?? $existing['namaFile'],
                    'uraFile' => $_POST['uraFile'] ?? $existing['uraFile']
                ];
            }
            
            // Validasi field wajib
            if (empty($input['namaFile'])) {
                $this->error('Field namaFile wajib diisi', null, 400);
            }
            
            // Optional: handle file upload for gambar
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                // Hapus gambar lama jika ada
                if (isset($existing['gambar']) && !empty($existing['gambar'])) {
                    $oldImagePath = $uploadDir . $existing['gambar'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // Upload gambar baru
                $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $filename = 'peraturan_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                    // Simpan hanya nama file
                    $input['gambar'] = $filename;
                } else {
                    $this->error('Gagal upload gambar', null, 500);
                }
            }
            
            $result = $this->model->update($id, $input, 'idTataTerib');
            
            if ($result) {
                $this->success([], 'Tata Tertib updated successfully');
            }
            $this->error('Failed to update tata tertib', null, 500);
        } catch (Exception $e) {
            error_log('ERROR in TataTerbibController::update - ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tata tertib tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idTataTerib');
        if (!$existing) {
            $this->error('Tata Tertib tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idTataTerib');
        
        if ($result) {
            $this->success([], 'Tata Tertib deleted successfully');
        }
        $this->error('Failed to delete tata tertib', null, 500);
    }
}
?>
