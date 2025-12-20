<?php
// === DATA DUMMY ALUMNI ===
$alumniData = [
    'RD' => [
        'nama' => 'Reza Danuarta, S.Kom.',
        'prodi' => 'Teknik Informatika',
        'angkatan' => '2018',
        'inisial' => 'RD',
        'email' => 'reza.danuarta@alumni.umi.ac.id',
        'bio' => 'Berfokus pada pengembangan sistem backend yang scalable. Selama menjadi asisten di laboratorium, aktif dalam divisi programming yang membantu riset sistem terdistribusi.',
        'pengalaman' => [
            ['tahun' => '2022 - Sekarang', 'posisi' => 'Senior Software Engineer', 'instansi' => 'Gojek'],
            ['tahun' => '2020 - 2022', 'posisi' => 'Backend Developer', 'instansi' => 'DANA Indonesia'],
            ['tahun' => '2019 - 2020', 'posisi' => 'Junior Developer', 'instansi' => 'Startup Lokal']
        ],
        'testimoni' => 'Pengalaman menjadi asisten di laboratorium memberikan pondasi teknis yang sangat kuat sebelum terjun ke dunia industri.'
    ],
    'AS' => [
        'nama' => 'Annisa Suci, S.Kom.',
        'prodi' => 'Sistem Informasi',
        'angkatan' => '2019',
        'inisial' => 'AS',
        'email' => 'annisa.suci@alumni.umi.ac.id',
        'bio' => 'Ahli dalam pengolahan data besar dan visualisasi data untuk pengambilan keputusan bisnis strategis.',
        'pengalaman' => [
            ['tahun' => '2021 - Sekarang', 'posisi' => 'Data Analyst', 'instansi' => 'BCA'],
            ['tahun' => '2020 - 2021', 'posisi' => 'Intern Data Scientist', 'instansi' => 'Telkom Indonesia']
        ],
        'testimoni' => 'Laboratorium adalah tempat terbaik untuk bereksperimen dengan data asli dan melatih logika analisis.'
    ]
];

$id = isset($_GET['id']) ? $_GET['id'] : null;
$data = isset($alumniData[$id]) ? $alumniData[$id] : null;
?>

<section class="alumni-section">
    <div class="container">
        <?php if ($data) : ?>
            <div style="margin-bottom: 40px;">
                <a href="index.php?page=alumni" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar Alumni
                </a>
            </div>

            <div class="detail-layout">
                <div class="left-sidebar">
                    <div class="facility-hero-img">
                        <div class="coord-avatar"><?= $data['inisial']; ?></div>
                        <h2 style="margin-top: 20px; font-size: 1.2rem; text-align: center;"><?= $data['nama']; ?></h2>
                        <span style="color: #64748b; font-weight: 600;"><?= $data['prodi']; ?></span>
                        <div class="badge-info" style="margin-top: 15px;">Angkatan <?= $data['angkatan']; ?></div>
                    </div>

                    <div class="coord-card">
                        <span class="coord-title">Kontak Profesional</span>
                        <p style="font-size: 0.85rem; margin-bottom: 15px; word-break: break-all; opacity: 0.8;"><?= $data['email']; ?></p>
                        <a href="mailto:<?= $data['email']; ?>" class="btn-wa">
                            <i class="ri-mail-line"></i> Kirim Pesan
                        </a>
                    </div>
                </div>

                <div class="right-content">
                    <span class="badge-info">Profil Alumni</span>
                    <h1>Karier & Pengalaman</h1>
                    <p class="description"><?= $data['bio']; ?></p>

                    <div class="spec-group">
                        <h3><i class="ri-briefcase-line"></i> Riwayat Pekerjaan</h3>
                        <div class="spec-list">
                            <?php foreach($data['pengalaman'] as $exp): ?>
                            <div class="spec-item">
                                <span class="spec-label"><?= $exp['tahun']; ?></span>
                                <div class="spec-value">
                                    <div style="color: #0f172a; font-weight: 700;"><?= $exp['posisi']; ?></div>
                                    <div style="color: #2563eb; font-size: 0.9rem;"><?= $exp['instansi']; ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="spec-group">
                        <h3><i class="ri-chat-quote-line"></i> Testimoni</h3>
                        <div class="testimoni-box">
                            "<?= $data['testimoni']; ?>"
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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

    fetch(`${API_URL}/alumni/${alumniId}`)
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