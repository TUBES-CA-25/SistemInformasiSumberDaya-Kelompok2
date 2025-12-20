<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/Database.php';

$db = new Database();
$conn = $db->connect(); // Method is connect() not getConnection() based on class definition

$sql = "ALTER TABLE Asisten 
ADD COLUMN IF NOT EXISTS jabatan VARCHAR(100) AFTER jurusan,
ADD COLUMN IF NOT EXISTS kategori VARCHAR(50) AFTER jabatan,
ADD COLUMN IF NOT EXISTS lab VARCHAR(100) AFTER kategori,
ADD COLUMN IF NOT EXISTS spesialisasi VARCHAR(255) AFTER lab,
ADD COLUMN IF NOT EXISTS bio TEXT AFTER spesialisasi,
ADD COLUMN IF NOT EXISTS skills TEXT AFTER bio";

if ($conn->query($sql) === TRUE) {
    echo "Table Asisten updated successfully successfully with new columns.";
} else {
    echo "Error updating table: " . $conn->error;
}
?>
