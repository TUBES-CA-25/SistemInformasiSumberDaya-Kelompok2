<section class="resource-section">
    <div class="container">
        <div class="page-header">
            <h1>👥 Jejaring Alumni</h1>
            <p>Daftar alumni asisten laboratorium yang telah berkontribusi dan kini berkarier di berbagai industri.</p>
        </div>

        <div id="alumniContainer" style="margin-top: 40px;">
            <div style="text-align: center; padding: 60px 20px;">
                <div style="animation: spin 1s linear infinite; display: inline-block; font-size: 40px;">⟳</div>
                <p style="margin-top: 20px; color: #999;">Sedang memuat data alumni...</p>
            </div>
        </div>
    </div>
</section>

<style>
.alumni-group {
    margin: 40px 0;
}

.section-divider {
    font-size: 22px;
    font-weight: 700;
    color: #333;
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 3px solid #667eea;
    display: inline-block;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.profile-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
}

.profile-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(102, 126, 234, 0.2);
}

.profile-img {
    width: 100%;
    height: 250px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.profile-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.profile-info h3 {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: #333;
}

.profile-info p {
    font-size: 13px;
    color: #667eea;
    font-weight: 600;
    margin: 0 0 8px 0;
}

.profile-info small {
    font-size: 12px;
    color: #666;
    line-height: 1.4;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', loadAlumni);

function loadAlumni() {
    fetch(API_URL + '/alumni')
    .then(res => res.json())
    .then(response => {
        if ((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            // Group alumni by angkatan
            const groupedByAngkatan = {};
            
            response.data.forEach(alumni => {
                const year = alumni.angkatan || 'Tahun Tidak Diketahui';
                if (!groupedByAngkatan[year]) {
                    groupedByAngkatan[year] = [];
                }
                groupedByAngkatan[year].push(alumni);
            });

            // Sort years descending
            const sortedYears = Object.keys(groupedByAngkatan).sort((a, b) => b - a);

            let html = '';
            sortedYears.forEach(year => {
                html += `<div class="alumni-group">
                    <h3 class="section-divider">Angkatan ${year}</h3>
                    <div class="profile-grid">`;

                groupedByAngkatan[year].forEach(alumni => {
                    const fotoUrl = alumni.foto || 'https://placehold.co/300x300/95a5a6/white?text=Alumni';
                    const divisi = alumni.divisi || 'Asisten';
                    const pekerjaan = alumni.pekerjaan ? `🚀 ${alumni.pekerjaan}` : 'Belum bekerja';
                    
                    html += `
                        <a href="<?php echo BASE_URL; ?>/public/detail-alumni.php?id=${alumni.id}" class="profile-card">
                            <div class="profile-img">
                                <img src="${fotoUrl}" alt="${alumni.nama}" onerror="this.src='https://placehold.co/300x300/95a5a6/white?text=Alumni'">
                            </div>
                            <div class="profile-info">
                                <h3>${alumni.nama}</h3>
                                <p>Ex-${divisi}</p>
                                <small style="color: #666;">${pekerjaan}</small>
                            </div>
                        </a>
                    `;
                });

                html += `</div></div>`;
            });

            document.getElementById('alumniContainer').innerHTML = html;
        } else {
            document.getElementById('alumniContainer').innerHTML = `
                <div style="text-align: center; padding: 60px 20px; color: #999;">
                    <div style="font-size: 40px; margin-bottom: 20px;">📭</div>
                    <p>Belum ada data alumni.</p>
                </div>
            `;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('alumniContainer').innerHTML = `
            <div style="text-align: center; padding: 60px 20px; color: red;">
                <div style="font-size: 40px; margin-bottom: 20px;">⚠️</div>
                <p>Error: Gagal memuat data alumni</p>
            </div>
        `;
    });
}
</script>