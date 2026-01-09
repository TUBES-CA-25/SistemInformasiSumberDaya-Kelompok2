<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/UserModel.php';

class UserController extends Controller {
    private $model;

    public function __construct() {
        // Cek login
        if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
            $this->redirect(PUBLIC_URL . '/login');
        }
        
        // Cek Super Admin - Hanya Super Admin yang bisa mengelola user
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
            // Jika dipanggil via AJAX/API
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $this->error('Akses ditolak. Anda bukan Super Admin.', null, 403);
            }
            // Jika akses langsung halaman
            $this->setFlash('error', '⚠️ Akses Ditolak: Halaman Manajemen User hanya untuk Super Admin.');
            $this->redirect(PUBLIC_URL . '/admin');
        }

        $this->model = new UserModel();
    }

    /**
     * Halaman index Manajemen User
     */
    public function adminIndex($params = []) {
        $users = $this->model->getAllUsers();
        $this->view('admin/user/index', ['users' => $users]);
    }

    /**
     * API: Ambil semua user
     */
    public function apiIndex() {
        $users = $this->model->getAllUsers();
        $this->success($users, 'Data user berhasil diambil');
    }

    /**
     * API: Ambil satu user
     */
    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID User tidak valid');
        
        $user = $this->model->getUserById($id);
        if (!$user) $this->error('User tidak ditemukan');
        
        $this->success($user);
    }

    /**
     * API: Simpan user baru
     */
    public function apiStore() {
        $data = $this->getJson();
        
        if (empty($data['username']) || empty($data['password']) || empty($data['role'])) {
            $this->error('Semua data wajib diisi (Username, Password, Role)');
        }

        // Cek apakah username sudah ada
        if ($this->model->getByUsername($data['username'])) {
            $this->error('Username sudah digunakan. Gunakan username lain.');
        }

        if ($this->model->store($data)) {
            $this->success(null, 'User Admin baru berhasil ditambahkan');
        } else {
            $this->error('Gagal menyimpan data user');
        }
    }

    /**
     * API: Update user
     */
    public function apiUpdate($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID User tidak valid');

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
     * API: Hapus user
     */
    public function apiDelete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID User tidak valid');

        // Mencegah menghapus diri sendiri
        if ($id == $_SESSION['user_id']) {
            $this->error('Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($this->model->delete($id)) {
            $this->success(null, 'User berhasil dihapus');
        } else {
            $this->error('Gagal menghapus user');
        }
    }
}
