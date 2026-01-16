<?php
// LOGIKA DATA MANAJEMEN (KEPALA LAB & LABORAN)
global $pdo;
$kepala_lab_list = [];
$laboran_list = [];

try {
    // Ambil data dari tabel manajemen
    $stmt = $pdo->query("SELECT * FROM manajemen ORDER BY idManajemen ASC");
    $manajemen_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($manajemen_data as $row) {
        // Pisahkan Kepala Lab vs Staff Biasa
        if (stripos($row['jabatan'], 'Kepala') !== false) {
            $kepala_lab_list[] = $row;
        } else {
            $laboran_list[] = $row;
        }
    }
} catch (Exception $e) {
    // Fallback jika database error
    $kepala_lab_list = [];
    $laboran_list = [];
}

// Helper Foto
function getHomeFotoUrl($row) {
    $foto = $row['foto'] ?? '';
    if (empty($foto)) {
        return "https://ui-avatars.com/api/?name=" . urlencode($row['nama']) . "&background=eff6ff&color=2563eb";
    }
    return defined('ASSETS_URL') ? ASSETS_URL . '/assets/uploads/' . $foto : 'assets/uploads/' . $foto;
}
?>

<section class="hero-section">
    <div class="container">
        <div class="hero-content reveal fade-left">
            <h1>Sistem Informasi Sumber Daya Laboratorium</h1>
            <p>Platform terintegrasi untuk manajemen praktikum, peminjaman fasilitas, dan pendataan asisten laboratorium Fakultas Ilmu Komputer secara efisien dan profesional.</p>
            <div class="btn-group">
                <a href="https://iclabs.fikom.umi.ac.id/s/registrasi/login" class="btn-primary" target="_blank">
                    Gabung Sekarang <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="hero-image reveal fade-right">
            <img src="images/logo-iclabs.png" alt="Logo ICLabs" class="hero-logo-img">
        </div>
    </div>
</section>

<section class="visi-misi-section">
    <div class="blob-decoration blob-blue"></div>
    <div class="blob-decoration blob-cyan"></div>

    <div class="container">
        <div class="section-header reveal fade-up">
            <span class="badge-pill">Tentang Kami</span>
            <h2>Komitmen & Tujuan</h2>
        </div>
        
        <div class="visi-misi-grid">
            <div class="vm-card reveal fade-up" style="transition-delay: 0.1s;">
                <div class="vm-icon-box">
                    <i class="ri-focus-2-line"></i>
                </div>
                <div class="vm-content">
                    <h3>Visi Laboratorium</h3>
                    <p>"Menjadikan laboratorium sebagai pusat kegiatan belajar dan interaksi yang dapat menghasilkan informasi dan karya baru secara ilmiah dibidang teknologi informasi."</p>
                </div>
            </div>

            <div class="vm-card reveal fade-up" style="transition-delay: 0.2s;">
                <div class="vm-icon-box">
                    <i class="ri-list-settings-line"></i>
                </div>
                <div class="vm-content">
                    <h3>Misi Utama</h3>
                    <ul class="professional-list">
                        <li><i class="ri-checkbox-circle-line"></i> Menjadi pusat kegiatan belajar dan pelatihan untuk merancang dan mengembangkan ilmu pengetahuan khususnya perangkat lunak dan perangkat keras komputer.</li>
                        <li><i class="ri-checkbox-circle-line"></i> Ikut aktif dalam menyelesaikan permasalahan software dan hardware di masyarakat dengan melibatkan peran serta mahasiswa, asisten, dan dosen laboratorium.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-slider-section">
    
    <div class="custom-shape-divider-top">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>

    <div class="container">
        <div class="section-header reveal fade-up">
            <span class="badge-pill">Keunggulan Kami</span>
            <h2>Ekosistem & Fasilitas</h2>
        </div>
    </div>
    
    <div class="slider-container reveal fade-up">
        <button class="slider-btn prev-btn" onclick="moveSlide(-1)">
            <i class="ri-arrow-left-s-line"></i>
        </button>
        <button class="slider-btn next-btn" onclick="moveSlide(1)">
            <i class="ri-arrow-right-s-line"></i>
        </button>

        <div class="slider-track" id="sliderTrack">
            <div class="slide">
                <div class="slide-content-wrapper"> 
                    <div class="slide-image">
                        <img src="images/Pusat-Kompetensi.jpg" alt="Pusat Kompetensi Digital">
                    </div>
                    <div class="slide-text">
                        <h2>Pusat Kompetensi</h2>
                        <p>Laboratorium FIKOM UMI hadir sebagai pusat pengembangan hard skill unggulan dengan kurikulum adaptif.</p>
                    </div>
                </div>
            </div>
            
            <div class="slide">
                <div class="slide-content-wrapper">
                    <div class="slide-image">
                        <img src="images/Infrastruktur-Modern.jpg" alt="Infrastruktur Laboratorium Modern">
                    </div>
                    <div class="slide-text">
                        <h2>Infrastruktur Modern</h2>
                        <p>Menyediakan laboratorium spesifik (RPL, Jaringan, Multimedia) dengan perangkat spesifikasi tinggi.</p>
                    </div>
                </div>
            </div>
            
            <div class="slide">
                <div class="slide-content-wrapper">
                    <div class="slide-image">
                        <img src="images/RisetDanInovasi.png" alt="Riset dan Inovasi Laboratorium">
                    </div>
                    <div class="slide-text">
                        <h2>Riset & Inovasi</h2>
                        <p>Mendukung kegiatan penelitian mahasiswa dan dosen dengan fasilitas komputasi yang memadai.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-dots">
            <button class="dot active" onclick="goToSlide(0)"></button>
            <button class="dot" onclick="goToSlide(1)"></button>
            <button class="dot" onclick="goToSlide(2)"></button>
        </div>
    </div>

    <div class="custom-shape-divider-bottom">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
