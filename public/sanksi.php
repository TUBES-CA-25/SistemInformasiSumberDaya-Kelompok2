<?php
// ENTRY POINT HALAMAN SANKSI
define('ROOT_PROJECT', dirname(__DIR__)); 

// Panggil Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// Panggil View Sanksi
require_once ROOT_PROJECT . '/app/views/praktikum/sanksi.php';

// Panggil Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>