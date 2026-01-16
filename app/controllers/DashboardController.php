<?php
require_once CONTROLLER_PATH . '/Controller.php';

// Load Model
require_once __DIR__ . '/../models/AsistenModel.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';
require_once __DIR__ . '/../models/AlumniModel.php';
// Load Database Wrapper
require_once __DIR__ . '/../config/Database.php';

class DashboardController extends Controller {
    
    public function index() {
        $this->view('admin/dashboard/index');
    }

    public function stats() {
        try {
            // 1. Inisialisasi Model
            $asistenModel = new AsistenModel();
            $labModel = new LaboratoriumModel();
            $alumniModel = new AlumniModel();
            
            // 2. Ambil Koneksi Database (MySQLi)
            $db = new Database(); 
            
            // PERHATIAN: Pastikan di Database.php nama fungsinya getConnection() atau getPdo()
            if (method_exists($db, 'getConnection')) {
                $mysqli = $db->getConnection();
            } else {
                $mysqli = $db->getPdo();
            }

            // 3. Tentukan Hari Ini
            $days = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $hariIni = $days[date('l')]; 

            // 4. Query Jadwal Hari Ini
            $sql = "SELECT 
                        j.*, 
                        m.namaMatakuliah, 
                        m.kodeMatakuliah, 
                        l.nama as namaLab 
                    FROM jadwalpraktikum j
                    JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                    JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                    WHERE j.hari = ? 
                    AND j.status = 'Aktif'
                    ORDER BY j.waktuMulai ASC";

            $stmt = $mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query Error: " . $mysqli->error);
            }
            $stmt->bind_param("s", $hariIni);
            $stmt->execute();
            $result = $stmt->get_result();
            $jadwalHariIni = $result->fetch_all(MYSQLI_ASSOC);

            // 5. FILTER: Hapus jadwal yang sudah selesai
            // Set timezone ke WITA (UTC+8)
            date_default_timezone_set('Asia/Makassar');
            $jamSekarang = date('H:i');
            $jadwalAktif = array_filter($jadwalHariIni, function($jadwal) use ($jamSekarang) {
                return $jadwal['waktuSelesai'] > $jamSekarang;
            });
            $jadwalAktif = array_values($jadwalAktif);

            // 6. Hitung Statistik
            $totalAsisten = $asistenModel->countAll();
            $totalLab = $labModel->countAll();
            $totalAlumni = $alumniModel->countAll();
            $totalJadwalToday = count($jadwalAktif);

            // 7. Kirim Response
            $this->success([
                'total_asisten' => $totalAsisten,
                'total_lab' => $totalLab,
                'total_alumni' => $totalAlumni,
                'total_jadwal_hari_ini' => $totalJadwalToday,
                'jadwal_hari_ini' => $jadwalAktif
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}