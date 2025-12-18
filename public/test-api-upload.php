<?php
/**
 * Simple test script for upload endpoint
 */

// Test basic API endpoint
echo "<h1>Test API Upload Endpoint</h1>";

// Test if API is accessible
echo "<h2>Testing API endpoint access...</h2>";

$baseUrl = "http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php";

// Test GET template endpoint
echo "<h3>1. Testing Template Download (GET)</h3>";
$templateUrl = $baseUrl . "/jadwal-praktikum/template";
echo "<a href='$templateUrl' target='_blank'>Download Template</a><br>";

// Test basic API
echo "<h3>2. Testing Basic API (GET)</h3>";
$healthUrl = $baseUrl . "/health";
echo "<a href='$healthUrl' target='_blank'>Health Check</a><br>";

// Show form for testing upload
echo "<h3>3. Testing Upload (POST)</h3>";
?>
<form action="<?php echo $baseUrl; ?>/jadwal-praktikum/upload" method="POST" enctype="multipart/form-data" target="_blank">
    <p>Select an Excel file:</p>
    <input type="file" name="excel_file" accept=".xlsx,.xls" required>
    <br><br>
    <button type="submit">Test Upload</button>
</form>

<h3>4. Manual Test with cURL</h3>
<p>You can also test manually with cURL:</p>
<pre>
curl -X POST \
  -F "excel_file=@/path/to/your/file.xlsx" \
  <?php echo $baseUrl; ?>/jadwal-praktikum/upload
</pre>

<h3>5. Check PHP Configuration</h3>
<table border="1">
    <tr><th>Setting</th><th>Value</th></tr>
    <tr><td>file_uploads</td><td><?php echo ini_get('file_uploads') ? 'ON' : 'OFF'; ?></td></tr>
    <tr><td>upload_max_filesize</td><td><?php echo ini_get('upload_max_filesize'); ?></td></tr>
    <tr><td>post_max_size</td><td><?php echo ini_get('post_max_size'); ?></td></tr>
    <tr><td>max_file_uploads</td><td><?php echo ini_get('max_file_uploads'); ?></td></tr>
    <tr><td>max_input_time</td><td><?php echo ini_get('max_input_time'); ?></td></tr>
    <tr><td>max_execution_time</td><td><?php echo ini_get('max_execution_time'); ?></td></tr>
</table>

<h3>6. PHP Errors Check</h3>
<?php
// Check if error reporting is enabled
echo "display_errors: " . (ini_get('display_errors') ? 'ON' : 'OFF') . "<br>";
echo "error_reporting: " . ini_get('error_reporting') . "<br>";
echo "log_errors: " . (ini_get('log_errors') ? 'ON' : 'OFF') . "<br>";
echo "error_log: " . ini_get('error_log') . "<br>";
?>