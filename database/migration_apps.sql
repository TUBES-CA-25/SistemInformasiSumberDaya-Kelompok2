-- ============================================================
-- SQL Migration: Fitur IC-Labs Apps
-- Sistem Informasi Manajemen Sumber Daya Laboratorium
-- ============================================================

CREATE TABLE IF NOT EXISTS `apps` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data Awal 9 Aplikasi
INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 1, 'Sistem Informasi Sumber Daya', 'Portal utama informasi lab dan manajemen sumber daya laboratorium.', 'ri-team-line', 'color-green', '/', '_self', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 1);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 2, 'Monitoring Praktikum', 'Input berita acara, absensi, dan update progres praktikum real-time.', 'ri-computer-line', 'color-orange', 'https://iclabs.fikom.umi.ac.id/s/monitoring-praktikum/', '_blank', 2, 1
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 2);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 3, 'Pendaftaran Asisten', 'Portal rekrutmen calon asisten baru laboratorium FIKOM UMI.', 'ri-user-add-line', 'color-cyan', 'https://iclabs.fikom.umi.ac.id/s/registrasi/login', '_blank', 3, 1
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 3);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 4, 'Pembayaran Lab', 'Portal pembayaran modul, denda, dan administrasi lab terpadu.', 'ri-wallet-3-line', 'color-indigo', 'https://iclabs.fikom.umi.ac.id/s/SIPEMLA/', '_blank', 4, 1
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 4);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 5, 'Sistem Informasi Lab', 'Pantau informasi kegiatan dan jadwal lab secara real-time.', 'ri-macbook-line', 'color-blue', '#', '_self', 5, 0
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 5);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 6, 'Monitoring Asisten', 'Pantau kehadiran dan evaluasi kinerja asisten praktikum.', 'ri-eye-2-line', 'color-purple', '#', '_self', 6, 0
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 6);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 7, 'Peminjaman Lab', 'Layanan reservasi ruangan lab untuk kegiatan akademik.', 'ri-calendar-check-line', 'color-red', '#', '_self', 7, 0
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 7);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 8, 'Inventori Barang', 'Pencatatan aset lab dan stok barang habis pakai.', 'ri-archive-line', 'color-yellow', '#', '_self', 8, 0
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 8);

INSERT INTO `apps` (`id`, `judul`, `deskripsi`, `ikon`, `warna`, `url`, `target`, `urutan`, `is_active`)
SELECT 9, 'Kuisioner & Survei', 'Penilaian kinerja asisten dan survei kepuasan layanan lab.', 'ri-survey-line', 'color-pink', '#', '_self', 9, 0
WHERE NOT EXISTS (SELECT 1 FROM `apps` WHERE `id` = 9);
