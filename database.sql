-- ========================================
-- Database: sistem_manajemen_sumber_daya
-- Created: 2025-12-08
-- ========================================

CREATE DATABASE IF NOT EXISTS sistem_manajemen_sumber_daya;
USE sistem_manajemen_sumber_daya;

-- ========================================
-- Table: peraturan_lab
-- ========================================
CREATE TABLE IF NOT EXISTS peraturan_lab (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255),
    urutan INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- Table: Asisten
-- ========================================
CREATE TABLE IF NOT EXISTS Asisten (
    idAsisten INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    foto VARCHAR(255),
    statusAktif BOOLEAN DEFAULT TRUE
);

-- Table: Laboratorium
CREATE TABLE IF NOT EXISTS Laboratorium (
    idLaboratorium INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    idKordinatorAsisten INT,
    deskripsi TEXT,
    gambar VARCHAR(255),
    jumlahPc INT,
    jumlahKursi INT,
    FOREIGN KEY (idKordinatorAsisten) REFERENCES Asisten(idAsisten) ON DELETE SET NULL
);

-- ========================================
-- Table: Matakuliah
-- ========================================
CREATE TABLE IF NOT EXISTS Matakuliah (
    idMatakuliah INT PRIMARY KEY AUTO_INCREMENT,
    kodeMatakuliah VARCHAR(20) UNIQUE NOT NULL,
    namaMatakuliah VARCHAR(150) NOT NULL,
    semester INT,
    sksKuliah INT
);

-- ========================================
-- Table: AsistenMatakuliah (Many-to-Many)
-- ========================================
CREATE TABLE IF NOT EXISTS AsistenMatakuliah (
    idAsistenMatakuliah INT PRIMARY KEY AUTO_INCREMENT,
    idAsisten INT NOT NULL,
    idMatakuliah INT NOT NULL,
    tahunAjaran VARCHAR(9),
    semeserMatakuliah VARCHAR(10),
    FOREIGN KEY (idAsisten) REFERENCES Asisten(idAsisten) ON DELETE CASCADE,
    FOREIGN KEY (idMatakuliah) REFERENCES Matakuliah(idMatakuliah) ON DELETE CASCADE,
    UNIQUE KEY unique_asisten_matakuliah (idAsisten, idMatakuliah, tahunAjaran)
);

-- ========================================
-- Table: jadwalPraktikum
-- ========================================
CREATE TABLE IF NOT EXISTS jadwalPraktikum (
    idJadwal INT PRIMARY KEY AUTO_INCREMENT,
    idMatakuliah INT NOT NULL,
    idLaboratorium INT NOT NULL,
    hari VARCHAR(20),
    waktuMulai TIME,
    waktuSelesai TIME,
    kelas VARCHAR(10),
    status VARCHAR(20),
    FOREIGN KEY (idMatakuliah) REFERENCES Matakuliah(idMatakuliah) ON DELETE CASCADE,
    FOREIGN KEY (idLaboratorium) REFERENCES Laboratorium(idLaboratorium) ON DELETE CASCADE
);

-- ========================================
-- Table: visMisi
-- ========================================
CREATE TABLE IF NOT EXISTS visMisi (
    idVisMisi INT PRIMARY KEY AUTO_INCREMENT,
    visi TEXT,
    misi TEXT,
    tanggalPembaruan DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- Table: tataTerib
-- ========================================
CREATE TABLE IF NOT EXISTS tataTerib (
    idTataTerib INT PRIMARY KEY AUTO_INCREMENT,
    namaFile VARCHAR(255),
    uraFile VARCHAR(255),
    tanggalUpload DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- Table: informasiLab
-- ========================================
CREATE TABLE IF NOT EXISTS informasiLab (
    id_informasi INT PRIMARY KEY AUTO_INCREMENT,
    informasi TEXT,
    judul_informasi VARCHAR(255),
    tipe_informasi VARCHAR(50),
    is_informasi BOOLEAN DEFAULT TRUE,
    tanggal_dibuat DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_berlaku_akhir DATETIME
);

-- ========================================
-- Table: integrsiWeb
-- ========================================
CREATE TABLE IF NOT EXISTS integrsiWeb (
    idIntegrasi INT PRIMARY KEY AUTO_INCREMENT,
    namaWeb VARCHAR(100),
    urlWeb VARCHAR(255),
    deskripsi TEXT
);

-- ========================================
-- Table: Manajemen
-- ========================================
CREATE TABLE IF NOT EXISTS Manajemen (
    idManajemen INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100),
    foto VARCHAR(255)
);

-- ========================================
-- Table: kontakt
-- ========================================
CREATE TABLE IF NOT EXISTS kontakt (
    idKontak INT PRIMARY KEY AUTO_INCREMENT,
    alamat VARCHAR(255),
    nomorTelepon VARCHAR(20),
    email VARCHAR(100),
    urlMap VARCHAR(255),
    tanggalPembaruan DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- Table: sanksi_lab
-- ========================================
CREATE TABLE IF NOT EXISTS sanksi_lab (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255),
    urutan INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- Table: alumni
-- ========================================
CREATE TABLE IF NOT EXISTS alumni (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(150) NOT NULL,
    angkatan YEAR NOT NULL,
    divisi VARCHAR(100),
    foto VARCHAR(255),
    pekerjaan VARCHAR(150),
    perusahaan VARCHAR(150),
    kesan_pesan TEXT,
    tahun_lulus VARCHAR(50),
    keahlian TEXT,
    linkedin VARCHAR(255),
    portfolio VARCHAR(255),
    email VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- ========================================
-- Data Peraturan Lab (Tata Tertib)
-- ========================================
INSERT INTO tataTerib (namaFile, uraFile) VALUES
('Disiplin Waktu Kehadiran', 'Praktikan wajib hadir 15 menit sebelum kegiatan praktikum dimulai untuk persiapan. Toleransi keterlambatan maksimal adalah 10 menit. Jika melebihi batas tersebut, praktikan tidak diperkenankan masuk dan dianggap tidak hadir (Alpa).'),
('Aturan Berpakaian & Identitas', 'Wajib mengenakan seragam kemeja putih (atau sesuai ketentuan fakultas), celana/rok kain hitam, dan bersepatu tertutup. Praktikan juga wajib membawa dan mengenakan Kartu Tanda Mahasiswa (KTM) atau Kartu Asisten selama berada di lingkungan laboratorium.'),
('Menjaga Kebersihan & Ketertiban', 'Dilarang keras membawa makanan, minuman, atau benda tajam ke dalam ruang laboratorium. Sampah wajib dibuang pada tempat yang disediakan. Praktikan dilarang membuat kegaduhan yang dapat mengganggu konsentrasi praktikan lain.'),
('Penggunaan Fasilitas Komputer', 'Dilarang mengubah pengaturan (setting) komputer, menginstal software tanpa izin, atau memindahkan perangkat keras (mouse, keyboard) antar meja. Segala kerusakan yang disebabkan oleh kelalaian praktikan akan dikenakan sanksi penggantian.');

-- ========================================
-- Indexes untuk performa query
-- ========================================
CREATE INDEX idx_asisten_email ON Asisten(email);
CREATE INDEX idx_matakuliah_kode ON Matakuliah(kodeMatakuliah);
CREATE INDEX idx_jadwal_matakuliah ON jadwalPraktikum(idMatakuliah);
CREATE INDEX idx_jadwal_lab ON jadwalPraktikum(idLaboratorium);
CREATE INDEX idx_asisten_matakuliah ON AsistenMatakuliah(idAsisten);
CREATE INDEX idx_informasi_tipe ON informasiLab(tipe_informasi);