<?php
// Simple Database Check Page
require_once '../app/config/config.php';
require_once '../app/config/Database.php';

echo '<h2>🔍 Database Check</h2>';

try {
	$database = new Database();
	$db = $database->connect();

	echo '<p><strong>DB Host:</strong> ' . htmlspecialchars(DB_HOST) . '</p>';
	echo '<p><strong>DB Name:</strong> ' . htmlspecialchars(DB_NAME) . '</p>';
	echo '<p><strong>DB User:</strong> ' . htmlspecialchars(DB_USER) . '</p>';

	// Check users table exists
	$tableCheck = $db->query("SHOW TABLES LIKE 'users'");
	if (!$tableCheck || $tableCheck->num_rows === 0) {
		echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
		echo "❌ Tabel 'users' tidak ditemukan di database '" . htmlspecialchars(DB_NAME) . "'.";
		echo "</div>";
	} else {
		// Count users
		$countRes = $db->query("SELECT COUNT(*) AS cnt FROM users");
		$countRow = $countRes->fetch_assoc();
		echo '<p><strong>Total Users:</strong> ' . intval($countRow['cnt']) . '</p>';

		// Show admin user if exists
		$res = $db->query("SELECT id, username, role, password, created_at, last_login FROM users WHERE username='admin' LIMIT 1");
		if ($res && $res->num_rows > 0) {
			$row = $res->fetch_assoc();
			echo '<h3>👤 Admin User</h3>';
			echo "<table border='1' style='border-collapse: collapse;'>";
			echo "<tr><th style='padding:6px'>Field</th><th style='padding:6px'>Value</th></tr>";
			foreach ([
				'id' => $row['id'],
				'username' => $row['username'],
				'role' => $row['role'],
				'password_hash_prefix(32)' => substr($row['password'], 0, 32) . '…',
				'created_at' => $row['created_at'],
				'last_login' => $row['last_login']
			] as $k => $v) {
				echo "<tr><td style='padding:6px'>" . htmlspecialchars($k) . "</td><td style='padding:6px;font-family:monospace'>" . htmlspecialchars((string)$v) . "</td></tr>";
			}
			echo "</table>";
		} else {
			echo "<div style='color: orange; border: 1px solid orange; padding: 10px; margin: 10px 0;'>";
			echo "⚠️ User 'admin' tidak ditemukan. Anda bisa menambahkan user default menggunakan file SQL di folder database (create_users_table.sql).";
			echo "</div>";
		}
	}

	echo "<div style='margin-top:20px'>";
	echo "<a href='" . htmlspecialchars(PUBLIC_URL) . "/fix_password.php' style='background:#007cba;color:#fff;padding:8px 14px;text-decoration:none;border-radius:4px'>🔧 Perbaiki Password Admin</a>";
	echo " <a href='" . htmlspecialchars(PUBLIC_URL) . "/login' style='background:#28a745;color:#fff;padding:8px 14px;text-decoration:none;border-radius:4px'>🔑 Ke Halaman Login</a>";
	echo "</div>";

} catch (Exception $e) {
	echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
	echo "❌ Database Error: " . htmlspecialchars($e->getMessage());
	echo "</div>";
}
?>
