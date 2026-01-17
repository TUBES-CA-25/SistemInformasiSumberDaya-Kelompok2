<section class="praktikum-section full-height">
    <div class="container flex-grow">
        <header class="page-header">
            <span class="header-badge">Repositori Materi</span>
            <h1>Modul Praktikum</h1>
            <p>Daftar materi praktikum Teknik Informatika & Sistem Informasi untuk mendukung kegiatan belajar di Laboratorium.</p>
        </header>

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

        </div>
    </div>
</section>