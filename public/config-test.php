<?php
define('ROOT_PROJECT', dirname(__DIR__));
require_once ROOT_PROJECT . '/app/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Config Debug</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 15px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        strong { color: #0066cc; }
    </style>
</head>
<body>

<h2>URL Configuration Debug</h2>

<div class="box">
    <strong>BASE_URL:</strong><br>
    <?php echo BASE_URL; ?>
</div>

<div class="box">
    <strong>API_URL:</strong><br>
    <?php echo API_URL; ?>
</div>

<div class="box">
    <strong>ASSETS_URL:</strong><br>
    <?php echo ASSETS_URL; ?>
</div>

<div class="box">
    <strong>CSS Path:</strong><br>
    <?php echo ASSETS_URL . '/css/style.css'; ?>
</div>

<div class="box">
    <strong>JS Path:</strong><br>
    <?php echo ASSETS_URL . '/js/script.js'; ?>
</div>

<h2>Test Links</h2>

<div class="box">
    <a href="<?php echo BASE_URL; ?>/public/index.php">Home</a><br>
    <a href="<?php echo BASE_URL; ?>/public/alumni.php">Alumni</a><br>
    <a href="<?php echo BASE_URL; ?>/public/admin-alumni.php">Admin Alumni</a>
</div>

<h2>API Test (Buka Console)</h2>

<div class="box">
    <button onclick="testAPI()">Test Fetch Alumni</button>
    <div id="result" style="margin-top: 10px; white-space: pre-wrap; background: #f9f9f9; padding: 10px; border: 1px solid #ddd; border-radius: 3px; display: none;"></div>
</div>

<script>
const API_URL = '<?php echo API_URL; ?>';
const BASE_URL = '<?php echo BASE_URL; ?>';

console.log('API_URL:', API_URL);
console.log('BASE_URL:', BASE_URL);

function testAPI() {
    const resultDiv = document.getElementById('result');
    resultDiv.style.display = 'block';
    resultDiv.textContent = 'Loading...';
    
    fetch(API_URL + '/alumni')
        .then(res => res.json())
        .then(data => {
            resultDiv.textContent = JSON.stringify(data, null, 2);
        })
        .catch(err => {
            resultDiv.textContent = 'ERROR: ' + err.message;
            console.error(err);
        });
}
</script>

</body>
</html>
