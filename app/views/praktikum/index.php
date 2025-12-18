<section class="praktikum-section">
    <div class="container">
        <div class="page-header">
            <h1>Peraturan dan Ketentuan Lab</h1>
            <p>Mahasiswa wajib mematuhi seluruh tata tertib berikut demi kenyamanan dan keselamatan bersama.</p>
        </div>

        <div class="rules-list" id="rulesList">
            <!-- Data akan dimuat dari API -->
            <div style="text-align: center; padding: 40px;">
                <p>Memuat data peraturan...</p>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadPeraturanLab();
    });

    function loadPeraturanLab() {
        const rulesList = document.getElementById('rulesList');
        
        // Use API_URL constant
        fetch(API_URL + '/peraturan-lab')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' && data.data.length > 0) {
                    // Clear loading message
                    rulesList.innerHTML = '';
                    
                    // Loop setiap peraturan dari database
                    data.data.forEach(rule => {
                        const ruleItem = document.createElement('div');
                        ruleItem.className = 'rules-item';
                        
                        // Gunakan image dari database atau placeholder
                        let imageUrl;
                        if (rule.gambar) {
                            const baseUrl = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2')
                                ? '/SistemInformasiSumberDaya-Kelompok2/storage/uploads/'
                                : '/storage/uploads/';
                            imageUrl = baseUrl + rule.gambar;
                        } else {
                            imageUrl = 'https://placehold.co/400x300/7f8c8d/white?text=Peraturan';
                        }
                        
                        ruleItem.innerHTML = `
                            <div class="rules-image">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect fill='%23ddd' width='400' height='300'/%3E%3C/svg%3E" 
                                     data-src="${imageUrl}" 
                                     alt="${rule.judul}" 
                                     loading="lazy"
                                     onerror="this.src='https://placehold.co/400x300/7f8c8d/white?text=Peraturan'">
                            </div>
                            <div class="rules-content">
                                <h3>${escapeHtml(rule.judul)}</h3>
                                <p>${escapeHtml(rule.deskripsi || 'Lihat peraturan lengkap untuk informasi detail.')}</p>
                            </div>
                        `;
                        
                        rulesList.appendChild(ruleItem);
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
                    rulesList.innerHTML = `
                        <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                            <p>Belum ada peraturan yang ditambahkan. Silahkan hubungi administrator.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading peraturan:', error);
                rulesList.innerHTML = `
                    <div style="text-align: center; padding: 40px; grid-column: 1/-1;">
                        <p>Gagal memuat data peraturan. Silahkan coba lagi nanti.</p>
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