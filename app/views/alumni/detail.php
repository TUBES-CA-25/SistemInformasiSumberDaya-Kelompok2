<?php
/**
 * VIEW: DETAIL ALUMNI
 * Update: Support JSON Data (Keahlian & Matkul) + Fallback Image
 */

$data = $alumni ?? null;

// --- PRE-PROCESSING DATA ---
if ($data) {
    // 1. Decode Keahlian/Skills (JSON -> Array)
    $skillsRaw = $data['keahlian'] ?? '[]';
    $skillsList = json_decode($skillsRaw, true);
    // Fallback jika data lama bukan JSON
    if (!is_array($skillsList) && !empty($skillsRaw)) {
        $skillsList = [$skillsRaw];
    } elseif (!is_array($skillsList)) {
        $skillsList = [];
    }

    // 2. Decode Mata Kuliah (JSON -> String untuk tampilan baris)
    $matkulRaw = $data['mata_kuliah'] ?? '[]';
    $matkulList = json_decode($matkulRaw, true);
    // Fallback
    if (!is_array($matkulList) && !empty($matkulRaw)) {
        $matkulList = [$matkulRaw]; 
    } elseif (!is_array($matkulList)) {
        $matkulList = [];
    }
    // Ubah array menjadi string dipisah koma (agar sesuai layout lama)
    $matkulString = !empty($matkulList) ? implode(', ', $matkulList) : '-';

    // 3. Setup Gambar
    $dbFoto = $data['foto'] ?? '';
    $namaEnc = urlencode($data['nama'] ?? '');
    $defaultAvatar = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";

    if (!empty($dbFoto)) {
        // Asumsi ASSETS_URL sudah didefinisikan di config utama
        $imgUrl = ASSETS_URL . '/assets/uploads/' . $dbFoto;
    } else {
        $imgUrl = $defaultAvatar;
    }
}
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
                    <img src="<?= $imgUrl ?>" 
                         alt="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                         onerror="this.onerror=null; this.src='<?= $defaultAvatar ?>';">
                </div>
                
                <div class="profile-content">
                    
                    <div class="alumni-badges">
                        <span class="category-badge">Angkatan <?= htmlspecialchars($data['angkatan'] ?? ''); ?></span>
                        <span class="divisi-badge">
                            Ex-Divisi <?= htmlspecialchars($data['divisi'] ?? 'Asisten Lab'); ?>
                        </span>
                    </div>

                    <?php if(!empty($matkulString) && $matkulString !== '-'): ?>
                        <div class="mt-2 text-sm text-gray-600">
                            <strong>Mata Kuliah Diajar:</strong> <?= htmlspecialchars($matkulString); ?>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="profile-name">
                        <?= htmlspecialchars($data['nama']); ?>
                    </h1>
                    
                    <h4 class="section-title">Kesan & Pesan</h4>
                    <div class="profile-bio alumni-quote">
                        "<?= htmlspecialchars($data['kesan_pesan'] ?? 'Tidak ada kesan pesan.'); ?>"
                    </div>

                    <?php if (!empty($skillsList)): ?>
                        <h4 class="section-title mt-30">Keahlian & Kompetensi</h4>
                        <div class="skills-container">
                            <?php foreach($skillsList as $skill): ?>
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