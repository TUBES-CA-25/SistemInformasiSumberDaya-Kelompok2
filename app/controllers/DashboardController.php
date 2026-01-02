<?php
require_once CONTROLLER_PATH . '/Controller.php';

// Load Model
require_once __DIR__ . '/../models/AsistenModel.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';
// Load Database Wrapper (Penting agar class Database dikenali)
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
            
            // 2. [PERBAIKAN] Buat Instance Database Manual
            // Karena $this->db tidak ada, kita buat baru:
            $db = new Database(); 
            $pdo = $db->getPdo(); // Ambil koneksi PDO

            // 3. Tentukan Hari Ini
            $days = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $hariIni = $days[date('l')]; 
            
            // Uncomment baris bawah ini jika ingin tes data hari Senin
            // $hariIni = 'Senin'; 

            // 4. Query Jadwal Hari Ini
            // Pastikan nama tabel 'jadwalpraktikum' sesuai database Anda
            $sql = "SELECT 
                        j.*, 
                        m.namaMatakuliah, 
                        m.kodeMatakuliah, 
                        l.nama as namaLab 
                    FROM jadwalpraktikum j
                    JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                    JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                    WHERE j.hari = :hari 
                    AND j.status = 'Aktif'
                    ORDER BY j.waktuMulai ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['hari' => $hariIni]);
            $jadwalHariIni = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 5. Hitung Statistik
            $totalAsisten = $asistenModel->countAll();
            $totalLab = $labModel->countAll();
            $totalJadwalToday = count($jadwalHariIni);

            // 6. Kirim Response
            $this->success([
                'total_asisten' => $totalAsisten,
                'total_lab' => $totalLab,
                'total_jadwal_hari_ini' => $totalJadwalToday,
                'jadwal_hari_ini' => $jadwalHariIni
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