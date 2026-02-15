<?php

/**
 * Controller Alumni
 * * Bertanggung jawab mengelola request HTTP terkait data alumni.
 * Controller ini berperan sebagai "Orchestrator" yang menghubungkan 
 * antara Request, Logika Bisnis (AlumniService), dan Output (View/JSON).
 * * @package App\Controllers
 */

require_once ROOT_PROJECT . '/app/services/AlumniService.php';

class AlumniController extends Controller
{
    /** * @var AlumniService Instance dari layanan logika alumni 
     */
    private $alumniService;

    /** * @var AlumniModel Instance dari model database alumni 
     */
    private $model;

    /**
     * Konstruktor AlumniController
     * Inisialisasi Service dan Model yang dibutuhkan.
     */
    public function __construct()
    {
        $this->alumniService = new AlumniService();
        $this->model = new AlumniModel();
    }

    /**
     * Menampilkan daftar alumni ke halaman publik.
     * * Method ini mengambil data alumni yang sudah dikelompokkan 
     * berdasarkan tahun angkatan melalui Service.
     * * @return void Mengirimkan data ke view 'alumni/alumni'
     */
    public function index()
    {
        $data = [
            'judul' => 'Daftar Alumni',
            'alumni_by_year' => $this->alumniService->getAlumniGroupedByYear()
        ];
        $this->view('alumni/alumni', $data);
    }

    /**
     * Menampilkan daftar alumni di halaman admin.
     * * Method ini untuk admin panel management alumni.
     * * @return void Mengirimkan data ke view 'admin/alumni/index'
     */
    public function adminIndex()
    {
        $this->view('admin/alumni/index', ['judul' => 'Manajemen Data Alumni']);
    }

    /**
     * Menampilkan detail profil satu alumni.
     * * Method ini menerima ID alumni, meminta data yang sudah diformat
     * (foto, keahlian, matkul) dari Service, dan menampilkannya.
     * * @param array $params Parameter route yang berisi 'id'
     * @return void Mengirimkan data ke view 'alumni/detail' atau redirect jika gagal
     */
    public function detail($params = [])
    {
        // Prioritas ID dari params route atau query string $_GET
        $id = $params['id'] ?? $_GET['id'] ?? null;
        
        // Meminta Service untuk mengolah data mentah menjadi siap tampil
        $formattedData = $this->alumniService->getFormattedDetail($id);

        if (!$formattedData) {
            // Jika ID tidak valid atau data tidak ditemukan
            $this->redirect('/alumni');
            return;
        }

        // Menambahkan judul dinamis berdasarkan nama alumni
        $formattedData['judul'] = 'Detail Alumni - ' . $formattedData['alumni']['nama'];
        
        $this->view('alumni/detail', $formattedData);
    }

    /**
     * Menyimpan data alumni baru ke database.
     * * Proses meliputi:
     * 1. Validasi input wajib.
     * 2. Pengunggahan foto (jika ada) melalui Service.
     * 3. Penyimpanan data ke database melalui Model.
     * * @return string JSON response sukses atau error
     */
    public function store()
    {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        $input = $_POST;
        
        // Validasi Sederhana
        if (empty($input['nama']) || empty($input['angkatan'])) {
            $this->error('Nama dan angkatan wajib diisi', null, 400);
        }

        // Handle Upload Foto via Service jika file dikirimkan
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $path = $this->alumniService->uploadPhoto($_FILES['foto'], $input['nama']);
            if ($path) {
                $input['foto'] = $path; // Simpan path relatif ke database
            }
        }

        // Eksekusi Insert ke Database
        $result = $this->model->insert($input);
        
