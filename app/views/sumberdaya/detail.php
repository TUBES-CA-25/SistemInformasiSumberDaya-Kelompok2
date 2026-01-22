<?php
/**
 * VIEW: DETAIL ASISTEN & MANAJEMEN (BIO FATIMAH & HIDE SKILLS FIX)
 * File: app/views/sumberdaya/detail-asisten.php
 */

if (!defined('ROOT_PROJECT')) {
    define('ROOT_PROJECT', dirname(__DIR__, 3)); 
}

global $pdo;

// ==========================================
// 1. LOGIKA ID & URL
// ==========================================
$id = 0;

if (isset($data['id'])) {
    $id = $data['id'];
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $lastSegment = end($segments);
    if (is_numeric($lastSegment)) {
        $id = $lastSegment;
    }
}

$type = $_GET['type'] ?? 'asisten'; 
$dataDetail = null;
$backLink = 'index.php?page=asisten'; 

try {
    if ($type === 'manajemen') {
        // --- DATA MANAJEMEN ---
        $stmt = $pdo->prepare("SELECT * FROM manajemen WHERE idManajemen = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $backLink = PUBLIC_URL . '/kepala'; 

        if ($row) {
            $isKepala = stripos(($row['jabatan'] ?? ''), 'Kepala') !== false;
            
            // --- INPUT MANUAL BIO (MANAJEMEN) ---
            $manualBio = "Staff/Pimpinan aktif di Laboratorium Fakultas Ilmu Komputer UMI.";

            // 1. Ir. Abdul Rachman Manga'
            if (stripos($row['nama'], 'Abdul Rachman') !== false) {
                $manualBio = "Ir. Abdul Rachman Manga', S.Kom., M.T., MTA., MCF adalah Kepala Laboratorium Jaringan Dan Pemrograman. Beliau memiliki kepakaran mendalam di bidang infrastruktur jaringan enterprise, keamanan siber, dan rekayasa perangkat lunak.";
            } 
            // 2. Ir. Huzain Azis
            elseif (stripos($row['nama'], 'Huzain Azis') !== false) {
                                $manualBio = "Ir. Huzain Azis, S.Kom., M.Cs. MTA adalah Kepala Laboratorium Komputasi Dasar. Beliau aktif dalam penelitian bidang kecerdasan buatan dan komputasi.";
            }
            // 3. Herdianti
            elseif (stripos($row['nama'], 'Herdianti') !== false) {
                $manualBio = "Herdianti, S.Si., M.Eng., MTA. adalah Kepala Laboratorium Riset yang berfokus pada pengembangan penelitian mahasiswa dan inovasi teknologi.";
            }
            // 4. Fatimah AR. Tuasamu (BARU)
            elseif (stripos($row['nama'], 'Fatimah') !== false) {
                $manualBio = "Fatimah AR. Tuasamu, S.Kom., MTA, MOS adalah Laboran di Fakultas Ilmu Komputer Universitas Muslim Indonesia. Beliau bertanggung jawab atas pengelolaan operasional harian laboratorium, pemeliharaan inventaris aset, serta memfasilitasi kebutuhan administrasi praktikum bagi dosen dan mahasiswa.";
            }
            // ------------------------------------
            
            $badgeStyle = $isKepala 
                ? 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;' 
                : 'background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;';

            $dataDetail = [
                'nama'      => $row['nama'] ?? 'Tanpa Nama',
                'jabatan'   => $row['jabatan'] ?? '-',
                'kategori'  => $isKepala ? 'Pimpinan' : 'Staff Laboratorium',
                'sub_info'  => !empty($row['nidn']) && $row['nidn'] != '-' ? 'NIDN: ' . $row['nidn'] : 'Fakultas Ilmu Komputer',
                'sub_icon'  => 'ri-id-card-line',
                'foto'      => $row['foto'] ?? '',
                'email'     => $row['email'] ?? '-', 
                'bio'       => $manualBio,
                'skills'    => [], // Kosongkan agar section skills hilang
                'badge_style' => $badgeStyle 
            ];
        }

    } else {
        // --- DATA ASISTEN ---
        $stmt = $pdo->prepare("SELECT * FROM asisten WHERE idAsisten = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $backLink = PUBLIC_URL . '/asisten';

        if ($row) {
            $statusAktif = $row['statusAktif'] ?? '';
            $isCA = ($statusAktif === 'CA' || $statusAktif === 'Calon Asisten');
            $isCoord = isset($row['isKoordinator']) && $row['isKoordinator'] == 1;
            
            $jabatan = 'Asisten Praktikum';
            $kategori = 'Asisten Laboratorium';
            $badgeStyle = 'background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;'; 

            if ($isCoord) {
                $jabatan = 'Koordinator Laboratorium';
                $kategori = 'Koordinator';
                $badgeStyle = 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;'; 
            } elseif ($isCA) {
                $jabatan = 'Calon Asisten (CA)';
                $kategori = 'Calon Asisten';
                $badgeStyle = 'background: #fffbeb; color: #d97706; border: 1px solid #fcd34d;'; 
            }

            $skills = [];
            if (!empty($row['skills'])) {
                $decoded = json_decode($row['skills'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $skills = $decoded;
                } else {
                    $skills = array_map('trim', explode(',', $row['skills']));
                }
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
                'email'     => $row['email'] ?? '-',
                'bio'       => !empty($row['bio']) ? $row['bio'] : "Mahasiswa aktif dan asisten laboratorium.",
                'skills'    => $skills,
                'badge_style' => $badgeStyle 
            ];
        }
    }
} catch (PDOException $e) {
    // Silent fail
}

function getDetailPhoto($name, $fotoName) {
    $namaEnc = urlencode($name);
    $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=64748b&size=512&font-size=0.35&bold=true";

    if (!empty($fotoName)) {
        if (strpos($fotoName, 'http') === 0) { return $fotoName; }
        $pathFisik = ROOT_PROJECT . "/public/assets/uploads/" . $fotoName;
        if (file_exists($pathFisik)) {
            return (defined('ASSETS_URL') ? ASSETS_URL : 'assets') . "/assets/uploads/" . $fotoName;
        }
    }
    return $imgUrl;
}
?>

<section class="sumberdaya-section" style="padding-top: 100px !important; padding-bottom: 80px;">
    <div class="container">
        
        <?php if ($dataDetail) : ?>
            <div class="detail-nav">
                <a href="<?= $backLink ?>" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <div class="profile-wrapper">
                <div class="profile-image">
                    <img src="<?= getDetailPhoto($dataDetail['nama'], $dataDetail['foto']) ?>" 
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

                    <?php if (!empty($dataDetail['skills'])) : ?>
                        <h4 class="section-title mt-30">Kompetensi & Keahlian</h4>
                        <div class="skills-container">
                            <?php foreach($dataDetail['skills'] as $skill): ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

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
                <p>ID: <?= htmlspecialchars($id) ?> (Type: <?= htmlspecialchars($type) ?>)</p>
                <a href="<?= $backLink ?>" class="btn-primary-pill">Kembali</a>
            </div>

        <?php endif; ?>

    </div>
</section>