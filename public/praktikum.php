<?php
// FILE TESTING UNTUK HALAMAN PRAKTIKUM
define('ROOT_PROJECT', dirname(__DIR__)); 

// Panggil Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// Panggil Halaman Praktikum (Peraturan)
require_once ROOT_PROJECT . '/app/views/praktikum/index.php';

// Panggil Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>