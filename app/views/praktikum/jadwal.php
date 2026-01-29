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
                <select id="day-select" class="custom-select" onchange="renderJadwalDashboard()">
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
    // Definisikan URL API dari PHP agar bisa dibaca oleh file JS eksternal
    window.API_JADWAL_URL = "<?= API_URL ?>/jadwal";
</script>

<script src="<?= PUBLIC_URL ?>/js/praktikum.js"></script>