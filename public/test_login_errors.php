<!DOCTYPE html>
<html>
<head>
    <title>🧪 Login Error Test Tool</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .test-section { 
            border: 1px solid #ccc; 
            padding: 15px; 
            margin: 10px 0; 
            background-color: #f9f9f9; 
            border-radius: 8px;
        }
        .test-form { 
            display: inline-block; 
            margin: 10px; 
            padding: 15px; 
            border: 1px solid #007cba;
            background-color: white;
            border-radius: 4px;
        }
        .test-button {
            background-color: #007cba; 
            color: white; 
            border: none; 
            padding: 8px 15px; 
            cursor: pointer;
            border-radius: 4px;
        }
        .error-preview {
            background-color: #ffeaea;
            border: 2px solid #d32f2f;
            color: #b71c1c;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success-preview {
            background-color: #e8f5e8;
            border: 2px solid #4caf50;
            color: #2e7d32;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        code { background-color: #f0f0f0; padding: 2px 4px; border-radius: 2px; }
    </style>
</head>
<body>
    <h1>🧪 Login Error Test Tool</h1>
    <p>Tool untuk menguji berbagai jenis error message yang akan ditampilkan saat login.</p>

    <div class="test-section">
        <h2>📋 Error Message Scenarios</h2>
        
        <h3>1️⃣ Username Kosong</h3>
        <div class="error-preview">
            ⚠️ Username tidak boleh kosong!
        </div>
        <div class="test-form">
            <form action="<?= PUBLIC_URL ?>/login" method="POST">
                <input type="text" name="username" value="" placeholder="(kosong)">
                <input type="password" name="password" value="admin123" placeholder="password">
                <button type="submit" class="test-button">Test Username Kosong</button>
            </form>
        </div>

        <h3>2️⃣ Password Kosong</h3>
        <div class="error-preview">
            ⚠️ Password tidak boleh kosong!
        </div>
        <div class="test-form">
            <form action="<?= PUBLIC_URL ?>/login" method="POST">
                <input type="text" name="username" value="admin" placeholder="username">
                <input type="password" name="password" value="" placeholder="(kosong)">
                <button type="submit" class="test-button">Test Password Kosong</button>
            </form>
        </div>

        <h3>3️⃣ Username Tidak Terdaftar</h3>
        <div class="error-preview">
            ❌ Username "<strong>usertest</strong>" tidak terdaftar dalam sistem!<br>
            <small style="color: #666;">💡 Pastikan username yang Anda masukkan benar.</small>
        </div>
        <div class="test-form">
            <form action="<?= PUBLIC_URL ?>/login" method="POST">
                <input type="text" name="username" value="usertest" placeholder="username salah">
                <input type="password" name="password" value="admin123" placeholder="password">
                <button type="submit" class="test-button">Test Username Salah</button>
            </form>
        </div>

        <h3>4️⃣ Password Salah (Username Benar)</h3>
        <div class="error-preview">
            ❌ Password salah untuk user "<strong>admin</strong>"!<br>
            <small style="color: #666;">💡 Username benar, tapi password yang Anda masukkan salah.</small>
        </div>
        <div class="test-form">
            <form action="<?= PUBLIC_URL ?>/login" method="POST">
                <input type="text" name="username" value="admin" placeholder="username">
                <input type="password" name="password" value="wrongpassword" placeholder="password salah">
                <button type="submit" class="test-button">Test Password Salah</button>
            </form>
        </div>

        <h3>5️⃣ Login Berhasil</h3>
        <div class="success-preview">
            ✅ Login berhasil! Selamat datang, <strong>admin</strong>!
        </div>
        <div class="test-form">
            <form action="<?= PUBLIC_URL ?>/login" method="POST">
                <input type="text" name="username" value="admin" placeholder="username">
                <input type="password" name="password" value="admin123" placeholder="password">
                <button type="submit" class="test-button">Test Login Sukses</button>
            </form>
        </div>
    </div>

    <div class="test-section">
        <h2>🛠️ Testing Guidelines</h2>
        <ol>
            <li><strong>Test setiap scenario</strong> dengan klik button yang sesuai</li>
            <li><strong>Perhatikan pesan error</strong> yang spesifik untuk setiap kasus</li>
            <li><strong>Username akan tetap tersimpan</strong> jika terjadi error (kecuali login sukses)</li>
            <li><strong>Error message</strong> memberikan hints untuk membantu user</li>
        </ol>

        <h3>📝 Test Results Expected:</h3>
        <ul>
            <li>✅ <strong>Username kosong:</strong> Warning khusus username</li>
            <li>✅ <strong>Password kosong:</strong> Warning khusus password</li>
            <li>✅ <strong>Username tidak ada:</strong> Error dengan nama username yang salah</li>
            <li>✅ <strong>Password salah:</strong> Error konfirmasi bahwa username benar</li>
            <li>✅ <strong>Login sukses:</strong> Redirect ke admin dashboard</li>
        </ul>
    </div>

    <div class="test-section">
        <h2>🔗 Quick Links</h2>
        <a href="<?= PUBLIC_URL ?>/login" class="test-button">🔑 Go to Login Page</a>
        <a href="<?= PUBLIC_URL ?>/admin" class="test-button">🏠 Admin Dashboard</a>
        <a href="<?= PUBLIC_URL ?>/logout" class="test-button">🚪 Logout</a>
    </div>

    <div style="background-color: #e7f3ff; padding: 15px; margin-top: 20px; border-left: 4px solid #007cba;">
        <h4>💡 Tips:</h4>
        <p>Setiap test akan redirect ke login page dengan error message yang sesuai. Setelah test login sukses, Anda akan masuk ke admin dashboard.</p>
    </div>
</body>
</html>