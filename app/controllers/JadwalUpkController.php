<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

class JadwalUpkController extends Controller {

    public function model($model) {
        if (defined('MODEL_PATH')) {
            require_once MODEL_PATH . '/' . $model . '.php';
        } else {
            require_once dirname(__DIR__) . '/models/' . $model . '.php';
        }
        return new $model;
    }

    public function index() {
        $data['judul'] = 'Jadwal UPK Praktikum';
        $data['jadwal'] = $this->model('JadwalUpkModel')->getAll();
        $this->view('praktikum/jadwalupk', $data);
    }

    public function adminIndex() {
        $data['judul'] = 'Kelola Jadwal UPK';
        
        // PASTI KAN baris ini ada untuk mengambil data dari DB
        $data['jadwal'] = $this->model('JadwalUpkModel')->getAll();
        
        // PASTI KAN variabel $data dikirim sebagai argumen kedua
        $this->view('admin/jadwalupk/index', $data);
    }

    public function upload() {
        // Bersihkan output buffer untuk mencegah karakter sampah
        while (ob_get_level()) { ob_end_clean(); }
        
        // Set header JSON respons jika dipanggil via AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strpos($_SERVER['REQUEST_URI'], 'api.php') !== false || isset($_FILES['excel_file']);
        
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
        }

        $fileKey = isset($_FILES['excel_file']) ? 'excel_file' : (isset($_FILES['file_import']) ? 'file_import' : null);

        if ($fileKey && isset($_FILES[$fileKey]['tmp_name'])) {
            $file = $_FILES[$fileKey];
            $filename = $file['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            try {
                if ($ext === 'csv') {
                    if ($this->model('JadwalUpkModel')->importCSV($file['tmp_name'])) {
                        if ($isAjax) {
                            echo json_encode(['status' => 'success', 'message' => 'Data CSV berhasil diimpor']);
                            exit;
                        }
                        header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
                        exit;
                    }
                } elseif ($ext === 'xlsx' || $ext === 'xls') {
                    $spreadsheet = IOFactory::load($file['tmp_name']);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $dataImport = [];

                    foreach ($worksheet->getRowIterator(2) as $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        $rowData = [];
                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getFormattedValue();
                        }

                        if (empty(array_filter($rowData))) continue;

                        $dataImport[] = [
                            'prodi'       => trim($rowData[1] ?? ''),
                            'tanggal'     => date('Y-m-d', strtotime(trim($rowData[2] ?? ''))),
                            'jam'         => trim($rowData[3] ?? ''),
                            'mata_kuliah' => trim($rowData[4] ?? ''),
                            'dosen'       => trim($rowData[5] ?? ''),
                            'frekuensi'   => trim($rowData[6] ?? ''),
                            'kelas'       => trim($rowData[7] ?? ''),
                            'ruangan'     => trim($rowData[8] ?? '')
                        ];
                    }

                    if (!empty($dataImport)) {
                        if ($this->model('JadwalUpkModel')->importData($dataImport)) {
                            if ($isAjax) {
                                echo json_encode(['status' => 'success', 'message' => 'Data Excel berhasil diimpor']);
                                exit;
                            }
                            header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
                            exit;
                        }
                    }
                }
                throw new Exception("Gagal memproses file atau format tidak didukung.");
            } catch (Exception $e) {
                if ($isAjax) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit;
                }
                header('Location: ' . PUBLIC_URL . '/admin/jadwalupk?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
        
        if ($isAjax) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang diunggah']);
            exit;
        }
        header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
        exit;
    }

    public function delete($id) {
        // Handle $id dari router API (array) atau router admin (numeric)
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;

        if (!$targetId) {
            if (strpos($_SERVER['REQUEST_URI'], 'api.php') !== false) {
                echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
                exit;
            }
            header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
            exit;
        }

        $result = $this->model('JadwalUpkModel')->deleteJadwal($targetId);

        // Jika request dari API
        if (strpos($_SERVER['REQUEST_URI'], 'api.php') !== false) {
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data di database']);
            }
            exit;
        }

        // Jika request dari admin biasa
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
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        $data = $this->model('JadwalUpkModel')->getById($targetId);
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
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($this->model('JadwalUpkModel')->update($targetId, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate data']);
        }
    }

    public function deleteMultiple($params = []) {
        // Bersihkan output buffer untuk mencegah karakter sampah
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];

            if (empty($ids)) {
                echo json_encode(['status' => 'error', 'message' => 'Tidak ada data dipilih']);
                exit;
            }

            if ($this->model('JadwalUpkModel')->deleteMultiple($ids)) {
                echo json_encode(['status' => 'success', 'message' => count($ids) . ' data berhasil dihapus']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data dari database']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
        }
        exit;
    }
}