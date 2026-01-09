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

    public function getAllUsers() {
        $query = "SELECT id, username, role, last_login, created_at FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->db->query($query);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getUserById($id) {
        $query = "SELECT id, username, role, last_login, created_at FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function store($data) {
        $username = $this->db->real_escape_string($data['username']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $role = $this->db->real_escape_string($data['role']);

        $query = "INSERT INTO " . $this->table . " (username, password, role, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $password, $role);
        return $stmt->execute();
    }

    public function update($id, $data, $idColumn = 'id') {
        $username = $this->db->real_escape_string($data['username']);
        $role = $this->db->real_escape_string($data['role']);
        
        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->table . " SET username = ?, password = ?, role = ? WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssi", $username, $password, $role, $id);
        } else {
            $query = "UPDATE " . $this->table . " SET username = ?, role = ? WHERE " . $idColumn . " = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssi", $username, $role, $id);
        }
        
        return $stmt->execute();
    }

    public function delete($id, $idColumn = 'id') {
        $query = "DELETE FROM " . $this->table . " WHERE " . $idColumn . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
