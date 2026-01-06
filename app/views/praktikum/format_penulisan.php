<section class="rules-section">
    <div class="container">
        <header class="page-header">
            <span class="header-badge">Pedoman ICLabs 2025</span>
            <h1>Format Penulisan Tugas</h1>
            <p>Pedoman teknis penyusunan laporan praktikum sesuai standar Laboratorium Terpadu FIKOM UMI.</p>
        </header>

        <div id="pedoman-container" class="rules-grid">
            <div class="text-center py-10 w-full col-span-full">
                <p class="text-gray-500">Memuat data...</p>
            </div>
        </div>

        <div id="unduhan-section" class="downloads-section" style="margin-top: 4rem; display: none;">
            <header class="section-header" style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 2rem; font-weight: 700; color: #1e293b;">Pusat Unduhan</h2>
                <p style="color: #64748b;">Dapatkan berkas format laporan dan dokumen pendukung lainnya.</p>
            </header>
            
            <div id="unduhan-container" class="downloads-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                <!-- Diterapkan via JS -->
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pedomanContainer = document.getElementById('pedoman-container');
    const unduhanContainer = document.getElementById('unduhan-container');
    const unduhanSection = document.getElementById('unduhan-section');
    const assetsUrl = '<?= ASSETS_URL ?>';
    const apiUrl = '<?= BASE_URL ?>/api.php/formatpenulisan';

    async function loadContent() {
        try {
            const response = await fetch(apiUrl);
            const result = await response.json();
            
            if (result.status === 'success' || result.code === 200) {
                renderContent(result.data);
            } else {
                pedomanContainer.innerHTML = '<div class="col-span-full text-center py-10"><p>Gagal memuat data.</p></div>';
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            pedomanContainer.innerHTML = '<div class="col-span-full text-center py-10"><p>Terjadi kesalahan sistem.</p></div>';
        }
    }

    function renderContent(data) {
        if (!data || data.length === 0) {
            pedomanContainer.innerHTML = `
                <article class="rule-card">
                    <div class="rule-icon icon-blue">
                        <i class="ri-layout-line"></i>
                    </div>
                    <h3>Belum Ada Data</h3>
                    <p>Konten pedoman penulisan sedang disiapkan.</p>
                </article>
            `;
            return;
        }

        // Render Pedoman
        const pedoman = data.filter(item => (item.kategori || 'pedoman') === 'pedoman');
        if (pedoman.length > 0) {
            pedomanContainer.innerHTML = pedoman.map(info => `
                <article class="rule-card">
                    <div class="rule-icon ${info.warna || 'icon-blue'}">
                        <i class="${info.icon || 'ri-layout-line'}"></i>
                    </div>
                    <h3>${info.judul}</h3>
                    <ul class="rule-list">
                        ${(info.deskripsi || '').split('\n')
                            .filter(line => line.trim() !== '')
                            .map(line => `<li><i class="ri-checkbox-circle-fill"></i> ${line.trim()}</li>`)
                            .join('')}
                    </ul>
                </article>
            `).join('');
        } else {
            pedomanContainer.innerHTML = '<div class="col-span-full text-center py-10"><p>Belum ada pedoman.</p></div>';
        }

        // Render Unduhan
        const unduhan = data.filter(item => item.kategori === 'unduhan');
        if (unduhan.length > 0) {
            unduhanSection.style.display = 'block';
            unduhanContainer.innerHTML = unduhan.map(item => `
                <div class="download-item" style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); display: flex; align-items: center; gap: 1rem;">
                    <div class="file-icon" style="width: 45px; height: 45px; background: #eff6ff; color: #2563eb; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="ri-file-download-line"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="font-weight: 600; color: #1e293b; margin-bottom: 0.25rem; font-size: 1rem;">${item.judul}</h4>
                        <div class="download-actions" style="display: flex; gap: 0.5rem;">
                            ${item.file ? `<a href="${assetsUrl}/uploads/format_penulisan/${item.file}" target="_blank" class="download-btn" style="font-size: 0.8rem; color: #2563eb; font-weight: 500;">Unduh PDF</a>` : ''}
                            ${item.link_external ? `<a href="${item.link_external}" target="_blank" class="link-btn" style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Buka Link</a>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            unduhanSection.style.display = 'none';
        }
    }

    loadContent();
});
</script>