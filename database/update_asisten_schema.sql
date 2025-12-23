-- Update Asisten Table Schema
-- Run this script to update the database structure for the new form requirements

-- 1. Change statusAktif to VARCHAR to support 'Asisten', 'CA', 'CCA', 'Tidak Aktif'
ALTER TABLE `asisten` MODIFY COLUMN `statusAktif` VARCHAR(20) DEFAULT 'Asisten';

-- 2. Add missing columns if they don't exist
-- We use a stored procedure approach or just simple ALTER statements that might fail if column exists (but it's safer to just try adding)

-- Add bio column
ALTER TABLE `asisten` ADD COLUMN `bio` TEXT NULL;

-- Add skills column
ALTER TABLE `asisten` ADD COLUMN `skills` TEXT NULL;
