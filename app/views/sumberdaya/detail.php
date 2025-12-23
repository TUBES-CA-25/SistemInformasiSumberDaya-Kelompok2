<?php
// === DATA DUMMY SUMBER DAYA (ASISTEN + KEPALA LAB + LABORAN) ===
$asistenData = [
    // --- 1. KEPALA LABORATORIUM ---
    'AF' => [
        'nama' => 'Dr. Ir. Ahmad Fauzi, M.Kom.',
        'jabatan' => 'Kepala Lab. Terpadu',
        'kategori' => 'Pimpinan',
        'spesialisasi' => 'Artificial Intelligence & Data Mining',
        'bio' => 'Beliau adalah Dosen senior dengan pengalaman lebih dari 15 tahun. Fokus penelitiannya meliputi Machine Learning, Deep Learning, dan penerapannya dalam Smart City.',
        'email' => 'ahmad.fauzi@umi.ac.id',
        'foto' => 'AF',
        'skills' => ['Academic Leadership', 'AI Research', 'Curriculum Development', 'Python']
    ],
    'BD' => [
        'nama' => 'Dr. Budi Darmawan, M.T.',
        'jabatan' => 'Kepala Lab. Jaringan Komputer',
        'kategori' => 'Pimpinan',
        'spesialisasi' => 'Network Security & Cloud Computing',
        'bio' => 'Ahli dalam infrastruktur jaringan dan keamanan siber. Aktif membimbing mahasiswa dalam sertifikasi MikroTik dan Cisco.',
        'email' => 'budi.d@umi.ac.id',
        'foto' => 'BD',
        'skills' => ['Cyber Security', 'Cloud Infrastructure', 'IoT', 'MikroTik']
    ],
    'CL' => [
        'nama' => 'Dr. Citra Lestari, M.Cs.',
        'jabatan' => 'Kepala Lab. Multimedia',
        'kategori' => 'Pimpinan',
        'spesialisasi' => 'Human Computer Interaction (HCI)',
        'bio' => 'Peneliti aktif di bidang interaksi manusia dan komputer serta pengembangan teknologi Augmented Reality (AR) untuk pendidikan.',
        'email' => 'citra.l@umi.ac.id',
        'foto' => 'CL',
        'skills' => ['UI/UX Research', 'Augmented Reality', 'Multimedia Systems']
    ],

    // --- 2. LABORAN (PLP) ---
    'DK' => [
        'nama' => 'Dedi Kurniawan, S.Kom.',
        'jabatan' => 'Pranata Laboratorium (Teknis)',
        'kategori' => 'Laboran',
        'spesialisasi' => 'Hardware & Network Maintenance',
        'bio' => 'Bertanggung jawab atas pemeliharaan perangkat keras dan kestabilan koneksi internet di seluruh laboratorium.',
        'email' => 'dedi.k@umi.ac.id',
        'foto' => 'DK',
        'skills' => ['Hardware Repair', 'Troubleshooting', 'Inventory Management']
    ],

    // --- 3. KOORDINATOR & ASISTEN ---
    '1' => [
        'nama' => 'Nahwa Kaka S.',
        'jabatan' => 'Koordinator Laboratorium',
        'kategori' => 'Koordinator',
        'spesialisasi' => 'Fullstack Web Developer',
        'bio' => 'Mahasiswa tingkat akhir yang memiliki passion di bidang arsitektur perangkat lunak. Telah menangani berbagai proyek sistem informasi kampus.',
        'email' => 'nahwa@umi.ac.id',
        'foto' => 'NK',
        'skills' => ['PHP', 'Laravel', 'React', 'System Design']
    ],
    '2' => [
        'nama' => 'Andi Saputra',
        'jabatan' => 'Asisten Divisi Network',
        'kategori' => 'Asisten',
        'spesialisasi' => 'Network Engineer',
        'bio' => 'Ahli dalam konfigurasi Mikrotik dan Cisco. Bertanggung jawab atas stabilitas jaringan saat praktikum berlangsung.',
        'email' => 'andi@umi.ac.id',
        'foto' => 'AS',
        'skills' => ['Cisco Packet Tracer', 'Mikrotik', 'Linux Server']
    ],
    '3' => [
        'nama' => 'Siti Aminah',
        'jabatan' => 'Asisten Divisi Data',
        'kategori' => 'Asisten',
        'spesialisasi' => 'Data Scientist',
        'bio' => 'Fokus pada penelitian Machine Learning dan Big Data. Sering menjadi mentor untuk praktikum Basis Data.',
        'email' => 'siti@umi.ac.id',
        'foto' => 'SA',
        'skills' => ['Python', 'TensorFlow', 'SQL', 'Data Mining']
    ],
    '4' => [
        'nama' => 'Budi Santoso',
        'jabatan' => 'Asisten Divisi Multimedia',
        'kategori' => 'Asisten',
        'spesialisasi' => 'UI/UX Designer',
        'bio' => 'Memiliki ketertarikan kuat pada desain antarmuka pengguna dan pengalaman pengguna.',
        'email' => 'budi@umi.ac.id',
        'foto' => 'BS',
        'skills' => ['Figma', 'Adobe XD', 'Prototyping']
    ],
    '5' => [
        'nama' => 'Dewi Lestari',
        'jabatan' => 'Calon Asisten',
        'kategori' => 'Calon Asisten',
        'spesialisasi' => 'Mobile Developer',
        'bio' => 'Sedang menjalani masa percobaan. Menguasai pengembangan aplikasi mobile menggunakan Flutter.',
        'email' => 'dewi@umi.ac.id',
        'foto' => 'DL',
        'skills' => ['Flutter', 'Dart', 'Firebase']
    ],
    '6' => [
        'nama' => 'Rahmat Fauzi',
        'jabatan' => 'Calon Asisten',
        'kategori' => 'Calon Asisten',
        'spesialisasi' => 'Backend Developer',
        'bio' => 'Fokus pada pengembangan API dan manajemen database.',
        'email' => 'rahmat@umi.ac.id',
        'foto' => 'RF',
        'skills' => ['Node.js', 'Express', 'MySQL']
    ],
];