</section>

<section class="video-section">
    <div class="container reveal fade-up">
        <div class="section-header">
            <span class="badge-pill">Profil Video</span>
            <h2>Mengapa Memilih Kami?</h2>
        </div>
        
        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/jxczLAHdX3M?rel=0" 
                title="Profil Laboratorium FIKOM UMI" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                allowfullscreen>
            </iframe>
        </div>

    </div>
</section>

<section class="management-section">
    
    <div class="custom-shape-divider-top">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>

    <div class="container">
        
        <div class="section-header reveal fade-up">
            <span class="badge-pill">Struktur Organisasi</span>
            <h2>Pimpinan & Staff Laboratorium</h2>
        </div>

        <?php if (!empty($kepala_lab_list)) : ?>
            <div class="pimpinan-wrapper reveal fade-up">
                <?php foreach ($kepala_lab_list as $row) : ?>
                    <div class="staff-card-home">
                        <div class="staff-photo-box">
                            <img src="<?= getHomeFotoUrl($row) ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
                        </div>
                        <div class="staff-content">
                            <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                            <span class="staff-role"><?= htmlspecialchars($row['jabatan']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($laboran_list)) : ?>
            <div class="laboran-grid reveal fade-up">
                <?php foreach ($laboran_list as $row) : ?>
                    <div class="staff-card-home">
                        <div class="staff-photo-box">
                            <img src="<?= getHomeFotoUrl($row) ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
                        </div>
                        <div class="staff-content">
                            <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                            <span class="staff-role"><?= htmlspecialchars($row['jabatan']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <div class="custom-shape-divider-bottom">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
</section>

<section class="info-section">
    <div class="blob-decoration blob-cyan" style="top: -50px; right: -50px; bottom: auto;"></div>
    <div class="blob-decoration blob-purple"></div>

    <div class="container">
        <div class="section-header reveal fade-up">
            <span class="badge-pill">Layanan</span>
            <h2>Akses Informasi Cepat</h2>
        </div>
        
        <div class="info-grid">
            <div class="info-card reveal fade-up" style="transition-delay: 0.1s;">
                <div class="card-icon"><i class="ri-user-add-line"></i></div>
                <h3>Rekrutmen Asisten</h3>
                <p>Informasi seleksi, syarat, dan ketentuan bagi calon asisten laboratorium.</p>
                <a href="index.php?page=asisten" class="link-arrow">Lihat Detail <i class="ri-arrow-right-line"></i></a>
            </div>

            <div class="info-card reveal fade-up" style="transition-delay: 0.2s;">
                <div class="card-icon"><i class="ri-calendar-check-line"></i></div>
                <h3>Jadwal Praktikum</h3>
                <p>Cek jadwal sesi praktikum semester berjalan secara real-time.</p>
                <a href="index.php?page=jadwal" class="link-arrow">Lihat Jadwal <i class="ri-arrow-right-line"></i></a>
            </div>

            <div class="info-card reveal fade-up" style="transition-delay: 0.3s;">
                <div class="card-icon"><i class="ri-computer-line"></i></div>
                <h3>Fasilitas & Riset</h3>
                <p>Prosedur peminjaman ruang laboratorium dan alat untuk kegiatan riset.</p>
                <a href="index.php?page=laboratorium" class="link-arrow">Ajukan Pinjaman <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="partner-section">
    <div class="slider">
        <div class="slide-track">
            
            <div class="partner-slide"><div class="partner-box"><img src="images/Microsoft-Logo.png" alt="Microsoft"></div></div>
            <div class="partner-slide"><div class="partner-box"><img src="images/PoldaSulsel-Logo.png" alt="PoldaSulsel"></div></div>
            <div class="partner-slide"><div class="partner-box"><img src="images/Nvidia-Logo.png" alt="Nvidia"></div></div>
            <div class="partner-slide"><div class="partner-box"><img src="images/Mikrotik-Logo.png" alt="Mikrotik"></div></div>

            <div class="partner-slide"><div class="partner-box"><img src="images/Microsoft-Logo.png" alt="Microsoft"></div></div>
            <div class="partner-slide"><div class="partner-box"><img src="images/PoldaSulsel-Logo.png" alt="PoldaSulsel"></div></div>
            <div class="partner-slide"><div class="partner-box"><img src="images/Nvidia-Logo.png" alt="Nvidia"></div></div>
            <div class="partner-slide"><div class="partner-box"><img src="images/Mikrotik-Logo.png" alt="Mikrotik"></div></div>

        </div>
    </div>
</section>

<script src="js/home.js"></script>