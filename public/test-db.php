<?php
// Test koneksi database
require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/app/config/Database.php';

$database = new Database();
$conn = $database->connect();

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get list of tables
$result = $conn->query("SHOW TABLES");
$tables = [];

while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'Database connection successful',
    'database' => DB_NAME,
    'tables_count' => count($tables),
    'tables' => $tables
], JSON_PRETTY_PRINT);

$conn->close();
?>
