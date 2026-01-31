<?php

require_once __DIR__ . '/Model.php';

class LaboratoriumGambarModel extends Model
{
    protected $table = 'laboratorium_gambar';
    protected $primaryKey = 'idGambar';

    /**
     * Get all images for a specific laboratorium
     */
    public function getByLaboratorium($idLaboratorium)
    {
        $sql = "SELECT * FROM {$this->table} WHERE idLaboratorium = ? ORDER BY isUtama DESC, urutan ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idLaboratorium);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Insert a new image record
     */
    public function insertImage($idLaboratorium, $namaGambar, $isUtama = 0, $urutan = 0)
    {
        $sql = "INSERT INTO {$this->table} (idLaboratorium, namaGambar, isUtama, urutan, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isii', $idLaboratorium, $namaGambar, $isUtama, $urutan);
        return $stmt->execute();
    }

    /**
     * Set an image as main (isUtama = 1)
     */
    public function setAsMain($idGambar)
    {
        $sql = "UPDATE {$this->table} SET isUtama = 1 WHERE idGambar = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idGambar);
        return $stmt->execute();
    }

    /**
     * Delete a specific image
     */
    public function deleteImage($idGambar)
    {
        $sql = "DELETE FROM {$this->table} WHERE idGambar = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idGambar);
        return $stmt->execute();
    }

    /**
     * Delete all images for a laboratorium (cascade)
     */
    public function deleteByLaboratorium($idLaboratorium)
    {
        $sql = "DELETE FROM {$this->table} WHERE idLaboratorium = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idLaboratorium);
        return $stmt->execute();
    }

    /**
     * Count images for a laboratorium
     */
    public function countByLaboratorium($idLaboratorium)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE idLaboratorium = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idLaboratorium);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
}
?>
