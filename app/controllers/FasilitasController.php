<?php

require_once ROOT_PROJECT . '/app/services/FasilitasService.php';
require_once ROOT_PROJECT . '/app/models/FasilitasModel.php';
require_once ROOT_PROJECT . '/app/models/LaboratoriumGambarModel.php';

/**
 * FasilitasController
 * * Mengelola seluruh sarana prasarana termasuk Laboratorium Praktikum dan Ruang Riset.
 * Bertanggung jawab atas tampilan publik, manajemen administratif (Admin), dan API Galeri.
 * * @package App\Controllers
 */
class FasilitasController extends Controller {
    
    /** @var FasilitasService Instance untuk logika bisnis dan pengolahan data kompleks */
    private $service;
    
    /** @var FasilitasModel Instance untuk akses data langsung ke tabel laboratorium */
    private $model;

    public function __construct() {
        $this->service = new FasilitasService();
        $this->model = new FasilitasModel();
    }

    // =========================================================================
    // PUBLIK: Navigasi & Tampilan Utama
    // =========================================================================

    /**
     * Tampilan Publik: Daftar semua fasilitas (Lab & Riset).
     */
    public function index(): void {
        // Hanya ambil fasilitas yang berjenis 'Laboratorium'
        $labs = $this->service->getFasilitasByJenis('Laboratorium');
        
        $this->view('fasilitas/laboratorium', [
            'judul' => 'Fasilitas & Ruang Laboratorium',
            'laboratorium' => $labs
        ]);
    }

    /**
     * Tampilan Publik: Denah Lokasi (Floor Plan)
     */
    public function denah(): void {
        // View denah bersifat statis, tapi kita tetap kirim judul dan stylesheet
        $this->view('fasilitas/denah', [
            'judul' => 'Denah Lantai 2',
            'pageCss' => 'fasilitas.css'
        ]);
    }

    /**
     * Tampilan Publik: Daftar Ruang Riset & Inovasi
     */
    public function riset(): void {
        // Gunakan Service khusus untuk Ruang Riset (sudah berisi logika normalisasi)
        $riset = $this->service->getRisetFacilities();

        $this->view('fasilitas/riset', [
            'judul' => 'Ruang Riset & Inovasi',
            'riset' => $riset
        ]);
    }

    

    /**
     * Tampilan Publik: Detail lengkap satu fasilitas.
     */
    public function detail(array $params = []): void {
        // Ambil identifier dari berbagai sumber (Router params, querystring, atau dari URL)
        $rawId = $params['id'] ?? $params['idLaboratorium'] ?? $_GET['id'] ?? null;

        // Fallback: ekstrak dari REQUEST_URI (contoh: /laboratorium/start-up atau /riset/research-room-1)
        if (empty($rawId) && !empty($_SERVER['REQUEST_URI'])) {
            if (preg_match('#/(?:laboratorium|riset)/([0-9a-zA-Z_-]+)#', $_SERVER['REQUEST_URI'], $m)) {
                $rawId = $m[1];
            }
        }

        if (empty($rawId)) {
            $this->setFlash('error', 'ID fasilitas tidak valid.');
            $this->redirect('/laboratorium');
            return;
        }

        // Meminta Service mengambil data Lab (mendukung Slug nama, Hash ID, maupun ID numerik)
        $data = $this->service->getFullDetail($rawId);

        if (!$data) {
            // Catat log untuk investigasi lebih lanjut
            $logDir = ROOT_PROJECT . '/storage/logs';
            if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
            @file_put_contents($logDir . '/fasilitas_detail.log', date('c') . " - Detail not found for id: {$id} - REQUEST_URI: {$_SERVER['REQUEST_URI']}\n", FILE_APPEND);

            $this->setFlash('error', 'Fasilitas tidak ditemukan.');
            $this->redirect('/laboratorium');
            return;
        }

        // Mapping: service mengembalikan key 'lab', sementara view mengharapkan 'laboratorium'
        $data['laboratorium'] = $data['lab'];

        // Logika penentuan link kembali yang dinamis
        $data['judul'] = 'Detail ' . ($data['laboratorium']['nama'] ?? 'Fasilitas');
        $data['back_link'] = $this->getBackLink($data['laboratorium']['jenis'] ?? '');
        
        $this->view('fasilitas/detail', $data);
    }

    // =========================================================================
    // ADMIN: Manajemen Data (CRUD)
    // =========================================================================

