<?php
require_once ROOT_PROJECT . '/app/models/Model.php';

class SanksiModel extends Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all sanksi lab
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM sanksi_lab ORDER BY created_at DESC";
            $result = $this->db->query($query);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting all sanksi: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get sanksi by ID
     */
    public function getById($id, $idColumn = 'id') {
        try {
            $query = "SELECT * FROM sanksi_lab WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting sanksi by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Create new sanksi
     */
    public function create($data) {
        try {
            $query = "INSERT INTO sanksi_lab (judul, deskripsi, gambar) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sss", 
                $data['judul'],
                $data['deskripsi'],
                $data['gambar']
            );
            $stmt->execute();
            return $this->db->insert_id;
        } catch (Exception $e) {
            error_log("Error creating sanksi: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update sanksi
     */
    public function update($id, $data, $idColumn = 'id') {
        try {
            $query = "UPDATE sanksi_lab SET judul = ?, deskripsi = ?, gambar = ?, updated_at = NOW() WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssi", 
                $data['judul'],
                $data['deskripsi'],
                $data['gambar'],
                $id
            );
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error updating sanksi: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete sanksi
     */
    public function delete($id, $idColumn = 'id') {
        try {
            $query = "DELETE FROM sanksi_lab WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (PDOException $e) {
            error_log("Error deleting sanksi: " . $e->getMessage());
            return false;
        }
    }
}
?>