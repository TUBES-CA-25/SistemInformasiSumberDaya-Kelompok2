# API Documentation - Sistem Informasi Manajemen Sumber Daya

**Base URL:** `http://localhost/SistemManagementSumberDaya/public/api.php`

**Response Format:** JSON dengan struktur standar:
```json
{
  "status": "success|error",
  "message": "Deskripsi",
  "data": {}
}
```

---

## 📋 Daftar Endpoint (12 Total)

| No | Endpoint | Method | Deskripsi |
|---|---|---|---|
| 1 | /health | GET | Cek status API |
| 2 | /laboratorium | GET, POST, PUT, DELETE | Data laboratorium |
| 3 | /asisten | GET, POST, PUT, DELETE | Data asisten praktikum |
| 4 | /matakuliah | GET, POST, PUT, DELETE | Data mata kuliah |
| 5 | /asisten-matakuliah | GET, POST, PUT, DELETE | Relasi asisten-matakuliah |
| 6 | /jadwal | GET, POST, PUT, DELETE | Jadwal praktikum |
| 7 | /visi-misi | GET, POST, PUT, DELETE | Visi dan misi |
| 8 | /tata-tertib | GET, POST, PUT, DELETE | File tata tertib |
| 9 | /informasi | GET, POST, PUT, DELETE | Berita dan pengumuman |
| 10 | /integrasi-web | GET, POST, PUT, DELETE | Link sistem eksternal |
| 11 | /manajemen | GET, POST, PUT, DELETE | Data manajemen |
| 12 | /kontak | GET, POST, PUT, DELETE | Informasi kontak |

---

## 1. Health Check

### GET /health
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/health
```

---

## 2. Laboratorium

### GET /laboratorium
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium
```

### GET /laboratorium/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium/1
```

### POST /laboratorium
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Lab Programming",
    "idKordinatorAsisten": 1,
    "deskripsi": "Laboratorium pemrograman",
    "gambar": "lab.jpg",
    "jumlahPc": 25,
    "jumlahKursi": 40
  }'
```

**Required:** `nama` | **Optional:** `idKordinatorAsisten`, `deskripsi`, `gambar`, `jumlahPc`, `jumlahKursi`

### PUT /laboratorium/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium/1 \
  -H "Content-Type: application/json" \
  -d '{"nama": "Lab Updated", "jumlahPc": 30}'
```

### DELETE /laboratorium/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/laboratorium/1
```

---

## 3. Asisten

### GET /asisten
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/asisten
```

### GET /asisten/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/asisten/1
```

### GET /asisten/{id}/matakuliah
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/asisten/1/matakuliah
```

### POST /asisten
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/asisten \
  -H "Content-Type: application/json" \
  -d '{"nama": "Budi", "jurusan": "TI", "email": "budi@example.com", "statusAktif": true}'
```

**Required:** `nama`, `email` | **Optional:** `jurusan`, `foto`, `statusAktif`

### PUT /asisten/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/asisten/1 \
  -H "Content-Type: application/json" \
  -d '{"statusAktif": false}'
```

### DELETE /asisten/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/asisten/1
```

---

## 4. Matakuliah

### GET /matakuliah
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/matakuliah
```

### GET /matakuliah/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/matakuliah/1
```

### GET /matakuliah/{id}/asisten
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/matakuliah/1/asisten
```

### POST /matakuliah
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/matakuliah \
  -H "Content-Type: application/json" \
  -d '{"kodeMatakuliah": "KK101", "namaMatakuliah": "Pemrograman Dasar", "semester": 1, "sksKuliah": 3}'
```

**Required:** `kodeMatakuliah` (unik), `namaMatakuliah` | **Optional:** `semester`, `sksKuliah`

### PUT /matakuliah/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/matakuliah/1 \
  -H "Content-Type: application/json" \
  -d '{"sksKuliah": 4}'
```

### DELETE /matakuliah/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/matakuliah/1
```

---

## 5. AsistenMatakuliah

### GET /asisten-matakuliah
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/asisten-matakuliah
```

### GET /asisten-matakuliah/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/asisten-matakuliah/1
```

### POST /asisten-matakuliah
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/asisten-matakuliah \
  -H "Content-Type: application/json" \
  -d '{"idAsisten": 1, "idMatakuliah": 1, "tahunAjaran": "2024/2025", "semeserMatakuliah": "Ganjil"}'
```

**Required:** `idAsisten` (FK), `idMatakuliah` (FK) | **Optional:** `tahunAjaran`, `semeserMatakuliah`

### PUT /asisten-matakuliah/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/asisten-matakuliah/1 \
  -H "Content-Type: application/json" \
  -d '{"tahunAjaran": "2025/2026"}'
```

### DELETE /asisten-matakuliah/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/asisten-matakuliah/1
```

---

## 6. Jadwal Praktikum

### GET /jadwal
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/jadwal
```

### GET /jadwal/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/jadwal/1
```

### POST /jadwal
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/jadwal \
  -H "Content-Type: application/json" \
  -d '{"idMatakuliah": 1, "idLaboratorium": 1, "hari": "Senin", "waktuMulai": "08:00:00", "waktuSelesai": "10:00:00", "kelas": "A", "status": "Aktif"}'
```

**Required:** `idMatakuliah` (FK), `idLaboratorium` (FK) | **Optional:** `hari`, `waktuMulai`, `waktuSelesai`, `kelas`, `status`

