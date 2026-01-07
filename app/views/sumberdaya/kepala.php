<?php
/**
 * VIEW: KEPALA LAB & MANAJEMEN
 * Fix: Link mengarah ke detail_asisten dengan parameter type=manajemen
 */

$pimpinan_list = [];
$laboran_list  = [];
$all_data      = [];

// 1. Ambil Data (Controller / Fallback)
if (!empty($data['manajemen'])) {
    $all_data = $data['manajemen'];
} else {
    global $pdo;
    try {
        if ($pdo instanceof PDO) {
            $stmt = $pdo->query("SELECT * FROM manajemen ORDER BY idManajemen ASC");
            $all_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Throwable $e) {
        $all_data = [];
    }
}

// 2. Kelompokkan Data
foreach ($all_data as $row) {
    $jabatan = $row['jabatan'] ?? '';
    // Deteksi Pimpinan (Kepala Lab)
    if (stripos($jabatan, 'Kepala') !== false) {
        $pimpinan_list[] = $row;
    } else {
        $laboran_list[] = $row;
    }
}

// 3. Helper Foto (Diperbarui agar sinkron dengan detail)
function getFotoUrl($row) {
    $fotoName = $row['foto'] ?? '';
    $namaEnc  = urlencode($row['nama']);
    
    // Default Avatar
    $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=512&bold=true";

    if (empty($fotoName)) return $imgUrl;
    if (strpos($fotoName, 'http') === 0) return $fotoName;

    // Cek Folder Uploads (Prioritas)
    if (file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $fotoName)) {
        return ASSETS_URL . '/assets/uploads/' . $fotoName;
    }
    // Cek Folder Manajemen (Legacy)
    if (file_exists(ROOT_PROJECT . '/public/images/manajemen/' . $fotoName)) {
        return ASSETS_URL . '/images/manajemen/' . $fotoName;
    }
    // Cek Folder Asisten (Fallback)
    if (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
        return ASSETS_URL . '/images/asisten/' . $fotoName;
    }

    return $imgUrl;
}
?>

<section class="sumberdaya-section fade-up">
    <div class="container">

        <header class="page-header">
            <span class="header-badge">Manajemen & Struktural <?= date('Y') ?></span>
            <h1>Struktur Pimpinan</h1>
            <p>Pimpinan Laboratorium dan Staff Administrasi Fakultas Ilmu Komputer</p>

            <div class="search-container">
                <input type="text" id="searchStaff" placeholder="Cari nama atau jabatan..." class="search-input">
                <i class="ri-search-line" style="position:absolute; right:20px; top:50%; transform:translateY(-50%); color:#94a3b8"></i>
            </div>
        </header>

        <?php if (!empty($pimpinan_list)) : ?>
            <div class="section-label">Kepala Laboratorium</div>
            
            <div class="pimpinan-wrapper">
                
                <?php foreach ($pimpinan_list as $row) : ?>
                    <?php $imgUrl = getFotoUrl($row); ?>

                    <a href="index.php?page=detail-asisten&id=<?= $row['idManajemen'] ?>&type=manajemen" class="card-link">
                        
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
                            </div>

                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                <?php if (!empty($row['nidn'])) : ?>
                                    <span class="staff-nidn" style="display: block; font-size: 0.75rem; color: #64748b; margin-top: -2px; margin-bottom: 4px; font-weight: 500;">
                                        NIDN: <?= htmlspecialchars($row['nidn']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="staff-role"><?= htmlspecialchars($row['jabatan']) ?></span>

                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-building-4-line"></i> Fikom UMI
                                    </div>
                                    <?php if (!empty($row['email'])) : ?>
                                        <div class="meta-item">
                                            <i class="ri-mail-line"></i>
                                            <?= htmlspecialchars($row['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                
            </div>
        <?php endif; ?>

        <?php if (!empty($laboran_list)) : ?>
            <div class="section-label">Pranata Laboratorium & Staff</div>
            <div class="staff-grid">
                
                <?php foreach ($laboran_list as $row) : ?>
                    <?php $imgUrl = getFotoUrl($row); ?>

                    <a href="index.php?page=detail-asisten&id=<?= $row['idManajemen'] ?>&type=manajemen" class="card-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
                            </div>

                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                <?php if (!empty($row['nidn'])) : ?>
                                    <span class="staff-nidn" style="display: block; font-size: 0.75rem; color: #64748b; margin-top: -2px; margin-bottom: 4px; font-weight: 500;">
                                        NIDN: <?= htmlspecialchars($row['nidn']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="staff-role"><?= htmlspecialchars($row['jabatan']) ?></span>

                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-building-4-line"></i> Fikom UMI
                                    </div>
                                    <?php if (!empty($row['email'])) : ?>
                                        <div class="meta-item">
                                            <i class="ri-mail-line"></i>
                                            <?= htmlspecialchars($row['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($pimpinan_list) && empty($laboran_list)) : ?>
            <div class="empty-state-wrapper">
                <div class="empty-icon"><i class="ri-folder-unknow-line"></i></div>
                <p>Data manajemen belum tersedia saat ini.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/kepala.js"></script>