<?php
/**
 * Setup Database Script
 * Jalankan file ini sekali untuk membuat database dan semua tabel
 */

$host = 'localhost';
$user = 'root';
$password = '';

// Connect tanpa database terlebih dahulu
$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// SQL untuk membuat database dan tabel
$sql = <<<EOT
-- Create Database
CREATE DATABASE IF NOT EXISTS sistem_manajemen_sumber_daya;
USE sistem_manajemen_sumber_daya;

-- Table: Laboratorium
CREATE TABLE IF NOT EXISTS Laboratorium (
    idLaboratorium INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    jumlahKursi INT
);

-- Table: Asisten
CREATE TABLE IF NOT EXISTS Asisten (
    idAsisten INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    foto VARCHAR(255),
    statusAktif BOOLEAN DEFAULT TRUE
);

-- Table: Matakuliah
CREATE TABLE IF NOT EXISTS Matakuliah (
    idMatakuliah INT PRIMARY KEY AUTO_INCREMENT,
    kodeMatakuliah VARCHAR(20) UNIQUE NOT NULL,
    namaMatakuliah VARCHAR(150) NOT NULL,
    semester INT,
    sksKuliah INT
);

-- Table: AsistenMatakuliah
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

-- Table: jadwalPraktikum
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

-- Table: visMisi
CREATE TABLE IF NOT EXISTS visMisi (
    idVisMisi INT PRIMARY KEY AUTO_INCREMENT,
    visi TEXT,
    misi TEXT,
    tanggalPembaruan DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: tataTerib
CREATE TABLE IF NOT EXISTS tataTerib (
    idTataTerib INT PRIMARY KEY AUTO_INCREMENT,
    namaFile VARCHAR(255),
    uraFile VARCHAR(255),
    tanggalUpload DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: informasiLab
CREATE TABLE IF NOT EXISTS informasiLab (
    id_informasi INT PRIMARY KEY AUTO_INCREMENT,
    informasi TEXT,
    judul_informasi VARCHAR(255),
    tipe_informasi VARCHAR(50),
    is_informasi BOOLEAN DEFAULT TRUE,
    tanggal_dibuat DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_berlaku_akhir DATETIME
);

-- Table: integrsiWeb
CREATE TABLE IF NOT EXISTS integrsiWeb (
    idIntegrasi INT PRIMARY KEY AUTO_INCREMENT,
    namaWeb VARCHAR(100),
    urlWeb VARCHAR(255),
    deskripsi TEXT
);

-- Table: Manajemen
CREATE TABLE IF NOT EXISTS Manajemen (
    idManajemen INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100),
    foto VARCHAR(255)
);

-- Table: kontakt
CREATE TABLE IF NOT EXISTS kontakt (
    idKontak INT PRIMARY KEY AUTO_INCREMENT,
    alamat VARCHAR(255),
    nomorTelepon VARCHAR(20),
    email VARCHAR(100),
    urlMap VARCHAR(255),
    tanggalPembaruan DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create Indexes
CREATE INDEX idx_asisten_email ON Asisten(email);
CREATE INDEX idx_matakuliah_kode ON Matakuliah(kodeMatakuliah);
CREATE INDEX idx_jadwal_matakuliah ON jadwalPraktikum(idMatakuliah);
CREATE INDEX idx_jadwal_lab ON jadwalPraktikum(idLaboratorium);
CREATE INDEX idx_asisten_matakuliah ON AsistenMatakuliah(idAsisten);
CREATE INDEX idx_informasi_tipe ON informasiLab(tipe_informasi);
EOT;

// Execute multiple queries
if ($conn->multi_query($sql)) {
    // Consume all results
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Database dan semua tabel berhasil dibuat!',
        'database' => 'sistem_manajemen_sumber_daya'
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error: ' . $conn->error
    ], JSON_PRETTY_PRINT);
}

$conn->close();
?>
