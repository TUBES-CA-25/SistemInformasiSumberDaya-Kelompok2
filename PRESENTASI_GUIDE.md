# 📊 PANDUAN PRESENTASI: Sistem Informasi Sumber Daya Laboratorium

---

## 🎯 OVERVIEW PROJECT

### **Nama & Tujuan**
- **Nama**: Sistem Informasi Sumber Daya Laboratorium (Lab Management System)
- **Tujuan**: Mengelola sumber daya manusia (SDM), fasilitas, jadwal praktikum, dan informasi laboratorium secara terintegrasi
- **User Target**: Admin, Kepala Lab, Koordinator, Asisten, dan Mahasiswa (Umum)

### **Bidang Domain**
- **Akademik/Pendidikan**: Manajemen laboratorium di institusi pendidikan
- **Tipe Sistem**: Web Application (Portal Informasi + Admin Dashboard)

---

## 🏗️ ARSITEKTUR & TEKNOLOGI

### **Stack Teknologi**
```
Frontend:
  - HTML5 / CSS3 (Tailwind CSS)
  - JavaScript (Vanilla JS)
  - Responsive Design (Mobile-first)

Backend:
  - PHP Native 7.4+ (PHP Modern)
  - MySQL/MariaDB (Database)

Development & DevOps:
  - Composer (PHP Dependency Manager)
  - .env File (Secure Configuration)
  - PHPOffice/PHPSpreadsheet (Excel Export)
  - Dotenv (Environment Variables)
```

### **Arsitektur Sistem: MVC Pattern**

```
┌─────────────────────────────────────────────────────────┐
│                     CLIENT (BROWSER)                      │
│                 (HTML, CSS, JavaScript)                  │
└────────────────────┬────────────────────────────────────┘
                     │ HTTP Request/Response
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  ROUTER (entry point)                    │
│         (public/index.php → app/config/Router.php)      │
│   Menentukan Controller & Method mana yang dijalankan   │
└────────────────────┬────────────────────────────────────┘
                     │
      ┌──────────────┼──────────────┐
      ▼              ▼              ▼
┌──────────────┐ ┌─────────────┐ ┌──────────────┐
│ CONTROLLERS  │ │ MIDDLEWARE  │ │   HELPERS    │
│              │ │             │ │              │
│ • Request    │ │ AuthCheck   │ │ • Utility    │
│ • Logic      │ │ Validation  │ │ • Functions  │
│ • Response   │ │ Security    │ │              │
└──────┬───────┘ └─────────────┘ └──────────────┘
       │
       ▼
┌──────────────────────────────────────┐
│          MODELS (Database)           │
│  • Query & Retrieve Data             │
│  • Business Logic Queries            │
└──────────────────────────────────────┘
       │
       ▼
┌──────────────────────────────────────┐
│     DATABASE (MySQL/MariaDB)         │
│  • Tabel Users, Alumni, Asisten, dll │
└──────────────────────────────────────┘
```

### **Struktur File Penting**
```
app/
├── config/
│   ├── config.php (Konfigurasi dasar, database, konstanta)
│   ├── Database.php (Connection MySQL)
│   └── Router.php (Routing system)
├── controllers/ (Business logic & request handling)
├── models/ (Database queries & data operations)
├── views/ (HTML templates - Frontend)
│   ├── admin/ (Dashboard admin)
│   └── [public pages]
├── middleware/ (AuthMiddleware - Authentikasi)
└── helpers/ (Helper functions)

public/
├── index.php (Entry point utama)
├── api.php (API endpoint)
└── assets/ (CSS, JS, Images)

database/
└── sistem_manajemen_sumber_daya.sql (Database schema & seed data)
```

---

## 🔑 KONSEP TEKNIS YANG WAJIB DIKETAHUI

### **1. Routing System (Route Handling)**
- **File**: `app/config/Router.php`
- **Konsep**: 
  - Mengubah URL menjadi Controller + Method (eg: `/alumni` → `AlumniController@index()`)
  - Clean URLs (tanpa `.php` di URL)
  - Mendukung parameter dinamis (eg: `/alumni/{id}`)
  
