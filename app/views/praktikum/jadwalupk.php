<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<section class="praktikum-section">
    <div class="container">
        <header class="page-header">
            <span class="header-badge">Jadwal Ujian Praktikum</span>
            <h1 id="upk-header-day">Jadwal UPK</h1>
            <p>Informasi real-time lokasi laboratorium, waktu ujian, dan dosen pengampu mata kuliah.</p>
            <div id="live-clock" class="live-clock-badge">00:00:00</div>
        </header>

        <div class="search-filter-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="keyword-search" class="search-input" placeholder="Cari Jadwal UPK, Dosen, atau Ruangan...">
            </div>
            <button class="btn-filter-toggle" id="toggle-filter-btn">
                <i class="fas fa-sliders-h"></i>
                <span>Filter Pencarian</span>
            </button>
        </div>

        <div class="filter-card" id="filter-card">
            <div class="filter-header">
                <h3><i class="fas fa-filter"></i> Filter Pencarian</h3>
                <button id="btn-reset-upk" class="btn-reset" style="display:none;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
            
            <div class="filter-grid" id="upk-filter-grid">
                <div class="adv-dropdown" id="dd-upk-tanggal">
                    <button class="adv-drop-btn">Tanggal <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-upk-prodi">
                    <button class="adv-drop-btn">Prodi <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-upk-ruang">
                    <button class="adv-drop-btn">Ruangan <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-upk-matkul">
                    <button class="adv-drop-btn">Mata Kuliah <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-upk-kelas">
                    <button class="adv-drop-btn">Kelas <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-upk-dosen">
                    <button class="adv-drop-btn">Dosen <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
                <div class="adv-dropdown" id="dd-upk-status">
                    <button class="adv-drop-btn">Status <i class="fas fa-chevron-down"></i></button>
                    <div class="adv-drop-content"></div>
                </div>
            </div>
        </div>

        <div id="upk-tables-container">
            <div style="text-align: center; padding: 50px; color: #64748b;">
                <i class="fas fa-circle-notch fa-spin fa-3x"></i>
                <p style="margin-top: 20px; font-weight: 600;">Memuat data jadwal UPK...</p>
            </div>
        </div>
    </div>
</section>

<script>
    <?php 
        // Ambil data dari controller, default ke array kosong jika null
        $rawJadwal = $data['jadwal'] ?? []; 
    ?>
    window.UPK_DATA = <?= json_encode($rawJadwal) ?>;
</script>

<script src="<?= PUBLIC_URL ?>/js/praktikum.js"></script>