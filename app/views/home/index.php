<section class="hero-section">
    <div class="container">
        <div class="hero-content reveal fade-left">
            <h1>Sistem Informasi Sumber Daya Laboratorium</h1>
            <p>Platform terintegrasi untuk manajemen praktikum, peminjaman fasilitas, dan pendataan asisten laboratorium Fakultas Ilmu Komputer secara efisien dan profesional.</p>
            <div class="btn-group">
                <a href="index.php?page=praktikum" class="btn-primary">Jelajahi Profil <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
        <div class="hero-image reveal fade-right">
            <img src="https://placehold.co/600x400/e2e8f0/1e293b?text=Digital+Laboratory" alt="Ilustrasi Lab">
        </div>
    </div>
</section>

<section class="visi-misi-section">
    <div class="container">
        <div class="section-header reveal fade-up">
            <span class="badge-pill">Tentang Kami</span>
            <h2>Komitmen & Tujuan</h2>
        </div>
        
        <div class="visi-misi-grid">
            <div class="vm-card reveal fade-up" style="transition-delay: 0.1s;">
                <div class="vm-icon-box">
                    <i class="ri-focus-2-line"></i>
                </div>
                <div class="vm-content">
                    <h3>Visi Laboratorium</h3>
                    <p>"Menjadi pusat unggulan pengembangan teknologi informasi yang adaptif terhadap industri 4.0 dan berlandaskan nilai-nilai integritas."</p>
                </div>
            </div>

            <div class="vm-card reveal fade-up" style="transition-delay: 0.2s;">
                <div class="vm-icon-box">
                    <i class="ri-list-settings-line"></i>
                </div>
                <div class="vm-content">
                    <h3>Misi Utama</h3>
                    <ul class="professional-list">
                        <li><i class="ri-checkbox-circle-line"></i> Penyelenggaraan praktikum berbasis kompetensi industri.</li>
                        <li><i class="ri-checkbox-circle-line"></i> Penyediaan infrastruktur riset yang mutakhir.</li>
                        <li><i class="ri-checkbox-circle-line"></i> Pengembangan soft-skill asisten laboratorium.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-slider-section">
    <div class="slider-container reveal fade-up">
        
        <button class="slider-btn prev-btn" onclick="moveSlide(-1)">
            <i class="ri-arrow-left-s-line"></i>
        </button>
        <button class="slider-btn next-btn" onclick="moveSlide(1)">
            <i class="ri-arrow-right-s-line"></i>
        </button>

        <div class="slider-track" id="sliderTrack">
            <div class="slide">
                <div class="slide-content-wrapper"> 
                    <div class="slide-image">
                        <img src="https://placehold.co/800x600/e2e8f0/1e293b?text=Kompetensi+Digital" alt="Image">
                    </div>
                    <div class="slide-text">
                        <h2>Pusat Kompetensi</h2>
                        <p>Laboratorium FIKOM UMI hadir sebagai pusat pengembangan hard skill unggulan dengan kurikulum adaptif.</p>
                    </div>
                </div>
            </div>
            <div class="slide">
                <div class="slide-content-wrapper">
                    <div class="slide-image">
                        <img src="https://placehold.co/800x600/e2e8f0/1e293b?text=Infrastruktur+IT" alt="Image">
                    </div>
                    <div class="slide-text">
                        <h2>Infrastruktur Modern</h2>
                        <p>Menyediakan laboratorium spesifik (RPL, Jaringan, Multimedia) dengan perangkat spesifikasi tinggi.</p>
                    </div>
                </div>
            </div>
             <div class="slide">
                <div class="slide-content-wrapper">
                    <div class="slide-image">
                        <img src="https://placehold.co/800x600/e2e8f0/1e293b?text=Riset+Inovasi" alt="Image">
                    </div>
                    <div class="slide-text">
                        <h2>Riset & Inovasi</h2>
                        <p>Mendukung kegiatan penelitian mahasiswa dan dosen dengan fasilitas komputasi yang memadai.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-dots">
            <button class="dot active" onclick="goToSlide(0)"></button>
            <button class="dot" onclick="goToSlide(1)"></button>
            <button class="dot" onclick="goToSlide(2)"></button>
        </div>
    </div>
