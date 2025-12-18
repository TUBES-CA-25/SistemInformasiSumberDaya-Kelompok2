<?php
/**
 * Test page untuk fitur upload jadwal praktikum
 */

require_once '../app/config/config.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Upload Jadwal Praktikum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-info {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
    <h1>Test Upload Jadwal Praktikum</h1>
    
    <div class="section">
        <h2>Links Test</h2>
        <p>Klik link di bawah untuk mengetes fitur upload jadwal praktikum:</p>
        
        <a href="admin-jadwal-upload.php" class="btn">Upload Jadwal Excel</a>
        <a href="api.php/jadwal-praktikum/template" class="btn btn-success">Download Template</a>
        <a href="api.php/jadwal" class="btn btn-info">Lihat Jadwal (JSON)</a>
    </div>

    <div class="section">
        <h2>Test Upload Form</h2>
        <p>Form quick test untuk upload file:</p>
        
        <form action="api.php/jadwal-praktikum/upload" method="POST" enctype="multipart/form-data">
            <input type="file" name="excel_file" accept=".xlsx,.xls" required>
            <button type="submit" class="btn">Test Upload</button>
        </form>
    </div>

    <div class="section">
        <h2>Struktur Database</h2>
        <p>Tabel yang terlibat dalam upload jadwal praktikum:</p>
        
        <h4>Tabel jadwalPraktikum:</h4>
        <ul>
            <li>idJadwal (AUTO_INCREMENT)</li>
            <li>idMatakuliah (FK ke Matakuliah)</li>
            <li>idLaboratorium (FK ke Laboratorium)</li>
            <li>hari (VARCHAR)</li>
            <li>waktuMulai (TIME)</li>
            <li>waktuSelesai (TIME)</li>
            <li>kelas (VARCHAR)</li>
            <li>status (VARCHAR)</li>
        </ul>

        <h4>Dependencies:</h4>
        <ul>
            <li>Tabel Matakuliah (untuk validasi mata kuliah)</li>
            <li>Tabel Laboratorium (untuk validasi lab)</li>
        </ul>
    </div>

    <div class="section">
        <h2>Format Excel Expected</h2>
        <table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>Mata Kuliah</th>
                    <th>Laboratorium</th>
                    <th>Hari</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pemrograman Web</td>
                    <td>Lab Komputer 1</td>
                    <td>Senin</td>
                    <td>08:00</td>
                    <td>10:00</td>
                    <td>A</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>Basis Data</td>
                    <td>Lab Komputer 2</td>
                    <td>Selasa</td>
                    <td>10:00</td>
                    <td>12:00</td>
                    <td>B</td>
                    <td>Aktif</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>API Endpoints</h2>
        <ul>
            <li><strong>POST</strong> /api.php/jadwal-praktikum/upload - Upload Excel file</li>
            <li><strong>GET</strong> /api.php/jadwal-praktikum/template - Download template Excel</li>
            <li><strong>GET</strong> /api.php/jadwal - Get all jadwal</li>
        </ul>
    </div>
</body>
</html>