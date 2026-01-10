<section class="rules-section">
    <div class="container">
        <header class="page-header">
            <span class="header-badge">Pedoman ICLabs 2025</span>
            <h1>Format Penulisan Tugas</h1>
            <p>Standar penyusunan laporan, makalah, dan tugas praktikum di lingkungan Fakultas Ilmu Komputer UMI.</p>
        </header>

        <div id="pedoman-container" class="rules-grid">
            <div class="col-span-full" style="grid-column: 1/-1; text-align: center; padding: 60px; color: #94a3b8;">
                <i class="fas fa-circle-notch fa-spin fa-3x" style="color: #cbd5e1; margin-bottom: 20px;"></i>
                <p>Memuat data pedoman...</p>
            </div>
        </div>

        <div id="unduhan-section" class="downloads-section" style="display: none;">
            <header class="section-header" style="text-align: center; margin-bottom: 2rem;">
                <span class="badge-pill" style="margin-bottom: 15px; background: #ecfdf5; color: #059669; border-color: #a7f3d0;">
                    <i class="fas fa-cloud-download-alt" style="margin-right: 5px;"></i> Resources
                </span>
                <h2>Pusat Unduhan Berkas</h2>
                <p style="color: #64748b; max-width: 600px; margin: 0 auto;">
                    Dapatkan template resmi dan dokumen pendukung praktikum. Silakan unduh sesuai kebutuhan mata kuliah Anda.
                </p>
            </header>
            
            <div id="unduhan-container" class="downloads-grid">
                </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Definisi URL API
    const baseUrl = '<?= BASE_URL ?>';
    const apiUrl = baseUrl + '/api.php/formatpenulisan';

    async function loadContent() {
        try {
            const response = await fetch(apiUrl);
            const result = await response.json();
            
            if (result.status === 'success' || result.code === 200) {
                renderContent(result.data);
            } else {
                showEmptyState();
            }
        } catch (error) {
            console.error('API Error:', error);
            showErrorState();
        }
    }

    function renderContent(data) {
        const pedomanContainer = document.getElementById('pedoman-container');
        const unduhanContainer = document.getElementById('unduhan-container');
        const unduhanSection = document.getElementById('unduhan-section');

        // 1. RENDER PEDOMAN (Guidelines)
        const pedoman = data.filter(item => (item.kategori || 'pedoman').toLowerCase() === 'pedoman');
        
        if (pedoman.length > 0) {
            pedomanContainer.innerHTML = pedoman.map(info => `
                <article class="rule-card">
                    <div class="rule-icon icon-blue">
                        <i class="${info.icon || 'ri-book-open-line'}"></i>
                    </div>
                    
                    <h3>${info.judul}</h3>
                    
                    <ul class="rule-list">
                        ${(info.deskripsi || '')
                            .split('\n')
                            .filter(l => l.trim())
                            .map(l => `<li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>${l.trim()}</span></li>`)
                            .join('')}
                    </ul>
                </article>
            `).join('');
        } else {
            pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align: center; color: #64748b;">Belum ada data pedoman.</div>`;
        }

        // 2. RENDER UNDUHAN (Downloads)
        const unduhan = data.filter(item => (item.kategori || '').toLowerCase() === 'unduhan');
        
        if (unduhan.length > 0) {
            unduhanSection.style.display = 'block'; // Tampilkan section jika ada data
            
            unduhanContainer.innerHTML = unduhan.map(item => {
                const fileName = item.file ? item.file.trim() : '';
                // Path file disesuaikan dengan struktur folder Anda
                const downloadPath = `assets/uploads/format_penulisan/${fileName}`;
                
                // Tentukan ekstensi file untuk ikon yang sesuai (Opsional)
                let fileIcon = 'ri-file-text-line';
                if(fileName.endsWith('.pdf')) fileIcon = 'ri-file-pdf-line';
                if(fileName.endsWith('.doc') || fileName.endsWith('.docx')) fileIcon = 'ri-file-word-line';
                if(fileName.endsWith('.zip') || fileName.endsWith('.rar')) fileIcon = 'ri-file-zip-line';

                return `
                    <div class="download-card">
                        <div class="file-icon-box">
                            <i class="${fileIcon}"></i>
                        </div>
                        
                        <div class="download-content">
                            <h4>${item.judul}</h4>
                            
                            <div class="file-meta">
                                <i class="ri-information-line"></i> Dokumen Resmi ICLabs
                            </div>

                            <div class="action-buttons">
                                ${item.file ? `
                                    <a href="${downloadPath}" 
                                       target="_blank" 
                                       download="${fileName}"
                                       class="btn-download">
                                        <i class="ri-download-cloud-2-fill"></i> Unduh
                                    </a>` : ''}
                                
                                ${item.link_external ? `
                                    <a href="${item.link_external}" 
                                       target="_blank" 
                                       class="btn-external">
                                        <i class="ri-external-link-line"></i> Link Drive
                                    </a>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    function showEmptyState() {
        document.getElementById('pedoman-container').innerHTML = 
            `<div style="grid-column: 1/-1; text-align:center; padding:40px;"><p>Data tidak ditemukan.</p></div>`;
    }

    function showErrorState() {
        document.getElementById('pedoman-container').innerHTML = 
            `<div style="grid-column: 1/-1; text-align:center; padding:40px; color:#ef4444;"><p>Gagal memuat data dari server.</p></div>`;
    }

    loadContent();
});
</script>