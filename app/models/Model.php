<?php
/**
 * Base Model Class
 * Parent class untuk semua model
 */

class Model {
    public $db;
    protected $table;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Get database connection
     */
    public function getDb() {
        return $this->db;
    }

    /**
     * Get semua data
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get data by ID
     */
    public function getById($id, $idColumn = 'id') {
        $query = "SELECT * FROM " . $this->table . " WHERE " . $idColumn . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Insert data
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_fill(0, count($data), "?"));
        $types = str_repeat("s", count($data));

        $query = "INSERT INTO " . $this->table . " (" . $columns . ") VALUES (" . $values . ")";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param($types, ...array_values($data));
        $result = $stmt->execute();
        
        if (!$result) {
            return false;
        }
        
        $stmt->close();
        return true;
    }

    /**
     * Update data
     */
    public function update($id, $data, $idColumn = 'id') {
        $set = "";
        $types = "";
        $values = [];

        foreach ($data as $column => $value) {
            $set .= $column . " = ?, ";
            $types .= "s";
            $values[] = $value;
        }

        $set = rtrim($set, ", ");
        $types .= "i";
        $values[] = $id;

        $query = "UPDATE " . $this->table . " SET " . $set . " WHERE " . $idColumn . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    /**
     * Delete data
     */
    public function delete($id, $idColumn = 'id') {
        $query = "DELETE FROM " . $this->table . " WHERE " . $idColumn . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Count data
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Get last insert ID
     */
    public function getLastInsertId() {
        return $this->db->insert_id;
    }
}
?>
