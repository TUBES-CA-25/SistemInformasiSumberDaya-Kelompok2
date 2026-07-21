<?php
/**
 * VIEW: KEPALA LAB & MANAJEMEN (MVC Clean Version)
 * Data dikirim dari ManajemenController::index()
 */

$pimpinan_list = $data['pimpinan'] ?? [];
$laboran_list  = $data['laboran'] ?? [];
?>

<section class="sumberdaya-section fade-up">
    <div class="container">

        <header class="page-header">
            <span class="header-badge">Manajemen & Struktural <?= date('Y') ?></span>
            <h1>Struktur Pimpinan</h1>
            <p>Pimpinan Laboratorium dan Staff Administrasi Fakultas Ilmu Komputer</p>

            <div class="search-container">
                <input type="text" id="searchStaff" placeholder="Cari nama atau jabatan..." class="search-input">
                <i class="ri-search-line" style="position:absolute; right:20px; top:50%; transform:translateY(-50%); color:#94a3b8"></i>
            </div>
        </header>

        <?php if (!empty($pimpinan_list)) : ?>
            <div class="section-label">Kepala Laboratorium</div>
            
            <div class="pimpinan-wrapper">
                <?php foreach ($pimpinan_list as $row) : ?>
                    <a href="javascript:void(0)" data-id="<?= $row['idManajemen'] ?>" data-type="manajemen" class="card-link staff-detail-link">
                        
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="<?= Helper::objectPosStyle($row) ?>" loading="lazy">
                            </div>

                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                
                                <?php if (!empty($row['nidn']) && $row['nidn'] !== '-') : ?>
                                    <span class="staff-nidn" style="display: block; font-size: 0.75rem; color: #64748b; margin-top: -2px; margin-bottom: 4px; font-weight: 500;">
                                        NIDN: <?= htmlspecialchars($row['nidn']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span class="staff-role"><?= htmlspecialchars($row['jabatan']) ?></span>

                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-building-4-line"></i> Fikom UMI
                                    </div>
                                    <?php if (!empty($row['email'])) : ?>
                                        <div class="meta-item">
                                            <i class="ri-mail-line"></i>
                                            <?= htmlspecialchars($row['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($laboran_list)) : ?>
            <div class="section-label">Pranata Laboratorium & Staff</div>
            
            <div class="pimpinan-wrapper">
                <?php foreach ($laboran_list as $row) : ?>
                    <a href="javascript:void(0)" data-id="<?= $row['idManajemen'] ?>" data-type="manajemen" class="card-link staff-detail-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="<?= Helper::objectPosStyle($row) ?>" loading="lazy">
                            </div>

                            <div class="staff-content">
                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>
                                
                                <?php if (!empty($row['nidn']) && $row['nidn'] !== '-') : ?>
                                    <span class="staff-nidn" style="display: block; font-size: 0.75rem; color: #64748b; margin-top: -2px; margin-bottom: 4px; font-weight: 500;">
                                        NIDN: <?= htmlspecialchars($row['nidn']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span class="staff-role"><?= htmlspecialchars($row['jabatan']) ?></span>

                                <div class="staff-footer">
                                    <div class="meta-item">
                                        <i class="ri-building-4-line"></i> Fikom UMI
                                    </div>
                                    <?php if (!empty($row['email'])) : ?>
                                        <div class="meta-item">
                                            <i class="ri-mail-line"></i>
                                            <?= htmlspecialchars($row['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($pimpinan_list) && empty($laboran_list)) : ?>
            <div class="empty-state-wrapper">
                <div class="empty-icon"><i class="ri-folder-unknow-line"></i></div>
                <h3 class="empty-title">Data Kosong</h3>
                <p>Data manajemen belum tersedia saat ini.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- Modal Detail Profil Pimpinan -->
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
<?php $kepalaJsPath = __DIR__ . '/../../../public/js/kepala.js'; ?>
<script src="<?= ASSETS_URL ?>/js/kepala.js?v=<?= file_exists($kepalaJsPath) ? filemtime($kepalaJsPath) : time() ?>"></script>