# Sistem Informasi Sumber Daya - Laboratorium

Aplikasi web PHP Native untuk manajemen informasi dan sumber daya laboratorium

## 📋 Struktur Folder

```
SistemInformasiSumberDaya-Kelompok2/
├── app/
│   ├── config/              # Konfigurasi aplikasi
│   │   ├── config.php       # Database & URL config
│   │   ├── Database.php     # Database connection class
│   │   └── Router.php       # Router configuration
│   ├── controllers/         # Logic controller
│   ├── models/              # Database models
│   ├── views/               # Template HTML
│   │   ├── admin/           # Admin dashboard views
│   │   ├── alumni/          # Alumni pages
│   │   ├── home/            # Public pages
│   │   ├── contact/         # Contact pages
│   │   ├── fasilitas/       # Facility pages
│   │   └── templates/       # Header & footer templates
│   ├── helpers/             # Helper functions
│   ├── middleware/          # Middleware classes
│   └── routes/              # Route definitions
├── public/                  # Entry point (akses via browser)
│   ├── index.php            # Homepage
│   ├── api.php              # API endpoint
│   ├── admin-*.php          # Admin pages
│   ├── alumni.php           # Alumni page
│   ├── contact.php          # Contact page
│   ├── css/                 # Stylesheet
│   ├── js/                  # JavaScript
│   └── images/              # Images
├── storage/
│   ├── logs/                # Application logs
│   └── uploads/             # User uploads
├── database/                # Database migration files
├── database.sql             # Database schema
├── insert_alumni_dummy.sql  # Sample data
├── SETUP_GUIDE.md           # Detailed setup guide
├── API_DOCUMENTATION.md     # API documentation
├── DATABASE_SCHEMA.md       # Database schema documentation
└── README.md                # This file
```

## 📦 Persyaratan Sistem

- **PHP 7.0** atau lebih tinggi
- **MySQL 5.7** atau lebih tinggi
- **XAMPP** atau server lokal lainnya
- **Git** (untuk clone repository)
- **Browser Modern** (Chrome, Firefox, Safari, Edge)

## 🚀 Panduan Instalasi & Setup Lengkap

### Langkah 1: Persiapan Folder
```bash
# Windows - Buka Command Prompt atau PowerShell
cd C:\xampp\htdocs

# Clone project
git clone https://github.com/kelompok2/SistemInformasiSumberDaya-Kelompok2.git

# Masuk ke folder project
cd SistemInformasiSumberDaya-Kelompok2
```

### Langkah 2: Pastikan XAMPP Running
1. **Buka XAMPP Control Panel** (`C:\xampp\xampp-control.exe`)
2. Klik **"Start"** untuk:
   - ✅ **Apache** (status: Running)
   - ✅ **MySQL** (status: Running)
3. Port default:
   - Apache: `http://localhost:80`
   - MySQL: `localhost:3306`

### Langkah 3: Setup Database

#### **Cara A: Via phpMyAdmin (Rekomendasi - Paling Mudah)**

1. **Buka phpMyAdmin:**
   ```
   http://localhost/phpmyadmin
   ```

2. **Login** (jika diminta):
   - Username: `root`
   - Password: *(kosong)*

3. **Import Database:**
   - Klik tab **"Import"**
   - Klik **"Choose File"** → pilih `database.sql` dari folder project
   - Klik **"Go"**
   - ✅ Database `sistem_manajemen_sumber_daya` berhasil dibuat

4. **Verifikasi:**
   - Di sidebar kiri, refresh
   - Cari database `sistem_manajemen_sumber_daya`
   - Expand → lihat tabel-tabel (Laboratorium, Asisten, Matakuliah, dll)

#### **Cara B: Via Command Line (Terminal)**

```bash
# Windows Command Prompt
cd C:\xampp\htdocs\SistemInformasiSumberDaya-Kelompok2

# Import database
mysql -u root -p < database.sql

# (tekan Enter saat diminta password - biarkan kosong)
```

