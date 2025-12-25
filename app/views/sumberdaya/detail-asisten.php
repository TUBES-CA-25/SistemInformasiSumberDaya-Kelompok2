<?php
/**
 * VIEW: DETAIL ASISTEN & MANAJEMEN (UNIVERSAL FIX)
 * File: app/views/sumberdaya/detail-asisten.php
 * Update: Menambahkan '??' pada $row['email'] untuk mencegah error undefined key.
 */

global $pdo;

// 1. Ambil Parameter dari URL
$id = $_GET['id'] ?? 0;
$type = $_GET['type'] ?? 'asisten'; // Default ke 'asisten'

$dataDetail = null;
$backLink = 'index.php?page=asisten'; // Default tombol kembali

// 2. Logika Database (Switch Tabel berdasarkan Type)
try {
    if ($type === 'manajemen') {
        // --- KASUS: KEPALA LAB / STAFF ---
        $stmt = $pdo->prepare("SELECT * FROM manajemen WHERE idManajemen = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $backLink = 'index.php?page=kepala'; // Kembali ke halaman Kepala

        if ($row) {
            $dataDetail = [
                'nama'      => $row['nama'] ?? 'Tanpa Nama',
                'jabatan'   => $row['jabatan'] ?? '-',
                'kategori'  => (stripos(($row['jabatan'] ?? ''), 'Kepala') !== false) ? 'Pimpinan' : 'Staff Laboratorium',
                // FIX: Gunakan ?? untuk NIDN
                'sub_info'  => !empty($row['nidn']) ? 'NIDN: ' . $row['nidn'] : 'Fakultas Ilmu Komputer',
                'sub_icon'  => 'ri-id-card-line',
                'foto'      => $row['foto'] ?? '',
                // FIX UTAMA: Tambahkan ?? '-' agar tidak error jika kolom email tidak ada
                'email'     => $row['email'] ?? '-', 
                'bio'       => !empty($row['bio']) ? $row['bio'] : "Staff/Pimpinan aktif di Laboratorium Fakultas Ilmu Komputer UMI.",
                'skills'    => ['Management', 'Administration', 'Laboratory'],
                'badge_style' => (stripos(($row['jabatan'] ?? ''), 'Kepala') !== false) ? 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;' : 'background: #f1f5f9; color: #475569;'
            ];
        }

    } else {
        // --- KASUS: ASISTEN (DEFAULT) ---
        $stmt = $pdo->prepare("SELECT * FROM asisten WHERE idAsisten = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $backLink = 'index.php?page=asisten'; // Kembali ke halaman Asisten

        if ($row) {
            $statusAktif = $row['statusAktif'] ?? '';
            $isCA = ($statusAktif === 'CA' || $statusAktif === 'Calon Asisten');
            $isCoord = isset($row['isKoordinator']) && $row['isKoordinator'] == 1;
            
            $jabatan = 'Asisten Praktikum';
            $kategori = 'Asisten Laboratorium';
            $badgeStyle = '';

            if ($isCoord) {
                $jabatan = 'Koordinator Laboratorium';
                $kategori = 'Koordinator';
            } elseif ($isCA) {
                $jabatan = 'Calon Asisten (CA)';
                $kategori = 'Calon Asisten';
                $badgeStyle = 'background: #fffbeb; color: #d97706; border: 1px solid #fcd34d;';
            }

            // Skill Parsing
            $skills = [];
            if (!empty($row['skills'])) {
                $decoded = json_decode($row['skills'], true);
                $skills = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : array_map('trim', explode(',', $row['skills']));
            } else {
                $skills = ['Teaching', 'Mentoring'];
            }

            $dataDetail = [
                'nama'      => $row['nama'] ?? 'Tanpa Nama',
                'jabatan'   => $jabatan,
                'kategori'  => $kategori,
                'sub_info'  => $row['jurusan'] ?? 'Teknik Informatika',
                'sub_icon'  => 'ri-graduation-cap-line',
                'foto'      => $row['foto'] ?? '',
                // FIX UTAMA: Tambahkan ?? '-' di sini juga
                'email'     => $row['email'] ?? '-',
                'bio'       => !empty($row['bio']) ? $row['bio'] : "Mahasiswa aktif jurusan " . ($row['jurusan']??'Teknik Informatika') . " yang berdedikasi membantu praktikum.",
                'skills'    => $skills,
                'badge_style' => $badgeStyle
            ];
        }
    }
} catch (PDOException $e) {
    // Silent fail
}

// 3. Helper Foto
function getDetailPhoto($name, $fotoName, $type) {
    $namaEnc = urlencode($name);
    $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=64748b&size=512&font-size=0.35&bold=true";

    if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
        if (strpos($fotoName, 'http') === 0) {
            $imgUrl = $fotoName;
        } else {
            // Cek folder berdasarkan tipe
            $folder = ($type === 'manajemen') ? 'manajemen' : 'asisten';
            $altFolder = ($type === 'manajemen') ? 'asisten' : 'manajemen';
            
            if (file_exists(ROOT_PROJECT . "/public/images/{$folder}/" . $fotoName)) {
                $imgUrl = ASSETS_URL . "/images/{$folder}/" . $fotoName;
            } elseif (file_exists(ROOT_PROJECT . "/public/assets/uploads/" . $fotoName)) {
                $imgUrl = ASSETS_URL . "/assets/uploads/" . $fotoName;
            } elseif (file_exists(ROOT_PROJECT . "/public/images/{$altFolder}/" . $fotoName)) {
                $imgUrl = ASSETS_URL . "/images/{$altFolder}/" . $fotoName;
            }
        }
    }
    return $imgUrl;
}
?>

<section class="sumberdaya-section">
    <div class="container">
        
        <?php if ($dataDetail) : ?>
            <div class="detail-nav">
                <a href="<?= $backLink ?>" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <div class="profile-wrapper">
                <div class="profile-image">
                    <img src="<?= getDetailPhoto($dataDetail['nama'], $dataDetail['foto'], $type) ?>" 
                         alt="<?= htmlspecialchars($dataDetail['nama']) ?>">
                </div>
                
                <div class="profile-content">
                    <span class="category-badge" style="<?= $dataDetail['badge_style'] ?>">
                        <?= htmlspecialchars($dataDetail['kategori']); ?>
                    </span>
                    
                    <h1 class="profile-name">
                        <?= htmlspecialchars($dataDetail['nama']); ?>
                    </h1>
                    
                    <span class="profile-role">
                        <?= htmlspecialchars($dataDetail['jabatan']); ?>
                    </span>
                    
                    <div class="specialization-box">
                        <div class="member-specialization">
                            <i class="<?= $dataDetail['sub_icon'] ?>"></i> 
                            <?= htmlspecialchars($dataDetail['sub_info']); ?>
                        </div>
                        
                        <?php if($dataDetail['email'] !== '-'): ?>
                            <div class="member-specialization" style="margin-top: 8px; color: #64748b; font-weight: 500;">
                                <i class="ri-mail-line"></i> 
                                <?= htmlspecialchars($dataDetail['email']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h4 class="section-title" style="margin-top: 30px;">Tentang</h4>
                    <p class="profile-bio"><?= nl2br(htmlspecialchars($dataDetail['bio'])); ?></p>

                    <h4 class="section-title mt-30">Kompetensi & Keahlian</h4>
                    <div class="skills-container">
                        <?php if(!empty($dataDetail['skills'])): ?>
                            <?php foreach($dataDetail['skills'] as $skill): ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="skill-tag">-</span>
                        <?php endif; ?>
                    </div>

                    <div class="contact-wrapper">
                        <?php if($dataDetail['email'] !== '-'): ?>
                            <a href="mailto:<?= htmlspecialchars($dataDetail['email']); ?>" class="btn-contact">
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
                <a href="<?= $backLink ?>" class="btn-primary-pill">Kembali</a>
            </div>

        <?php endif; ?>

    </div>
</section>