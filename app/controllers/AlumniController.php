<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AlumniModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class AlumniController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new AlumniModel();
    }

    /**
     * Halaman publik: Daftar Alumni
     * [UPDATE] Menambahkan logika pengelompokan tahun (Year Grouping)
     * agar View tidak perlu mikir (MVC Standard).
     */
    public function index($params = []) {
        // 1. Ambil data mentah
        $raw_data = $this->model->getAll();
        
        // 2. LOGIC: Kelompokkan berdasarkan tahun angkatan
        $alumni_by_year = [];
        
        if (!empty($raw_data) && is_array($raw_data)) {
            foreach ($raw_data as $row) {
                $year = $row['angkatan'] ?? 'Unknown';
                $alumni_by_year[$year][] = $row;
            }
            // Sortir tahun terbaru di atas (Descending)
            krsort($alumni_by_year);
        }

        // 3. Siapkan data untuk view
        $data = [
            'judul' => 'Daftar Alumni',
            'alumni_by_year' => $alumni_by_year
        ];

        $this->view('alumni/alumni', $data);
    }

    /**
     * Halaman publik: Detail Alumni
     * [UPDATE] Menambahkan logic pembersihan string dan URL gambar
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/alumni');
            return;
        }
        
        // Ambil data (gunakan getById atau getAlumniById sesuai model Anda)
        $alumniRow = $this->model->getById((int)$id, 'id');
        
        if (!$alumniRow) {
            $this->redirect('/alumni');
            return;
        }

        // --- LOGIC PEMBERSIH DATA (CLEANING) ---

        // A. Logic Gambar & Avatar
        $dbFoto = $alumniRow['foto'] ?? '';
        $namaEnc = urlencode($alumniRow['nama'] ?? '');
        $imgUrl = '';

        if (!empty($dbFoto)) {
            // Cek link external vs lokal
            if (strpos($dbFoto, 'http') === 0) {
                $imgUrl = $dbFoto;
            } else {
                $imgUrl = PUBLIC_URL . '/assets/uploads/' . $dbFoto;
            }
        } else {
            // Default Avatar
            $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";
        }

        // B. Logic Keahlian (Hapus karakter [], ", ' lalu jadikan Array)
        $skillsRaw = $alumniRow['keahlian'] ?? '';
        $cleanSkills = str_replace(['[', ']', '"', "'", '\\'], '', $skillsRaw);
        $skillsList = array_filter(array_map('trim', explode(',', $cleanSkills)));

        // C. Logic Mata Kuliah (Bersihkan lalu jadikan String rapi)
        $matkulRaw = $alumniRow['mata_kuliah'] ?? '';
        $cleanMk = str_replace(['[', ']', '"', "'", '\\'], '', $matkulRaw);
        $matkulString = implode(', ', array_filter(array_map('trim', explode(',', $cleanMk))));

        // Siapkan Data Matang
        $data = [
            'alumni'        => $alumniRow,
            'img_url'       => $imgUrl,
            'skills_list'   => $skillsList,
            'matkul_string' => $matkulString,
            'judul'         => 'Detail Alumni - ' . $alumniRow['nama']
        ];

        $this->view('alumni/detail', $data);
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
                'mata_kuliah' => $_POST['mata_kuliah'] ?? '',
                'kesan_pesan' => $_POST['kesan_pesan'] ?? '',
                'keahlian' => $_POST['keahlian'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];
            
            // Validasi field wajib
            if (empty($input['nama']) || empty($input['angkatan'])) {
                $this->error('Field nama dan angkatan wajib diisi', null, 400);
                return;
            }
            
            // Handle file upload jika ada
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $subFolder = 'alumni/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                    return;
                }
                
                $filename = Helper::generateFilename('alumni', $input['nama'], $ext);
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $input['foto'] = $subFolder . $filename;
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
                'mata_kuliah' => $_POST['mata_kuliah'] ?? '',
                'kesan_pesan' => $_POST['kesan_pesan'] ?? '',
                'keahlian' => $_POST['keahlian'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];
            
            // Validasi field wajib
            if (empty($input['nama']) || empty($input['angkatan'])) {
                $this->error('Field nama dan angkatan wajib diisi', null, 400);
                return;
            }
            
            // Handle file upload jika ada file baru
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $subFolder = 'alumni/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($ext, $allowedExts)) {
                    $this->error('Format file tidak didukung. Gunakan: jpg, jpeg, png, gif', null, 400);
                    return;
                }
                
                // Delete old file if exists
                if (!empty($alumni['foto'])) {
                    $oldFilePath = $alumni['foto'];
                    $baseUploadPath = dirname(__DIR__, 2) . '/public/assets/uploads/';
                    $fullOldPath = $baseUploadPath . $oldFilePath;
                    
                    if (file_exists($fullOldPath) && is_file($fullOldPath)) {
                        @unlink($fullOldPath);
                    }
                }
                
                $filename = Helper::generateFilename('alumni', $input['nama'], $ext);
                $target = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $input['foto'] = $subFolder . $filename;
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