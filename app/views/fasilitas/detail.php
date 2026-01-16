<?php
/**
 * VIEW: DETAIL FASILITAS (FIXED SLIDER & CONTACT & DYNAMIC BACK BUTTON)
 */

global $pdo;
$lab = null;
$gallery = [];
$id_lab = 0;
$linkKembali = PUBLIC_URL . '/laboratorium'; // Default link jika data null

// 1. LOGIKA ID
if (isset($data['id'])) {
    $id_lab = $data['id'];
} elseif (isset($_GET['id'])) {
    $id_lab = $_GET['id'];
} else {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $lastSegment = end($segments);
    if (is_numeric($lastSegment)) $id_lab = $lastSegment;
}

// 2. QUERY DATABASE
if ($id_lab > 0) {
    try {
        $query = "SELECT l.*, a.email as email_koordinator, a.nama as nama_asisten, a.foto as foto_asisten 
                  FROM laboratorium l 
                  LEFT JOIN asisten a ON l.idKordinatorAsisten = a.idAsisten 
                  WHERE l.idLaboratorium = ?";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_lab]);
        $lab = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lab) {
            // Ambil Galeri Gambar
            $stmtImg = $pdo->prepare("SELECT namaGambar, deskripsiGambar FROM laboratorium_gambar WHERE idLaboratorium = ? ORDER BY isUtama DESC, urutan ASC");
            $stmtImg->execute([$id_lab]);
            $extraImages = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

            // Susun Gallery
            if (!empty($lab['gambar']) && file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $lab['gambar'])) {
                $gallery[] = [
                    'src' => ASSETS_URL . '/assets/uploads/' . $lab['gambar'],
                    'desc' => 'Foto Utama'
                ];
            }
            foreach ($extraImages as $img) {
                if (!empty($img['namaGambar']) && file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $img['namaGambar'])) {
                    if (empty($lab['gambar']) || $lab['gambar'] !== $img['namaGambar']) {
                        $gallery[] = [
                            'src' => ASSETS_URL . '/assets/uploads/' . $img['namaGambar'],
                            'desc' => $img['deskripsiGambar'] ?? ''
                        ];
                    }
                }
            }
        }
    } catch (Exception $e) {}
}

// 3. DATA PROCESSING & DYNAMIC LINK LOGIC
if ($lab) {
    // --- A. LOGIKA TOMBOL KEMBALI DINAMIS ---
    $kategori = isset($lab['jenis']) ? strtolower($lab['jenis']) : '';
    
    // Cek jika ini adalah Ruang Riset
    if (strpos($kategori, 'riset') !== false || strpos($kategori, 'research') !== false) {
        $linkKembali = PUBLIC_URL . '/fasilitas/ruang_riset';
    } 
    // Cek jika ini adalah Fasilitas Umum
    else if (strpos($kategori, 'umum') !== false || strpos($kategori, 'public') !== false) {
        $linkKembali = PUBLIC_URL . '/fasilitas/public_area';
    }
    // Default tetap ke Laboratorium

    // --- B. DATA SPESIFIKASI ---
    $hardwareData = [
        'Processor' => $lab['processor'],
        'RAM'       => $lab['ram'],
        'Storage'   => $lab['storage'],
        'GPU'       => $lab['gpu'],
        'Monitor'   => $lab['monitor'],
        'Jumlah PC' => $lab['jumlahPc'] ? $lab['jumlahPc'] . ' Unit' : null,
    ];
    $hardwareData = array_filter($hardwareData, fn($value) => !empty($value));
    $softwareList  = !empty($lab['software']) ? array_map('trim', explode(',', $lab['software'])) : [];
    $pendukungList = !empty($lab['fasilitas_pendukung']) ? array_map('trim', explode(',', $lab['fasilitas_pendukung'])) : [];
    
    $coordName = !empty($lab['koordinator_nama']) ? $lab['koordinator_nama'] : ($lab['nama_asisten'] ?? 'Koordinator Lab');
    $coordEmail = $lab['email_koordinator'] ?? null;

    $initials = '';
    foreach (explode(' ', $coordName) as $part) {
        if (ctype_alpha($part[0])) $initials .= strtoupper($part[0]);
        if (strlen($initials) >= 2) break;
    }
    
    // Tentukan link kembali berdasarkan jenis fasilitas
    $backLink = ($lab['jenis'] === 'Riset') ? PUBLIC_URL . '/riset' : PUBLIC_URL . '/laboratorium';
}
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
                <span class="badge-type"><?= htmlspecialchars($lab['jenis']) ?></span>
                <h1 class="main-title"><?= htmlspecialchars($lab['nama']) ?></h1>
                
                <div class="meta-info-row">
                    <div class="meta-item-detail">
                        <i class="ri-group-fill"></i>
                        <span>Kapasitas: <b><?= $lab['kapasitas'] ?> Orang</b></span>
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
                            <?php 
                                $fotoKoord = null;
                                if (!empty($lab['koordinator_foto']) && file_exists(ROOT_PROJECT.'/public/assets/uploads/'.$lab['koordinator_foto'])) {
                                    $fotoKoord = ASSETS_URL . '/assets/uploads/' . $lab['koordinator_foto'];
                                } elseif (!empty($lab['foto_asisten']) && file_exists(ROOT_PROJECT.'/public/assets/uploads/'.$lab['foto_asisten'])) {
                                    $fotoKoord = ASSETS_URL . '/assets/uploads/' . $lab['foto_asisten'];
                                }
                                
                                if ($fotoKoord): 
                            ?>
                                <img src="<?= $fotoKoord ?>" class="coord-img-circle" alt="Foto Koordinator" loading="lazy">
                            <?php else: ?>
                                <div class="coord-avatar-circle"><?= $initials ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="coord-name-large">
                            <?= htmlspecialchars($coordName) ?>
                        </div>
                        <span class="coord-role-badge">Koordinator</span>

                        <?php if ($coordEmail): ?>
                            <a href="mailto:<?= htmlspecialchars($coordEmail) ?>" class="coord-contact-btn">
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
                <a href="<?= PUBLIC_URL ?>/laboratorium" class="btn-back" style="justify-content: center; margin-top: 30px;">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/fasilitas.js"></script>