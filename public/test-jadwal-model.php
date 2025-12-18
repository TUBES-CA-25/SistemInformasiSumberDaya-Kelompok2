<?php
/**
 * Test API jadwal langsung tanpa frontend
 */

require_once '../app/config/config.php';
require_once '../app/config/Database.php';
require_once '../app/models/Model.php';
require_once '../app/models/JadwalPraktikumModel.php';

$model = new JadwalPraktikumModel();

echo "<h1>Test JadwalPraktikumModel</h1>";

try {
    echo "<h2>1. Test getAll() method</h2>";
    $data = $model->getAll();
    
    echo "<p>Total records: " . count($data) . "</p>";
    
    if (!empty($data)) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Mata Kuliah</th><th>Lab</th><th>Hari</th><th>Waktu</th><th>Kelas</th><th>Status</th></tr>";
        
        foreach (array_slice($data, 0, 10) as $row) { // Limit 10 rows
            echo "<tr>";
            echo "<td>" . $row['idJadwal'] . "</td>";
            echo "<td>" . ($row['namaMatakuliah'] ?? 'NULL') . " (" . ($row['kodeMatakuliah'] ?? '-') . ")</td>";
            echo "<td>" . ($row['namaLab'] ?? 'NULL') . "</td>";
            echo "<td>" . ($row['hari'] ?? 'NULL') . "</td>";
            echo "<td>" . ($row['waktuMulai'] ?? 'NULL') . " - " . ($row['waktuSelesai'] ?? 'NULL') . "</td>";
            echo "<td>" . ($row['kelas'] ?? 'NULL') . "</td>";
            echo "<td>" . ($row['status'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Sample Raw Data (JSON):</h3>";
        echo "<pre>" . json_encode(array_slice($data, 0, 2), JSON_PRETTY_PRINT) . "</pre>";
        
    } else {
        echo "<p style='color: red;'>❌ No data found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test API call secara langsung
echo "<h2>2. Test Direct API Call</h2>";
try {
    require_once '../app/controllers/Controller.php';
    require_once '../app/controllers/JadwalPraktikumController.php';
    
    $controller = new \App\Controllers\JadwalPraktikumController();
    
    echo "<p>Controller created successfully</p>";
    
    // Capture output
    ob_start();
    $controller->index();
    $apiOutput = ob_get_clean();
    
    echo "<h3>API Response:</h3>";
    echo "<pre>" . htmlspecialchars($apiOutput) . "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ API Error: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Quick Links</h2>";
echo "<ul>";
echo "<li><a href='api.php/jadwal'>API /jadwal endpoint</a></li>";
echo "<li><a href='admin-jadwal.php'>Admin Jadwal Page</a></li>";
echo "<li><a href='debug-jadwal.php'>Debug Jadwal</a></li>";
echo "</ul>";
?>