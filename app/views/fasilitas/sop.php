<style>
    /* Fix Layout (Jaga-jaga agar footer tidak naik) */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    main {
        flex: 1;
    }

    /* --- Desain Card SOP --- */
    .sop-wrapper {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .sop-header-row {
        background: #f8fafc;
        padding: 20px 30px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .sop-header-title {
        font-weight: 800;
        color: #475569;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sop-item {
        display: flex;
        align-items: center;
        padding: 25px 30px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
        gap: 20px;
    }

    .sop-item:last-child {
        border-bottom: none;
    }

    .sop-item:hover {
        background-color: #f0f9ff; /* Warna biru sangat muda saat hover */
        transform: translateX(5px);
        border-left: 4px solid #2563eb;
    }

    /* Nomor Urut */
    .sop-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: #94a3b8;
        min-width: 30px;
    }

    /* Icon Dokumen */
    .sop-icon {
        width: 45px;
        height: 45px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    /* Teks Judul & Deskripsi */
    .sop-info {
        flex: 1;
    }

    .sop-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
        display: block;
    }

    .sop-desc {
        font-size: 0.9rem;
        color: #64748b;
        margin: 0;
    }

    /* Tombol Aksi */
    .btn-sop-action {
        padding: 10px 20px;
        background: #fff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .btn-sop-action:hover {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    /* Responsif Mobile */
    @media (max-width: 600px) {
        .sop-item {
            flex-wrap: wrap;
            padding: 20px;
        }
        .sop-number {
            display: none; /* Sembunyikan nomor di HP biar bersih */
        }
        .sop-info {
            width: 100%;
            margin-bottom: 15px;
        }
        .btn-sop-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

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
                            
                            <a href="<?= PUBLIC_URL ?>/assets/uploads/<?= $sop['file'] ?>" target="_blank" class="btn-sop-action">
                                Buka Dokumen <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </div>
    </div>
</section>