<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            
            <div class="footer-section brand-col">
                <div class="footer-logo">
                    <img src="<?php echo ASSETS_URL; ?>/images/navbar-icon.png" alt="Logo IC-Labs FIKOM UMI" style="height: 40px; object-fit: contain;">
                    <span style="font-weight: 800; font-size: 1.2rem; color: #fff;">IC-Labs</span>
                </div>
                <p class="footer-desc">
                    Laboratorium terpadu Fakultas Ilmu Komputer UMI. Pusat pengembangan keahlian praktis dan riset inovatif mahasiswa.
                </p>
                <div class="contact-list">
                    <p><i class="ri-mail-send-line"></i> fikom.iclabs@umi.ac.id</p>
                    <p><i class="ri-phone-line"></i> +62 411 455666</p>
                </div>
            </div>

            <div class="footer-section">
                <h3>Menu Utama</h3>
                <ul class="footer-links">
                    <li><a href="<?php echo PUBLIC_URL; ?>/home">Beranda</a></li>
                    <li><a href="<?php echo PUBLIC_URL; ?>/atasan">Profil Pimpinan</a></li>
                    <li><a href="<?php echo PUBLIC_URL; ?>/asisten">Asisten Lab</a></li>
                    <li><a href="<?php echo PUBLIC_URL; ?>/laboratorium">Fasilitas & Lab</a></li>
                    <li><a href="<?php echo PUBLIC_URL; ?>/riset">Riset & Inovasi</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Akademik & Tautan</h3>
                <ul class="footer-links">
                    <li><a href="<?php echo PUBLIC_URL; ?>/jadwal">Jadwal Praktikum</a></li>
                    <li><a href="<?php echo PUBLIC_URL; ?>/tatatertib">Tata Tertib</a></li>
                    <li><a href="<?php echo PUBLIC_URL; ?>/alumni">Data Alumni</a></li>
                    
                    <li class="separator-link" style="margin: 10px 0; border-top: 1px solid #334155;"></li>
                    
                    <li>
                        <a href="https://fikom.umi.ac.id" target="_blank" rel="noopener" style="color: #ffffffff;">
                            Website FIKOM
                        </a>
                    </li>
                    <li>
                        <a href="https://umi.ac.id" target="_blank" rel="noopener" style="color: #ffffffff;">
                            Website UMI
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Lokasi Kami</h3>
                <div class="map-wrapper">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.791123008261!2d119.448235!3d-5.137305!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd3165008369%3A0x7af75b8baf265f2b!2sFakultas%20Ilmu%20Komputer%20UMI!5e0!3m2!1sid!2sus!4v1766106276722!5m2!1sid!2sus" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" title="Peta Lokasi FIKOM UMI">
                    </iframe>
                </div>
            </div>

        </div>

        <div class="copyright">
            <p>&copy; <?= date('Y'); ?> Laboratorium Fikom UMI. All rights reserved.</p>
        </div>
    </div>
</footer>

<a href="#top" id="backToTop" class="back-to-top" aria-label="Kembali ke atas">
    <i class="ri-arrow-up-line"></i>
</a>

<?php $mainJsPath = __DIR__ . '/../../../public/js/main.js'; ?>
<script src="<?= ASSETS_URL ?>/js/main.js?v=<?= file_exists($mainJsPath) ? filemtime($mainJsPath) : time() ?>" defer></script>

</body>
</html>