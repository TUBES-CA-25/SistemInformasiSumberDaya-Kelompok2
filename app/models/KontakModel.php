<?php

/**
 * KontakModel
 * Model untuk mengelola:
 * 1. Informasi Saluran Kontak Lab (Lokasi, Email, WhatsApp, Maps, Sosial Media)
 * 2. Kotak Masuk Pesan Pengunjung dari formulir kontak
 */

require_once __DIR__ . '/Model.php';

class KontakModel extends Model {

    protected $table = 'kontak';
    protected $tablePesan = 'pesan_kontak';

    public function __construct() {
        parent::__construct();
        $this->ensureTablesExist();
    }

    /**
     * Memastikan tabel kontak dan pesan_kontak tersedia di database.
     */
    private function ensureTablesExist(): void {
        // 1. Tabel Kontak Saluran
        $sqlKontak = "CREATE TABLE IF NOT EXISTS `kontak` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `nama` VARCHAR(100) NOT NULL,
            `tipe` VARCHAR(50) NOT NULL DEFAULT 'lainnya',
            `ikon` VARCHAR(100) NOT NULL DEFAULT 'ri-information-line',
            `nilai` TEXT NOT NULL,
            `tautan` VARCHAR(255) NULL,
            `urutan` INT DEFAULT 0,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        try {
            $this->db->query($sqlKontak);
        } catch (\Throwable $e) {}

        // 2. Tabel Kotak Masuk Pesan Pengunjung
        $sqlPesan = "CREATE TABLE IF NOT EXISTS `pesan_kontak` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `nama` VARCHAR(150) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `subjek` VARCHAR(255) NOT NULL,
            `pesan` TEXT NOT NULL,
            `status` ENUM('belum_dibaca', 'dibaca') NOT NULL DEFAULT 'belum_dibaca',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        try {
            $this->db->query($sqlPesan);
        } catch (\Throwable $e) {}

        // Seed data jika tabel kontak kosong
        if ($this->countAll() === 0) {
            $this->seedDefaultData();
        }
    }

    /**
     * Seeding data default saluran kontak
     */
    private function seedDefaultData(): void {
        $defaults = [
            [
                'nama'      => 'Lokasi Lab',
                'tipe'      => 'lokasi',
                'ikon'      => 'ri-map-pin-2-line',
                'nilai'     => "Kampus II UMI, Gedung FIKOM Lt. 2 & 3\nJl. Urip Sumoharjo No. Km. 5, Makassar",
                'tautan'    => 'https://maps.google.com/?q=Fakultas+Ilmu+Komputer+UMI',
                'urutan'    => 1,
                'is_active' => 1
            ],
            [
                'nama'      => 'Email Resmi',
                'tipe'      => 'email',
                'ikon'      => 'ri-mail-line',
                'nilai'     => 'fikom.iclabs@umi.ac.id',
                'tautan'    => 'mailto:fikom.iclabs@umi.ac.id',
                'urutan'    => 2,
                'is_active' => 1
            ],
            [
                'nama'      => 'WhatsApp Support',
                'tipe'      => 'whatsapp',
                'ikon'      => 'ri-whatsapp-line',
                'nilai'     => '+62 411 455666',
                'tautan'    => 'https://wa.me/62411455666',
                'urutan'    => 3,
                'is_active' => 1
            ],
            [
                'nama'      => 'Google Maps Embed',
                'tipe'      => 'maps',
                'ikon'      => 'ri-map-pin-line',
                'nilai'     => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.791123008261!2d119.448235!3d-5.137305!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd3165008369%3A0x7af75b8baf265f2b!2sFakultas%20Ilmu%20Komputer%20UMI!5e0!3m2!1sid!2sus!4v1766106276722!5m2!1sid!2sus',
                'tautan'    => '',
                'urutan'    => 4,
                'is_active' => 1
            ]
        ];

        foreach ($defaults as $item) {
            $this->insert($item);
        }
    }

    // ==========================================
    // BAGIAN CRUD SALURAN KONTAK
    // ==========================================

