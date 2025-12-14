<section class="profile-detail-section">
    <div class="container">
        <a href="/SistemManagementSumberDaya/public/alumni.php" class="back-link">← Kembali ke Daftar Alumni</a>

        <div id="detailContainer">
            <div style="text-align: center; padding: 100px 20px;">
                <p style="color: #999;">Sedang memuat detail alumni...</p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', loadAlumniDetail);

function loadAlumniDetail() {
    const params = new URLSearchParams(window.location.search);
    const alumniId = params.get('id');

    if (!alumniId) {
        document.getElementById('detailContainer').innerHTML = '<p style="color: red;">Error: ID Alumni tidak ditemukan</p>';
        return;
    }

    fetch(`/SistemManagementSumberDaya/public/api.php/alumni/${alumniId}`)
    .then(res => res.json())
    .then(response => {
        if ((response.status === 'success' || response.code === 200) && response.data) {
            const alumni = response.data;
            
            const html = `
                <div class="detail-card">
                    <div class="detail-left">
                        <img src="${alumni.foto || 'https://placehold.co/400x500'}" alt="${alumni.nama}">
                    </div>

                    <div class="detail-right">
                        <span class="badge-role">Alumni Angkatan ${alumni.angkatan || '—'}</span>
                        <h1 style="margin-top: 10px;">${alumni.nama}</h1>
                        <p class="current-job">Saat ini bekerja sebagai <strong>${alumni.pekerjaan || 'Belum bekerja'}</strong>${alumni.perusahaan ? ` di <strong>${alumni.perusahaan}</strong>` : ''}</p>

                        <div class="testimony-box">
                            <h3>💬 Kesan & Pesan</h3>
                            <p>"${alumni.kesan_pesan || 'Tidak ada kesan/pesan'}"</p>
                        </div>
                        
                        <div class="detail-meta">
                            <div class="meta-item">
                                <strong>Divisi Dahulu</strong>
                                <p>${alumni.divisi || '—'}</p>
                            </div>
                            <div class="meta-item">
                                <strong>Tahun Lulus</strong>
                                <p>${alumni.tahun_lulus || '—'}</p>
                            </div>
                            <div class="meta-item">
                                <strong>Keahlian</strong>
                                <p>${alumni.keahlian || '—'}</p>
                            </div>
                        </div>

                        <div class="social-links">
                            ${alumni.linkedin ? `<a href="${alumni.linkedin}" target="_blank">LinkedIn</a>` : ''}
                            ${alumni.portfolio ? `<a href="${alumni.portfolio}" target="_blank">Portfolio</a>` : ''}
                            ${alumni.email ? `<a href="mailto:${alumni.email}">Email</a>` : ''}
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('detailContainer').innerHTML = html;
        } else {
            document.getElementById('detailContainer').innerHTML = '<p style="color: #999;">Data alumni tidak ditemukan</p>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('detailContainer').innerHTML = '<p style="color: red;">Error: Gagal memuat data alumni</p>';
    });
}
</script>