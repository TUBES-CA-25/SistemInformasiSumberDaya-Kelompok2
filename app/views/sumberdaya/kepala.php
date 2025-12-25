<?php
/**
 * VIEW: KEPALA LAB & MANAJEMEN (DYNAMIC DB)
 * Mode kompatibel:
 * 1️⃣ Jika controller mengirim data → pakai $data['manajemen']
 * 2️⃣ Jika tidak → fallback query langsung via PDO (legacy mode)
 */


$pimpinan_list = [];
$laboran_list  = [];
$all_data      = [];


/**
 * ===========================
 * 1️⃣ Ambil data dari Controller (MVC)
 * ===========================
 */
if (!empty($data['manajemen'])) {

    $all_data = $data['manajemen'];


/**
 * ===========================
 * 2️⃣ Fallback — Mode Lama (query langsung)
 * ===========================
 */
} else {

    global $pdo;

    try {
        if ($pdo instanceof PDO) {
            $stmt = $pdo->query("SELECT * FROM manajemen ORDER BY idManajemen ASC");
            $all_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (Throwable $e) {
        echo "<div class='alert-error'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        $all_data = [];
    }
}


/**
 * ===========================
 * 3️⃣ Kelompokkan data
 * ===========================
 */
foreach ($all_data as $row) {

    $jabatan = $row['jabatan'] ?? '';

    // tampil besar (exec-card)
    if (stripos($jabatan, 'Kepala') !== false) {
        $pimpinan_list[] = $row;

    // tampil grid
    } else {
        $laboran_list[] = $row;
    }
}


/**
 * ===========================
 * 4️⃣ Helper Foto
 * ===========================
 */
function getFotoUrl($row)
{
    $fotoName = $row['foto'] ?? '';
    $namaEnc  = urlencode($row['nama']);

    // Default Avatar
    $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=512&bold=true";

    if (!$fotoName) return $imgUrl;

    if (strpos($fotoName, 'http') !== false)
        return $fotoName;

    if (file_exists(ROOT_PROJECT . '/public/images/manajemen/' . $fotoName))
        return ASSETS_URL . '/images/manajemen/' . $fotoName;

    if (file_exists(ROOT_PROJECT . '/public/images/asisten/' . $fotoName))
        return ASSETS_URL . '/images/asisten/' . $fotoName;

    return $imgUrl;
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
                <i class="ri-search-line"
                    style="position:absolute; right:20px; top:50%; transform:translateY(-50%); color:#94a3b8"></i>
            </div>
        </header>



        <!-- ========================= -->
        <!-- 🟦 KEPALA LAB / PIMPINAN -->
        <!-- ========================= -->
        <?php if (!empty($pimpinan_list)) : ?>
            
            <div class="section-label">Kepala Laboratorium</div>

            <?php foreach ($pimpinan_list as $row) : ?>
                <?php $imgUrl = getFotoUrl($row); ?>

                <a href="index.php?page=detail&id=<?= $row['idManajemen'] ?>&type=manajemen"
                   class="card-link" style="margin-bottom:50px">

                    <div class="exec-card">
                        <div class="exec-photo">
                            <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
                        </div>

                        <div class="exec-info">

                            <span class="exec-badge">Pimpinan</span>

                            <h3 class="staff-name" style="font-size:2rem; margin-bottom:5px">
                                <?= htmlspecialchars($row['nama']) ?>
                            </h3>

                            <p class="exec-role">
                                <?= htmlspecialchars($row['jabatan']) ?>
                            </p>

                            <p style="color:#64748b; margin-bottom:20px">
                                <?= !empty($row['nidn'])
                                    ? 'NIDN: ' . htmlspecialchars($row['nidn'])
                                    : 'Fakultas Ilmu Komputer UMI' ?>
                            </p>

                            <div class="exec-footer">
                                <div class="meta-item">
                                    <i class="ri-mail-line"></i>
                                    <span>
                                        <?= !empty($row['email'])
                                            ? htmlspecialchars($row['email'])
                                            : 'Hubungi Staff' ?>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            <?php endforeach; ?>
        <?php endif; ?>



        <!-- ========================= -->
        <!-- 🟨 LABORAN / STAFF -->
        <!-- ========================= -->
        <?php if (!empty($laboran_list)) : ?>

            <div class="section-label">Pranata Laboratorium & Staff</div>

            <div class="staff-grid">

                <?php foreach ($laboran_list as $row) : ?>
                    <?php $imgUrl = getFotoUrl($row); ?>

                    <a href="index.php?page=detail&id=<?= $row['idManajemen'] ?>&type=manajemen"
                       class="card-link">

                        <div class="staff-card">

                            <div class="staff-photo-box">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy">
                            </div>

                            <div class="staff-content">

                                <h3 class="staff-name"><?= htmlspecialchars($row['nama']) ?></h3>

                                <span class="staff-role">
                                    <?= htmlspecialchars($row['jabatan']) ?>
                                </span>

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



        <!-- ========================= -->
        <!-- ⛔ KOSONG -->
        <!-- ========================= -->
        <?php if (empty($pimpinan_list) && empty($laboran_list)) : ?>
            <div class="empty-state-wrapper">
                <div class="empty-icon"><i class="ri-folder-unknow-line"></i></div>
                <p>Data manajemen belum tersedia saat ini.</p>
            </div>
        <?php endif; ?>

    </div>
</section>


<script src="<?= ASSETS_URL ?>/js/kepala.js"></script>