#### **Cara C: Via MySQL Workbench**
1. Buka MySQL Workbench
2. New Query
3. Copy-paste isi `database.sql`
4. Execute (Ctrl+Enter)

### Langkah 4: Konfigurasi Database (Jika Diperlukan)

Edit file `app/config/config.php`:

```php
<?php
// Database Configuration
define('DB_HOST', 'localhost');     // Host MySQL
define('DB_USER', 'root');          // Username MySQL
define('DB_PASS', '');              // Password MySQL (kosong untuk default XAMPP)
define('DB_NAME', 'sistem_manajemen_sumber_daya');  // Nama database
```

**Catatan:**
- Jika password MySQL Anda berbeda, ubah `define('DB_PASS', '');`
- Contoh: `define('DB_PASS', 'password123');`

### Langkah 5: Pastikan Folder Permission

Folder berikut harus writable (write permission):
```
storage/logs/
storage/uploads/
```

**Cara set permission (Windows):**
1. Right-click folder → Properties
2. Tab Security → Edit
3. Select user → Permissions → Check "Full Control"
4. OK

### Langkah 6: Cek Apache mod_rewrite (Opsional tapi Recommended)

Untuk routing URL yang lebih baik:

1. **Buka file** `C:\xampp\apache\conf\httpd.conf`
2. **Cari baris** (Ctrl+F):
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. **Jika ada tanda #** di depan, hapus:
   ```
   # LoadModule rewrite_module modules/mod_rewrite.so
   ```
   Menjadi:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. **Restart Apache** di XAMPP Control Panel

### Langkah 7: Setup Data Dummy (Opsional)

Untuk menambah data contoh Alumni:

```bash
mysql -u root -p sistem_manajemen_sumber_daya < insert_alumni_dummy.sql
```

Atau via phpMyAdmin:
- Import tab → Choose `insert_alumni_dummy.sql` → Go

## 📱 Akses Aplikasi

### 🏠 Homepage (Publik)
```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/
```
Fitur publik:
- Informasi Laboratorium
- Data Alumni
- Kontak Laboratorium
- Jadwal Praktikum

### 🔐 Admin Dashboard
```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin-dashboard.php
```
Menu Admin:
- 📊 Dashboard (Statistik)
- 👨‍💼 Data Asisten
- 🎓 Data Alumni
- 🏢 Data Fasilitas (Laboratorium)
- 📚 Data Mata Kuliah
- 📅 Jadwal Praktikum

### 🔌 API Endpoints
```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/[endpoint]
```

**Contoh Endpoints:**
```bash
# Health check
curl http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/health

# Get semua laboratorium
curl http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/laboratorium

# Get laboratorium by ID
curl http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/laboratorium/1

# Get semua asisten
curl http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/asisten
```

Lihat [API_DOCUMENTATION.md](API_DOCUMENTATION.md) untuk dokumentasi lengkap.

## 📚 Fitur Utama

✅ **Dashboard Admin**
- Statistik real-time
- Aktivitas terbaru

✅ **Manajemen Data**
- Asisten
- Alumni
- Laboratorium/Fasilitas
- Mata Kuliah
- Jadwal Praktikum

✅ **API RESTful**
- Create, Read, Update, Delete
- JSON response
- Error handling

✅ **Manajemen Konten**
- Peraturan Lab
- Sanksi Lab
- Informasi Umum

✅ **Halaman Publik**
- Profile Alumni
- Kontak Laboratorium
- Jadwal Praktikum
- Informasi Fasilitas

## 🔧 Troubleshooting

### ❌ Error: Database connection failed
**Penyebab:** MySQL tidak running atau konfigurasi salah

**Solusi:**
```bash
# 1. Pastikan MySQL running
# Buka XAMPP Control Panel → Start MySQL

# 2. Cek konfigurasi
# Edit app/config/config.php
# Pastikan username, password, dan DB_NAME benar

# 3. Test koneksi
mysql -u root -p -h localhost
# (test password)
```

### ❌ Error: File not found (404)
**Penyebab:** URL path salah

