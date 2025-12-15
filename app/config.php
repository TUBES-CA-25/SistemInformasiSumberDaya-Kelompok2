<?php
/**
 * Application Configuration
 * Auto-detect base URL untuk fleksibilitas deployment
 */

// Detect protokol (HTTP atau HTTPS)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

// Detect host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Detect script path (dirname dari SCRIPT_NAME)
// Jika SCRIPT_NAME = /SistemManagementSumberDaya/public/index.php
// dirname = /SistemManagementSumberDaya/public
// dirname lagi = /SistemManagementSumberDaya
$baseScriptPath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
if (empty($baseScriptPath) || $baseScriptPath === '.') {
    $baseScriptPath = '';
}

// Build Base URL (untuk navigation/links)
define('BASE_URL', $protocol . $host . $baseScriptPath);

// API URL (untuk fetch calls dari browser - harus dari root / absolute path)
define('API_URL', $baseScriptPath . '/public/api.php');

// ASSETS URL (untuk CSS, JS, images - relatif path dari browser root)
define('ASSETS_URL', $baseScriptPath . '/public');

// Debug (uncomment untuk testing)
// echo "<!-- DEBUG CONFIG -->\n";
// echo "<!-- Protocol: $protocol -->\n";
// echo "<!-- Host: $host -->\n";
// echo "<!-- Base Script Path: $baseScriptPath -->\n";
// echo "<!-- BASE_URL: " . BASE_URL . " -->\n";
// echo "<!-- API_URL: " . API_URL . " -->\n";
// echo "<!-- ASSETS_URL: " . ASSETS_URL . " -->\n";
// echo "<!-- /DEBUG CONFIG -->\n";
?>
