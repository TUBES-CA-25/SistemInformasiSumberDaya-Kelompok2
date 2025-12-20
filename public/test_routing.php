<!DOCTYPE html>
<html>
<head>
    <title>🔗 Routing Integration Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-section { 
            border: 1px solid #ccc; 
            padding: 15px; 
            margin: 10px 0; 
            background-color: #f9f9f9; 
        }
        .test-link { 
            display: inline-block; 
            padding: 8px 15px; 
            margin: 5px; 
            background-color: #007cba; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px; 
        }
        .test-link:hover { background-color: #005a8b; }
        .legacy-link { background-color: #28a745; }
        .legacy-link:hover { background-color: #1e7e34; }
    </style>
</head>
<body>
    <h1>🔗 Routing System Integration Test</h1>
    
    <div class="test-section">
        <h2>🚀 MVC Route System (NEW)</h2>
        <p>Clean URLs dengan sistem MVC. Untuk admin dan authentication.</p>
        
        <h3>🔐 Authentication Routes:</h3>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/login" class="test-link">📋 Login Page</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/logout" class="test-link">🚪 Logout</a>
        
        <h3>👨‍💼 Admin Routes:</h3>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin" class="test-link">🏠 Admin Dashboard</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin/alumni" class="test-link">🎓 Admin Alumni</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin/asisten" class="test-link">👨‍🏫 Admin Asisten</a>
        
        <h3>📊 Public MVC Routes:</h3>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/alumni" class="test-link">🎓 View Alumni</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/asisten" class="test-link">👨‍🏫 View Asisten</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/contact" class="test-link">📞 Contact</a>
    </div>
    
    <div class="test-section">
        <h2>📄 Legacy Page System (OLD)</h2>
        <p>Parameter-based routing. Untuk halaman publik sederhana.</p>
        
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=home" class="test-link legacy-link">🏠 Home</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=tatatertib" class="test-link legacy-link">📋 Tata Tertib</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=jadwal" class="test-link legacy-link">📅 Jadwal</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=asisten" class="test-link legacy-link">👨‍🏫 Asisten (Legacy)</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=laboratorium" class="test-link legacy-link">🧪 Laboratorium</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=alumni" class="test-link legacy-link">🎓 Alumni (Legacy)</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=contact" class="test-link legacy-link">📞 Contact (Legacy)</a>
    </div>
    
    <div class="test-section">
        <h2>🔀 Auto-redirect Tests</h2>
        <p>Legacy pages yang otomatis redirect ke MVC routes.</p>
        
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=login" class="test-link">🔀 ?page=login → /login</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/?page=admin" class="test-link">🔀 ?page=admin → /admin</a>
    </div>
    
    <div class="test-section">
        <h2>🛠️ Debug & Tools</h2>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/check_database.php" class="test-link">🔍 Database Checker</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/test_password.php" class="test-link">🔑 Password Tester</a>
        <a href="/SistemInformasiSumberDaya-Kelompok2/public/create_user.php" class="test-link">➕ User Creator</a>
    </div>

    <hr>
    <h3>📚 Integration Notes:</h3>
    <ul>
        <li><strong>✅ MVC Routes:</strong> Use clean URLs like <code>/login</code>, <code>/admin</code></li>
        <li><strong>✅ Legacy Routes:</strong> Use parameters like <code>?page=home</code></li>
        <li><strong>✅ Auto-redirect:</strong> Some legacy pages redirect to MVC routes</li>
        <li><strong>✅ Authentication:</strong> Admin routes protected by AuthMiddleware</li>
        <li><strong>✅ Error Handling:</strong> 404 pages for invalid routes</li>
    </ul>

    <div style="background-color: #e7f3ff; padding: 15px; margin-top: 20px; border-left: 4px solid #007cba;">
        <h4>🎯 Quick Start:</h4>
        <ol>
            <li>Go to <strong>/login</strong> for admin access</li>
            <li>Use credentials: <code>admin</code> / <code>admin123</code></li>
            <li>Access admin dashboard at <strong>/admin</strong></li>
        </ol>
    </div>
</body>
</html>