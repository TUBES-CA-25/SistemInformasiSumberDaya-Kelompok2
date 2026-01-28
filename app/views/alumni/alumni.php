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
                    
                    <div class="section-label">
                        <span>Angkatan <?= htmlspecialchars($year) ?></span>
                    </div>
                    
                    <div class="staff-grid">
    <?php foreach ($alumni_list as $row) : ?>
        <?php 
            // ==========================================
            // 1. FIX ID: Cek apakah pakai 'idAlumni' atau 'id'
            // ==========================================
            $idAlumni = $row['idAlumni'] ?? $row['id'] ?? 0;

            // ==========================================
            // 2. LOGIKA GAMBAR
            // ==========================================
            $fotoName = $row['foto'] ?? '';
            $namaEnc = urlencode($row['nama']);

            // Default Avatar
            $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";

            // Cek Database / File
            if (!empty($fotoName)) {
                // Gunakan Helper atau path manual yang aman
                $path = ROOT_PROJECT . '/public/assets/uploads/' . $fotoName;
                if (file_exists($path)) {
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
                    $imgUrl = $baseUrl . '/assets/uploads/' . $fotoName;
                }
            }

            $divisi = $row['divisi'] ?? 'Asisten Lab';
        ?>

        <a href="<?= PUBLIC_URL ?>/index.php?page=detail_alumni&id=<?= $idAlumni ?>" class="card-link">
            <div class="staff-card">
                <div class="staff-photo-box">
                    <img src="<?= $imgUrl ?>" 
                         alt="<?= htmlspecialchars($row['nama']) ?>" 
                         loading="lazy">
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

<script src="<?= PUBLIC_URL ?>/js/alumni.js"></script>