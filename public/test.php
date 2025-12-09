<?php
// Mendefinisikan root folder proyek utama
define('ROOT_PROJECT', dirname(dirname(__FILE__)));

// Panggil Header (Tambahkan /app di depannya)
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// Panggil Halaman Home (Tambahkan /app di depannya)
require_once ROOT_PROJECT . '/app/views/home/index.php';

// Panggil Footer (Tambahkan /app di depannya)
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>