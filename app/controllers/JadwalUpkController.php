<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        if (isset($_FILES['file_import']['tmp_name'])) {
            $file = $_FILES['file_import'];
            $filename = $file['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if ($ext === 'csv') {
                if ($this->model('JadwalUpkModel')->importCSV($file['tmp_name'])) {
                    header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
                    exit;
                }
            } elseif ($ext === 'xlsx' || $ext === 'xls') {
                try {
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

                        // Mapping based on common format: No, Prodi, Tanggal, Jam, MK, Dosen, Freq, Kelas, Ruangan
                        // Assuming Column A (index 0) is "No", so we start from index 1
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
                            header('Location: ' . PUBLIC_URL . '/admin/jadwalupk');
                            exit;
                        }
                    }
                } catch (Exception $e) {
                    // Log error if needed
                    header('Location: ' . PUBLIC_URL . '/admin/jadwalupk?error=' . urlencode($e->getMessage()));
                    exit;
                }
            }
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