- **Contoh Routes**:
  ```php
  GET /home              → HomeController@index()
  GET /alumni            → AlumniController@index()
  GET /alumni/{id}       → AlumniController@detail($id)
  GET /asisten           → AsistenController@index()
  GET /admin/dashboard   → DashboardController@index() [Protected]
  POST /login            → AuthController@authenticate()
  ```

### **2. MVC Pattern Implementation**

**Model (M)** - Database Layer:
```php
// Setiap model merepresentasikan satu tabel
class AlumniModel extends Model {
    protected $table = 'alumni';
    
    public function getAll()      // SELECT *
    public function getById($id)  // SELECT WHERE id
    public function insert($data) // INSERT INTO
    public function update($id, $data) // UPDATE
}
```

**Controller (C)** - Business Logic:
```php
class AlumniController extends Controller {
    public function index() {
        $alumni = $this->alumniModel->getAll();
        $this->view('alumni/index', ['alumni' => $alumni]);
    }
}
```

**View (V)** - Presentation:
```php
// app/views/alumni/index.php
<?php foreach($alumni as $item): ?>
    <div><?= $item['nama'] ?></div>
<?php endforeach; ?>
```

### **3. Database Schema (Tabel Utama)**

**Tabel Alumni**
- Menyimpan data alumni asisten lab (nama, angkatan, divisi, keahlian)
- Relasi: Banyak alumni dari satu angkatan

**Tabel Asisten**
- Data asisten lab aktif (nama, jabatan, lab, skills, email, foto)
- `isKoordinator`: Flag untuk menandai asisten sebagai koordinator

**Tabel Users**
- Authentikasi (username, password, role)
- Relasi: User bisa Admin, Kepala Lab, Asisten, atau Member biasa

**Tabel Matakuliah**
- Daftar mata kuliah & lab yang mengadakan praktikum

**Tabel Jadwal Praktikum**
- Schedule praktikum dengan informasi waktu, ruangan, asisten

**Tabel Peraturan & Sanksi**
- Tata tertib lab dan system denda/sanksi pelanggaran

---

## 🔐 KEAMANAN & AUTENTIKASI

### **Authentication System**
- **Metode**: Session-based (PHP `$_SESSION`)
- **Password**: Menggunakan `password_hash()` & `password_verify()` (Bcrypt)
- **Flow Login**:
  1. User input username & password
  2. Cek di database (UserModel::getByUsername)
  3. Verifikasi password dengan hash
  4. Set `$_SESSION['user_id']`, `$_SESSION['username']`, `$_SESSION['role']`
  5. Redirect ke dashboard

### **Authorization (Middleware)**
- **File**: `app/middleware/AuthMiddleware.php`
- **Fungsi**: Protect admin routes
- **Konsep**: Sebelum mengakses admin page, check apakah `$_SESSION['user_id']` ada
- **Jika tidak**: Redirect ke login atau return 401 (untuk API)

### **Security Best Practices**
- ✅ Password hashing (Bcrypt)
- ✅ Generic error messages (Username atau Password salah) → Prevent User Enumeration
- ✅ Environment variables (.env) untuk kredensial sensitif
- ✅ Prepared Statements (MySQLi) → Prevent SQL Injection
- ✅ htmlspecialchars() → Prevent XSS
- ✅ Input validation & sanitization

---

## 📱 FRONTEND LOGIC (JavaScript)

### **Main.js - Global Utilities**

File: `public/js/main.js` (Dipanggil di semua halaman)

**Fitur yang diimplementasikan**:

1. **Mobile Menu Toggle** (Hamburger Button)
   - Toggle navbar saat layar mobile
   - Event: Click on `.menu-toggle`
   - Action: Toggle class `.active` pada `.nav-links`

2. **Mobile Dropdown Accordion**
   - Dropdown menu bersifat accordion di mobile (hanya 1 dropdown bisa terbuka)
   - Hanya aktif saat layar ≤ 992px (mobile/tablet)
   - Desktop menggunakan CSS `:hover`

3. **Back to Top Button** (Footer)
   - Tombol muncul saat user scroll > 300px
   - Click → Smooth scroll ke atas (window.scrollTo)

