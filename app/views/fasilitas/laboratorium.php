<?php
/**
 * VIEW: LABORATORIUM PENGAJARAN
 * Filter: jenis = 'Laboratorium'
 */

$lab_list = [];

if (!empty($data['laboratorium'])) {
    $lab_list = $data['laboratorium'];
} else {
    global $pdo;
    try {
        if ($pdo instanceof PDO) {
            // FILTER: Hanya ambil yang jenisnya 'Laboratorium'
            $stmt = $pdo->query("SELECT * FROM laboratorium WHERE jenis = 'Laboratorium' ORDER BY idLaboratorium ASC");
            $lab_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Throwable $e) { $lab_list = []; }
}

function getLabImg($row) {
    if (!empty($row['gambar']) && file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $row['gambar'])) {
        return ASSETS_URL . '/assets/uploads/' . $row['gambar'];
    }
    return null;
}
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
                        $imgSrc = getLabImg($row); 
                        $descRaw = $row['deskripsi'] ?? ''; 
                        $deskripsi = strlen($descRaw) > 150 ? substr($descRaw, 0, 150) . '...' : $descRaw;
                    ?>

                    <a href="<?= PUBLIC_URL ?>/detail_fasilitas/<?= $row['idLaboratorium'] ?>" class="facility-row">
                        <div class="facility-img-side">
                            <?php if ($imgSrc) : ?>
                                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($row['nama']) ?>" 
                                     style="width:100%; height:100%; object-fit:cover;">
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
                            <p><?= htmlspecialchars($deskripsi) ?></p>
                            
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
                    <i class="ri-computer-line" style="font-size:3rem;"></i>
                    <p>Data laboratorium belum tersedia.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>