<?php
/**
 * VIEW: DETAIL ALUMNI (DIRECT QUERY VERSION)
 * Solusi: Mengambil data langsung dari Database (Bypass Controller)
 * agar data DIJAMIN ada.
 */

// 1. Pastikan koneksi database tersedia (mengambil global $pdo)
global $pdo; 

// Jika $pdo belum ada (misal karena struktur include beda), coba include config
if (!isset($pdo)) {
    // Sesuaikan path ini dengan lokasi file config database Anda jika perlu
    // require_once dirname(__DIR__, 3) . '/config/database.php'; 
}

// 2. Ambil ID dari URL (index.php?page=detail_alumni&id=18)
$id = $_GET['id'] ?? 0;
$data = null;

// 3. QUERY LANGSUNG KE DATABASE (Seperti file detail-asisten.php)
if ($id > 0 && isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM alumni WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Silent error
    }
} elseif (isset($alumni)) {
    // Fallback: Jika controller sudah mengirimkan data
    $data = $alumni;
}

// ==========================================
// 4. PRE-PROCESSING DATA (LOGIKA PEMBERSIH)
// ==========================================
if ($data) {
    // --- GAMBAR ---
    $dbFoto = $data['foto'] ?? '';
    $namaEnc = urlencode($data['nama'] ?? '');
    $defaultAvatar = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";

    // Cek path gambar (Assets URL / Uploads)
    if (!empty($dbFoto)) {
        // Cek apakah link external
        if (strpos($dbFoto, 'http') === 0) {
            $imgUrl = $dbFoto;
        } else {
            // Asumsi folder uploads
            $base = defined('ASSETS_URL') ? ASSETS_URL : 'assets';
            $imgUrl = $base . '/assets/uploads/' . $dbFoto;
        }
    } else {
        $imgUrl = $defaultAvatar;
    }

    // --- KEAHLIAN (BRUTE FORCE CLEANING) ---
    // Kita hancurkan format JSON/String menjadi Array bersih
    $skillsRaw = $data['keahlian'] ?? '';
    $skillsList = [];

    if (!empty($skillsRaw)) {
        // Hapus: kurung siku [ ], kutip dua ", kutip satu ', backslash \
        $cleanRaw = str_replace(['[', ']', '"', "'", '\\'], '', $skillsRaw);
        // Pecah berdasarkan koma
        $skillsList = explode(',', $cleanRaw);
        // Bersihkan spasi
        $skillsList = array_map('trim', $skillsList);
        // Hapus elemen kosong
        $skillsList = array_filter($skillsList);
    }

    // --- MATA KULIAH (BRUTE FORCE CLEANING) ---
    $matkulRaw = $data['mata_kuliah'] ?? '';
    $matkulList = [];
    if (!empty($matkulRaw)) {
        $cleanMk = str_replace(['[', ']', '"', "'", '\\'], '', $matkulRaw);
        $matkulList = explode(',', $cleanMk);
        $matkulList = array_map('trim', $matkulList);
        $matkulList = array_filter($matkulList);
    }
    $matkulString = implode(', ', $matkulList);
}
?>

<section class="alumni-section">
    <div class="container">
        
        <?php if ($data) : ?>
            <div class="detail-nav">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali
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
                        <span class="category-badge">Angkatan <?= htmlspecialchars($data['angkatan'] ?? '-'); ?></span>
                        <span class="divisi-badge">
                            Ex-Divisi <?= htmlspecialchars($data['divisi'] ?? 'Asisten Lab'); ?>
                        </span>
                    </div>

                    <h1 class="profile-name">
                        <?= htmlspecialchars($data['nama']); ?>
                    </h1>

                    <?php if(!empty($matkulString)): ?>
                        <div class="meta-info-box">
                            <span class="meta-label">Mata Kuliah yang Pernah Diajar:</span>
                            <span class="meta-value"><?= htmlspecialchars($matkulString); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <h4 class="section-title mt-30">Kesan & Pesan</h4>
                    <div class="profile-bio alumni-quote">
                        "<?= htmlspecialchars($data['kesan_pesan'] ?? 'Tidak ada kesan pesan.'); ?>"
                    </div>

                    <?php if (count($skillsList) > 0): ?>
                        <h4 class="section-title mt-30">Kompetensi & Keahlian</h4>
                        <div class="skills-container">
                            <?php foreach($skillsList as $skill): ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <?php endif; ?>

                    <div class="contact-wrapper">
                        <?php if(!empty($data['email']) && $data['email'] !== '-'): ?>
                            <a href="mailto:<?= htmlspecialchars($data['email']); ?>" class="btn-contact">
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
                <p class="empty-desc">ID: <?= htmlspecialchars($id) ?> tidak ditemukan di database.</p>
                <a href="index.php?page=alumni" class="btn-primary-pill">Kembali ke Daftar</a>
            </div>

        <?php endif; ?>

    </div>
</section>