    /**
     * API: Mengambil semua data fasilitas dalam format JSON.
     * Digunakan oleh tabel admin atau komponen front-end yang menggunakan AJAX.
     */
    public function apiIndex(): void {
        // 1. Bersihkan output buffer untuk memastikan tidak ada spasi/teks liar yang merusak JSON
        if (ob_get_length()) ob_clean();

        // 2. Set header sebagai JSON
        header('Content-Type: application/json');

        try {
            // 3. Ambil data dari model
            $labs = $this->model->getAll();
            
            // 4. Sertakan galeri gambar untuk setiap lab (dibutuhkan untuk fitur edit/hapus gambar)
            $gambarModel = new LaboratoriumGambarModel();
            foreach ($labs as &$lab) {
                $lab['images'] = $gambarModel->getByLaboratorium($lab['idLaboratorium']);
            }

            // 5. Kirim response sukses
            echo json_encode([
                'status'  => true,
                'message' => 'Data fasilitas berhasil dimuat',
                'data'    => $labs
            ]);
        } catch (Exception $e) {
            // 6. Kirim response error jika terjadi kegagalan sistem
            http_response_code(500);
            echo json_encode([
                'status'  => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data'    => []
            ]);
        }
        exit;
    }
    
    /**
     * Admin: Daftar Fasilitas untuk tabel manajemen admin.
     */
    public function adminIndex(): void {
        require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
        $asistenModel = new AsistenModel();
        $asistenList = $asistenModel->getAll();

        $labs = $this->model->getAll();
        $this->view('admin/fasilitas/index', [
            'judul' => 'Kelola Fasilitas Laboratorium',
            'laboratorium' => $labs,
            'asistenList'  => $asistenList
        ]);
    }

