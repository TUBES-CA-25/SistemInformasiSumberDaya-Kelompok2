<?php
/**
 * Migration script to add photo positioning columns (foto_pos_x, foto_pos_y)
 * to 'asisten', 'alumni', and 'manajemen' tables.
 *
 * These columns store a percentage (0-100) used as CSS object-position so
 * admins can manually reposition how a profile photo is framed inside its
 * square display box, without needing to re-crop/re-upload the file.
 */

require_once __DIR__ . '/app/config/Database.php';

try {
    $database = new Database();
    $mysqli = $database->connect();

    echo "Starting migration...\n";

    $tables = [
        'asisten'   => 'idAsisten',
        'alumni'    => 'id',
        'manajemen' => 'idManajemen',
    ];

    foreach ($tables as $table => $pk) {
        foreach (['foto_pos_x', 'foto_pos_y'] as $column) {
            $check = $mysqli->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
            if ($check && $check->num_rows > 0) {
                echo "Column '{$column}' already exists on '{$table}', skipping.\n";
                continue;
            }

            $sql = "ALTER TABLE `{$table}` ADD COLUMN `{$column}` DECIMAL(5,2) NOT NULL DEFAULT 50.00";
            if ($mysqli->query($sql)) {
                echo "Added column '{$column}' to '{$table}'.\n";
            } else {
                throw new Exception("Failed to add column '{$column}' to '{$table}': " . $mysqli->error);
            }
        }
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
