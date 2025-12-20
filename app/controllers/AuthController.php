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

        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Username dan Password wajib diisi');
            $this->redirect(PUBLIC_URL . '/login');
            return;
        }

        $user = $this->userModel->getByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Login sukses
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Update last login
            $this->userModel->updateLastLogin($user['id']);

            $this->redirect(PUBLIC_URL . '/admin/dashboard');
        } else {
            // Login gagal
            $this->setFlash('error', 'Username atau Password salah');
            $this->redirect(PUBLIC_URL . '/login');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . PUBLIC_URL . '/login');
        exit;
    }
}
