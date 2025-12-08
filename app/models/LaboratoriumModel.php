<?php
require_once __DIR__ . '/Model.php';

class LaboratoriumModel extends Model {
    protected $table = 'Laboratorium';

    public function getLaboratoriumWithJadwal($idLab) {
        $query = "SELECT l.*, j.* FROM Laboratorium l 
                  LEFT JOIN jadwalPraktikum j ON l.idLaboratorium = j.idLaboratorium
                  WHERE l.idLaboratorium = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idLab);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
