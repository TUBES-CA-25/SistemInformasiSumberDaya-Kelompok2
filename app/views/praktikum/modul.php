<section class="praktikum-section" style="min-height: 100vh; display: flex; flex-direction: column;">
    <div class="container" style="flex: 1;">
        <header class="page-header">
            <span class="header-badge">Repositori Materi</span>
            <h1>Modul Praktikum</h1>
            <p>Daftar materi praktikum Teknik Informatika & Sistem Informasi untuk mendukung kegiatan belajar di Laboratorium.</p>
        </header>

        <div class="rules-grid" style="grid-template-columns: 1fr; gap: 50px;">
            
            <div class="rule-card" style="padding: 0; overflow: hidden; border-top: none;">
                <div class="lab-header" style="background: #2563eb; color: white; padding: 20px 30px; display: flex; align-items: center; gap: 15px;">
                    <div class="lab-icon" style="background: rgba(255,255,255,0.2); color: white;">
                        <i class="ri-code-s-slash-line"></i>
                    </div>
                    <h3 style="margin: 0; color: white; font-size: 1.5rem; font-weight: 800;">Teknik Informatika</h3>
                </div>
                
                <div class="table-responsive">
                    <table class="table-schedule">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px; background: #2563eb; border-bottom: 4px solid #1e40af;">No</th>
                                <th style="background: #2563eb; border-bottom: 4px solid #1e40af;">Mata Kuliah</th>
                                <th style="background: #2563eb; border-bottom: 4px solid #1e40af;">Judul Modul</th>
                                <th class="text-center" style="width: 200px; background: #2563eb; border-bottom: 4px solid #1e40af;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['modul_ti'])) : ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8; font-style: italic;">
                                        <i class="ri-folder-open-line" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                        Belum ada modul TI tersedia
                                    </td>
                                </tr>
                            <?php else : $no = 1; foreach ($data['modul_ti'] as $m) : ?>
                                <tr>
                                    <td class="text-center font-bold" style="color: #64748b;"><?= $no++; ?></td>
                                    <td>
                                        <span class="schedule-matkul"><?= htmlspecialchars($m['nama_matakuliah']) ?></span>
                                    </td>
                                    <td>
                                        <div class="dosen-info">
                                            <span class="dosen-name"><?= htmlspecialchars($m['judul']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= PUBLIC_URL ?>/assets/uploads/modul/<?= $m['file'] ?>" download class="btn-download" style="padding: 8px 16px; border-radius: 50px; background-color: #2563eb;">
                                            <i class="ri-download-cloud-2-line"></i> Unduh PDF
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rule-card" style="padding: 0; overflow: hidden; border-top: none;">
                <div class="lab-header" style="background: #2563eb; color: white; padding: 20px 30px; display: flex; align-items: center; gap: 15px;">
                    <div class="lab-icon" style="background: rgba(255,255,255,0.2); color: white;">
                        <i class="ri-line-chart-line"></i>
                    </div>
                    <h3 style="margin: 0; color: white; font-size: 1.5rem; font-weight: 800;">Sistem Informasi</h3>
                </div>

                <div class="table-responsive">
                    <table class="table-schedule">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px; background: #2563eb; border-bottom: 4px solid #1e40af;">No</th>
                                <th style="background: #2563eb; border-bottom: 4px solid #1e40af;">Mata Kuliah</th>
                                <th style="background: #2563eb; border-bottom: 4px solid #1e40af;">Judul Modul</th>
                                <th class="text-center" style="width: 200px; background: #2563eb; border-bottom: 4px solid #1e40af;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['modul_si'])) : ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8; font-style: italic;">
                                        <i class="ri-folder-open-line" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                        Belum ada modul SI tersedia
                                    </td>
                                </tr>
                            <?php else : $no = 1; foreach ($data['modul_si'] as $m) : ?>
                                <tr>
                                    <td class="text-center font-bold" style="color: #64748b;"><?= $no++; ?></td>
                                    <td>
                                        <span class="schedule-matkul"><?= htmlspecialchars($m['nama_matakuliah']) ?></span>
                                    </td>
                                    <td>
                                        <div class="dosen-info">
                                            <span class="dosen-name"><?= htmlspecialchars($m['judul']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= PUBLIC_URL ?>/assets/uploads/modul/<?= $m['file'] ?>" download class="btn-download" style="padding: 8px 16px; border-radius: 50px; background-color: #2563eb;">
                                            <i class="ri-download-cloud-2-line"></i> Unduh PDF
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