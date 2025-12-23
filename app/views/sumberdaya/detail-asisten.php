<section class="sumberdaya-section">
    <div class="container">
        
        <a href="<?= BASE_URL ?>/asisten" class="btn-back">
            <i class="ri-arrow-left-line"></i> Kembali ke Daftar
        </a>

        <div class="profile-wrapper">
            <div class="profile-image">
                <?php
                    $fotoPath = !empty($asisten['foto']) ? ASSETS_URL . '/uploads/' . $asisten['foto'] : 'https://placehold.co/400x500/f1f5f9/94a3b8?text=' . urlencode(substr($asisten['nama'], 0, 2));
                ?>
                <img src="<?= $fotoPath ?>" alt="<?= htmlspecialchars($asisten['nama']) ?>" onerror="this.src='https://placehold.co/400x500/f1f5f9/94a3b8?text=FOTO'">
            </div>
            
            <div class="profile-content">
                <span class="profile-role-badge">
                    <?= htmlspecialchars($asisten['jabatan'] ?? 'Asisten Laboratorium') ?>
                </span>
                
                <h2><?= htmlspecialchars($asisten['nama']) ?></h2>
                
                <div class="profile-bio">
                    <p><strong>Jurusan:</strong> <?= htmlspecialchars($asisten['jurusan'] ?? '-') ?></p>
                    <p><strong>Laboratorium:</strong> <?= htmlspecialchars($asisten['lab'] ?? '-') ?></p>
                    <p><strong>Status:</strong> <?= $asisten['statusAktif'] == '1' ? 'Aktif' : 'Tidak Aktif' ?></p>
                    <br>
                    <?php if (!empty($asisten['bio'])): ?>
                        <p><?= nl2br(htmlspecialchars($asisten['bio'])) ?></p>
                    <?php else: ?>
                        <p><em>Belum ada biografi yang ditambahkan.</em></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($asisten['spesialisasi'])): ?>
                <div class="skills-container">
                    <?php 
                        $skills = explode(',', $asisten['spesialisasi']);
                        foreach($skills as $skill): 
                    ?>
                        <span class="skill-tag"><?= trim(htmlspecialchars($skill)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <a href="mailto:<?= htmlspecialchars($asisten['email']) ?>" class="btn-contact">
                    <i class="ri-mail-send-line"></i> Hubungi Asisten
                </a>
            </div>
        </div>

    </div>
</section>
