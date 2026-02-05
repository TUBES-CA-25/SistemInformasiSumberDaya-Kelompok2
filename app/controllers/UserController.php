<?php

/**
 * UserController
 * * Mengelola fungsionalitas Manajemen User (Admin).
 * Kelas ini memiliki proteksi tingkat tinggi di mana hanya user dengan role 'super_admin'
 * yang diizinkan untuk melakukan operasi CRUD pada akun administrator lainnya.
 * * @package App\Controllers
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/UserModel.php';

class UserController extends Controller {
    
    /** @var UserModel Instance model user */
    private $model;

    /**
     * Konstruktor: Melakukan pengecekan autentikasi dan otorisasi.
     * * Alur Keamanan:
     * 1. Cek apakah user sudah login.
     * 2. Cek apakah user memiliki hak akses 'super_admin'.
     */
    public function __construct() {
        // parent::__construct(); // Memastikan constructor parent terpanggil jika ada

        // 1. Verifikasi Status Login
        if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
            $this->redirect(PUBLIC_URL . '/login');
            exit;
        }
        
        // 2. Verifikasi Role Super Admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
            $this->handleUnauthorizedAccess();
        }

        $this->model = new UserModel();
    }

    /**
     * Menangani akses tidak sah berdasarkan tipe request (AJAX vs Regular).
     * * @return void
     */
    private function handleUnauthorizedAccess(): void {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($isAjax) {
            $this->error('Akses ditolak. Anda tidak memiliki izin Super Admin.', null, 403);
        } else {
            $this->setFlash('error', '⚠️ Akses Ditolak: Halaman Manajemen User hanya untuk Super Admin.');
            $this->redirect(PUBLIC_URL . '/admin');
        }
        exit;
    }

    // =========================================================================
    // VIEW METHODS (Render Halaman HTML)
    // =========================================================================

    /**
     * Menampilkan halaman indeks Manajemen User untuk Admin.
     * * @param array $params
     * @return void
     */
    public function adminIndex(array $params = []): void {
        $data = [
            'judul' => 'Manajemen User Admin',
            'users' => $this->model->getAllUsers()
        ];
        $this->view('admin/user/index', $data);
    }

    // =========================================================================
    // API METHODS (JSON Responses)
    // =========================================================================

    /**
     * API: Mengambil daftar semua user.
     * * @return void
     */
    public function apiIndex(): void {
        $users = $this->model->getAllUsers();
        $this->success($users, 'Data user berhasil diambil');
    }

    /**
     * API: Mengambil detail satu user berdasarkan ID.
     * * @param array $params
     * @return void
     */
    public function apiShow(array $params): void {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID User tidak valid');
        }
        
        $user = $this->model->getUserById($id);
        if (!$user) {
            $this->error('User tidak ditemukan');
        }
        
        $this->success($user);
    }

    /**
     * API: Menyimpan user admin baru ke database.
     * * Validasi: Username unik, password wajib, role wajib.
     * @return void
     */
    public function apiStore(): void {
        $data = $this->getJson();
        
        // Validasi Input Wajib
        if (empty($data['username']) || empty($data['password']) || empty($data['role'])) {
            $this->error('Data wajib diisi (Username, Password, Role)');
        }

        // Validasi Username Unik
        if ($this->model->getByUsername($data['username'])) {
            $this->error('Username sudah digunakan. Gunakan nama lain.');
        }

        if ($this->model->store($data)) {
            $this->success(null, 'User Admin baru berhasil ditambahkan', 201);
        } else {
            $this->error('Gagal menyimpan data user ke database');
        }
    }

    /**
     * API: Memperbarui data user yang sudah ada.
     * * @param array $params
     * @return void
     */
    public function apiUpdate(array $params): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID User tidak ditemukan');
        }

        $data = $this->getJson();
        if (empty($data['username']) || empty($data['role'])) {
            $this->error('Username dan Role wajib diisi');
        }

        if ($this->model->update($id, $data)) {
            $this->success(null, 'Data user berhasil diperbarui');
        } else {
            $this->error('Gagal memperbarui data user');
        }
    }

    /**
     * API: Menghapus user dari sistem.
     * * Keamanan: Mencegah user menghapus akunnya sendiri yang sedang aktif.
     * @param array $params
     * @return void
     */
    public function apiDelete(array $params): void {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->error('ID User tidak ditemukan');
        }

        // Proteksi: Self-deletion Prevention
        if ($id == $_SESSION['user_id']) {
            $this->error('Keamanan: Anda tidak diperbolehkan menghapus akun Anda sendiri.');
        }

        if ($this->model->delete($id)) {
            $this->success(null, 'User berhasil dihapus selamanya');
        } else {
            $this->error('Terjadi kesalahan saat menghapus user');
        }
    }
}