<?php
/**
 * VIEW: ALUMNI ASISTEN LABORATORIUM
 * Fix: Memaksa keseragaman warna avatar (Abu-abu) meskipun di database ada link avatar lama.
 */

global $pdo;

$alumni_by_year = [];

try {
    $stmt = $pdo->query("SELECT * FROM alumni ORDER BY angkatan DESC, nama ASC");
    $all_alumni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($all_alumni as $row) {
        $year = $row['angkatan'];
        $alumni_by_year[$year][] = $row;
    }

} catch (PDOException $e) {
    echo "<div class='alert-error'>Error Database: " . $e->getMessage() . "</div>";
}
?>

<section class="alumni-section fade-up">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Jejak Karir & Kontribusi</span>
            <h1>Alumni Asisten Laboratorium</h1>
            <p>Daftar lulusan yang telah berkontribusi dan kini berkarya di berbagai industri.</p>

            <div class="search-container">
                <input type="text" id="searchAlumni" placeholder="Cari nama, pekerjaan, atau perusahaan..." class="search-input">
                <div class="search-icon-box">
                    <i class="ri-search-line"></i>
                </div>
            </div>
        </header>

        <?php if (!empty($alumni_by_year)) : ?>
            
            <?php foreach ($alumni_by_year as $year => $alumni_list) : ?>
                <div class="alumni-group">
                    
                    <div class="year-divider" style="margin: 50px 0 30px 0; display: flex; align-items: center; gap: 20px;">
                        <h2 style="font-size: 1.8rem; color: #0f172a; font-weight: 800; white-space: nowrap;">
                            Angkatan <?= htmlspecialchars($year) ?>
                        </h2>
                        <div style="height: 2px; background: #e2e8f0; width: 100%; border-radius: 2px;"></div>
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
                                    // [FIX] Jika link di database adalah 'ui-avatars', ABAIKAN agar tetap pakai style abu-abu kita
                                    if (strpos($fotoName, 'ui-avatars.com') !== false) {
                                        // Do nothing (biarkan tetap pakai $imgUrl default di atas)
                                    } 
                                    // Jika URL gambar lain (misal dari Google/LinkedIn), baru dipakai
                                    elseif (strpos($fotoName, 'http') !== false) {
                                        $imgUrl = $fotoName;
                                    } 
                                    // Jika file lokal ada
                                    elseif (file_exists(ROOT_PROJECT . '/public/images/alumni/' . $fotoName)) {
                                        $imgUrl = ASSETS_URL . '/images/alumni/' . $fotoName;
                                    }
                                }

                                // Data Text
                                $pekerjaan = $row['pekerjaan'] ?? 'Alumni';
                                $perusahaan = $row['perusahaan'] ?? '-';
                                $divisi = $row['divisi'] ?? 'Asisten Lab';
                            ?>

                            <a href="index.php?page=detail_alumni&id=<?= $row['id'] ?>" class="card-link">
                                <div class="staff-card">
                                    <div class="staff-photo-box">
                                        <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
                                    </div>
                                    
                                    <div class="staff-content">
                                        <div class="staff-name"><?= htmlspecialchars($row['nama']) ?></div>
                                        <span class="staff-role"><?= htmlspecialchars($pekerjaan) ?></span>
                                        
                                        <div class="staff-footer">
                                            <?php if($perusahaan !== '-'): ?>
                                                <div class="meta-item">
                                                    <i class="ri-building-line"></i> <?= htmlspecialchars($perusahaan) ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="meta-item">
                                                <i class="ri-briefcase-line"></i> <?= htmlspecialchars($divisi) ?>
                                            </div>
                                        </div>
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