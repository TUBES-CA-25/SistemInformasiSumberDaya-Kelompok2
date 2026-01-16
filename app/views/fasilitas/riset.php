<?php
/**
 * VIEW: RUANG RISET & INOVASI
 * Filter: jenis = 'Riset'
 */

$riset_list = [];

// 1. Ambil Data (Controller atau Fallback DB)
if (!empty($data['riset'])) {
    $riset_list = $data['riset'];
} else {
    global $pdo;
    try {
        if ($pdo instanceof PDO) {
            // FILTER: Hanya ambil yang jenisnya 'Riset'
            $stmt = $pdo->query("SELECT * FROM laboratorium WHERE jenis = 'Riset' ORDER BY idLaboratorium ASC");
            $riset_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Throwable $e) { $riset_list = []; }
}

// 2. Helper Warna & Ikon berdasarkan Nama Riset
function getRisetStyle($nama) {
    $n = strtolower($nama);
    if (strpos($n, 'ai') !== false || strpos($n, 'intelligence') !== false) {
        return ['bg' => '#eff6ff', 'icon' => 'ri-brain-line', 'color' => '#2563eb', 'badge_bg' => '#dbeafe', 'badge_text' => '#1e40af']; // Biru
    } elseif (strpos($n, 'iot') !== false || strpos($n, 'network') !== false || strpos($n, 'jaringan') !== false) {
        return ['bg' => '#f0fdf4', 'icon' => 'ri-wifi-line', 'color' => '#16a34a', 'badge_bg' => '#dcfce7', 'badge_text' => '#16a34a']; // Hijau
    } elseif (strpos($n, 'mobile') !== false || strpos($n, 'app') !== false) {
        return ['bg' => '#fff7ed', 'icon' => 'ri-smartphone-line', 'color' => '#ea580c', 'badge_bg' => '#ffedd5', 'badge_text' => '#9a3412']; // Orange
    } else {
        return ['bg' => '#f8fafc', 'icon' => 'ri-flask-line', 'color' => '#64748b', 'badge_bg' => '#f1f5f9', 'badge_text' => '#475569']; // Abu (Default)
    }
}
?>

<section class="fasilitas-section">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Pusat Riset & Pengembangan</span>
            <h1>Ruang Riset & Inovasi</h1>
            <p>Ruang kolaborasi untuk penelitian dosen dan pengembangan inovasi mahasiswa tingkat akhir.</p>
        </header>

        <div class="facility-grid">
            
            <?php if (!empty($riset_list)) : ?>
                <?php foreach ($riset_list as $row) : ?>
                    <?php 
                        $style = getRisetStyle($row['nama']); 
                        $descRaw = $row['deskripsi'] ?? '';
                        $deskripsi = strlen($descRaw) > 120 ? substr($descRaw, 0, 120) . '...' : $descRaw;
                    ?>

                    <a href="<?= PUBLIC_URL ?>/detail_fasilitas/<?= $row['idLaboratorium'] ?>" class="facility-row" data-id="<?= $row['idLaboratorium'] ?>" data-link>
                        <div class="facility-img-side" style="background: <?= $style['bg'] ?>;">
                            <?php if(!empty($row['gambar']) && file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $row['gambar'])): ?>
                                <img src="<?= ASSETS_URL ?>/assets/uploads/<?= $row['gambar'] ?>" 
                                     style="width:100%; height:100%; object-fit:cover; opacity:0.9;" loading="lazy">
                            <?php else: ?>
                                <div class="img-overlay-placeholder" style="background: transparent;">
                                    <i class="<?= $style['icon'] ?>" style="color: <?= $style['color'] ?>;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="facility-info-side">
                            <div class="facility-meta">
                                <span class="badge-info" style="background:<?= $style['badge_bg'] ?>; color:<?= $style['badge_text'] ?>;">
                                    <i class="ri-group-line"></i> Kapasitas <?= $row['kapasitas'] ?> Peneliti
                                </span>
                            </div>
                            
                            <h3><?= htmlspecialchars($row['nama']) ?></h3>
                            <p><?= htmlspecialchars($deskripsi) ?></p>
                            
                            <div class="mini-specs">
                                <?php if (!empty($row['processor'])): ?>
                                    <div class="mini-spec-item"><i class="ri-microchip-line"></i> <?= htmlspecialchars($row['processor']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($row['fasilitas_pendukung'])): ?>
                                    <div class="mini-spec-item"><i class="ri-stack-line"></i> Lengkap</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>

                <?php endforeach; ?>
            <?php else : ?>
                <div style="text-align:center; padding:50px; color:#94a3b8; width:100%;">
                    <i class="ri-flask-line" style="font-size:3rem;"></i>
                    <p>Data riset belum tersedia.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/fasilitas.js"></script>