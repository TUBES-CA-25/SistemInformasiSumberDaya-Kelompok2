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
                <input type="text" id="searchAsisten" placeholder="Cari asisten..." class="search-input" aria-label="Cari asisten">
                <i class="ri-search-line search-icon-compact"></i>
            </div>
        </div>

        <div class="search-empty-state" id="searchEmptyState">
            <i class="ri-search-eye-line"></i>
            <p>Tidak ada asisten yang cocok dengan pencarian Anda.</p>
        </div>

        <?php if (!empty($koordinator_list)) : ?>
            <div class="section-label">
                <span>Koordinator Laboratorium</span>
            </div>

            <?php foreach ($koordinator_list as $coord) : ?>
                <div class="card-link exec-margin" data-id="<?= $coord['idAsisten'] ?>" data-type="asisten"> 
                    <div class="exec-card">
                        <div class="exec-photo">
                            <img src="<?= $coord['foto_url'] ?>" alt="<?= htmlspecialchars($coord['nama']) ?>" class="asisten-photo" style="<?= Helper::objectPosStyle($coord) ?>" loading="lazy">
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
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="asisten" class="card-link asisten-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="asisten-photo" style="<?= Helper::objectPosStyle($row) ?>" loading="lazy">
                                <div class="photo-scrim"></div>
                                <span class="view-hint"><i class="ri-arrow-right-up-line"></i></span>
                                <div class="staff-overlay-info">
                                    <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                </div>
                            </div>
                            <div class="staff-content">
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
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="asisten-photo" style="<?= Helper::objectPosStyle($row) ?>" loading="lazy">
                                <div class="photo-scrim"></div>
                                <span class="view-hint"><i class="ri-arrow-right-up-line"></i></span>
                                <div class="staff-overlay-info">
                                    <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                </div>
                            </div>
                            <div class="staff-content">
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
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($alumni_list)) : ?>
            <div class="section-label mt-40">
                <span>Alumni</span>
            </div>

            <div class="staff-grid">
                <?php foreach ($alumni_list as $row) : ?>
                    <a href="javascript:void(0)" data-id="<?= $row['idAsisten'] ?>" data-type="alumni" class="card-link asisten-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama'] ?? '') ?>" class="asisten-photo" style="<?= Helper::objectPosStyle($row) ?>" loading="lazy">
                                <div class="photo-scrim"></div>
                                <span class="view-hint"><i class="ri-arrow-right-up-line"></i></span>
                                <div class="staff-overlay-info">
                                    <h3 class="staff-name"><?= htmlspecialchars($row['nama'] ?? '') ?></h3>
                                </div>
                            </div>
                            <div class="staff-content">
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
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- Modal Detail Profil Asisten -->
<div id="profileModal" class="profile-modal-overlay">
    <div class="profile-modal-container">
        <button class="profile-modal-close" id="closeProfileModal" aria-label="Tutup Detail">
            <i class="ri-close-line"></i>
        </button>
        <div class="profile-modal-content">
            <!-- Loading State -->
            <div id="modalLoading" class="modal-loading-state">
                <div class="modal-spinner"></div>
                <p>Memuat profil...</p>
            </div>
            
            <!-- Error State -->
            <div id="modalError" class="modal-error-state" style="display: none;">
                <i class="ri-error-warning-line"></i>
                <p>Gagal memuat profil. Silakan coba lagi.</p>
            </div>
            
            <!-- Profile Data (Dynamic) -->
            <div id="modalBody" class="profile-modal-body" style="display: none;">
                <!-- Header / Split Column -->
                <div class="modal-profile-header-wrapper">
                    <div class="modal-profile-image">
                        <img id="modalImg" src="" alt="">
                    </div>
                    <div class="modal-profile-info-header">
                        <span id="modalCategory" class="category-badge"></span>
                        <h2 id="modalName" class="profile-name"></h2>
                        <span id="modalRole" class="profile-role"></span>
                        
                        <div class="specialization-box">
                            <div class="member-specialization" id="modalSubInfo">
                                <i class="ri-graduation-cap-line"></i> 
                                <span></span>
                            </div>
                            <div class="member-specialization" id="modalEmailBox" style="margin-top: 8px; color: #64748b; font-weight: 500;">
                                <i class="ri-mail-line"></i> 
                                <span id="modalEmail"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Details / Full Width below photo -->
                <div class="modal-profile-details">
                    <h4 class="section-title">Tentang</h4>
                    <p id="modalBio" class="profile-bio"></p>

                    <div id="modalSkillsSection">
                        <h4 class="section-title mt-30">Kompetensi & Keahlian</h4>
                        <div id="modalSkillsContainer" class="skills-container"></div>
                    </div>

                    <div class="contact-wrapper">
                        <a id="modalMailBtn" href="" class="btn-contact">
                            <i class="ri-mail-send-line"></i> Kirim Email
                        </a>
                        <button id="modalMailDisabled" class="btn-disabled" style="display: none;" disabled>
                            <i class="ri-mail-forbid-line"></i> Email Tidak Tersedia
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $asistenJsPath = __DIR__ . '/../../../public/js/asisten.js'; ?>
<script src="<?= ASSETS_URL ?>/js/asisten.js?v=<?= file_exists($asistenJsPath) ? filemtime($asistenJsPath) : time() ?>" defer></script>