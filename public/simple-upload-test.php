<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Upload Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
        .success { color: green; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h1>Simple Upload Test for Jadwal Praktikum</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h2>Debug Information:</h2>";
        echo "<div class='info'>";
        echo "POST data received<br>";
        echo "Files count: " . count($_FILES) . "<br>";
        
        if (isset($_FILES['excel_file'])) {
            $file = $_FILES['excel_file'];
            echo "File name: " . $file['name'] . "<br>";
            echo "File size: " . $file['size'] . " bytes<br>";
            echo "File type: " . $file['type'] . "<br>";
            echo "File error: " . $file['error'] . "<br>";
            echo "Temp file: " . $file['tmp_name'] . "<br>";
            echo "File exists: " . (file_exists($file['tmp_name']) ? 'Yes' : 'No') . "<br>";
        }
        echo "</div>";
        
        // Now try to call API directly
        echo "<h2>Calling API:</h2>";
        
        // Simulate form data to API
        $ch = curl_init();
        $apiUrl = "http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/jadwal-praktikum/upload";
        
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $postFields = array(
                'excel_file' => new CURLFile($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['type'], $_FILES['excel_file']['name'])
            );
            
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            echo "<div class='info'>";
            echo "HTTP Code: " . $httpCode . "<br>";
            if ($error) {
                echo "<div class='error'>cURL Error: " . $error . "</div>";
            }
            echo "Response: <pre>" . htmlspecialchars($response) . "</pre>";
            echo "</div>";
        } else {
            echo "<div class='error'>No file uploaded or upload error</div>";
        }
    }
    ?>
    
    <h2>Upload Form:</h2>
    <form method="POST" enctype="multipart/form-data">
        <p>Select Excel file:</p>
        <input type="file" name="excel_file" accept=".xlsx,.xls" required>
        <br><br>
        <button type="submit">Upload and Test</button>
    </form>
    
    <h2>Quick Links:</h2>
    <ul>
        <li><a href="data-referensi.php" style="color: blue; font-weight: bold;">📋 Lihat Data Mata Kuliah & Lab</a></li>
        <li><a href="fix-zip-extension.php" style="color: red; font-weight: bold;">🔧 Fix ZIP Extension Problem</a></li>
        <li><a href="upload-jadwal-csv.php" style="color: green; font-weight: bold;">📄 Upload CSV (Alternative)</a></li>
        <li><a href="api.php/jadwal-praktikum/template">Download Template Excel</a></li>
        <li><a href="api.php/jadwal-praktikum/csv-template">Download Template CSV</a></li>
        <li><a href="test-api-upload.php">API Test Page</a></li>
        <li><a href="debug-upload.php">Debug Upload</a></li>
        <li><a href="generate-sample-excel.php">Generate Sample Excel</a></li>
    </ul>
    
    <h2>Error Log Tips:</h2>
    <p>Check XAMPP error logs at:</p>
    <ul>
        <li>C:\xampp\apache\logs\error.log</li>
        <li>C:\xampp\php\logs\php_error_log</li>
    </ul>
</body>
</html>