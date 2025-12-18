<section class="resource-section">
    <div class="container">
        <div class="page-header">
            <h1>Asisten Laboratorium</h1>
            <p>Mahasiswa berprestasi yang berdedikasi membantu kegiatan praktikum.</p>
        </div>

        <div class="coordinator-section">
            <div class="coord-card" id="koordinatorCard">
                <div class="coord-img">
                    <img src="https://placehold.co/400x400/7f8c8d/white?text=Loading" alt="Koordinator">
                </div>
                <div class="coord-info">
                    <span class="badge">Koordinator Lab</span>
                    <h2>Memuat data...</h2>
                    <p>Bertanggung jawab atas seluruh operasional harian asisten dan jadwal praktikum.</p>
                </div>
            </div>
        </div>

        <h3 class="section-divider">Anggota Asisten</h3>

        <div class="profile-grid" id="asistenList">
            <!-- Data akan dimuat dari API -->
            <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                <p>Memuat data asisten...</p>
            </div>
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
                                <a href="/profil.php?id=${koordinator.idAsisten}" class="btn-sm">Lihat Profil Lengkap →</a>
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
                        asistenCard.href = '/profil.php?id=' + asisten.idAsisten;
                        
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