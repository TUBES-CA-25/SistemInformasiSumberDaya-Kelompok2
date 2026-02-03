<?php

class DashboardService {
    private $asistenModel;
    private $labModel;
    private $alumniModel;
    private $db;

    public function __construct() {
        $this->asistenModel = new AsistenModel();
        $this->labModel = new FasilitasModel();
        $this->alumniModel = new AlumniModel();
        $this->db = new Database();
    }

    /**
     * Mengambil semua statistik dashboard dalam satu paket data
     */
    public function getDashboardStats() {
        // Set timezone WITA
        date_default_timezone_set('Asia/Makassar');

        $mysqli = method_exists($this->db, 'getConnection') ? $this->db->getConnection() : $this->db->getPdo();
        
        $hariEng = date('l');
        $dayNames = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIndo = $dayNames[$hariEng];

        // Ambil Jadwal
        $jadwalHariIni = $this->fetchJadwalByDay($mysqli, $hariIndo);
        
        // Filter Jadwal Aktif (Sedang Berlangsung)
        $jamSekarang = date('H:i:s');
        $jadwalAktif = array_values(array_filter($jadwalHariIni, function($jadwal) use ($jamSekarang) {
            return ($jamSekarang >= $jadwal['waktuMulai'] && $jamSekarang <= $jadwal['waktuSelesai']);
        }));

        return [
            'total_asisten' => $this->asistenModel->countAll(),
            'total_lab' => $this->labModel->countAll(),
            'total_alumni' => $this->alumniModel->countAll(),
            'total_jadwal_hari_ini' => count($jadwalAktif),
            'jadwal_hari_ini' => $jadwalAktif,
            'server_time' => $jamSekarang,
            'hari' => $hariIndo
        ];
    }

    private function fetchJadwalByDay($mysqli, $hari) {
        $sql = "SELECT j.*, m.namaMatakuliah, m.kodeMatakuliah, l.nama as namaLab 
                FROM jadwalpraktikum j
                JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                WHERE j.hari = ? AND j.status = 'Aktif'
                ORDER BY j.waktuMulai ASC";

        $stmt = $mysqli->prepare($sql);
        if (!$stmt) throw new Exception("Database Error: " . $mysqli->error);

        $stmt->bind_param("s", $hari);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}