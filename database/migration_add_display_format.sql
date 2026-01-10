-- Migrasi: Menambah kolom display_format untuk peraturan_lab dan sanksi_lab
-- Tanggal: 10 Januari 2026

-- Tambah kolom ke tabel peraturan_lab
ALTER TABLE `peraturan_lab` ADD COLUMN `display_format` VARCHAR(20) DEFAULT 'list' COMMENT 'Format tampilan: list (poin-poin) atau plain (teks biasa)' AFTER `kategori`;

-- Tambah kolom ke tabel sanksi_lab
ALTER TABLE `sanksi_lab` ADD COLUMN `display_format` VARCHAR(20) DEFAULT 'list' COMMENT 'Format tampilan: list (poin-poin) atau plain (teks biasa)' AFTER `updated_at`;

-- Update data yang sudah ada ke default 'list'
UPDATE `peraturan_lab` SET `display_format` = 'list' WHERE `display_format` IS NULL;
UPDATE `sanksi_lab` SET `display_format` = 'list' WHERE `display_format` IS NULL;
