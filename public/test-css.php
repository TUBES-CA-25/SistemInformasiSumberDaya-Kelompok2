<?php
// Test halaman untuk lihat apa yang dihasilkan
define('ROOT_PROJECT', dirname(__DIR__));

// Echo PATH debug
echo "<!-- ROOT_PROJECT: " . ROOT_PROJECT . " -->\n";
echo "<!-- Curr DIR: " . __DIR__ . " -->\n";

require_once ROOT_PROJECT . '/app/config.php';

echo "<!-- BASE_URL: " . BASE_URL . " -->\n";
echo "<!-- API_URL: " . API_URL . " -->\n";
echo "<!-- ASSETS_URL: " . ASSETS_URL . " -->\n";
echo "<!-- CSS URL should be: " . ASSETS_URL . "/css/style.css -->\n";

// Check file exists
$css_path = ROOT_PROJECT . '/public/css/style.css';
echo "<!-- CSS file path: " . $css_path . " -->\n";
echo "<!-- CSS file exists: " . (file_exists($css_path) ? 'YES' : 'NO') . " -->\n";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Header</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <style>
        body { background: #f0f0f0; padding: 20px; }
        .test-box { background: white; padding: 20px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>

<div class="test-box">
    <h1>Test CSS Loading</h1>
    <p>CSS URL: <strong><?php echo ASSETS_URL; ?>/css/style.css</strong></p>
    <p>BASE_URL: <strong><?php echo BASE_URL; ?></strong></p>
    
    <h2>Navigation Test (seharusnya ada styling)</h2>
    <nav class="navbar" style="border: 1px solid red; padding: 10px;">
        <div class="container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>/public/index.php">Logo Test</a>
            </div>
            <ul class="nav-links">
                <li><a href="#">Menu 1</a></li>
                <li><a href="#">Menu 2</a></li>
            </ul>
        </div>
    </nav>
</div>

</body>
</html>
