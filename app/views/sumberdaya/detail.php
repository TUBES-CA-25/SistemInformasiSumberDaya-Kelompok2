<?php
/**
 * VIEW: DETAIL ASISTEN & MANAJEMEN (MVC Version)
 * File: app/views/sumberdaya/detail.php
 * * Data '$dataDetail' dikirim dari DetailSumberDayaController.
 */

$d = $data['dataDetail'] ?? null;
?>

<section class="sumberdaya-section fade-up">
    <div class="container">
        
        <?php if ($d) : ?>
            
            <div class="detail-nav">
                <a href="<?= $d['back_link'] ?>" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

            <div class="profile-wrapper">
                <div class="profile-image">
                    <img src="<?= $d['foto_url'] ?>" alt="<?= htmlspecialchars($d['nama']) ?>" style="<?= Helper::objectPosStyle($d) ?>" loading="lazy">
                </div>
                
                <div class="profile-content">
                    <span class="category-badge <?= $d['badge_style'] ?>">
                        <?= htmlspecialchars($d['kategori']); ?>
                    </span>
                    
                    <h1 class="profile-name">
                        <?= htmlspecialchars($d['nama']); ?>
                    </h1>
                    
                    <span class="profile-role">
                        <?= htmlspecialchars($d['jabatan']); ?>
                    </span>
                    
                    <div class="specialization-box">
                        <div class="member-specialization">
                            <i class="<?= $d['sub_icon'] ?>"></i> 
                            <?= htmlspecialchars($d['sub_info']); ?>
                        </div>
                        
                        <?php if($d['email'] !== '-'): ?>
                            <div class="member-specialization" style="margin-top: 8px; color: #64748b; font-weight: 500;">
                                <i class="ri-mail-line"></i> 
                                <?= htmlspecialchars($d['email']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php 
                    $isAsisten = (strpos($d['back_link'] ?? '', 'asisten') !== false || in_array($d['kategori'] ?? '', ['Asisten Laboratorium', 'Koordinator', 'Calon Asisten', 'Asisten']));
                    $isAlumni  = (strpos($d['back_link'] ?? '', 'alumni') !== false || ($d['badge_style'] ?? '') === 'badge-alumni');
                    $titleBio  = $isAlumni ? 'Mata Kuliah yang Pernah Diajar' : ($isAsisten ? 'Mata Kuliah yang Diajar' : 'Tentang');
                    ?>
                    <h4 class="section-title" style="margin-top: 30px;"><?= $titleBio ?></h4>
                    <?php if (!empty($d['matkul']) && is_array($d['matkul'])): ?>
                        <div class="skills-container" style="margin-top: 10px;">
                            <?php foreach($d['matkul'] as $mk): ?>
                                <span class="skill-tag" style="background: #ecfdf5; color: #047857; border-color: #a7f3d0; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="ri-book-open-line"></i> <?= htmlspecialchars($mk); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="profile-bio"><?= nl2br(htmlspecialchars($d['bio'])); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($d['skills'])) : ?>
                        <h4 class="section-title mt-30">Kompetensi & Keahlian</h4>
                        <div class="skills-container">
                            <?php foreach($d['skills'] as $skill): ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="contact-wrapper">
                        <?php if (!empty($d['email']) && $d['email'] !== '-'): ?>
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($d['email']) ?>" target="_blank" rel="noopener noreferrer" class="btn-contact">
                                <i class="ri-mail-send-line"></i> Email
                            </a>
                        <?php else: ?>
                            <button class="btn-disabled" disabled>
                                <i class="ri-mail-forbid-line"></i> Email Tidak Tersedia
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php else : ?>
            
            <div class="empty-state-wrapper" style="text-align: center; padding: 50px 0;">
                <div class="empty-icon" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 20px;">
                    <i class="ri-user-unfollow-line"></i>
                </div>
                <h2 class="empty-title">Data Tidak Ditemukan</h2>
                <p>Profil yang Anda cari tidak tersedia atau telah dihapus.</p>
                <br>
                <a href="<?= PUBLIC_URL ?>/asisten" class="btn-primary-pill" style="padding: 10px 25px; background: #2563eb; color: white; border-radius: 50px; text-decoration: none;">
                    Kembali ke Daftar
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>