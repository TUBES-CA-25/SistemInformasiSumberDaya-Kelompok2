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

        <div class="rules-grid" style="margin-bottom: 30px;">
            <article class="rule-card">
                <div class="rule-img-box" style="height: 500px; overflow: hidden; border-radius: 12px; margin-bottom: 15px; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                    <img src="<?= ASSETS_URL ?>/assets/uploads/Pria.jpg" alt="Standar Pakaian Pria" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <h3>Standar Pakaian Pria</h3>
                <ul class="rule-list">
                    <li><i class="ri-checkbox-circle-fill"></i> <span>Berpakaian rapi memakai kemeja putih polos</span></li>
                    <li><i class="ri-checkbox-circle-fill"></i> <span>Menggunakan celana kain berwarna hitam bukan dari bahan jeans atau semi jeans</span></li>
                    <li><i class="ri-checkbox-circle-fill"></i> <span>Rambut rapi dan tidak panjang</span></li>
                </ul>
            </article>

            <article class="rule-card">
                <div class="rule-img-box" style="height: 500px; overflow: hidden; border-radius: 12px; margin-bottom: 15px; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                    <img src="<?= ASSETS_URL ?>/assets/uploads/Wanita.jpg" alt="Standar Pakaian Wanita" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <h3>Standar Pakaian Wanita</h3>
                <ul class="rule-list">
                    <li><i class="ri-checkbox-circle-fill"></i> <span>Berpakaian rapi memakai kemeja tunik putih polos (tidak transparan)</span></li>
                    <li><i class="ri-checkbox-circle-fill"></i> <span>Memakai Jilbab Segitiga Hitam (bukan pasmina) dan menutupi dada</span></li>
                    <li><i class="ri-checkbox-circle-fill"></i> <span>Menggunakan Rok Panjang berwarna hitam yang menutupi mata kaki, tidak terbelah, tidak span, dan bukan bahan jeans/semi jeans</span></li>
                    <li><i class="ri-checkbox-circle-fill"></i> <span>Memakai kaos kaki dengan tinggi minimal 10 cm di atas mata kaki</span></li>
                </ul>
            </article>
        </div>

        <div style="margin-bottom: 50px;">
            <div class="sanksi-title" style="margin-bottom: 20px;">
                <i class="ri-video-line" style="color: #2563eb; font-size: 1.5rem;"></i>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">Video Panduan & Tata Tertib Lab</h2>
            </div>
            
            <div style="width: 100%; max-width: 900px; margin: 0 auto; background: #000; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0;"
                        src="https://www.youtube.com/embed/OLLK4sF2XyM?rel=0&modestbranding=1" 
                        title="Video Panduan Lab FIKOM UMI" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>

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