    /**
     * Ambil semua data kontak saluran untuk Admin (diurutkan berdasarkan urutan ASC)
     */
    public function getAllOrdered(): array {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `urutan` ASC, `id` ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil data kontak aktif untuk tampilan publik (kecuali tipe 'maps')
     */
    public function getActivePublic(): array {
        $sql = "SELECT * FROM `{$this->table}` WHERE `is_active` = 1 AND `tipe` != 'maps' ORDER BY `urutan` ASC, `id` ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil URL iframe Google Maps yang aktif
     */
    public function getActiveMapsUrl(): string {
        $sql = "SELECT `nilai` FROM `{$this->table}` WHERE `tipe` = 'maps' AND `is_active` = 1 LIMIT 1";
        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $val = trim($row['nilai']);
            // Jika user memasukkan tag <iframe src="...">, ekstrak src-nya saja
            if (preg_match('/src="([^"]+)"/', $val, $matches)) {
                return $matches[1];
            }
            return $val;
        }
        return 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.791123008261!2d119.448235!3d-5.137305!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd3165008369%3A0x7af75b8baf265f2b!2sFakultas%20Ilmu%20Komputer%20UMI!5e0!3m2!1sid!2sus!4v1766106276722!5m2!1sid!2sus';
    }

    /**
     * Ambil email penerima notifikasi pesan kontak
     * Sesuai settingan CONTACT_EMAIL_SOURCE ('kontak' atau 'env')
     * 
     * @return string Email tujuan yang valid
     */
    public function getRecipientEmail(): string {
        $source = defined('CONTACT_EMAIL_SOURCE') ? strtolower(CONTACT_EMAIL_SOURCE) : 'kontak';

        // 1. Jika diset 'env', utamakan ADMIN_EMAIL dari .env
        if ($source === 'env') {
            if (defined('ADMIN_EMAIL') && !empty(ADMIN_EMAIL) && filter_var(ADMIN_EMAIL, FILTER_VALIDATE_EMAIL)) {
                return ADMIN_EMAIL;
            }
        }

        // 2. Sumber 'kontak' (default): cari email resmi yang disetting pada saluran kontak
        $sql = "SELECT `nilai` FROM `{$this->table}` WHERE `tipe` = 'email' AND `is_active` = 1 ORDER BY `urutan` ASC, `id` ASC LIMIT 1";
        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $email = trim($row['nilai']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        // 3. Jika belum ketemu, cari kolom nilai yang memiliki format email valid
        $sqlAll = "SELECT `nilai` FROM `{$this->table}` WHERE `is_active` = 1 ORDER BY `urutan` ASC, `id` ASC";
        $resAll = $this->db->query($sqlAll);
        if ($resAll) {
            while ($row = $resAll->fetch_assoc()) {
                $val = trim($row['nilai']);
                if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    return $val;
                }
            }
        }

        // 4. Fallback jika di database kontak belum ada email: gunakan ADMIN_EMAIL
        if (defined('ADMIN_EMAIL') && !empty(ADMIN_EMAIL) && filter_var(ADMIN_EMAIL, FILTER_VALIDATE_EMAIL)) {
            return ADMIN_EMAIL;
        }

        return 'nahwakakaa@gmail.com';
    }

    /**
     * Toggle status aktif/nonaktif saluran kontak
     */
    public function toggleStatus(int $id): bool {
        $kontak = $this->getById($id);
        if (!$kontak) return false;

        $newStatus = (int)$kontak['is_active'] === 1 ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }

    // ==========================================
    // BAGIAN KOTAK MASUK PESAN (INBOX)
    // ==========================================

    /**
     * Simpan pesan baru dari pengunjung
     */
    public function simpanPesan(string $nama, string $email, string $subjek, string $pesan): bool {
        $stmt = $this->db->prepare("INSERT INTO `{$this->tablePesan}` (`nama`, `email`, `subjek`, `pesan`, `status`) VALUES (?, ?, ?, ?, 'belum_dibaca')");
        if (!$stmt) return false;

        $stmt->bind_param("ssss", $nama, $email, $subjek, $pesan);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /**
     * Ambil semua pesan masuk
     */
    public function getAllPesan(): array {
        $sql = "SELECT * FROM `{$this->tablePesan}` ORDER BY `created_at` DESC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil satu pesan berdasarkan ID
     */
    public function getPesanById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->tablePesan}` WHERE `id` = ? LIMIT 1");
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();
        return $data ?: null;
    }

    /**
     * Tandai pesan sebagai telah dibaca
     */
    public function tandaiDibaca(int $id): bool {
        $stmt = $this->db->prepare("UPDATE `{$this->tablePesan}` SET `status` = 'dibaca' WHERE `id` = ?");
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /**
     * Hapus pesan masuk
     */
    public function hapusPesan(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `{$this->tablePesan}` WHERE `id` = ?");
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /**
     * Hitung pesan yang belum dibaca
     */
    public function countUnreadPesan(): int {
        $sql = "SELECT COUNT(*) as total FROM `{$this->tablePesan}` WHERE `status` = 'belum_dibaca'";
        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return (int)$row['total'];
        }
        return 0;
    }
}
