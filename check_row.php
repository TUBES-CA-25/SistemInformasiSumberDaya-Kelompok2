<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/Database.php';
require_once __DIR__ . '/app/models/JadwalPraktikumModel.php';

$model = new JadwalPraktikumModel();
$all = $model->getAll();
foreach ($all as $row) {
    if (isset($row['namaMatakuliah']) && $row['namaMatakuliah'] === 'Sistem Operasi' && $row['kelas'] === 'B2') {
        echo "dosen: " . var_export($row['dosen'], true) . "\n";
        echo "namaDosen: " . var_export($row['namaDosen'], true) . "\n";
        echo "namaAsisten1: " . var_export($row['namaAsisten1'], true) . "\n";
        echo "namaAsisten2: " . var_export($row['namaAsisten2'], true) . "\n";
        break;
    }
}
