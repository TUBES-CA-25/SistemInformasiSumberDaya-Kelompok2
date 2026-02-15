<?php

/**
 * JadwalUpkModel
 * * Model ini bertanggung jawab untuk mengelola data Jadwal UPK (Ujian Praktikum Komputer).
 * Mendukung operasi CRUD standar, penghapusan massal, serta impor data via Excel/CSV.
 * * @package App\Models
 */
class JadwalUpkModel {
    /** @var string Nama tabel database */
    private $table = 'jadwalupk';
    
    /** @var PDO Instance koneksi database */
    private $db;

    /**
     * Inisialisasi koneksi database menggunakan driver PDO.
     */
    public function __construct() {
        $database = new Database;
        $this->db = $database->getPdo();
    }

    /**
     * Mengambil semua jadwal ujian praktikum.
     * * Data diurutkan secara kronologis berdasarkan tanggal dan jam.
     * * @return array Kumpulan data jadwal dalam format associative array.
     */
    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY tanggal ASC, jam ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail satu jadwal berdasarkan ID.
     * * @param int $id ID Jadwal.
     * @return array|bool Data jadwal atau false jika tidak ditemukan.
     */
    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Menambahkan data jadwal UPK baru.
     * * @param array $data Data input dari form.
     * @return bool Status keberhasilan eksekusi.
     */
    public function create(array $data): bool {
        $sql = "INSERT INTO {$this->table} (prodi, mata_kuliah, dosen, tanggal, jam, kelas, ruangan, frekuensi) 
                VALUES (:prodi, :mata_kuliah, :dosen, :tanggal, :jam, :kelas, :ruangan, :frekuensi)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'prodi'       => $data['prodi'],
            'mata_kuliah' => $data['mata_kuliah'],
            'dosen'       => $data['dosen'],
            'tanggal'     => $data['tanggal'],
            'jam'         => $data['jam'],
            'kelas'       => $data['kelas'],
            'ruangan'     => $data['ruangan'],
            'frekuensi'   => $data['frekuensi'] ?? ''
        ]);
    }

    /**
     * Memperbarui data jadwal UPK yang sudah ada.
     * * @param int $id ID Jadwal yang akan diubah.
     * @param array $data Data pembaharuan.
     * @return bool Status keberhasilan.
     */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET 
                prodi = :prodi, mata_kuliah = :mata_kuliah, dosen = :dosen, 
                tanggal = :tanggal, jam = :jam, kelas = :kelas, 
                ruangan = :ruangan, frekuensi = :frekuensi 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'          => $id,
            'prodi'       => $data['prodi'],
            'mata_kuliah' => $data['mata_kuliah'],
            'dosen'       => $data['dosen'],
            'tanggal'     => $data['tanggal'],
            'jam'         => $data['jam'],
            'kelas'       => $data['kelas'],
            'ruangan'     => $data['ruangan'],
            'frekuensi'   => $data['frekuensi'] ?? ''
        ]);
    }

    /**
     * Menghapus satu data jadwal.
     * * @param int $id ID Jadwal.
     * @return bool
     */
    public function deleteJadwal(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Menghapus banyak data jadwal sekaligus (Bulk Delete).
     * * @param array $ids Kumpulan ID jadwal yang akan dihapus.
     * @return bool
     */
    public function deleteMultiple(array $ids): bool {
        if (empty($ids)) return false;
        
        $ids = array_values($ids);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        $sql = "DELETE FROM {$this->table} WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($ids);
    }

    /**
     * Melakukan impor data massal (biasanya dari array hasil olahan Excel).
     * Menggunakan Database Transaction untuk menjamin data tidak rusak jika terjadi error.
     * * 
     * * @param array $dataList Kumpulan data mentah hasil parsing file.
     * @return bool True jika seluruh data berhasil masuk.
     */
    public function importData(array $dataList): bool {
        try {
            $this->db->beginTransaction();

            // Mengosongkan tabel sebelum impor baru (Reset data)
            $this->db->exec("DELETE FROM {$this->table}");

            $sql = "INSERT INTO {$this->table} (prodi, tanggal, jam, mata_kuliah, dosen, frekuensi, kelas, ruangan) 
                    VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);

            foreach ($dataList as $row) {
                $stmt->execute([
                    $row['prodi']       ?? null,
                    $row['tanggal']     ?? null,
                    $row['jam']         ?? null,
                    $row['mata_kuliah'] ?? null,
                    $row['dosen']       ?? null,
                    $row['frekuensi']   ?? '',
                    $row['kelas']       ?? '',
                    $row['ruangan']     ?? ''
                ]);
            }
            
            if ($this->db->inTransaction()) {
                $this->db->commit();
            }
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Impor Data Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Impor data langsung dari file CSV.
     * * @param string $filename Path file CSV di server.
     * @return bool
     */
    public function importCSV(string $filename): bool {
        try {
            if (!file_exists($filename) || !($handle = fopen($filename, "r"))) {
                return false;
            }

            // Identifikasi delimiter secara otomatis
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

            $this->db->beginTransaction();
            $this->db->exec("DELETE FROM {$this->table}");

            $sql = "INSERT INTO {$this->table} (prodi, tanggal, jam, mata_kuliah, dosen, frekuensi, kelas, ruangan) 
                    VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);

            // Lewati baris header
            fgetcsv($handle, 0, $delimiter);

            while (($row = fgetcsv($handle, 4000, $delimiter)) !== FALSE) {
                // Handling jika seluruh kolom dibungkus kutip ganda
                if (count($row) == 1 && !empty($row[0])) {
                    $row = str_getcsv($row[0], ',');
                }

                if (empty($row[0])) continue;

                // Konversi tanggal cerdas (Contoh: "29 December 2025" -> "2025-12-29")
                $tanggalRaw = trim($row[2] ?? '');
                $tanggalDB  = !empty($tanggalRaw) ? date('Y-m-d', strtotime($tanggalRaw)) : null;

                $stmt->execute([
                    trim($row[1] ?? ''), // Prodi
                    $tanggalDB,          // Tanggal (Formatted)
                    trim($row[3] ?? ''), // Jam
                    trim($row[4] ?? ''), // MK
                    trim($row[5] ?? ''), // Dosen
                    trim($row[6] ?? ''), // Freq
                    trim($row[7] ?? ''), // Kelas
                    trim($row[8] ?? '')  // Ruangan
                ]);
            }

            fclose($handle);
            if ($this->db->inTransaction()) {
                $this->db->commit();
            }
            return true;
        } catch (Exception $e) {
            if (isset($handle)) fclose($handle);
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }
}