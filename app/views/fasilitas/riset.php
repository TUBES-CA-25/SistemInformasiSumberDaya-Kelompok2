<?php
/**
 * VIEW: RUANG RISET & INOVASI
 * Versi: MVC Murni (Logic style & DB dipindah ke Controller)
 */

// 1. Ambil data dari Controller
$riset_list = $data['riset'] ?? [];
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
                        // Ambil data yang sudah disiapkan Controller
                        // Gunakan 'Null Coalescing' (??) untuk mencegah error undefined key
                        $style = $row['style_final'] ?? [
                            'bg' => '#f8fafc', 'icon' => 'ri-flask-line', 'color' => '#64748b', 'badge_bg' => '#f1f5f9', 'badge_text' => '#475569'
                        ];
                        
                        $finalImage = $row['img_src_final'] ?? null;
                        $finalDesc = $row['deskripsi_final'] ?? $row['deskripsi'] ?? '';
                    ?>

                    <a href="<?= PUBLIC_URL ?>/laboratorium/<?= $row['idLaboratorium'] ?>"
                       class="facility-row" 
                       data-id="<?= $row['idLaboratorium'] ?>">
                        
                        <div class="facility-img-side" style="background: <?= $style['bg'] ?>;">
                            <?php if (!empty($finalImage)): ?>
                                <img src="<?= $finalImage ?>" 
                                     alt="<?= htmlspecialchars($row['nama']) ?>"
                                     style="width:100%; height:100%; object-fit:cover; opacity:0.9;" 
                                     loading="lazy">
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
                            <p><?= htmlspecialchars($finalDesc) ?></p>
                            
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
                    <i class="ri-flask-line" style="font-size:3rem; margin-bottom:15px; display:block;"></i>
                    <p>Data riset belum tersedia.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<script src="<?= defined('ASSETS_URL') ? ASSETS_URL : PUBLIC_URL ?>/js/fasilitas.js"></script>