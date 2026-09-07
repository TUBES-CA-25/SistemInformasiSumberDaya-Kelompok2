<section class="apps-section fade-up">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Ekosistem Digital</span>
            <h1>IC-Labs Apps</h1>
            <p>Akses terintegrasi ke seluruh layanan dan sistem informasi Laboratorium FIKOM UMI.</p>
        </header>

        <div class="apps-grid">
            <?php if (!empty($apps)): ?>
                <?php foreach ($apps as $app): 
                    $isActive = ((int)$app['is_active'] === 1);
                    $target   = !empty($app['target']) ? $app['target'] : '_blank';
                    $url      = $app['url'];
                    if ($url === '/' || $url === '') {
                        $url = BASE_URL . '/';
                    }
                    $warna = !empty($app['warna']) ? $app['warna'] : 'color-blue';
                    $ikon  = !empty($app['ikon']) ? $app['ikon'] : 'ri-apps-line';
                ?>
                    <?php if ($isActive): ?>
                        <a href="<?= htmlspecialchars($url) ?>" class="app-card" target="<?= htmlspecialchars($target) ?>" rel="noopener">
                            <div class="app-icon-box <?= htmlspecialchars($warna) ?>">
                                <i class="<?= htmlspecialchars($ikon) ?>"></i>
                            </div>
                            <div class="app-content">
                                <h3><?= htmlspecialchars($app['judul']) ?></h3>
                                <p><?= htmlspecialchars($app['deskripsi']) ?></p>
                            </div>
                            <div class="app-arrow">
                                <i class="ri-arrow-right-line"></i>
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="app-card maintenance" data-status="maintenance">
                            <div class="app-icon-box <?= htmlspecialchars($warna) ?>">
                                <i class="<?= htmlspecialchars($ikon) ?>"></i>
                            </div>
                            <div class="app-content">
                                <h3><?= htmlspecialchars($app['judul']) ?></h3>
                                <p><?= htmlspecialchars($app['deskripsi']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/" class="app-card">
                    <div class="app-icon-box color-green">
                        <i class="ri-team-line"></i>
                    </div>
                    <div class="app-content">
                        <h3>Sistem Informasi Sumber Daya</h3>
                        <p>Portal utama informasi lab dan manajemen sumber daya laboratorium.</p>
                    </div>
                    <div class="app-arrow">
                        <i class="ri-arrow-right-line"></i>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="<?= PUBLIC_URL ?>/js/apps.js" defer></script>