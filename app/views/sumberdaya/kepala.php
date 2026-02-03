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
                    <a href="<?= PUBLIC_URL ?>/kepala/<?= $row['idManajemen'] ?>" class="card-link">
                        
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
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
            
            <div class="pimpinan-wrapper"> <?php foreach ($laboran_list as $row) : ?>
                    <a href="<?= PUBLIC_URL ?>/kepala/<?= $row['idManajemen'] ?>" class="card-link">
                        <div class="staff-card">
                            <div class="staff-photo-box">
                                <img src="<?= $row['foto_url'] ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
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

<script src="<?= ASSETS_URL ?>/js/kepala.js"></script>