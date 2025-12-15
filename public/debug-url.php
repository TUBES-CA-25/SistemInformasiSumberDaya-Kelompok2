<?php
// Debug URL configuration
define('ROOT_PROJECT', dirname(__DIR__)); 
require_once ROOT_PROJECT . '/app/config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug URL Config</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; border-radius: 5px; margin: 10px 0; border: 1px solid #ddd; }
        .label { font-weight: bold; color: #333; }
        .value { color: #0066cc; word-break: break-all; }
        .check { padding: 10px; margin: 5px 0; border-radius: 3px; }
        .ok { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<h1>🔍 Debug URL Configuration</h1>

<div class="box">
    <div class="label">BASE_URL:</div>
    <div class="value"><?php echo BASE_URL; ?></div>
</div>

<div class="box">
    <div class="label">API_URL:</div>
    <div class="value"><?php echo API_URL; ?></div>
</div>

<div class="box">
    <div class="label">ASSETS_URL:</div>
    <div class="value"><?php echo ASSETS_URL; ?></div>
</div>

<div class="box">
    <div class="label">CSS File Path:</div>
    <div class="value"><?php echo ASSETS_URL . '/css/style.css'; ?></div>
    <?php 
    $css_file = dirname(__DIR__) . '/public/css/style.css';
    if (file_exists($css_file)) {
        echo '<div class="check ok">✓ CSS file exists locally</div>';
    } else {
        echo '<div class="check error">✗ CSS file NOT found at: ' . $css_file . '</div>';
    }
    ?>
</div>

<div class="box">
    <div class="label">Test Links:</div>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>/public/index.php">Home</a></li>
        <li><a href="<?php echo BASE_URL; ?>/public/alumni.php">Alumni</a></li>
        <li><a href="<?php echo BASE_URL; ?>/public/admin-alumni.php">Admin Alumni</a></li>
    </ul>
</div>

<div class="box">
    <div class="label">Server Variables:</div>
    <ul>
        <li>HTTP_HOST: <?php echo $_SERVER['HTTP_HOST']; ?></li>
        <li>SCRIPT_NAME: <?php echo $_SERVER['SCRIPT_NAME']; ?></li>
        <li>REQUEST_URI: <?php echo $_SERVER['REQUEST_URI']; ?></li>
        <li>HTTPS: <?php echo isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'Not set'; ?></li>
    </ul>
</div>

</body>
</html>
