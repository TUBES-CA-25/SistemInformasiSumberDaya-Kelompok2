<?php
class AdminModulController extends Controller {
    public function index() {
        $data['judul'] = 'Manajemen Modul Praktikum';
        $this->view('admin/templates/header');
        $this->view('admin/modul/index', $data);
        $this->view('admin/templates/footer');
    }

    public function add() {
        // Implementasi method add untuk menambah modul
        header('Location: ' . PUBLIC_URL . '/admin/modul');
        exit;
    }

    public function delete($id) {
        // Implementasi method delete untuk menghapus modul
        if ($id) {
            // Tambahkan logika delete di sini jika diperlukan
        }
        header('Location: ' . PUBLIC_URL . '/admin/modul');
        exit;
    }
}