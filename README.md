# Sistem Informasi Sumber Daya - Laboratorium

Aplikasi web modern berbasis PHP Native dengan arsitektur **MVC (Model-View-Controller)** untuk manajemen sumber daya laboratorium yang komprehensif.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)

## ✨ Fitur Utama
        
### 🏢 Manajemen Laboratorium
- **Profil Fasilitas**: Informasi lengkap mengenai alat dan ruang laboratorium.
- **Jadwal Praktikum**: Sistem penjadwalan terintegrasi.
- **Peraturan & Sanksi**: Informasi tata tertib dan sanksi pelanggaran.

### 👥 Manajemen SDM (Sumber Daya Manusia)
- **Detail Asisten**: Profil lengkap asisten lab (Bio, Skills, Keahlian).
- **Struktur Organisasi**: Manajemen Kepala Lab dan Koordinator.
- **Data Alumni**: Database alumni asisten laboratorium.

### 🛠️ Fitur Teknis
- **MVC Architecture**: Struktur kode yang rapi dan maintainable.
- **Secure Configuration**: Menggunakan `.env` untuk keamanan kredensial.
- **Clean URLs**: URL yang ramah pengguna dan SEO friendly.
- **RESTful API**: Mendukung integrasi dangan sistem lain.
- **Responsive Design**: Tampilan modern dan responsif dengan Tailwind CSS.

---

## 🚀 Panduan Instalasi (Langkah demi Langkah)

Ikuti panduan ini untuk menjalankan proyek di komputer lokal Anda.

### 1. Prasyarat Sistem
Pastikan Anda telah menginstall:
- **PHP** (versi 7.4 atau lebih baru)
- **Composer** (untuk manajemen dependensi)
- **MySQL / MariaDB** (bisa menggunakan XAMPP/Laragon)
- **Git**

### 2. Clone Project
Buka terminal (Command Prompt/PowerShell/Git Bash) dan jalankan:

```bash
cd C:\xampp\htdocs
git clone https://github.com/kelompok2/SistemInformasiSumberDaya-Kelompok2.git
cd SistemInformasiSumberDaya-Kelompok2
```

### 3. Install Dependensi
Install library yang dibutuhkan (termasuk PHPOffice dan PHPDoEnv) menggunakan Composer:

```bash
composer install
```

### 4. Konfigurasi Environment (.env)
Jangan mengedit file `config.php` secara langsung. Gunakan file environment untuk konfigurasi yang aman.

1.  Salin file contoh konfigurasi:
    ```bash
    cp .env.example .env
    # Atau di Windows Command Prompt:
    copy .env.example .env
    ```

2.  Buka file `.env` dengan text editor dan sesuaikan dengan setting database Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sistem_manajemen_sumber_daya
    DB_USERNAME=root      # Default XAMPP: root
    DB_PASSWORD=          # Default XAMPP: kosong
    ```

### 5. Setup Database
1.  Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Buat database baru bernama `sistem_manajemen_sumber_daya`.
3.  Pilih tab **Import**.
4.  Pilih file `database/database.sql` dari folder proyek.
5.  Klik **Go/Kirim**.

### 6. Jalankan Aplikasi
Buka browser dan akses URL berikut:

```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/
```

Untuk masuk ke Panel Admin:
```
http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin
```

---

## 📂 Struktur Direktori

```
SistemInformasiSumberDaya-Kelompok2/
├── .env                  # 🔒 Konfigurasi Environment (Password DB, dll)
├── app/
│   ├── config/           # Konfigurasi App & Database
│   ├── controllers/      # Logika Aplikasi (Controller)
│   ├── models/           # Akses Database (Model)
│   └── views/            # Tampilan (HTML/PHP)
├── public/               # Folder publik (Akses Web)
│   ├── assets/           # Gambar, Uploads
│   ├── css/              # File CSS
│   └── index.php         # Entry point aplikasi
├── vendor/               # Dependensi Composer
└── database.sql          # File backup database
```

---

## 🔧 Troubleshooting

### Error: "Class 'Dotenv\Dotenv' not found"
**Solusi:** Anda belum menjalankan `composer install`. Jalankan perintah tersebut di terminal folder proyek.

### Error: Database Connection Failed
**Solusi:** Periksa file `.env` Anda. Pastikan `DB_USERNAME` dan `DB_PASSWORD` sesuai dengan settingan MySQL di komputer Anda.

### Tampilan Berantakan / CSS Tidak Muncul
**Solusi:** Pastikan Anda membuka melalui `localhost`, bukan klik ganda file `.php`. Cek juga settingan `APP_URL` di file `.env`.

---

## 👥 Kontributor
Dikerjakan oleh **Kelompok 2** - Teknik Informatika.

## 📝 Lisensi
Project ini dilisensikan di bawah [MIT License](LICENSE).
