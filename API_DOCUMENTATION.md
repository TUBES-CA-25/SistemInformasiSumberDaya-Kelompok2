# API Documentation

## Base URL
```
http://localhost/SistemManagementSumberDaya/public/api.php
```

## Response Format
Semua response dalam format JSON dengan struktur:
```json
{
    "status": "success|error",
    "message": "message",
    "data": {}
}
```

## Authentication
API ini saat ini belum menggunakan authentication. Tambahkan auth middleware jika diperlukan.

---

## Endpoints

### Health Check
```
GET /health
```

### Laboratorium
```
GET    /laboratorium              - Get all
GET    /laboratorium/{id}         - Get by ID
POST   /laboratorium              - Create
PUT    /laboratorium/{id}         - Update
DELETE /laboratorium/{id}         - Delete
```

**Request Body (POST/PUT):**
```json
{
    "nama": "Lab Komputer",
    "deskripsi": "Laboratorium Komputer...",
    "gambar": "lab1.jpg",
    "jumlahKursi": 30
}
```

### Asisten
```
GET    /asisten                   - Get all
GET    /asisten/{id}              - Get by ID
GET    /asisten/{id}/matakuliah   - Get matakuliah
POST   /asisten                   - Create
PUT    /asisten/{id}              - Update
DELETE /asisten/{id}              - Delete
```

**Request Body (POST/PUT):**
```json
{
    "nama": "Nama Asisten",
    "jurusan": "Teknik Informatika",
    "email": "asisten@univ.ac.id",
    "foto": "asisten1.jpg",
    "statusAktif": true
}
```

### Matakuliah
```
GET    /matakuliah                - Get all
GET    /matakuliah/{id}           - Get by ID
GET    /matakuliah/{id}/asisten   - Get asisten
POST   /matakuliah                - Create
PUT    /matakuliah/{id}           - Update
DELETE /matakuliah/{id}           - Delete
```

**Request Body (POST/PUT):**
```json
{
    "kodeMatakuliah": "TI101",
    "namaMatakuliah": "Pemrograman Dasar",
    "semester": 1,
    "sksKuliah": 3
}
```

### Jadwal Praktikum
```
GET    /jadwal                    - Get all
GET    /jadwal/{id}               - Get by ID
POST   /jadwal                    - Create
PUT    /jadwal/{id}               - Update
DELETE /jadwal/{id}               - Delete
```

**Request Body (POST/PUT):**
```json
{
    "idMatakuliah": 1,
    "idLaboratorium": 1,
    "hari": "Senin",
    "waktuMulai": "08:00:00",
    "waktuSelesai": "10:00:00",
    "kelas": "A",
    "status": "active"
}
```

### Informasi Lab
```
GET    /informasi                 - Get all
GET    /informasi/{id}            - Get by ID
GET    /informasi/tipe/{type}     - Get by type
POST   /informasi                 - Create
PUT    /informasi/{id}            - Update
DELETE /informasi/{id}            - Delete
```

**Request Body (POST/PUT):**
```json
{
    "informasi": "Konten informasi",
    "judul_informasi": "Judul",
    "tipe_informasi": "pengumuman",
    "is_informasi": true
}
```

### Visi Misi
```
GET    /visi-misi                 - Get latest
POST   /visi-misi                 - Create
```

**Request Body (POST):**
```json
{
    "visi": "Visi text",
    "misi": "Misi text"
}
```

### Manajemen
```
GET    /manajemen                 - Get all
GET    /manajemen/{id}            - Get by ID
POST   /manajemen                 - Create
PUT    /manajemen/{id}            - Update
DELETE /manajemen/{id}            - Delete
```

**Request Body (POST/PUT):**
```json
{
    "nama": "Nama Manager",
    "jabatan": "Kepala Lab",
    "foto": "manager1.jpg"
}
```

### Kontak
```
GET    /kontak                    - Get latest
POST   /kontak                    - Create
PUT    /kontak/{id}               - Update
```

**Request Body (POST/PUT):**
```json
{
    "alamat": "Jl. Universitas No. 1",
    "nomorTelepon": "0274123456",
    "email": "lab@univ.ac.id",
    "urlMap": "https://maps.google.com/..."
}
```

---

## Error Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `404` - Not Found
- `500` - Internal Server Error

---

## Contoh Request dengan cURL

### GET All Laboratorium
```bash
curl -X GET "http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium"
```

### POST Create Laboratorium
```bash
curl -X POST "http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Lab Komputer",
    "deskripsi": "Laboratorium Komputer",
    "jumlahKursi": 30
  }'
```

### PUT Update Laboratorium
```bash
curl -X PUT "http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium/1" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Lab Komputer Updated",
    "jumlahKursi": 35
  }'
```

### DELETE Laboratorium
```bash
curl -X DELETE "http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium/1"
```

---

## Setup Database

1. Buka phpMyAdmin di `http://localhost/phpmyadmin`
2. Jalankan script `database.sql` yang ada di root project
3. Pastikan nama database sesuai: `sistem_manajemen_sumber_daya`

---

## Development Notes

- Semua file model ada di `app/models/`
- Semua file controller ada di `app/controllers/`
- Router handling ada di `app/config/Router.php`
- Setiap request secara otomatis ter-route ke controller yang sesuai

---
