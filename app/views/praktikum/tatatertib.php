<?php
/**
 * VIEW: TATA TERTIB & SANKSI (FINAL FIXED - NO ERROR)
 * Menggunakan tabel: peraturan_lab & sanksi_lab
 */

global $pdo;

// ==========================================
// 1. AMBIL DATA DARI DATABASE
// ==========================================

// A. Data Peraturan (Tabel: peraturan_lab)
$rules_data = [];
try {
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
                    <li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>Berpakaian rapi memakai kemeja putih polos</span></li>
                    <li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>Menggunakan celana kain berwarna hitam bukan dari bahan jeans atau semi jeans</span></li>
                    <li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>Rambut rapi dan tidak panjang</span></li>
                </ul>
            </article>

            <article class="rule-card">
                <div class="rule-img-box" style="height: 500px; overflow: hidden; border-radius: 12px; margin-bottom: 15px; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                    <img src="<?= ASSETS_URL ?>/assets/uploads/Wanita.jpg" alt="Standar Pakaian Wanita" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <h3>Standar Pakaian Wanita</h3>
                <ul class="rule-list">
                    <li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>Berpakaian rapi memakai kemeja tunik putih polos (tidak transparan)</span></li>
                    <li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>Memakai Jilbab Segitiga Hitam (bukan pasmina) dan menutupi dada</span></li>
                    <li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>Menggunakan Rok Panjang berwarna hitam yang menutupi mata kaki, tidak terbelah, tidak span, dan bukan bahan jeans/semi jeans</span></li>
                    <li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>Memakai kaos kaki dengan tinggi minimal 10 cm di atas mata kaki</span></li>
                </ul>
            </article>
        </div>

        <div style="margin-bottom: 80px;">
            <div class="video-header-wrapper">
                <div class="video-badge">
                    <i class="ri-movie-line"></i> Wajib Disimak
                </div>
                
                <div class="video-title-box">
                    <h2>
                        <span class="video-play-icon"><i class="ri-play-fill"></i></span>
                        Video Panduan & Keselamatan
                    </h2>
                </div>
                
                <p class="video-desc">
                    Pelajari prosedur keselamatan, penggunaan alat, dan tata tertib laboratorium melalui video panduan resmi berikut ini.
                </p>
            </div>
            
            <div style="width: 100%; max-width: 900px; margin: 0 auto; background: #000; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); border: 4px solid #ffffff;">
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
                        // FIX: Cek apakah kolom 'gambar' ada dan tidak kosong
                        $hasImage = !empty($row['gambar']);
                        $imageUrl = $hasImage ? ASSETS_URL . '/assets/uploads/' . $row['gambar'] : '';
                    ?>

                    <article class="rule-card">
                        <?php if ($hasImage) : ?>
                            <div class="rule-img-box">
                                <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($row['judul']) ?>" loading="lazy">
                            </div>
                        <?php else : ?>
                            <div class="rule-icon icon-red">
                                <i class="ri-error-warning-line"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h3><?= htmlspecialchars($row['judul']) ?></h3>
                        
                        <ul class="rule-list">
                            <li>
                                <i class="ri-prohibited-line" style="color: #dc2626;"></i>
                                <span><?= htmlspecialchars($row['deskripsi']) ?></span>
                            </li>
                        </ul>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                    <i class="ri-file-list-3-line" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <p style="color: #64748b; margin-top: 10px;">Data peraturan umum belum ditambahkan di database.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="sanksi-container">
            <div class="sanksi-title">
                <i class="ri-alarm-warning-fill"></i>
                <h2>Ketentuan Sanksi Pelanggaran</h2>
            </div>

            <div class="sanksi-grid">
                <?php if (!empty($sanksi_data)) : ?>
                    <?php foreach ($sanksi_data as $row) : ?>
                        <?php
                            // 1. Ambil nama file dari DB
                            $gambarDB = $row['gambar'] ?? '';
                            $sanksiImgUrl = '';

                            // 2. Cek apakah file fisik BENAR-BENAR ADA di folder
                            if (!empty($gambarDB)) {
                                // Definisikan path fisik komputer (bukan URL)
                                $pathFisik = defined('ROOT_PROJECT') 
                                    ? ROOT_PROJECT . '/public/assets/uploads/' . $gambarDB 
                                    : dirname(__DIR__, 3) . '/public/assets/uploads/' . $gambarDB;

                                if (file_exists($pathFisik)) {
                                    $sanksiImgUrl = ASSETS_URL . '/assets/uploads/' . $gambarDB;
                                }
                            }
                        ?>
                        
                        <div class="sanksi-item">
                            <div class="sanksi-icon-box">
                                <?php if (!empty($sanksiImgUrl)): ?>
                                    <img src="<?= $sanksiImgUrl ?>" alt="Sanksi" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                <?php else : ?>
                                    <i class="ri-alarm-warning-fill" style="font-size: 2rem;"></i>
                                <?php endif; ?>
                            </div>

                            <div class="sanksi-content">
                                <h4><?= htmlspecialchars($row['judul']) ?></h4>
                                <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="sanksi-item" style="grid-column: 1/-1; justify-content: center;">
                        <p>Data sanksi belum tersedia di database.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</section>