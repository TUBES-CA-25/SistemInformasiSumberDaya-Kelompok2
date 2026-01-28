<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class AsistenController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new AsistenModel();
    }

    /**
     * Halaman Publik: Daftar Asisten
     * [UPDATE] Logika pemisahan data & URL Foto dipindah ke sini (MVC Pattern).
     */
    public function index($params = []) {
        // 1. Ambil SEMUA data mentah dari Model
        // (Pastikan model mengambil semua data tanpa filter WHERE statusAktif)
        $all_data = $this->model->getAll(); 
        
        // 2. Siapkan Wadah
        $koordinator_list = [];
        $asisten_list     = [];
        $ca_list          = [];
        $alumni_list      = [];

        // 3. Logic: Filter Data & Proses Foto
        if (!empty($all_data)) {
            foreach ($all_data as $row) {
                // A. Proses URL Foto di Controller (Bukan di View)
                $row['foto_url'] = $this->processPhotoUrl($row);
                
                // B. Normalisasi Status
                $status = strtolower($row['statusAktif'] ?? ''); 
                $isCoord = $row['isKoordinator'] ?? 0;

                // C. Pengelompokan
                if ($isCoord == 1) {
                    $koordinator_list[] = $row;
                }
                elseif ($status == 'ca' || strpos($status, 'calon') !== false) {
                    $ca_list[] = $row;
                }
                elseif ($status == 'alumni') {
                    $alumni_list[] = $row;
                }
                // Default: Masukkan ke Asisten Praktikum jika bukan kategori di atas
                else {
                    $asisten_list[] = $row;
                }
            }
        }

        // 4. Kirim Data Matang ke View
        $data = [
            'judul'       => 'Asisten Laboratorium',
            'koordinator' => $koordinator_list,
            'asisten'     => $asisten_list,
            'ca'          => $ca_list,
            'alumni'      => $alumni_list
        ];

        $this->view('sumberdaya/asisten', $data);
    }

    /**
     * Halaman Publik: Detail Asisten
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) { $this->redirect('/asisten'); return; }

        $asisten = $this->model->getById($id);
        
        if (!$asisten) { $this->redirect('/asisten'); return; }

        // Proses Foto & Skills
        $asisten['foto_url'] = $this->processPhotoUrl($asisten);
        
        $skillsRaw = $asisten['skills'] ?? '[]';
        $skillsList = json_decode($skillsRaw, true);
        if (!is_array($skillsList)) {
            $skillsList = array_map('trim', explode(',', str_replace(['[', ']', '"'], '', $skillsRaw)));
        }
        $asisten['skills_list'] = array_filter($skillsList);

        $this->view('sumberdaya/detail', ['id' => $id, 'asisten' => $asisten]);
    }

    /**
     * HELPER PRIVATE: Memproses URL Foto
     * Logika: UI Avatar -> External URL -> Local Upload -> Legacy Image -> Default
     */
    private function processPhotoUrl($row) {
        $fotoName = $row['foto'] ?? '';
        $namaEnc = urlencode($row['nama'] ?? 'User');
        
        // Default Avatar
        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
            if (strpos($fotoName, 'http') !== false) {
                // Jika URL eksternal
                $imgUrl = $fotoName;
            } else {
                // Cek Folder Uploads (Structure baru)
                $path1 = ROOT_PROJECT . '/public/assets/uploads/' . $fotoName;
                // Cek Folder Images (Legacy structure)
                $path2 = ROOT_PROJECT . '/public/images/asisten/' . $fotoName;

                if (file_exists($path1)) {
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgUrl = $baseUrl . '/assets/uploads/' . $fotoName;
                } elseif (file_exists($path2)) {
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgUrl = $baseUrl . '/images/asisten/' . $fotoName;
                }
            }
        }
        return $imgUrl;
    }

    // =========================================================================
    // ADMIN FUNCTIONS (Sama seperti sebelumnya)
    // =========================================================================

    public function adminIndex($params = []) {
        $data = $this->model->getAllForAdmin(); 
        $this->view('admin/asisten/index', ['asisten' => $data]);
    }

    public function create($params = []) {
        $this->view('admin/asisten/form', ['asisten' => null, 'action' => 'create']);
    }

    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) { $this->redirect('/admin/asisten'); return; }
        
        $asisten = $this->model->getById($id);
        if (!$asisten) {
            $this->setFlash('error', 'Data asisten tidak ditemukan');
            $this->redirect('/admin/asisten');
            return;
        }
        
        // Decode skills for form
        $skillsRaw = $asisten['skills'] ?? '[]';
        $skillsArr = json_decode($skillsRaw, true);
        if(!is_array($skillsArr)) {
             $skillsArr = array_map('trim', explode(',', str_replace(['[', ']', '"'], '', $skillsRaw)));
        }
        $asisten['skills_array'] = $skillsArr;
        
        $this->view('admin/asisten/form', ['asisten' => $asisten, 'action' => 'edit']);
    }

    public function pilihKoordinator($params = []) {
        $data = $this->model->getAllForAdmin();
        $this->view('admin/asisten/pilih-koordinator', ['asisten' => $data]);
    }

    // =========================================================================
    // API & CRUD OPERATIONS
    // =========================================================================

    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Asisten retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID asisten tidak ditemukan', null, 400);

        $data = $this->model->getById($id);
        if (!$data) $this->error('Asisten tidak ditemukan', null, 404);
        
        $data['foto_url'] = $this->processPhotoUrl($data);
        $this->success($data, 'Asisten retrieved successfully');
    }

    public function store() {
        try {
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'email' => $_POST['email'] ?? '',
                'jurusan' => $_POST['jurusan'] ?? '',
                'bio' => $_POST['bio'] ?? '',
                'skills' => isset($_POST['skills']) ? $_POST['skills'] : '[]',
                'statusAktif' => $_POST['statusAktif'] ?? 'Asisten',
                'isKoordinator' => $_POST['isKoordinator'] ?? '0'
            ];

            if (empty($input['nama']) || empty($input['email'])) {
                $this->error('Field Nama dan Email wajib diisi', null, 400);
                return;
            }

            // Normalisasi JSON Skills
            if (!empty($input['skills']) && !is_array($input['skills']) && $input['skills'][0] !== '[') {
                $skillsArray = array_map('trim', explode(',', $input['skills']));
                $input['skills'] = json_encode($skillsArray);
            } elseif (is_array($input['skills'])) {
                $input['skills'] = json_encode($input['skills']);
            }

            // Handle File Upload via Helper
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $subFolder = 'asisten/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung', null, 400);
                    return;
                }
                
                $filename = Helper::generateFilename('asisten', $input['nama'], $ext);
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
                    $input['foto'] = $subFolder . $filename;
                } else {
                    $this->error('Gagal mengupload file', null, 500);
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
            error_log('Asisten store error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) { $this->error('ID asisten tidak ditemukan', null, 400); return; }

            $asisten = $this->model->getById($id);
            if (!$asisten) { $this->error('Asisten tidak ditemukan', null, 404); return; }

            $input = [
                'nama' => $_POST['nama'] ?? '',
                'email' => $_POST['email'] ?? '',
                'jurusan' => $_POST['jurusan'] ?? '',
                'bio' => $_POST['bio'] ?? '',
                'skills' => isset($_POST['skills']) ? $_POST['skills'] : '[]',
                'statusAktif' => $_POST['statusAktif'] ?? 'Asisten',
                'isKoordinator' => $_POST['isKoordinator'] ?? '0'
            ];

            if (empty($input['nama']) || empty($input['email'])) {
                $this->error('Field Nama dan Email wajib diisi', null, 400);
                return;
            }

            if (!empty($input['skills']) && !is_array($input['skills']) && $input['skills'][0] !== '[') {
                $skillsArray = array_map('trim', explode(',', $input['skills']));
                $input['skills'] = json_encode($skillsArray);
            } elseif (is_array($input['skills'])) {
                $input['skills'] = json_encode($input['skills']);
            }

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $subFolder = 'asisten/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung', null, 400);
                    return;
                }
                
                // Hapus foto lama
                if (!empty($asisten['foto'])) {
                    $oldFilePath = dirname(__DIR__, 2) . '/public/assets/uploads/' . $asisten['foto'];
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                
                $filename = Helper::generateFilename('asisten', $input['nama'], $ext);
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
                    $input['foto'] = $subFolder . $filename;
                } else {
                    $this->error('Gagal mengupload file', null, 500);
                    return;
                }
            }

            $result = $this->model->update($id, $input, 'idAsisten');
            if ($result) {
                $this->success(null, 'Asisten berhasil diupdate', 200);
            } else {
                $this->error('Gagal mengupdate asisten', null, 500);
            }
        } catch (Exception $e) {
            error_log('Asisten update error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) { $this->error('ID tidak ditemukan', null, 400); return; }

        $asisten = $this->model->getById($id);
        if (!$asisten) { $this->error('Asisten tidak ditemukan', null, 404); return; }

        if (!empty($asisten['foto'])) {
            $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
            $fotoPath = $uploadDir . $asisten['foto'];
            if (file_exists($fotoPath) && is_file($fotoPath)) {
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

    public function setKoordinator($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) { $this->error('ID tidak ditemukan', null, 400); return; }

            $this->model->resetAllKoordinator();
            $result = $this->model->update($id, ['isKoordinator' => 1], 'idAsisten');
            
            if ($result) {
                $this->success(null, 'Koordinator berhasil diperbarui', 200);
            } else {
                $this->error('Gagal mengupdate koordinator', null, 500);
            }
        } catch (Exception $e) {
            error_log('Set Koordinator error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }
}
?>