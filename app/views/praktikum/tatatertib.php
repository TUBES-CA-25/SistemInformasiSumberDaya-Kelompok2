<?php
/**
 * VIEW: TATA TERTIB & SANKSI (FINAL FIXED)
 * Menggunakan tabel: peraturan_lab & sanksi_lab
 */

global $pdo;

// ==========================================
// 1. AMBIL DATA DARI DATABASE
// ==========================================

// A. Data Peraturan (Tabel: peraturan_lab)
$rules_data = [];
try {
    // Kita urutkan berdasarkan kolom 'urutan' agar rapi
    $stmt = $pdo->query("SELECT * FROM peraturan_lab ORDER BY urutan ASC");
    $rules_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $rules_data = [];
}

// B. Data Sanksi (Tabel: sanksi_lab)
$sanksi_data = [];
try {
    $stmt = $pdo->query("SELECT * FROM sanksi_lab ORDER BY urutan ASC");
    $sanksi_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $sanksi_data = [];
}

// ==========================================
// 2. HELPER STYLE (WARNA & IKON)
// ==========================================
function getRuleStyle($index) {
    $styles = [
        0 => ['class' => 'icon-blue',   'icon' => 'ri-history-line'],
        1 => ['class' => 'icon-pink',   'icon' => 'ri-shirt-line'],
        2 => ['class' => 'icon-red',    'icon' => 'ri-spam-3-line'],
        3 => ['class' => 'icon-purple', 'icon' => 'ri-computer-line'],
        4 => ['class' => 'icon-green',  'icon' => 'ri-shield-check-line']
    ];
    // Modulo agar jika data lebih dari 5, warnanya mengulang dari awal
    return $styles[$index % count($styles)];
}
?>

<section class="rules-section">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Pedoman & Aturan <?= date('Y') ?></span>
            
            <h1>Tata Tertib & Sanksi</h1>
            <p>Pedoman standar operasional dan kedisiplinan bagi seluruh praktikan di lingkungan Laboratorium Terpadu FIKOM UMI.</p>
        </header>

        <div class="rules-grid">
            
            <?php if (!empty($rules_data)) : ?>
                <?php foreach ($rules_data as $index => $row) : ?>
                    <?php 
                        // Ambil style default (ikon & warna) berdasarkan urutan
                        $style = getRuleStyle($index); 

                        // Cek apakah ada gambar di database?
                        $hasImage = !empty($row['gambar']);
                        $imageUrl = ASSETS_URL . '/assets/uploads/' . $row['gambar'];
                    ?>

                    <article class="rule-card">
                        
                        <?php if ($hasImage) : ?>
                            <div class="rule-img-box">
                                <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($row['judul']) ?>" loading="lazy">
                            </div>
                        <?php else : ?>
                            <div class="rule-icon <?= $style['class'] ?>">
                                <i class="<?= $style['icon'] ?>"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h3><?= htmlspecialchars($row['judul']) ?></h3>
                        
                        <ul class="rule-list">
                            <li>
                                <i class="ri-checkbox-circle-fill"></i>
                                <span><?= htmlspecialchars($row['deskripsi']) ?></span>
                            </li>
                        </ul>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                    <i class="ri-file-list-3-line" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <p style="color: #64748b; margin-top: 10px;">Data peraturan belum ditambahkan.</p>
                </div>
            <?php endif; ?>

        </div>

        <div class="sanksi-container">
            <div class="sanksi-title">
                <i class="ri-scales-fill"></i>
                <h2>Ketentuan Sanksi Pelanggaran</h2>
            </div>

            <div class="sanksi-grid">
                <?php if (!empty($sanksi_data)) : ?>
                    <?php foreach ($sanksi_data as $row) : ?>
                        <div class="sanksi-item">
                            <h4><?= htmlspecialchars($row['judul']) ?></h4>
                            
                            <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="sanksi-item" style="grid-column: 1/-1; text-align: center;">
                        <p>Data sanksi belum tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</section>