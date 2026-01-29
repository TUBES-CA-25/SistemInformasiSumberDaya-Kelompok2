<?php
class ModulController extends Controller {
    
    /**
     * Admin Index - Kelola Modul Praktikum
     */
    public function adminIndex() {
        $data['judul'] = 'Kelola Modul Praktikum';
        $this->view('admin/modul/index', $data);
    }

    public function index() {
        $method = $_SERVER['REQUEST_METHOD'];
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        // 1. Tampilkan Halaman View (untuk Pengguna Publik)
        if ($method === 'GET' && !$isAjax) {
            require_once '../app/models/ModulModel.php';
            $model = new ModulModel();
            
            $data['modul_ti'] = $model->getByJurusan('TI');
            $data['modul_si'] = $model->getByJurusan('SI');
            
            // URUTAN YANG BENAR: Header -> Konten -> Footer
           
            $this->view('praktikum/modul', $data); 
          
        } 
        // 2. AJAX GET (Data JSON untuk Admin)
        else if ($method === 'GET' && $isAjax) {
            $this->getJson();
        } 
        // 3. POST (Simpan atau Update via Method Spoofing)
        else if ($method === 'POST') {
            if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
                $this->update();
            } else {
                $this->store();
            }
        } 
        // 4. DELETE
        else if ($method === 'DELETE') {
            $this->delete();
        }
    }

    public function getJson() {
        if (ob_get_length()) ob_clean(); // Hapus output liar (seperti spasi atau error php)
        require_once '../app/models/ModulModel.php';
        $model = new ModulModel();
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success', 
            'data' => $model->getAllModul()
        ]);
        exit;
    }

    public function store() {
        if (ob_get_length()) ob_clean(); // Cegah error "Unexpected token <"
        header('Content-Type: application/json');
        
        require_once '../app/models/ModulModel.php';
        $model = new ModulModel();
        
        // Pastikan name di HTML adalah 'file'
        if ($model->tambahModul($_POST, $_FILES['file']) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Berhasil']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal simpan database atau upload file']);
        }
        exit;
    }

    public function update() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        require_once '../app/models/ModulModel.php';
        $model = new ModulModel();
        
        if ($model->updateModul($_POST, $_FILES['file'] ?? ['error' => 4]) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal update']);
        }
        exit;
    }

    public function delete() {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', rtrim($url, '/'));
        $id = end($segments);
        
        require_once '../app/models/ModulModel.php';
        $model = new ModulModel();
        
        header('Content-Type: application/json');
        if ($model->deleteModul($id) > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }
}