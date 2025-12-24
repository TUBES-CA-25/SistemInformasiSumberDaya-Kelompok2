<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/UserModel.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        // Jika sudah login, redirect ke admin
        if (isset($_SESSION['user_id'])) {
            $this->redirect(PUBLIC_URL . '/admin/dashboard');
            return;
        }
        
        $this->partial('auth/login');
    }

    public function authenticate() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validasi input kosong
        if (empty($username) && empty($password)) {
            $this->setFlash('error', '⚠️ Username dan Password tidak boleh kosong!');
            $this->redirect(PUBLIC_URL . '/login');
            return;
        }
        
        if (empty($username)) {
            $this->setFlash('error', '⚠️ Username tidak boleh kosong!');
            $this->redirect(PUBLIC_URL . '/login');
            return;
        }
        
        if (empty($password)) {
            $this->setFlash('error', '⚠️ Password tidak boleh kosong!');
            $this->redirect(PUBLIC_URL . '/login');
            return;
        }

        // Cari user berdasarkan username
        $user = $this->userModel->getByUsername($username);

        if (!$user) {
            // Username tidak ditemukan
            $this->setFlash('error', '❌ Username "<strong>' . htmlspecialchars($username) . '</strong>" tidak terdaftar dalam sistem!<br><small style="color: #666;">💡 Pastikan username yang Anda masukkan benar.</small>');
            $this->redirect(PUBLIC_URL . '/login');
            return;
        }
        
        if (!password_verify($password, $user['password'])) {
            // Password salah (username benar tapi password salah)
            $this->setFlash('error', '❌ Password salah untuk user "<strong>' . htmlspecialchars($username) . '</strong>"!<br><small style="color: #666;">💡 Username benar, tapi password yang Anda masukkan salah.</small>');
            $this->redirect(PUBLIC_URL . '/login');
            return;
        }

        // Login sukses
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
        
        // Success message
        $this->setFlash('success', '✅ Login berhasil! Selamat datang, <strong>' . $user['username'] . '</strong>!');

        $this->redirect(PUBLIC_URL . '/admin/dashboard');
    }

    public function logout() {
        $username = $_SESSION['username'] ?? 'User';
        
        // Clear session
        session_destroy();
        
        // Start new session untuk flash message
        session_start();
        $_SESSION['flash']['success'] = '👋 <strong>' . $username . '</strong> telah logout. Terima kasih!<br><small style="color: #666;">💡 Login kembali jika diperlukan.</small>';
        
        header('Location: ' . PUBLIC_URL . '/login');
        exit;
    }
}
