# Sistem Informasi Sumber Daya - Laboratorium

Aplikasi web PHP Native untuk manajemen informasi dan sumber daya laboratorium dengan **MVC Pattern**

## ⚡ **PERUBAHAN PENTING - MVC REFACTOR**

**Aplikasi telah direfactor ke MVC pattern dengan clean URLs!**

### 🔄 **URL Baru vs Lama:**

| **Fungsi** | **URL LAMA** | **URL BARU (MVC)** |
|------------|--------------|-------------------|
| Beranda | `/index.php` | `/` atau `/home` |
| Alumni | `/alumni.php` | `/alumni` |
| Detail Alumni | `/detail-alumni.php?id=123` | `/alumni/123` |
| Contact | `/contact.php` | `/contact` |
| Asisten | `/asisten.php` | `/asisten` |
| Jadwal | `/jadwal.php` | `/jadwal` |
| Laboratorium | `/laboratorium.php` | `/laboratorium` |
| Detail Lab | `/detail-fasilitas.php?id=123` | `/laboratorium/123` |
| Praktikum | `/praktikum.php` | `/praktikum` |
| Riset | `/riset.php` | `/riset` |
| Profil | `/profil.php` | `/profil` |
| Kepala Lab | `/kepala-lab.php` | `/kepala-lab` |
| Sanksi | `/sanksi.php` | `/sanksi` |
| **ADMIN** | | |
| Admin Dashboard | `/admin-dashboard.php` | `/admin` atau `/admin/dashboard` |
| Admin Alumni | `/admin-alumni.php` | `/admin/alumni` |
| Form Alumni | `/admin-alumni-form.php` | `/admin/alumni/create` |
| Edit Alumni | `/admin-alumni-form.php?id=123` | `/admin/alumni/123/edit` |
| Admin Asisten | `/admin-asisten.php` | `/admin/asisten` |
| Admin Jadwal | `/admin-jadwal.php` | `/admin/jadwal` |
| Upload Jadwal | `/admin-jadwal-upload.php` | `/admin/jadwal/upload` |
| Admin Lab | `/admin-laboratorium.php` | `/admin/laboratorium` |
| **API** | | |
| API Alumni | `/api.php?route=alumni` | `/api/alumni` |
| API Detail | `/api.php?route=alumni&id=123` | `/api/alumni/123` |
| Health Check | `/api.php?route=health` | `/api/health` |

### ⚠️ **Breaking Changes:**
- **Semua URL lama tidak bisa diakses lagi**
- **File `.php` entry points sudah dihapus**
- **Harus menggunakan URL baru**

## 📋 Struktur Folder (Setelah MVC Refactor)

```
SistemInformasiSumberDaya-Kelompok2/
├── app/
│   ├── config/              # Konfigurasi aplikasi
│   │   ├── config.php       # Database & URL config
│   │   ├── Database.php     # Database connection class
│   │   └── Router.php       # Enhanced MVC router with 60+ routes
│   ├── controllers/         # MVC Controllers
│   │   ├── Controller.php   # Base controller with view rendering
│   │   ├── HomeController.php
│   │   ├── AlumniController.php
│   │   ├── ContactController.php
│   │   ├── DashboardController.php
│   │   └── ...              # All other controllers
│   ├── models/              # Database models
│   ├── views/               # Template HTML/PHP views
│   │   ├── admin/           # Admin dashboard views
│   │   ├── alumni/          # Alumni pages
│   │   ├── home/            # Public homepage
│   │   ├── contact/         # Contact pages
│   │   ├── errors/          # Error pages (404, 500, etc)
│   │   └── templates/       # Header & footer templates
│   ├── helpers/             # Helper functions
│   └── middleware/          # Middleware classes
├── public/                  # Public web root
│   ├── index.php            # 🎯 Single Entry Point (MVC)
│   ├── api.php              # API Entry Point (legacy support)
│   ├── .htaccess            # 🚀 URL Rewriting for clean URLs
│   ├── css/                 # Stylesheets
│   ├── js/                  # JavaScript files
│   └── assets/              # Static assets
├── storage/
│   ├── logs/                # Application logs
│   └── uploads/             # User uploads
├── database/                # Database files
├── MVC_REFACTOR_GUIDE.md    # 📖 MVC Usage guide
└── README.md                # This file (updated)
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

## 📱 Akses Aplikasi (MVC URLs)

⚠️ **PENTING: Gunakan URL MVC yang baru, bukan URL lama `.php`**

### 🏠 **Homepage & Halaman Publik**

**Base URL:**
```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/
```

**Halaman Publik (Clean URLs):**
```bash
# Homepage
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/

# Alumni
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/alumni
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/alumni/123  # Detail alumni ID 123

# Informasi Lab
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/laboratorium
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/laboratorium/456  # Detail lab ID 456

# Halaman lainnya
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/contact
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/asisten
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/jadwal
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/praktikum
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/riset
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/profil
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/kepala-lab
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/sanksi
```

### 🔐 **Admin Dashboard (MVC)**

**Admin Base:**
```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin
```

**Admin URLs (Clean):**
```bash
# Dashboard
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/dashboard

# Alumni Management
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/alumni
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/alumni/create
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/alumni/123/edit

# Asisten Management
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/asisten
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/asisten/create
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/asisten/koordinator

# Jadwal Management
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/jadwal
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/jadwal/upload
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/jadwal/csv-upload

# Lab Management
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/laboratorium
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/laboratorium/create
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/laboratorium/123/detail

# Management Lainnya
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/matakuliah
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/manajemen
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/peraturan
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/sanksi
```

### 🔌 **API Endpoints (Clean URLs)**

**API Base:**
```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/
```

**API URLs (RESTful):**
```bash
# Health Check
GET http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/health

# Alumni API
GET http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/alumni
GET http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/alumni/123

# Asisten API
GET http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/asisten

# Lab API
GET http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/laboratorium
GET http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/laboratorium/456

# Jadwal API
GET http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/jadwal
```

### ❌ **URL LAMA (TIDAK BERFUNGSI LAGI)**
```bash
# Jangan gunakan URL ini - sudah dihapus!
❌ /alumni.php
❌ /admin-alumni.php  
❌ /contact.php
❌ /api.php?route=alumni
❌ /detail-alumni.php?id=123
```
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
