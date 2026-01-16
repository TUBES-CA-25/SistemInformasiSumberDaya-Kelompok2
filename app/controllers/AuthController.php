<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/UserModel.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        // Cek status login
        if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
            $this->redirect(PUBLIC_URL . '/admin');
            return;
        }
        
        // Bersihkan sesi residu
        if (isset($_SESSION['user_id'])) {
            session_unset();
            session_destroy();
            session_start();
        }
        
        $this->partial('auth/login');
    }

    public function authenticate() {
        // Gunakan trim untuk menghapus spasi tidak sengaja di awal/akhir
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        // 1. Validasi Input Kosong
        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Username dan Password wajib diisi.');
            $this->redirect(PUBLIC_URL . '/pintuSISDA');
            return;
        }

        // 2. Cari User
        $user = $this->userModel->getByUsername($username);

        // 3. Verifikasi Kredensial
        // Menggunakan satu pesan error untuk keamanan (mencegah User Enumeration)
        // Jika username tidak ketemu ATAU password salah, pesannya sama.
        if (!$user || !password_verify($password, $user['password'])) {
            
            // OPSI 1: Pesan Aman (Disarankan untuk Production)
            $this->setFlash('error', 'Username atau Password yang Anda masukkan salah.');
            
            /* // OPSI 2: Pesan Spesifik (Hanya pakai ini jika diminta dosen/klien spesifik)
            if (!$user) {
                $this->setFlash('error', 'Username tidak terdaftar dalam sistem.');
            } else {
                $this->setFlash('error', 'Password yang Anda masukkan tidak sesuai.');
            }
            */

            $this->redirect(PUBLIC_URL . '/pintuSISDA');
            return;
        }

        // 4. Login Sukses
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = "login";
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
        
        // Pesan Sukses yang Bersih
        $this->setFlash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['username']) . '.');

        $this->redirect(PUBLIC_URL . '/admin');
    }

    public function logout() {
        // Ambil username sebelum destroy untuk pesan perpisahan
        $username = $_SESSION['username'] ?? 'User';
        
        // Hapus semua data sesi
        session_unset();
        session_destroy();
        
        // Mulai sesi baru hanya untuk flash message
        session_start();
        
        // Pesan Logout yang Elegan
        $this->setFlash('success', 'Anda telah berhasil logout dari sistem.');
        
        header('Location: ' . PUBLIC_URL . '/pintuSISDA');
        exit;
    }
}