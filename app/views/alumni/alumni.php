<?php
/**
 * VIEW: ALUMNI ASISTEN LABORATORIUM
 * Menggunakan data yang dipassing dari controller melalui variabel `$alumni`.
 */

$alumni_by_year = [];

// Data alumni diharapkan dipassing oleh controller sebagai array associative
$all_alumni = $alumni ?? [];

if (!empty($all_alumni) && is_array($all_alumni)) {
    foreach ($all_alumni as $row) {
        $year = $row['angkatan'] ?? 'Unknown';
        $alumni_by_year[$year][] = $row;
    }
}
?>

<section class="alumni-section fade-up">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Jejak Karir & Kontribusi</span>
            <h1>Alumni Asisten Laboratorium</h1>
            <p>Daftar lulusan yang telah berkontribusi dan kini berkarya di berbagai industri.</p>

            <div class="search-container">
                <input type="text" id="searchAlumni" placeholder="Cari nama atau angkatan..." class="search-input">
                <div class="search-icon-box">
                    <i class="ri-search-line"></i>
                </div>
            </div>
        </header>

        <?php if (!empty($alumni_by_year)) : ?>
            
            <?php foreach ($alumni_by_year as $year => $alumni_list) : ?>
                <div class="alumni-group">
                    
                    <div class="section-label" style="margin-top:50px; margin-bottom:18px;">
                        <span>Angkatan <?= htmlspecialchars($year) ?></span>
                    </div>
                    
                    <div class="staff-grid">
                        <?php foreach ($alumni_list as $row) : ?>
                            <?php 
                                // LOGIKA GAMBAR PINTAR (UI Avatars)
                                $fotoName = $row['foto'] ?? '';
                                $namaEnc = urlencode($row['nama']);

                                // 1. Settingan Default Kita (Abu-abu Elegan & Bold)
                                $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=256&bold=true";

                                // 2. Cek File Fisik / URL di Database
                                if (!empty($fotoName)) {
                                    // Abaikan jika link adalah ui-avatars agar tetap pakai style abu-abu kita
                                    if (strpos($fotoName, 'ui-avatars.com') !== false) {
                                        // keep default
                                    } elseif (strpos($fotoName, 'http') !== false) {
                                        // external URL seperti LinkedIn/Google
                                        $imgUrl = $fotoName;
                                    } else {
                                        // Cek dua lokasi potensial: images/alumni dan assets/uploads
                                        $path1 = ROOT_PROJECT . '/public/images/alumni/' . $fotoName;
                                        $path2 = ROOT_PROJECT . '/public/assets/uploads/' . $fotoName;

                                        if (file_exists($path2)) {
                                            $imgUrl = ASSETS_URL . '/assets/uploads/' . $fotoName;
                                        } elseif (file_exists($path1)) {
                                            $imgUrl = ASSETS_URL . '/images/alumni/' . $fotoName;
                                        }
                                    }
                                }

                                // Data Text
                                $divisi = $row['divisi'] ?? 'Asisten Lab';
                            ?>

                            <a href="index.php?page=detail_alumni&id=<?= $row['id'] ?>" class="card-link">
                                <div class="staff-card">
                                    <div class="staff-photo-box">
                                        <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
                                    </div>
                                    
                                    <div class="staff-content">
                                        <div class="staff-name"><?= htmlspecialchars($row['nama']) ?></div>
                                        <span class="staff-role"><?= htmlspecialchars($divisi) ?></span>
                                    </div>
                                </div>
                            </a>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else : ?>
            
            <div class="empty-state-wrapper">
                <div class="empty-icon">
                    <i class="ri-user-search-line"></i>
                </div>
                <h2 class="empty-title">Belum Ada Data</h2>
                <p class="empty-desc">Data alumni belum tersedia di database.</p>
            </div>

        <?php endif; ?>

    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/alumni.js"></script>