<?php
require_once __DIR__ . '/Model.php';

class AsistenModel extends Model {
    protected $table = 'asisten';

    /**
     * Get all asisten sorted by urutan tampilan
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY urutanTampilan ASC, nama ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAsistenByEmail($email) {
        $query = "SELECT * FROM Asisten WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAsistenMatakuliah($idAsisten) {
        $query = "SELECT am.*, m.namaMatakuliah, m.kodeMatakuliah 
                  FROM AsistenMatakuliah am
                  JOIN Matakuliah m ON am.idMatakuliah = m.idMatakuliah
                  WHERE am.idAsisten = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idAsisten);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Reset semua koordinator ke 0
     */
    public function resetAllKoordinator() {
        $query = "UPDATE Asisten SET isKoordinator = 0";
        $stmt = $this->db->prepare($query);
        return $stmt->execute();
    }
}
?>
