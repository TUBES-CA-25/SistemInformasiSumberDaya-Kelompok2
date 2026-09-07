<?php

/**
 * AppsModel
 * Model untuk mengelola ekosistem aplikasi IC-Labs Apps.
 */

require_once __DIR__ . '/Model.php';

class AppsModel extends Model {

    protected $table = 'apps';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * Memastikan tabel apps ada dan melakukan seed data awal jika masih kosong
     */
    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `apps` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `judul` VARCHAR(150) NOT NULL,
            `deskripsi` TEXT NOT NULL,
            `ikon` VARCHAR(100) NOT NULL DEFAULT 'ri-apps-line',
            `warna` VARCHAR(50) NOT NULL DEFAULT 'color-blue',
            `url` VARCHAR(255) NOT NULL DEFAULT '#',
            `target` VARCHAR(20) NOT NULL DEFAULT '_blank',
            `urutan` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        try {
            $this->db->query($sql);
        } catch (\Throwable $e) {}

        if ($this->countAll() === 0) {
            $this->seedDefaultApps();
        }
    }

    /**
     * Seed 9 data awal aplikasi IC-Labs
     */
    private function seedDefaultApps(): void {
        $defaults = [
            [
                'judul'     => 'Sistem Informasi Sumber Daya',
                'deskripsi' => 'Portal utama informasi lab dan manajemen sumber daya laboratorium.',
                'ikon'      => 'ri-team-line',
                'warna'     => 'color-green',
                'url'       => '/',
                'target'    => '_self',
                'urutan'    => 1,
                'is_active' => 1
            ],
            [
                'judul'     => 'Monitoring Praktikum',
                'deskripsi' => 'Input berita acara, absensi, dan update progres praktikum real-time.',
                'ikon'      => 'ri-computer-line',
                'warna'     => 'color-orange',
                'url'       => 'https://iclabs.fikom.umi.ac.id/s/monitoring-praktikum/',
                'target'    => '_blank',
                'urutan'    => 2,
                'is_active' => 1
            ],
            [
                'judul'     => 'Pendaftaran Asisten',
                'deskripsi' => 'Portal rekrutmen calon asisten baru laboratorium FIKOM UMI.',
                'ikon'      => 'ri-user-add-line',
                'warna'     => 'color-cyan',
                'url'       => 'https://iclabs.fikom.umi.ac.id/s/registrasi/login',
                'target'    => '_blank',
                'urutan'    => 3,
                'is_active' => 1
            ],
            [
                'judul'     => 'Pembayaran Lab',
                'deskripsi' => 'Portal pembayaran modul, denda, dan administrasi lab terpadu.',
                'ikon'      => 'ri-wallet-3-line',
                'warna'     => 'color-indigo',
                'url'       => 'https://iclabs.fikom.umi.ac.id/s/SIPEMLA/',
                'target'    => '_blank',
                'urutan'    => 4,
                'is_active' => 1
            ],
            [
                'judul'     => 'Sistem Informasi Lab',
                'deskripsi' => 'Pantau informasi kegiatan dan jadwal lab secara real-time.',
                'ikon'      => 'ri-macbook-line',
                'warna'     => 'color-blue',
                'url'       => '#',
                'target'    => '_self',
                'urutan'    => 5,
                'is_active' => 0
            ],
            [
                'judul'     => 'Monitoring Asisten',
                'deskripsi' => 'Pantau kehadiran dan evaluasi kinerja asisten praktikum.',
                'ikon'      => 'ri-eye-2-line',
                'warna'     => 'color-purple',
                'url'       => '#',
                'target'    => '_self',
                'urutan'    => 6,
                'is_active' => 0
            ],
            [
                'judul'     => 'Peminjaman Lab',
                'deskripsi' => 'Layanan reservasi ruangan lab untuk kegiatan akademik.',
                'ikon'      => 'ri-calendar-check-line',
                'warna'     => 'color-red',
                'url'       => '#',
                'target'    => '_self',
                'urutan'    => 7,
                'is_active' => 0
            ],
            [
                'judul'     => 'Inventori Barang',
                'deskripsi' => 'Pencatatan aset lab dan stok barang habis pakai.',
                'ikon'      => 'ri-archive-line',
                'warna'     => 'color-yellow',
                'url'       => '#',
                'target'    => '_self',
                'urutan'    => 8,
                'is_active' => 0
            ],
            [
                'judul'     => 'Kuisioner & Survei',
                'deskripsi' => 'Penilaian kinerja asisten dan survei kepuasan layanan lab.',
                'ikon'      => 'ri-survey-line',
                'warna'     => 'color-pink',
                'url'       => '#',
                'target'    => '_self',
                'urutan'    => 9,
                'is_active' => 0
            ]
        ];

        foreach ($defaults as $app) {
            $this->insert($app);
        }
    }

    /**
     * Ambil semua aplikasi (untuk admin)
     */
    public function getAllOrdered(): array {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `urutan` ASC, `id` ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil semua aplikasi untuk publik (aktif di atas, non-aktif di bawah)
     */
    public function getAllForPublic(): array {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `is_active` DESC, `urutan` ASC, `id` ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Toggle status aktif/nonaktif aplikasi
     */
    public function toggleStatus(int $id): bool {
        $app = $this->getById($id);
        if (!$app) return false;

        $newStatus = ((int)$app['is_active'] === 1) ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }
}
