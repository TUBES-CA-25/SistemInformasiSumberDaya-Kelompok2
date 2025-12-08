# Dokumentasi Database Schema
## Sistem Informasi Manajemen Sumber Daya

**Database Name:** `sistem_manajemen_sumber_daya`

---

## 📋 Daftar Tabel dan Field

### 1. **Laboratorium**
Tabel yang menyimpan informasi laboratorium yang tersedia.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idLaboratorium` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap laboratorium |
| `nama` | VARCHAR(100) | NOT NULL | Nama laboratorium |
| `idKordinatorAsisten` | INT | FOREIGN KEY → Asisten.idAsisten | ID asisten yang menjadi koordinator lab |
| `deskripsi` | TEXT | NULLABLE | Deskripsi detail laboratorium |
| `gambar` | VARCHAR(255) | NULLABLE | Path/URL gambar laboratorium |
| `jumlahPc` | INT | NULLABLE | Jumlah unit komputer di lab |
| `jumlahKursi` | INT | NULLABLE | Jumlah kursi yang tersedia |

**Relationships:**
- Foreign Key: `idKordinatorAsisten` → `Asisten.idAsisten` (ON DELETE SET NULL)

---

### 2. **Asisten**
Tabel yang menyimpan data asisten praktikum.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idAsisten` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap asisten |
| `nama` | VARCHAR(100) | NOT NULL | Nama lengkap asisten |
| `jurusan` | VARCHAR(100) | NULLABLE | Jurusan atau program studi asisten |
| `email` | VARCHAR(100) | UNIQUE, NULLABLE | Email asisten (unik) |
| `foto` | VARCHAR(255) | NULLABLE | Path/URL foto asisten |
| `statusAktif` | BOOLEAN | DEFAULT TRUE | Status aktif/tidak aktif asisten |

**Relationships:**
- Referenced by: `Laboratorium.idKordinatorAsisten`
- Referenced by: `AsistenMatakuliah.idAsisten`

---

### 3. **Matakuliah**
Tabel yang menyimpan data mata kuliah praktikum.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idMatakuliah` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap mata kuliah |
| `kodeMatakuliah` | VARCHAR(20) | UNIQUE, NOT NULL | Kode mata kuliah (misal: KK101) |
| `namaMatakuliah` | VARCHAR(150) | NOT NULL | Nama lengkap mata kuliah |
| `semester` | INT | NULLABLE | Semester tempat mata kuliah diajarkan |
| `sksKuliah` | INT | NULLABLE | Jumlah SKS (Satuan Kredit Semester) |

**Relationships:**
- Referenced by: `AsistenMatakuliah.idMatakuliah`
- Referenced by: `jadwalPraktikum.idMatakuliah`

---

### 4. **AsistenMatakuliah**
Tabel pivot/junction untuk relasi Many-to-Many antara Asisten dan Matakuliah.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idAsistenMatakuliah` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap record |
| `idAsisten` | INT | NOT NULL, FOREIGN KEY → Asisten.idAsisten | ID asisten |
| `idMatakuliah` | INT | NOT NULL, FOREIGN KEY → Matakuliah.idMatakuliah | ID mata kuliah |
| `tahunAjaran` | VARCHAR(9) | NULLABLE | Tahun akademik (misal: 2024/2025) |
| `semeserMatakuliah` | VARCHAR(10) | NULLABLE | Semester mata kuliah |

**Constraints:**
- UNIQUE KEY: `(idAsisten, idMatakuliah, tahunAjaran)` - Setiap asisten hanya sekali per mata kuliah per tahun akademik
- Foreign Keys: ON DELETE CASCADE

**Relationships:**
- FK: `idAsisten` → `Asisten.idAsisten`
- FK: `idMatakuliah` → `Matakuliah.idMatakuliah`

---

