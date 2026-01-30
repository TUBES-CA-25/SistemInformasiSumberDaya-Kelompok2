<?php
/**
 * VIEW: DETAIL FASILITAS (MVC Pure Version)
 * Data sudah disiapkan lengkap oleh LaboratoriumController::detail()
 */

// 1. Ekstrak Data dari Controller
$lab         = $data['laboratorium'] ?? null;
$gallery     = $data['gallery'] ?? [];
$hardwareData = $data['hardware'] ?? [];
$softwareList = $data['software'] ?? [];
$pendukungList = $data['pendukung'] ?? [];
$backLink    = $data['back_link'] ?? (PUBLIC_URL . '/laboratorium');
$coord       = $data['koordinator'] ?? ['nama' => 'N/A', 'initials' => 'NA'];
?>

<section class="fasilitas-section">
    <div class="container">
        
        <?php if ($lab) : ?>
            
            <a href="<?= $backLink ?>" class="btn-back">
                <i class="ri-arrow-left-line"></i> Kembali ke Daftar Fasilitas
            </a>

            <div class="slider-container" id="labSlider">
                <?php if (!empty($gallery)) : ?>
                    <div class="slider-track" id="sliderTrack">
                        <?php foreach ($gallery as $index => $img) : ?>
                            <div class="slide-item">
                                <img src="<?= $img['src'] ?>" alt="Slide <?= $index + 1 ?>">
                                <?php if(!empty($img['desc'])): ?>
                                    <div class="slide-overlay"><?= htmlspecialchars($img['desc']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($gallery) > 1) : ?>
                        <button class="slider-btn prev-btn" onclick="window.moveSlide(-1)"><i class="ri-arrow-left-s-line"></i></button>
                        <button class="slider-btn next-btn" onclick="window.moveSlide(1)"><i class="ri-arrow-right-s-line"></i></button>
                        
                        <div class="slider-dots">
                            <?php foreach ($gallery as $index => $img) : ?>
                                <div class="dot <?= $index === 0 ? 'active' : '' ?>" onclick="window.goToSlide(<?= $index ?>)"></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php else : ?>
                    <div class="no-image-placeholder">
                        <i class="ri-image-2-line" style="font-size: 5rem; opacity:0.5;"></i>
                        <p style="margin-top:15px; font-weight:600;">Tidak ada gambar tersedia</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="main-info-header">
                <h1 class="main-title"><?= htmlspecialchars($lab['nama']) ?></h1>
                
                <div class="meta-info-row">
                    <div class="meta-item-detail">
                        <i class="ri-group-fill"></i>
                        <span>Kapasitas: <b><?= htmlspecialchars($lab['kapasitas'] ?? '-') ?> Orang</b></span>
                    </div>
                    <?php if(!empty($lab['lokasi'])): ?>
                    <div class="meta-item-detail">
                        <i class="ri-map-pin-fill"></i>
                        <span>Lokasi: <?= htmlspecialchars($lab['lokasi']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-bottom-split">
                
                <div class="left-details">
                    <div class="description">
                        <?= nl2br(htmlspecialchars($lab['deskripsi'] ?? 'Deskripsi belum tersedia.')) ?>
                    </div>

                    <?php if (!empty($hardwareData)) : ?>
                    <div class="spec-group">
                        <h3><i class="ri-cpu-line"></i> Spesifikasi Hardware</h3>
                        <div class="spec-list">
                            <?php foreach($hardwareData as $label => $val): ?>
                            <div class="spec-item">
                                <span class="spec-label"><?= $label ?></span>
                                <span class="spec-value"><?= htmlspecialchars($val) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($softwareList)) : ?>
                    <div class="spec-group">
                        <h3><i class="ri-code-box-line"></i> Software & Tools</h3>
                        <div class="software-tags">
                            <?php foreach($softwareList as $sw): ?>
                                <span class="sw-tag"><?= htmlspecialchars($sw) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($pendukungList)) : ?>
                    <div class="spec-group">
                        <h3><i class="ri-shield-star-line"></i> Fasilitas Pendukung</h3>
                        <ul class="facility-list">
                            <?php foreach($pendukungList as $p): ?>
                                <li><?= htmlspecialchars($p) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="right-sidebar">
                    <div class="coord-card-premium">
                        <span class="coord-label-top">PENANGGUNG JAWAB</span>
                        
                        <div class="coord-photo-frame">
                            <?php if (!empty($coord['foto'])): ?>
                                <img src="<?= $coord['foto'] ?>" class="coord-img-circle" alt="Foto Koordinator" loading="lazy">
                            <?php else: ?>
                                <div class="coord-avatar-circle"><?= htmlspecialchars($coord['initials']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="coord-name-large">
                            <?= htmlspecialchars($coord['nama']) ?>
                        </div>
                        <span class="coord-role-badge">Koordinator</span>

                        <?php if (!empty($coord['email'])): ?>
                            <a href="mailto:<?= htmlspecialchars($coord['email']) ?>" class="coord-contact-btn">
                                <i class="ri-mail-send-line"></i> Hubungi Email
                            </a>
                        <?php else: ?>
                            <a href="#" class="coord-contact-btn" style="opacity:0.6; cursor:not-allowed;">
                                <i class="ri-mail-forbid-line"></i> Email Tidak Tersedia
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        
        <?php else: ?>
            <div class="error-container">
                <i class="ri-file-search-line error-icon"></i>
                <h2 style="color: #0f172a; margin-bottom: 10px;">Data Tidak Ditemukan</h2>
                <p>Maaf, detail fasilitas yang Anda cari tidak tersedia.</p>
                <a href="<?= PUBLIC_URL ?>/laboratorium" class="btn-back" style="justify-content: center; margin-top: 30px;">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>

<script src="<?= defined('ASSETS_URL') ? ASSETS_URL : PUBLIC_URL ?>/js/fasilitas.js"></script>