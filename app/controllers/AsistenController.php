<?php
namespace App\Controllers;

use Exception;

require_once __DIR__ . '/../models/AsistenModel.php';

class AsistenController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \AsistenModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Asisten retrieved successfully');
    }

    public function show($params) {
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
            // Cek apakah request multipart/form-data (upload file)
            if (isset($_FILES['foto'])) {
                $input = [
                    'nama' => $_POST['nama'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'jurusan' => $_POST['jurusan'] ?? '',
                    'statusAktif' => $_POST['statusAktif'] ?? 1
                ];

                $required = ['nama', 'email'];
                $missing = $this->validateRequired($input, $required);
                if (!empty($missing)) {
                    $this->error('Field required: ' . implode(', ', $missing), null, 400);
                }

                $existing = $this->model->getAsistenByEmail($input['email']);
                if ($existing) {
                    $this->error('Email sudah terdaftar', null, 400);
                }

                // Proses upload file
                $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $file = $_FILES['foto'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'asisten_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                    $target = $uploadDir . $filename;
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        // Simpan hanya nama file, path akan diatur di view
                        $input['foto'] = $filename;
                    } else {
                        $input['foto'] = '';
                    }
                } else {
                    $input['foto'] = '';
                }

                $result = $this->model->insert($input);
                if ($result) {
                    $this->success(['id' => $this->model->getLastInsertId()], 'Asisten created successfully', 201);
                }
                $this->error('Failed to create asisten', null, 500);
            } else {
                // Fallback: JSON (API lama)
                $input = $this->getJson();
                $required = ['nama', 'email'];
                $missing = $this->validateRequired($input, $required);
                if (!empty($missing)) {
                    $this->error('Field required: ' . implode(', ', $missing), null, 400);
                }
                $existing = $this->model->getAsistenByEmail($input['email']);
                if ($existing) {
                    $this->error('Email sudah terdaftar', null, 400);
                }
                $result = $this->model->insert($input);
                if ($result) {
                    $this->success(['id' => $this->model->getLastInsertId()], 'Asisten created successfully', 201);
                }
                $this->error('Failed to create asisten', null, 500);
            }
        } catch (\Exception $e) {
            error_log('ERROR in AsistenController::store - ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID asisten tidak ditemukan', null, 400);
            }

            $existing = $this->model->getById($id, 'idAsisten');
            if (!$existing) {
                $this->error('Asisten tidak ditemukan', null, 404);
            }

            // Cek apakah request multipart/form-data (upload file)
            if (isset($_FILES['foto'])) {
                $input = [
                    'nama' => $_POST['nama'] ?? $existing['nama'],
                    'email' => $_POST['email'] ?? $existing['email'],
                    'jurusan' => $_POST['jurusan'] ?? $existing['jurusan'],
                    'statusAktif' => $_POST['statusAktif'] ?? $existing['statusAktif']
                ];
            } else {
                // Ambil dari $_POST atau JSON
                $input = $_POST;
                if (empty($input)) {
                    $input = $this->getJson() ?? [];
                }
                // Jika field kosong, gunakan data lama
                if (empty($input['nama'])) $input['nama'] = $existing['nama'];
                if (empty($input['email'])) $input['email'] = $existing['email'];
                if (empty($input['jurusan'])) $input['jurusan'] = $existing['jurusan'];
                if (empty($input['statusAktif'])) $input['statusAktif'] = $existing['statusAktif'];
            }

            // Optional: handle file upload
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                // Hapus foto lama jika ada
                if (isset($existing['foto']) && !empty($existing['foto'])) {
                    $oldImagePath = $uploadDir . $existing['foto'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // Upload foto baru
                $file = $_FILES['foto'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'asisten_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    // Simpan hanya nama file
                    $input['foto'] = $filename;
                } else {
                    $this->error('Gagal upload foto', null, 500);
                }
            }

            $result = $this->model->update($id, $input, 'idAsisten');
            
            if ($result) {
                // Jika ada perubahan isKoordinator ke 1, reset semua koordinator lain ke 0
                if (!empty($_POST['isKoordinator']) && $_POST['isKoordinator'] == 1) {
                    // Reset semua asisten lain menjadi bukan koordinator
                    $db = $this->model->getDb();
                    $resetQuery = "UPDATE Asisten SET isKoordinator = 0 WHERE idAsisten != ?";
                    $stmt = $db->prepare($resetQuery);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->close();
                }
                
                $this->success([], 'Asisten updated successfully');
            }
            $this->error('Failed to update asisten', null, 500);
        } catch (\Exception $e) {
            error_log('ERROR in AsistenController::update - ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idAsisten');
        if (!$existing) {
            $this->error('Asisten tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idAsisten');
        
        if ($result) {
            $this->success([], 'Asisten deleted successfully');
        }
        $this->error('Failed to delete asisten', null, 500);
    }

    public function matakuliah($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
        }

        $data = $this->model->getAsistenMatakuliah($id);
        $this->success($data, 'Matakuliah asisten retrieved successfully');
    }
}
?>
