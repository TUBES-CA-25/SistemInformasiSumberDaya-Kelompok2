-- ============================================================
-- SQL Migration: Fitur Kontak & Kotak Masuk Pesan
-- Sistem Informasi Manajemen Sumber Daya Laboratorium
-- ============================================================

-- 1. Buat Tabel `kontak` (Saluran Kontak Resmi Lab)
CREATE TABLE IF NOT EXISTS `kontak` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Buat Tabel `pesan_kontak` (Kotak Masuk Pesan Formulir Pengunjung)
CREATE TABLE IF NOT EXISTS `pesan_kontak` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `subjek` VARCHAR(255) NOT NULL,
    `pesan` TEXT NOT NULL,
    `status` ENUM('belum_dibaca', 'dibaca') NOT NULL DEFAULT 'belum_dibaca',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Seed Data Bawaan (Jika tabel kontak masih kosong)
INSERT INTO `kontak` (`id`, `nama`, `tipe`, `ikon`, `nilai`, `tautan`, `urutan`, `is_active`)
SELECT 1, 'Lokasi Lab', 'lokasi', 'ri-map-pin-2-line', 'Kampus II UMI, Gedung FIKOM Lt. 2 & 3\nJl. Urip Sumoharjo No. Km. 5, Makassar', 'https://maps.google.com/?q=Fakultas+Ilmu+Komputer+UMI', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM `kontak` WHERE `id` = 1);

INSERT INTO `kontak` (`id`, `nama`, `tipe`, `ikon`, `nilai`, `tautan`, `urutan`, `is_active`)
SELECT 2, 'Email Resmi', 'email', 'ri-mail-line', 'fikom.iclabs@umi.ac.id', 'mailto:fikom.iclabs@umi.ac.id', 2, 1
WHERE NOT EXISTS (SELECT 1 FROM `kontak` WHERE `id` = 2);

INSERT INTO `kontak` (`id`, `nama`, `tipe`, `ikon`, `nilai`, `tautan`, `urutan`, `is_active`)
SELECT 3, 'WhatsApp Support', 'whatsapp', 'ri-whatsapp-line', '+62 411 455666', 'https://wa.me/62411455666', 3, 1
WHERE NOT EXISTS (SELECT 1 FROM `kontak` WHERE `id` = 3);

INSERT INTO `kontak` (`id`, `nama`, `tipe`, `ikon`, `nilai`, `tautan`, `urutan`, `is_active`)
SELECT 4, 'Google Maps Embed', 'maps', 'ri-map-pin-line', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.791123008261!2d119.448235!3d-5.137305!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd3165008369%3A0x7af75b8baf265f2b!2sFakultas%20Ilmu%20Komputer%20UMI!5e0!3m2!1sid!2sus!4v1766106276722!5m2!1sid!2sus', '', 4, 1
WHERE NOT EXISTS (SELECT 1 FROM `kontak` WHERE `id` = 4);