### PUT /jadwal/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/jadwal/1 \
  -H "Content-Type: application/json" \
  -d '{"hari": "Selasa", "status": "Ditunda"}'
```

### DELETE /jadwal/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/jadwal/1
```

---

## 7. Visi Misi

### GET /visi-misi
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/visi-misi
```

### GET /visi-misi/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/visi-misi/1
```

### POST /visi-misi
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/visi-misi \
  -H "Content-Type: application/json" \
  -d '{"visi": "Visi text", "misi": "Misi text"}'
```

**Required:** `visi`, `misi`

### PUT /visi-misi/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/visi-misi/1 \
  -H "Content-Type: application/json" \
  -d '{"visi": "Updated"}'
```

### DELETE /visi-misi/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/visi-misi/1
```

---

## 8. Tata Tertib

### GET /tata-tertib
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/tata-tertib
```

### GET /tata-tertib/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/tata-tertib/1
```

### POST /tata-tertib
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/tata-tertib \
  -H "Content-Type: application/json" \
  -d '{"namaFile": "Tata-Tertib.pdf", "uraFile": "Peraturan lab"}'
```

**Required:** `namaFile` | **Optional:** `uraFile`

### PUT /tata-tertib/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/tata-tertib/1 \
  -H "Content-Type: application/json" \
  -d '{"uraFile": "Updated"}'
```

### DELETE /tata-tertib/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/tata-tertib/1
```

---

## 9. Informasi Lab

### GET /informasi
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/informasi
```

### GET /informasi/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/informasi/1
```

### GET /informasi/tipe/{type}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/informasi/tipe/pengumuman
```

**Tipe:** `berita`, `pengumuman`, `info_penting`

### POST /informasi
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/informasi \
  -H "Content-Type: application/json" \
  -d '{"informasi": "Konten", "judul_informasi": "Judul", "tipe_informasi": "pengumuman", "is_informasi": true}'
```

**Required:** `informasi`, `tipe_informasi` | **Optional:** `judul_informasi`, `is_informasi`, `tanggal_berlaku_akhir`

### PUT /informasi/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/informasi/1 \
  -H "Content-Type: application/json" \
  -d '{"is_informasi": false}'
```

### DELETE /informasi/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/informasi/1
```

---

## 10. Integrasi Web

### GET /integrasi-web
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/integrasi-web
```

### GET /integrasi-web/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/integrasi-web/1
```

### POST /integrasi-web
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/integrasi-web \
  -H "Content-Type: application/json" \
  -d '{"namaWeb": "Portal", "urlWeb": "https://portal.ac.id", "deskripsi": "Desc"}'
```

**Required:** `namaWeb` | **Optional:** `urlWeb`, `deskripsi`

### PUT /integrasi-web/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/integrasi-web/1 \
  -H "Content-Type: application/json" \
  -d '{"deskripsi": "Updated"}'
```

### DELETE /integrasi-web/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/integrasi-web/1
```

---

## 11. Manajemen

### GET /manajemen
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/manajemen
```

### GET /manajemen/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/manajemen/1
```

### POST /manajemen
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/manajemen \
  -H "Content-Type: application/json" \
  -d '{"nama": "Dr. Budi", "jabatan": "Kepala Lab", "foto": "budi.jpg"}'
```

**Required:** `nama` | **Optional:** `jabatan`, `foto`

### PUT /manajemen/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/manajemen/1 \
  -H "Content-Type: application/json" \
  -d '{"jabatan": "Wakil Kepala"}'
```

### DELETE /manajemen/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/manajemen/1
```

---

## 12. Kontak

### GET /kontak
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/kontak
```

### GET /kontak/{id}
```bash
curl http://localhost/SistemManagementSumberDaya/public/api.php/kontak/1
```

### POST /kontak
```bash
curl -X POST http://localhost/SistemManagementSumberDaya/public/api.php/kontak \
  -H "Content-Type: application/json" \
  -d '{"alamat": "Jl. Teknik", "nomorTelepon": "0251123456", "email": "lab@ac.id", "urlMap": "https://maps..."}'
```

**Optional:** `alamat`, `nomorTelepon`, `email`, `urlMap`

### PUT /kontak/{id}
```bash
curl -X PUT http://localhost/SistemManagementSumberDaya/public/api.php/kontak/1 \
  -H "Content-Type: application/json" \
  -d '{"nomorTelepon": "0251654321"}'
```

### DELETE /kontak/{id}
```bash
curl -X DELETE http://localhost/SistemManagementSumberDaya/public/api.php/kontak/1
```

---

## HTTP Status Codes

| Code | Meaning |
|------|----------|
| 200 | OK |
| 201 | Created |
| 400 | Bad Request |
| 404 | Not Found |
| 500 | Server Error |

---

## ⚠️ Error Response

```json
{
  "status": "error",
  "message": "Field required: nama",
  "data": null
}
```

---

## Foreign Key Requirements

Pastikan parent record sudah ada:

- **Laboratorium.idKordinatorAsisten** → Asisten.idAsisten harus ada
- **AsistenMatakuliah.idAsisten** → Asisten.idAsisten harus ada
- **AsistenMatakuliah.idMatakuliah** → Matakuliah.idMatakuliah harus ada
- **JadwalPraktikum.idMatakuliah** → Matakuliah.idMatakuliah harus ada
- **JadwalPraktikum.idLaboratorium** → Laboratorium.idLaboratorium harus ada

---

**API Version:** 1.0  
**Last Updated:** 11 Desember 2025  
**Status:** ✅ Production Ready
