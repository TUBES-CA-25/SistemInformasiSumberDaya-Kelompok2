<?php

/**
 * ShowcaseModel
 * Model untuk mengelola data Slider Showcase di Halaman Utama (Home).
 */

require_once __DIR__ . '/Model.php';

class ShowcaseModel extends Model {
    
    protected $table = 'home_showcase';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * Membuat tabel home_showcase otomatis jika belum ada & isi seed awal.
     */
    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `home_showcase` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `badge_text` VARCHAR(100) NOT NULL,
            `judul` VARCHAR(255) NOT NULL,
            `deskripsi` TEXT NOT NULL,
            `deskripsi_lengkap` TEXT NULL,
            `gambar` VARCHAR(255) NULL,
            `galeri_foto` TEXT NULL,
            `link_url` VARCHAR(255) NULL,
            `link_label` VARCHAR(100) NULL,
            `urutan` INT DEFAULT 0,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        try {
            $this->db->query($sql);
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE `home_showcase` ADD COLUMN `deskripsi_lengkap` TEXT NULL AFTER `deskripsi`");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE `home_showcase` ADD COLUMN `galeri_foto` TEXT NULL AFTER `gambar`");
        } catch (\Throwable $e) {}

        // Jika tabel kosong, masukkan seed data default
        if ($this->countAll() === 0) {
            $this->seedDefaultData();
        }
    }

    /**
     * Seed data awal untuk slider home.
     */
    private function seedDefaultData(): void {
        $seeds = [
            [
                'badge_text'        => 'PENCAPAIAN UNGGULAN',
                'judul'             => '<span class="text-blue">Pencapaian</span> & Inovasi Riset',
                'deskripsi'         => 'Laboratorium FIKOM UMI melahirkan publikasi ilmiah bereputasi, paten HAKI software, dan inovasi komputasi bernilai tinggi.',
                'deskripsi_lengkap' => 'Laboratorium FIKOM UMI secara aktif memfasilitasi riset kolaboratif antara dosen dan mahasiswa. Melalui pusat riset ini, telah dihasilkan puluhan jurnal bereputasi (Scopus/Sinta), paten HAKI perangkat lunak, serta produk sistem komputasi terapan yang diimplementasikan di industri dan masyarakat.',
                'gambar'            => 'RisetDanInovasi.png',
                'galeri_foto'       => json_encode(['RisetDanInovasi.png', 'Pusat-Kompetensi.jpg', 'Infrastruktur-Modern.jpg']),
                'link_url'          => '/riset',
                'link_label'        => 'Lihat Riset',
                'urutan'            => 1,
                'is_active'         => 1
            ],
            [
                'badge_text'        => 'FASILITAS UNGGULAN',
                'judul'             => '<span class="text-blue">Pusat</span> Kompetensi',
                'deskripsi'         => 'Laboratorium FIKOM UMI hadir sebagai pusat pengembangan hard skill unggulan dengan kurikulum adaptif.',
                'deskripsi_lengkap' => 'Sebagai pusat pelatihan resmi berlisensi internasional (seperti MikroTik Academy & Cisco), IC-Labs memfasilitasi mahasiswa dengan sertifikasi profesi berstandar industri. Program bootcamp dan pelatihan rutin dirancang khusus untuk memenuhi kebutuhan lapangan kerja IT modern.',
                'gambar'            => 'Pusat-Kompetensi.jpg',
                'galeri_foto'       => json_encode(['Pusat-Kompetensi.jpg', 'Infrastruktur-Modern.jpg', 'RisetDanInovasi.png']),
                'link_url'          => '/asisten',
                'link_label'        => 'Lihat Selengkapnya',
                'urutan'            => 2,
                'is_active'         => 1
            ],
            [
                'badge_text'        => 'PERANGKAT MODERN',
                'judul'             => '<span class="text-blue">Infrastruktur</span> Spesifik',
                'deskripsi'         => 'Menyediakan laboratorium spesifik (RPL, Jaringan, Multimedia) dengan perangkat spesifikasi tinggi.',
                'deskripsi_lengkap' => 'Dilengkapi dengan Workstation High-Performance Computing (HPC), GPU Server untuk kecerdasan buatan (AI), perangkat jaringan enterprise, serta ruangan Multimedia berstandar tinggi untuk mendukung kenyamanan praktikum dan penelitian komputasi.',
                'gambar'            => 'Infrastruktur-Modern.jpg',
                'galeri_foto'       => json_encode(['Infrastruktur-Modern.jpg', 'RisetDanInovasi.png', 'Pusat-Kompetensi.jpg']),
                'link_url'          => '/laboratorium',
                'link_label'        => 'Lihat Fasilitas',
                'urutan'            => 3,
                'is_active'         => 1
            ]
        ];

        foreach ($seeds as $item) {
            $this->createItem($item);
        }
    }

    /**
     * Ambil semua data diurutkan berdasarkan urutan.
     */
    public function getAllOrdered(): array {
        $query = "SELECT * FROM {$this->table} ORDER BY urutan ASC, id ASC";
        $result = $this->db->query($query);
        return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil hanya data aktif untuk tampilan publik home.
     */
    public function getAllActive(): array {
        $query = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY urutan ASC, id ASC";
        $result = $this->db->query($query);
        return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil data berdasarkan ID.
     */
    public function getById($id, $idColumn = null): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        if (!$stmt) return null;
        $idInt = (int)$id;
        $stmt->bind_param("i", $idInt);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_assoc() : null;
    }

    /**
     * Tambah item showcase baru.
     */
    public function createItem(array $data): bool {
        $badge             = $data['badge_text'] ?? '';
        $judul             = $data['judul'] ?? '';
        $deskripsi         = $data['deskripsi'] ?? '';
        $deskripsi_lengkap = $data['deskripsi_lengkap'] ?? '';
        $gambar            = $data['gambar'] ?? '';
        $galeri            = is_array($data['galeri_foto'] ?? null) ? json_encode($data['galeri_foto']) : ($data['galeri_foto'] ?? '[]');
        $link_url          = $data['link_url'] ?? '';
        $link_label        = $data['link_label'] ?? '';
        $urutan            = (int)($data['urutan'] ?? 0);
        $is_active         = (int)($data['is_active'] ?? 1);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} (badge_text, judul, deskripsi, deskripsi_lengkap, gambar, galeri_foto, link_url, link_label, urutan, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("ssssssssii", $badge, $judul, $deskripsi, $deskripsi_lengkap, $gambar, $galeri, $link_url, $link_label, $urutan, $is_active);
        return $stmt->execute();
    }

    /**
     * Update item showcase.
     */
    public function updateItem(int $id, array $data): bool {
        $badge             = $data['badge_text'] ?? '';
        $judul             = $data['judul'] ?? '';
        $deskripsi         = $data['deskripsi'] ?? '';
        $deskripsi_lengkap = $data['deskripsi_lengkap'] ?? '';
        $link_url          = $data['link_url'] ?? '';
        $link_label        = $data['link_label'] ?? '';
        $urutan            = (int)($data['urutan'] ?? 0);
        $is_active         = (int)($data['is_active'] ?? 1);

        $fields = ["badge_text=?", "judul=?", "deskripsi=?", "deskripsi_lengkap=?", "link_url=?", "link_label=?", "urutan=?", "is_active=?"];
        $types = "ssssssii";
        $params = [$badge, $judul, $deskripsi, $deskripsi_lengkap, $link_url, $link_label, $urutan, $is_active];

        if (!empty($data['gambar'])) {
            $fields[] = "gambar=?";
            $types .= "s";
            $params[] = $data['gambar'];
        }

        if (isset($data['galeri_foto'])) {
            $fields[] = "galeri_foto=?";
            $types .= "s";
            $params[] = is_array($data['galeri_foto']) ? json_encode($data['galeri_foto']) : $data['galeri_foto'];
        }

        $types .= "i";
        $params[] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id=?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    /**
     * Hapus item showcase.
     */
    public function deleteItem(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id=?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
