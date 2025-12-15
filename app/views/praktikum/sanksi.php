<section class="praktikum-section">
    <div class="container">
        <div class="page-header">
            <h1>Sanksi Pelanggaran Lab</h1>
            <p>Daftar konsekuensi yang berlaku jika praktikan melanggar tata tertib laboratorium.</p>
        </div>

        <div class="rules-list" id="sanksiList">
            <!-- Data akan dimuat dari API -->
            <div style="text-align: center; padding: 40px;">
                <p>Memuat data sanksi...</p>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadSanksiLab();
    });

    function loadSanksiLab() {
        const sanksiList = document.getElementById('sanksiList');
        
        // Fetch data dari API - gunakan path absolut
        const apiUrl = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2') 
            ? '/SistemInformasiSumberDaya-Kelompok2/public/api.php/sanksi-lab'
            : '/api/sanksi-lab';
        
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
                    sanksiList.innerHTML = '';
                    
                    // Loop setiap sanksi dari database
                    data.data.forEach(sanksi => {
                        const sanksiItem = document.createElement('div');
                        sanksiItem.className = 'rules-item';
                        
                        // Gunakan image dari database atau placeholder
                        // Path gambar: /SistemInformasiSumberDaya-Kelompok2/storage/uploads/{filename}
                        let imageUrl;
                        if (sanksi.gambar) {
                            const baseUrl = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2')
                                ? '/SistemInformasiSumberDaya-Kelompok2/storage/uploads/'
                                : '/storage/uploads/';
                            imageUrl = baseUrl + sanksi.gambar;
                        } else {
                            imageUrl = 'https://placehold.co/400x300/c0392b/white?text=Sanksi';
                        }
                        
                        sanksiItem.innerHTML = `
                            <div class="rules-image">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect fill='%23ddd' width='400' height='300'/%3E%3C/svg%3E" 
                                     data-src="${imageUrl}" 
                                     alt="${sanksi.judul}" 
                                     loading="lazy"
                                     onerror="this.src='https://placehold.co/400x300/c0392b/white?text=Sanksi'">
                            </div>
                            <div class="rules-content">
                                <h3>${escapeHtml(sanksi.judul)}</h3>
                                <p>${escapeHtml(sanksi.deskripsi || 'Lihat peraturan lengkap untuk informasi detail.')}</p>
                            </div>
                        `;
                        
                        sanksiList.appendChild(sanksiItem);
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
                    sanksiList.innerHTML = `
                        <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                            <p>Belum ada sanksi yang ditambahkan. Silahkan hubungi administrator.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading sanksi:', error);
                sanksiList.innerHTML = `
                    <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                        <p>Gagal memuat data sanksi. Silahkan coba lagi nanti.</p>
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