    /**
     * Admin: Halaman Detail khusus Admin.
     */
    public function adminDetail(array $params = []): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/informasi-lab');
            return;
        }

        $data = $this->service->getFullDetail((int)$id);
        if (!$data) {
            $this->setFlash('error', 'Data fasilitas tidak ditemukan');
            $this->redirect('/admin/informasi-lab');
            return;
        }

        $data['judul'] = 'Detail ' . $data['lab']['nama'];
        $this->view('admin/fasilitas/detail', $data);
    }

    /**
     * Admin: Form Tambah/Edit Fasilitas.
     */
    public function create(): void {
        $this->view('admin/fasilitas/form', [
            'judul' => 'Tambah Fasilitas Laboratorium',
            'action' => 'create',
            'data' => null
        ]);
    }

    public function edit(array $params = []): void {
        $id = $params['id'] ?? null;
        $lab = $this->model->getById($id, 'idLaboratorium');

        if (!$lab) {
            $this->setFlash('error', 'Data tidak ditemukan');
            $this->redirect('/admin/informasi-lab');
            return;
        }

        $this->view('admin/fasilitas/form', [
            'judul' => 'Edit Fasilitas Laboratorium',
            'action' => 'edit',
            'data' => $lab
        ]);
    }

    // =========================================================================
    // API & AJAX: Operasi Data
    // =========================================================================

    public function store(): void {
        $input = $this->getJson() ?? $_POST;
        unset($input['_method'], $input['idLaboratorium']);

        // Convert empty foreign key idKordinatorAsisten to null
        if (array_key_exists('idKordinatorAsisten', $input) && (empty($input['idKordinatorAsisten']) || $input['idKordinatorAsisten'] === '0')) {
            $input['idKordinatorAsisten'] = null;
        }
        
        // Map form name to database column
        if (isset($input['fasilitas'])) {
            $input['fasilitas_pendukung'] = $input['fasilitas'];
            unset($input['fasilitas']);
        }

        if (empty($input['nama'])) {
            $this->error('Nama wajib diisi');
            return;
        }

        // Validasi ekstensi foto sebelum memproses
        if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['name'][0])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            foreach ($_FILES['gambar']['name'] as $key => $name) {
                if ($_FILES['gambar']['error'][$key] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowed)) {
                        $this->error('Format file gambar tidak valid. Hanya menerima file gambar (jpg, jpeg, png, gif, webp, svg)', null, 400);
                        return;
                    }
                }
            }
        }

        // Handle main image if uploaded
        if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['name'][0])) {
            $uploadResult = $this->handleMultipleUploads($_FILES['gambar'], $input['nama']);
            if ($uploadResult) {
                $input['gambar'] = $uploadResult[0]; // Set first image as main
            }
        }

        if ($this->model->insert($input)) {
            $newId = $this->model->getLastInsertId();
            
            // Save all uploaded images to gallery
            if (isset($uploadResult) && !empty($uploadResult)) {
                $gambarModel = new LaboratoriumGambarModel();
                foreach ($uploadResult as $filename) {
                    $gambarModel->insert([
                        'idLaboratorium' => $newId,
                        'namaGambar' => $filename
                    ]);
                }
            }

            $this->success(['id' => $newId], 'Fasilitas berhasil ditambahkan', 201);
        } else {
            $this->error('Gagal menyimpan ke database');
        }
    }

    public function update(array $params = []): void {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        $id = $params['id'] ?? $_POST['idLaboratorium'] ?? $_POST['id'] ?? null;
        $input = $this->getJson() ?? $_POST;
        unset($input['_method'], $input['idLaboratorium'], $input['id']);

        // Convert empty foreign key idKordinatorAsisten to null
        if (array_key_exists('idKordinatorAsisten', $input) && (empty($input['idKordinatorAsisten']) || $input['idKordinatorAsisten'] === '0')) {
            $input['idKordinatorAsisten'] = null;
        }

        // Map form name to database column
        if (isset($input['fasilitas'])) {
            $input['fasilitas_pendukung'] = $input['fasilitas'];
            unset($input['fasilitas']);
        }

        // Validasi ekstensi foto sebelum memproses
        if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['name'][0])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            foreach ($_FILES['gambar']['name'] as $key => $name) {
                if ($_FILES['gambar']['error'][$key] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowed)) {
                        $this->error('Format file gambar tidak valid. Hanya menerima file gambar (jpg, jpeg, png, gif, webp, svg)', null, 400);
                        return;
                    }
                }
            }
        }

        // Handle multiple images if uploaded
        if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['name'][0])) {
            $existing = $this->model->getById($id, 'idLaboratorium');
            $labName = $input['nama'] ?? $existing['nama'] ?? 'lab';

            $uploadResult = $this->handleMultipleUploads($_FILES['gambar'], $labName);
            if ($uploadResult) {
                // Save all uploaded images to gallery
                $gambarModel = new LaboratoriumGambarModel();
                foreach ($uploadResult as $filename) {
                    $gambarModel->insert([
                        'idLaboratorium' => $id,
                        'namaGambar' => $filename
                    ]);
                }
                
                // If lab doesn't have a main image yet, set the first one as main
                if (empty($existing['gambar'])) {
                    $input['gambar'] = $uploadResult[0];
                }
            }
        }

        if ($this->model->update($id, $input, 'idLaboratorium')) {
            $this->success([], 'Fasilitas berhasil diperbarui');
        } else {
            $this->error('Gagal mengupdate data');
        }
    }

    /**
     * Helper to handle multiple image uploads
     */
    private function handleMultipleUploads($files, $labName): array {
        require_once ROOT_PROJECT . '/app/helpers/ImageOptimizer.php';

        $uploadedFiles = [];
        $subFolder = 'laboratorium/';
        $uploadDir = ROOT_PROJECT . '/public/assets/uploads/' . $subFolder;
        
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $cleanName = Helper::slugify($labName ?? 'lab');

        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                    continue;
                }
                $filename = "laboratorium_{$cleanName}_" . time() . '_' . rand(100, 999) . '.' . $ext;
                
                if (move_uploaded_file($files['tmp_name'][$key], $uploadDir . $filename)) {
                    // Kompresi gambar agar tidak menyebabkan lag saat ditampilkan (HD resolution)
                    ImageOptimizer::optimize($uploadDir . $filename, 1920, 1080);

                    // Konversi ke WebP agar ukuran file lebih kecil
                    $webpPath = ImageOptimizer::convertToWebp($uploadDir . $filename);
                    if ($webpPath) {
                        $filename = basename($webpPath);
                    }

                    $uploadedFiles[] = $subFolder . $filename;
                }
            }
        }
        return $uploadedFiles;
    }

    public function delete(array $params): void {
        $id = $params['id'] ?? null;
        
        // Proteksi relasi jadwal
        if (method_exists($this->model, 'hasJadwal') && $this->model->hasJadwal($id)) {
            $this->error('Fasilitas masih digunakan dalam jadwal praktikum.');
            return;
        }

        if ($this->model->delete($id, 'idLaboratorium')) {
            $this->success([], 'Fasilitas berhasil dihapus');
        } else {
            $this->error('Gagal menghapus data');
        }
    }

    /**
     * API: Hapus Gambar Galeri (via Service untuk pembersihan file fisik).
     */
    public function deleteImage(array $params): void {
        $id = $params['id'] ?? null;
        try {
            $this->service->deleteImageWithLogic((int)$id);
            $this->success(['id' => $id], 'Gambar berhasil dihapus');
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, 500);
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    private function getBackLink(string $jenis): string {
        return (stripos($jenis, 'riset') !== false) ? PUBLIC_URL . '/riset' : PUBLIC_URL . '/laboratorium';
    }
}