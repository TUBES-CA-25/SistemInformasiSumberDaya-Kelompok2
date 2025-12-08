<?php
require_once __DIR__ . '/Model.php';

class AsistenMatakuliahModel extends Model {
    protected $table = 'AsistenMatakuliah';

    public function getAsistenMatakuliahByYear($tahunAjaran) {
        $query = "SELECT * FROM AsistenMatakuliah WHERE tahunAjaran = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $tahunAjaran);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
