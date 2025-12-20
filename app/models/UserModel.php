<?php
require_once __DIR__ . '/Model.php';

class UserModel extends Model {
    protected $table = 'users';

    public function getByUsername($username) {
        $username = $this->db->real_escape_string($username);
        $query = "SELECT * FROM " . $this->table . " WHERE username = '$username'";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    public function updateLastLogin($id) {
        $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
