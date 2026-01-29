<section class="sumberdaya-section" style="padding-top: 100px; padding-bottom: 100px; background: #f8fafc;">
    <div class="container">
        
        <header class="page-header reveal fade-up">
            <span class="header-badge">Dokumen Resmi</span>
            <h1>Standar Operasional Prosedur</h1>
            <p>Panduan teknis dan tata tertib pelaksanaan kegiatan praktikum serta riset di Laboratorium.</p>
        </header>

        <div class="search-container reveal fade-up" style="margin-bottom: 40px;">
            <input type="text" class="search-input" placeholder="Cari nama prosedur...">
            <i class="ri-search-line search-icon-compact"></i>
        </div>

        <div class="sop-container reveal fade-up" style="max-width: 900px; margin: 0 auto;">
            
            <?php if (empty($data['sop_list'])) : ?>
                <div class="empty-state-wrapper" style="text-align: center; padding: 60px 20px;">
                    <div style="background: #e2e8f0; width: 80px; height: 80px; border-radius: 50%; display:flex; align-items:center; justify-content:center; margin: 0 auto 20px;">
                        <i class="ri-folder-open-line" style="font-size: 2.5rem; color: #64748b;"></i>
                    </div>
                    <h3 style="color: #1e293b; margin-bottom: 10px;">Belum Ada Dokumen</h3>
                    <p style="color: #64748b;">Daftar SOP sedang dalam proses pembaruan oleh admin.</p>
                </div>
            <?php else : ?>
                
                <div class="sop-wrapper">
                    <div class="sop-header-row">
                        <span class="sop-header-title">Daftar Prosedur</span>
                        <span class="sop-header-title">Aksi</span>
                    </div>

                    <?php $no = 1; foreach ($data['sop_list'] as $sop) : ?>
                        <div class="sop-item">
                            <div class="sop-number"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?>.</div>
                            
                            <div class="sop-icon">
                                <i class="ri-file-text-line"></i>
                            </div>
                            
                            <div class="sop-info">
                                <span class="sop-title"><?= htmlspecialchars($sop['judul']) ?></span>
                                <p class="sop-desc">
                                    <?= htmlspecialchars($sop['deskripsi'] ?? 'Dokumen standar operasional laboratorium.') ?>
                                </p>
                            </div>
                            
                            <a href="<?= PUBLIC_URL ?>/assets/uploads/pdf/<?= $sop['file'] ?>" target="_blank" class="btn-sop-action">
                                Buka Dokumen <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </div>
    </div>
</section>

<script src="<?= ASSETS_URL ?>/js/sop.js"></script>