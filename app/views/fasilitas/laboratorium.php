<?php
// 1. Ambil data yang dikirim Controller
// Menggunakan null coalescing operator (??) untuk keamanan jika data kosong
$lab_list = $data['laboratorium'] ?? [];
?>

<section class="fasilitas-section">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Sarana & Prasarana</span>
            <h1>Fasilitas Laboratorium</h1>
            <p>Laboratorium komputer berstandar industri untuk menunjang praktikum dan keahlian mahasiswa.</p>
        </header>

        <div class="facility-grid">
            
            <?php if (!empty($lab_list)) : ?>
                <?php foreach ($lab_list as $row) : ?>
                    <?php
                        // --- PENYESUAIAN VARIABLE (FIX ERROR) ---
                        // Kita cek semua kemungkinan nama variable agar tidak error lagi
                        
                        // 1. Cek Gambar (img_src atau img_src_final atau gambar mentah)
                        $finalImage = $row['img_src'] ?? $row['img_src_final'] ?? null;
                        
                        // 2. Cek Deskripsi (short_desc atau deskripsi_final atau deskripsi mentah)
                        $finalDesc = $row['short_desc'] ?? $row['deskripsi_final'] ?? $row['deskripsi'] ?? '';
                    ?>

                    <a href="<?= PUBLIC_URL ?>/index.php?page=detail_fasilitas&id=<?= $row['idLaboratorium'] ?>"
                       class="facility-row" 
                       data-id="<?= $row['idLaboratorium'] ?>">
                        
                        <div class="facility-img-side">
                            <?php if (!empty($finalImage)) : ?>
                                <img src="<?= $finalImage ?>" 
                                     alt="<?= htmlspecialchars($row['nama']) ?>" 
                                     style="width:100%; height:100%; object-fit:cover;" 
                                     loading="lazy">
                            <?php else : ?>
                                <div class="img-overlay-placeholder">
                                    <i class="ri-computer-line"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="facility-info-side">
                            <div class="facility-meta">
                                <span class="badge-info">
                                    <i class="ri-group-line"></i> Kapasitas <?= $row['kapasitas'] ?> Mahasiswa
                                </span>
                            </div>

                            <h3><?= htmlspecialchars($row['nama']) ?></h3>
                            
                            <p><?= htmlspecialchars($finalDesc) ?></p>
                            
                            <div class="mini-specs">
                                <?php if (!empty($row['processor'])) : ?>
                                    <div class="mini-spec-item"><i class="ri-cpu-line"></i> <?= htmlspecialchars($row['processor']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($row['ram'])) : ?>
                                    <div class="mini-spec-item"><i class="ri-ram-2-line"></i> <?= htmlspecialchars($row['ram']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($row['gpu'])) : ?>
                                    <div class="mini-spec-item"><i class="ri-window-line"></i> <?= htmlspecialchars($row['gpu']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>

                <?php endforeach; ?>
            
            <?php else : ?>
                <div style="text-align:center; padding:50px; color:#94a3b8; width:100%;">
                    <i class="ri-computer-line" style="font-size:3rem; margin-bottom:15px; display:block;"></i>
                    <p>Data laboratorium belum tersedia.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<script src="<?= defined('ASSETS_URL') ? ASSETS_URL : PUBLIC_URL ?>/js/fasilitas.js"></script>