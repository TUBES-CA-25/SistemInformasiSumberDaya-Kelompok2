<?php
// Proteksi awal: Bersihkan semua buffer karakter sampah
while (ob_get_level()) { ob_end_clean(); } 

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/JadwalPraktikumModel.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class JadwalPraktikumController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \JadwalPraktikumModel();
    }

    public function uploadExcel() {
        // Pastikan respon murni JSON untuk menghindari "Unexpected token <"
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json');

        try {
            if (!isset($_FILES['excel_file'])) throw new Exception("File tidak ditemukan.");

            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $success = 0;

            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) { $rowData[] = $cell->getFormattedValue(); }

                if (empty(array_filter($rowData))) continue;

                // MAPPING BERDASARKAN FILE EXCEL ANDA (Jadwal Lab.xlsx)
                $dosenNama = trim($rowData[2] ?? '');  // Kolom C (Dosen)
                $mkNama    = trim($rowData[3] ?? '');  // Kolom D (Mata Kuliah)
                $kelas     = trim($rowData[5] ?? '');  // Kolom F (Kelas)
                $freq      = trim($rowData[6] ?? '');  // Kolom G (Frekuensi)
                $labNama   = trim($rowData[7] ?? '');  // Kolom H (Ruangan)
                $hari      = trim($rowData[8] ?? '');  // Kolom I (Hari)
                $jamFull   = str_replace('.', ':', trim($rowData[9] ?? '')); // Kolom J (Jam)
                $asisten1  = trim($rowData[11] ?? ''); // Kolom L (Asisten 1)
                $asisten2  = trim($rowData[12] ?? ''); // Kolom M (Asisten 2)

                // Split jam (Contoh: 07:00 - 09:30)
                $parts = explode('-', $jamFull);
                $start = trim($parts[0] ?? '00:00');
                $end   = isset($parts[1]) ? trim($parts[1]) : $start;

                // ANTI-0 IMPOR: Cari atau buat otomatis data master
                $idMK = $this->findOrCreateMaster('matakuliah', 'namaMatakuliah', $mkNama);
                $idLab = $this->findOrCreateMaster('laboratorium', 'nama', $labNama);

                if ($idMK && $idLab) {
                    $this->model->insert([
                        'idMatakuliah'   => $idMK,
                        'kelas'          => $kelas,
                        'idLaboratorium' => $idLab,
                        'hari'           => ucfirst(strtolower($hari)),
                        'waktuMulai'     => $start,
                        'waktuSelesai'   => $end,
                        'dosen'          => $dosenNama,
                        'asisten1'       => $asisten1,
                        'asisten2'       => $asisten2,
                        'frekuensi'      => $freq,
                        'status'         => 'Aktif'
                    ]);
                    $success++;
                }
            }
            echo json_encode(['status' => 'success', 'message' => "Berhasil mengimpor $success data."]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    private function findOrCreateMaster($table, $column, $name) {
        if (empty($name)) return null;
        $db = $this->model->db;
        $stmt = $db->prepare("SELECT * FROM $table WHERE LCASE(TRIM($column)) = LCASE(TRIM(?)) LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        if ($res) return $res[array_key_first($res)];
        
        // Buat baru jika tidak ditemukan (Menghindari gagal impor karena data master kosong)
        $db->query("INSERT INTO $table ($column) VALUES ('".addslashes($name)."')");
        return $db->insert_id;
    }

    public function apiIndex() {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $this->model->getAll()]);
        exit;
    }
}