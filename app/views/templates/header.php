<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <link rel="stylesheet" href="/css/style.css">
    
    </head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="http://localhost/SistemManagementSumberDaya/public">
                    ★ LOGO
                </a>
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links">
                <li><a href="/index.php">Home</a></li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="/praktikum.php">Peraturan & Ketentuan</a>
                        <a href="/sanksi.php">Sanksi Pelanggaran</a> <a href="/jadwal.php">Jadwal Praktikum</a> </div>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropbtn">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="/kepala-lab.php">Kepala Laboratorium</a>
                        <a href="/asisten.php">Asisten Laboratorium</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="/laboratorium.php">Ruang Laboratorium</a>
                        <a href="/riset.php">Ruang Riset</a>
                    </div>
                </li>
                <li><a href="/alumni.php">Alumni</a></li>
                <li><a href="/contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.querySelector('.nav-links');

        menuToggle.addEventListener('click', () => {
            // Toggle menu navigasi
            navLinks.classList.toggle('active');
            
            // Toggle animasi ikon burger
            menuToggle.classList.toggle('active'); 
        });
    </script>
    
    <main>