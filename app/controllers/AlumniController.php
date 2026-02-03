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
        $input = $_POST;
        
        // Validasi Sederhana
        if (empty($input['nama']) || empty($input['angkatan'])) {
            return $this->error('Nama dan angkatan wajib diisi', null, 400);
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
        
        return $result 
            ? $this->success([], 'Data alumni berhasil disimpan') 
            : $this->error('Terjadi kesalahan saat menyimpan data');
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
        $id = $params['id'] ?? null;
        
        // Ambil data alumni sebelum dihapus untuk mendapatkan informasi file foto
        $alumni = $this->model->getById($id, 'id');
        
        // Hapus file fisik di storage jika ada
        if ($alumni && !empty($alumni['foto'])) {
            $this->alumniService->deletePhoto($alumni['foto']);
        }

        // Hapus record dari database
        $result = $this->model->delete($id, 'id');
        
        return $result 
            ? $this->success([], 'Data alumni berhasil dihapus') 
            : $this->error('Gagal menghapus data dari database');
    }
}