<?php
/**
 * Test Database Insert
 */

require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/app/config/Database.php';

$database = new Database();
$conn = $database->connect();

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Test insert data
$nama = "Lab Komputer Test";
$deskripsi = "Laboratorium untuk testing";
$gambar = "lab-test.jpg";
$jumlahKursi = 25;

$query = "INSERT INTO Laboratorium (nama, deskripsi, gambar, jumlahKursi) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
}

$stmt->bind_param("sssi", $nama, $deskripsi, $gambar, $jumlahKursi);
$result = $stmt->execute();

if ($result) {
    // Get all data
    $get_query = "SELECT * FROM Laboratorium";
    $get_result = $conn->query($get_query);
    $data = $get_result->fetch_all(MYSQLI_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Insert successful',
        'insert_id' => $conn->insert_id,
        'all_data' => $data
    ], JSON_PRETTY_PRINT);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Insert failed: ' . $stmt->error
    ], JSON_PRETTY_PRINT);
}

$stmt->close();
$conn->close();
?>
