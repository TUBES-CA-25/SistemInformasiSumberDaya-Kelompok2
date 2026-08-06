<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<section class="praktikum-section full-height">
    <div class="container flex-grow">
        <header class="page-header">
            <span class="header-badge">Repositori Materi</span>
            <h1>Modul Praktikum</h1>
            <p>Daftar materi praktikum Teknik Informatika & Sistem Informasi untuk mendukung kegiatan belajar di Laboratorium.</p>

            <div class="filter-controls" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-top: 25px;">
                <!-- Prodi Filter -->
                <div class="day-selector-wrapper" style="margin-top: 0;">
                    <select id="modul-prodi-select" class="custom-select" onchange="filterModulPage()" style="min-width: 160px;">
                        <option value="">Semua Prodi</option>
                        <option value="TI">Teknik Informatika (TI)</option>
                        <option value="SI">Sistem Informasi (SI)</option>
                    </select>
                    <i class="fas fa-chevron-down select-icon"></i>
                </div>

                <!-- Search Input -->
                <div class="search-input-wrapper" style="position: relative; display: inline-block;">
                    <input type="text" id="modul-search-input" onkeyup="filterModulPage()" oninput="filterModulPage()" placeholder="Cari matakuliah, modul..." 
                        style="padding: 12px 20px 12px 45px; border-radius: 12px; border: 2px solid #e2e8f0; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600; outline: none; transition: all 0.3s; background: white; color: #0f172a; min-width: 280px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <i class="fas fa-search" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #64748b;"></i>
                </div>
            </div>
        </header>

        <div class="modul-grid">
            
            <div class="modul-card" data-prodi="TI">
                <div class="modul-header header-ti">
                    <div class="modul-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div class="header-text">
                        <h3 style="color: #ffffff;">Teknik Informatika (TI)</h3>
                        <span>Modul Praktikum Program Studi</span>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table-modul">
                        <thead>
                            <tr>
                                <th class="text-center w-no">No</th>
                                <th>Mata Kuliah</th>
                                <th>Judul Modul</th>
                                <th class="text-center w-action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['modul_ti'])) : ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <div class="empty-content">
                                            <i class="ri-folder-open-line"></i>
                                            <p>Belum ada modul TI tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : $no = 1; foreach ($data['modul_ti'] as $m) : ?>
                                <tr>
                                    <td class="text-center number-col"><?= $no++; ?></td>
                                    <td>
                                        <span class="matkul-name"><?= htmlspecialchars($m['nama_matakuliah']) ?></span>
                                    </td>
                                    <td>
                                        <span class="modul-title"><?= htmlspecialchars($m['judul']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= PUBLIC_URL ?>/assets/uploads/modul/<?= $m['file'] ?>" download class="btn-download-pill">
                                            <i class="ri-download-cloud-2-line"></i> Unduh
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modul-card" data-prodi="SI">
                <div class="modul-header header-si">
                    <div class="modul-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="header-text">
                        <h3 style="color: #ffffff;">Sistem Informasi (SI)</h3>
                        <span>Modul Praktikum Program Studi</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-modul">
                        <thead>
                            <tr>
                                <th class="text-center w-no">No</th>
                                <th>Mata Kuliah</th>
                                <th>Judul Modul</th>
                                <th class="text-center w-action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['modul_si'])) : ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <div class="empty-content">
                                            <i class="ri-folder-open-line"></i>
                                            <p>Belum ada modul SI tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : $no = 1; foreach ($data['modul_si'] as $m) : ?>
                                <tr>
                                    <td class="text-center number-col"><?= $no++; ?></td>
                                    <td>
                                        <span class="matkul-name"><?= htmlspecialchars($m['nama_matakuliah']) ?></span>
                                    </td>
                                    <td>
                                        <span class="modul-title"><?= htmlspecialchars($m['judul']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= PUBLIC_URL ?>/assets/uploads/modul/<?= $m['file'] ?>" download class="btn-download-pill">
                                            <i class="ri-download-cloud-2-line"></i> Unduh
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="modul-empty-message" class="empty-schedule" style="grid-column: 1 / -1; margin-top: 20px; display: none;">
                <i class="far fa-folder-open"></i>
                <h3>Tidak Ada Modul Ditemukan</h3>
                <p>Tidak ada modul praktikum yang cocok dengan pencarian Anda.</p>
            </div>

        </div>
    </div>
</section>

<script src="<?= PUBLIC_URL ?>/js/praktikum.js?v=<?= time() ?>" defer></script>