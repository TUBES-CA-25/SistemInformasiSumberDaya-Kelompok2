<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/praktikum.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<section class="praktikum-section">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Jadwal Ujian Praktikum</span>
            <h1 id="header-day">Memuat Hari...</h1>
            <p>Informasi real-time lokasi laboratorium, waktu ujian, dan dosen pengampu mata kuliah.</p>
            
            <div id="live-clock" class="live-clock-badge">
                00:00:00
            </div>
        </header>

        <div id="lab-tables-container">
            <?php if (empty($data['jadwal'])): ?>
                <div class="empty-schedule">
                    <i class="far fa-calendar-times"></i>
                    <h3>Belum Ada Jadwal</h3>
                    <p>Jadwal UPK belum dirilis oleh admin.</p>
                </div>
            <?php else: 
                // Grouping Data
                $grouped = [];
                foreach($data['jadwal'] as $row) {
                    $grouped[$row['ruangan']][] = $row;
                }
                ksort($grouped);

                foreach($grouped as $ruangan => $items):
            ?>
                <div class="schedule-wrapper">
                    <div class="lab-header">
                        <div class="lab-icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <h2 class="lab-title"><?= $ruangan ?></h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-schedule">
                            <thead>
                                <tr>
                                    <th>Waktu & Tanggal</th>
                                    <th>Mata Kuliah</th>
                                    <th>Kelas / Freq</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($items as $item): 
                                    $tgl = date('d M Y', strtotime($item['tanggal']));
                                    $isToday = ($item['tanggal'] == date('Y-m-d'));
                                ?>
                                <tr>
                                    <td>
                                        <div class="schedule-date"><?= $tgl ?></div>
                                        <div class="schedule-time"><?= $item['jam'] ?></div>
                                    </td>
                                    <td>
                                        <span class="schedule-matkul"><?= $item['mata_kuliah'] ?></span>
                                        <span class="badge-prodi"><?= $item['prodi'] ?></span>
                                    </td>
                                    <td>
                                        <span class="schedule-kelas">Kelas <?= $item['kelas'] ?></span>
                                        <span class="schedule-freq"><?= $item['frekuensi'] ?></span>
                                    </td>
                                    <td>
                                        <div class="dosen-info">
                                            <i class="fas fa-user-tie"></i>
                                            <span class="dosen-name"><?= $item['dosen'] ?></span>
                                        </div>
                                    </td>
                                    <td>
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
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<script>
function startClock() {
    const clockElement = document.getElementById('live-clock');
    const dayDisplay = document.getElementById('header-day');
    const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const bulanIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    setInterval(() => {
        const now = new Date();
        clockElement.innerText = now.toLocaleTimeString('id-ID', { hour12: false }).replace(/\./g, ':');
        dayDisplay.innerText = "Jadwal " + hariIndo[now.getDay()] + ", " + now.getDate() + " " + bulanIndo[now.getMonth()];
    }, 1000);
}
startClock();
</script>