<?php
/**
 * Check All Data in Database
 */

require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/app/config/Database.php';

$database = new Database();
$conn = $database->connect();

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get all data with count
$count_query = "SELECT COUNT(*) as total FROM Laboratorium";
$count_result = $conn->query($count_query);
$count_row = $count_result->fetch_assoc();

$query = "SELECT * FROM Laboratorium ORDER BY idLaboratorium DESC";
$result = $conn->query($query);
$data = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'total_count' => $count_row['total'],
    'data' => $data
], JSON_PRETTY_PRINT);

$conn->close();
?>
