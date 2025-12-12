<?php
// ENTRY POINT HALAMAN JADWAL
define('ROOT_PROJECT', dirname(__DIR__)); 

// Panggil Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// Panggil View Jadwal
require_once ROOT_PROJECT . '/app/views/praktikum/jadwal.php';

// Panggil Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>