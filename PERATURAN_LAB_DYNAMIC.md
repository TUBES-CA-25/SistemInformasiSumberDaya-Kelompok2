# Panduan Menggunakan Peraturan Lab Dinamis

## 📋 Ringkasan Perubahan

Halaman **Peraturan dan Ketentuan Lab** telah diubah dari statis (hardcoded) menjadi **dinamis menggunakan REST API**. Artinya, semua data peraturan sekarang tersimpan di database dan bisa dikelola melalui admin dashboard.

---

## 🎯 Fitur yang Ditambahkan

### 1. **Halaman Frontend (Publik)**
- **URL:** `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/praktikum.php`
- Data peraturan secara otomatis dimuat dari API REST
- Menampilkan peraturan dari database (tidak lagi hardcoded)

### 2. **Admin Panel untuk Manajemen Peraturan**
- **URL:** `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php`
- Fitur CRUD lengkap (Create, Read, Update, Delete)
- Tambah, edit, hapus peraturan langsung dari admin

### 3. **REST API Endpoint**
Sudah tersedia endpoint untuk peraturan lab:
```
GET    /api/tata-tertib         → Lihat semua peraturan
GET    /api/tata-tertib/{id}    → Lihat detail peraturan
POST   /api/tata-tertib         → Tambah peraturan baru
PUT    /api/tata-tertib/{id}    → Edit peraturan
DELETE /api/tata-tertib/{id}    → Hapus peraturan
```

---

## 🔧 Cara Menggunakan

### **A. Menambah Peraturan Baru**

#### Via Admin Panel:
1. Login ke admin: `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php`
2. Klik tombol **"+ Tambah Peraturan Baru"**
3. Isi form:
   - **Nama Peraturan** (Judul peraturan)
   - **Deskripsi** (Penjelasan lengkap)
4. Klik **"Simpan Peraturan"**

#### Via cURL (Command Line):
```bash
curl -X POST http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib \
  -H "Content-Type: application/json" \
  -d '{
    "namaFile": "Kehadiran Wajib",
    "uraFile": "Praktikan wajib hadir 100% untuk tidak di-drop."
  }'
```

#### Via Postman:
1. Buat request baru
2. Method: **POST**
3. URL: `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib`
4. Header: `Content-Type: application/json`
5. Body (raw JSON):
```json
{
  "namaFile": "Peraturan Baru",
  "uraFile": "Deskripsi peraturan"
}
```

---

### **B. Mengedit Peraturan Existing**

#### Via Admin Panel:
1. Buka: `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php`
2. Cari peraturan yang ingin diedit
3. Klik tombol **"Edit"**
4. Ubah data
5. Klik **"Simpan Peraturan"**

#### Via cURL:
```bash
curl -X PUT http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib/1 \
  -H "Content-Type: application/json" \
  -d '{
    "namaFile": "Peraturan Terbaru",
    "uraFile": "Deskripsi yang sudah diupdate"
  }'
```

---

### **C. Menghapus Peraturan**

#### Via Admin Panel:
1. Buka: `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php`
2. Cari peraturan yang ingin dihapus
3. Klik tombol **"Hapus"**
4. Konfirmasi penghapusan

#### Via cURL:
```bash
curl -X DELETE http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib/1
```

---

### **D. Melihat Semua Peraturan (API)**

```bash
curl http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib
```

Response:
```json
{
  "status": "success",
  "message": "Data Tata Tertib retrieved successfully",
  "data": [
    {
      "idTataTerib": 1,
      "namaFile": "Disiplin Waktu Kehadiran",
      "uraFile": "Praktikan wajib hadir 15 menit sebelum...",
      "tanggalUpload": "2025-12-15 10:30:00"
    },
    {
      "idTataTerib": 2,
      "namaFile": "Aturan Berpakaian & Identitas",
      "uraFile": "Wajib mengenakan seragam kemeja putih...",
      "tanggalUpload": "2025-12-15 10:35:00"
    }
  ]
}
```

---

## 📊 Struktur Database

Tabel: `tataTerib`

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `idTataTerib` | INT | Primary Key, Auto Increment |
| `namaFile` | VARCHAR(255) | Judul peraturan |
| `uraFile` | VARCHAR(255) | Deskripsi peraturan |
| `tanggalUpload` | DATETIME | Timestamp otomatis |

---

## 📁 File-File yang Diubah/Ditambah

### **Diubah:**
- [app/views/praktikum/index.php](../../app/views/praktikum/index.php) - Perubahan dari statis ke dinamis
- [app/views/admin/templates/header.php](../../app/views/admin/templates/header.php) - Tambah menu "Peraturan Lab"
- [database.sql](../../database.sql) - Tambah sample data peraturan

### **Ditambah (Baru):**
- [app/views/admin/peraturan/index.php](../../app/views/admin/peraturan/index.php) - List peraturan admin
- [app/views/admin/peraturan/form.php](../../app/views/admin/peraturan/form.php) - Form tambah/edit peraturan
- [public/admin-peraturan.php](../../public/admin-peraturan.php) - Entry point admin list
- [public/admin-peraturan-form.php](../../public/admin-peraturan-form.php) - Entry point form

---

## 🚀 Workflow Lengkap

```
1. Admin login → Admin Panel
   ↓
2. Klik menu "Peraturan Lab"
   ↓
3. Lihat daftar peraturan (diambil dari API /tata-tertib)
   ↓
4. Pilih: Tambah, Edit, atau Hapus
   ↓
5. Data disimpan ke database
   ↓
6. Halaman frontend secara otomatis menampilkan data terbaru
   ↓
7. Pengunjung web lihat peraturan dari database (dinamis)
```

---

## ✅ Verifikasi Setup

Untuk memastikan semuanya berfungsi:

### 1. **Test API**
```bash
# Check semua peraturan
curl http://localhost/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib
```

### 2. **Test Frontend**
- Buka: `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/praktikum.php`
- Seharusnya menampilkan 4 peraturan default yang sudah diinsert ke database

### 3. **Test Admin Panel**
- Buka: `http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php`
- Seharusnya bisa melihat tabel dengan 4 peraturan
- Coba tambah/edit/hapus untuk test CRUD

---

## 🔄 Integrasi dengan Sistem Lain

Jika ada halaman lain yang juga perlu dinamis (seperti Sanksi Lab), ikuti pola yang sama:

1. **Create table** di database
2. **Create model** (sudah ada: `SanksiLabModel`)
3. **Create controller** (sudah ada: `SanksiLabController`)
4. **Create API route** di `app/routes/api.php`
5. **Update view** (ganti hardcoded dengan fetch API)
6. **Create admin CRUD** (list + form)

---

## 📝 Tips & Best Practice

✅ **DO:**
- Selalu gunakan API untuk fetch data di frontend
- Validasi input di server (controller)
- Sanitize output untuk prevent XSS
- Backup database sebelum perubahan besar
- Test di local sebelum production

❌ **DON'T:**
- Jangan hardcode data di view lagi
- Jangan trust user input tanpa validasi
- Jangan ubah struktur table tanpa backup
- Jangan lupa tambah error handling

---

## 🔗 Referensi

- [REST API Documentation](../../API_DOCUMENTATION.md)
- [Database Schema](../../DATABASE_SCHEMA.md)
- [Admin Panel Guide](../../SETUP_GUIDE.md)

---

**Selesai! Peraturan Lab sekarang sudah dinamis dan terkelola dengan baik.** ✨
