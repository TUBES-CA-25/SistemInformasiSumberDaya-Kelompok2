<?php
chdir(__DIR__ . '/..');
define('ROOT_PROJECT', getcwd());
// Minimal bootstrapping
require 'app/controllers/Controller.php';
require 'app/controllers/AsistenController.php';

ob_start();
$ctrl = new AsistenController();
$ctrl->index();
$out = ob_get_clean();
file_put_contents('tests/asisten_index_output.html', $out);
echo 'Saved length: ' . strlen($out) . "\n";