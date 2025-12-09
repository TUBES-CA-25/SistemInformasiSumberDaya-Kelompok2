<?php
// --- 1. AKTIFKAN ERROR REPORTING (PENTING UNTUK DEBUGGING) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- 2. DEFINISI ROOT PROJECT ---
// Mengambil folder induk dari folder public
define('ROOT_PROJECT', dirname(__DIR__)); 

// --- 3. PANGGIL FILE (INCLUDE) ---
// Gunakan echo untuk mengecek apakah path benar (Opsional, nanti dihapus)
// echo "Mencoba memuat: " . ROOT_PROJECT . '/app/views/templates/header.php <br>';

// Panggil Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// Panggil Halaman Home
require_once ROOT_PROJECT . '/app/views/home/index.php';

// Panggil Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>