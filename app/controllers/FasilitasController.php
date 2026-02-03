<?php

require_once ROOT_PROJECT . '/app/Services/FasilitasService.php';
require_once ROOT_PROJECT . '/app/models/FasilitasModel.php';

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
        // Mengambil data yang sudah diolah Service (termasuk thumbnail gambar utama)
        $labs = $this->service->getFasilitasWithThumbnails();
        
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
        // Ambil ID dari berbagai sumber (Router params, querystring, atau dari URL jika perlu)
        $id = $params['id'] ?? $params['idLaboratorium'] ?? $_GET['id'] ?? null;

        // Fallback: ekstrak dari REQUEST_URI (contoh: /laboratorium/123)
        if (empty($id) && !empty($_SERVER['REQUEST_URI'])) {
            if (preg_match('#/laboratorium/([0-9]+)#', $_SERVER['REQUEST_URI'], $m)) {
                $id = $m[1];
            }
        }

        // Validasi ID
        $id = (is_numeric($id)) ? (int)$id : null;
        if ($id === null) {
            $this->setFlash('error', 'ID fasilitas tidak valid.');
            $this->redirect('/laboratorium');
            return;
        }

        // Meminta Service mengambil data Lab + Galeri Foto + SOP/Peraturan terkait
        $data = $this->service->getFullDetail($id);

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
        if (ob_get_level()) ob_end_clean();

        // 2. Set header sebagai JSON
        header('Content-Type: application/json');

        try {
            // 3. Ambil data dari model
            // Anda bisa menggunakan $this->model->getAll() 
            // atau $this->service->getFasilitasWithThumbnails() jika ingin data yang lebih lengkap
            $labs = $this->model->getAll();

            // 4. Kirim response sukses
            echo json_encode([
                'status'  => true,
                'message' => 'Data fasilitas berhasil dimuat',
                'data'    => $labs
            ]);
        } catch (Exception $e) {
            // 5. Kirim response error jika terjadi kegagalan sistem
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
        $labs = $this->model->getAll();
        $this->view('admin/fasilitas/index', [
            'judul' => 'Kelola Fasilitas Laboratorium',
            'laboratorium' => $labs
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
        if (empty($input['nama'])) {
            $this->error('Nama wajib diisi');
            return;
        }

        if ($this->model->insert($input)) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Fasilitas berhasil ditambahkan', 201);
        } else {
            $this->error('Gagal menyimpan ke database');
        }
    }

    public function update(array $params = []): void {
        $id = $params['id'] ?? null;
        $input = $this->getJson() ?? $_POST;

        if ($this->model->update($id, $input, 'idLaboratorium')) {
            $this->success([], 'Fasilitas berhasil diperbarui');
        } else {
            $this->error('Gagal mengupdate data');
        }
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
        return (stripos($jenis, 'riset') !== false) ? 'index.php?page=riset' : 'index.php?page=laboratorium';
    }
}