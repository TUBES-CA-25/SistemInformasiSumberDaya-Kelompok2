<?php 
// Cek data dari Controller (Support data raw atau grouped)
$jadwal = $data['jadwal'] ?? [];
$grouped = $data['jadwal_grouped'] ?? [];

// Jika controller belum grouping, kita group di sini (Fallback)
if (empty($grouped) && !empty($jadwal)) {
    foreach($jadwal as $row) {
        $grouped[$row['ruangan']][] = $row;
    }
    ksort($grouped);
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<style>
    .hidden {
        display: none !important;
    }
    .upk-search-wrapper input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
    }
</style>

<section class="praktikum-section">
    <div class="container">
        
        <?php if (isset($data['is_aktif']) && !$data['is_aktif']): ?>
            <div class="empty-schedule" style="padding: 80px 20px; text-align: center; max-width: 600px; margin: 40px auto; background: white; border-radius: 20px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="width: 80px; height: 80px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-lock" style="font-size: 2.2rem; color: #3b82f6;"></i>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 12px;">Jadwal UPK Sedang Nonaktif</h2>
                <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px;">
                    Saat ini jadwal Ujian Praktikum Komputer (UPK) belum dibuka atau sedang dinonaktifkan oleh administrator laboratorium. Silakan periksa kembali saat periode ujian dimulai atau hubungi koordinator lab.
                </p>
                <a href="<?= PUBLIC_URL ?>/jadwal" style="display: inline-flex; align-items: center; gap: 8px; background: #2563eb; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                    <i class="fas fa-calendar-alt"></i> Lihat Jadwal Praktikum
                </a>
            </div>
        <?php else: ?>
            <header class="page-header">
                <span class="header-badge">Jadwal Ujian Praktikum</span>
                
                <h1 id="upk-header-day">Memuat Hari...</h1>
                
                <p>Informasi real-time lokasi laboratorium, waktu ujian, dan dosen pengampu mata kuliah.</p>
                
                <div id="live-clock" class="live-clock-badge">
                    00:00:00
                </div>

                <div class="filter-controls" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-top: 25px;">
                    <!-- Prodi Filter -->
                    <div class="day-selector-wrapper" style="margin-top: 0;">
                        <select id="upk-prodi-select" class="custom-select" onchange="filterJadwalUpk()" style="min-width: 140px;">
                            <option value="">Semua Prodi</option>
                            <option value="TI">Teknik Informatika (TI)</option>
                            <option value="SI">Sistem Informasi (SI)</option>
                        </select>
                        <i class="fas fa-chevron-down select-icon"></i>
                    </div>

                    <!-- Lab/Ruangan -->
                    <div class="day-selector-wrapper" style="margin-top: 0;">
                        <select id="upk-lab-select" class="custom-select" onchange="filterJadwalUpk()" style="min-width: 160px;">
                            <option value="">Semua Lab</option>
                            <?php foreach(array_keys($grouped) as $ruangan): ?>
                                <option value="<?= htmlspecialchars($ruangan) ?>"><?= htmlspecialchars($ruangan) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down select-icon"></i>
                    </div>

                    <!-- Pencarian -->
                    <div class="upk-search-wrapper" style="position: relative; display: inline-block;">
                        <input type="text" id="upk-search-input" onkeyup="filterJadwalUpk()" oninput="filterJadwalUpk()" placeholder="Cari matakuliah, dosen..." 
                            style="padding: 12px 20px 12px 45px; border-radius: 12px; border: 2px solid #e2e8f0; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600; outline: none; transition: all 0.3s; background: white; color: #0f172a; min-width: 250px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                        <i class="fas fa-search" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #64748b;"></i>
                    </div>
                </div>
            </header>

            <div id="lab-tables-container">

                <?php if (empty($grouped) && empty($jadwal)): ?>
                    <div class="empty-schedule">
                        <i class="far fa-calendar-times"></i>
                        <h3>Belum Ada Jadwal</h3>
                        <p>Jadwal UPK belum dirilis oleh admin.</p>
                    </div>
                <?php else: ?>
                    
                    <?php foreach($grouped as $ruangan => $items): ?>
                    <div class="schedule-wrapper" data-ruangan="<?= htmlspecialchars($ruangan) ?>">
                        <div class="lab-header">
                            <div class="lab-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <h2 class="lab-title"><?= htmlspecialchars($ruangan) ?></h2>
                        </div>
                        
                        <?php 
                            $hasAnyFreq = false;
                            foreach ($items as $it) {
                                if (!empty($it['frekuensi']) && trim($it['frekuensi']) !== '-' && trim($it['frekuensi']) !== '0') {
                                    $hasAnyFreq = true;
                                    break;
                                }
                            }
                        ?>
                        <div class="table-responsive">
                            <table class="table-schedule">
                                <thead>
                                    <tr>
                                        <th>Waktu & Tanggal</th>
                                        <th>Mata Kuliah</th>
                                        <th><?= $hasAnyFreq ? 'Kelas / Freq' : 'Kelas' ?></th>
                                        <th>Dosen Pengampu</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($items as $item): 
                                        $tgl = date('d M Y', strtotime($item['tanggal']));
                                        $isToday = ($item['tanggal'] == date('Y-m-d'));
                                    ?>
                                    <tr data-ruangan="<?= htmlspecialchars($ruangan) ?>" data-matkul="<?= htmlspecialchars($item['mata_kuliah']) ?>" data-dosen="<?= htmlspecialchars($item['dosen']) ?>" data-prodi="<?= htmlspecialchars($item['prodi']) ?>" data-frekuensi="<?= htmlspecialchars($item['frekuensi']) ?>" data-kelas="<?= htmlspecialchars($item['kelas']) ?>">
                                        <td class="time-cell">
                                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                                <div>
                                                    <span class="time-range"><?= htmlspecialchars($item['jam']) ?></span>
                                                    <span class="date-badge"><i class="far fa-calendar"></i> <?= $tgl ?></span>
                                                </div>
                                                <span class="mobile-status-badge <?= $isToday ? 'badge-ongoing' : ($item['tanggal'] < date('Y-m-d') ? 'badge-finished' : 'badge-upcoming') ?>">
                                                    <?= $isToday ? 'HARI INI' : ($item['tanggal'] < date('Y-m-d') ? 'SELESAI' : 'AKAN DATANG') ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="schedule-matkul">
                                                <?= htmlspecialchars($item['mata_kuliah']) ?>
                                                <span class="badge-prodi"><?= htmlspecialchars($item['prodi']) ?></span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="schedule-kelas">Kelas <?= htmlspecialchars($item['kelas']) ?></span>
                                            <?php if (!empty($item['frekuensi']) && trim($item['frekuensi']) !== '-'): ?>
                                                <span class="schedule-freq"><?= htmlspecialchars($item['frekuensi']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="dosen-info">
                                                <i class="fas fa-user-tie"></i>
                                                <span class="dosen-name"><?= htmlspecialchars($item['dosen']) ?></span>
                                            </div>
                                        </td>
                                        <td class="desktop-status-cell">
                                            <?php if($isToday): ?>
                                                <span class="status-label badge-ongoing">HARI INI</span>
                                            <?php elseif($item['tanggal'] < date('Y-m-d')): ?>
                                                <span class="status-label badge-finished">SELESAI</span>
                                            <?php else: ?>
                                                <span class="status-label badge-upcoming">AKAN DATANG</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div id="upk-empty-message" class="empty-schedule hidden">
                        <i class="far fa-calendar-times"></i>
                        <h3>Tidak Ada Hasil</h3>
                        <p>Tidak ada jadwal UPK yang cocok dengan filter pencarian Anda.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="<?= PUBLIC_URL ?>/js/praktikum.js?v=<?= time() ?>" defer></script>