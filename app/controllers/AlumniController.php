<?php
/**
 * Alumni Controller
 * 
 * Menangani semua operasi terkait alumni termasuk:
 * - Menampilkan daftar alumni (public)
 * - Menampilkan detail alumni (public)
 * - CRUD operations (admin)
 * - API endpoints
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AlumniModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class AlumniController extends Controller
{
    // ============================================
    // PROPERTIES
    // ============================================
    /** @var AlumniModel Model untuk operasi database alumni */
    private $model;

    // ============================================
    // CONSTRUCTOR
    // ============================================
    /**
     * Inisialisasi controller dengan model
     */
    public function __construct()
    {
        $this->model = new AlumniModel();
    }

    // ============================================
    // SECTION 1: PUBLIC ROUTES (Frontend)
    // ============================================

    /**
     * Halaman publik: Daftar semua alumni
     * 
     * Fitur:
     * - Mengambil data alumni dari database
     * - Mengelompokkan berdasarkan tahun angkatan
     * - Mengurutkan tahun secara descending (terbaru di atas)
     * 
     * @param array $params Route parameters (unused)
     */
    public function index($params = [])
    {
        // Ambil data mentah dari database
        $raw_data = $this->model->getAll();

        // Kelompokkan alumni berdasarkan tahun angkatan
        $alumni_by_year = [];
        if (!empty($raw_data) && is_array($raw_data)) {
            foreach ($raw_data as $row) {
                $year = $row['angkatan'] ?? 'Unknown';
                $alumni_by_year[$year][] = $row;
            }
            // Sortir tahun descending (terbaru di atas)
            krsort($alumni_by_year);
        }

        // Siapkan data untuk view
        $data = [
            'judul' => 'Daftar Alumni',
            'alumni_by_year' => $alumni_by_year
        ];

        $this->view('alumni/alumni', $data);
    }

    /**
     * Halaman publik: Detail satu alumni
     * 
     * Fitur:
     * - Ambil data alumni berdasarkan ID
     * - Proses gambar/avatar (external URL atau upload lokal)
     * - Bersihkan dan format data keahlian dan mata kuliah
     * - Tampilkan detail alumni dengan data yang sudah dimurnikan
     * 
     * @param array $params Route parameters dengan 'id' key
     */
    public function detail($params = [])
    {
        // Ambil ID dari URL params atau query string
        $id = $params['id'] ?? $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('index.php?page=alumni');
            return;
        }

        // Ambil data alumni dari database
        $alumniRow = $this->model->getById((int)$id, 'id');

        if (!$alumniRow) {
            $this->redirect('index.php?page=alumni');
            return;
        }

        // -------- DATA CLEANING & PROCESSING --------

        // A. Process Gambar/Avatar
        $imgUrl = $this->processAlumniImage($alumniRow);

        // B. Clean & Process Keahlian (Skills)
        $skillsList = $this->cleanArrayField($alumniRow['keahlian'] ?? '');

        // C. Clean & Process Mata Kuliah
        $matkulString = $this->cleanArrayField($alumniRow['mata_kuliah'] ?? '', true);

        // Siapkan data matang untuk view
        $data = [
            'alumni'        => $alumniRow,
            'img_url'       => $imgUrl,
            'skills_list'   => $skillsList,
            'matkul_string' => $matkulString,
            'judul'         => 'Detail Alumni - ' . $alumniRow['nama']
        ];

        $this->view('alumni/detail', $data);
    }

    // ============================================
    // SECTION 2: ADMIN ROUTES (CRUD)
    // ============================================

    /**
     * Admin: Tampilkan daftar semua alumni (tabel)
     * 
     * @param array $params Route parameters (unused)
     */
    public function adminIndex($params = [])
    {
        $data = $this->model->getAll();
        $this->view('admin/alumni/index', ['alumni' => $data]);
    }

    /**
     * Admin: Form create alumni baru
     * 
     * @param array $params Route parameters (unused)
     */
    public function create($params = [])
    {
        $this->view('admin/alumni/form', [
            'alumni' => null,
            'action' => 'create'
        ]);
    }

    /**
     * Admin: Form edit alumni
     * 
     * Mengambil data alumni existing dan menampilkan form edit
     * 
     * @param array $params Route parameters dengan 'id' key
     */
    public function edit($params = [])
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->redirect('/admin/alumni');
            return;
        }

        // Ambil data existing
        $alumni = $this->model->getById($id, 'id');
        
        if (!$alumni) {
            $this->setFlash('error', 'Data alumni tidak ditemukan');
            $this->redirect('/admin/alumni');
            return;
        }

        $this->view('admin/alumni/form', [
            'alumni' => $alumni,
            'action' => 'edit'
        ]);
    }

    /**
     * Admin: Simpan alumni baru (POST)
     * 
     * Proses:
     * 1. Validasi input (nama, angkatan wajib)
     * 2. Handle upload foto jika ada
     * 3. Insert ke database
     * 4. Return JSON response
     */
    public function store()
    {
        try {
            // Ambil data dari form POST
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
                $uploadResult = $this->handlePhotoUpload('alumni');
                if ($uploadResult['success']) {
                    $input['foto'] = $uploadResult['path'];
                } else {
                    $this->error($uploadResult['message'], null, 400);
                    return;
                }
            }

            // Insert ke database
            $result = $this->model->insert($input);
            
            if ($result) {
                $this->success(
                    ['id' => $this->model->getLastInsertId()],
                    'Alumni berhasil ditambahkan',
                    201
                );
            } else {
                $this->error('Gagal menambahkan alumni', null, 500);
            }
        } catch (Exception $e) {
            error_log('Alumni store error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Admin: Update alumni (PUT)
     * 
     * Proses:
     * 1. Validasi ID dan data yang ada
     * 2. Handle upload foto baru jika ada
     * 3. Hapus foto lama jika ada foto baru
     * 4. Update database
     * 5. Return JSON response
     * 
     * @param array $params Route parameters dengan 'id' key
     */
    public function update($params)
    {
        try {
            $id = $params['id'] ?? null;
            
            if (!$id) {
                $this->error('ID tidak ditemukan', null, 400);
                return;
            }

            // Cek apakah alumni exists
            $alumni = $this->model->getById($id, 'id');
            if (!$alumni) {
                $this->error('Alumni tidak ditemukan', null, 404);
                return;
            }

            // Ambil data dari form POST
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
                // Hapus foto lama jika ada
                if (!empty($alumni['foto'])) {
                    $this->deletePhotoFile($alumni['foto']);
                }

                // Upload foto baru
                $uploadResult = $this->handlePhotoUpload('alumni');
                if ($uploadResult['success']) {
                    $input['foto'] = $uploadResult['path'];
                } else {
                    $this->error($uploadResult['message'], null, 400);
                    return;
                }
            }

            // Update database
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

    /**
     * Admin: Hapus alumni (DELETE)
     * 
     * @param array $params Route parameters dengan 'id' key
     */
    public function delete($params)
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        // Ambil data untuk hapus foto
        $alumni = $this->model->getById($id, 'id');
        
        // Hapus foto jika ada
        if ($alumni && !empty($alumni['foto'])) {
            $this->deletePhotoFile($alumni['foto']);
        }

        // Hapus dari database
        $result = $this->model->delete($id, 'id');
        
        if ($result) {
            $this->success([], 'Alumni berhasil dihapus', 200);
        } else {
            $this->error('Gagal menghapus alumni', null, 500);
        }
    }

    // ============================================
    // SECTION 3: API ROUTES
    // ============================================

    /**
     * API: Ambil daftar semua alumni
     * 
     * Response: JSON array of alumni
     */
    public function apiIndex()
    {
        $data = $this->model->getAll();
        $this->success($data, 'Data alumni retrieved successfully');
    }

    /**
     * API: Ambil detail satu alumni berdasarkan ID
     * 
     * @param array $params Route parameters dengan 'id' key
     */
    public function apiShow($params)
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'id');
        
        if (!$data) {
            $this->error('Data tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Alumni retrieved successfully');
    }

    // ============================================
    // SECTION 4: HELPER METHODS (Private)
    // ============================================

    /**
     * Process alumni photo/image
     * 
     * Logic:
     * 1. Jika ada foto di database, gunakan itu
     *    - Jika external URL, gunakan langsung
     *    - Jika path lokal, gabung dengan base URL
     * 2. Jika tidak ada foto, gunakan avatar placeholder
     * 
     * @param array $alumni Data alumni dari database
     * @return string URL gambar alumni
     */
    private function processAlumniImage($alumni)
    {
        $dbFoto = $alumni['foto'] ?? '';
        $namaEnc = urlencode($alumni['nama'] ?? '');

        // Jika tidak ada foto, gunakan avatar placeholder
        if (empty($dbFoto)) {
            return "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";
        }

        // Cek apakah foto adalah URL external
        if (strpos($dbFoto, 'http') === 0) {
            return $dbFoto;
        }

        // Jika path lokal, gabung dengan base URL
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        return $baseUrl . '/assets/uploads/' . $dbFoto;
    }

    /**
     * Clean array field dari database
     * 
     * Hapus karakter bracket, quote, backslash
     * Ubah dari string "[item1","item2"]" menjadi array atau string
     * 
     * @param string $rawData Data array dalam bentuk string
     * @param bool $asString Return sebagai string (comma-separated) atau array
     * @return mixed Array atau string tergantung parameter $asString
     */
    private function cleanArrayField($rawData, $asString = false)
    {
        // Hapus karakter tidak perlu: [], ", ', \
        $cleaned = str_replace(['[', ']', '"', "'", '\\'], '', $rawData);
        
        // Split by comma dan trim
        $items = array_filter(array_map('trim', explode(',', $cleaned)));

        // Return format sesuai parameter
        return $asString ? implode(', ', $items) : $items;
    }

    /**
     * Handle file upload dengan validasi
     * 
     * Proses:
     * 1. Validasi extension file
     * 2. Buat folder destination jika belum ada
     * 3. Generate unique filename
     * 4. Move uploaded file ke destination
     * 5. Return status dan path
     * 
     * @param string $folderName Nama subfolder untuk upload (misal: 'alumni')
     * @return array ['success' => bool, 'path' => string, 'message' => string]
     */
    private function handlePhotoUpload($folderName)
    {
        $subFolder = $folderName . '/';
        $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
        
        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validasi extension file
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowedExts)) {
            return [
                'success' => false,
                'path' => null,
                'message' => 'Format file tidak didukung. Gunakan: jpg, jpeg, png, gif'
            ];
        }

        // Generate unique filename
        $filename = Helper::generateFilename($folderName, $_POST['nama'] ?? 'alumni', $ext);
        $target = $uploadDir . $filename;

        // Move file ke destination
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            return [
                'success' => true,
                'path' => $subFolder . $filename,
                'message' => null
            ];
        }

        return [
            'success' => false,
            'path' => null,
            'message' => 'Gagal mengupload file'
        ];
    }

    /**
     * Delete photo file dari filesystem
     * 
     * @param string $filePath Relative path foto dari uploads folder
     * @return bool Success status
     */
    private function deletePhotoFile($filePath)
    {
        $baseUploadPath = dirname(__DIR__, 2) . '/public/assets/uploads/';
        $fullPath = $baseUploadPath . $filePath;

        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }

        return false;
    }
}