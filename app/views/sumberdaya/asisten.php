<?php
$all_data = isset($data['asisten']) ? $data['asisten'] : [];

$koordinator_list = [];
$asisten_list = [];
$ca_list = [];

if (!empty($all_data)) {
    foreach ($all_data as $row) {
        $status = $row['statusAktif'] ?? 0;

        if (isset($row['isKoordinator']) && $row['isKoordinator'] == 1) {
            $koordinator_list[] = $row;
            continue;
        }

        if ($status === 'CA' || $status === 'Calon Asisten') {
            $ca_list[] = $row;
        } 
        elseif ($status == 1 || $status === 'Asisten' || $status === 'Aktif') {
            $asisten_list[] = $row;
        }
    }
}
?>

<section class="sumberdaya-section fade-up">
    <div class="container"> 
        
        <div class="page-header">
            <span class="header-badge">Sumber Daya Manusia</span>
            <h1>Asisten Laboratorium</h1>
            <p>Mahasiswa terpilih yang berdedikasi membantu kelancaran praktikum.</p>

            <div class="search-container" style="margin-top: 30px; position: relative; max-width: 450px; margin-left: auto; margin-right: auto;">
                <input type="text" id="searchAsisten" placeholder="Cari asisten..." 
                       class="search-input" 
                       style="width: 100%; padding: 14px 24px; padding-right: 50px; border-radius: 50px; border: 1px solid #cbd5e1; outline: none;">
                <i class="ri-search-line" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            </div>
        </div>

        <?php if (!empty($koordinator_list)) : ?>
            <div class="section-label">
                <span>Koordinator Laboratorium</span>
            </div>

            <?php foreach ($koordinator_list as $coord) : ?>
                <?php 
                    $fotoName = $coord['foto'] ?? '';
                    $namaEnc = urlencode($coord['nama']);
                    $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

                    if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
                        if (strpos($fotoName, 'http') !== false) {
                            $imgUrl = $fotoName;
                        } elseif (file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $fotoName)) {
                            $imgUrl = ASSETS_URL . '/assets/uploads/' . $fotoName;
                        } elseif (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
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
                            <h3 class="staff-name"><?= htmlspecialchars($coord['nama']) ?></h3>
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

                                <!-- FIX LINK DETAIL -->
                                <a href="index.php?page=detail-asisten&id=<?= $coord['idAsisten'] ?>&type=asisten" class="btn-contact">
                                    Lihat Profil
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="section-label">
            <span>Asisten Praktikum</span>
        </div>

        <div class="staff-grid">
            <?php if (empty($asisten_list)) : ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #94a3b8; border: 1 dashed #cbd5e1; border-radius: 12px;">
                    <p>Belum ada data asisten praktikum.</p>
                </div>
            <?php else : ?>
                <?php foreach ($asisten_list as $row) : ?>
                    <?php 
                        $fotoName = $row['foto'] ?? '';
                        $namaEnc = urlencode($row['nama']);
                        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=f1f5f9&color=475569&size=256&bold=true"; 

                        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
                            if (strpos($fotoName, 'http') !== false) {
                                $imgUrl = $fotoName;
                            } elseif (file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $fotoName)) {
                                $imgUrl = ASSETS_URL . '/assets/uploads/' . $fotoName;
                            } elseif (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
                                $imgUrl = ASSETS_URL . '/images/asisten/' . $fotoName;
                            }
                        }
                    ?>
                    
                    <!-- FIX LINK DETAIL -->
                    <a href="index.php?page=detail-asisten&id=<?= $row['idAsisten'] ?>&type=asisten" class="card-link">
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
                                        <span><?= htmlspecialchars($row['jurusan'] ?? 'Teknik Informatika') ?></span>
                                    </div>
                                    
                                    <?php if (!empty($row['email'])) : ?>
                                    <div class="meta-item">
                                        <i class="ri-mail-line"></i> 
                                        <span style="font-size: 0.8rem; word-break: break-all;"><?= htmlspecialchars($row['email']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($ca_list)) : ?>
            <div class="section-label" style="margin-top: 40px;">
                <span>Calon Asisten (CA)</span>
            </div>

            <div class="staff-grid">
                <?php foreach ($ca_list as $row) : ?>
                    <?php 
                        $fotoName = $row['foto'] ?? '';
                        $namaEnc = urlencode($row['nama']);
                        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=fffbeb&color=d97706&size=256&bold=true"; 

                        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
                            if (strpos($fotoName, 'http') !== false) {
                                $imgUrl = $fotoName;
                            } elseif (file_exists(ROOT_PROJECT . '/public/assets/uploads/' . $fotoName)) {
                                $imgUrl = ASSETS_URL . '/assets/uploads/' . $fotoName;
                            } elseif (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
                                $imgUrl = ASSETS_URL . '/images/asisten/' . $fotoName;
                            }
                        }
                    ?>
                    
                    <!-- FIX LINK DETAIL -->
                    <a href="index.php?page=detail-asisten&id=<?= $row['idAsisten'] ?>&type=asisten" class="card-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
                                <span style="position: absolute; top: 10px; right: 10px; background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: bold;">CA</span>
                            </div>
                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                <span class="staff-role" style="color: #d97706;">Calon Asisten</span>
                                
                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-graduation-cap-line"></i> 
                                        <span><?= htmlspecialchars($row['jurusan'] ?? 'Teknik Informatika') ?></span>
                                    </div>

                                    <?php if (!empty($row['email'])) : ?>
                                    <div class="meta-item">
                                        <i class="ri-mail-line"></i> 
                                        <span style="font-size: 0.8rem; word-break: break-all;"><?= htmlspecialchars($row['email']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/asisten.js"></script>
