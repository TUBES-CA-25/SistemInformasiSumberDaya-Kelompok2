<?php

/**
 * SopController
 * * Mengelola Standar Operasional Prosedur (SOP) Laboratorium.
 * Menangani tampilan publik untuk mahasiswa dan fungsi CRUD berbasis AJAX untuk Admin.
 * * @package App\Controllers
 */
class SopController extends Controller {

    /**
     * Admin Index - Menampilkan halaman utama manajemen SOP.
     */
    public function adminIndex(): void {
        $data['judul'] = 'Kelola SOP Laboratorium';
        $this->view('admin/sop/index', $data);
    }

    /**
     * Entry Point Utama (Orchestrator).
     * Mengarahkan request berdasarkan HTTP Method dan tipe request (AJAX/Regular).
     */
    public function index(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        switch ($method) {
            case 'GET':
                $isAjax ? $this->getJson() : $this->renderPublicView();
                break;

            case 'POST':
                if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
                    $this->update();
                } else {
                    $this->store();
                }
                break;

            case 'DELETE':
                $this->delete();
                break;

            default:
                // Menggunakan helper sendResponse sebagai pengganti handleError
                $this->sendResponse("Method $method tidak diizinkan", 405, 'error');
                break;
        }
    }

    /**
     * Merender halaman tampilan daftar SOP untuk pengguna publik.
     */
    private function renderPublicView(): void {
        $model = $this->loadModel();
        $data = [
            'judul'       => 'SOP & Prosedur Laboratorium',
            'active_page' => 'sop',
            'sop_list'    => $model->getAllSop()
        ];
        
        $this->view('fasilitas/sop', $data);
    }

    /**
     * Mengambil seluruh data SOP dalam format JSON untuk DataTables.
     */
    public function getJson(): void {
        $this->prepareJsonResponse();
        $model = $this->loadModel();
        $data = $model->getAllSop();

        echo json_encode([
            'status' => 'success',
            'code'   => 200,
            'data'   => $data
        ]);
        exit;
    }

    /**
     * Menyimpan data SOP baru beserta unggahan file PDF.
     */
    public function store(): void {
        $this->prepareJsonResponse();

        try {
            $model = $this->loadModel();

            $this->validateInput($_POST, ['judul']);
            if (empty($_FILES['file']['name'])) {
                throw new Exception('File PDF wajib diunggah');
            }

            $result = $model->tambahDataSop($_POST, $_FILES['file']);
            
            if ($result > 0) {
                $this->sendResponse('Berhasil ditambahkan');
            } else {
                throw new Exception('Gagal menyimpan data ke database');
            }
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), 400, 'error');
        }
    }

    /**
     * Memperbarui data SOP yang sudah ada.
     */
    public function update(): void {
        $this->prepareJsonResponse();

        try {
            $model = $this->loadModel();

            $this->validateInput($_POST, ['id_sop', 'judul']);

            $file = $_FILES['file'] ?? ['error' => 4];
            $result = $model->updateDataSop($_POST, $file);

            if ($result) {
                $this->sendResponse('Berhasil diupdate');
            } else {
                throw new Exception('Gagal memperbarui data atau tidak ada perubahan');
            }
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), 400, 'error');
        }
    }

    /**
     * Menghapus data SOP berdasarkan ID yang diekstrak dari URL.
     */
    public function delete(): void {
        $this->prepareJsonResponse();
        
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', rtrim($url, '/'));
        $id = end($segments);

        $model = $this->loadModel();

        if ($model->deleteSop($id) > 0) {
            $this->sendResponse('Data berhasil dihapus');
        } else {
            $this->sendResponse('Gagal menghapus data', 500, 'error');
        }
    }

    // =========================================================================
    // PRIVATE HELPER METHODS
    // =========================================================================

    /**
     * Memuat file model SOP secara dinamis.
     * @return object SopModel instance
     */
    private function loadModel(): object {
        require_once ROOT_PROJECT . '/app/models/SopModel.php';
        return new SopModel();
    }

    /**
     * Menyiapkan header dan membersihkan buffer untuk respon JSON.
     */
    private function prepareJsonResponse(): void {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Mengirimkan respon JSON standar dan menghentikan eksekusi.
     * Menggantikan fungsi handleError yang sebelumnya hilang.
     */
    private function sendResponse(string $message, int $code = 200, string $status = 'success'): void {
        if (ob_get_length()) ob_clean(); // Proteksi tambahan agar JSON bersih
        http_response_code($code);
        echo json_encode([
            'status'  => $status,
            'code'    => $code,
            'message' => $message
        ]);
        exit;
    }

    /**
     * Validasi sederhana untuk field yang wajib diisi.
     */
    private function validateInput(array $data, array $fields): void {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field " . ucwords(str_replace('_', ' ', $field)) . " wajib diisi");
            }
        }
    }
}