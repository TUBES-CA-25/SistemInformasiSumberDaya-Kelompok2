<?php
// Ambil data yang dikirim dari AsistenController
$koordinator_list = $data['koordinator'] ?? [];
$asisten_list     = $data['asisten'] ?? [];
$ca_list          = $data['ca'] ?? [];
$alumni_list      = $data['alumni'] ?? [];
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
                <div class="card-link exec-margin" data-id="<?= $coord['idAsisten'] ?>" data-type="asisten"> 
                    <div class="exec-card">
                        <div class="exec-photo">
                            <img src="<?= $coord['foto_url'] ?>" alt="<?= htmlspecialchars($coord['nama']) ?>" class="asisten-photo" loading="lazy">
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
                                <a href="javascript:void(0)" data-id="<?= $coord['idAsisten'] ?>" data-type="asisten" class="btn-contact asisten-detail-link">
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="section-label mt-40">
            <span>Asisten Praktikum</span>
        </div>

        <div class="staff-grid">
            <?php if (empty($asisten_list)) : ?>
                <div class="no-data-message">
                    <p>Belum ada data asisten praktikum.</p>
                </div>
            <?php else : ?>
                <?php foreach ($asisten_list as $row) : ?>
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="asisten" class="card-link asisten-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="asisten-photo" loading="lazy">
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
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="asisten" class="card-link asisten-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="asisten-photo" loading="lazy">
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

        <?php if (!empty($alumni_list)) : ?>
            <div class="section-label mt-40">
                <span>Alumni Asisten</span>
            </div>

            <div class="staff-grid">
                <?php foreach ($alumni_list as $row) : ?>
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="alumni" class="card-link asisten-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama'] ?? '') ?>" class="asisten-photo" loading="lazy">
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