4. **Keyboard Shortcut**
   - `Ctrl + Shift + L + ;` → Redirect ke login page
   - Menggunakan event tracking `keydown` & `keyup`

5. **Hidden Click Trigger**
   - Click footer logo 5x dalam 800ms → Redirect ke login
   - Untuk easter egg / admin login hidden

6. **Global Click Handler**
   - Close navbar & dropdowns saat click di luar area navbar
   - Menggunakan `event.target.closest()` untuk event delegation

---

## 📊 LOGIKA BISNIS UTAMA

### **1. Alumni Management**
- **Flow**: Admin input data alumni → Simpan ke DB → Tampilkan di halaman Alumni
- **Data yang dikelola**:
  - Nama, angkatan, divisi, mata kuliah yang diajar
  - Foto profile, kesan/pesan, keahlian (skills)
  - Email untuk kontak

### **2. Asisten Management**
- **Role**: Asisten Lab & Koordinator Asisten
- **Data**: Nama, jabatan, lab, spesialisasi, bio, skills, email, foto
- **Dashboard Admin**: Kelola data asisten, set sebagai koordinator

### **3. Jadwal Praktikum**
- **Fitur**: 
  - List jadwal praktikum per mata kuliah
  - Tampilkan asisten yang bertugas
  - Info waktu, ruangan, peserta
  
- **Logika**: Query join antara tabel jadwal, matakuliah, asisten

### **4. Informasi Laboratorium (Fasilitas)**
- **Konten**: Deskripsi lab, alat yang tersedia, standar fasilitas
- **Gambar**: Gallery foto lab dengan deskripsi

### **5. Peraturan & Sanksi**
- **Tujuan**: Informasi tata tertib & konsekuensi pelanggaran
- **Data**: Daftar peraturan, tingkatan denda/sanksi
- **Akses**: Public (bisa dilihat mahasiswa)

### **6. Format Penulisan & SOP**
- **Konten**: Standar penulisan laporan, prosedur operasional standar
- **File**: Bisa dalam format PDF/artikel

### **7. User Management (Admin)**
- **CRUD Users**: Create, Read, Update, Delete user akun
- **Role Assignment**: Admin, Kepala Lab, Asisten, Member
- **Password Management**: Admin bisa reset password user

---

## 🎨 UI/UX HIGHLIGHTS

### **Design Philosophy**
- **Responsive**: Mobile-first design dengan Tailwind CSS
- **Modern**: Clean UI dengan component-based styling
- **User-Friendly**: Navigasi intuitif, hamburger menu mobile

### **Key Pages**
```
Public Pages:
├── Home (Landing page)
├── Alumni (Daftar & detail alumni)
├── Asisten (Profil asisten lab)
├── Jadwal Praktikum (Skedule praktikum)
├── Laboratorium (Info fasilitas)
├── Peraturan & Sanksi
├── Format Penulisan & SOP
└── Kontak

Admin Pages:
├── Dashboard (Statistik & overview)
├── Alumni Management (CRUD)
├── Asisten Management (CRUD)
├── Matakuliah Management
├── Jadwal Management
├── Laboratorium Management
├── User Management
├── Settings
└── Logout
```

---

## 🔧 INSTALASI & SETUP (Quick Reference)

```bash
# 1. Clone & masuk folder
cd C:\xampp\htdocs
git clone <repo-url>
cd SistemInformasiSumberDaya-Kelompok2

# 2. Install dependencies
composer install

# 3. Setup .env file
copy .env.example .env
# Edit .env dengan database credentials

# 4. Import database
# Buka phpMyAdmin → Import database/sistem_manajemen_sumber_daya.sql

# 5. Jalankan
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin
```

---

## 📝 FITUR UNGGULAN UNTUK DIPRESENTASIKAN

### **1. Custom MVC Framework**
- ✨ Built from scratch (bukan menggunakan framework seperti Laravel)
- Clean code, mudah dipahami dan dimaintain

### **2. Security Best Practices**
- Password hashing dengan Bcrypt
- Session-based authentication
- Prepared statements (SQL Injection protection)
- Environment variables untuk sensitive data

