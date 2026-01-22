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

            <br>

            <div class="day-selector-wrapper">
                <select id="day-select" class="custom-select" onchange="updateDashboard()">
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
                <i class="fas fa-chevron-down select-icon"></i>
            </div>
        </header>

        <div id="lab-tables-container">
            <div style="text-align: center; padding: 50px; color: #64748b;">
                <i class="fas fa-circle-notch fa-spin fa-3x"></i>
                <p style="margin-top: 20px; font-weight: 600;">Mengambil data jadwal...</p>
            </div>
        </div>
    </div>
</section>

<script>
// Variabel Global
const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
let allData = []; // Menyimpan data agar tidak perlu fetch berulang kali saat ganti hari

/**
 * FUNGSI JAM DIGITAL
 * (Hanya update jam, tidak update teks Header Hari lagi)
 */
function startClock() {
    const clockElement = document.getElementById('live-clock');
    
    setInterval(() => {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false }).replace(/\./g, ':');
        clockElement.innerText = timeString;
    }, 1000);
}

/**
 * INISIALISASI HALAMAN
 */
function initPage() {
    const now = new Date();
    const hariIni = hariIndo[now.getDay()];
    
    // Set dropdown ke hari ini (jika Minggu, default ke Senin)
    const dropdown = document.getElementById('day-select');
    if (hariIni !== "Minggu") {
        dropdown.value = hariIni;
    } else {
        dropdown.value = "Senin";
    }

    startClock();
    fetchDataAndRender(); // Fetch pertama kali
}

/**
 * FETCH DATA DARI API
 */
function fetchDataAndRender() {
    fetch('<?= API_URL ?>/jadwal')
        .then(res => res.json())
        .then(res => {
            allData = res.data; // Simpan data ke variabel global
            updateDashboard();  // Render tampilan
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

/**
 * FUNGSI RENDER DASHBOARD UTAMA
 */
function updateDashboard() {
    const container = document.getElementById('lab-tables-container');
    const headerDay = document.getElementById('header-day');
    
    // Ambil hari yang dipilih user dari dropdown
    const selectedDay = document.getElementById('day-select').value;
    
    // Update Judul
    headerDay.innerText = "Jadwal Hari " + selectedDay;

    // Filter data sesuai hari yang dipilih
    const filteredData = allData.filter(item => item.hari === selectedDay);

    // Cek apakah hari yang dipilih sama dengan hari ini (Real-time check)
    const now = new Date();
    const realToday = hariIndo[now.getDay()];
    const isToday = (selectedDay === realToday);
    const jamSekarang = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');

    if (filteredData.length === 0) {
        container.innerHTML = `
            <div class="empty-schedule">
                <i class="far fa-calendar-times"></i>
                <h3>Tidak Ada Praktikum</h3>
                <p>Tidak ada jadwal praktikum terdaftar untuk hari ${selectedDay}.</p>
            </div>`;
        return;
    }

    const labs = [...new Set(filteredData.map(item => item.namaLab))].sort();
    let finalHtml = '';

    labs.forEach(lab => {
        const jadwalLab = filteredData.filter(item => item.namaLab === lab);
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
                            <th class="text-nowrap">Waktu</th>
                            <th width="25%">Mata Kuliah</th>
                            <th class="text-nowrap">Kls/Freq</th>
                            <th width="20%">Dosen</th>
                            <th width="20%">Asisten</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;

        jadwalLab.forEach(item => {
            const start = item.waktuMulai.substring(0, 5);
            const end = item.waktuSelesai.substring(0, 5);
            
            let statusBadge = '';
            let statusText = '';
            
            // LOGIKA STATUS: Hanya aktif jika melihat jadwal HARI INI
            if (isToday) {
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
            } else {
                // Jika melihat hari lain, statusnya netral
                statusText = 'TERJADWAL';
                statusBadge = 'status-label badge-scheduled';
            }

            const kelasFreq = `<b>${item.kelas || '-'}</b> <span style="color:#94a3b8">/</span> ${item.frekuensi || '-'}`;
            
            const asisten1 = item.asisten1 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.asisten1}</div>` : '';
            const asisten2 = item.asisten2 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.asisten2}</div>` : '';
            const asistenDisplay = (asisten1 || asisten2) ? `<div class="asisten-cell">${asisten1}${asisten2}</div>` : '<span style="color:#cbd5e1">-</span>';

            finalHtml += `
                <tr>
                    <td class="text-nowrap" style="font-family:'JetBrains Mono', monospace; font-size:0.95rem;">
                        ${start} - ${end}
                    </td>
                    
                    <td style="color: #0f172a; font-weight: 700;">
                        ${item.namaMatakuliah}
                    </td>
                    
                    <td class="text-nowrap">${kelasFreq}</td>
                    
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
}

// Jalankan saat halaman siap
initPage();

// Refresh data otomatis setiap 60 detik (bukan render ulang, tapi fetch baru jaga-jaga ada perubahan data)
setInterval(fetchDataAndRender, 60000); 

// Live update status badge jika sedang melihat HARI INI (update setiap menit)
setInterval(() => {
    const dropdown = document.getElementById('day-select');
    const now = new Date();
    const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    if(dropdown.value === hariIndo[now.getDay()]) {
        updateDashboard(); // Re-render untuk update badge status
    }
}, 60000);

</script>