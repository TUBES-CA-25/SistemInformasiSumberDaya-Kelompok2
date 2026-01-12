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

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (prodi, mata_kuliah, dosen, tanggal, jam, kelas, ruangan, frekuensi) 
                VALUES (:prodi, :mata_kuliah, :dosen, :tanggal, :jam, :kelas, :ruangan, :frekuensi)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'prodi' => $data['prodi'],
            'mata_kuliah' => $data['mata_kuliah'],
            'dosen' => $data['dosen'],
            'tanggal' => $data['tanggal'],
            'jam' => $data['jam'],
            'kelas' => $data['kelas'],
            'ruangan' => $data['ruangan'],
            'frekuensi' => $data['frekuensi'] ?? ''
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table SET 
                prodi = :prodi, 
                mata_kuliah = :mata_kuliah, 
                dosen = :dosen, 
                tanggal = :tanggal, 
                jam = :jam, 
                kelas = :kelas, 
                ruangan = :ruangan, 
                frekuensi = :frekuensi 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'prodi' => $data['prodi'],
            'mata_kuliah' => $data['mata_kuliah'],
            'dosen' => $data['dosen'],
            'tanggal' => $data['tanggal'],
            'jam' => $data['jam'],
            'kelas' => $data['kelas'],
            'ruangan' => $data['ruangan'],
            'frekuensi' => $data['frekuensi'] ?? ''
        ]);
    }

    public function deleteMultiple($ids) {
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "DELETE FROM $this->table WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($ids);
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