<?php
class JadwalUpkModel {
    private $table = 'jadwalupk';
    private $db;

    public function __construct() {
        $database = new Database;
        $this->db = $database->getPdo();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM $this->table ORDER BY tanggal ASC, jam ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteJadwal($id) {
        $stmt = $this->db->prepare("DELETE FROM $this->table WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function importCSV($filename) {
        // Kosongkan tabel
        $this->db->exec("TRUNCATE TABLE " . $this->table);

        if (($handle = fopen($filename, "r")) !== FALSE) {
            // Deteksi delimiter (; atau ,)
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

            // Lewati Header
            fgetcsv($handle, 0, $delimiter);

            $sql = "INSERT INTO $this->table (prodi, tanggal, jam, mata_kuliah, dosen, frekuensi, kelas, ruangan) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);

            while (($row = fgetcsv($handle, 4000, $delimiter)) !== FALSE) {
                // Penanganan jika satu baris dibungkus kutip ganda (seperti file Anda)
                if (count($row) == 1 && !empty($row[0])) {
                    $row = str_getcsv($row[0], ',');
                }

                if (empty($row[0])) continue;

                // Mapping kolom (Prodi, Tanggal, Jam, MK, Dosen, Freq, Kelas, Ruangan)
                $prodi   = trim($row[0] ?? '');
                $rawTgl  = trim($row[1] ?? ''); // "29 December 2025"
                $jam     = trim($row[2] ?? '');
                $mk      = trim($row[3] ?? '');
                $dosen   = trim($row[4] ?? '');
                $freq    = trim($row[5] ?? '');
                $kelas   = trim($row[6] ?? '');
                $ruangan = trim($row[7] ?? '');

                // Konversi tanggal cerdas
                $tanggalDB = date('Y-m-d', strtotime($rawTgl));

                $stmt->execute([$prodi, $tanggalDB, $jam, $mk, $dosen, $freq, $kelas, $ruangan]);
            }
            fclose($handle);
            return true;
        }
        return false;
    }
}