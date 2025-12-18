<?php
/**
 * Script untuk debug dan test jadwal praktikum
 */

require_once '../app/config/config.php';
require_once '../app/config/Database.php';

$database = new Database();
$conn = $database->connect();

echo "<h1>Debug Jadwal Praktikum</h1>";

// Test database connection
if ($conn->connect_error) {
    die("<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>");
}
echo "<p style='color: green;'>✅ Database connected</p>";

// Check tabel structure
echo "<h2>1. Struktur Tabel jadwalPraktikum</h2>";
$result = $conn->query("DESCRIBE jadwalPraktikum");
if ($result) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Tabel jadwalPraktikum tidak ditemukan</p>";
}

// Check data count
echo "<h2>2. Jumlah Data</h2>";
$result = $conn->query("SELECT COUNT(*) as total FROM jadwalPraktikum");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p>Total data jadwal: " . $row['total'] . "</p>";
} else {
    echo "<p style='color: red;'>❌ Error counting data</p>";
}

// Test JOIN query (seperti yang digunakan di model)
echo "<h2>3. Test JOIN Query</h2>";
$query = "SELECT j.*, 
                 m.namaMatakuliah, m.kodeMatakuliah,
                 l.nama as namaLab, l.nama as namaLaboratorium
          FROM jadwalPraktikum j
          JOIN Matakuliah m ON j.idMatakuliah = m.idMatakuliah
          JOIN Laboratorium l ON j.idLaboratorium = l.idLaboratorium
          ORDER BY j.idJadwal DESC";

$result = $conn->query($query);
if ($result) {
    echo "<p style='color: green;'>✅ JOIN Query berhasil</p>";
    echo "<p>Rows found: " . $result->num_rows . "</p>";
    
    if ($result->num_rows > 0) {
        echo "<h4>Sample Data (5 rows):</h4>";
        echo "<table border='1' cellpadding='5' style='font-size: 12px;'>";
        echo "<tr><th>ID</th><th>Mata Kuliah</th><th>Laboratorium</th><th>Hari</th><th>Waktu</th><th>Kelas</th><th>Status</th></tr>";
        
        $count = 0;
        while ($row = $result->fetch_assoc() && $count < 5) {
            echo "<tr>";
            echo "<td>" . $row['idJadwal'] . "</td>";
            echo "<td>" . $row['namaMatakuliah'] . " (" . $row['kodeMatakuliah'] . ")</td>";
            echo "<td>" . $row['namaLab'] . "</td>";
            echo "<td>" . $row['hari'] . "</td>";
            echo "<td>" . $row['waktuMulai'] . " - " . $row['waktuSelesai'] . "</td>";
            echo "<td>" . $row['kelas'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "</tr>";
            $count++;
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ Tidak ada data dengan JOIN</p>";
    }
} else {
    echo "<p style='color: red;'>❌ JOIN Query gagal: " . $conn->error . "</p>";
}

// Test direct API call
echo "<h2>4. Test API Call</h2>";
$api_url = "http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/jadwal";
echo "<p>Testing: <a href='$api_url' target='_blank'>$api_url</a></p>";

// Test raw data in jadwalPraktikum table
echo "<h2>5. Raw Data jadwalPraktikum</h2>";
$result = $conn->query("SELECT * FROM jadwalPraktikum ORDER BY idJadwal DESC LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>ID MK</th><th>ID Lab</th><th>Hari</th><th>Waktu Mulai</th><th>Waktu Selesai</th><th>Kelas</th><th>Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['idJadwal'] . "</td>";
        echo "<td>" . $row['idMatakuliah'] . "</td>";
        echo "<td>" . $row['idLaboratorium'] . "</td>";
        echo "<td>" . $row['hari'] . "</td>";
        echo "<td>" . $row['waktuMulai'] . "</td>";
        echo "<td>" . $row['waktuSelesai'] . "</td>";
        echo "<td>" . $row['kelas'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Tidak ada data di tabel jadwalPraktikum</p>";
}

$conn->close();
?>

<h2>6. Quick Links</h2>
<ul>
    <li><a href="api.php/jadwal">Test API /jadwal</a></li>
    <li><a href="admin-jadwal.php">Admin Jadwal Page</a></li>
    <li><a href="upload-jadwal-csv.php">Upload CSV</a></li>
    <li><a href="data-referensi.php">Data Referensi</a></li>
</ul>