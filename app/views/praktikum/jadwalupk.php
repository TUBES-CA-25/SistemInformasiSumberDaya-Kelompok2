<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<section class="praktikum-section" style="padding: 60px 0; background: #f8fafc; min-height: 100vh;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; px: 20px;">
        
        <header class="page-header" style="text-align: center; margin-bottom: 60px;">
            <span class="header-badge" style="background: #eff6ff; color: #2563eb; padding: 6px 16px; rounded-full: 50px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid #dbeafe;">
                Jadwal Ujian Praktikum 2025/2026
            </span>
            <h1 id="header-day" style="font-size: 2.5rem; font-weight: 800; color: #0f172a; margin: 20px 0 10px;">Memuat Hari...</h1>
            <p style="color: #64748b; max-width: 600px; margin: 0 auto 30px;">Informasi real-time lokasi laboratorium, waktu ujian, dan dosen pengampu mata kuliah.</p>
            
            <div id="live-clock" class="live-clock-badge" style="display: inline-block; background: #2563eb; color: #fff; padding: 12px 24px; border-radius: 16px; font-family: 'JetBrains Mono', monospace; font-size: 1.8rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                00:00:00
            </div>
        </header>

        <div id="lab-tables-container">
            <?php if (empty($data['jadwal'])): ?>
                <div class="empty-schedule" style="text-align: center; padding: 80px; background: #fff; border-radius: 24px; border: 2px dashed #e2e8f0;">
                    <i class="far fa-calendar-times" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <h3 style="font-weight: 700; color: #1e293b;">Belum Ada Jadwal</h3>
                    <p style="color: #94a3b8;">Jadwal UPK belum dirilis oleh admin.</p>
                </div>
            <?php else: 
                // Grouping Data per Ruangan agar RAPI
                $grouped = [];
                foreach($data['jadwal'] as $row) {
                    $grouped[$row['ruangan']][] = $row;
                }
                ksort($grouped);

                foreach($grouped as $ruangan => $items):
            ?>
                <div class="schedule-wrapper" style="background: #fff; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 40px;">
                    <div class="lab-header" style="background: #f1f5f9; padding: 20px 30px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid #e2e8f0;">
                        <div style="width: 40px; h: 40px; background: #2563eb; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;"><?= $ruangan ?></h2>
                    </div>
                    
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 18px 30px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Waktu & Tanggal</th>
                                    <th style="padding: 18px 30px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Mata Kuliah</th>
                                    <th style="padding: 18px 30px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Kelas / Freq</th>
                                    <th style="padding: 18px 30px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Dosen Pengampu</th>
                                    <th style="padding: 18px 30px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody style="divide-y: 1px solid #f1f5f9;">
                                <?php foreach($items as $item): 
                                    $tgl = date('d M Y', strtotime($item['tanggal']));
                                    $isToday = ($item['tanggal'] == date('Y-m-d'));
                                ?>
                                <tr style="border-top: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 20px 30px;">
                                        <div style="font-weight: 800; color: #0f172a;"><?= $tgl ?></div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #2563eb; margin-top: 4px;">
                                            <?= $item['jam'] ?>
                                        </div>
                                    </td>
                                    <td style="padding: 20px 30px;">
                                        <div style="font-weight: 700; color: #1e293b; font-size: 1rem;"><?= $item['mata_kuliah'] ?></div>
                                        <span style="font-size: 0.7rem; background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 4px; font-weight: 700;"><?= $item['prodi'] ?></span>
                                    </td>
                                    <td style="padding: 20px 30px;">
                                        <div style="font-weight: 700; color: #475569;">Kelas <?= $item['kelas'] ?></div>
                                        <div style="font-size: 0.75rem; color: #94a3b8;"><?= $item['frekuensi'] ?></div>
                                    </td>
                                    <td style="padding: 20px 30px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-user-tie" style="color: #cbd5e1;"></i>
                                            <span style="font-weight: 600; color: #334155; font-size: 0.9rem;"><?= $item['dosen'] ?></span>
                                        </div>
                                    </td>
                                    <td style="padding: 20px 30px; text-align: center;">
                                        <?php if($isToday): ?>
                                            <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; border: 1px solid #fcd34d;">HARI INI</span>
                                        <?php elseif($item['tanggal'] < date('Y-m-d')): ?>
                                            <span style="background: #f1f5f9; color: #94a3b8; padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; font-weight: 800;">SELESAI</span>
                                        <?php else: ?>
                                            <span style="background: #ecfdf5; color: #059669; padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; border: 1px solid #a7f3d0;">AKAN DATANG</span>
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