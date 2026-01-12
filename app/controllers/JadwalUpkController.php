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

    public function apiShow($id) {
        $data = $this->model('JadwalUpkModel')->getById($id);
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store() {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->model('JadwalUpkModel')->create($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan data']);
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->model('JadwalUpkModel')->update($id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate data']);
        }
    }

    public function deleteMultiple() {
        $data = json_decode(file_get_contents('php://input'), true);
        $ids = $data['ids'] ?? [];
        if (!empty($ids)) {
            if ($this->model('JadwalUpkModel')->deleteMultiple($ids)) {
                echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data dipilih']);
        }
    }
}