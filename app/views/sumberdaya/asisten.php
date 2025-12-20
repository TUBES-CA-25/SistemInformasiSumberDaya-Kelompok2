<section class="sumberdaya-section">
    <div class="container">
        
        <header class="page-header">
            <span class="header-badge">Struktur Organisasi 2025</span>
            <h1>Personel Laboratorium</h1>
            <p>Fakultas Ilmu Komputer Universitas Muslim Indonesia</p>
        </header>

        <span class="section-label">Struktural Utama</span>
        
        <a href="index.php?page=detail&id=1" class="card-link">
            <div class="exec-card">
                <div class="exec-photo">
                    <div class="exec-placeholder">NK</div>
                </div>
                
                <div class="exec-info">
                    <span class="exec-badge">Koordinator Laboratorium</span>
                    <h3>Nahwa Kaka S.</h3>
                    <span class="exec-role">Fullstack Web Developer</span>
                    
                    <div class="exec-footer">
                        <div class="meta-item">
                            <i class="ri-mail-line"></i> nahwa@umi.ac.id
                        </div>
                        <div class="meta-item">
                            <i class="ri-graduation-cap-line"></i> Teknik Informatika
                        </div>
                    </div>
                </div>
            </div>
        </a>


        <span class="section-label">Tim Asisten</span>
        
        <div class="staff-grid">
            
            <a href="index.php?page=detail&id=2" class="card-link">
                <div class="staff-card">
                    <div class="staff-photo-box">
                        <div class="staff-placeholder">AS</div>
                    </div>
                    <div class="staff-content">
                        <div class="staff-name">Andi Saputra</div>
                        <span class="staff-role">Network Engineer</span>
                        
                        <div class="staff-footer">
                            <div class="meta-item">
                                <i class="ri-mail-line"></i> andi@umi.ac.id
                            </div>
                            <div class="meta-item">
                                <i class="ri-graduation-cap-line"></i> Teknik Informatika
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="index.php?page=detail&id=3" class="card-link">
                <div class="staff-card">
                    <div class="staff-photo-box">
                        <div class="staff-placeholder">SA</div>
                    </div>
                    <div class="staff-content">
                        <div class="staff-name">Siti Aminah</div>
                        <span class="staff-role">Data Scientist</span>
                        
                        <div class="staff-footer">
                            <div class="meta-item">
                                <i class="ri-mail-line"></i> siti@umi.ac.id
                            </div>
                            <div class="meta-item">
                                <i class="ri-graduation-cap-line"></i> Sistem Informasi
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            
            <a href="index.php?page=detail&id=4" class="card-link">
                <div class="staff-card">
                    <div class="staff-photo-box">
                        <div class="staff-placeholder">BS</div>
                    </div>
                    <div class="staff-content">
                        <div class="staff-name">Budi Santoso</div>
                        <span class="staff-role">UI/UX Designer</span>
                        
                        <div class="staff-footer">
                            <div class="meta-item">
                                <i class="ri-mail-line"></i> budi@umi.ac.id
                            </div>
                            <div class="meta-item">
                                <i class="ri-graduation-cap-line"></i> Teknik Informatika
                            </div>
                        </div>
                    </div>
                </div>
            </a>

        </div>


        <span class="section-label">Calon Asisten</span>

        <div class="staff-grid">
            
            <a href="index.php?page=detail&id=5" class="card-link">
                <div class="staff-card">
                    <div class="staff-photo-box">
                        <div class="staff-placeholder">DL</div>
                    </div>
                    <div class="staff-content">
                        <div class="staff-name">Dewi Lestari</div>
                        <span class="staff-role">Mobile Developer</span>
                        
                        <div class="staff-footer">
                            <div class="meta-item">
                                <i class="ri-mail-line"></i> dewi@umi.ac.id
                            </div>
                            <div class="meta-item">
                                <i class="ri-graduation-cap-line"></i> Sistem Informasi
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="index.php?page=detail&id=6" class="card-link">
                <div class="staff-card">
                    <div class="staff-photo-box">
                        <div class="staff-placeholder">RF</div>
                    </div>
                    <div class="staff-content">
                        <div class="staff-name">Rahmat Fauzi</div>
                        <span class="staff-role">Backend Developer</span>
                        
                        <div class="staff-footer">
                            <div class="meta-item">
                                <i class="ri-mail-line"></i> rahmat@umi.ac.id
                            </div>
                            <div class="meta-item">
                                <i class="ri-graduation-cap-line"></i> Teknik Informatika
                            </div>
                        </div>
                    </div>
                </div>
            </a>

        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadAsisten();
        loadKoordinator();
    });

    function loadKoordinator() {
        const koordinatorCard = document.getElementById('koordinatorCard');
        
        // Fetch data dari API
        const apiUrl = API_URL + '/asisten';
        
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data.length > 0) {
                    // Cari koordinator
                    const koordinator = data.data.find(a => a.isKoordinator == 1);
                    
                    if (koordinator) {
                        // Gunakan image dari database atau placeholder
                        let imageUrl;
                        if (koordinator.foto) {
                            const baseUrl = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2')
                                ? '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/'
                                : '/assets/uploads/';
                            imageUrl = baseUrl + koordinator.foto;
                        } else {
                            imageUrl = 'https://placehold.co/400x400/7f8c8d/white?text=Koordinator';
                        }
                        
                        koordinatorCard.innerHTML = `
                            <div class="coord-img">
                                <img src="${imageUrl}" alt="${koordinator.nama}" onerror="this.src='https://placehold.co/400x400/7f8c8d/white?text=Koordinator'">
                            </div>
                            <div class="coord-info">
                                <span class="badge">Koordinator Lab</span>
                                <h2>${escapeHtml(koordinator.nama)}</h2>
                                <p>Bertanggung jawab atas seluruh operasional harian asisten dan jadwal praktikum. ${escapeHtml(koordinator.jurusan ? 'Mahasiswa dari ' + koordinator.jurusan : '')}</p>
                                <a href="${window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2') ? '/SistemInformasiSumberDaya-Kelompok2' : ''}/asisten/${koordinator.idAsisten}" class="btn-sm">Lihat Profil Lengkap →</a>
                            </div>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error('Error loading koordinator:', error);
            });
    }

    function loadAsisten() {
        const asistenList = document.getElementById('asistenList');
        
        // Fetch data dari API
        const apiUrl = API_URL + '/asisten';
        
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' && data.data.length > 0) {
                    // Clear loading message
                    asistenList.innerHTML = '';
                    
                    // Loop setiap asisten dari database (exclude koordinator)
                    data.data.forEach(asisten => {
                        // Skip koordinator, hanya tampilkan anggota asisten
                        if (asisten.isKoordinator == 1) return;
                        
                        const asistenCard = document.createElement('a');
                        asistenCard.className = 'profile-card';
                        const baseUrl = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2')
                            ? '/SistemInformasiSumberDaya-Kelompok2'
                            : '';
                        asistenCard.href = baseUrl + '/asisten/' + asisten.idAsisten;
                        
                        // Gunakan image dari database atau placeholder
                        // Path gambar: /assets/uploads/{filename}
                        let imageUrl;
                        if (asisten.foto) {
                            const baseUrl = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2')
                                ? '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/'
                                : '/assets/uploads/';
                            imageUrl = baseUrl + asisten.foto;
                        } else {
                            imageUrl = 'https://placehold.co/300x300/bdc3c7/white?text=Asisten';
                        }
                        
                        asistenCard.innerHTML = `
                            <div class="profile-img">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Crect fill='%23ddd' width='300' height='300'/%3E%3C/svg%3E" 
                                     data-src="${imageUrl}" 
                                     alt="${asisten.nama}" 
                                     loading="lazy"
                                     onerror="this.src='https://placehold.co/300x300/bdc3c7/white?text=Asisten'">
                            </div>
                            <div class="profile-info">
                                <h3>${escapeHtml(asisten.nama)}</h3>
                                <p>${escapeHtml(asisten.jurusan || 'Asisten')}</p>
                            </div>
                        `;
                        
                        asistenList.appendChild(asistenCard);
                    });
                    
                    // Lazy load gambar menggunakan Intersection Observer
                    const images = document.querySelectorAll('img[data-src]');
                    const imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.src = img.dataset.src;
                                img.removeAttribute('data-src');
                                observer.unobserve(img);
                            }
                        });
                    });
                    images.forEach(img => imageObserver.observe(img));
                } else {
                    // Jika tidak ada data
                    asistenList.innerHTML = `
                        <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                            <p>Belum ada asisten yang ditambahkan. Silahkan hubungi administrator.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading asisten:', error);
                asistenList.innerHTML = `
                    <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                        <p>Gagal memuat data asisten. Silahkan coba lagi nanti.</p>
                    </div>
                `;
            });
    }

    // Helper function untuk escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>