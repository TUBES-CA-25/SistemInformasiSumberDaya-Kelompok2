<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';
require_once __DIR__ . '/../models/AsistenModel.php';
require_once __DIR__ . '/../models/LaboratoriumGambarModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class LaboratoriumController extends Controller {
    private $model;
    private $asistenModel;
    private $gambarModel;

    public function __construct() {
        $this->model = new \LaboratoriumModel();
        $this->asistenModel = new \AsistenModel();
        $this->gambarModel = new \LaboratoriumGambarModel();
    }

    // API methods
    public function apiIndex() {
        $data = $this->model->getAll();
        
        // Load images for each laboratorium
        foreach ($data as &$lab) {
            $lab['images'] = $this->gambarModel->getByLaboratorium($lab['idLaboratorium']);
        }
        
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

        // Add images array
        $data['images'] = $this->gambarModel->getByLaboratorium($id);

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

    public function denah() {
        $data['judul'] = 'Denah Lokasi & Tata Letak';
        
        $this->view('fasilitas/denah', $data);

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
                'jenis' => $_POST['jenis'] ?? 'Laboratorium',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'jumlahPc' => $_POST['jumlahPc'] ?? 0,
                'kapasitas' => $_POST['kapasitas'] ?? 0,
                'processor' => $_POST['processor'] ?? '',
                'ram' => $_POST['ram'] ?? '',
                'storage' => $_POST['storage'] ?? '',
                'gpu' => $_POST['gpu'] ?? '',
                'monitor' => $_POST['monitor'] ?? '',
                'software' => $_POST['software'] ?? '',
                'fasilitas_pendukung' => $_POST['fasilitas'] ?? '',
                'idKordinatorAsisten' => !empty($_POST['idKordinatorAsisten']) ? $_POST['idKordinatorAsisten'] : null
            ];
            
            // Validasi field wajib
            if (empty($input['nama'])) {
                $this->error('Nama laboratorium wajib diisi', null, 400);
                return;
            }
            
            // Handle multiple file uploads
            $uploadedImages = [];
            if (isset($_FILES['gambar']) && is_array($_FILES['gambar']['name'])) {
                $subFolder = 'laboratorium/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileCount = count($_FILES['gambar']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['gambar']['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['gambar']['name'][$i], PATHINFO_EXTENSION));
                        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (!in_array($ext, $allowedExts)) {
                            $this->error('Format file tidak didukung pada file ' . $_FILES['gambar']['name'][$i] . '. Gunakan: jpg, jpeg, png, gif', null, 400);
                            return;
                        }
                        
                        $filename = Helper::generateFilename('lab', $input['nama'], $ext);
                        $target = $uploadDir . $filename;
                        
                        if (move_uploaded_file($_FILES['gambar']['tmp_name'][$i], $target)) {
                            $uploadedImages[] = [
                                'filename' => $subFolder . $filename,
                                'description' => $_POST['gambar_desc'][$i] ?? null
                            ];
                        } else {
                            $this->error('Gagal mengupload file: ' . $_FILES['gambar']['name'][$i], null, 500);
                            return;
                        }
                    }
                }
                
                // Set gambar pertama sebagai utama jika ada
                if (!empty($uploadedImages)) {
                    $input['gambar'] = $uploadedImages[0]['filename'];
                }
            }
            
            // Simpan ke Database
            $result = $this->model->insert($input);
            
            if ($result) {
                $lastId = $this->model->getLastInsertId();
                
                // Simpan uploaded images ke tabel laboratorium_gambar
                if (!empty($uploadedImages)) {
                    foreach ($uploadedImages as $index => $image) {
                        $isUtama = ($index === 0) ? 1 : 0;
                        $this->gambarModel->insertImage(
                            $lastId,
                            $image['filename'],
                            $image['description'],
                            $isUtama,
                            $index
                        );
                    }
                }
                
                $this->success(['id' => $lastId], 'Laboratorium berhasil ditambahkan', 201);
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
                'nama' => $_POST['nama'] ?? $existing['nama'],
                'jenis' => $_POST['jenis'] ?? ($existing['jenis'] ?? 'Laboratorium'),
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'jumlahPc' => $_POST['jumlahPc'] ?? 0,
                'kapasitas' => $_POST['kapasitas'] ?? 0,
                'processor' => $_POST['processor'] ?? '',
                'ram' => $_POST['ram'] ?? '',
                'storage' => $_POST['storage'] ?? '',
                'gpu' => $_POST['gpu'] ?? '',
                'monitor' => $_POST['monitor'] ?? '',
                'software' => $_POST['software'] ?? '',
                'fasilitas_pendukung' => $_POST['fasilitas'] ?? '',
                'idKordinatorAsisten' => !empty($_POST['idKordinatorAsisten']) ? $_POST['idKordinatorAsisten'] : null
            ];
            
            // Validasi field wajib
            if (empty($input['nama'])) {
                $this->error('Nama laboratorium wajib diisi', null, 400);
                return;
            }
            
            // Handle multiple file uploads
            $uploadedImages = [];
            if (isset($_FILES['gambar']) && is_array($_FILES['gambar']['name'])) {
                $subFolder = 'laboratorium/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileCount = count($_FILES['gambar']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['gambar']['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['gambar']['name'][$i], PATHINFO_EXTENSION));
                        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (!in_array($ext, $allowedExts)) {
                            $this->error('Format file tidak didukung pada file ' . $_FILES['gambar']['name'][$i] . '. Gunakan: jpg, jpeg, png, gif', null, 400);
                            return;
                        }
                        
                        $filename = Helper::generateFilename('lab', $input['nama'], $ext);
                        $target = $uploadDir . $filename;
                        
                        if (move_uploaded_file($_FILES['gambar']['tmp_name'][$i], $target)) {
                            $uploadedImages[] = [
                                'filename' => $subFolder . $filename,
                                'description' => $_POST['gambar_desc'][$i] ?? null
                            ];
                        } else {
                            $this->error('Gagal mengupload file: ' . $_FILES['gambar']['name'][$i], null, 500);
                            return;
                        }
                    }
                }
                
                // Set gambar pertama sebagai utama jika ada
                if (!empty($uploadedImages)) {
                    $input['gambar'] = $uploadedImages[0]['filename'];
                }
            }
            
            // Update Database
            $result = $this->model->update($id, $input, 'idLaboratorium');
            
            if ($result) {
                // Jika ada file baru, tambahkan ke tabel laboratorium_gambar
                if (!empty($uploadedImages)) {
                    foreach ($uploadedImages as $index => $image) {
                        $isUtama = ($index === 0) ? 1 : 0;
                        $this->gambarModel->insertImage(
                            $id,
                            $image['filename'],
                            $image['description'],
                            $isUtama,
                            $index
                        );
                    }
                }
                
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

        // Cek apakah lab masih digunakan di jadwal praktikum
        $db = new Database();
        $mysqli = method_exists($db, 'getConnection') ? $db->getConnection() : $db->getPdo();
        
        // Query untuk check jadwal praktikum
        if (is_object($mysqli) && get_class($mysqli) === 'mysqli') {
            // MySQLi
            $stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM jadwalpraktikum WHERE idLaboratorium = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $jadwalCount = $result['count'] ?? 0;
        } else {
            // PDO
            $stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM jadwalpraktikum WHERE idLaboratorium = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $jadwalCount = $result['count'] ?? 0;
        }

        if ($jadwalCount > 0) {
            $this->error("Laboratorium tidak bisa dihapus karena masih digunakan dalam $jadwalCount jadwal praktikum. Silakan hapus jadwal tersebut terlebih dahulu.", null, 400);
            return;
        }

        $result = $this->model->delete($id, 'idLaboratorium');
        
        if ($result) {
            $this->success([], 'Laboratorium deleted successfully');
        }
        $this->error('Failed to delete laboratorium', null, 500);
    }

    /**
     * Delete specific image only
     */
    public function deleteImage($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID gambar tidak ditemukan', null, 400);
            return;
        }

        try {
            $gambar = $this->gambarModel->getById($id, 'idGambar');
            if (!$gambar) {
                $this->error('Data gambar tidak ditemukan di database (ID: ' . $id . ')', null, 404);
                return;
            }

            // Hapus file fisik jika ada
            $filename = $gambar['namaGambar'];
            $rootUploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
            
            // Cek di root (jika data lama) atau di subfolder (jika data baru dengan prefix)
            $filePath = $rootUploadDir . $filename;
            
            if (file_exists($filePath)) {
                @unlink($filePath);
            } else {
                // Mencoba cek tanpa prefix jika ternyata prefix ditambahkan tapi file ada di root
                $baseName = basename($filename);
                if (file_exists($rootUploadDir . $baseName)) {
                    @unlink($rootUploadDir . $baseName);
                }
            }

            $result = $this->gambarModel->deleteImage($id);
            if ($result) {
                $this->success(['id' => $id], 'Gambar berhasil dihapus');
            } else {
                $this->error('Gagal menghapus record gambar di database', null, 500);
            }
        } catch (Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage(), null, 500);
        }
    }
}
