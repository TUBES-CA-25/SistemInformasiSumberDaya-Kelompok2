<?php

global $pdo; 

$pimpinan_list = [];
$laboran_list = [];

try {
    $stmt = $pdo->query("SELECT * FROM manajemen ORDER BY idManajemen ASC");
    $all_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($all_data as $row) {
        $jabatan = $row['jabatan'] ?? '';
        // Deteksi Kepala Lab / Pimpinan
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
 * Fungsi Helper Render Kartu (UPDATED: UI Avatars Fallback)
 */
function renderStaffCard($row) {
    $fotoName = $row['foto'] ?? '';
    $namaEnc = urlencode($row['nama']);
    
    // 1. Default ke UI Avatars (Inisial Nama) agar tidak pecah/tanda tanya
    $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256";

    // 2. Cek apakah ada file foto fisik
    if (!empty($fotoName)) {
        if (strpos($fotoName, 'http') !== false) {
            $imgUrl = $fotoName; 
        } elseif (file_exists(ROOT_PROJECT . '/public/images/manajemen/' . $fotoName)) {
            $imgUrl = ASSETS_URL . '/images/manajemen/' . $fotoName;
        } elseif (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName)) {
            // Fallback cek di folder asisten jika di folder manajemen tidak ada
            $imgUrl = ASSETS_URL . '/images/asisten/' . $fotoName;
        }
    }
    ?>

    <a href="index.php?page=detail&id=<?= $row['idManajemen'] ?>&type=manajemen" class="card-link">
        <div class="staff-card">
            <div class="staff-photo-box">
                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>"> 
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