<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/Database.php';

$dbObj = new Database();
$db = $dbObj->getConnection();

$res = $db->query("DESCRIBE users");
echo "COLUMNS:\n";
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\nUSERS:\n";
$users = $db->query("SELECT * FROM users");
while ($u = $users->fetch_assoc()) {
    unset($u['password']);
    print_r($u);
}
