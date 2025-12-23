<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/variables.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">

    <?php 
        if (isset($pageCss) && !empty($pageCss)) {
            echo '<link rel="stylesheet" href="' . ASSETS_URL . '/css/' . $pageCss . '">';
        }
    ?>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?php echo PUBLIC_URL; ?>/home" class="brand-logo">
                    <img src="<?php echo ASSETS_URL; ?>/images/logo-iclabs.png" alt="Logo IC-Labs" class="logo-img">
                </a>
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links">
                <li><a href="<?php echo PUBLIC_URL; ?>/home">Home</a></li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="<?php echo PUBLIC_URL; ?>/tata-tertib">Tata Tertib</a>
                        <a href="<?php echo PUBLIC_URL; ?>/jadwal">Jadwal</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="<?php echo PUBLIC_URL; ?>/kepala-lab">Kepala Lab</a>
                        <a href="<?php echo PUBLIC_URL; ?>/asisten">Asisten</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="<?php echo PUBLIC_URL; ?>/laboratorium">Ruang Lab</a>
                        <a href="<?php echo PUBLIC_URL; ?>/riset">Ruang Riset</a>
                    </div>
                </li>
                
                <li><a href="<?php echo PUBLIC_URL; ?>/alumni">Alumni</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>/contact">Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.querySelector('.nav-links');
        if(menuToggle){
            menuToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                menuToggle.classList.toggle('active'); 
            });
        }
    </script>
    
    <main>