### 5. **jadwalPraktikum**
Tabel yang menyimpan jadwal praktikum untuk setiap mata kuliah di laboratorium.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idJadwal` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap jadwal |
| `idMatakuliah` | INT | NOT NULL, FOREIGN KEY → Matakuliah.idMatakuliah | ID mata kuliah |
| `idLaboratorium` | INT | NOT NULL, FOREIGN KEY → Laboratorium.idLaboratorium | ID laboratorium tempat praktikum |
| `hari` | VARCHAR(20) | NULLABLE | Hari praktikum (misal: Senin, Selasa, dst) |
| `waktuMulai` | TIME | NULLABLE | Jam mulai praktikum |
| `waktuSelesai` | TIME | NULLABLE | Jam selesai praktikum |
| `kelas` | VARCHAR(10) | NULLABLE | Kelas/grup praktikum (misal: A, B, C) |
| `status` | VARCHAR(20) | NULLABLE | Status jadwal (misal: Aktif, Ditunda, Dibatalkan) |

**Relationships:**
- FK: `idMatakuliah` → `Matakuliah.idMatakuliah` (ON DELETE CASCADE)
- FK: `idLaboratorium` → `Laboratorium.idLaboratorium` (ON DELETE CASCADE)

---

### 6. **visMisi**
Tabel yang menyimpan visi dan misi laboratorium/institusi.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idVisMisi` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap record visi misi |
| `visi` | TEXT | NULLABLE | Pernyataan visi |
| `misi` | TEXT | NULLABLE | Pernyataan misi |
| `tanggalPembaruan` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Tanggal dan waktu pembaruan terakhir |

---

### 7. **tataTerib**
Tabel yang menyimpan file tata tertib atau aturan laboratorium.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idTataTerib` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap file tata tertib |
| `namaFile` | VARCHAR(255) | NULLABLE | Nama file tata tertib |
| `uraFile` | VARCHAR(255) | NULLABLE | Uraian/deskripsi file |
| `tanggalUpload` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Tanggal file diupload |

---

### 8. **informasiLab**
Tabel yang menyimpan informasi umum laboratorium (berita, pengumuman, dll).

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `id_informasi` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap informasi |
| `informasi` | TEXT | NULLABLE | Isi informasi/konten |
| `judul_informasi` | VARCHAR(255) | NULLABLE | Judul informasi |
| `tipe_informasi` | VARCHAR(50) | NULLABLE | Tipe informasi (misal: berita, pengumuman, info_penting) |
| `is_informasi` | BOOLEAN | DEFAULT TRUE | Status aktif/tidak aktif informasi |
| `tanggal_dibuat` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Tanggal informasi dibuat |
| `tanggal_berlaku_akhir` | DATETIME | NULLABLE | Tanggal berlaku akhir informasi |

---

### 9. **integrsiWeb**
Tabel yang menyimpan integrasi/link ke website atau sistem eksternal.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idIntegrasi` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap integrasi |
| `namaWeb` | VARCHAR(100) | NULLABLE | Nama website/sistem eksternal |
| `urlWeb` | VARCHAR(255) | NULLABLE | URL/link ke website |
| `deskripsi` | TEXT | NULLABLE | Deskripsi integrasi atau website |

---

### 10. **Manajemen**
Tabel yang menyimpan data anggota manajemen/kepemimpinan laboratorium.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idManajemen` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk setiap anggota manajemen |
| `nama` | VARCHAR(100) | NOT NULL | Nama lengkap anggota manajemen |
| `jabatan` | VARCHAR(100) | NULLABLE | Jabatan/posisi dalam manajemen |
| `foto` | VARCHAR(255) | NULLABLE | Path/URL foto profil |

---

### 11. **kontakt**
Tabel yang menyimpan informasi kontak laboratorium.

| Field | Type | Constraint | Keterangan |
|-------|------|-----------|-----------|
| `idKontak` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik untuk informasi kontak |
| `alamat` | VARCHAR(255) | NULLABLE | Alamat laboratorium |
| `nomorTelepon` | VARCHAR(20) | NULLABLE | Nomor telepon laboratorium |
| `email` | VARCHAR(100) | NULLABLE | Email laboratorium |
| `urlMap` | VARCHAR(255) | NULLABLE | URL maps/lokasi laboratorium |
| `tanggalPembaruan` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Tanggal pembaruan informasi kontak |

---

## 🔗 Entity Relationship Diagram (ERD)

```
Laboratorium
├── idKordinatorAsisten → Asisten.idAsisten
│
Asisten
├── ← idAsisten (referensi dari Laboratorium.idKordinatorAsisten)
└── idAsisten → AsistenMatakuliah.idAsisten

Matakuliah
├── idMatakuliah → AsistenMatakuliah.idMatakuliah
└── idMatakuliah → jadwalPraktikum.idMatakuliah

AsistenMatakuliah
├── idAsisten → Asisten.idAsisten
└── idMatakuliah → Matakuliah.idMatakuliah

jadwalPraktikum
├── idMatakuliah → Matakuliah.idMatakuliah
└── idLaboratorium → Laboratorium.idLaboratorium

