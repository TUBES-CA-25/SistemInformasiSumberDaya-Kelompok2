<?php
/**
 * Alter Laboratorium Table - Add Missing Columns
 */

require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/app/config/Database.php';

$database = new Database();
$conn = $database->connect();

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if columns exist and add if missing
$queries = [
    "ALTER TABLE Laboratorium ADD COLUMN idKordinatorAsisten INT AFTER nama",
    "ALTER TABLE Laboratorium ADD FOREIGN KEY (idKordinatorAsisten) REFERENCES Asisten(idAsisten) ON DELETE SET NULL",
    "ALTER TABLE Laboratorium ADD COLUMN jumlahPc INT AFTER gambar",
];

$results = [];
foreach ($queries as $query) {
    $result = $conn->query($query);
    if ($result) {
        $results[] = ['query' => $query, 'status' => 'success'];
    } else {
        // Column might already exist, that's okay
        $results[] = ['query' => $query, 'status' => 'skipped', 'error' => $conn->error];
    }
}

// Get table structure
$describe = "DESCRIBE Laboratorium";
$result = $conn->query($describe);
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'Laboratorium table updated',
    'operations' => $results,
    'current_columns' => $columns
], JSON_PRETTY_PRINT);

$conn->close();
?>
