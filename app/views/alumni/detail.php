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

// Ambil ID dari parameter yang dikirim controller (via $params['id'])
$id = isset($params['id']) ? $params['id'] : (isset($alumni['id']) ? $alumni['id'] : null);
$data = isset($alumniData[$id]) ? $alumniData[$id] : null;
?>

<section class="alumni-section">
    <div class="container">
        <?php if ($data) : ?>
            <div style="margin-bottom: 40px;">
                <a href="<?= BASE_URL ?>/alumni" class="btn-back">
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
        <?php else: ?>
            <div style="padding: 60px 20px; text-align: center;">
                <i class="ri-user-unfollow-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                <h2 style="margin-top: 20px; color: #64748b;">Data Alumni Tidak Ditemukan</h2>
                <p style="color: #94a3b8; margin-bottom: 30px;">Alumni dengan ID "<?= htmlspecialchars($id ?? '') ?>" tidak ada dalam database.</p>
                <a href="<?= BASE_URL ?>/alumni" class="btn-back" style="display: inline-flex;">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar Alumni
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Data alumni ditampilkan secara statis dari PHP -->