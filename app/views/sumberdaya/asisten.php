<?php
$all_data = isset($data['asisten']) ? $data['asisten'] : [];

$koordinator_list = [];
$asisten_list = [];
$ca_list = [];

// Tambahan: ambil data alumni jika disediakan
$alumni_data = isset($data['alumni']) ? $data['alumni'] : [];
$alumni_list = [];

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

// Buat daftar alumni dari $alumni_data atau filter dari $all_data bila perlu
if (!empty($alumni_data)) {
    foreach ($alumni_data as $row) {
        // opsional: cek status atau atribut lain di $row
        $alumni_list[] = $row;
    }
}
?>

<section class="sumberdaya-section fade-up">
    <div class="container"> 
        
        <div class="page-header">
            <span class="header-badge">Sumber Daya Manusia</span>
            <h1>Asisten Laboratorium</h1>
            <p>Mahasiswa terpilih yang berdedikasi membantu kelancaran praktikum.</p>

            <div class="search-container">
                <input type="text" id="searchAsisten" placeholder="Cari asisten..." class="search-input">
                <i class="ri-search-line"
                    style="position:absolute; right:20px; top:50%; transform:translateY(-50%); color:#94a3b8"></i>
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
                <div class="card-link exec-margin" data-id="<?= $coord['idAsisten'] ?>" data-type="asisten"> 
                    <div class="exec-card">
                        <div class="exec-photo">
                            <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($coord['nama']) ?>" class="asisten-photo" loading="lazy">
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
                                    <span class="email"><?= htmlspecialchars($coord['email']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="exec-action" style="margin-top: 10px; padding-top: 10px;">

                                <!-- FIX LINK DETAIL -->
                                <a href="javascript:void(0)" data-id="<?= $coord['idAsisten'] ?>" data-type="asisten" class="btn-contact asisten-detail-link">
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
                <div class="no-data-message">
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
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="asisten" class="card-link asisten-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="asisten-photo" loading="lazy">
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
                                        <span class="email"><?= htmlspecialchars($row['email']) ?></span>
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
            <div class="section-label mt-40">
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
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="asisten" class="card-link asisten-detail-link">
                        <div class="staff-card">
                                <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="asisten-photo" loading="lazy">
                                <span class="badge-ca">CA</span>
                            </div>
                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                <span class="staff-role ca-role">Calon Asisten</span>
                                
                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-graduation-cap-line"></i> 
                                        <span><?= htmlspecialchars($row['jurusan'] ?? 'Teknik Informatika') ?></span>
                                    </div>

                                    <?php if (!empty($row['email'])) : ?>
                                    <div class="meta-item">
                                        <i class="ri-mail-line"></i> 
                                        <span class="email"><?= htmlspecialchars($row['email']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- NEW: Alumni Section -->
        <?php if (!empty($alumni_list)) : ?>
            <div class="section-label mt-40">
                <span>Alumni Asisten</span>
            </div>

            <div class="staff-grid">
                <?php foreach ($alumni_list as $row) : ?>
                    <?php 
                        $fotoName = $row['foto'] ?? '';
                        $namaEnc = urlencode($row['nama'] ?? '');
                        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eef2ff&color=1e293b&size=256&bold=true"; 

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
                    
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="alumni" class="card-link asisten-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama'] ?? '') ?>" class="asisten-photo" loading="lazy">
                                <span class="badge-alumni">Alumni</span>
                            </div>
                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama'] ?? '') ?></h3>
                                <span class="staff-role">Alumni Asisten</span>
                                
                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-graduation-cap-line"></i> 
                                        <span><?= htmlspecialchars($row['jurusan'] ?? 'Teknik Informatika') ?></span>
                                    </div>

                                    <?php if (!empty($row['angkatan'])) : ?>
                                    <div class="meta-item">
                                        <i class="ri-calendar-line"></i>
                                        <span><?= htmlspecialchars($row['angkatan']) ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($row['email'])) : ?>
                                    <div class="meta-item">
                                        <i class="ri-mail-line"></i> 
                                        <span class="email"><?= htmlspecialchars($row['email']) ?></span>
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
