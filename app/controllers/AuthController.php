<?php

/**
 * AuthController - Kelola Autentikasi & Otorisasi Pengguna
 * 
 * Menangani:
 * - Halaman login publik
 * - Proses autentikasi username/password dengan password hashing
 * - Validasi kredensial dengan proteksi user enumeration
 * - Manajemen session user
 * - Update last login tracking
 * - Proses logout dengan pembersihan session
 * 
 * Tabel Database: user
 * Kunci Utama: id
 * Session Fields: user_id, username, role, status
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/UserModel.php';

class AuthController extends Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var UserModel Instance model untuk operasi data user */
    private $userModel;

    
    // =========================================================================
    // BAGIAN 2: KONSTRUKTOR
    // =========================================================================
    
    /**
     * Inisialisasi AuthController dengan UserModel
     */
    public function __construct() {
        $this->userModel = new UserModel();
    }

    
    // =========================================================================
    // BAGIAN 3: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Login Form - Tampilkan halaman formulir login
     * 
     * Memeriksa status login pengguna sebelum menampilkan form.
     * Jika sudah login (session lengkap), redirect ke dashboard admin.
     * Membersihkan session tidak lengkap atau error sebelum menampilkan form.
     * 
     * @return void Menampilkan partial auth/login atau redirect
     */
    public function login() {
        // Cek status login lengkap
        if (isset($_SESSION['status']) && $_SESSION['status'] == 'login' &&
            isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $this->redirect(PUBLIC_URL . '/admin');
            return;
        }
        
        // Bersihkan session yang tidak lengkap atau error sebelum form
        session_unset();
        session_destroy();
        session_start();
        
        $this->partial('auth/login');
    }

    /**
     * Autentikasi - Proses login dengan validasi kredensial
     * 
     * Validasi langkah:
     * 1. Periksa input username dan password tidak kosong
     * 2. Cari user berdasarkan username di database
     * 3. Verifikasi password menggunakan password_verify()
     * 4. Jika valid, set session dan update last login
     * 5. Jika tidak valid, tampilkan error tanpa membedakan (user enumeration protection)
     * 
     * Session Data yang Disimpan:
     * - user_id: ID unik pengguna untuk identifikasi
     * - username: Nama pengguna untuk display
     * - role: Role pengguna (admin, user, dll)
     * - status: Status login ('login')
     * 
     * Keamanan:
     * - Password menggunakan bcrypt hash verification
     * - Pesan error sama untuk username tidak ditemukan atau password salah
     *   (mencegah user enumeration attack)
     * - Update last_login untuk audit trail
     * 
     * @return void Redirect ke admin dashboard atau login page dengan flash message
     */
    public function authenticate() {
        // Kumpulkan input dan trim spasi
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        // LANGKAH 1: Validasi input wajib
        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Username dan Password wajib diisi.');
            $this->redirect(PUBLIC_URL . '/iclabs-login');
            return;
        }

        // LANGKAH 2: Cari user berdasarkan username
        $user = $this->userModel->getByUsername($username);

        // LANGKAH 3: Verifikasi password
        // Gunakan pesan error yang sama untuk keamanan (mencegah user enumeration)
        if (!$user || !password_verify($password, $user['password'])) {
            // Pesan aman untuk production
            $this->setFlash('error', 'Username atau Password yang Anda masukkan salah.');
            $this->redirect(PUBLIC_URL . '/iclabs-login');
            return;
        }

        // LANGKAH 4: Login sukses - set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = "login";
        
        // LANGKAH 5: Update waktu last login untuk audit trail
        $this->userModel->updateLastLogin($user['id']);
        
        // Tampilkan pesan sukses dengan nama pengguna (escaped untuk keamanan)
        $this->setFlash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['username']) . '.');

        $this->redirect(PUBLIC_URL . '/admin');
    }

    /**
     * Logout - Proses logout pengguna
     * 
     * Langkah logout:
     * 1. Simpan username sebelum menghapus session
     * 2. Hapus semua data session
     * 3. Destroy session di server
     * 4. Mulai session baru untuk flash message (opsional)
     * 5. Redirect ke halaman login dengan pesan sukses
     * 
     * Session Cleanup:
     * - session_unset(): Hapus semua variabel session
     * - session_destroy(): Hapus file session dari server
     * - session_start(): Mulai session baru untuk flash message
     * 
     * @return void Redirect ke halaman login dengan pesan logout sukses
     */
    public function logout() {
        // Simpan username sebelum session dihapus (untuk greeting/audit)
        $username = $_SESSION['username'] ?? 'User';
        
        // Hapus semua data session
        session_unset();
        session_destroy();
        
        // Mulai session baru untuk flash message
        session_start();
        
        // Tampilkan pesan logout sukses yang elegan
        $this->setFlash('success', 'Anda telah berhasil logout dari sistem.');
        
        header('Location: ' . PUBLIC_URL . '/iclabs-login');
        exit;
    }
}