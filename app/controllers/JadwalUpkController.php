<?php
class JadwalUpkController extends Controller {

    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    public function index() {
        $data['judul'] = 'Jadwal UPK Praktikum';
        $data['jadwal'] = $this->model('JadwalUpkModel')->getAll();
        $this->view('praktikum/jadwalupk', $data);
    }

    public function admin_index() {
        $data['judul'] = 'Kelola Jadwal UPK';
        
        // PASTI KAN baris ini ada untuk mengambil data dari DB
        $data['jadwal'] = $this->model('JadwalUpkModel')->getAll();
        
        // PASTI KAN variabel $data dikirim sebagai argumen kedua
        $this->view('admin/jadwalupk/index', $data);
    }

    public function upload() {
        if (isset($_FILES['file_csv']['tmp_name'])) {
            if ($this->model('JadwalUpkModel')->importCSV($_FILES['file_csv']['tmp_name'])) {
                header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
                exit;
            }
        }
    }

    public function delete($id) {
        $this->model('JadwalUpkModel')->deleteJadwal($id);
        header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
        exit;
    }

    public function apiIndex() {
        $data = $this->model('JadwalUpkModel')->getAll();
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }
}