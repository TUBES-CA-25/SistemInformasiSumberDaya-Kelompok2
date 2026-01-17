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
        // Mulai output buffering untuk menangkap output yang tidak terduga
        ob_start();
        
        // Set JSON header terlebih dahulu
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            require_once '../app/models/SopModel.php';
            $sopModel = new SopModel();
            
            // Debug log
            error_log('Store POST Data: ' . json_encode($_POST));
            error_log('Store FILE Data: ' . json_encode($_FILES));
            
            // Validasi input
            if (empty($_POST['judul'])) {
                throw new Exception('Judul SOP harus diisi');
            }
            
            if (empty($_FILES['file']['name'])) {
                throw new Exception('File PDF harus diupload');
            }
            
            $result = $sopModel->tambahDataSop($_POST, $_FILES['file']);
            if ($result > 0) {
                ob_end_clean(); // Hapus output buffer apapun
                http_response_code(200);
                echo json_encode(['status' => 'success', 'code' => 200, 'message' => 'Berhasil ditambahkan']);
            } else {
                throw new Exception('Gagal menyimpan data SOP ke database');
            }
        } catch (Exception $e) {
            ob_end_clean(); // Hapus output buffer apapun
            error_log('SOP Store Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function update() {
        // Mulai output buffering untuk menangkap output yang tidak terduga
        ob_start();
        
        // Set JSON header terlebih dahulu
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            require_once '../app/models/SopModel.php';
            $sopModel = new SopModel();
            
            if (empty($_POST['id_sop'])) {
                throw new Exception('ID SOP tidak valid');
            }
            
            if (empty($_POST['judul'])) {
                throw new Exception('Judul SOP harus diisi');
            }
            
            $result = $sopModel->updateDataSop($_POST, $_FILES['file'] ?? ['error' => 4]);
            if ($result) {
                ob_end_clean(); // Hapus output buffer apapun
                http_response_code(200);
                echo json_encode(['status' => 'success', 'code' => 200, 'message' => 'Berhasil diupdate']);
            } else {
                throw new Exception('Gagal mengupdate data SOP');
            }
        } catch (Exception $e) {
            ob_end_clean(); // Hapus output buffer apapun
            error_log('SOP Update Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
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