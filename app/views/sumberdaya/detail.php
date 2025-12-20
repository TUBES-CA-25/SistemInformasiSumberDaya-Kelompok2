<?php
/**
 * VIEW: DETAIL PROFIL (CLEAN CODE & SMART IMAGE)
 * Menangani detail Asisten & Kepala Lab dengan fallback gambar avatar.
 */

global $pdo;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'asisten'; 

$data = null;
$backLink = 'index.php?page=asisten'; 

if ($id > 0) {
    try {
        if ($type === 'manajemen') {
            // --- LOGIKA MANAJEMEN ---
            $stmt = $pdo->prepare("SELECT * FROM manajemen WHERE idManajemen = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $backLink = 'index.php?page=kepala'; 
                $data = [
                    'nama' => $row['nama'],
                    'email' => '-', 
                    'foto' => $row['foto'],
                    'folder_foto' => 'manajemen', 
                    'jurusan' => 'Fakultas Ilmu Komputer',
                    'kategori' => (stripos($row['jabatan'], 'Kepala') !== false) ? 'Pimpinan Lab' : 'Staff / Laboran',
                    'jabatan' => $row['jabatan'],
                    'spesialisasi' => 'Manajemen Laboratorium',
                    'bio' => $row['nama'] . " menjabat sebagai " . $row['jabatan'] . " di lingkungan Fakultas Ilmu Komputer UMI.",
                    'skills' => ['Laboratory Management', 'Administration', 'Leadership']
                ];
            }
        } else {
            // --- LOGIKA ASISTEN ---
            $stmt = $pdo->prepare("SELECT * FROM asisten WHERE idAsisten = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $backLink = 'index.php?page=asisten';
                $data = [
                    'nama' => $row['nama'],
                    'email' => $row['email'] ?? '-',
                    'foto' => $row['foto'],
                    'folder_foto' => 'asisten', 
                    'jurusan' => $row['jurusan'] ?? 'Teknik Informatika',
                    'kategori' => ($row['isKoordinator'] == 1) ? 'Koordinator Lab' : 'Asisten Praktikum',
                    'jabatan' => ($row['isKoordinator'] == 1) ? 'Koordinator Laboratorium' : 'Asisten Laboratorium',
                    'spesialisasi' => $row['jurusan'] ?? 'General IT Support',
                    'bio' => "Mahasiswa aktif dari jurusan " . ($row['jurusan'] ?? 'Teknik Informatika') . " yang berdedikasi tinggi dalam membantu kegiatan praktikum.",
                    'skills' => ['Teaching', 'Mentoring', 'Laboratory Assistance']
                ];
            }
        }
    } catch (PDOException $e) { }
}
?>

<section class="sumberdaya-section">
    <div class="container">
        
        <?php if ($data) : ?>
            <div class="detail-nav">
                <a href="<?= $backLink ?>" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <div class="profile-wrapper">
                <div class="profile-image">
                    <?php 
                        // --- LOGIKA GAMBAR PINTAR (UI AVATARS) ---
                        $folder = $data['folder_foto']; 
                        $fotoName = $data['foto'];
                        $namaEnc = urlencode($data['nama']);

                        // 1. Default ke UI Avatars (Resolusi Tinggi 512px)
                        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=64748b&size=512&font-size=0.35";

                        if (!empty($fotoName)) {
                            // 2. Cek URL Eksternal
                            if (strpos($fotoName, 'http') !== false) {
                                $imgUrl = $fotoName;
                            } 
                            // 3. Cek File di Folder Utama (manajemen/asisten)
                            elseif (file_exists(ROOT_PROJECT . "/public/images/$folder/" . $fotoName)) {
                                $imgUrl = ASSETS_URL . "/images/$folder/" . $fotoName;
                            } 
                            // 4. Fallback: Cek di folder asisten (jika file nyasar)
                            elseif (file_exists(ROOT_PROJECT . "/public/images/asisten/" . $fotoName)) {
                                $imgUrl = ASSETS_URL . "/images/asisten/" . $fotoName;
                            }
                        }
                    ?>
                    <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($data['nama']) ?>">
                </div>
                
                <div class="profile-content">
                    <span class="category-badge">
                        <?= htmlspecialchars($data['kategori']); ?>
                    </span>
                    
                    <h1 class="profile-name">
                        <?= htmlspecialchars($data['nama']); ?>
                    </h1>
                    <span class="profile-role">
                        <?= htmlspecialchars($data['jabatan']); ?>
                    </span>
                    
                    <div class="specialization-box">
                        <span class="member-specialization">
                            <i class="ri-graduation-cap-line"></i> 
                            <?= htmlspecialchars($data['jurusan']); ?>
                        </span>
                    </div>

                    <h4 class="section-title">Tentang</h4>
                    <p class="profile-bio"><?= htmlspecialchars($data['bio']); ?></p>

                    <h4 class="section-title mt-30">Kompetensi & Keahlian</h4>
                    <div class="skills-container">
                        <?php foreach($data['skills'] as $skill): ?>
                            <span class="skill-tag"><?= $skill; ?></span>
                        <?php endforeach; ?>
                        <span class="skill-tag"><?= htmlspecialchars($data['spesialisasi']); ?></span>
                    </div>

                    <div class="contact-wrapper">
                        <?php if($data['email'] && $data['email'] !== '-'): ?>
                            <a href="mailto:<?= htmlspecialchars($data['email']); ?>" class="btn-contact">
                                <i class="ri-mail-send-line"></i> Hubungi via Email
                            </a>
                        <?php else: ?>
                            <button class="btn-contact btn-disabled">
                                <i class="ri-mail-forbid-line"></i> Email Tidak Tersedia
                            </button>
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
                <p class="empty-desc">Maaf, data tidak ditemukan di database.</p>
                <a href="<?= $backLink ?>" class="btn-primary-pill">
                    Kembali
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>