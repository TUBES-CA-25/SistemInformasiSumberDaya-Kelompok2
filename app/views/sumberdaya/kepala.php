<?php

global $pdo; 

$pimpinan_list = [];
$laboran_list = [];

try {
    $stmt = $pdo->query("SELECT * FROM manajemen ORDER BY idManajemen ASC");
    $all_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($all_data as $row) {
        $jabatan = $row['jabatan'] ?? '';
        if (stripos($jabatan, 'Kepala') !== false) {
            $pimpinan_list[] = $row;
        } else {
            $laboran_list[] = $row;
        }
    }
} catch (PDOException $e) {
    echo "<div class='alert-error'>Error Database: " . $e->getMessage() . "</div>";
}
?>

<section class="sumberdaya-section fade-up">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Manajemen & Struktural <?= date('Y') ?></span>
            <h1>Struktur Pimpinan</h1>
            <p>Pimpinan Laboratorium dan Staff Administrasi Fakultas Ilmu Komputer</p>

            <div class="search-container">
                <input type="text" id="searchStaff" placeholder="Cari nama atau jabatan..." class="search-input">
                <div class="search-icon-box">
                    <i class="ri-search-line"></i>
                </div>
            </div>
        </header>

        <?php if (!empty($pimpinan_list)) : ?>
            <span class="section-label">Kepala Laboratorium</span>

            <div class="staff-grid">
                <?php foreach ($pimpinan_list as $row) : ?>
                    <?php renderStaffCard($row); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($laboran_list)) : ?>
            <span class="section-label">Pranata Laboratorium & Staff</span>
            
            <div class="staff-grid">
                <?php foreach ($laboran_list as $row) : ?>
                    <?php renderStaffCard($row); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($pimpinan_list) && empty($laboran_list)) : ?>
            <div class="empty-state-wrapper">
                <p>Data manajemen belum tersedia.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/kepala.js"></script>

<?php
/**
 * Fungsi Helper Render Kartu
 */
function renderStaffCard($row) {
    $fotoName = $row['foto'] ?? '';
    $imgUrl = ASSETS_URL . '/images/default-avatar.png'; 

    if (!empty($fotoName)) {
        if (strpos($fotoName, 'http') !== false) {
            $imgUrl = $fotoName; 
        } elseif (file_exists(ROOT_PROJECT . '/public/images/manajemen/' . $fotoName)) {
            $imgUrl = ASSETS_URL . '/images/manajemen/' . $fotoName;
        } elseif (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
            $imgUrl = ASSETS_URL . '/images/asisten/' . $fotoName;
        }
    }
    
    $inisial = strtoupper(substr($row['nama'], 0, 2));
    ?>

    <a href="index.php?page=detail&id=<?= $row['idManajemen'] ?>&type=manajemen" class="card-link">
        <div class="staff-card">
            <div class="staff-photo-box">
                <div class="staff-placeholder"><?= $inisial ?></div>
                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy" onerror="this.style.display='none'"> 
            </div>

            <div class="staff-content">
                <div class="staff-name"><?= htmlspecialchars($row['nama']) ?></div>
                <span class="staff-role"><?= htmlspecialchars($row['jabatan']) ?></span>
                
                <div class="staff-footer">
                    <div class="meta-item">
                        <i class="ri-mail-line"></i> Hubungi Staff
                    </div>
                    <div class="meta-item">
                        <i class="ri-building-4-line"></i> Fikom UMI
                    </div>
                </div>
            </div>
        </div>
    </a>
    <?php
}
?>