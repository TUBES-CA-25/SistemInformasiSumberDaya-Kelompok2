<footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="<?php echo ASSETS_URL; ?>/images/logo-iclabs.png" alt="Logo" style="height: 40px; object-fit: contain;">
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
                    <h3>Menu Pintas</h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo PUBLIC_URL; ?>/home">Beranda</a></li>
                        <li><a href="<?php echo PUBLIC_URL; ?>/jadwal">Jadwal Praktikum</a></li>
                        <li><a href="<?php echo PUBLIC_URL; ?>/laboratorium">Fasilitas Lab</a></li>
                        <li><a href="<?php echo PUBLIC_URL; ?>/alumni">Alumni</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Lokasi Kami</h3>
                    <div class="map-wrapper">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.791123008261!2d119.448235!3d-5.137305!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd3165008369%3A0x7af75b8baf265f2b!2sFakultas%20Ilmu%20Komputer%20UMI!5e0!3m2!1sid!2sus!4v1766106276722!5m2!1sid!2sus" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>

            <div class="copyright">
                <p>© <?= date('Y'); ?> Laboratorium Fikom UMI. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <a href="#" id="backToTop" class="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </a>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <script src="<?= ASSETS_URL ?>/js/main.js"></script>

</body>
</html>