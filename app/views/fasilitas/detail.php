<?php
/**
 * VIEW: DETAIL FASILITAS (Pixel-Perfect Row Alignment Edition)
 * Data disiapkan lengkap oleh LaboratoriumController::detail()
 */

// 1. Ekstrak Data dari Controller
$lab          = $data['laboratorium'] ?? null;
$gallery      = $data['gallery'] ?? [];
$hardwareData = $data['hardware'] ?? [];
$softwareList = $data['software'] ?? [];
$pendukungList = $data['pendukung'] ?? [];
$backLink     = $data['back_link'] ?? (PUBLIC_URL . '/laboratorium');
$coord        = $data['koordinator'] ?? ['nama' => 'N/A', 'initials' => 'NA'];
?>

<section class="fasilitas-section detail-page-section symmetrical-detail-view">
    <div class="container">
        
        <?php if ($lab) : ?>
            
            <!-- Top Navigation -->
            <a href="<?= $backLink ?>" class="btn-back-sym">
                <i class="ri-arrow-left-line"></i> Kembali ke Daftar Fasilitas
            </a>

            <!-- Full-Width 3D Interactive Photo Deck (Preserved Intact) -->
            <div class="detail-hero-wrapper" id="photoDeckWrapper">
                <div class="photo-stack-container" id="photoStackContainer">
                    <?php if (!empty($gallery)) : ?>
                        <div class="photo-stack-deck <?= count($gallery) === 1 ? 'single-photo' : '' ?>" id="photoStackDeck">
                            <?php foreach ($gallery as $index => $img) : ?>
                                <div class="photo-card-item card-pos-<?= $index % 3 ?>" data-index="<?= $index ?>" onclick="window.openLightbox(<?= $index ?>)">
                                    <div class="card-inner">
                                        <img src="<?= $img['src'] ?>" alt="Foto <?= htmlspecialchars($lab['nama']) ?> - <?= $index + 1 ?>" loading="lazy">
                                        <?php if(!empty($img['desc'])): ?>
                                            <div class="card-overlay-badge">
                                                <i class="ri-camera-lens-line"></i> <?= htmlspecialchars($img['desc']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-expand-hint"><i class="ri-fullscreen-line"></i> Perbesar</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="no-image-placeholder-award">
                            <i class="ri-image-2-line"></i>
                            <p>Dokumentasi foto belum tersedia</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Eye-Catching Symmetrical Header Title Block -->
            <div class="sym-header-card eyecatching-header">
                <div class="sym-header-top">
                    <div class="header-type-tag">
                        <i class="ri-sparkles-fill"></i>
                        <span><?= htmlspecialchars($lab['jenis'] ?? 'Fasilitas Akademik') ?></span>
                    </div>
                    <div class="sym-instansi-chip">
                        <i class="ri-building-2-fill"></i> FIKOM UMI Makassar
                    </div>
                </div>

                <h1 class="sym-title eyecatching-title"><?= htmlspecialchars($lab['nama']) ?></h1>
                
                <div class="sym-meta-chips">
                    <div class="sym-chip highlight-chip">
                        <div class="chip-icon-box blue"><i class="ri-user-group-fill"></i></div>
                        <span>Kapasitas: <strong><?= htmlspecialchars($lab['kapasitas'] ?? '-') ?> Mahasiswa</strong></span>
                    </div>
                    <?php if(!empty($lab['lokasi'])): ?>
                    <div class="sym-chip highlight-chip">
                        <div class="chip-icon-box purple"><i class="ri-map-pin-fill"></i></div>
                        <span>Lokasi: <strong><?= htmlspecialchars($lab['lokasi']) ?></strong></span>
                    </div>
                    <?php endif; ?>
                    <div class="sym-chip highlight-chip">
                        <div class="chip-icon-box emerald"><i class="ri-computer-fill"></i></div>
                        <span>Perangkat: <strong>Lengkap & Terinstal</strong></span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid Organized in 2 Pixel-Perfect Aligned Rows -->
            <div class="sym-dashboard-rows">
                
                <!-- ROW 1: Tentang Fasilitas (Left) & Penanggung Jawab (Right) -->
                <div class="sym-row">
                    <!-- Card 1: Tentang Fasilitas -->
                    <div class="sym-card sym-card-equal">
                        <div class="sym-card-header">
                            <div class="sym-icon-badge blue"><i class="ri-article-line"></i></div>
                            <h3>Tentang Fasilitas</h3>
                        </div>
                        <div class="sym-desc-text">
                            <?= nl2br(htmlspecialchars($lab['deskripsi'] ?? 'Deskripsi laboratorium belum tersedia.')) ?>
                        </div>
                    </div>

                    <!-- Card 2: Penanggung Jawab (Koordinator) -->
                    <div class="sym-card sym-card-equal sym-coord-card">
                        <div class="sym-card-header">
                            <div class="sym-icon-badge amber"><i class="ri-user-star-line"></i></div>
                            <h3>Penanggung Jawab</h3>
                        </div>
                        <div class="sym-coord-content">
                            <div class="sym-avatar-box">
                                <?php if (!empty($coord['foto'])): ?>
                                    <img src="<?= $coord['foto'] ?>" alt="Foto Koordinator" style="<?= Helper::objectPosStyle($coord) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="sym-initials"><?= htmlspecialchars($coord['initials']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="sym-coord-details">
                                <h4 class="sym-coord-name"><?= htmlspecialchars($coord['nama']) ?></h4>
                                <span class="sym-coord-role">Koordinator Laboratorium</span>
                                <?php if (!empty($coord['email'])): ?>
                                    <a href="mailto:<?= htmlspecialchars($coord['email']) ?>" class="sym-email-btn">
                                        <i class="ri-mail-send-line"></i> <?= htmlspecialchars($coord['email']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="sym-email-disabled"><i class="ri-mail-forbid-line"></i> Kontak N/A</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 2: Spesifikasi Hardware (Left) & Software Terinstall (Right) -->
                <div class="sym-row">
                    <!-- Card 3: Spesifikasi Hardware -->
                    <?php if (!empty($hardwareData)) : ?>
                    <div class="sym-card sym-card-equal">
                        <div class="sym-card-header">
                            <div class="sym-icon-badge purple"><i class="ri-cpu-line"></i></div>
                            <h3>Spesifikasi Hardware</h3>
                        </div>
                        <div class="sym-hw-grid">
                            <?php foreach($hardwareData as $label => $val): ?>
                                <?php 
                                    $icon = 'ri-cpu-line';
                                    $lblLower = strtolower($label);
                                    if (strpos($lblLower, 'ram') !== false || strpos($lblLower, 'memori') !== false) $icon = 'ri-ram-2-line';
                                    elseif (strpos($lblLower, 'vga') !== false || strpos($lblLower, 'gpu') !== false || strpos($lblLower, 'grafis') !== false) $icon = 'ri-macbook-line';
                                    elseif (strpos($lblLower, 'storage') !== false || strpos($lblLower, 'ssd') !== false || strpos($lblLower, 'hdd') !== false || strpos($lblLower, 'penyimpanan') !== false) $icon = 'ri-hard-drive-2-line';
                                    elseif (strpos($lblLower, 'monitor') !== false || strpos($lblLower, 'layar') !== false) $icon = 'ri-tv-2-line';
                                    elseif (strpos($lblLower, 'os') !== false || strpos($lblLower, 'sistem') !== false) $icon = 'ri-terminal-box-line';
                                ?>
                                <div class="sym-hw-item">
                                    <div class="sym-hw-icon"><i class="<?= $icon ?>"></i></div>
                                    <div class="sym-hw-info">
                                        <span class="sym-hw-lbl"><?= htmlspecialchars($label) ?></span>
                                        <span class="sym-hw-val"><?= htmlspecialchars($val) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Card 4: Software Terinstall -->
                    <?php if (!empty($softwareList)) : ?>
                    <div class="sym-card sym-card-equal">
                        <div class="sym-card-header">
                            <div class="sym-icon-badge emerald"><i class="ri-code-box-line"></i></div>
                            <h3>Software & Tools Terinstall</h3>
                        </div>
                        <div class="sym-sw-tags">
                            <?php foreach($softwareList as $sw): ?>
                                <span class="sym-sw-tag">
                                    <span class="dot"></span>
                                    <?= htmlspecialchars($sw) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Full-Width Bottom Section: Sarana Pendukung -->
            <?php if (!empty($pendukungList)) : ?>
            <div class="sym-card sym-full-width-card">
                <div class="sym-card-header">
                    <div class="sym-icon-badge indigo"><i class="ri-shield-star-line"></i></div>
                    <h3>Fasilitas & Sarana Pendukung</h3>
                </div>
                <div class="sym-facility-grid">
                    <?php foreach($pendukungList as $p): ?>
                        <div class="sym-fac-item">
                            <i class="ri-checkbox-circle-fill"></i>
                            <span><?= htmlspecialchars($p) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="error-container-award">
                <i class="ri-file-search-line error-icon"></i>
                <h2>Data Tidak Ditemukan</h2>
                <p>Maaf, detail laboratorium atau fasilitas yang Anda cari tidak tersedia dalam sistem.</p>
                <a href="<?= PUBLIC_URL ?>/laboratorium" class="btn-back-sym" style="margin: 20px auto 0; display: inline-flex;">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar Fasilitas
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- Fullscreen Photo Lightbox Modal -->
<div id="photoLightboxModal" class="lightbox-modal" aria-hidden="true">
    <button type="button" class="lightbox-close-btn" id="lightboxCloseBtn" aria-label="Tutup"><i class="ri-close-line"></i></button>
    <button type="button" class="lightbox-nav-btn prev" id="lightboxPrevBtn" aria-label="Foto Sebelumnya"><i class="ri-arrow-left-s-line"></i></button>
    <button type="button" class="lightbox-nav-btn next" id="lightboxNextBtn" aria-label="Foto Selanjutnya"><i class="ri-arrow-right-s-line"></i></button>
    <div class="lightbox-content">
        <img id="lightboxImg" src="" alt="Foto Perbesar">
        <div id="lightboxCaption" class="lightbox-caption"></div>
    </div>
</div>

<script>
    window.galleryImages = <?= json_encode(array_values($gallery)) ?>;
</script>
<script src="<?= defined('ASSETS_URL') ? ASSETS_URL : PUBLIC_URL ?>/js/fasilitas.js" defer></script>