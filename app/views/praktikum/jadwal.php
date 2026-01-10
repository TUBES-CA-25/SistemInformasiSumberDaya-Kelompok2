<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">

<section class="praktikum-section">
    <div class="container">
        <header class="page-header">
            <span class="header-badge">Jadwal Praktikum 2025</span>
            <h1 id="header-day">Memuat Jadwal...</h1>
            <p>Informasi real-time penggunaan ruangan laboratorium, asisten bertugas, dan status praktikum.</p>
            
            <div id="live-clock" class="live-clock-badge">
                00:00:00
            </div>
        </header>

        <div id="lab-tables-container">
            <div style="text-align: center; padding: 50px; color: #64748b;">
                <i class="fas fa-circle-notch fa-spin fa-3x"></i>
                <p style="margin-top: 20px; font-weight: 600;">Mengambil data jadwal terbaru...</p>
            </div>
        </div>
    </div>
</section>

<script>
/**
 * FUNGSI JAM DIGITAL & HARI
 */
function startClock() {
    const clockElement = document.getElementById('live-clock');
    const dayDisplay = document.getElementById('header-day');
    const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

    setInterval(() => {
        const now = new Date();
        // Format Waktu: 14:05:30
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false }).replace(/\./g, ':');
        clockElement.innerText = timeString;
        
        // Update Hari
        dayDisplay.innerText = "Jadwal Hari " + hariIndo[now.getDay()];
    }, 1000);
}

/**
 * FUNGSI RENDER DASHBOARD
 */
function updateDashboard() {
    fetch('<?= API_URL ?>/jadwal')
        .then(res => res.json())
        .then(res => {
            const container = document.getElementById('lab-tables-container');
            const now = new Date();
            const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const hariIni = hariIndo[now.getDay()];
            
            // Format jam sekarang (HH:mm) untuk perbandingan
            const jamSekarang = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');

            // Filter data hanya hari ini
            const dataHariIni = res.data.filter(item => item.hari === hariIni);

            // JIKA KOSONG
            if (dataHariIni.length === 0) {
                container.innerHTML = `
                    <div class="empty-schedule">
                        <i class="far fa-calendar-times"></i>
                        <h3>Tidak Ada Praktikum</h3>
                        <p>Hari ini tidak ada jadwal praktikum yang terdaftar di sistem.</p>
                    </div>`;
                return;
            }

            // Grouping berdasarkan Lab
            const labs = [...new Set(dataHariIni.map(item => item.namaLab))].sort();
            let finalHtml = '';

            labs.forEach(lab => {
                const jadwalLab = dataHariIni.filter(item => item.namaLab === lab);
                
                // Urutkan jadwal berdasarkan jam mulai
                jadwalLab.sort((a, b) => a.waktuMulai.localeCompare(b.waktuMulai));

                finalHtml += `
                <div class="schedule-wrapper" style="margin-bottom: 60px;">
                    <div class="lab-header">
                        <div class="lab-icon"><i class="fas fa-desktop"></i></div>
                        <h2 class="lab-title">${lab}</h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-schedule">
                            <thead>
                                <tr>
                                    <th width="12%">Waktu</th>
                                    <th width="25%">Mata Kuliah</th>
                                    <th width="8%">Kls/Freq</th>
                                    <th width="20%">Dosen</th>
                                    <th width="20%">Asisten</th>
                                    <th width="15%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>`;

                jadwalLab.forEach(item => {
                    const start = item.waktuMulai.substring(0, 5);
                    const end = item.waktuSelesai.substring(0, 5);
                    
                    // Logic Status yang Lebih Cerdas
                    let statusBadge = '';
                    let statusText = '';
                    
                    if (jamSekarang >= start && jamSekarang < end) {
                        statusText = 'BERLANGSUNG';
                        statusBadge = 'status-label badge-ongoing';
                    } else if (jamSekarang < start) {
                        statusText = 'AKAN DATANG';
                        statusBadge = 'status-label badge-upcoming';
                    } else {
                        statusText = 'SELESAI';
                        statusBadge = 'status-label badge-finished';
                    }

                    // Gabung Kelas & Freq
                    const kelasFreq = `<b>${item.kelas || '-'}</b> <span style="color:#94a3b8">/</span> ${item.frekuensi || '-'}`;
                    
                    // Formatting Asisten (Biar Rapi)
                    const asisten1 = item.asisten1 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.asisten1}</div>` : '';
                    const asisten2 = item.asisten2 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.asisten2}</div>` : '';
                    const asistenDisplay = (asisten1 || asisten2) ? `<div class="asisten-cell">${asisten1}${asisten2}</div>` : '<span style="color:#cbd5e1">-</span>';

                    finalHtml += `
                        <tr>
                            <td style="font-family:'JetBrains Mono', monospace; font-size:0.95rem;">
                                ${start} - ${end}
                            </td>
                            
                            <td style="color: #0f172a; font-weight: 700;">
                                ${item.namaMatakuliah}
                            </td>
                            
                            <td>${kelasFreq}</td>
                            
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <i class="fas fa-chalkboard-teacher" style="color:#64748b;"></i>
                                    <span style="font-weight:500;">${item.dosen}</span>
                                </div>
                            </td>
                            
                            <td>${asistenDisplay}</td>
                            
                            <td style="text-align:center;">
                                <span class="${statusBadge}">${statusText}</span>
                            </td>
                        </tr>`;
                });
                finalHtml += `</tbody></table></div></div>`;
            });
            container.innerHTML = finalHtml;
        })
        .catch(err => {
            console.error(err);
            document.getElementById('lab-tables-container').innerHTML = `
                <div class="empty-schedule" style="border-color:#fca5a5;">
                    <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat menghubungi server API.</p>
                </div>`;
        });
}

// Jalankan Fungsi
startClock();
updateDashboard();
setInterval(updateDashboard, 30000); // Auto-refresh tiap 30 detik
</script>