        if ($result) {
            $this->success([], 'Data alumni berhasil disimpan', 201);
        } else {
            $this->error('Terjadi kesalahan saat menyimpan data', null, 500);
        }
    }

    /**
     * Memperbarui data alumni yang sudah ada.
     * 
     * Proses meliputi:
     * 1. Validasi ID alumni
     * 2. Validasi input wajib
     * 3. Handle upload foto baru jika ada
     * 4. Update ke database
     * 
     * @param array $params Parameter route yang berisi 'id'
     * @return string JSON response sukses atau error
     */
    public function update($params)
    {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        
        $id = $params['id'] ?? null;
        $input = $_POST;
        
        // Hapus _method jika ada
        unset($input['_method']);
        
        if (!$id) {
            $this->error('ID alumni tidak diberikan', null, 400);
        }
        
        // Validasi Sederhana
        if (empty($input['nama']) || empty($input['angkatan'])) {
            $this->error('Nama dan angkatan wajib diisi', null, 400);
        }

        // Handle Upload Foto via Service jika file dikirimkan
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $path = $this->alumniService->uploadPhoto($_FILES['foto'], $input['nama']);
            if ($path) {
                $input['foto'] = $path; // Simpan path relatif ke database
            }
        }

        // Eksekusi Update ke Database
        $result = $this->model->update($id, $input, 'id');
        
        if ($result) {
            $this->success([], 'Data alumni berhasil diperbarui', 200);
        } else {
            $this->error('Gagal memperbarui data alumni', null, 500);
        }
    }

    /**
     * Menghapus data alumni beserta file fotonya.
     * * Proses meliputi:
     * 1. Pengecekan data alumni untuk mendapatkan path foto.
     * 2. Penghapusan file fisik foto melalui Service.
     * 3. Penghapusan record di database.
     * * @param array $params Parameter route yang berisi 'id'
     * @return string JSON response sukses atau error
     */
    public function delete($params)
    {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        
        $id = $params['id'] ?? null;
        
        // Ambil data alumni sebelum dihapus untuk mendapatkan informasi file foto
        $alumni = $this->model->getById($id, 'id');
        
        // Hapus file fisik di storage jika ada
        if ($alumni && !empty($alumni['foto'])) {
            $this->alumniService->deletePhoto($alumni['foto']);
        }

        // Hapus record dari database
        $result = $this->model->delete($id, 'id');
        
        if ($result) {
            $this->success([], 'Data alumni berhasil dihapus', 200);
        } else {
            $this->error('Gagal menghapus data dari database', null, 500);
        }
    }

    /**
     * API endpoint untuk mengambil data alumni dalam format JSON.
     * * Digunakan oleh admin/alumni.js untuk menampilkan data di tabel.
     * * @return void Mengirimkan JSON response dengan data alumni
     */
    public function apiIndex() {
        // Bersihkan output buffer agar JSON tidak rusak oleh warning/HTML
        if (ob_get_level()) ob_end_clean();

        try {
            header('Content-Type: application/json');
            $data = $this->model->getAll(); // Ambil semua data alumni

            echo json_encode([
                'status' => true,
                'message' => 'Data alumni berhasil diambil',
                'data' => $data
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil data alumni: ' . $e->getMessage(),
                'data' => null
            ]);
            exit;
        }
    }

    /**
     * API endpoint untuk mendapatkan data satu alumni berdasarkan ID.
     * Digunakan untuk pre-populate form pada edit modal.
     * 
     * @param array $params Parameter route yang berisi 'id'
     * @return void JSON response dengan data alumni
     */
    public function apiShow($params = [])
    {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');

        $id = $params['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'status' => false,
                'code' => 400,
                'message' => 'ID alumni tidak diberikan',
                'data' => null
            ]);
            exit;
        }

        try {
            $alumni = $this->model->getById($id, 'id');

            if (!$alumni) {
                echo json_encode([
                    'status' => false,
                    'code' => 404,
                    'message' => 'Data alumni tidak ditemukan',
                    'data' => null
                ]);
                exit;
            }

            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Data alumni berhasil diambil',
                'data' => $alumni
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'code' => 500,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ]);
            exit;
        }
    }
}