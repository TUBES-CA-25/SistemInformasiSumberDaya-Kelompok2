-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Des 2025 pada 01.41
-- Versi server: 10.4.32-MariaDB-log
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_manajemen_sumber_daya`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alumni`
--

CREATE TABLE `alumni` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `angkatan` year(4) NOT NULL,
  `divisi` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `pekerjaan` varchar(150) DEFAULT NULL,
  `perusahaan` varchar(150) DEFAULT NULL,
  `kesan_pesan` text DEFAULT NULL,
  `tahun_lulus` varchar(50) DEFAULT NULL,
  `keahlian` text DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alumni`
--

INSERT INTO `alumni` (`id`, `nama`, `angkatan`, `divisi`, `foto`, `pekerjaan`, `perusahaan`, `kesan_pesan`, `tahun_lulus`, `keahlian`, `linkedin`, `portfolio`, `email`, `created_at`, `updated_at`) VALUES
(2, 'Siti Nurhaliza bapak', '2023', 'Divisi Jaringan', 'https://placehold.co/300x300/667eea/white?text=Siti', 'Network Engineer', 'Telkom Indonesia', 'Awal yang baik untuk karir di dunia telekomunikasi. Terima kasih atas bimbingan selama menjadi asisten.', '2023', 'Cisco, Networking, Linux', 'https://linkedin.com/in/siti-nurhaliza', 'https://siti-portfolio.com', 'siti@email.com', '2025-12-15 02:28:26', '2025-12-19 11:05:25'),
(3, 'Ahmad Pratama', '2022', 'Divisi Multimedia', 'https://placehold.co/300x300/667eea/white?text=Ahmad', 'UI/UX Designer', 'Gojek', 'Laboratorium membentuk kreativitas saya dan membuat saya percaya bahwa desain itu penting.', '2022', 'Figma, Adobe XD, UI/UX Design, Web Design', 'https://linkedin.com/in/ahmad-pratama', 'https://ahmad-design.com', 'ahmad@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(4, 'Dwi Putri Lestari', '2022', 'Koordinator Lab', 'https://placehold.co/300x300/667eea/white?text=Dwi', 'ASN Kominfo', 'Kementerian Komunikasi dan Informatika', 'Bangga bisa melayani negara dengan keahlian yang didapat dari laboratorium. Semoga lab terus berkembang.', '2022', 'Public Policy, IT Governance, Java', 'https://linkedin.com/in/dwi-putri', 'https://dwi-portfolio.com', 'dwi@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(5, 'Budi Santoso', '2021', 'Divisi Database', 'https://placehold.co/300x300/667eea/white?text=Budi', 'Data Analyst', 'Bukalapak', 'Belajar banyak tentang data dan database management. Menjadi asisten adalah keputusan terbaik saya.', '2021', 'SQL, Python, Data Analysis, PostgreSQL', 'https://linkedin.com/in/budi-santoso', 'https://budi-analytics.com', 'budi@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(6, 'Eka Sulistyaningrum', '2021', 'Divisi Web', 'https://placehold.co/300x300/667eea/white?text=Eka', 'Frontend Developer', 'Shopee', 'Pengalaman di lab mengajarkan saya tentang deadline management dan code quality yang baik.', '2021', 'JavaScript, React, Vue, HTML/CSS', 'https://linkedin.com/in/eka-sulistya', 'https://eka-frontend.com', 'eka@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(7, 'Fajar Wijaya', '2020', 'Divisi Sistem', 'https://placehold.co/300x300/667eea/white?text=Fajar', 'DevOps Engineer', 'Jago.com', 'Infrastruktur yang kami bangun di lab menjadi fondasi pengetahuan saya tentang system administration.', '2020', 'AWS, Docker, Kubernetes, Linux', 'https://linkedin.com/in/fajar-wijaya', 'https://fajar-devops.com', 'fajar@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(8, 'Hani Khoirunisa', '2020', 'Divisi Multimedia', 'https://placehold.co/300x300/667eea/white?text=Hani', 'Motion Graphics Designer', 'Trans Media', 'Lab memberikan saya portofolio yang solid untuk masuk ke industri kreatif.', '2020', 'After Effects, Premiere Pro, Animation, UI Design', 'https://linkedin.com/in/hani-khoirunisa', 'https://hani-motion.com', 'hani@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(9, 'Rizwan Ardiyan', '2023', 'Koordinator Lab', 'alumni_1766078894_8729.png', 'Software Engineer', 'Tokopedia', 'Pengalaman yang luar biasa menjadi asisten di lab ini. Banyak ilmu yang saya dapatkan terutama dalam teamwork dan kepemimpinan.', '2023', 'PHP, Laravel, React, MySQL, Docker', 'https://linkedin.com/in/rizwan', 'https://rizwan.dev', 'rizwan@email.com', '2025-12-19 01:20:54', '2025-12-19 01:28:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `asisten`
--

CREATE TABLE `asisten` (
  `idAsisten` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `statusAktif` tinyint(1) DEFAULT 1,
  `isKoordinator` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `asisten`
--

INSERT INTO `asisten` (`idAsisten`, `nama`, `jurusan`, `email`, `foto`, `statusAktif`, `isKoordinator`) VALUES
(2, 'Budi Santoso', 'Teknik Informatika', 'budi@mail.com', NULL, 1, 0),
(3, 'Siti Nurhaliza', 'Sistem Informasi', 'siti@mail.com', NULL, 1, 0),
(4, 'Ahmad Wijaya', 'Teknik Komputer', 'ahmad@mail.com', NULL, 1, 0),
(5, 'Rina Puspita', 'Informatika', 'rina@mail.com', NULL, 1, 0),
(6, 'M RIZWAN', 'Informatika', 'rizwan@example.com', 'asisten_1766077436_4546.png', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `asistenmatakuliah`
--

CREATE TABLE `asistenmatakuliah` (
  `idAsistenMatakuliah` int(11) NOT NULL,
  `idAsisten` int(11) NOT NULL,
  `idMatakuliah` int(11) NOT NULL,
  `tahunAjaran` varchar(9) DEFAULT NULL,
  `semeserMatakuliah` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `informasilab`
--

CREATE TABLE `informasilab` (
  `id_informasi` int(11) NOT NULL,
  `informasi` text DEFAULT NULL,
  `judul_informasi` varchar(255) DEFAULT NULL,
  `tipe_informasi` varchar(50) DEFAULT NULL,
  `is_informasi` tinyint(1) DEFAULT 1,
  `tanggal_dibuat` datetime DEFAULT current_timestamp(),
  `tanggal_berlaku_akhir` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `integrsiweb`
--

CREATE TABLE `integrsiweb` (
  `idIntegrasi` int(11) NOT NULL,
  `namaWeb` varchar(100) DEFAULT NULL,
  `urlWeb` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwalpraktikum`
