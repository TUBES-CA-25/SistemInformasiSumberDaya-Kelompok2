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
    // [TAMBAHAN] Sortir tahun dari yang terbaru (Descending)
    krsort($alumni_by_year);
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
                    
                    <div class="section-label">
                        <span>Angkatan <?= htmlspecialchars($year) ?></span>
                    </div>
                    
                    <div class="staff-grid">
                        <?php foreach ($alumni_list as $row) : ?>
                            <?php 
                                // ==========================================
                                // LOGIKA GAMBAR (DISEDERHANAKAN)
                                // ==========================================
                                $fotoName = $row['foto'] ?? '';
                                $namaEnc = urlencode($row['nama']);

                                // 1. Default Avatar (Abu-abu Elegan)
                                $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";

                                // 2. Cek Database
                                if (!empty($fotoName)) {
                                    // Pastikan ASSETS_URL terdefinisi, jika tidak kosongkan
                                    $baseUrl = defined('ASSETS_URL') ? ASSETS_URL : '';
                                    
                                    // Asumsi: Database menyimpan 'alumni/file.jpg'
                                    // Kita arahkan langsung ke folder uploads
                                    $imgUrl = $baseUrl . '/assets/uploads/' . $fotoName;
                                }

                                // Data Text
                                $divisi = $row['divisi'] ?? 'Asisten Lab';
                            ?>

                            <a href="index.php?page=detail_alumni&id=<?= $row['id'] ?>" class="card-link">
                                <div class="staff-card">
                                    <div class="staff-photo-box">
                                        <img src="<?= $imgUrl ?>" 
                                             alt="<?= htmlspecialchars($row['nama']) ?>" 
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= $namaEnc ?>&background=f1f5f9&color=475569&size=512&bold=true';">
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchAlumni');
    
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll('.card-link');
            let groups = document.querySelectorAll('.alumni-group');

            cards.forEach(function(card) {
                let name = card.querySelector('.staff-name').textContent.toLowerCase();
                let yearText = card.closest('.alumni-group').querySelector('.section-label span').textContent.toLowerCase();
                
                // Cari berdasarkan Nama ATAU Angkatan
                if (name.includes(filter) || yearText.includes(filter)) {
                    card.style.display = ""; // Tampilkan
                    card.classList.remove('hidden-by-search');
                } else {
                    card.style.display = "none"; // Sembunyikan
                    card.classList.add('hidden-by-search');
                }
            });

            // Sembunyikan grup tahun jika semua isinya tersembunyi
            groups.forEach(function(group) {
                let visibleCards = group.querySelectorAll('.card-link:not(.hidden-by-search)');
                if(visibleCards.length === 0) {
                    group.style.display = "none";
                } else {
                    group.style.display = "block";
                }
            });
        });
    }
});
</script>