</section>

<section class="video-section">
    <div class="container reveal fade-up">
        <div class="section-header">
            <span class="badge-pill">Profil Video</span>
            <h2>Mengapa Memilih Kami?</h2>
        </div>
        <div class="video-placeholder">
            <img src="https://placehold.co/1000x500/f1f5f9/94a3b8?text=PLAY+PROFILE+VIDEO" alt="Video Player">
        </div>
    </div>
</section>

<section class="info-section">
    <div class="container">
        <div class="section-header reveal fade-up">
            <span class="badge-pill">Layanan</span>
            <h2>Akses Informasi Cepat</h2>
        </div>
        
        <div class="info-grid">
            <div class="info-card reveal fade-up" style="transition-delay: 0.1s;">
                <div class="card-icon"><i class="ri-user-add-line"></i></div>
                <h3>Rekrutmen Asisten</h3>
                <p>Informasi seleksi, syarat, dan ketentuan bagi calon asisten laboratorium.</p>
                <a href="index.php?page=asisten" class="link-arrow">Lihat Detail <i class="ri-arrow-right-line"></i></a>
            </div>

            <div class="info-card reveal fade-up" style="transition-delay: 0.2s;">
                <div class="card-icon"><i class="ri-calendar-check-line"></i></div>
                <h3>Jadwal Praktikum</h3>
                <p>Cek jadwal sesi praktikum semester berjalan secara real-time.</p>
                <a href="index.php?page=jadwal" class="link-arrow">Lihat Jadwal <i class="ri-arrow-right-line"></i></a>
            </div>

            <div class="info-card reveal fade-up" style="transition-delay: 0.3s;">
                <div class="card-icon"><i class="ri-computer-line"></i></div>
                <h3>Fasilitas & Riset</h3>
                <p>Prosedur peminjaman ruang laboratorium dan alat untuk kegiatan riset.</p>
                <a href="index.php?page=laboratorium" class="link-arrow">Ajukan Pinjaman <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="partner-section">
    <div class="slider">
        <div class="slide-track">
            
            <div class="partner-slide"><div class="partner-box">Partner 1</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 2</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 3</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 4</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 5</div></div>

            <div class="partner-slide"><div class="partner-box">Partner 1</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 2</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 3</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 4</div></div>
            <div class="partner-slide"><div class="partner-box">Partner 5</div></div>

        </div>
    </div>
</section>

<script>
    // 1. ANIMASI SCROLL REVEAL
    window.addEventListener('scroll', reveal);
    function reveal() {
        var reveals = document.querySelectorAll('.reveal');
        for (var i = 0; i < reveals.length; i++) {
            var windowheight = window.innerHeight;
            var revealtop = reveals[i].getBoundingClientRect().top;
            var revealpoint = 100;
            if (revealtop < windowheight - revealpoint) {
                reveals[i].classList.add('active');
            }
        }
    }
    reveal();

    // 2. SLIDER LOGIC (FEATURES) - TIDAK MENGGANGGU PARTNER SLIDER
    let currentSlide = 0;
    const track = document.getElementById('sliderTrack');
    const slides = document.querySelectorAll('.slide-content-wrapper'); // Diperbaiki selectornya agar spesifik
    const dots = document.querySelectorAll('.dot');
    
    // Hitung jumlah slide berdasarkan dot karena struktur slide features berbeda dengan partner
    const totalSlides = dots.length; 

    function updateSlider() {
        if(track){
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            dots.forEach(dot => dot.classList.remove('active'));
            if(dots[currentSlide]) {
                dots[currentSlide].classList.add('active');
            }
        }
    }

    function moveSlide(direction) {
        currentSlide += direction;
        if (currentSlide < 0) {
            currentSlide = totalSlides - 1;
        } else if (currentSlide >= totalSlides) {
            currentSlide = 0;
        }
        updateSlider();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlider();
    }

    setInterval(() => {
        moveSlide(1);
    }, 5000);
</script>