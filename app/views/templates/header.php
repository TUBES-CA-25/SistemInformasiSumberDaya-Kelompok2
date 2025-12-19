<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/style.css">

    <?php 
        if (isset($pageCss) && !empty($pageCss)) {
            echo '<link rel="stylesheet" href="css/' . $pageCss . '">';
        }
    ?>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php?page=home" class="brand-logo">
                    <img src="images/logo-iclabs.png" alt="Logo IC-Labs" class="logo-img">
                </a>
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links">
                <li><a href="index.php?page=home">Home</a></li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="index.php?page=tatatertib">Tata Tertib</a>
                        <a href="index.php?page=jadwal">Jadwal</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="index.php?page=asisten">Asisten</a>
                        <a href="index.php?page=kepala">Kepala Lab</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="index.php?page=laboratorium">Ruang Lab</a>
                        <a href="index.php?page=riset">Ruang Riset</a>
                    </div>
                </li>
                
                <li><a href="index.php?page=alumni">Alumni</a></li>
                <li><a href="index.php?page=contact">Contact</a></li>
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