<?php

class SopController extends Controller {
    
    // Ini fungsi "Router" di dalam Controller
    // Karena index.php mengarahkan semua '/sop' ke sini, kita harus pilah requestnya.
    public function index() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // 1. Jika Request-nya GET biasa -> Tampilkan Halaman View
        if ($method === 'GET' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $data['judul'] = 'SOP & Prosedur Laboratorium';
            $data['active_page'] = 'sop';
            
            // Load Model Manual
            require_once '../app/models/SopModel.php';
            $sopModel = new SopModel();
            $data['sop_list'] = $sopModel->getAllSop();
            
            $this->view('fasilitas/sop', $data); 
        }
        
        // 2. Jika Request-nya AJAX (untuk Data Table Admin)
        else if ($method === 'GET') {
            $this->getJson();
        }
        
        // 3. Jika Request-nya POST (Simpan Data)
        else if ($method === 'POST') {
            // Cek apakah ini Update (PUT via POST) atau Create
            if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
                $this->update();
            } else {
                $this->store();
            }
        }
        
        // 4. Jika Request-nya DELETE
        else if ($method === 'DELETE') {
            $this->delete();
        }
    }

    // --- API Methods ---

    public function getJson() {
        require_once '../app/models/SopModel.php';
        $sopModel = new SopModel();
        $data = $sopModel->getAllSop();
        
        echo json_encode([
            'status' => 'success',
            'code' => 200,
            'data' => $data
        ]);
        exit;
    }

    public function store() {
        require_once '../app/models/SopModel.php';
        $sopModel = new SopModel();
        
        if ($sopModel->tambahDataSop($_POST, $_FILES['file']) > 0) {
            echo json_encode(['status' => 'success', 'code' => 200, 'message' => 'Berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'code' => 500, 'message' => 'Gagal upload file atau simpan database']);
        }
        exit;
    }

    public function update() {
        require_once '../app/models/SopModel.php';
        $sopModel = new SopModel();
        
        if ($sopModel->updateDataSop($_POST, $_FILES['file'] ?? ['error' => 4])) {
            echo json_encode(['status' => 'success', 'code' => 200, 'message' => 'Berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'code' => 500, 'message' => 'Gagal update']);
        }
        exit;
    }

    public function delete() {
        // Ambil ID dari URL
        // Asumsi URL: /sop/123
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', rtrim($url, '/'));
        $id = end($segments);

        require_once '../app/models/SopModel.php';
        $sopModel = new SopModel();

        if ($sopModel->deleteSop($id) > 0) {
            echo json_encode(['status' => 'success', 'code' => 200]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus']);
        }
        exit;
    }
}