**Solusi:**
```
Gunakan URL yang benar:
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/

Bukan:
http://localhost/SistemManagementSumberDaya/  ❌
http://localhost/SistemInformasiSumberDaya/   ❌
```

### ❌ Warning: require_once failed
**Penyebab:** Path file salah di setup sebelumnya

**Solusi:**
- Update ke versi terbaru (branch fitur-url-config)
- Clear browser cache: Ctrl+F5
- Reload page

### ❌ Error: Foreign key constraint (errno: 150)
**Penyebab:** Tabel belum di-create dengan benar

**Solusi:**
```bash
# 1. Delete database lama
# phpMyAdmin → Select DB → Operations → Delete

# 2. Import ulang database.sql yang sudah benar
```

### ❌ CSS/JS tidak ter-load
**Penyebab:** Path asset salah

**Solusi:**
- Check browser DevTools (F12) → Network tab
- Lihat status CSS file (200 atau 404?)
- Pastikan ASSETS_URL benar di config.php

### ❌ Storage/logs permission denied
**Penyebab:** Folder tidak writable

**Solusi:**
```bash
# Windows PowerShell (Run as Admin)
icacls "C:\xampp\htdocs\SistemInformasiSumberDaya-Kelompok2\storage\logs" /grant:r "$($env:USERNAME):F"
icacls "C:\xampp\htdocs\SistemInformasiSumberDaya-Kelompok2\storage\uploads" /grant:r "$($env:USERNAME):F"
```

## 📖 Dokumentasi Lanjutan

- **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Panduan detail untuk development
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Dokumentasi API lengkap
- **[DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)** - Skema dan relasi database

## 🛠️ Development Tips

### 1️⃣ Debug Mode
Aktifkan di `app/config/config.php`:
```php
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
```

### 2️⃣ Browser DevTools (F12)
- **Console** → Error JavaScript
- **Network** → Status CSS/JS/API
- **Elements** → Check HTML structure

### 3️⃣ phpMyAdmin
Test query SQL:
1. phpMyAdmin → Select DB
2. SQL tab
3. Write query
4. Execute

### 4️⃣ Postman
Test API:
1. Download Postman
2. New Request
3. URL: `http://localhost/.../api.php/endpoint`
4. Method: GET/POST/PUT/DELETE
5. Headers: Content-Type: application/json

### 5️⃣ Backup Database
```bash
# Export database
mysqldump -u root -p sistem_manajemen_sumber_daya > backup.sql

# Restore database
mysql -u root -p sistem_manajemen_sumber_daya < backup.sql
```

## 📂 File-file Penting

| File | Fungsi |
|------|--------|
| `app/config/config.php` | Konfigurasi database & URL |
| `app/config/Database.php` | Class koneksi database |
| `public/index.php` | Entry point homepage |
| `public/api.php` | Entry point API |
| `database.sql` | Schema database |
| `insert_alumni_dummy.sql` | Data dummy |

## 🚢 Deployment

Untuk production:
1. Set `APP_ENV = 'production'` di config.php
2. Disable error display
3. Setup HTTPS
4. Backup database
5. Use strong MySQL password
6. Restrict folder permissions

## 👥 Team

Proyek **Sistem Informasi Sumber Daya** dikerjakan oleh **Kelompok 2**

## 📝 Lisensi

MIT License - Bebas digunakan untuk keperluan pendidikan

---

## ✅ Checklist Setup

- [ ] XAMPP di-install dan berjalan (Apache + MySQL)
- [ ] Project di-clone ke `C:\xampp\htdocs`
- [ ] Database di-import via phpMyAdmin atau Command Line
- [ ] `app/config/config.php` di-konfigurasi dengan benar
- [ ] Folder `storage/logs` dan `storage/uploads` writable
- [ ] Homepage bisa diakses: `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/`
- [ ] Admin dashboard bisa diakses
- [ ] API endpoint responsif

---

**Selamat! Project siap digunakan! 🎉**

Untuk bantuan lebih lanjut, lihat dokumentasi atau hubungi tim development.
