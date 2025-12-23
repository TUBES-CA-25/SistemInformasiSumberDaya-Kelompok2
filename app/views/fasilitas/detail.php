<?php
// === DATA DUMMY LENGKAP (FASILITAS LAB & RISET) ===
$fasilitasData = [
    
    // =========================================
    // 1. LABORATORIUM PENGAJARAN (TEACHING LAB)
    // =========================================

    // --- LAB SOFTWARE ENGINEERING ---
    'LAB-SE' => [
        'nama' => 'Lab. Software Engineering',
        'sub_judul' => 'Kapasitas: 40 Mahasiswa',
        'kategori' => 'Laboratorium',
        'lokasi' => 'Gedung A, Lantai 2',
        'deskripsi' => 'Laboratorium ini dirancang khusus untuk menunjang mata kuliah pemrograman tingkat lanjut, rekayasa perangkat lunak, dan pengembangan sistem informasi berbasis web maupun mobile.',
        'foto' => 'SE', 
        
        'hardware' => [
            'Processor' => 'Intel Core i7 Gen 12',
            'RAM' => '32 GB DDR4',
            'Storage' => 'SSD NVMe 1 TB',
            'GPU' => 'NVIDIA RTX 3060',
            'Monitor' => '24 Inch IPS Display',
            'Unit' => '41 PC (1 Instruktur + 40 Praktikan)'
        ],
        'software' => [
            'Visual Studio Code', 'JetBrains Suite', 'XAMPP', 
            'Node.js', 'Python 3.10', 'Android Studio', 
            'Flutter SDK', 'Git', 'Docker Desktop'
        ],
        'pendukung' => [
            'AC Central (2 Unit @ 2 PK)',
            'Proyektor HD & Sound System',
            'Whiteboard Kaca (Glassboard)',
            'Koneksi LAN Gigabit & WiFi 6',
            'Kursi Ergonomis'
        ],
        'koordinator' => [
            'nama' => 'Bpk. Ahmad Fauzi, M.T.',
            'inisial' => 'AF',
            'wa' => '628123456789' 
        ]
    ],

    // --- LAB JARINGAN ---
    'LAB-JK' => [
        'nama' => 'Lab. Jaringan Komputer',
        'sub_judul' => 'Kapasitas: 35 Mahasiswa',
        'kategori' => 'Laboratorium',
        'lokasi' => 'Gedung A, Lantai 3',
        'deskripsi' => 'Fasilitas simulasi jaringan dan keamanan siber. Dilengkapi dengan perangkat keras jaringan fisik (Router/Switch) yang dapat dikonfigurasi langsung oleh mahasiswa.',
        'foto' => 'JK',
        
        'hardware' => [
            'Router' => 'MikroTik RB1100AHx4',
            'Switch' => 'Cisco Catalyst 2960',
            'Server' => 'Dell PowerEdge R740',
            'PC Client' => 'Intel Core i5 Gen 11',
            'RAM' => '16 GB DDR4',
            'Tools' => 'Crimping Set & LAN Tester'
        ],
        'software' => [
            'Cisco Packet Tracer', 'GNS3', 'Wireshark', 
            'WinBox', 'VirtualBox', 'Kali Linux', 'Putty'
        ],
        'pendukung' => [
            'Rak Server Open Rack',
            'Kabel UTP Cat 6 (Roll)',
            'AC Split (2 Unit)',
            'Smart TV 50 Inch untuk Presentasi'
        ],
        'koordinator' => [
            'nama' => 'Bpk. Budi Darmawan, M.T.',
            'inisial' => 'BD',
            'wa' => '628123456789'
        ]
    ],

    // --- LAB MULTIMEDIA ---
    'LAB-MM' => [
        'nama' => 'Lab. Multimedia & Game',
        'sub_judul' => 'Kapasitas: 30 Mahasiswa',
        'kategori' => 'Laboratorium',
        'lokasi' => 'Gedung B, Lantai 2',
        'deskripsi' => 'Laboratorium dengan spesifikasi High-End Performance untuk kebutuhan rendering video 4K, pemodelan 3D, dan pengembangan game engine.',
        'foto' => 'MM',
        
        'hardware' => [
            'Processor' => 'AMD Ryzen 7 5800X',
            'GPU' => 'NVIDIA RTX 3060 12GB',
            'RAM' => '32 GB DDR4',
            'Monitor' => '27" 4K Color Calibrated',
            'Input' => 'Wacom Intuos Pro'
        ],
        'software' => [
            'Adobe Creative Cloud', 'Blender 3D', 'Unity 3D', 
            'Unreal Engine 5', 'Davinci Resolve', 'Figma'
        ],
        'pendukung' => [
            'Ruang Kedap Suara (Recording)',
            'Green Screen Studio',
            'Lighting Set',
            'Koneksi Internet Dedicated 100Mbps'
        ],
        'koordinator' => [
            'nama' => 'Ibu Dr. Citra Lestari, M.Cs.',
            'inisial' => 'CL',
            'wa' => '628123456789'
        ]
    ],

    // =========================================
    // 2. PUSAT RISET & INOVASI (RESEARCH CENTER)
    // =========================================

    // --- RISET AI ---
    'RST-AI' => [
        'nama' => 'Artificial Intelligence Research',
        'sub_judul' => 'Kapasitas: 10 Peneliti',
        'kategori' => 'Riset',
        'lokasi' => 'Gedung C, Lantai 1',
        'deskripsi' => 'Ruang riset khusus untuk penelitian Deep Learning, Computer Vision, dan NLP. Ruangan ini digunakan oleh dosen dan mahasiswa tingkat akhir untuk publikasi jurnal internasional.',
        'foto' => 'AI',
        
        'hardware' => [
            'Server' => 'NVIDIA DGX Station A100',
            'Workstation' => 'Threadripper Pro 64-Core',
            'RAM' => '128 GB ECC Memory',
            'Storage' => 'NAS Synology 40TB (Dataset)',
            'Unit' => '5 High-Performance PC'
        ],
        'software' => [
            'PyTorch', 'TensorFlow', 'Jupyter Lab', 
            'MATLAB Campus License', 'Anaconda', 'Docker'
        ],
        'pendukung' => [
            'Akses 24 Jam untuk Peneliti',
            'Whiteboard Diskusi Full Wall',
            'Meeting Table',
            'Koneksi Fiber Optik Pribadi'
        ],
        'koordinator' => [
            'nama' => 'Prof. Dr. Iwan Santoso',
            'inisial' => 'IS',
            'wa' => '6281122334455'
        ]
    ],

    // --- RISET IOT ---
    'RST-IOT' => [
        'nama' => 'IoT & Smart System',
        'sub_judul' => 'Kapasitas: 15 Peneliti',
        'kategori' => 'Riset',
        'lokasi' => 'Gedung C, Lantai 1',
        'deskripsi' => 'Tempat pengembangan prototipe perangkat keras cerdas dan sistem tertanam. Dilengkapi dengan berbagai modul sensor, mikrokontroler, dan peralatan manufaktur ringan.',
        'foto' => 'IOT',
        
        'hardware' => [
            'Printing' => 'Ender 3 V2 (3D Printer)',
            'Gateway' => 'LoRaWAN Gateway',
            'Tools' => 'Solder Station & Oscilloscope',
            'Kits' => 'Arduino, ESP32, Raspberry Pi 4',
            'Workstation' => 'PC Soldering & Testing'
        ],
        'software' => [
            'Arduino IDE', 'Altium Designer', 'Fritzing', 
            'Raspberry Pi OS', 'MQTT Broker', 'Node-RED'
        ],
        'pendukung' => [
            'Meja Anti-Statik (ESD Safe)',
            'Lemari Penyimpanan Komponen',
            'Power Supply Variable',
            'Smoke Absorber'
        ],
        'koordinator' => [
            'nama' => 'Ibu Dr. Rina Maharani',
            'inisial' => 'RM',
            'wa' => '6281233445566'
        ]
    ],

    // --- RISET MOBILE ---
    'RST-MOB' => [
        'nama' => 'Mobile Innovation Center',
        'sub_judul' => 'Kapasitas: 12 Peneliti',
        'kategori' => 'Riset',
        'lokasi' => 'Gedung B, Lantai 3',
        'deskripsi' => 'Wadah inkubasi startup digital bagi mahasiswa. Fokus pada inovasi aplikasi mobile berbasis Flutter, Kotlin, iOS, dan teknologi Cloud Native.',
        'foto' => 'MOB',
        
        'hardware' => [
            'Workstation' => 'Mac Mini M2 Pro',
            'Testing' => 'iPhone 14 Pro & Pixel 7 Dev',
            'Monitor' => 'LG Ultrafine 4K',
            'Unit' => '8 Unit Mac & 4 Unit Windows'
        ],
        'software' => [
            'Xcode', 'Android Studio', 'Flutter SDK', 
            'Firebase Console', 'Postman', 'React Native'
        ],
        'pendukung' => [
            'Device Testing Lab',
            'High-Speed Wi-Fi 6E',
            'Sofa Diskusi Santai',
            'Layanan Cloud Credit (AWS/GCP)'
        ],
        'koordinator' => [
            'nama' => 'Bpk. Bayu Perdana, M.Kom.',
            'inisial' => 'BP',
            'wa' => '628199887766'
        ]
    ],
];

