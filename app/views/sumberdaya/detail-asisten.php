<section class="sumberdaya-section">
    <div class="container">
        
        <a href="<?= BASE_URL ?>/asisten" class="btn-back">
            <i class="ri-arrow-left-line"></i> Kembali ke Daftar
        </a>

        <div class="profile-wrapper">
            <div class="profile-image" style="display:flex;align-items:center;justify-content:center;background:#f4f6fa;">
                <?php if (!empty($asisten['foto'])): ?>
                    <img src="<?= BASE_URL . '/public/assets/uploads/' . $asisten['foto'] ?>" alt="<?= htmlspecialchars($asisten['nama']) ?>" style="width:100%;height:100%;object-fit:cover;object-position:top center;">
                <?php else: ?>
                    <span style="font-size:7rem;font-weight:700;color:#64748b;">
                        <?= strtoupper(substr($asisten['nama'],0,2)) ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="profile-content">
                <?php if($asisten['isKoordinator']): ?>
                    <div class="profile-role-badge" style="margin-bottom:18px;">Koordinator Lab</div>
                <?php endif; ?>
                <h2 style="font-size:2.2rem;font-weight:900;margin-bottom:0;line-height:1.1;"> <?= htmlspecialchars($asisten['nama']) ?> </h2>
                <div style="font-size:1.1rem;color:#64748b;margin-bottom:18px;">
                    <?= $asisten['isKoordinator'] ? 'Koordinator Laboratorium' : 'Asisten Laboratorium' ?>
                </div>
                <div style="margin-bottom:18px;display:flex;align-items:center;gap:8px;">
                    <svg width="18" height="18" fill="#2563eb" style="vertical-align:middle;" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                    <span style="font-weight:600; color:#2563eb;"> <?= htmlspecialchars($asisten['jurusan'] ?? '-') ?> </span>
                </div>
                <div style="margin-bottom:18px;">
                    <strong>Tentang</strong><br>
                    <span style="color:#334155;">
                        <?= !empty($asisten['bio']) ? nl2br(htmlspecialchars($asisten['bio'])) : 'Belum ada biografi.' ?>
                    </span>
                </div>
                <div style="margin-bottom:18px;">
                    <strong>Kompetensi &amp; Keahlian</strong><br>
                    <div class="skills-container" style="margin-top:8px;">
                        <?php 
                            $skills = [];
                            if (!empty($asisten['skills'])) {
                                $skills = json_decode($asisten['skills'], true);
                                if (json_last_error() !== JSON_ERROR_NONE) {
                                    $skills = explode(',', $asisten['skills']);
                                }
                            } elseif (!empty($asisten['spesialisasi'])) {
                                $skills = explode(',', $asisten['spesialisasi']);
                            }
                            if (!empty($skills) && is_array($skills)):
                                foreach($skills as $skill): 
                                    $skill = trim($skill);
                                    if(empty($skill)) continue;
                        ?>
                            <span class="skill-tag"> <?= htmlspecialchars($skill) ?> </span>
                        <?php 
                                endforeach;
                            else:
                        ?>
                            <span class="text-muted">Belum ada data keahlian.</span>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="mailto:<?= htmlspecialchars($asisten['email']) ?>" class="btn-contact" style="margin-top:18px;">&#9993; Hubungi via Email</a>
            </div>
        </div>

    </div>
</section>
