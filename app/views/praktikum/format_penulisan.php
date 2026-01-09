<section class="rules-section">
    <div class="container">
        <header class="page-header">
            <span class="header-badge">Pedoman ICLabs 2025</span>
            <h1>Format Penulisan Tugas</h1>
            <p>Unduh berkas fisik laporan praktikum sesuai standar FIKOM UMI.</p>
        </header>

        <div id="pedoman-container" class="rules-grid">
            <div class="text-center py-10 w-full col-span-full">
                <p class="text-gray-500 italic">Sinkronisasi pedoman...</p>
            </div>
        </div>

        <div id="unduhan-section" class="downloads-section" style="margin-top: 4rem; display: none;">
            <header class="section-header" style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 2rem; font-weight: 700; color: #1e293b;">Pusat Unduhan</h2>
                <p style="color: #64748b;">Klik tombol di bawah untuk mengunduh berkas fisik secara langsung.</p>
            </header>
            
            <div id="unduhan-container" class="downloads-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. DEFINISI JALUR (PATH) ABSOLUT
    // Kita pastikan mengambil URL dasar dari konstanta PHP
    const baseUrl = '<?= BASE_URL ?>';
    const assetsUrl = '<?= ASSETS_URL ?>';
    const apiUrl = baseUrl + '/api.php/formatpenulisan';

    async function loadContent() {
        try {
            const response = await fetch(apiUrl);
            const result = await response.json();
            
            if (result.status === 'success' || result.code === 200) {
                renderContent(result.data);
            }
        } catch (error) {
            console.error('API Error:', error);
        }
    }

    function renderContent(data) {
        const pedomanContainer = document.getElementById('pedoman-container');
        const unduhanContainer = document.getElementById('unduhan-container');
        const unduhanSection = document.getElementById('unduhan-section');

        // Render Bagian Pedoman
        const pedoman = data.filter(item => (item.kategori || 'pedoman').toLowerCase() === 'pedoman');
        pedomanContainer.innerHTML = pedoman.map(info => `
            <article class="rule-card">
                <div class="rule-icon ${info.warna || 'icon-blue'}">
                    <i class="${info.icon || 'ri-layout-line'}"></i>
                </div>
                <h3>${info.judul}</h3>
                <ul class="rule-list">
                    ${(info.deskripsi || '').split('\n').filter(l => l.trim()).map(l => `<li><i class="ri-checkbox-circle-fill"></i> ${l.trim()}</li>`).join('')}
                </ul>
            </article>
        `).join('');

        // Render Bagian Unduhan (SINKRONISASI FILE FISIK)
        const unduhan = data.filter(item => (item.kategori || '').toLowerCase() === 'unduhan');
        if (unduhan.length > 0) {
            unduhanSection.style.display = 'block';
            unduhanContainer.innerHTML = unduhan.map(item => {
                
               const fileName = item.file ? item.file.trim() : '';
                const downloadPath = `assets/uploads/format_penulisan/${fileName}`;

                return `
                    <div class="download-item" style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1rem; border: 1px solid #f1f5f9;">
                        <div class="file-icon" style="width: 48px; height: 48px; background: #eff6ff; color: #2563eb; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="ri-file-text-line"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 5px; font-size: 1rem;">${item.judul}</h4>
                            <div style="display: flex; gap: 15px;">
                                ${item.file ? `
                                    <a href="${downloadPath}" 
                                       target="_blank" 
                                       download="${fileName}"
                                       style="color: #2563eb; font-size: 0.85rem; font-weight: 700; text-decoration: none;">
                                        <i class="ri-download-cloud-line"></i> Unduh Berkas
                                    </a>` : ''}
                                
                                ${item.link_external ? `
                                    <a href="${item.link_external}" 
                                       target="_blank" 
                                       style="color: #64748b; font-size: 0.85rem; font-weight: 700; text-decoration: none;">
                                        <i class="ri-external-link-line"></i> Buka Link
                                    </a>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    loadContent();
});
</script>