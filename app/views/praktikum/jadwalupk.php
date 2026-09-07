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

// Helper nama hari & bulan bahasa Indonesia
$hariIndo = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];
$bulanIndoPendek = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
    7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
];
$bulanIndoPanjang = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$now = time();
$hariIniStr = ($hariIndo[date('l', $now)] ?? date('l', $now)) . ', ' . date('d', $now) . ' ' . ($bulanIndoPanjang[(int)date('m', $now)] ?? date('F', $now)) . ' ' . date('Y', $now);
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

    /* Live Header Status Bar */
    .upk-live-bar {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 8px 20px;
        border-radius: 9999px;
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.05);
        margin-top: 18px;
        font-size: 0.88rem;
    }
    .upk-live-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #334155;
    }
    .upk-live-item.clock-highlight {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 800;
        color: #2563eb;
        background: #eff6ff;
        padding: 3px 12px;
        border-radius: 9999px;
        border: 1px solid #dbeafe;
    }
    .upk-tz-label {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 700;
    }
    .upk-live-divider {
        color: #cbd5e1;
        font-size: 0.8rem;
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: upkPulse 2s infinite;
    }
    @keyframes upkPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Tabel Kolom Waktu & Tanggal */
    .upk-datetime-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 10px;
    }
    .upk-datetime-wrapper {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-start;
    }
    .upk-date-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.83rem;
        color: #1e293b;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .upk-date-pill:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }
    .upk-date-icon {
        color: #2563eb;
        font-size: 0.82rem;
    }
    .upk-day-tag {
        font-weight: 800;
        color: #1d4ed8;
        background: #eff6ff;
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        letter-spacing: 0.01em;
        border: 1px solid #dbeafe;
    }
    .upk-date-text {
        font-weight: 700;
        color: #334155;
    }
    .upk-time-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 4px 10px;
        border-radius: 8px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.82rem;
        font-weight: 700;
        color: #15803d;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .upk-time-pill:hover {
        border-color: #86efac;
        background: #dcfce7;
    }
    .upk-time-icon {
        color: #16a34a;
        font-size: 0.8rem;
    }
    .upk-time-text {
        letter-spacing: 0.02em;
    }
    .upk-wita-tag {
        font-size: 0.65rem;
        font-weight: 800;
        color: #166534;
        background: #dcfce7;
        padding: 1px 4px;
        border-radius: 3px;
        border: 1px solid #bbf7d0;
    }

    /* Night Mode Support */
    body.night-mode .upk-live-bar {
        background: #1e293b;
        border-color: #334155;
    }
    body.night-mode .upk-live-item {
        color: #cbd5e1;
    }
    body.night-mode .upk-live-item.clock-highlight {
        background: #1e3a8a;
        border-color: #2563eb;
        color: #93c5fd;
    }
    body.night-mode .upk-tz-label {
        color: #93c5fd;
    }
    body.night-mode .upk-date-pill {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    body.night-mode .upk-date-pill:hover {
        background: #334155;
    }
    body.night-mode .upk-day-tag {
        background: #1e3a8a;
        color: #93c5fd;
        border-color: #1d4ed8;
    }
    body.night-mode .upk-date-text {
        color: #e2e8f0;
    }
    body.night-mode .upk-time-pill {
        background: #064e3b;
        border-color: #065f46;
        color: #6ee7b7;
    }
    body.night-mode .upk-time-pill:hover {
        background: #047857;
    }
    body.night-mode .upk-time-icon {
        color: #34d399;
    }
    body.night-mode .upk-wita-tag {
        background: #022c22;
        border-color: #065f46;
        color: #a7f3d0;
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
                <span class="header-badge"><i class="fas fa-calendar-check mr-1"></i> Jadwal Ujian Praktikum</span>
                
                <h1 class="page-title">Jadwal Ujian Praktikum Komputer (UPK)</h1>
                
                <p>Informasi real-time lokasi laboratorium, waktu ujian, dan dosen pengampu mata kuliah.</p>
                
                <!-- Live Header Status Bar -->
                <div class="upk-live-bar">
                    <div class="upk-live-item">
                        <i class="far fa-calendar-alt" style="color: #2563eb;"></i>
                        <span id="upk-header-day"><?= $hariIniStr ?></span>
                    </div>
                    <span class="upk-live-divider">•</span>
                    <div class="upk-live-item clock-highlight">
                        <i class="far fa-clock"></i>
                        <span id="live-clock"><?= date('H:i:s') ?></span>
                        <span class="upk-tz-label">WITA</span>
                    </div>
                    <span class="upk-live-divider">•</span>
                    <div class="upk-live-item" style="color: #16a34a;">
                        <span class="pulse-dot"></span>
                        <span style="font-size: 0.8rem; font-weight: 700;">Live Update</span>
                    </div>
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
                                        <th style="min-width: 220px;">Waktu & Tanggal</th>
                                        <th>Mata Kuliah</th>
                                        <th><?= $hasAnyFreq ? 'Kelas / Freq' : 'Kelas' ?></th>
                                        <th>Dosen Pengampu</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($items as $item): 
                                        $tglRaw = $item['tanggal'] ?? '';
                                        $timestamp = !empty($tglRaw) ? strtotime($tglRaw) : time();
                                        $namaHari = $hariIndo[date('l', $timestamp)] ?? date('l', $timestamp);
                                        $d = date('d', $timestamp);
                                        $m = (int)date('m', $timestamp);
                                        $bulanStr = $bulanIndoPendek[$m] ?? date('M', $timestamp);
                                        $y = date('Y', $timestamp);
                                        $tglFormatted = "$d $bulanStr $y";
                                        $isToday = ($tglRaw == date('Y-m-d'));

                                        $statusText = 'AKAN DATANG';
                                        $statusClass = 'badge-upcoming';
                                        if ($isToday) {
                                            $statusText = 'HARI INI';
                                            $statusClass = 'badge-ongoing';
                                        } elseif (!empty($tglRaw) && $tglRaw < date('Y-m-d')) {
                                            $statusText = 'SELESAI';
                                            $statusClass = 'badge-finished';
                                        }
                                    ?>
                                    <tr data-ruangan="<?= htmlspecialchars($ruangan) ?>" data-matkul="<?= htmlspecialchars($item['mata_kuliah']) ?>" data-dosen="<?= htmlspecialchars($item['dosen']) ?>" data-prodi="<?= htmlspecialchars($item['prodi']) ?>" data-frekuensi="<?= htmlspecialchars($item['frekuensi']) ?>" data-kelas="<?= htmlspecialchars($item['kelas']) ?>">
                                        <td class="time-cell" style="vertical-align: middle;">
                                            <div class="upk-datetime-container">
                                                <div class="upk-datetime-wrapper">
                                                    <!-- Tanggal -->
                                                    <div class="upk-date-pill">
                                                        <i class="far fa-calendar-alt upk-date-icon"></i>
                                                        <span class="upk-day-tag"><?= $namaHari ?></span>
                                                        <span class="upk-date-text"><?= $tglFormatted ?></span>
                                                    </div>
                                                    <!-- Jam -->
                                                    <div class="upk-time-pill">
                                                        <i class="far fa-clock upk-time-icon"></i>
                                                        <span class="upk-time-text"><?= htmlspecialchars($item['jam']) ?></span>
                                                        <span class="upk-wita-tag">WITA</span>
                                                    </div>
                                                </div>
                                                <span class="mobile-status-badge <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="schedule-matkul">
                                                <?= htmlspecialchars($item['mata_kuliah']) ?>
                                                <span class="badge-prodi"><?= htmlspecialchars($item['prodi']) ?></span>
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <span class="schedule-kelas">Kelas <?= htmlspecialchars($item['kelas']) ?></span>
                                            <?php if (!empty($item['frekuensi']) && trim($item['frekuensi']) !== '-'): ?>
                                                <span class="schedule-freq">/ <?= htmlspecialchars($item['frekuensi']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="dosen-info">
                                                <i class="fas fa-user-tie"></i>
                                                <span class="dosen-name"><?= htmlspecialchars($item['dosen']) ?></span>
                                            </div>
                                        </td>
                                        <td class="desktop-status-cell text-center">
                                            <span class="status-label <?= $statusClass ?>"><?= $statusText ?></span>
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