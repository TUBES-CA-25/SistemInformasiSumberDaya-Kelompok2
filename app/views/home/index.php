<?php
// Retrieve data sent from Controller
$kepala_lab_list = $data['kepala_lab'] ?? [];
$laboran_list = $data['laboran'] ?? [];
?>

<section class="hero-section">
    <div class="container">
        <!-- Boxed Hero Wrapper (Restricting background width and adding border-radius) -->
        <div class="hero-boxed-wrapper">
            <!-- Boxed Faculty Building Background -->
            <div class="hero-bg-frame">
                <div id="bgDay" class="hero-bg-img" style="background-image: url('<?= PUBLIC_URL ?>/images/gedung-fikom-siang.webp');"></div>
                <div id="bgNight" class="hero-bg-img" style="background-image: url('<?= PUBLIC_URL ?>/images/gedung-fikom-malam.webp'); opacity: 0;"></div>
                <div class="hero-bg-overlay"></div>
            </div>
            
            <div class="hero-content reveal fade-left">
                <h1>Pusat Layanan & <span class="text-blue">Sumber Daya Laboratorium</span> IC-LABS</h1>
                <p>Portal terpadu pengelola dan manajemen sumber daya laboratorium Fakultas Ilmu Komputer UMI. Akses informasi jadwal praktikum, profil asisten, modul akademik, fasilitas riset, dan layanan ekosistem komputasi.</p>
                <div class="btn-group">
                    <a href="https://iclabs.fikom.umi.ac.id/s/registrasi/login" class="btn-primary" target="_blank">
                        Gabung Sekarang <i class="ri-arrow-right-line"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/sumberdaya/asisten" class="btn-outline">
                        Lihat Fasilitas <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="visi-misi-section">
    <div class="container">
        <div class="section-header reveal fade-up">
            <span class="badge-pill"><i class="ri-compass-discover-line"></i> TENTANG KAMI</span>
            <h2>Visi & Misi</h2>
        </div>
        
        <div class="visi-misi-grid">
            <!-- Visi Card -->
            <div class="vm-card vm-card-visi reveal fade-up" style="transition-delay: 0.1s;">
                <div class="vm-card-top">
                    <div class="vm-icon-box icon-visi">
                        <i class="ri-focus-2-line"></i>
                    </div>
                    <div class="vm-card-title">
                        <span class="vm-sub-label">Tujuan Utama</span>
                        <h3>Visi Laboratorium</h3>
                    </div>
                </div>

                <div class="vm-card-body">
                    <div class="visi-quote-box">
                        <i class="ri-double-quotes-l quote-icon"></i>
                        <p>"Menjadikan laboratorium sebagai pusat kegiatan belajar dan interaksi yang dapat menghasilkan informasi dan karya baru secara ilmiah dibidang teknologi informasi."</p>
                    </div>
                </div>
            </div>

            <!-- Misi Card -->
            <div class="vm-card vm-card-misi reveal fade-up" style="transition-delay: 0.2s;">
                <div class="vm-card-top">
                    <div class="vm-icon-box icon-misi">
                        <i class="ri-list-settings-line"></i>
                    </div>
                    <div class="vm-card-title">
                        <span class="vm-sub-label">Komitmen Layanan</span>
                        <h3>Misi Utama</h3>
                    </div>
                </div>

                <div class="vm-card-body">
                    <div class="misi-items-wrapper">
                        <div class="misi-item-box">
                            <div class="misi-badge">1</div>
                            <p>Menjadi pusat kegiatan belajar dan pelatihan untuk merancang dan mengembangkan ilmu pengetahuan khususnya perangkat lunak dan perangkat keras komputer.</p>
                        </div>
                        <div class="misi-item-box">
                            <div class="misi-badge">2</div>
                            <p>Ikut aktif dalam menyelesaikan permasalahan software dan hardware di masyarakat dengan melibatkan peran mahasiswa, asisten, dan dosen laboratorium.</p>
                        </div>
                    </div>
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
        <button class="slider-btn prev-btn" onclick="moveSlide(-1)"><i class="ri-arrow-left-s-line"></i></button>
        <button class="slider-btn next-btn" onclick="moveSlide(1)"><i class="ri-arrow-right-s-line"></i></button>

        <div class="slider-track" id="sliderTrack">
            <?php 
            $slides = !empty($data['showcase_list']) ? $data['showcase_list'] : [
                [
                    'badge_text' => 'FASILITAS UNGGULAN',
                    'judul'      => '<span class="text-blue">Pusat</span> Kompetensi',
                    'deskripsi'  => 'Laboratorium FIKOM UMI hadir sebagai pusat pengembangan hard skill unggulan dengan kurikulum adaptif.',
                    'img_url'    => PUBLIC_URL . '/images/Pusat-Kompetensi.jpg',
                    'link_url'   => PUBLIC_URL . '/asisten',
                    'link_label' => 'Lihat Selengkapnya'
                ],
                [
                    'badge_text' => 'PERANGKAT MODERN',
                    'judul'      => '<span class="text-blue">Infrastruktur</span> Spesifik',
                    'deskripsi'  => 'Menyediakan laboratorium spesifik (RPL, Jaringan, Multimedia) dengan perangkat spesifikasi tinggi.',
                    'img_url'    => PUBLIC_URL . '/images/Infrastruktur-Modern.jpg',
                    'link_url'   => PUBLIC_URL . '/laboratorium',
                    'link_label' => 'Lihat Fasilitas'
                ],
                [
                    'badge_text' => 'KOLABORASI RISET',
                    'judul'      => '<span class="text-blue">Riset</span> & Inovasi',
                    'deskripsi'  => 'Mendukung kegiatan penelitian mahasiswa dan dosen dengan fasilitas komputasi yang memadai.',
                    'img_url'    => PUBLIC_URL . '/images/RisetDanInovasi.png',
                    'link_url'   => PUBLIC_URL . '/riset',
                    'link_label' => 'Lihat Riset'
                ]
            ];

            foreach ($slides as $idx => $slide) :
                $badge     = htmlspecialchars($slide['badge_text'] ?? 'UNGGULAN');
                $judul     = $slide['judul'] ?? '';
                $deskripsi = htmlspecialchars($slide['deskripsi'] ?? '');
                $imgUrl    = $slide['img_url'] ?? '';
                $linkUrl   = trim($slide['link_url'] ?? '');
                $linkLabel = htmlspecialchars($slide['link_label'] ?? '');
                $hasLink   = !empty($linkUrl) && $linkUrl !== '#' && $linkUrl !== 'javascript:void(0)';
            ?>
                <div class="slide">
                    <div class="slide-content-wrapper"> 
                        <div class="slide-image">
                            <img src="<?= $imgUrl ?>" alt="<?= strip_tags($judul) ?>" loading="lazy">
                        </div>
                        <div class="slide-text">
                            <span class="slide-badge"><?= $badge ?></span>
                            <h2><?= $judul ?></h2>
                            <p><?= $deskripsi ?></p>
                            <?php if ($hasLink): ?>
                                <a href="<?= $linkUrl ?>" class="btn-primary" style="padding: 10px 22px; font-size: 0.88rem; margin-top: 15px; width: fit-content;">
                                    <?= $linkLabel ?: 'Lihat Selengkapnya' ?> <i class="ri-arrow-right-line"></i>
                                </a>
                            <?php else: ?>
                                <div class="slide-feature-tag">
                                    <i class="ri-sparkling-fill"></i>
                                    <span><?= $linkLabel ?: 'Laboratorium Terpadu FIKOM UMI' ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="slider-dots">
            <?php foreach ($slides as $idx => $s) : ?>
                <button class="dot <?= $idx === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $idx ?>)"></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="custom-shape-divider-bottom">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
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
                <?php foreach ($kepala_lab_list as $row) : 
                    $jabatanRaw = htmlspecialchars($row['jabatan']);
                    $roleMain = $jabatanRaw;
                    $roleSub = '';
                    if (stripos($jabatanRaw, 'Kepala Laboratorium') !== false) {
                        $roleMain = 'Kepala Laboratorium';
                        $roleSub = trim(str_ireplace('Kepala Laboratorium', '', $jabatanRaw));
                    }
                ?>
                    <div class="staff-card-home">
                        <div class="staff-photo-box">
                            <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="<?= Helper::objectPosStyle($row) ?>" loading="lazy">
                        </div>
                        <div class="staff-content">
                            <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                            <span class="staff-role">
                                <i class="ri-shield-star-line"></i>
                                <span class="role-text">
                                    <span class="role-main"><?= $roleMain ?></span>
                                    <?php if (!empty($roleSub)) : ?>
                                        <span class="role-sub"><?= $roleSub ?></span>
                                    <?php endif; ?>
                                </span>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($laboran_list)) : ?>
            <div class="laboran-grid reveal fade-up">
                <?php foreach ($laboran_list as $row) : 
                    $jabatanRaw = htmlspecialchars($row['jabatan']);
                    $roleMain = $jabatanRaw;
                    $roleSub = '';
                    if (stripos($jabatanRaw, 'Laboran Laboratorium') !== false) {
                        $roleMain = 'Laboran Laboratorium';
                        $roleSub = trim(str_ireplace('Laboran Laboratorium', '', $jabatanRaw));
                    } else if (stripos($jabatanRaw, 'Laboran') !== false) {
                        $roleMain = 'Laboran';
                        $roleSub = trim(str_ireplace('Laboran', '', $jabatanRaw));
                    }
                ?>
                    <div class="staff-card-home">
                        <div class="staff-photo-box">
                            <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="<?= Helper::objectPosStyle($row) ?>" loading="lazy">
                        </div>
                        <div class="staff-content">
                            <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                            <span class="staff-role">
                                <i class="ri-code-box-line"></i>
                                <span class="role-text">
                                    <span class="role-main"><?= $roleMain ?></span>
                                    <?php if (!empty($roleSub)) : ?>
                                        <span class="role-sub"><?= $roleSub ?></span>
                                    <?php endif; ?>
                                </span>
                            </span>
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
                <a href="https://iclabs.fikom.umi.ac.id/s/registrasi/login" class="link-arrow">Lihat Detail <i class="ri-arrow-right-line"></i></a>
            </div>

            <div class="info-card reveal fade-up" style="transition-delay: 0.2s;">
                <div class="card-icon"><i class="ri-calendar-check-line"></i></div>
                <h3>Jadwal Praktikum</h3>
                <p>Cek jadwal sesi praktikum semester berjalan secara real-time.</p>
                <a href="<?= PUBLIC_URL ?>/jadwal" class="link-arrow">Lihat Jadwal <i class="ri-arrow-right-line"></i></a>
            </div>

            <div class="info-card reveal fade-up" style="transition-delay: 0.3s;">
                <div class="card-icon"><i class="ri-computer-line"></i></div>
                <h3>Fasilitas & Riset</h3>
                <p>Prosedur peminjaman ruang laboratorium dan alat untuk kegiatan riset.</p>
                <a href="<?= PUBLIC_URL ?>/laboratorium" class="link-arrow">Ajukan Pinjaman <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="partner-section">
    <div class="container" style="margin-bottom: 25px;">
        <div class="section-header reveal fade-up" style="margin-bottom: 0;">
            <span class="badge-pill">KOLABORASI</span>
            <h2>Mitra & Kerjasama</h2>
        </div>
    </div>
    <div class="slider">
        <div class="slide-track">
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Microsoft-Logo.png" alt="Microsoft">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/microsoft_dark.png" alt="Microsoft">
            </div></div>
            <div class="partner-slide"><div class="partner-box"><img src="<?= PUBLIC_URL ?>/images/PoldaSulsel-Logo.png" alt="PoldaSulsel"></div></div>
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Nvidia-Logo.png" alt="Nvidia">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/nvidia_dark.png" alt="Nvidia">
            </div></div>
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Mikrotik-Logo.png" alt="Mikrotik">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/mikrotik_dark.png" alt="Mikrotik">
            </div></div>
            
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Microsoft-Logo.png" alt="Microsoft">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/microsoft_dark.png" alt="Microsoft">
            </div></div>
            <div class="partner-slide"><div class="partner-box"><img src="<?= PUBLIC_URL ?>/images/PoldaSulsel-Logo.png" alt="PoldaSulsel"></div></div>
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Nvidia-Logo.png" alt="Nvidia">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/nvidia_dark.png" alt="Nvidia">
            </div></div>
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Mikrotik-Logo.png" alt="Mikrotik">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/mikrotik_dark.png" alt="Mikrotik">
            </div></div>
           
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Microsoft-Logo.png" alt="Microsoft">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/microsoft_dark.png" alt="Microsoft">
            </div></div>
            <div class="partner-slide"><div class="partner-box"><img src="<?= PUBLIC_URL ?>/images/PoldaSulsel-Logo.png" alt="PoldaSulsel"></div></div>
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Nvidia-Logo.png" alt="Nvidia">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/nvidia_dark.png" alt="Nvidia">
            </div></div>
            <div class="partner-slide"><div class="partner-box">
                <img class="logo-light" src="<?= PUBLIC_URL ?>/images/Mikrotik-Logo.png" alt="Mikrotik">
                <img class="logo-dark"  src="<?= PUBLIC_URL ?>/images/mikrotik_dark.png" alt="Mikrotik">
            </div></div>
        </div>
    </div>
</section>

<?php $homeJsPath = __DIR__ . '/../../../public/js/home.js'; ?>
<script src="<?= PUBLIC_URL ?>/js/home.js?v=<?= file_exists($homeJsPath) ? filemtime($homeJsPath) : time() ?>"></script>