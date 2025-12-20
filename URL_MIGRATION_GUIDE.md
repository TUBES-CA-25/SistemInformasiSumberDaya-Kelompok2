# 🔄 MIGRATION GUIDE: URL Lama → URL Baru (MVC)

## ⚠️ **BREAKING CHANGES - Semua URL berubah!**

Setelah refactor ke MVC, **SEMUA URL LAMA sudah tidak berfungsi**. Gunakan URL baru di bawah ini:

---

## 🏠 **HALAMAN PUBLIK**

| **Halaman** | **URL LAMA** ❌ | **URL BARU** ✅ |
|-------------|----------------|----------------|
| **Beranda** | `/index.php` | `/` |
| **Alumni** | `/alumni.php` | `/alumni` |
| **Detail Alumni** | `/detail-alumni.php?id=123` | `/alumni/123` |
| **Contact** | `/contact.php` | `/contact` |
| **Asisten** | `/asisten.php` | `/asisten` |
| **Jadwal** | `/jadwal.php` | `/jadwal` |
| **Laboratorium** | `/laboratorium.php` | `/laboratorium` |
| **Detail Lab** | `/detail-fasilitas.php?id=456` | `/laboratorium/456` |
| **Praktikum** | `/praktikum.php` | `/praktikum` |
| **Riset** | `/riset.php` | `/riset` |
| **Profil** | `/profil.php` | `/profil` |
| **Kepala Lab** | `/kepala-lab.php` | `/kepala-lab` |
| **Sanksi** | `/sanksi.php` | `/sanksi` |

---

## 🔐 **HALAMAN ADMIN**

| **Halaman Admin** | **URL LAMA** ❌ | **URL BARU** ✅ |
|-------------------|----------------|----------------|
| **Dashboard** | `/admin-dashboard.php` | `/admin` atau `/admin/dashboard` |
| **Alumni** | `/admin-alumni.php` | `/admin/alumni` |
| **Form Alumni** | `/admin-alumni-form.php` | `/admin/alumni/create` |
| **Edit Alumni** | `/admin-alumni-form.php?id=123` | `/admin/alumni/123/edit` |
| **Asisten** | `/admin-asisten.php` | `/admin/asisten` |
| **Form Asisten** | `/admin-asisten-form.php` | `/admin/asisten/create` |
| **Pilih Koordinator** | `/admin-asisten-pilih-koordinator.php` | `/admin/asisten/koordinator` |
| **Jadwal** | `/admin-jadwal.php` | `/admin/jadwal` |
| **Form Jadwal** | `/admin-jadwal-form.php` | `/admin/jadwal/create` |
| **Upload Jadwal** | `/admin-jadwal-upload.php` | `/admin/jadwal/upload` |
| **Upload CSV** | `/admin-jadwal-csv-upload.php` | `/admin/jadwal/csv-upload` |
| **Laboratorium** | `/admin-laboratorium.php` | `/admin/laboratorium` |
| **Form Lab** | `/admin-laboratorium-form.php` | `/admin/laboratorium/create` |
| **Detail Lab** | `/admin-laboratorium-detail.php?id=123` | `/admin/laboratorium/123/detail` |
| **Form Detail Lab** | `/admin-laboratorium-detail-form.php` | `/admin/laboratorium/123/detail/create` |
| **Matakuliah** | `/admin-matakuliah.php` | `/admin/matakuliah` |
| **Form Matakuliah** | `/admin-matakuliah-form.php` | `/admin/matakuliah/create` |
| **Manajemen** | `/admin-manajemen.php` | `/admin/manajemen` |
| **Form Manajemen** | `/admin-manajemen-form.php` | `/admin/manajemen/create` |
| **Peraturan** | `/admin-peraturan.php` | `/admin/peraturan` |
| **Form Peraturan** | `/admin-peraturan-form.php` | `/admin/peraturan/create` |
| **Sanksi** | `/admin-sanksi.php` | `/admin/sanksi` |
| **Form Sanksi** | `/admin-sanksi-form.php` | `/admin/sanksi/create` |

---

## 🔌 **API ENDPOINTS**

| **API** | **URL LAMA** ❌ | **URL BARU** ✅ |
|---------|----------------|----------------|
| **Health Check** | `/api.php?route=health` | `/api/health` |
| **Alumni List** | `/api.php?route=alumni` | `/api/alumni` |
| **Alumni Detail** | `/api.php?route=alumni&id=123` | `/api/alumni/123` |
| **Asisten List** | `/api.php?route=asisten` | `/api/asisten` |
| **Lab List** | `/api.php?route=laboratorium` | `/api/laboratorium` |
| **Lab Detail** | `/api.php?route=laboratorium&id=456` | `/api/laboratorium/456` |
| **Jadwal List** | `/api.php?route=jadwal` | `/api/jadwal` |

---

## 🔧 **Contoh Penggunaan di Kode**

### HTML Links (Update semua link di template):
```html
<!-- LAMA ❌ -->
<a href="alumni.php">Alumni</a>
<a href="detail-alumni.php?id=123">Detail Alumni</a>
<a href="admin-alumni.php">Admin Alumni</a>

<!-- BARU ✅ -->
<a href="/alumni">Alumni</a>
<a href="/alumni/123">Detail Alumni</a>
<a href="/admin/alumni">Admin Alumni</a>
```

### JavaScript AJAX (Update semua fetch/XMLHttpRequest):
```javascript
// LAMA ❌
fetch('api.php?route=alumni&id=123')

// BARU ✅ 
fetch('/api/alumni/123')
```

### PHP Redirects (Update di controller):
```php
// LAMA ❌
header('Location: admin-alumni.php');

// BARU ✅
header('Location: /admin/alumni');
```

---

## ✅ **Testing URLs**

Coba akses URL baru ini untuk memastikan semuanya bekerja:

```bash
# Test Public Pages
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/alumni
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/contact

# Test Admin Pages  
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin/alumni

# Test API
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/health
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api/alumni
```

---

## 🚨 **Jika URL Tidak Bekerja**

1. **Pastikan mod_rewrite Apache aktif**
2. **Cek file `.htaccess` ada di `/public/`**
3. **Restart Apache di XAMPP**
4. **Periksa error log Apache**

Jika masih bermasalah, baca detail di [MVC_REFACTOR_GUIDE.md](MVC_REFACTOR_GUIDE.md)