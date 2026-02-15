<?php

/**
 * ModulController
 * * Controller ini menangani manajemen modul praktikum, termasuk tampilan publik
 * untuk mahasiswa dan fungsionalitas CRUD (Create, Read, Update, Delete) 
 * berbasis AJAX untuk kebutuhan administrasi.
 * * @package App\Controllers
 */
class ModulController extends Controller {

    /**
     * Tampilan Dashboard Admin Modul.
     * Digunakan untuk merender halaman manajemen modul di sisi admin.
     * * @return void
     */
    public function adminIndex(): void {
        $data['judul'] = 'Kelola Modul Praktikum';
        $this->view('admin/modul/index', $data);
    }

    /**
     * Entry Point Utama (Routing Logic).
     * Mengatur alur berdasarkan HTTP Method dan jenis request (Ajax/Regular).
     * * @return void
     */
    public function index(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        // Deteksi apakah request dikirim melalui AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        // 1. Handling GET Request (Tampilan Publik atau Data JSON)
        if ($method === 'GET') {
            if ($isAjax) {
                $this->getJson(); // Jika AJAX, kirim data JSON
            } else {
                $this->renderPublicView(); // Jika regular, tampilkan halaman modul
            }
        } 
        
        // 2. Handling POST Request (Simpan atau Update)
        else if ($method === 'POST') {
            // Cek 'Method Spoofing' untuk menangani update pada form multipart
            if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
                $this->update();
            } else {
                $this->store();
            }
        } 
        
        // 3. Handling DELETE Request
        else if ($method === 'DELETE') {
            $this->delete();
        }
    }

    /**
     * Merender halaman tampilan modul praktikum untuk mahasiswa.
     * Memisahkan data berdasarkan jurusan (TI dan SI).
     * * @return void
     */
    private function renderPublicView(): void {
        require_once ROOT_PROJECT . '/app/models/ModulModel.php';
        $model = new ModulModel();
        
        $data['modul_ti'] = $model->getByJurusan('TI');
        $data['modul_si'] = $model->getByJurusan('SI');
        
        $this->view('praktikum/modul', $data);
    }

    /**
     * Mengambil seluruh data modul dalam format JSON.
     * Digunakan oleh DataTables atau frontend admin.
     * * @return void
     */
    public function getJson(): void {
        $this->cleanBuffer();
        
        require_once ROOT_PROJECT . '/app/models/ModulModel.php';
        $model = new ModulModel();
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success', 
            'data'   => $model->getAllModul()
        ]);
        exit;
    }

    /**
     * Menyimpan data modul baru ke database dan mengunggah file.
     * * @return void
     */
    public function store(): void {
        $this->cleanBuffer();
        header('Content-Type: application/json');
        
        require_once ROOT_PROJECT . '/app/models/ModulModel.php';
        $model = new ModulModel();
        
        // Cek apakah file diunggah
        $file = $_FILES['file'] ?? null;

        if ($model->tambahModul($_POST, $file) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Modul berhasil ditambahkan']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data atau upload file']);
        }
        exit;
    }

    /**
     * Memperbarui data modul yang sudah ada.
     * * @return void
     */
    public function update(): void {
        $this->cleanBuffer();
        header('Content-Type: application/json');
        
        require_once ROOT_PROJECT . '/app/models/ModulModel.php';
        $model = new ModulModel();
        
        // Jika file tidak diganti, kirim array error default PHP (error 4 = No File)
        $file = $_FILES['file'] ?? ['error' => 4];
        
        if ($model->updateModul($_POST, $file) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Modul berhasil diperbarui']);
        } else {
            // Kode ini juga dijalankan jika user menekan simpan tanpa mengubah data apapun
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data yang diubah atau gagal update']);
        }
        exit;
    }

    /**
     * Menghapus data modul berdasarkan ID.
     * * @param array $params Parameter dari router
     * @return void
     */
    public function delete(array $params = []): void {
        $this->cleanBuffer();
        header('Content-Type: application/json');

        $id = $params['id'] ?? null;
        if (!$id) {
            // Fallback jika tidak lewat router params
            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $segments = explode('/', rtrim($url, '/'));
            $id = end($segments);
        }
        
        require_once ROOT_PROJECT . '/app/models/ModulModel.php';
        $model = new ModulModel();
        
        if ($model->deleteModul($id) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Modul berhasil dihapus']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus modul']);
        }
        exit;
    }

    /**
     * Helper: Membersihkan output buffer.
     * Mencegah adanya whitespace atau teks liar yang merusak format JSON.
     * * @return void
     */
    private function cleanBuffer(): void {
        if (ob_get_length()) {
            ob_clean();
        }
    }
}