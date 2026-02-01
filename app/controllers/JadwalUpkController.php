<?php

/**
 * Controller Jadwal UPK (Ujian Praktikum Komputer)
 * * Kelas ini bertugas sebagai orchestrator untuk mengelola alur data Jadwal UPK.
 * Mengintegrasikan navigasi view, respon API JSON, dan delegasi logika bisnis
 * berat (seperti pengolahan file Excel/CSV) ke dalam JadwalUpkService.
 * * @package App\Controllers
 */

require_once ROOT_PROJECT . '/app/services/JadwalUpkService.php';
require_once ROOT_PROJECT . '/app/models/JadwalUpkModel.php'; 

class JadwalUpkController extends Controller 
{
    /** @var JadwalUpkModel Instance model untuk akses database */
    private $model;

    /** @var JadwalUpkService Instance service untuk pemrosesan logika bisnis */
    private $service;

    /**
     * Konstruktor JadwalUpkController
     * Inisialisasi dependensi Model dan Service.
     */
    public function __construct() 
    {
        $this->model = new JadwalUpkModel(); 
        $this->service = new JadwalUpkService($this->model);
    }

    /**
     * Tampilan Publik: Daftar Jadwal UPK
     * Menampilkan jadwal praktikum yang sedang berlangsung kepada mahasiswa.
     * * @return void
     */
    public function index(): void 
    {
        $data = [
            'judul'  => 'Jadwal UPK Praktikum',
            'jadwal' => $this->model->getAll() 
        ];

        $this->view('praktikum/jadwalupk', $data);
    }

    /**
     * Dashboard Admin: Kelola Jadwal UPK
     * Menampilkan tabel manajemen jadwal khusus untuk administrator.
     * * @return void
     */
    public function adminIndex(): void 
    {
        $data = [
            'judul'  => 'Kelola Jadwal UPK',
            'jadwal' => $this->model->getAll()
        ];

        $this->view('admin/jadwalupk/index', $data);
    }

    /**
     * Endpoint Impor Data: Mengolah file Excel atau CSV
     * Menangani unggahan file, memvalidasi format, dan melakukan bulk insert.
     * Mendukung respon AJAX (JSON) maupun Redirect konvensional.
     * * @return void
     */
    public function upload(): void 
    {
        $this->cleanBuffers();
        $isAjax = $this->isAjaxRequest();
        
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
        }

        // Mendeteksi kunci file dari berbagai kemungkinan input form
        $fileKey = $_FILES['excel_file'] ?? $_FILES['file_import'] ?? null;

        try {
            // 1. Validasi Keberadaan File
            if (!$fileKey || empty($fileKey['tmp_name'])) {
                throw new Exception("Tidak ada file yang diunggah atau file rusak.");
            }

            $extension = strtolower(pathinfo($fileKey['name'], PATHINFO_EXTENSION));
            $isSuccess = false;

            // 2. Delegasi Pemrosesan Berdasarkan Ekstensi
            if ($extension === 'csv') {
                $isSuccess = $this->service->importCSV($fileKey['tmp_name']);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                $dataRows  = $this->service->parseExcel($fileKey['tmp_name']);
                $isSuccess = $this->model->importData($dataRows);
            } else {
                throw new Exception("Format file .$extension tidak didukung. Gunakan Excel atau CSV.");
            }

            // 3. Memberikan Respon Berdasarkan Hasil Akhir
            if ($isSuccess) {
                $this->handleResponse($isAjax, "Impor data berhasil diproses.");
            } else {
                throw new Exception("Gagal menyimpan data ke database.");
            }

        } catch (Exception $e) {
            // Catch semua error dari Controller maupun Service
            $this->handleResponse($isAjax, $e->getMessage(), 400);
        }
    }

    /**
     * Menghapus Record Jadwal Berdasarkan ID
     * * @param array|int $params Parameter dari router yang mengandung 'id'
     * @return void
     */
    public function delete($params): void 
    {
        $id = is_array($params) ? ($params['id'] ?? null) : $params;
        $isAjax = $this->isAjaxRequest();

        if (!$id) {
            $this->handleResponse($isAjax, "ID jadwal tidak ditemukan.", 400);
            return;
        }

        if ($this->model->deleteJadwal($id)) {
            $this->handleResponse($isAjax, "Data jadwal berhasil dihapus.");
        } else {
            $this->handleResponse($isAjax, "Gagal menghapus data dari database.", 500);
        }
    }

    // =========================================================================
    // PRIVATE HELPER METHODS (INTERNAL ONLY)
    // =========================================================================

    /**
     * Membersihkan Output Buffer
     * Mencegah adanya karakter sampah (whitespace/warning) yang merusak format JSON.
     */
    private function cleanBuffers(): void 
    {
        while (ob_get_level()) { 
            ob_end_clean(); 
        }
    }

    /**
     * Mendeteksi Jenis Request
     * @return bool True jika request berasal dari AJAX atau endpoint API.
     */
    private function isAjaxRequest(): bool 
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
               strpos($_SERVER['REQUEST_URI'], 'api') !== false;
    }

    /**
     * Manajemen Respon Terpadu
     * Menentukan apakah aplikasi harus mengirimkan JSON atau melakukan Redirect
     * dengan membawa pesan sukses/error.
     * * @param bool   $isAjax  Status request
     * @param string $message Pesan yang akan disampaikan
     * @param int    $code    HTTP Status Code
     */
    private function handleResponse(bool $isAjax, string $message, int $code = 200): void 
    {
        if ($isAjax) {
            http_response_code($code);
            echo json_encode([
                'status'  => ($code < 400) ? 'success' : 'error',
                'code'    => $code,
                'message' => $message
            ]);
        } else {
            $type = ($code < 400) ? 'success' : 'error';
            // Penggunaan redirect diakhiri exit di base controller tetap aman untuk return void
            $this->redirect("/admin/jadwalupk?{$type}=" . urlencode($message));
        }
        exit;
    }
}