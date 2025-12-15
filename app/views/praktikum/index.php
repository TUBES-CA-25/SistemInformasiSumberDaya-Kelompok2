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
        
        // Fetch data dari API - gunakan path absolut
        const apiUrl = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2') 
            ? '/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib'
            : '/api/tata-tertib';
        
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
                    rulesList.innerHTML = '';
                    
                    // Loop setiap peraturan dari database
                    data.data.forEach(rule => {
                        const ruleItem = document.createElement('div');
                        ruleItem.className = 'rules-item';
                        
                        // Gunakan image dari database atau placeholder
                        const imageUrl = rule.gambar ? `/uploads/tata-tertib/${rule.gambar}` : 'https://placehold.co/400x300/7f8c8d/white?text=Peraturan';
                        
                        ruleItem.innerHTML = `
                            <div class="rules-image">
                                <img src="${imageUrl}" alt="${rule.namaFile}" onerror="this.src='https://placehold.co/400x300/7f8c8d/white?text=Peraturan'">
                            </div>
                            <div class="rules-content">
                                <h3>${escapeHtml(rule.namaFile)}</h3>
                                <p>${escapeHtml(rule.uraFile || 'Lihat peraturan lengkap untuk informasi detail.')}</p>
                            </div>
                        `;
                        
                        rulesList.appendChild(ruleItem);
                    });
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