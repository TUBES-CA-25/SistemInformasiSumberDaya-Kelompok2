<?php

/**
 * DashboardController - Kelola Dashboard Admin & Statistik Sistem
 * 
 * Menangani:
 * - Tampilan halaman dashboard admin
 * - Pengambilan statistik sistem (asisten, laboratorium, alumni)
 * - Tracking jadwal praktikum hari ini yang sedang berlangsung
 * - API endpoint untuk real-time dashboard updates
 * 
 * Database Integration:
 * - AsistenModel: Data asisten laboratorium
 * - LaboratoriumModel: Data laboratorium
 * - AlumniModel: Data alumni
 * - Database: Koneksi MySQLi/PDO
 * 
 * Timezone: Asia/Makassar (UTC+8 - WITA)
 */

require_once CONTROLLER_PATH . '/Controller.php';

// Load Model untuk statistik
require_once __DIR__ . '/../models/AsistenModel.php';
require_once __DIR__ . '/../models/FasilitasModel.php';
require_once __DIR__ . '/../models/AlumniModel.php';

// Load Database Wrapper untuk query jadwal
require_once __DIR__ . '/../config/Database.php';

class DashboardController extends Controller {
    
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var string[] Mapping nama hari dari English ke Indonesia */
    private $dayNames = [
        'Sunday' => 'Minggu', 
        'Monday' => 'Senin', 
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 
        'Thursday' => 'Kamis', 
        'Friday' => 'Jumat', 
        'Saturday' => 'Sabtu'
    ];

    
    // =========================================================================
    // BAGIAN 2: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Index - Tampilkan halaman dashboard admin
     * 
     * Menampilkan overview dashboard dengan statistik dan jadwal hari ini.
     * Tidak menampilkan data langsung di view - data diambil via API endpoint
     * stats() untuk real-time updates.
     * 
     * @return void Menampilkan view admin/dashboard/index
     */
    public function index() {
        $this->view('admin/dashboard/index');
    }

    /**
     * Stats API - Ambil statistik sistem dan jadwal hari ini
     * 
     * Mengambil data statistik real-time untuk dashboard:
     * 1. Set timezone ke Asia/Makassar (WITA UTC+8)
     * 2. Inisialisasi models (AsistenModel, LaboratoriumModel, AlumniModel)
     * 3. Ambil koneksi database untuk query jadwal
     * 4. Tentukan nama hari hari ini
     * 5. Query jadwal praktikum hari ini yang status Aktif
     * 6. Filter jadwal yang SEDANG BERLANGSUNG (jam sekarang dalam range)
     * 7. Hitung total asisten, lab, alumni
     * 8. Return statistik dan jadwal aktif sebagai JSON
     * 
     * Filter Jadwal:
     * - Hanya untuk hari hari ini
     * - Status = 'Aktif'
     * - Jam mulai <= jam sekarang <= jam selesai
     * 
     * Response Format:
     * ```json
     * {
     *   "status": "success",
     *   "message": "...",
     *   "data": {
     *     "total_asisten": 5,
     *     "total_lab": 3,
     *     "total_alumni": 25,
     *     "total_jadwal_hari_ini": 2,
     *     "jadwal_hari_ini": [...]
     *   }
     * }
     * ```
     * 
     * @return void JSON response dengan statistik dan jadwal
     */
    public function stats() {
        try {
            // LANGKAH 1: Set timezone ke WITA (UTC+8) untuk sinkronisasi jam
            date_default_timezone_set('Asia/Makassar');

            // LANGKAH 2: Inisialisasi model-model untuk statistik
            $asistenModel = new AsistenModel();
            $labModel = new FasilitasModel();
            $alumniModel = new AlumniModel();
            
            // LANGKAH 3: Ambil koneksi database untuk query jadwal
            // Kompatibel dengan MySQLi atau PDO
            $db = new Database(); 
            
            if (method_exists($db, 'getConnection')) {
                $mysqli = $db->getConnection();
            } else {
                $mysqli = $db->getPdo();
            }

            // LANGKAH 4: Tentukan nama hari hari ini berdasarkan timezone
            $hariIni = $this->dayNames[date('l')]; 

            // LANGKAH 5: Query jadwal praktikum hari ini yang aktif
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
                throw new Exception("Database Error: " . $mysqli->error);
            }

            $stmt->bind_param("s", $hariIni);
            $stmt->execute();
            $result = $stmt->get_result();
            $jadwalHariIni = $result->fetch_all(MYSQLI_ASSOC);

            // LANGKAH 6: Filter hanya jadwal yang SEDANG BERLANGSUNG di jam sekarang
            // Kriteria: waktuMulai <= jamSekarang <= waktuSelesai
            $jamSekarang = date('H:i:s');
            $jadwalAktif = array_filter($jadwalHariIni, function($jadwal) use ($jamSekarang) {
                return ($jamSekarang >= $jadwal['waktuMulai'] && 
                        $jamSekarang <= $jadwal['waktuSelesai']);
            });
            $jadwalAktif = array_values($jadwalAktif);

            // LANGKAH 7: Hitung statistik total
            $totalAsisten = $asistenModel->countAll();
            $totalLab = $labModel->countAll();
            $totalAlumni = $alumniModel->countAll();
            $totalJadwalToday = count($jadwalAktif);

            // LANGKAH 8: Return response JSON dengan statistik
            $this->success([
                'total_asisten' => $totalAsisten,
                'total_lab' => $totalLab,
                'total_alumni' => $totalAlumni,
                'total_jadwal_hari_ini' => $totalJadwalToday,
                'jadwal_hari_ini' => $jadwalAktif
            ], 'Statistik dashboard berhasil diambil');

        } catch (Exception $e) {
            // Log error dan return response error
            error_log('Dashboard stats error: ' . $e->getMessage());
            $this->error('Gagal mengambil statistik dashboard: ' . $e->getMessage(), null, 500);
        }
    }
}