<?php
define('ROOT_PROJECT', __DIR__);
define('APP_PATH', ROOT_PROJECT . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/models/Model.php';
require_once APP_PATH . '/models/AsistenModel.php';

try {
    $model = new AsistenModel();
    $db = $model->getDb();
    
    // Modify skills in asisten table to TEXT
    $sql1 = "ALTER TABLE asisten MODIFY COLUMN skills TEXT NULL";
    if ($db->query($sql1)) {
        echo "SUCCESS: Column 'skills' in 'asisten' table modified to TEXT.\n";
    } else {
        echo "ERROR modifying asisten skills: " . $db->error . "\n";
    }

    // Modify bio in asisten table to TEXT
    $sql2 = "ALTER TABLE asisten MODIFY COLUMN bio TEXT NULL";
    if ($db->query($sql2)) {
        echo "SUCCESS: Column 'bio' in 'asisten' table modified to TEXT.\n";
    } else {
        echo "ERROR modifying asisten bio: " . $db->error . "\n";
    }

    // Modify keahlian in alumni table to TEXT
    $sql3 = "ALTER TABLE alumni MODIFY COLUMN keahlian TEXT NULL";
    if ($db->query($sql3)) {
        echo "SUCCESS: Column 'keahlian' in 'alumni' table modified to TEXT.\n";
    } else {
        echo "ERROR modifying alumni keahlian: " . $db->error . "\n";
    }

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
