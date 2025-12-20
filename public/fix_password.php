<?php
/**
 * Password Fix Tool
 * Update password admin yang sudah ada di database
 */

require_once '../app/config/config.php';
require_once '../app/config/Database.php';

echo "<h2>🔧 Password Fix Tool</h2>";

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    try {
        $database = new Database();
        $db = $database->connect();
        
        // Hash baru yang benar untuk admin123
        $newHash = '$2y$10$9dZeOKeyCyGLbICQl4l2S.rhW9VQd7Tj5iqbdSe43yG1YKUv3Utey';
        
        // Update password admin
        $updateQuery = "UPDATE users SET password = ? WHERE username = 'admin'";
        $stmt = $db->prepare($updateQuery);
        $stmt->bind_param("s", $newHash);
        
        if ($stmt->execute()) {
            echo "<div style='color: green; border: 1px solid green; padding: 10px; margin: 10px 0;'>";
            echo "✅ <strong>Password admin berhasil diperbaiki!</strong><br>";
            echo "📝 Username: <code>admin</code><br>";
            echo "🔑 Password: <code>admin123</code><br>";
            echo "🔐 Hash baru: <code>$newHash</code>";
            echo "</div>";
        } else {
            echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
            echo "❌ Gagal update password: " . $db->error;
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
        echo "❌ Error: " . $e->getMessage();
        echo "</div>";
    }
}

// Check current password hash
try {
    $database = new Database();
    $db = $database->connect();
    
    $getAdmin = "SELECT username, password, created_at FROM users WHERE username = 'admin'";
    $result = $db->query($getAdmin);
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $currentHash = $admin['password'];
        
        echo "<h3>📊 Current Admin Status</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th style='padding: 8px;'>Field</th><th style='padding: 8px;'>Value</th></tr>";
        echo "<tr><td style='padding: 8px;'>Username</td><td style='padding: 8px;'><strong>" . $admin['username'] . "</strong></td></tr>";
        echo "<tr><td style='padding: 8px;'>Current Hash</td><td style='padding: 8px; font-family: monospace; font-size: 12px;'>" . $currentHash . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Created</td><td style='padding: 8px;'>" . $admin['created_at'] . "</td></tr>";
        echo "</table>";
        
        echo "<h3>🧪 Password Tests</h3>";
        $testPasswords = ['admin123', 'password', 'admin', 'secret', 'laravel'];
        
        foreach ($testPasswords as $testPass) {
            $isValid = password_verify($testPass, $currentHash);
            $icon = $isValid ? "✅" : "❌";
            $color = $isValid ? "green" : "gray";
            echo "<div style='color: $color; margin: 5px 0;'>";
            echo "$icon Testing '<strong>$testPass</strong>': " . ($isValid ? "MATCH!" : "No match");
            echo "</div>";
        }
        
        // Check if current hash matches admin123
        $isCorrect = password_verify('admin123', $currentHash);
        
        if (!$isCorrect) {
            echo "<div style='background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0;'>";
            echo "<h4 style='margin-top: 0;'>⚠️ Password Issue Detected!</h4>";
            echo "<p>Current password hash is NOT for 'admin123'. This is why login fails.</p>";
            echo "<p><strong>Solution:</strong> Click button below to fix the password.</p>";
            echo "</div>";
            
            echo "<form method='POST'>";
            echo "<button type='submit' name='update_password' style='background: #dc3545; color: white; padding: 15px 30px; border: none; font-size: 16px; cursor: pointer; border-radius: 5px;'>";
            echo "🔧 Fix Password (Set to admin123)";
            echo "</button>";
            echo "</form>";
        } else {
            echo "<div style='background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0;'>";
            echo "<h4 style='margin-top: 0;'>✅ Password is Correct!</h4>";
            echo "<p>Current hash matches 'admin123'. Login should work properly.</p>";
            echo "</div>";
        }
        
    } else {
        echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
        echo "❌ Admin user not found in database!";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
    echo "❌ Database Error: " . $e->getMessage();
    echo "</div>";
}
?>

<h3>📝 Password Hash Information</h3>
<div style="background-color: #f8f9fa; padding: 15px; font-family: monospace; font-size: 12px;">
<strong>Current hash in SQL file:</strong><br>
$2y$10$9dZeOKeyCyGLbICQl4l2S.rhW9VQd7Tj5iqbdSe43yG1YKUv3Utey<br><br>

<strong>Old incorrect hash:</strong><br>
$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi (password: 'password')<br><br>

<strong>Expected for admin123:</strong><br>
$2y$10$9dZeOKeyCyGLbICQl4l2S.rhW9VQd7Tj5iqbdSe43yG1YKUv3Utey ✅
</div>

<div style="margin-top: 30px; text-align: center;">
    <a href="/SistemInformasiSumberDaya-Kelompok2/public/login" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🔑 Test Login</a>
    <a href="/SistemInformasiSumberDaya-Kelompok2/public/check_database.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">🔍 Database Check</a>
</div>