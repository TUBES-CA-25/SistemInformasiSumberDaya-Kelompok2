<?php
// Script kecil untuk menguji rendering view fasilitas/detail.php dari CLI
chdir(__DIR__ . '/..');
define('ROOT_PROJECT', getcwd());
define('VIEW_PATH', ROOT_PROJECT . '/app/views');
define('PUBLIC_URL', 'http://localhost');

require_once 'app/Services/FasilitasService.php';
$s = new FasilitasService();
$full = $s->getFullDetail(30);
if (!$full) { echo "Service returned null\n"; exit(1); }
$full['laboratorium'] = $full['lab'];

ob_start();
require VIEW_PATH . '/fasilitas/detail.php';
$out = ob_get_clean();
file_put_contents('tests/test_view_output.html', $out);
echo "Rendered length: " . strlen($out) . "\n";
echo "Output saved to tests/test_view_output.html\n";