<?php
// 1. Ektrak data dari Controller agar kodingan HTML di bawah lebih bersih
// Kita gunakan 'Null Coalescing Operator' (??) untuk mencegah error jika data kosong
$alumni       = $data['alumni'] ?? [];
$imgUrl       = $data['img_url'] ?? '';
$skillsList   = $data['skills_list'] ?? [];
$matkulString = $data['matkul_string'] ?? '';
?>

<section class="alumni-section">
    <div class="container">
        
        <?php if (!empty($alumni)) : ?>
            
            <div class="detail-nav">
                <a href="<?= PUBLIC_URL ?>/alumni" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <div class="profile-wrapper">
                
                <div class="profile-image">
                    <img src="<?= $imgUrl ?>" 
                         alt="<?= htmlspecialchars($alumni['nama'] ?? 'Alumni') ?>">
                </div>
                
                <div class="profile-content">
                    
                    <div class="alumni-badges">
                        <span class="category-badge">Asisten Angkatan <?= htmlspecialchars($alumni['angkatan'] ?? '-'); ?></span>
                    </div>

                    <h1 class="profile-name">
                        <?= htmlspecialchars($alumni['nama']); ?>
                    </h1>

                    <?php if(!empty($matkulString)): ?>
                        <div class="meta-info-box">
                            <span class="meta-label">Mata Kuliah yang Pernah Diajar:</span>
                            <span class="meta-value"><?= htmlspecialchars($matkulString); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <h4 class="section-title mt-30">Kesan & Pesan</h4>
                    <div class="profile-bio alumni-quote">
                        "<?= htmlspecialchars($alumni['kesan_pesan'] ?? 'Tidak ada kesan pesan.'); ?>"
                    </div>

                    <?php if (!empty($skillsList)): ?>
                        <h4 class="section-title mt-30">Kompetensi & Keahlian</h4>
                        <div class="skills-container">
                            <?php foreach($skillsList as $skill): ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="contact-wrapper">
                        <?php if(!empty($alumni['email']) && $alumni['email'] !== '-'): ?>
                            <a href="mailto:<?= htmlspecialchars($alumni['email']); ?>" class="btn-contact">
                                <i class="ri-mail-send-line"></i> Kirim Email
                            </a>
                        <?php else: ?>
                            <button class="btn-contact btn-disabled" disabled>
                                <i class="ri-mail-forbid-line"></i> Email Tidak Tersedia
                            </button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        <?php else : ?>
            
            <div class="empty-state-wrapper">
                <div class="empty-icon"><i class="ri-user-unfollow-line"></i></div>
                <h2 class="empty-title">Data Tidak Ditemukan</h2>
                <a href="<?= PUBLIC_URL ?>/alumni" class="btn-primary-pill">Kembali ke Daftar</a>
            </div>

        <?php endif; ?>

    </div>
</section>