// Logika Ambil Data
// Prioritize $id from controller, fallback to $_GET['id']
$id = isset($id) ? $id : (isset($_GET['id']) ? $_GET['id'] : null);
$data = isset($fasilitasData[$id]) ? $fasilitasData[$id] : null;
?>

<section class="fasilitas-section">
    <div class="container">
        
        <?php if ($data) : ?>
            <div style="margin-bottom: 40px;">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="ri-arrow-left-up-line"></i> Kembali
                </a>
            </div>

            <div class="detail-layout">
                
                <div class="left-sidebar">
                    <div class="facility-hero-img">
                        <?php if($data['kategori'] == 'Riset'): ?>
                            <i class="ri-lightbulb-flash-line" style="font-size: 5rem; color: #cbd5e1;"></i>
                        <?php else: ?>
                            <i class="ri-building-2-line" style="font-size: 5rem; color: #cbd5e1;"></i>
                        <?php endif; ?>
                        
                        <span style="margin-top:15px; font-weight:700; color:#64748b; font-size: 1.1rem;">
                            <i class="ri-group-fill" style="color:#2563eb;"></i> <?= $data['sub_judul']; ?>
                        </span>
                    </div>

                    <div class="coord-card">
                        <span class="coord-title">Penanggung Jawab</span>
                        <div class="coord-avatar"><?= $data['koordinator']['inisial']; ?></div>
                        <div class="coord-name" style="font-size: 1.2rem; font-weight: 700;"><?= $data['koordinator']['nama']; ?></div>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 5px;">Koordinator <?= $data['kategori']; ?></p>
                        
                        <a href="https://wa.me/<?= $data['koordinator']['wa']; ?>" class="btn-wa">
                            <i class="ri-whatsapp-line"></i> Hubungi via WhatsApp
                        </a>
                    </div>
                </div>

                <div class="right-content">
                    <span class="badge-info" style="margin-bottom: 15px;">Detail <?= $data['kategori']; ?></span>
                    <h1><?= $data['nama']; ?></h1>
                    
                    <p style="color: #64748b; font-weight: 600; margin-bottom: 10px;">
                        <i class="ri-map-pin-line"></i> Lokasi: <?= $data['lokasi']; ?>
                    </p>
                    
                    <p class="description"><?= $data['deskripsi']; ?></p>

                    <div class="spec-group">
                        <h3><i class="ri-cpu-line"></i> Spesifikasi Perangkat Keras</h3>
                        <div class="spec-list">
                            <?php foreach($data['hardware'] as $label => $val): ?>
                            <div class="spec-item">
                                <span class="spec-label"><?= $label; ?></span>
                                <span class="spec-value"><?= $val; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="spec-group">
                        <h3><i class="ri-terminal-box-line"></i> Perangkat Lunak & SDK</h3>
                        <div class="software-tags">
                            <?php foreach($data['software'] as $sw): ?>
                                <span class="sw-tag"><?= $sw; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="spec-group">
                        <h3><i class="ri-shield-check-line"></i> Fasilitas Pendukung</h3>
                        <ul class="facility-list">
                            <?php foreach($data['pendukung'] as $p): ?>
                                <li><?= $p; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 100px 0;">
                <i class="ri-error-warning-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                <h2 style="margin-top: 20px; color: #0f172a;">Data Fasilitas Tidak Ditemukan</h2>
                <a href="index.php" class="btn-back" style="margin-top: 20px; display: inline-block;">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>

    </div>
</section>