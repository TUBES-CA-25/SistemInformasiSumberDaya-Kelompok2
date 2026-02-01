<?php

/**
 * UserModel Class
 * * Mengelola fungsionalitas database untuk tabel 'users', termasuk autentikasi,
 * manajemen akun administrator, dan hashing password.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class UserModel extends Model {
    
    /** @var string Nama tabel di database */
    protected $table = 'users';

    /**
     * Mencari user berdasarkan username.
     * * Digunakan pada proses login dan validasi registrasi.
     * * @param string $username
     * @return array|null Data user atau null jika tidak ditemukan.
     */
    public function getByUsername(string $username): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc() ?: null;
        }
        
        return null;
    }

    /**
     * Memperbarui timestamp login terakhir user.
     * * @param int $id ID User.
     * @return bool
     */
    public function updateLastLogin(int $id): bool {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        
        return false;
    }

    /**
     * Mengambil semua daftar user tanpa menyertakan hash password.
     * * @return array Daftar user diurutkan dari yang terbaru.
     */
    public function getAllUsers(): array {
        $sql = "SELECT id, username, role, last_login, created_at 
                FROM {$this->table} 
                ORDER BY created_at DESC";
                
        $result = $this->db->query($sql);
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Mengambil detail profil user berdasarkan ID (Tanpa password).
     * * @param int $id
     * @return array|null
     */
    public function getUserById(int $id): ?array {
        $sql = "SELECT id, username, role, last_login, created_at 
                FROM {$this->table} 
                WHERE id = ? LIMIT 1";
                
        $stmt = $this->db->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc() ?: null;
        }
        
        return null;
    }

    /**
     * Menambahkan user administrator baru.
     * * Menggunakan password_hash untuk keamanan penyimpanan kredensial.
     * * @param array $data Data input (username, password, role).
     * @return bool
     */
    public function store(array $data): bool {
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO {$this->table} (username, password, role, created_at) 
                VALUES (?, ?, ?, NOW())";
                
        $stmt = $this->db->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("sss", $data['username'], $passwordHash, $data['role']);
            return $stmt->execute();
        }
        
        return false;
    }

    /**
     * Memperbarui data user.
     * * Secara cerdas mendeteksi apakah password perlu diubah atau tidak.
     * * @param int|string $id
     * @param array $data Data update.
     * @param string $idColumn Nama kolom primary key.
     * @return bool
     */
    public function update($id, array $data, string $idColumn = 'id'): bool {
        $hasPassword = !empty($data['password']);
        
        if ($hasPassword) {
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE {$this->table} SET username = ?, password = ?, role = ? WHERE {$idColumn} = ?";
        } else {
            $sql = "UPDATE {$this->table} SET username = ?, role = ? WHERE {$idColumn} = ?";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt) {
            if ($hasPassword) {
                $stmt->bind_param("sssi", $data['username'], $passwordHash, $data['role'], $id);
            } else {
                $stmt->bind_param("ssi", $data['username'], $data['role'], $id);
            }
            return $stmt->execute();
        }
        
        return false;
    }

    /**
     * Menghapus user berdasarkan ID.
     * * @param int|string $id
     * @param string $idColumn
     * @return bool
     */
    public function delete($id, string $idColumn = 'id'): bool {
        $sql = "DELETE FROM {$this->table} WHERE {$idColumn} = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        
        return false;
    }
}