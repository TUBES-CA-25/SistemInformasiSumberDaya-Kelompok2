<?php
/**
 * VIEW: DAFTAR ASISTEN (UPDATED STRUCTURE)
 * Fix: Logika Avatar seragam (Bold & Abaikan link lama di DB)
 */

global $pdo; 

$koordinator_list = [];
$asisten_list = [];

try {
    // Query data asisten aktif
    $stmt = $pdo->query("SELECT * FROM asisten WHERE statusAktif = 1 ORDER BY nama ASC");
    $all_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pisahkan Koordinator dan Anggota
    foreach ($all_data as $row) {
        if ($row['isKoordinator'] == 1) {
            $koordinator_list[] = $row;
        } else {
            $asisten_list[] = $row;
        }
    }

} catch (PDOException $e) {
    echo "<div class='alert-error'>Error Database: " . $e->getMessage() . "</div>";
}
?>

<section class="sumberdaya-section fade-up">
    <div class="container">
        
        <div class="page-header">
            <span class="header-badge">Sumber Daya Manusia</span>
            <h1>Asisten Laboratorium</h1>
            <p>Mahasiswa terpilih yang berdedikasi membantu kelancaran praktikum dan riset di Laboratorium FIKOM UMI.</p>

            <div class="search-container" style="margin-top: 30px; position: relative; max-width: 450px; margin-left: auto; margin-right: auto;">
                <input type="text" id="searchAsisten" placeholder="Cari asisten..." 
                       class="search-input" 
                       style="width: 100%; padding: 14px 24px; padding-right: 50px; border-radius: 50px; border: 1px solid #cbd5e1; outline: none; font-size: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <i class="ri-search-line" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.2rem;"></i>
            </div>
        </div>

        <?php if (!empty($koordinator_list)) : ?>
            <div class="section-label">
                <span>Koordinator Laboratorium</span>
            </div>

            <?php foreach ($koordinator_list as $coord) : ?>
                <?php 
                    // LOGIKA GAMBAR KOORDINATOR (Tetap Biru agar Spesial)
                    $fotoName = $coord['foto'] ?? '';
                    $namaEnc = urlencode($coord['nama']);
                    
                    // Default: Biru Muda + Bold
                    $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

                    if (!empty($fotoName)) {
                        // 1. Abaikan jika link di DB adalah ui-avatars (biar style kita yang menang)
                        if (strpos($fotoName, 'ui-avatars.com') !== false) {
                            // Do nothing
                        }
                        // 2. Cek External
                        elseif (strpos($fotoName, 'http') !== false) {
                            $imgUrl = $fotoName;
                        }
                        // 3. Cek File Lokal
                        elseif (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
                            $imgUrl = ASSETS_URL . '/images/asisten/' . $fotoName;
                        }
                    }
                ?>
                <div class="card-link" style="margin-bottom: 40px;"> 
                    <div class="exec-card">
                        <div class="exec-photo">
                            <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($coord['nama']) ?>">
                        </div>
                        <div class="exec-info">
                            <span class="exec-badge">Koordinator</span>
                            <h3 class="staff-name" style="font-size: 2rem; margin-bottom: 5px;"><?= htmlspecialchars($coord['nama']) ?></h3>
                            <p class="staff-role exec-role">
                                <?= htmlspecialchars($coord['jurusan'] ?? 'Teknik Informatika') ?>
                            </p>
                            
                            <div class="exec-footer">
                                <?php if (!empty($coord['email'])) : ?>
                                <div class="meta-item">
                                    <i class="ri-mail-line"></i> 
                                    <span><?= htmlspecialchars($coord['email']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div style="margin-top: 20px;">
                                <a href="index.php?page=detail&id=<?= $coord['idAsisten'] ?>&type=asisten" class="btn-contact" style="font-size: 0.9rem; padding: 10px 20px;">
                                    Lihat Profil <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="section-label">
            <span>Daftar Asisten Praktikum</span>
        </div>

        <div class="staff-grid">
            
            <?php if (empty($asisten_list)) : ?>
                <div style="width: 100%; text-align: center; padding: 60px; color: #64748b; background: #fff; border-radius: 20px; border: 2px solid #e2e8f0;">
                    <p>Belum ada data asisten aktif.</p>
                </div>
            <?php else : ?>
                
                <?php foreach ($asisten_list as $row) : ?>
                    <?php 
                        // LOGIKA GAMBAR ASISTEN (Abu-abu Seragam)
                        $fotoName = $row['foto'] ?? '';
                        $namaEnc = urlencode($row['nama']);
                        
                        // Default: Abu-abu + Bold (Sama seperti Alumni)
                        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=256&bold=true"; 

                        if (!empty($fotoName)) {
                            // 1. Abaikan link ui-avatars dari database
                            if (strpos($fotoName, 'ui-avatars.com') !== false) {
                                // Do nothing
                            }
                            // 2. Cek External
                            elseif (strpos($fotoName, 'http') !== false) {
                                $imgUrl = $fotoName;
                            }
                            // 3. Cek File Lokal
                            elseif (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
                                $imgUrl = ASSETS_URL . '/images/asisten/' . $fotoName;
                            }
                        }
                        
                        $jurusan = $row['jurusan'] ?? 'Teknik Informatika';
                        $email = $row['email'] ?? '';
                        $id = $row['idAsisten']; 
                    ?>
                    
                    <a href="index.php?page=detail&id=<?= $id ?>&type=asisten" class="card-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
                            </div>

                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                <span class="staff-role">Asisten Praktikum</span>

                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-graduation-cap-line"></i> 
                                        <span><?= htmlspecialchars($jurusan) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/asisten.js"></script>