--

CREATE TABLE `jadwalpraktikum` (
  `idJadwal` int(11) NOT NULL,
  `idMatakuliah` int(11) NOT NULL,
  `idLaboratorium` int(11) NOT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `waktuMulai` time DEFAULT NULL,
  `waktuSelesai` time DEFAULT NULL,
  `kelas` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwalpraktikum`
--

INSERT INTO `jadwalpraktikum` (`idJadwal`, `idMatakuliah`, `idLaboratorium`, `hari`, `waktuMulai`, `waktuSelesai`, `kelas`, `status`) VALUES
(1, 8, 7, 'Senin', '08:00:00', '10:00:00', 'A', 'Aktif'),
(2, 9, 8, 'Selasa', '10:00:00', '12:00:00', 'B', 'Aktif'),
(3, 10, 10, 'Rabu', '11:20:00', '12:40:00', 'C', 'Aktif'),
(4, 9, 9, 'Selasa', '10:00:00', '12:00:00', 'B', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontakt`
--

CREATE TABLE `kontakt` (
  `idKontak` int(11) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `nomorTelepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `urlMap` varchar(255) DEFAULT NULL,
  `tanggalPembaruan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laboratorium`
--

CREATE TABLE `laboratorium` (
  `idLaboratorium` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `idKordinatorAsisten` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `jumlahPc` int(11) DEFAULT NULL,
  `jumlahKursi` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT 0,
  `processor` varchar(255) DEFAULT NULL,
  `ram` varchar(100) DEFAULT NULL,
  `storage` varchar(100) DEFAULT NULL,
  `gpu` varchar(255) DEFAULT NULL,
  `monitor` varchar(255) DEFAULT NULL,
  `software` text DEFAULT NULL,
  `fasilitas_pendukung` text DEFAULT NULL,
  `koordinator_nama` varchar(255) DEFAULT NULL,
  `koordinator_foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laboratorium`
--

INSERT INTO `laboratorium` (`idLaboratorium`, `nama`, `idKordinatorAsisten`, `deskripsi`, `gambar`, `jumlahPc`, `jumlahKursi`, `lokasi`, `kapasitas`, `processor`, `ram`, `storage`, `gpu`, `monitor`, `software`, `fasilitas_pendukung`, `koordinator_nama`, `koordinator_foto`) VALUES
(7, 'Microcontroller', 4, 'Tempat untuk belajar Microcontroller', NULL, 20, 25, 'Gedung Fakultas Ilmu Komputer lantai 2', 25, 'Intel i5', '8GB', '256GB SSD', 'Integrated', '21 inch', 'Visual Studio Code', 'AC, WiFi', 'M. Rizwan', 'koordinator_1766067968_69440f0027653.png'),
(8, 'Computer Vision', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Data Science', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Start Up', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'IoT', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `manajemen`
--

CREATE TABLE `manajemen` (
  `idManajemen` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `manajemen`
--

INSERT INTO `manajemen` (`idManajemen`, `nama`, `jabatan`, `foto`) VALUES
(1, 'Dr. Ahmad Rizki, S.Kom, M.Kom', 'Kepala Lab Rekayasa Perangkat Lunak', 'manajemen_1765770548_9775.png'),
(2, 'Ir. Budi Santoso, M.T', 'Kepala Lab Jaringan Komputer', 'manajemen_1765770649_8909.png'),
(3, 'Siti Nurhaliza, S.T, M.Kom', 'Kepala Lab Multimedia', 'manajemen_1765770661_5875.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `matakuliah`
--

CREATE TABLE `matakuliah` (
  `idMatakuliah` int(11) NOT NULL,
  `kodeMatakuliah` varchar(20) NOT NULL,
  `namaMatakuliah` varchar(150) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `sksKuliah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `matakuliah`
--

INSERT INTO `matakuliah` (`idMatakuliah`, `kodeMatakuliah`, `namaMatakuliah`, `semester`, `sksKuliah`) VALUES
(8, 'TI_MICRO', 'Microcontroller', NULL, NULL),
(9, 'TI_SD', 'Struktur Data', NULL, NULL),
(10, 'SI_PBO', 'Pemrograman Berorientasi Objek', NULL, NULL),
(11, 'TI_BD2', 'Basis Data II', NULL, NULL),
(12, 'TI_ALPRO', 'Algoritma Pemrograman', NULL, NULL),
(13, 'SI_PTI', 'Pengantar Teknologi Informasi', NULL, NULL),
(14, 'SI_JARKOM', 'Jaringan Komputer', NULL, NULL),
(15, 'TI_PP', 'Pengenalan Pemrograman', NULL, NULL),
(16, 'TI_MOBILE', 'Pemrograman Mobile', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peraturan_lab`
--

CREATE TABLE `peraturan_lab` (
  `id` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peraturan_lab`
--

INSERT INTO `peraturan_lab` (`id`, `judul`, `deskripsi`, `gambar`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 'Tata Tertib Umum Laboratorium', 'Peraturan dasar yang harus dipatuhi oleh semua pengguna laboratorium teknologi informasi', NULL, 1, '2025-12-18 23:53:18', '2025-12-18 23:53:18'),
(2, 'Penggunaan Peralatan Lab', 'Ketentuan penggunaan komputer, jaringan, dan peralatan elektronik di laboratorium', NULL, 2, '2025-12-18 23:53:18', '2025-12-18 23:53:18'),
(3, 'Jadwal dan Reservasi Ruangan', 'Prosedur pemesanan dan penggunaan ruang laboratorium untuk kegiatan praktikum', NULL, 3, '2025-12-18 23:53:18', '2025-12-18 23:53:18'),
(4, 'Keamanan dan Kebersihan', 'Aturan menjaga keamanan data, perangkat, dan kebersihan lingkungan laboratorium', NULL, 4, '2025-12-18 23:53:18', '2025-12-18 23:53:18'),
(5, 'Sanksi dan Pelanggaran', 'Konsekuensi yang berlaku bagi mahasiswa yang melanggar tata tertib laboratorium', NULL, 5, '2025-12-18 23:53:18', '2025-12-18 23:53:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sanksi_lab`
--

CREATE TABLE `sanksi_lab` (
  `id` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sanksi_lab`
--

INSERT INTO `sanksi_lab` (`id`, `judul`, `deskripsi`, `gambar`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 'Keterlambatan Hadir', 'Praktikan yang terlambat lebih dari 10 menit tidak diperkenankan mengikuti praktikum pada sesi tersebut. Status kehadiran akan dicatat sebagai Alpa (Tidak Hadir), yang dapat mempengaruhi kelulusan mata kuliah praktikum.', 'sanksi_1765767567_5524.png', 0, '2025-12-15 10:11:55', '2025-12-15 10:59:27'),
(2, 'Merusak Fasilitas', 'Jika terjadi kerusakan pada hardware (monitor, keyboard, mouse, dll) akibat kelalaian atau kesengajaan, praktikan wajib melakukan Penggantian Barang dengan spesifikasi yang sama atau setara dalam kurun waktu 1x24 jam.', NULL, 0, '2025-12-15 10:11:55', '2025-12-15 10:11:55'),
(3, 'Pelanggaran Seragam & Atribut', 'Praktikan yang tidak mengenakan seragam sesuai ketentuan atau tidak membawa Kartu Tanda Mahasiswa (KTM) akan mendapatkan Teguran Keras dan pengurangan poin nilai kedisiplinan pada sesi tersebut.', NULL, 0, '2025-12-15 10:11:55', '2025-12-15 10:11:55'),
(4, 'Kecurangan (Plagiasi/Mencontek)', 'Segala bentuk kecurangan seperti mencontek saat ujian atau menyalin tugas teman (plagiasi) akan dikenakan sanksi Nilai E (Tidak Lulus) secara otomatis pada mata kuliah praktikum yang bersangkutan.', NULL, 0, '2025-12-15 10:11:55', '2025-12-15 10:11:55'),
(6, 'Test Update', 'Test update desc', 'sanksi_1765767514_3994.png', 0, '2025-12-15 10:16:08', '2025-12-15 10:58:34'),
(11, 'fafdfafdsa', 'fafdafda', 'sanksi_1765767543_8483.png', 0, '2025-12-15 10:48:12', '2025-12-15 10:59:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tataterib`
--

CREATE TABLE `tataterib` (
  `idTataTerib` int(11) NOT NULL,
  `namaFile` varchar(255) DEFAULT NULL,
  `uraFile` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggalUpload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tataterib`
--

INSERT INTO `tataterib` (`idTataTerib`, `namaFile`, `uraFile`, `gambar`, `tanggalUpload`) VALUES
(1, 'Disiplin Waktu Kehadiran', 'Praktikan wajib hadir 15 menit sebelum kegiatan praktikum dimulai untuk persiapan. Toleransi keterlambatan maksimal adalah 10 menit. Jika melebihi batas tersebut, praktikan tidak diperkenankan masuk dan dianggap tidak hadir (Alpa).', 'peraturan_1765768258_4534.png', '2025-12-15 09:51:10'),
(2, 'Aturan Berpakaian & Identitas', 'Wajib mengenakan seragam kemeja putih (atau sesuai ketentuan fakultas), celana/rok kain hitam, dan bersepatu tertutup. Praktikan juga wajib membawa dan mengenakan Kartu Tanda Mahasiswa (KTM) atau Kartu Asisten selama berada di lingkungan laboratorium.', NULL, '2025-12-15 09:51:10'),
(3, 'Menjaga Kebersihan & Ketertiban', 'Dilarang keras membawa makanan, minuman, atau benda tajam ke dalam ruang laboratorium. Sampah wajib dibuang pada tempat yang disediakan. Praktikan dilarang membuat kegaduhan yang dapat mengganggu konsentrasi praktikan lain.', NULL, '2025-12-15 09:51:10'),
(4, 'Penggunaan Fasilitas Komputer', 'Dilarang mengubah pengaturan (setting) komputer, menginstal software tanpa izin, atau memindahkan perangkat keras (mouse, keyboard) antar meja. Segala kerusakan yang disebabkan oleh kelalaian praktikan akan dikenakan sanksi penggantian.', NULL, '2025-12-15 09:51:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vismisi`
--

CREATE TABLE `vismisi` (
  `idVisMisi` int(11) NOT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `tanggalPembaruan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `asisten`
--
ALTER TABLE `asisten`
  ADD PRIMARY KEY (`idAsisten`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_asisten_email` (`email`);

--
-- Indeks untuk tabel `asistenmatakuliah`
--
ALTER TABLE `asistenmatakuliah`
  ADD PRIMARY KEY (`idAsistenMatakuliah`),
  ADD UNIQUE KEY `unique_asisten_matakuliah` (`idAsisten`,`idMatakuliah`,`tahunAjaran`),
  ADD KEY `idMatakuliah` (`idMatakuliah`),
  ADD KEY `idx_asisten_matakuliah` (`idAsisten`);

--
-- Indeks untuk tabel `informasilab`
--
ALTER TABLE `informasilab`
  ADD PRIMARY KEY (`id_informasi`),
  ADD KEY `idx_informasi_tipe` (`tipe_informasi`);

--
-- Indeks untuk tabel `integrsiweb`
--
ALTER TABLE `integrsiweb`
  ADD PRIMARY KEY (`idIntegrasi`);

--
-- Indeks untuk tabel `jadwalpraktikum`
--
ALTER TABLE `jadwalpraktikum`
  ADD PRIMARY KEY (`idJadwal`),
  ADD KEY `idx_jadwal_matakuliah` (`idMatakuliah`),
  ADD KEY `idx_jadwal_lab` (`idLaboratorium`);

--
-- Indeks untuk tabel `kontakt`
--
ALTER TABLE `kontakt`
  ADD PRIMARY KEY (`idKontak`);

--
-- Indeks untuk tabel `laboratorium`
--
ALTER TABLE `laboratorium`
  ADD PRIMARY KEY (`idLaboratorium`),
  ADD KEY `idKordinatorAsisten` (`idKordinatorAsisten`);

--
-- Indeks untuk tabel `manajemen`
--
ALTER TABLE `manajemen`
  ADD PRIMARY KEY (`idManajemen`);

--
-- Indeks untuk tabel `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`idMatakuliah`),
  ADD UNIQUE KEY `kodeMatakuliah` (`kodeMatakuliah`),
  ADD KEY `idx_matakuliah_kode` (`kodeMatakuliah`);

--
-- Indeks untuk tabel `peraturan_lab`
--
ALTER TABLE `peraturan_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sanksi_lab`
--
ALTER TABLE `sanksi_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tataterib`
--
ALTER TABLE `tataterib`
  ADD PRIMARY KEY (`idTataTerib`);

--
-- Indeks untuk tabel `vismisi`
--
ALTER TABLE `vismisi`
  ADD PRIMARY KEY (`idVisMisi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `asisten`
--
ALTER TABLE `asisten`
  MODIFY `idAsisten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `asistenmatakuliah`
--
ALTER TABLE `asistenmatakuliah`
  MODIFY `idAsistenMatakuliah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `informasilab`
--
ALTER TABLE `informasilab`
  MODIFY `id_informasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `integrsiweb`
--
ALTER TABLE `integrsiweb`
  MODIFY `idIntegrasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwalpraktikum`
--
ALTER TABLE `jadwalpraktikum`
  MODIFY `idJadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kontakt`
--
ALTER TABLE `kontakt`
  MODIFY `idKontak` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laboratorium`
--
ALTER TABLE `laboratorium`
  MODIFY `idLaboratorium` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `manajemen`
--
ALTER TABLE `manajemen`
  MODIFY `idManajemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `idMatakuliah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `peraturan_lab`
--
ALTER TABLE `peraturan_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sanksi_lab`
--
ALTER TABLE `sanksi_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tataterib`
--
ALTER TABLE `tataterib`
  MODIFY `idTataTerib` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `vismisi`
--
ALTER TABLE `vismisi`
  MODIFY `idVisMisi` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `asistenmatakuliah`
--
ALTER TABLE `asistenmatakuliah`
  ADD CONSTRAINT `asistenmatakuliah_ibfk_1` FOREIGN KEY (`idAsisten`) REFERENCES `asisten` (`idAsisten`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistenmatakuliah_ibfk_2` FOREIGN KEY (`idMatakuliah`) REFERENCES `matakuliah` (`idMatakuliah`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwalpraktikum`
--
ALTER TABLE `jadwalpraktikum`
  ADD CONSTRAINT `jadwalpraktikum_ibfk_1` FOREIGN KEY (`idMatakuliah`) REFERENCES `matakuliah` (`idMatakuliah`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwalpraktikum_ibfk_2` FOREIGN KEY (`idLaboratorium`) REFERENCES `laboratorium` (`idLaboratorium`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laboratorium`
--
ALTER TABLE `laboratorium`
  ADD CONSTRAINT `laboratorium_ibfk_1` FOREIGN KEY (`idKordinatorAsisten`) REFERENCES `asisten` (`idAsisten`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
