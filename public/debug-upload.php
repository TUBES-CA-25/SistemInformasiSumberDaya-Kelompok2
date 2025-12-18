<?php
/**
 * Debug script untuk mengecek masalah upload
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Upload Jadwal Praktikum</h1>";

// Check if Composer autoload exists
echo "<h2>1. Check Composer Autoload</h2>";
$autoloadPath = '../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    echo "✅ Composer autoload found<br>";
    require_once $autoloadPath;
} else {
    echo "❌ Composer autoload NOT found at: " . $autoloadPath . "<br>";
}

// Check PhpSpreadsheet
echo "<h2>2. Check PhpSpreadsheet</h2>";
try {
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    echo "✅ PhpSpreadsheet loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading PhpSpreadsheet: " . $e->getMessage() . "<br>";
}

// Check config
echo "<h2>3. Check Config</h2>";
try {
    require_once '../app/config/config.php';
    echo "✅ Config loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading config: " . $e->getMessage() . "<br>";
}

// Check database connection
echo "<h2>4. Check Database</h2>";
try {
    require_once '../app/config/Database.php';
    $db = new Database();
    $connection = $db->getConnection();
    if ($connection) {
        echo "✅ Database connection successful<br>";
    } else {
        echo "❌ Database connection failed<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Check models
echo "<h2>5. Check Models</h2>";
try {
    require_once '../app/models/JadwalPraktikumModel.php';
    require_once '../app/models/MatakuliahModel.php';
    require_once '../app/models/LaboratoriumModel.php';
    echo "✅ Models loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading models: " . $e->getMessage() . "<br>";
}

// Check controller
echo "<h2>6. Check Controller</h2>";
try {
    require_once '../app/controllers/Controller.php';
    require_once '../app/controllers/JadwalPraktikumController.php';
    echo "✅ Controllers loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading controllers: " . $e->getMessage() . "<br>";
}

// Check upload settings
echo "<h2>7. Check PHP Upload Settings</h2>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "file_uploads: " . (ini_get('file_uploads') ? 'ON' : 'OFF') . "<br>";

// Test API routing
echo "<h2>8. Test API Routing</h2>";
$testRoutes = [
    '/jadwal-praktikum/template',
    '/jadwal-praktikum/upload'
];

foreach ($testRoutes as $route) {
    $fullUrl = "http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php" . $route;
    echo "Route: <a href='$fullUrl' target='_blank'>$route</a><br>";
}

echo "<h2>9. Quick Upload Test Form</h2>";
?>
<form action="api.php/jadwal-praktikum/upload" method="POST" enctype="multipart/form-data">
    <input type="file" name="excel_file" accept=".xlsx,.xls">
    <button type="submit">Test Upload</button>
</form>

<h2>10. Error Log Check</h2>
<p>Check your PHP error log or XAMPP error log for detailed error messages.</p>
<p>XAMPP error log location: C:\xampp\apache\logs\error.log</p>

<h2>11. Manual Controller Test</h2>
<?php
// Test controller instantiation
try {
    $controller = new \App\Controllers\JadwalPraktikumController();
    echo "✅ JadwalPraktikumController instantiated successfully<br>";
} catch (Exception $e) {
    echo "❌ Error instantiating controller: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>