# Panduan Setup Backend API

## Langkah-langkah Instalasi

### 1. Persiapan Database
- Buka **phpMyAdmin** di `http://localhost/phpmyadmin`
- Buat database baru atau import `database.sql` dari root project
- Pastikan nama database: `sistem_manajemen_sumber_daya`

### 2. Konfigurasi Database
Edit file `app/config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistem_manajemen_sumber_daya');
```

### 3. Struktur Folder
Pastikan struktur folder sudah benar:
```
SistemManagementSumberDaya/
├── app/
│   ├── config/
│   │   ├── config.php
│   │   ├── Database.php
│   │   └── Router.php
│   ├── controllers/
│   ├── models/
│   ├── routes/
│   │   ├── api.php
│   │   └── web.php
│   └── helpers/
├── public/
│   ├── index.php
│   ├── api.php
│   ├── css/
│   ├── js/
│   └── images/
└── database.sql
```

### 4. Testing API

#### Menggunakan cURL:
```bash
# Test health check
curl http://localhost/SistemManagementSumberDaya/public/api.php/health

# Get all laboratorium
curl http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium

# Create laboratorium
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium \
  -H "Content-Type: application/json" \
  -d '{"nama":"Lab Baru","deskripsi":"Deskripsi","jumlahKursi":30}'
```

#### Menggunakan Postman:
1. Buka Postman
2. Import collection atau buat request baru
3. Method: GET, POST, PUT, DELETE
4. URL: `http://localhost/SistemManagementSumberDaya/public/api.php/[endpoint]`
5. Header: `Content-Type: application/json`

### 5. Troubleshooting

#### API tidak bisa diakses
- Pastikan Apache mod_rewrite aktif
- Edit httpd.conf dan uncomment: `LoadModule rewrite_module modules/mod_rewrite.so`

#### Database connection error
- Pastikan MySQL/MariaDB running
- Check konfigurasi di `app/config/config.php`
- Pastikan database dan user sudah dibuat

#### 404 Not Found
- Check URL routing di `app/routes/api.php`
- Pastikan controller dan method ada

#### Permission Denied
- Pastikan folder `storage/logs` dan `storage/uploads` writable

## File Dokumentasi
- **API_DOCUMENTATION.md** - Dokumentasi lengkap API
- **database.sql** - Script untuk membuat database

## Development Tips
- Selalu cek response di browser DevTools atau Postman
- Log query dengan menambahkan error_log di helper
- Gunakan Postman untuk test API lebih mudah
- Backup database secara berkala

---