Standalone Tables:
├── visMisi (Visi & Misi)
├── tataTerib (File Tata Tertib)
├── informasiLab (Informasi Laboratorium)
├── integrsiWeb (Integrasi Website)
├── Manajemen (Data Manajemen)
└── kontakt (Informasi Kontak)
```

---

## 📊 Ringkasan Tabel

| No. | Tabel | Jumlah Field | Keterangan |
|-----|-------|--------------|-----------|
| 1 | Laboratorium | 7 | Data laboratorium dengan koordinator |
| 2 | Asisten | 6 | Data asisten praktikum |
| 3 | Matakuliah | 5 | Data mata kuliah praktikum |
| 4 | AsistenMatakuliah | 5 | Relasi many-to-many Asisten-Matakuliah |
| 5 | jadwalPraktikum | 8 | Jadwal praktikum di laboratorium |
| 6 | visMisi | 3 | Visi dan misi laboratorium |
| 7 | tataTerib | 3 | File tata tertib |
| 8 | informasiLab | 6 | Informasi dan pengumuman laboratorium |
| 9 | integrsiWeb | 3 | Integrasi website eksternal |
| 10 | Manajemen | 3 | Data manajemen/kepemimpinan |
| 11 | kontakt | 5 | Informasi kontak laboratorium |

**Total: 11 Tabel, 63 Field**

---

## 🔐 Foreign Key Relationships

| Tabel Anak | Field | Tabel Parent | Field | ON DELETE |
|-----------|-------|-------------|-------|-----------|
| Laboratorium | idKordinatorAsisten | Asisten | idAsisten | SET NULL |
| AsistenMatakuliah | idAsisten | Asisten | idAsisten | CASCADE |
| AsistenMatakuliah | idMatakuliah | Matakuliah | idMatakuliah | CASCADE |
| jadwalPraktikum | idMatakuliah | Matakuliah | idMatakuliah | CASCADE |
| jadwalPraktikum | idLaboratorium | Laboratorium | idLaboratorium | CASCADE |

---

## 📑 Indexing Strategy

Untuk meningkatkan performa query, sudah dibuat index pada field-field berikut:

```sql
CREATE INDEX idx_asisten_email ON Asisten(email);
CREATE INDEX idx_matakuliah_kode ON Matakuliah(kodeMatakuliah);
CREATE INDEX idx_jadwal_matakuliah ON jadwalPraktikum(idMatakuliah);
CREATE INDEX idx_jadwal_lab ON jadwalPraktikum(idLaboratorium);
CREATE INDEX idx_asisten_matakuliah ON AsistenMatakuliah(idAsisten);
CREATE INDEX idx_informasi_tipe ON informasiLab(tipe_informasi);
```

---

## 💾 Data Type Conventions

- **ID Fields**: INT dengan AUTO_INCREMENT
- **Nama/Teks Pendek**: VARCHAR (20-255)
- **Teks Panjang**: TEXT
- **Email/URL**: VARCHAR(255)
- **Boolean**: BOOLEAN (0/1)
- **DateTime**: DATETIME dengan default CURRENT_TIMESTAMP
- **Status**: VARCHAR (untuk nilai enumerasi string)

---

## 📝 Catatan Penting

1. **Foreign Key Constraints**: Semua relasi parent-child sudah dienkapsulasi dengan foreign key constraints. Pastikan data parent sudah ada sebelum memasukkan data child.

2. **Unique Constraints**: 
   - `Asisten.email` harus unik
   - `Matakuliah.kodeMatakuliah` harus unik
   - `AsistenMatakuliah` memiliki unique composite key pada `(idAsisten, idMatakuliah, tahunAjaran)`

3. **Cascading Deletes**: Ketika record parent dihapus, record child akan otomatis dihapus kecuali pada `Laboratorium.idKordinatorAsisten` yang di-set NULL.

4. **Timestamp Fields**: Field `tanggalPembaruan`, `tanggal_dibuat`, dan `tanggalUpload` secara otomatis terisi dengan waktu saat record dibuat/diupdate.

5. **Empty Tables**: Tabel `visMisi`, `tataTerib`, `informasiLab`, `integrsiWeb`, `Manajemen`, dan `kontakt` merupakan tabel konfigurasi yang setiap institusi biasanya memiliki 1 record (atau beberapa untuk informasiLab).

---

## 🚀 SQL untuk Melihat Schema

```sql
-- Melihat struktur tabel
DESCRIBE Laboratorium;

-- Melihat foreign key
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'sistem_manajemen_sumber_daya';

-- Melihat index
SHOW INDEX FROM Laboratorium;

-- Melihat semua tabel
SHOW TABLES FROM sistem_manajemen_sumber_daya;
```

---

**Dokumentasi dibuat:** 8 Desember 2025  
**Database Version:** 1.0  
**Status:** ✅ Schema Lengkap & Tervalidasi
