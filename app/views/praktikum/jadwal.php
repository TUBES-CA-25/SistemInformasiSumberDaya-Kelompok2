<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<section class="praktikum-section">
    <div class="container">
        <header class="page-header">
            <h1 id="header-day">Jadwal Praktikum</h1>
            <p>Data ditampilkan per ruangan laboratorium dengan informasi asisten lengkap.</p>
            
            <div style="margin-top: 30px;">
                <span id="live-clock" class="header-badge" style="background: #0f172a; color: #fff; font-family: 'JetBrains Mono', monospace; font-size: 1.5rem;">00:00:00</span>
            </div>
        </header>

        <div id="lab-tables-container">
            </div>
    </div>
</section>

<script>
/**
 * JAM DIGITAL (Update setiap detik)
 */
function startClock() {
    const clockElement = document.getElementById('live-clock');
    const dayDisplay = document.getElementById('header-day');
    const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

    setInterval(() => {
        const now = new Date();
        clockElement.innerText = now.toLocaleTimeString('id-ID', { hour12: false }).replace(/\./g, ':');
        dayDisplay.innerText = "Jadwal Hari " + hariIndo[now.getDay()];
    }, 1000);
}

/**
 * RENDER TABEL (Pemisahan Kolom Kelas, Freq, Asisten 1 & 2)
 */
function updateDashboard() {
    fetch('<?= API_URL ?>/jadwal')
        .then(res => res.json())
        .then(res => {
            const container = document.getElementById('lab-tables-container');
            const now = new Date();
            const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const hariIni = hariIndo[now.getDay()];
            const jamSekarang = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');

            const dataHariIni = res.data.filter(item => item.hari === hariIni);

            if (dataHariIni.length === 0) {
                container.innerHTML = `<div class="rule-card" style="text-align:center;"><h3>Kosong</h3><p>Tidak ada jadwal untuk hari ini.</p></div>`;
                return;
            }

            const labs = [...new Set(dataHariIni.map(item => item.namaLab))].sort();
            let finalHtml = '';

            labs.forEach(lab => {
                const jadwalLab = dataHariIni.filter(item => item.namaLab === lab);
                
                finalHtml += `
                <div class="schedule-wrapper" style="margin-bottom: 60px;">
                    <div class="sanksi-title">
                        <i class="fas fa-door-open"></i>
                        <h2 style="color: #0f172a;">RUANGAN: ${lab}</h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-schedule">
                            <thead>
                                <tr>
                                    <th width="10%">Waktu</th>
                                    <th width="20%">Mata Kuliah</th>
                                    <th width="5%">Kelas</th>
                                    <th width="10%">Freq</th>
                                    <th width="15%">Dosen</th>
                                    <th width="15%">Asisten 1</th>
                                    <th width="15%">Asisten 2</th>
                                    <th width="10%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>`;

                jadwalLab.forEach(item => {
                    const start = item.waktuMulai.substring(0, 5);
                    const end = item.waktuSelesai.substring(0, 5);
                    let statusTxt = 'Upcoming', statusClass = 'status-active';

                    if (jamSekarang >= start && jamSekarang <= end) {
                        statusTxt = 'Ongoing';
                    } else if (jamSekarang > end) {
                        statusTxt = 'Finish'; statusClass = 'status-closed';
                    }

                    finalHtml += `
                        <tr>
                            <td>${start} - ${end}</td>
                            <td style="color: #0f172a; font-weight: 800;">${item.namaMatakuliah}</td>
                            <td>${item.kelas || '-'}</td>
                            <td>${item.frekuensi || '-'}</td>
                            <td>${item.dosen}</td>
                            <td style="color: #475569; font-size: 0.9rem;">${item.asisten1 || '-'}</td>
                            <td style="color: #475569; font-size: 0.9rem;">${item.asisten2 || '-'}</td>
                            <td style="text-align:center;">
                                <span class="status-label ${statusClass}">${statusTxt}</span>
                            </td>
                        </tr>`;
                });
                finalHtml += `</tbody></table></div></div>`;
            });
            container.innerHTML = finalHtml;
        });
}

startClock();
updateDashboard();
setInterval(updateDashboard, 30000); // Sinkronisasi data tiap 30 detik
</script>