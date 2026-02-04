<section class="praktikum-section full-height">
    <div class="container flex-grow">
        <header class="page-header">
            <span class="header-badge">Repositori Materi</span>
            <h1>Modul Praktikum</h1>
            <p>Daftar materi praktikum Teknik Informatika & Sistem Informasi untuk mendukung kegiatan belajar di Laboratorium.</p>
        </header>

        <div class="search-filter-container" style="justify-content: center; margin-bottom: 40px;">
            <div class="search-box" style="max-width: 600px; width: 100%;">
                <i class="ri-search-line"></i>
                <input type="text" id="modul-praktikum-search" class="search-input" placeholder="Cari Mata Kuliah atau Judul Modul...">
            </div>
        </div>

        <div class="modul-grid">
            
            <div class="modul-card">
                <div class="modul-header header-ti">
                    <div class="modul-icon">
                        <i class="ri-code-s-slash-line"></i>
                    </div>
                    <div class="header-text">
                        <h3 style="color: #ffffff;">Teknik Informatika</h3>
                        <span>Fakultas Ilmu Komputer</span>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table-modul" id="table-ti">
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
                                <tr class="no-data">
                                    <td colspan="4" class="empty-state">
                                        <div class="empty-content">
                                            <i class="ri-folder-open-line"></i>
                                            <p>Belum ada modul TI tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : $no = 1; foreach ($data['modul_ti'] as $m) : ?>
                                <tr class="modul-item">
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
                            <tr class="not-found-msg" style="display: none;">
                                <td colspan="4" style="text-align: center; padding: 20px; color: #94a3b8;">
                                    Tidak ada modul yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modul-card">
                <div class="modul-header header-si">
                    <div class="modul-icon">
                        <i class="ri-line-chart-line"></i>
                    </div>
                    <div class="header-text">
                        <h3 style="color: #ffffff;">Sistem Informasi</h3>
                        <span>Fakultas Ilmu Komputer</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-modul" id="table-si">
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
                                <tr class="no-data">
                                    <td colspan="4" class="empty-state">
                                        <div class="empty-content">
                                            <i class="ri-folder-open-line"></i>
                                            <p>Belum ada modul SI tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : $no = 1; foreach ($data['modul_si'] as $m) : ?>
                                <tr class="modul-item">
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
                            <tr class="not-found-msg" style="display: none;">
                                <td colspan="4" style="text-align: center; padding: 20px; color: #94a3b8;">
                                    Tidak ada modul yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

<script src="<?= PUBLIC_URL ?>/js/praktikum.js"></script>
