<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">

<section class="praktikum-section">
    <div class="container">
        <header class="page-header">
            <span class="header-badge">Jadwal Praktikum 2025</span>
            <h1 id="header-title">Jadwal Praktikum</h1>
            <p>Informasi real-time penggunaan ruangan laboratorium, asisten bertugas, dan status praktikum.</p>
            <div id="live-clock" class="live-clock-badge">00:00:00</div>
        </header>

        <div class="filter-card">
            <div class="filter-header">
                <h3><i class="fas fa-filter"></i> Filter Pencarian</h3>
                <button id="btn-reset-filter" class="btn-reset" style="display:none;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
            
            <div class="filter-grid">
                <div class="adv-dropdown" id="dd-hari">
                    <button class="adv-drop-btn">Hari <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-prodi">
                    <button class="adv-drop-btn">Prodi <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-jam">
                    <button class="adv-drop-btn">Jam <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-kelas">
                    <button class="adv-drop-btn">Kelas <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-matkul">
                    <button class="adv-drop-btn">Mata Kuliah <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-dosen">
                    <button class="adv-drop-btn">Dosen <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-asisten">
                    <button class="adv-drop-btn">Asisten <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-status">
                    <button class="adv-drop-btn">Status <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
            </div>
        </div>

        <div id="lab-tables-container">
            <div style="text-align: center; padding: 50px; color: #64748b;">
                <i class="fas fa-circle-notch fa-spin fa-3x"></i>
                <p style="margin-top: 20px; font-weight: 600;">Mengambil data jadwal...</p>
            </div>
        </div>
    </div>
</section>

<script>
    // URL API SAMA SEPERTI KODE LAMA
    window.API_JADWAL_URL = "<?= API_URL ?>/jadwal";
</script>

<script src="<?= PUBLIC_URL ?>/js/praktikum.js"></script>