### **3. Responsive Design**
- Mobile-first approach
- Adaptive navigation (hamburger menu)
- Tailwind CSS untuk styling yang efisien

### **4. Comprehensive Data Management**
- Alumni tracking dengan skills & expertise
- Asisten management dengan role assignment
- Jadwal terintegrasi untuk koordinasi praktikum

### **5. Admin Dashboard**
- Full CRUD operations untuk semua entities
- User management dengan role-based access
- Data visualization & reporting ready

---

## 💡 POIN PENTING SAAT PRESENTASI

### **Highlights Teknis**
1. **Arsitektur**: Jelaskan flow request dari router → controller → model → database
2. **MVC Pattern**: Tunjukkan bagaimana separation of concerns diterapkan
3. **Routing**: Demo URL yang clean (tanpa .php)
4. **Security**: Jelaskan password hashing & session management
5. **Frontend**: Tunjukkan responsive design & mobile menu functionality

### **Highlights Bisnis**
1. **Problem Solved**: Sebelumnya manual → Sekarang terintegrasi
2. **Features**: Alumni DB, Asisten profiling, Jadwal automation
3. **Scalability**: Mudah ditambah fitur baru (add new controller/model)
4. **User Experience**: Intuitif untuk admin & mahasiswa

### **Demo Ideas**
- Login dengan credentials yang ada di database
- Akses public pages (Alumni, Jadwal, Laboratorium)
- Masuk ke admin dashboard
- Lakukan CRUD operation (e.g., edit alumni)
- Tunjukkan mobile responsiveness
- Tunjukkan database structure & queries

---

## 📚 REFERENSI KODE PENTING

### **File yang harus dipahami sebelum presentasi**:

1. **Router Logic** → `app/config/Router.php`
   - Memahami bagaimana routing bekerja

2. **Base Controller** → `app/controllers/Controller.php`
   - Memahami view rendering & response handling

3. **Base Model** → `app/models/Model.php`
   - Memahami CRUD operations

4. **Auth System** → `app/controllers/AuthController.php` + `AuthMiddleware.php`
   - Memahami login flow & authorization

5. **Sample Controller** → `app/controllers/AlumniController.php`
   - Contoh implementasi controller dalam practice

6. **Database Schema** → `database/sistem_manajemen_sumber_daya.sql`
   - Memahami struktur data

7. **Frontend Logic** → `public/js/main.js`
   - Memahami interaksi UI

---

## ❓ KEMUNGKINAN PERTANYAAN & JAWABAN

**Q: Kenapa menggunakan PHP Native daripada Laravel?**
A: Untuk pembelajaran mendalam tentang konsep fundamental MVC, tanpa abstraksi framework. Lebih lightweight untuk project skala kecil-menengah.

**Q: Bagaimana jika password user lupa?**
A: Admin bisa reset password di admin dashboard. Implementasi recovery bisa ditambah di fase future.

**Q: Apa masalah jika server down?**
A: Implementasi backup database & server monitoring bisa ditambahkan. Saat ini focused pada functionality.

**Q: Bagaimana performa dengan banyak user?**
A: Database sudah menggunakan prepared statements (aman) & indexes (fast). Untuk skala besar, bisa optimize dengan caching.

**Q: Bisa hosting di cloud?**
A: Ya, selama server support PHP 7.4+ & MySQL/MariaDB. Tinggal upload file & import database.

---

## 🎓 SUMMARY UNTUK DIEVALUASI

| Aspek | Status |
|-------|--------|
| **MVC Architecture** | ✅ Fully Implemented |
| **Routing System** | ✅ Custom Built |
| **Database** | ✅ Normalized Schema |
| **Authentication** | ✅ Session-based + Password Hash |
| **Authorization** | ✅ Middleware Middleware |
| **CRUD Operations** | ✅ All Entities |
| **Frontend** | ✅ Responsive (Tailwind) |
| **Security** | ✅ SQL Injection, XSS Protection |
| **Code Quality** | ✅ OOP, Clean Code |
| **Documentation** | ✅ Comments & README |

---

**Good luck with your presentation! 🚀**