// Logika Ambil Data
$id = isset($_GET['id']) ? $_GET['id'] : null;
$data = isset($asistenData[$id]) ? $asistenData[$id] : null;
?>

<section class="sumberdaya-section">
    <div class="container">
        
        <?php if ($data) : ?>
            <div style="margin-bottom: 30px;">
                <?php 
                    $backLink = PUBLIC_URL . '/asisten'; // Default
                    if ($data['kategori'] == 'Pimpinan' || $data['kategori'] == 'Laboran') {
                        $backLink = PUBLIC_URL . '/kepala-lab';
                    }
                ?>
                <a href="<?= $backLink; ?>" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="profile-wrapper">
                <div class="profile-image">
                    <?php if (strlen($data['foto']) <= 3) : ?>
                        <div class="exec-placeholder" style="font-size: 8rem; color: #94a3b8; width: 100%; height: 100%;">
                            <?= $data['foto']; ?>
                        </div>
                    <?php else : ?>
                        <img src="<?= $data['foto']; ?>" alt="<?= $data['nama']; ?>">
                    <?php endif; ?>
                </div>
                
                <div class="profile-content">
                    <span class="skill-tag" style="background:#e0f2fe; color:#0369a1; border:none; margin-bottom:15px; display:inline-block;">
                        <?= $data['kategori']; ?>
                    </span>
                    
                    <h1 style="margin-bottom: 5px; font-size: 2.5rem;"><?= $data['nama']; ?></h1>
                    <span class="jabatan" style="font-size:1.2rem; color:#64748b;"><?= $data['jabatan']; ?></span>
                    
                    <div style="margin: 20px 0;">
                        <span class="member-specialization" style="background:none; padding:0; color:#0f172a; font-weight:600;">
                            <i class="ri-focus-2-line" style="color:#2563eb; margin-right:5px;"></i> 
                            <?= $data['spesialisasi']; ?>
                        </span>
                    </div>

                    <h4 style="color:#0f172a; margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">Tentang</h4>
                    <p><?= $data['bio']; ?></p>

                    <h4 style="color:#0f172a; margin-bottom:10px; margin-top:30px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">Keahlian & Kompetensi</h4>
                    <div class="skills-container">
                        <?php foreach($data['skills'] as $skill): ?>
                            <span class="skill-tag"><?= $skill; ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div style="margin-top: 40px;">
                        <a href="mailto:<?= $data['email']; ?>" class="btn-contact">
                            <i class="ri-mail-send-line"></i> Hubungi via Email
                        </a>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <div style="text-align: center; padding: 100px 0;">
                <h2>Data Tidak Ditemukan</h2>
                <p>Maaf, data personel dengan ID tersebut tidak tersedia.</p>
                <a href="<?= PUBLIC_URL ?>/asisten" class="btn-back">Kembali ke Home</a>
            </div>
        <?php endif; ?>

    </div>
</section>