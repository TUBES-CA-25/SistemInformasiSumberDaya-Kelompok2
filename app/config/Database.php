<?php
require_once __DIR__ . '/config.php';

class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $user = DB_USER;
    private $password = DB_PASS;
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection Error: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8");
        } catch(Exception $e) {
            // Tampilkan error hanya di mode development
            if (defined('APP_ENV') && APP_ENV === 'development') {
                die($e->getMessage());
            } else {
                die("Database connection problem.");
            }
        }

        return $this->conn;
    }
}

$db = new Database();
$conn = $db->connect();

function query($query) {
    global $conn;
    
    $result = mysqli_query($conn, $query);

    if (!$result) {
        if (defined('APP_ENV') && APP_ENV === 'development') {
            die("Query Error: " . mysqli_error($conn));
        }
        return [];
    }

    if (is_object($result)) {
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    return mysqli_affected_rows($conn);
}
?>