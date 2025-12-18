<?php
/**
 * Contoh penggunaan PhpSpreadsheet untuk import/export Excel
 */

require_once '../app/config/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Fungsi untuk export data ke Excel
 */
function exportToExcel($data, $headers, $filename = 'export.xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set headers
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }
    
    // Set data
    $row = 2;
    foreach ($data as $rowData) {
        $col = 'A';
        foreach ($rowData as $value) {
            $sheet->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }
    
    // Style headers
    $headerRange = 'A1:' . chr(64 + count($headers)) . '1';
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $sheet->getStyle($headerRange)->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FFCCCCCC');
    
    // Auto-size columns
    foreach (range('A', chr(64 + count($headers))) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Output file
    $writer = new Xlsx($spreadsheet);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}

/**
 * Fungsi untuk import data dari Excel
 */
function importFromExcel($filename) {
    if (!file_exists($filename)) {
        throw new Exception("File tidak ditemukan: " . $filename);
    }
    
    $spreadsheet = IOFactory::load($filename);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = [];
    
    foreach ($worksheet->getRowIterator() as $row) {
        $rowData = [];
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        $data[] = $rowData;
    }
    
    return $data;
}

// Contoh penggunaan
if ($_GET['action'] == 'export_sample') {
    // Contoh data untuk export
    $headers = ['ID', 'Nama', 'Email', 'Tanggal Lahir', 'Status'];
    $data = [
        [1, 'John Doe', 'john@example.com', '1990-01-15', 'Aktif'],
        [2, 'Jane Smith', 'jane@example.com', '1992-03-22', 'Aktif'],
        [3, 'Bob Johnson', 'bob@example.com', '1988-07-10', 'Nonaktif']
    ];
    
    exportToExcel($data, $headers, 'contoh_export.xlsx');
}

if ($_POST['action'] == 'import' && isset($_FILES['excel_file'])) {
    try {
        $uploadedFile = $_FILES['excel_file']['tmp_name'];
        $data = importFromExcel($uploadedFile);
        
        echo "<h3>Data berhasil diimport:</h3>";
        echo "<table border='1'>";
        foreach ($data as $index => $row) {
            echo "<tr>";
            foreach ($row as $cell) {
                if ($index == 0) {
                    echo "<th>" . htmlspecialchars($cell) . "</th>";
                } else {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Jika tidak ada action, tampilkan form
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh PhpSpreadsheet</title>
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
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        input[type="file"] {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Contoh Penggunaan PhpSpreadsheet</h1>
    
    <div class="section">
        <h2>Export Excel</h2>
        <p>Klik tombol di bawah untuk mengunduh contoh file Excel:</p>
        <button onclick="window.location.href='?action=export_sample'">Download Contoh Excel</button>
    </div>
    
    <div class="section">
        <h2>Import Excel</h2>
        <p>Upload file Excel untuk melihat isinya:</p>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="import">
            <input type="file" name="excel_file" accept=".xlsx,.xls" required>
            <button type="submit">Upload dan Import</button>
        </form>
    </div>
    
    <div class="section">
        <h2>Dokumentasi</h2>
        <p>PhpSpreadsheet telah berhasil diinstall dan siap digunakan. Anda dapat menggunakannya untuk:</p>
        <ul>
            <li>Membaca file Excel (.xlsx, .xls)</li>
            <li>Menulis file Excel dengan formatting</li>
            <li>Mengkonversi data dari database ke Excel</li>
            <li>Import data Excel ke database</li>
        </ul>
        
        <h3>Contoh Penggunaan di Controller:</h3>
        <pre><code>
// Di dalam Controller
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Export data alumni ke Excel
public function exportAlumni() {
    $alumni = $this->alumniModel->getAll();
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set headers
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nama');
    $sheet->setCellValue('C1', 'Email');
    // ... dst
    
    // Set data
    $row = 2;
    foreach ($alumni as $item) {
        $sheet->setCellValue('A' . $row, $item['id']);
        $sheet->setCellValue('B' . $row, $item['nama']);
        $sheet->setCellValue('C' . $row, $item['email']);
        // ... dst
        $row++;
    }
    
    $writer = new Xlsx($spreadsheet);
    // Output atau save file
}
        </code></pre>
    </div>
</body>
</html>