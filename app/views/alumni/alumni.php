<section class="sumberdaya-section fade-up">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Jejak Karir & Kontribusi</span>
            <h1>Alumni Laboratorium</h1>
            <p>Daftar lulusan yang telah berkontribusi dan kini berkarya di berbagai industri.</p>

            <div class="search-container">
                <input type="text" id="searchAlumni" placeholder="Cari nama atau angkatan..." class="search-input" aria-label="Cari alumni">
                <i class="ri-search-line search-icon-compact"></i>
            </div>
        </header>

        <div class="search-empty-state" id="searchEmptyState">
            <i class="ri-search-eye-line"></i>
            <p>Tidak ada alumni yang cocok dengan pencarian Anda.</p>
        </div>

        <?php if (!empty($alumni_by_year)) : ?>
            
            <?php foreach ($alumni_by_year as $year => $alumni_list) : ?>
                <div class="alumni-group">
                    
                    <div class="section-label">
                        <span>Angkatan <?= htmlspecialchars($year) ?></span>
                    </div>
                    
                    <div class="staff-grid">
                        <?php foreach ($alumni_list as $row) : ?>
                            <?php 
                                $idAlumni = $row['idAlumni'] ?? $row['id'] ?? 0;
                                $fotoName = $row['foto'] ?? '';
                                $namaEnc  = urlencode($row['nama'] ?? 'Alumni');

                                $imgUrl = Helper::processPhotoUrl($fotoName, $row['nama'] ?? 'Alumni');
                                $divisiRaw = $row['divisi'] ?? ($row['jurusan'] ?? '');
                                $divisiClean = trim(str_ireplace(['alumni asisten', 'asisten lab', 'asisten', 'alumni'], '', $divisiRaw));
                                $divisi = !empty($divisiClean) ? $divisiClean : ($row['jurusan'] ?? 'Teknik Informatika');
                            ?>

                            <a href="javascript:void(0)" data-id="<?= $idAlumni ?>" data-type="alumni" class="card-link asisten-detail-link">
                                <div class="staff-card">
                                    <div class="staff-photo-box">
                                        <img src="<?= $imgUrl ?>"
                                             alt="<?= htmlspecialchars($row['nama'] ?? '') ?>"
                                             class="asisten-photo"
                                             style="<?= Helper::objectPosStyle($row) ?>"
                                             loading="lazy">
                                        <div class="photo-scrim"></div>
                                        <span class="view-hint"><i class="ri-arrow-right-up-line"></i></span>
                                        <div class="staff-overlay-info">
                                            <h3 class="staff-name"><?= htmlspecialchars($row['nama'] ?? '') ?></h3>
                                        </div>
                                    </div>
                                    
                                    <div class="staff-content">
                                        <div class="meta-item">
                                            <i class="ri-graduation-cap-line"></i>
                                            <span><?= htmlspecialchars($divisi) ?></span>
                                        </div>

                                        <?php if (!empty($row['angkatan'])) : ?>
                                        <div class="meta-item">
                                            <i class="ri-calendar-line"></i>
                                            <span>Angkatan <?= htmlspecialchars($row['angkatan']) ?></span>
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
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else : ?>
            
            <div class="empty-state-wrapper">
                <div class="empty-icon">
                    <i class="ri-user-search-line"></i>
                </div>
                <h2 class="empty-title">Belum Ada Data</h2>
                <p class="empty-desc">Data alumni belum tersedia di database.</p>
            </div>

        <?php endif; ?>

    </div>
</section>

<!-- Modal Detail Profil Alumni -->
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

<?php $asistenJsPath = ROOT_PROJECT . '/public/js/asisten.js'; ?>
<script src="<?= ASSETS_URL ?>/js/asisten.js?v=<?= file_exists($asistenJsPath) ? filemtime($asistenJsPath) : time() ?>"></script>
<script src="<?= ASSETS_URL ?>/js/alumni.js"></script>