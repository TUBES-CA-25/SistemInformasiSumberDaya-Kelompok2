<?php
require_once ROOT_PROJECT . '/app/models/Model.php';

class PeraturanModel extends Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all peraturan lab
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM peraturan_lab ORDER BY urutan ASC, created_at DESC";
            $result = $this->db->query($query);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting all peraturan: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get peraturan by ID
     */
    public function getById($id, $idColumn = 'id') {
        try {
            $query = "SELECT * FROM peraturan_lab WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting peraturan by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Create new peraturan
     */
    public function create($data) {
        try {
            $query = "INSERT INTO peraturan_lab (judul, deskripsi, gambar, urutan) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssi", 
                $data['judul'],
                $data['deskripsi'],
                $data['gambar'],
                $data['urutan']
            );
            $stmt->execute();
            return $this->db->insert_id;
        } catch (Exception $e) {
            error_log("Error creating peraturan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update peraturan
     */
    public function update($id, $data, $idColumn = 'id') {
        try {
            $query = "UPDATE peraturan_lab SET judul = ?, deskripsi = ?, gambar = ?, urutan = ?, updated_at = NOW() WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssii", 
                $data['judul'],
                $data['deskripsi'],
                $data['gambar'],
                $data['urutan'],
                $id
            );
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error updating peraturan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete peraturan
     */
    public function delete($id, $idColumn = 'id') {
        try {
            $query = "DELETE FROM peraturan_lab WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error deleting peraturan: " . $e->getMessage());
            return false;
        }
    }
}
?>