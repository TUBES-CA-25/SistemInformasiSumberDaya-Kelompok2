<?php
require_once CONTROLLER_PATH . '/Controller.php';

// Load Model
require_once __DIR__ . '/../models/AsistenModel.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';
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
            
            // 2. Ambil Koneksi Database (MySQLi)
            $db = new Database(); 
            
            // PERHATIAN: Pastikan di Database.php nama fungsinya getConnection() atau getPdo()
            // Jika error, coba ganti getPdo() menjadi getConnection()
            if (method_exists($db, 'getConnection')) {
                $mysqli = $db->getConnection();
            } else {
                $mysqli = $db->getPdo(); // Fallback jika Anda menamainya getPdo tapi isinya mysqli
            }

            // 3. Tentukan Hari Ini
            $days = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $hariIni = $days[date('l')]; 
            
            // $hariIni = 'Senin'; // Uncomment untuk testing

            // 4. Query Jadwal Hari Ini (GAYA MYSQLI)
            // Perbedaan 1: Placeholder menggunakan tanda tanya (?) bukan (:nama)
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

            // Perbedaan 2: Binding Parameter menggunakan bind_param
            // "s" artinya string
            $stmt->bind_param("s", $hariIni);
            
            // Perbedaan 3: Eksekusi
            $stmt->execute();

            // Perbedaan 4: Ambil Hasil (Get Result)
            $result = $stmt->get_result();
            
            // Perbedaan 5: Fetch All (MYSQLI_ASSOC)
            $jadwalHariIni = $result->fetch_all(MYSQLI_ASSOC);

            // 5. FILTER: Hapus jadwal yang sudah selesai berdasarkan waktuSelesai
            $jamSekarang = date('H:i');
            $jadwalAktif = array_filter($jadwalHariIni, function($jadwal) use ($jamSekarang) {
                // Bandingkan waktuSelesai dengan jam sekarang
                // Jika waktuSelesai > jamSekarang, maka masih aktif (belum selesai)
                return $jadwal['waktuSelesai'] > $jamSekarang;
            });
            // Re-index array agar tidak ada gap index
            $jadwalAktif = array_values($jadwalAktif);

            // 6. Hitung Statistik
            $totalAsisten = $asistenModel->countAll();
            $totalLab = $labModel->countAll();
            $totalJadwalToday = count($jadwalAktif); // Gunakan jadwalAktif, bukan jadwalHariIni

            // 7. Kirim Response
            $this->success([
                'total_asisten' => $totalAsisten,
                'total_lab' => $totalLab,
                'total_jadwal_hari_ini' => $totalJadwalToday,
                'jadwal_hari_ini' => $jadwalAktif  // Kirim jadwalAktif, bukan jadwalHariIni
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