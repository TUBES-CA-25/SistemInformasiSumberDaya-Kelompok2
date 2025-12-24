<?php
/**
 * VIEW: DETAIL ALUMNI (FULL FIX V2 - SMART IMAGE)
 * Mengambil data real dari database dengan logika gambar UI Avatars yang seragam.
 */

global $pdo;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = null;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM alumni WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Parsing Keahlian
            $skills = !empty($row['keahlian']) ? array_map('trim', explode(',', $row['keahlian'])) : [];

            $data = [
                'nama' => $row['nama'],
                'angkatan' => $row['angkatan'],
                'divisi' => $row['divisi'] ?? 'Asisten Lab',
                'pekerjaan' => $row['pekerjaan'] ?? 'Belum terisi',
                'perusahaan' => $row['perusahaan'] ?? '-',
                'foto' => $row['foto'],
                'email' => $row['email'],
                'linkedin' => $row['linkedin'],
                'portfolio' => $row['portfolio'],
                'tahun_lulus' => $row['tahun_lulus'],
                'testimoni' => $row['kesan_pesan'] ?? 'Tidak ada kesan pesan.',
                'skills' => $skills
            ];
        }
    } catch (PDOException $e) {
        // Silent error handling
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
                    <?php 
                        // --- LOGIKA GAMBAR PINTAR (SAMA DENGAN ALUMNI.PHP) ---
                        $fotoName = $data['foto'];
                        $namaEnc = urlencode($data['nama']);

                        // 1. Default: Avatar Inisial (Abu-abu, Bold, Ukuran Besar 512px)
                        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=512&bold=true";

                        if (!empty($fotoName)) {
                            // 2. Cek apakah link database adalah ui-avatars lama (misal warna biru)
                            // Jika YA, kita abaikan agar tetap pakai style abu-abu kita ($imgUrl default di atas)
                            if (strpos($fotoName, 'ui-avatars.com') !== false) {
                                // Do nothing (biarkan default)
                            }
                            // 3. Jika link eksternal lain (LinkedIn/Google), gunakan
                            elseif (strpos($fotoName, 'http') !== false) {
                                $imgUrl = $fotoName;
                            } 
                            // 4. Jika file lokal ada, gunakan
                            elseif (file_exists(ROOT_PROJECT . "/public/images/alumni/" . $fotoName)) {
                                $imgUrl = ASSETS_URL . "/images/alumni/" . $fotoName;
                            }
                        }
                    ?>
                    <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($data['nama']) ?>">
                </div>
                
                <div class="profile-content">
                    
                    <div class="alumni-badges">
                        <span class="category-badge">Angkatan <?= htmlspecialchars($data['angkatan']); ?></span>
                        <span class="divisi-badge">
                            Ex-Divisi <?= htmlspecialchars($data['divisi']); ?>
                        </span>
                    </div>
                    
                    <h1 class="profile-name">
                        <?= htmlspecialchars($data['nama']); ?>
                    </h1>
                    
                    <div class="specialization-box">
                        <span class="member-specialization">
                            <i class="ri-briefcase-line"></i> 
                            <?= htmlspecialchars($data['pekerjaan']); ?> 
                            <?php if($data['perusahaan'] !== '-'): ?>
                                <span class="company-name">at <?= htmlspecialchars($data['perusahaan']); ?></span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <h4 class="section-title">Kesan & Pesan</h4>
                    <div class="profile-bio alumni-quote">
                        "<?= htmlspecialchars($data['testimoni']); ?>"
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

                        <?php if(!empty($data['linkedin'])): ?>
                            <a href="<?= htmlspecialchars($data['linkedin']); ?>" target="_blank" class="btn-contact btn-linkedin">
                                <i class="ri-linkedin-box-fill"></i> LinkedIn
                            </a>
                        <?php endif; ?>

                        <?php if(!empty($data['portfolio'])): ?>
                            <a href="<?= htmlspecialchars($data['portfolio']); ?>" target="_blank" class="btn-contact btn-portfolio">
                                <i class="ri-dribbble-line"></i> Portfolio
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
