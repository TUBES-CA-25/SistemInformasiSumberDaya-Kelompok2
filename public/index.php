<?php
// 1. Tampilkan Error (Agar kalau ada salah ketik ketahuan)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Definisi Folder Utama (Root)
// Mengambil folder induk dari folder public
define('ROOT_PROJECT', dirname(__DIR__)); 

// 3. Panggil Halaman Home (Sementara langsung panggil View dulu)
// Karena Backend Controller belum siap, kita "bypass" langsung ke tampilan.

// Panggil Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// Panggil Isi Halaman Home
require_once ROOT_PROJECT . '/app/views/home/index.php';

// Panggil Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>