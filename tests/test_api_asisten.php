<?php
chdir(__DIR__ . '/..');
define('ROOT_PROJECT', getcwd());
require 'app/controllers/Controller.php';
require 'app/controllers/AsistenController.php';
ob_start();
(new AsistenController())->apiIndex();
$out = ob_get_clean();
file_put_contents('tests/asisten_api.json', $out);
echo substr($out, 0, 400) . "\n";