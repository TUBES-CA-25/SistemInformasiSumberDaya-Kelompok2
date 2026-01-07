<?php
/**
 * VIEW: DETAIL ALUMNI
 * Menggunakan data yang dipassing dari controller sebagai `$alumni`.
 */

$data = $alumni ?? null;
?>

<section class="alumni-section">
    <div class="container">
        
        <?php if ($data) : ?>
            <div class="detail-nav">
                <a href="index.php?page=alumni" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar Alumni
                </a>
            </div>

            <div class="profile-wrapper">
                
                <div class="profile-image">
                    <?php 
                        // --- LOGIKA GAMBAR PINTAR (SAMA DENGAN ALUMNI.PHP) ---
                        $fotoName = $data['foto'] ?? '';
                        $namaEnc = urlencode($data['nama'] ?? '');

                        // Default: Avatar Inisial (Abu-abu, Bold, Ukuran Besar 512px)
                        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";

                        if (!empty($fotoName)) {
                            if (strpos($fotoName, 'ui-avatars.com') !== false) {
                                // keep default
                            } elseif (strpos($fotoName, 'http') !== false) {
                                $imgUrl = $fotoName;
                            } else {
                                $path1 = ROOT_PROJECT . "/public/images/alumni/" . $fotoName;
                                $path2 = ROOT_PROJECT . "/public/assets/uploads/" . $fotoName;

                                if (file_exists($path2)) {
                                    $imgUrl = ASSETS_URL . "/assets/uploads/" . $fotoName;
                                } elseif (file_exists($path1)) {
                                    $imgUrl = ASSETS_URL . "/images/alumni/" . $fotoName;
                                }
                            }
                        }
                    ?>
                    <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($data['nama'] ?? '') ?>">
                </div>
                
                <div class="profile-content">
                    
                    <div class="alumni-badges">
                        <span class="category-badge">Angkatan <?= htmlspecialchars($data['angkatan'] ?? ''); ?></span>
                        <span class="divisi-badge">
                            Ex-Divisi <?= htmlspecialchars($data['divisi'] ?? 'Asisten Lab'); ?>
                        </span>
                    </div>

                    <?php if(!empty($data['mata_kuliah']) && $data['mata_kuliah'] !== '-'): ?>
                        <div class="mt-2 text-sm text-gray-600">
                            <strong>Mata Kuliah Diajar:</strong> <?= htmlspecialchars($data['mata_kuliah']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="profile-name">
                        <?= htmlspecialchars($data['nama']); ?>
                    </h1>
                    
                    <h4 class="section-title">Kesan & Pesan</h4>
                    <div class="profile-bio alumni-quote">
                        "<?= htmlspecialchars($data['testimoni'] ?? 'Tidak ada kesan pesan.'); ?>"
                    </div>

                    <?php if (!empty($data['skills'])): ?>
                        <h4 class="section-title mt-30">Keahlian & Kompetensi</h4>
                        <div class="skills-container">
                            <?php foreach($data['skills'] as $skill): ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="contact-wrapper">
                        <?php if(!empty($data['email'])): ?>
                            <a href="mailto:<?= htmlspecialchars($data['email']); ?>" class="btn-contact">
                                <i class="ri-mail-send-line"></i> Email
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        <?php else : ?>
            
            <div class="empty-state-wrapper">
                <div class="empty-icon">
                    <i class="ri-user-unfollow-line"></i>
                </div>
                <h2 class="empty-title">Data Tidak Ditemukan</h2>
                <p class="empty-desc">Maaf, data alumni dengan ID tersebut tidak tersedia.</p>
                <a href="index.php?page=alumni" class="btn-primary-pill">
                    Kembali ke Daftar
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>
