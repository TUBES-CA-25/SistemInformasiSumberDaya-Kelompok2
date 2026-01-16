-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jan 2026 pada 13.22
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
  `mata_kuliah` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kesan_pesan` text DEFAULT NULL,
  `keahlian` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `urutanTampilan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alumni`
--

INSERT INTO `alumni` (`id`, `nama`, `angkatan`, `divisi`, `mata_kuliah`, `foto`, `kesan_pesan`, `keahlian`, `email`, `created_at`, `updated_at`, `urutanTampilan`) VALUES
(18, 'Arisa Tien Hardianti, S.Kom', '2020', 'Asisten', 'Basis Data I', NULL, '', 'Basis Data, SQL, Pengajaran', '', '2026-01-08 15:23:35', '2026-01-10 11:30:22', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `asisten`
--

CREATE TABLE `asisten` (
  `idAsisten` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `lab` varchar(100) DEFAULT NULL,
  `spesialisasi` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `statusAktif` varchar(20) DEFAULT 'Asisten',
  `isKoordinator` tinyint(1) DEFAULT 0,
  `urutanTampilan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `asisten`
--

INSERT INTO `asisten` (`idAsisten`, `nama`, `jurusan`, `jabatan`, `kategori`, `lab`, `spesialisasi`, `bio`, `skills`, `email`, `foto`, `statusAktif`, `isKoordinator`, `urutanTampilan`) VALUES
(13, 'Adam Adnan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Koordinator Laboratorium', '[]', 'adamadnan.iclabs@umi.ac.id', 'asisten_1767845309_3424.jpg', 'Asisten', 1, 0),
(14, 'Dewi Ernita Rahma', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Basisdata, Struktur Data, Algoritma Pemrograman', '[]', 'dewiernitarahma.iclabs@umi.ac.id', 'asisten_1767845981_7966.png', 'Asisten', 0, 0),
(15, 'Farid Wadji Mufti', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Basisdata, Microcontroller', '[]', 'faridwajdimufli.iclabs@umi.ac.id', 'asisten_1767846034_4202.jpg', 'Asisten', 0, 0),
(16, 'Julisa', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Basisdata, Algoritma Pemrograman, Pengenalan Pemrograman', '[]', 'julisa.iclabs@umi.ac.id', 'asisten_1767846124_1097.jpg', 'Asisten', 0, 0),
(17, 'Maharani Safwa Andini', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Microcontroller, Pengenalan Pemrograman', '[]', 'maharanisahwaandini.iclabs@umi.ac.id', 'asisten_1767846492_4226.jpg', 'Asisten', 0, 0),
(18, 'Ahmad Mufli Ramadhan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Pengenalan Pemrograman, Struktur Data, PBO', '[]', 'ahmadmufliramadhan.iclabs@umi.ac.id', 'asisten_1767846654_8523.jpeg', 'Asisten', 0, 0),
(19, 'Muhammad Alif Maulana. R', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Jaringan Komputer, Basisdata, Pengenalan Pemrograman', '[]', 'muhalifmaulanaar.iclabs@umi.ac.id', 'asisten_1767846799_3970.png', 'Asisten', 0, 0),
(20, 'Tazkira Amalia', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Pengantar Teknologi Informasi, Struktur Data, PBO', '[]', 'tazkirahamalia.iclabs@umi.ac.id', 'asisten_1767846911_7163.jpg', 'Asisten', 0, 0),
(21, 'Wahyu Kadri Rahmat Suat', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Microcontroller, Basisdata, Struktur Data', '[]', 'wahyukadrirahmatsuat.iclabs@umi.ac.id', 'asisten_1767846974_2116.png', 'Asisten', 0, 0),
(22, 'Aan Maulana Sampe', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pemrograman Berorientasi Objek, Jaringan Komputer', '[\"Mobile\"]', '13020230081@student.umi.ac.id', 'asisten_1767847118_5230.png', 'CA', 0, 3),
(23, 'Andi Ahsan Ashuri', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[\"Mobile\"]', '13020230224@student.umi.ac.id', 'asisten_1767847163_2915.png', 'CA', 0, 3),
(24, 'Andi Ikhlas Mallomo', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Struktur Data, Basisdata', '[\"Mobile\"]', '13020230251@student.umi.ac.id', 'asisten_1767847230_1778.jpg', 'CA', 0, 3),
(25, 'Andi Rifqi Aunur Rahman', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Pengenalan Pemrograman, Basisdata', '[]', '13020230219@student.umi.ac.id', 'asisten_1767847292_2856.png', 'CA', 0, 0),
(26, 'Farah Tsabitaputri Az Zahra', 'Teknik Informatika', NULL, NULL, NULL, NULL, '', '[]', '13020230268@student.umi.ac.id', 'asisten_1767847358_6722.jpg', 'CA', 0, 0),
(27, 'Firli Anastasya Hafid', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata, Algoritma Pemrograman', '[]', '13020230241@student.umi.ac.id', 'asisten_1767847436_9017.jpg', 'CA', 0, 0),
(28, 'Hendrawan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pengenalan Pemrograman', '[]', '13020230309@student.umi.ac.id', 'asisten_1767847556_5460.jpg', 'CA', 0, 0),
(29, 'Ichwal', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Jaringan Komputer, PBO, Struktur Data', '[]', '13020230049@student.umi.ac.id', 'asisten_1767847731_2445.jpg', 'CA', 0, 0),
(30, 'Laode Muhammad Dhaifan Kasyfillah', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pengenalan Pemrograman', '[]', '13020230232@student.umi.ac.id', 'asisten_1767847789_6687.jpg', 'CA', 0, 0),
(31, 'Muh. Fatwah Fajriansyah M.', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pengantar Teknologi Informasi', '[]', '13020230319@student.umi.ac.id', 'asisten_1767847858_7787.jpg', 'CA', 0, 0),
(32, 'Muhammad Nur Fuad', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '[]', '13020230030@student.umi.ac.id', 'asisten_1767847914_6148.jpg', 'CA', 0, 0),
(33, 'Muhammad Rafli', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '[]', '13020230290@student.umi.ac.id', 'asisten_1767848005_7910.jpg', 'CA', 0, 0),
(34, 'Muhammad Rifky Saputra Scania', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '[]', '13020230193@student.umi.ac.id', 'asisten_1767848075_4678.jpg', 'CA', 0, 0),
(35, 'M. Rizwan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman, Basisdata', '', '13020230100@student.umi.ac.id', 'asisten_1767848152_4734.png', 'CA', 0, 0),
(36, 'Nahwa Kaka Saputra Anggareksa', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[\"Design Grafis\", \"UI/UX\", \"React\", \"Web Development\"]', '13020230187@student.umi.ac.id', 'asisten_1767848210_2417.jpg', 'CA', 0, 0),
(37, 'Nurfajri Mukmin Saputra', 'Sistem Informasi', NULL, NULL, NULL, NULL, 'Asisten 2 Struktur Data, Pengantar Teknologi Informasi', '[]', '13120230033@student.umi.ac.id', 'asisten_1767848284_9117.jpg', 'CA', 0, 0),
(38, 'Raihan Nur Rizqillah', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '[]', '13020230306@student.umi.ac.id', 'asisten_1767848364_3306.png', 'CA', 0, 0),
(39, 'Rizqi Ananda Jalil', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata, Pengenalan Pemrograman', '[]', '13020230244@student.umi.ac.id', 'asisten_1767848419_6838.jpg', 'CA', 0, 0),
(40, 'Siti Safira Tawetubun', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[]', '13020230217@student.umi.ac.id', 'asisten_1767848474_6358.png', 'CA', 0, 0),
(41, 'Sitti Lutfia', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata', '[]', '13020230255@student.umi.ac.id', 'asisten_1767848519_7591.jpeg', 'CA', 0, 0),
(42, 'Sitti Nurhalimah', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata', '[]', '13020230297@student.umi.ac.id', 'asisten_1767848577_9784.jpeg', 'CA', 0, 0),
(43, 'Thalita Sherly Putri Jasmin', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pemrograman Berorientasi Objek', '[]', '13020230096@student.umi.ac.id', 'asisten_1767848643_6824.png', 'CA', 0, 0),
(44, 'Zaki Falihin Ayyubi', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata', '[]', '13020230253@student.umi.ac.id', 'asisten_1767848688_9669.jpg', 'CA', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `format_penulisan`
--

CREATE TABLE `format_penulisan` (
  `id_format` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `kategori` enum('pedoman','unduhan') DEFAULT 'unduhan',
  `link_external` varchar(255) DEFAULT NULL,
  `tanggal_update` date DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'ri-file-info-line',
  `warna` varchar(30) DEFAULT 'icon-blue',
  `urutan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `format_penulisan`
--

INSERT INTO `format_penulisan` (`id_format`, `judul`, `deskripsi`, `file`, `kategori`, `link_external`, `tanggal_update`, `icon`, `warna`, `urutan`) VALUES
(11, 'Lembar Kerja & Margin', 'Kertas: A4 (21,0 x 29,7 cm), 70 gram.\r\nMargin: Kiri 4cm, Atas 4cm, Kanan 3cm, Bawah 3cm.\r\nWajib menggunakan Watermark logo ICLabs.', NULL, 'pedoman', '', '2026-01-07', 'ri-article-line', 'icon-blue', 3),
(12, 'Teknik Penulisan', 'Wajib tulis tangan dengan pulpen warna HITAM.\r\nSetiap soal dari modul wajib ditulis ulang.\r\nJawaban ditulis tepat di bawah soal terkait.\r\nGambar harus jelas dan pas di dalam margin.', NULL, 'pedoman', '', '2026-01-07', 'ri-pencil-line', 'icon-pink', 2),
(14, 'Buku Panduan Lengkap', '', '1767861278_18d2a6ba.pdf', 'unduhan', 'https://drive.google.com/file/d/1a3_E6rvW_4pDJkSTVTApltDkZSnnFCez/view?usp=sharing', '2026-01-08', '', 'icon-blue', 1),
(15, 'Watermark ICLabs', '', '1767861288_8bccc44f.png', 'unduhan', 'https://drive.google.com/file/d/1aO9zL2nn06jKxTwYEeX2v0QzazE-z4Pv/view?usp=sharing', '2026-01-08', '', 'icon-blue', 2),
(16, 'Logo Resmi UMI', '', '1767861296_2998a0c1.png', 'unduhan', 'https://drive.google.com/file/d/1CiX5QyzBXMCJFplUeMDYDMmE2tBZJm4D/view?usp=sharing', '2026-01-08', '', 'icon-blue', 3),
(17, 'Kelengkapan Sampul', 'Judul: Kapital, Bold, Font 14 (Tengah Atas).\r\nLogo UMI ukuran 5x6 cm (300 dpi).\r\nData: Nama, Stambuk, Frekuensi, Dosen, & Asisten.', NULL, 'pedoman', '', '2026-01-07', 'ri-file-list-3-line', 'icon-red', 1);

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
  `kelas` varchar(50) DEFAULT NULL,
  `idLaboratorium` int(11) NOT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `waktuMulai` time DEFAULT NULL,
  `waktuSelesai` time DEFAULT NULL,
  `dosen` varchar(255) DEFAULT NULL,
  `asisten1` varchar(255) DEFAULT NULL,
  `asisten2` varchar(255) DEFAULT NULL,
  `frekuensi` varchar(150) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwalpraktikum`
--

INSERT INTO `jadwalpraktikum` (`idJadwal`, `idMatakuliah`, `kelas`, `idLaboratorium`, `hari`, `waktuMulai`, `waktuSelesai`, `dosen`, `asisten1`, `asisten2`, `frekuensi`, `tanggal`, `status`) VALUES
(305, 8, 'A1,A2,A3', 29, 'Senin', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Nur Fuad', 'TI_MICRO-5', NULL, 'Aktif'),
(306, 9, 'A3', 27, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'TI_SD-3', NULL, 'Aktif'),
(307, 9, 'A4', 26, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Sitti Lutfia', 'TI_SD-4', NULL, 'Aktif'),
(308, 10, 'A1', 23, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Thalita Sherly Putri Jasmin', 'SI_PBO-1', NULL, 'Aktif'),
(309, 10, 'B1', 24, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Tazkirah Amaliah', 'SI_PBO-2', NULL, 'Aktif'),
(310, 8, 'A3', 29, 'Senin', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Maharani Safwa Andini', 'Farid Wajdi Mufti', 'TI_MICRO-3', NULL, 'Aktif'),
(311, 11, 'A7', 27, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_BD2-7', NULL, 'Aktif'),
(312, 11, 'A8', 26, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Maharani Safwa Andini', 'Andi Rifqi Aunur Rahman', 'TI_BD2-8', NULL, 'Aktif'),
(313, 8, 'A5', 29, 'Senin', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_MICRO-6', NULL, 'Aktif'),
(314, 9, 'B2', 24, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Farid Wajdi Mufti', 'Aan Maulana Sampe', 'TI_SD-10', NULL, 'Aktif'),
(315, 9, 'B1', 23, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Dewi Ernita Rahma', 'Andi Ahsan Ashuri', 'TI_SD-9', NULL, 'Aktif'),
(316, 8, 'A6', 29, 'Senin', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Raihan Nur Rizqillah', 'TI_MICRO-7', NULL, 'Aktif'),
(317, 9, 'A1', 23, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_SD-1', NULL, 'Aktif'),
(318, 9, 'A2', 24, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Wahyu Kadri Rahmat Suat', 'Maharani Safwa Andini', 'TI_SD-2', NULL, 'Aktif'),
(319, 11, 'A3', 27, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Sitti Lutfia', 'TI_BD2-3', NULL, 'Aktif'),
(320, 11, 'A5', 26, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Farah Tsabitaputri Az Zahra', 'TI_BD2-5', NULL, 'Aktif'),
(321, 13, 'A1', 23, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'SI_PTI-1', NULL, 'Aktif'),
(322, 13, 'B1', 24, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Ahmad Mufli Ramadhan', 'Muh. Fatwah Fajriansyah M.', 'SI_PTI-2', NULL, 'Aktif'),
(323, 8, 'B2', 29, 'Selasa', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Muhammad Nur Fuad', 'TI_MICRO-11', NULL, 'Aktif'),
(324, 14, 'B1', 27, 'Selasa', '09:40:00', '12:10:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Aan Maulana Sampe', 'SI_JARKOM-2', NULL, 'Aktif'),
(325, 12, 'A3', 23, 'Selasa', '10:30:00', '14:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-3', NULL, 'Aktif'),
(326, 14, 'A1', 27, 'Selasa', '13:00:00', '15:30:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'SI_JARKOM-1', NULL, 'Aktif'),
(327, 11, 'A1', 23, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_BD2-1', NULL, 'Aktif'),
(328, 11, 'A2', 24, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Rizqi Ananda Jalil', 'TI_BD2-2', NULL, 'Aktif'),
(329, 8, 'B1', 29, 'Selasa', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Muhammad Rafli', 'TI_MICRO-10', NULL, 'Aktif'),
(330, 9, 'B3', 27, 'Sabtu', '07:00:00', '09:30:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-11', NULL, 'Aktif'),
(331, 12, 'A2', 23, 'Sabtu', '07:00:00', '10:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-2', NULL, 'Aktif'),
(332, 9, 'B4', 27, 'Sabtu', '09:40:00', '12:10:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Wahyu Kadri Rahmat Suat', 'Muhammad Rifky Saputra Scania', 'TI_SD-12', NULL, 'Aktif'),
(333, 12, 'A4', 23, 'Sabtu', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nahwa Kaka Saputra Anggareksa', 'TI_ALPRO-4', NULL, 'Aktif'),
(334, 11, 'A4', 27, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Andi Ikhlas Mallomo', 'TI_BD2-4', NULL, 'Aktif'),
(335, 11, 'A6', 26, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Thalita Sherly Putri Jasmin', 'TI_BD2-6', NULL, 'Aktif'),
(336, 8, 'A5,A7,B1,B2,B3', 29, 'Sabtu', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Muhammad Rafli', 'TI_MICRO-13', NULL, 'Aktif'),
(337, 12, 'B1', 23, 'Sabtu', '14:30:00', '18:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Ichwal', 'TI_ALPRO-5', NULL, 'Aktif'),
(338, 8, 'B3', 29, 'Rabu', '07:00:00', '09:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Rifky Saputra Scania', 'TI_MICRO-12', NULL, 'Aktif'),
(339, 11, 'B4', 26, 'Rabu', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Dewi Ernita Rahma', 'Muh. Fatwah Fajriansyah M.', 'TI_BD2-12', NULL, 'Aktif'),
(340, 8, 'A7', 29, 'Rabu', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-8', NULL, 'Aktif'),
(341, 9, 'A7', 27, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muhammad Rifky Saputra Scania', 'TI_SD-7', NULL, 'Aktif'),
(342, 9, 'A8', 26, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-8', NULL, 'Aktif'),
(343, 15, 'A1', 23, 'Rabu', '10:30:00', '14:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_PP-1', NULL, 'Aktif'),
(344, 9, 'A5', 27, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Zaki Falihin Ayyubi', 'TI_SD-5', NULL, 'Aktif'),
(345, 9, 'A6', 26, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muh. Fatwah Fajriansyah M.', 'TI_SD-6', NULL, 'Aktif'),
(346, 8, 'A8', 29, 'Rabu', '13:00:00', '15:30:00', 'Tasrif Hasanuddin, S.T., M.Cs.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-9', NULL, 'Aktif'),
(347, 15, 'A2', 23, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_PP-2', NULL, 'Aktif'),
(348, 15, 'A4', 24, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Muhammad Alif Maulana. R', 'Andi Ikhlas Mallomo', 'TI_PP-4', NULL, 'Aktif'),
(349, 11, 'B2', 24, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Farid Wajdi Mufti', 'Rizqi Ananda Jalil', 'TI_BD2-10', NULL, 'Aktif'),
(350, 11, 'B1', 23, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Andi Rifqi Aunur Rahman', 'TI_BD2-9', NULL, 'Aktif'),
(351, 16, 'C2', 27, 'Kamis', '07:00:00', '09:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Muhammad Nur Fuad', 'TI_MOBILE-1', NULL, 'Aktif'),
(352, 12, 'B2', 23, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Dewi Ernita Rahma', 'M. Rizwan', 'TI_ALPRO-6', NULL, 'Aktif'),
(353, 12, 'B3', 24, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_ALPRO-7', NULL, 'Aktif'),
(354, 15, 'A3', 23, 'Kamis', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'TI_PP-3', NULL, 'Aktif'),
(355, 11, 'B3', 26, 'Jumat', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_BD2-11', NULL, 'Aktif'),
(356, 8, 'A1', 29, 'Jumat', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Andi Ahsan Ashuri', 'TI_MICRO-1', NULL, 'Aktif'),
(357, 12, 'A1', 23, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Andi Rifqi Aunur Rahman', 'SI_ALPRO-1', NULL, 'Aktif'),
(358, 12, 'B1', 24, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'SI_ALPRO-2', NULL, 'Aktif'),
(359, 8, 'A2', 29, 'Jumat', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Muhammad Alif Maulana. R', 'Zaki Falihin Ayyubi', 'TI_MICRO-2', NULL, 'Aktif'),
(360, 15, 'B2', 27, 'Jumat', '10:30:00', '14:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nurfajri Mukmin Saputra', 'TI_PP-6', NULL, 'Aktif'),
(361, 12, 'A1', 23, 'Jumat', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Julisa', 'TI_ALPRO-1', NULL, 'Aktif'),
(362, 15, 'B1', 23, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_PP-5', NULL, 'Aktif'),
(363, 15, 'B3', 24, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_PP-7', NULL, 'Aktif'),
(364, 8, 'A4', 29, 'Jumat', '15:40:00', '18:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Farah Tsabitaputri Az Zahra', 'TI_MICRO-4', NULL, 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwalupk`
--

CREATE TABLE `jadwalupk` (
  `id` int(11) NOT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` varchar(100) NOT NULL,
  `mata_kuliah` varchar(255) NOT NULL,
  `dosen` varchar(255) NOT NULL,
  `frekuensi` varchar(100) DEFAULT NULL,
  `kelas` varchar(100) DEFAULT NULL,
  `ruangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwalupk`
--

INSERT INTO `jadwalupk` (`id`, `prodi`, `tanggal`, `jam`, `mata_kuliah`, `dosen`, `frekuensi`, `kelas`, `ruangan`) VALUES
(11, 'TI', '2026-01-05', '13.15 - 15.15', 'Algoritma Pemrograman', 'Ramdaniah, S.Kom., M.T.,MTA.', 'TI_ALPRO-2', 'A2', 'Lab Computer Vision'),
(12, 'TI', '2026-01-05', '13.15 - 15.15', 'Algoritma Pemrograman', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'TI_ALPRO-6', 'B2', 'Lab Startup'),
(13, 'TI', '2026-01-05', '13.15 - 15.15', 'Algoritma Pemrograman', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'TI_ALPRO-7', 'B3', 'Lab IoT'),
(14, 'SI', '2026-01-05', '16.00 - 18.00', 'Algoritma Pemrograman', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'SI_ALPRO-1', 'A1', 'Lab Startup'),
(15, 'SI', '2026-01-05', '16.00 - 18.00', 'Algoritma Pemrograman', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'SI_ALPRO-2', 'B1', 'Lab IoT'),
(16, 'TI', '2026-01-05', '16.00 - 18.00', 'Microcontroller', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'TI_MICRO-6', 'A5', 'Lab Microcontroller'),
(17, 'TI', '2026-01-06', '08.00 - 10.00', 'Basis Data II', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'TI_BD2-10', 'B2', 'Lab IoT'),
(18, 'TI', '2026-01-06', '08.00 - 10.00', 'Basis Data II', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'TI_BD2-9', 'B1', 'Lab Startup'),
(19, 'TI', '2026-01-06', '08.00 - 10.00', 'Pengenalan Pemrograman', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'TI_PP-1', 'A1', 'Lab Computer Vision'),
(20, 'TI', '2026-01-06', '10.15 - 12.15', 'Basis Data II', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'TI_BD2-12', 'B4', 'Lab IoT'),
(21, 'TI', '2026-01-06', '10.15 - 12.15', 'Pengenalan Pemrograman', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'TI_PP-2', 'A2', 'Lab Computer Vision'),
(22, 'TI', '2026-01-06', '13.15 - 15.15', 'Basis Data II', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'TI_BD2-11', 'B3', 'Lab Computer Vision'),
(23, 'TI', '2026-01-06', '13.15 - 15.15', 'Pengenalan Pemrograman', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'TI_PP-3', 'A3', 'Lab Startup'),
(24, 'TI', '2026-01-06', '13.15 - 15.15', 'Pengenalan Pemrograman', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'TI_PP-4', 'A4', 'Lab IoT'),
(25, 'SI', '2026-01-06', '16.00 - 18.00', 'Pengantar Teknologi Informasi', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'SI_PTI-1', 'A1', 'Lab Startup'),
(26, 'SI', '2026-01-06', '16.00 - 18.00', 'Pengantar Teknologi Informasi', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'SI_PTI-2', 'B1', 'Lab IoT'),
(27, 'TI', '2026-01-07', '08.00 - 10.00', 'Basis Data II', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'TI_BD2-7', 'A7', 'Lab Computer Vision'),
(28, 'TI', '2026-01-07', '08.00 - 10.00', 'Basis Data II', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'TI_BD2-8', 'A8', 'Lab Data Science'),
(29, 'TI', '2026-01-07', '08.00 - 10.00', 'Struktur Data', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'TI_SD-10', 'B2', 'Lab IoT'),
(30, 'TI', '2026-01-07', '08.00 - 10.00', 'Struktur Data', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'TI_SD-9', 'B1', 'Lab Startup'),
(31, 'TI', '2026-01-07', '10.15 - 12.15', 'Basis Data II', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'TI_BD2-3', 'A3', 'Lab Computer Vision'),
(32, 'TI', '2026-01-07', '10.15 - 12.15', 'Basis Data II', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'TI_BD2-5', 'A5', 'Lab Data Science'),
(33, 'TI', '2026-01-07', '10.15 - 12.15', 'Struktur Data', 'Nurul Fadhillah, S.Kom., M.Kom', 'TI_SD-11', 'B3', 'Lab Startup'),
(34, 'TI', '2026-01-07', '13.15 - 15.15', 'Basis Data II', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'TI_BD2-4', 'A4', 'Lab Computer Vision'),
(35, 'TI', '2026-01-07', '13.15 - 15.15', 'Basis Data II', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'TI_BD2-6', 'A6', 'Lab Data Science'),
(36, 'TI', '2026-01-07', '13.15 - 15.15', 'Struktur Data', 'Nurul Fadhillah, S.Kom., M.Kom', 'TI_SD-12', 'B4', 'Lab Startup'),
(37, 'TI', '2026-01-07', '16.00 - 18.00', 'Basis Data II', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'TI_BD2-1', 'A1', 'Lab Computer Vision'),
(38, 'TI', '2026-01-07', '16.00 - 18.00', 'Basis Data II', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'TI_BD2-2', 'A2', 'Lab Data Science'),
(39, 'TI', '2026-01-07', '16.00 - 18.00', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-7', 'A7', 'Lab Startup'),
(40, 'TI', '2026-01-07', '16.00 - 18.00', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-8', 'A8', 'Lab IoT'),
(41, 'TI', '2026-01-08', '08.00 - 10.00', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-1', 'A1', 'Lab Computer Vision'),
(42, 'TI', '2026-01-08', '08.00 - 10.00', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-2', 'A2', 'Lab Data Science'),
(43, 'SI', '2026-01-08', '08.00 - 10.00', 'Pemrograman Berorientasi Objek', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'SI_PBO-1', 'A1', 'Lab Startup'),
(44, 'SI', '2026-01-08', '08.00 - 10.00', 'Pemrograman Berorientasi Objek', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'SI_PBO-2', 'B1', 'Lab IoT'),
(45, 'TI', '2026-01-08', '08.00 - 10.00', 'Microcontroller', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'TI_MICRO-7', 'A6', 'Lab Microcontroller'),
(46, 'TI', '2026-01-08', '10.15 - 12.15', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-3', 'A3', 'Lab Computer Vision'),
(47, 'TI', '2026-01-08', '10.15 - 12.15', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-4', 'A4', 'Lab Data Science'),
(48, 'TI', '2026-01-08', '13.15 - 15.15', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-5', 'A5', 'Lab Computer Vision'),
(49, 'TI', '2026-01-08', '13.15 - 15.15', 'Struktur Data', 'Syariful Mujaddid, S.Kom.,M.T.', 'TI_SD-6', 'A6', 'Lab Data Science'),
(50, 'SI', '2026-01-08', '13.15 - 15.15', 'Jaringan Komputer', 'Fahmi, S.Kom., M.T.', 'SI_JARKOM-1', 'A1', 'Lab Startup'),
(51, 'SI', '2026-01-08', '13.15 - 15.15', 'Jaringan Komputer', 'Fahmi, S.Kom., M.T.', 'SI_JARKOM-2', 'B1', 'Lab IoT'),
(52, 'TI', '2026-01-08', '13.15 - 15.15', 'Microcontroller', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'TI_MICRO-11', 'B2', 'Lab Microcontroller'),
(53, 'TI', '2026-01-08', '16.00 - 18.00', 'Pemrograman Mobile', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'TI_MOBILE-1', 'C2', 'Lab Computer Vision'),
(54, 'TI', '2026-01-08', '16.00 - 18.00', 'Microcontroller', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'TI_MICRO-13', 'A5,A7,B1,B2,B3', 'Lab Microcontroller'),
(55, 'TI', '2026-01-09', '08.00 - 10.00', 'Microcontroller', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'TI_MICRO-2', 'A2', 'Lab Microcontroller'),
(56, 'TI', '2026-01-09', '10.15 - 12.15', 'Microcontroller', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'TI_MICRO-3', 'A3', 'Lab Microcontroller'),
(57, 'TI', '2026-01-09', '13.15 - 15.15', 'Microcontroller', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'TI_MICRO-8', 'A7', 'Lab Microcontroller'),
(58, 'TI', '2026-01-09', '16.00 - 18.00', 'Microcontroller', 'Tasrif Hasanuddin, S.T., M.Cs.', 'TI_MICRO-9', 'A8', 'Lab Microcontroller'),
(59, 'TI', '2026-01-17', '16.00 - 18.00', 'Basis Dataa', 'Ramdaniah, S.Kom., M.T.,MTA.', 'TI_ALPRO-2', 'A', 'Lab Microcontroller'),
(60, 'TI', '2026-01-17', '10.15 - 12.15', 'bababab', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'TI_MICRO-12', 'A1', 'Lab Computer Vision'),
(61, 'TI', '2026-01-17', '13.15 - 15.15', 'Pemrograman Mobile', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', '', 'B1', 'Laboratorium Microcontroler');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laboratorium`
--

CREATE TABLE `laboratorium` (
  `idLaboratorium` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` enum('Laboratorium','Riset') DEFAULT 'Laboratorium',
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

INSERT INTO `laboratorium` (`idLaboratorium`, `nama`, `jenis`, `idKordinatorAsisten`, `deskripsi`, `gambar`, `jumlahPc`, `jumlahKursi`, `lokasi`, `kapasitas`, `processor`, `ram`, `storage`, `gpu`, `monitor`, `software`, `fasilitas_pendukung`, `koordinator_nama`, `koordinator_foto`) VALUES
(23, 'Start Up', 'Laboratorium', 18, 'Laboratorium Startup adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 36 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif', 'lab_1767850945_1246.jpg', 36, NULL, NULL, 36, 'Inter core i7-9700F', 'DDR4 16 GB', 'SSD SATA 500 GB', 'VGA MSI GeForce GTX 1650', 'Monitor LG 22\" Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After, Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '3 TV TCL 75” Inch, 1 Buah Speaker Samsung, Spliter HDMI, Kabel HDMI', NULL, NULL),
(24, 'IoT', 'Laboratorium', 21, 'Laboratorium IOT adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 24 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 24 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767852021_9734.jpg', 24, NULL, NULL, 24, 'CPU [Intel Core i5-7100]', 'RAM DDR4 [8 GB]', 'HDD [1 TB]', 'VGA NVIDIA Geforce GT 1030', 'Monitor LG 22” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(25, 'Computer Network', 'Laboratorium', 21, 'Laboratorium Computer Network adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 15 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 24 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767853201_6100.png', 15, NULL, NULL, 24, 'CPU [Intel Core i7-10700k]', 'DDR4 16 GB', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor AOC 27” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(26, 'Data Science', 'Laboratorium', 18, 'Laboratorium Data Science adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 26 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854477_4975.jpg', 26, NULL, NULL, 26, 'CPU [Intel i7-12700f]', 'RAM DDR4 [16 GB]', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(27, 'Computer Vision', 'Laboratorium', 19, 'Laboratorium Computer Vision adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 26 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854621_5299.jpg', 26, NULL, NULL, 26, 'CPU [Intel i7-12700f]', 'RAM DDR4 [16 GB]', 'SSD NVME 512 GB', 'VGA NVIDIA Geforce GT 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(28, 'Multimedia', 'Laboratorium', 19, 'Laboratorium Multimedia adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 30 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 30 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854751_1846.jpg', 30, NULL, NULL, 30, 'CPU [Intel i7-12700f]', 'RAM DDR4 16 GB', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(29, 'Microcontroller', 'Laboratorium', 18, 'Laboratorium Microcontroler adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 25 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854892_3342.jpg', 25, NULL, NULL, 25, 'CPU [Intel Core i5-4460]', 'RAM DDR4 [8 GB]', 'HDD [1 TB]', 'VGA NVIDIA Geforce GT 1650', 'Monitor LG 20” Inch', 'Livewire, Arduino, Microsoft Office, Google Chrome', '1 TV TCL 75” Inch, USB to HDMI, Kabel HDMI', NULL, NULL),
(30, 'Research Room 1', 'Riset', 17, 'Research Room 1 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'lab_1767854958_8098.png', 0, NULL, NULL, 0, '', '', '', '', '', '', '', NULL, NULL),
(31, 'Research Room 2', 'Riset', 20, 'Research Room 2 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'lab_1767855066_4115.jpg', 0, NULL, NULL, 0, '', '', '', '', '', '', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(32, 'Research Room 3', 'Riset', 16, 'Research Room 3 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'lab_1767855107_4991.png', 0, NULL, NULL, 0, '', '', '', '', '', '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `laboratorium_gambar`
--

CREATE TABLE `laboratorium_gambar` (
  `idGambar` int(11) NOT NULL,
  `idLaboratorium` int(11) NOT NULL,
  `namaGambar` varchar(255) NOT NULL,
  `deskripsiGambar` text DEFAULT NULL,
  `isUtama` tinyint(1) DEFAULT 0 COMMENT '1 = gambar utama, 0 = gambar tambahan',
  `urutan` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laboratorium_gambar`
--

INSERT INTO `laboratorium_gambar` (`idGambar`, `idLaboratorium`, `namaGambar`, `deskripsiGambar`, `isUtama`, `urutan`, `created_at`) VALUES
(22, 23, 'lab_1767850945_1246.jpg', NULL, 1, 0, '2026-01-08 13:42:25'),
(23, 23, 'lab_1767850945_1945.jpg', NULL, 0, 1, '2026-01-08 13:42:25'),
(24, 23, 'lab_1767850945_8490.jpg', NULL, 0, 2, '2026-01-08 13:42:25'),
(25, 24, 'lab_1767852021_9734.jpg', NULL, 1, 0, '2026-01-08 14:00:21'),
(26, 24, 'lab_1767852021_6760.jpg', NULL, 0, 1, '2026-01-08 14:00:21'),
(27, 24, 'lab_1767852021_1796.jpg', NULL, 0, 2, '2026-01-08 14:00:21'),
(28, 25, 'lab_1767853201_6100.png', NULL, 1, 0, '2026-01-08 14:20:01'),
(29, 25, 'lab_1767853201_7175.png', NULL, 0, 1, '2026-01-08 14:20:01'),
(30, 25, 'lab_1767853201_9328.png', NULL, 0, 2, '2026-01-08 14:20:01'),
(31, 25, 'lab_1767853201_2917.png', NULL, 0, 3, '2026-01-08 14:20:01'),
(32, 26, 'lab_1767854477_4975.jpg', NULL, 1, 0, '2026-01-08 14:41:17'),
(33, 26, 'lab_1767854477_4837.jpg', NULL, 0, 1, '2026-01-08 14:41:17'),
(34, 26, 'lab_1767854477_2177.jpg', NULL, 0, 2, '2026-01-08 14:41:17'),
(35, 26, 'lab_1767854477_9390.jpg', NULL, 0, 3, '2026-01-08 14:41:17'),
(36, 27, 'lab_1767854621_5299.jpg', NULL, 1, 0, '2026-01-08 14:43:41'),
(37, 27, 'lab_1767854621_4842.jpg', NULL, 0, 1, '2026-01-08 14:43:41'),
(38, 28, 'lab_1767854751_1846.jpg', NULL, 1, 0, '2026-01-08 14:45:51'),
(39, 28, 'lab_1767854751_7242.jpg', NULL, 0, 1, '2026-01-08 14:45:51'),
(40, 28, 'lab_1767854751_7621.jpg', NULL, 0, 2, '2026-01-08 14:45:51'),
(41, 28, 'lab_1767854751_5763.jpg', NULL, 0, 3, '2026-01-08 14:45:51'),
(42, 28, 'lab_1767854751_6789.jpg', NULL, 0, 4, '2026-01-08 14:45:51'),
(43, 29, 'lab_1767854892_3342.jpg', NULL, 1, 0, '2026-01-08 14:48:12'),
(44, 29, 'lab_1767854892_6066.jpg', NULL, 0, 1, '2026-01-08 14:48:12'),
(45, 29, 'lab_1767854892_1458.jpg', NULL, 0, 2, '2026-01-08 14:48:12'),
(46, 29, 'lab_1767854892_8927.jpg', NULL, 0, 3, '2026-01-08 14:48:12'),
(47, 29, 'lab_1767854892_2071.jpg', NULL, 0, 4, '2026-01-08 14:48:12'),
(48, 30, 'lab_1767854958_8098.png', NULL, 1, 0, '2026-01-08 14:49:18'),
(49, 31, 'lab_1767855066_4115.jpg', NULL, 1, 0, '2026-01-08 14:51:06'),
(50, 31, 'lab_1767855066_6985.jpg', NULL, 0, 1, '2026-01-08 14:51:06'),
(51, 32, 'lab_1767855107_4991.png', NULL, 1, 0, '2026-01-08 14:51:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `manajemen`
--

CREATE TABLE `manajemen` (
  `idManajemen` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nidn` varchar(20) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutanTampilan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `manajemen`
--

INSERT INTO `manajemen` (`idManajemen`, `nama`, `nidn`, `jabatan`, `foto`, `urutanTampilan`) VALUES
(1, 'Ir. Abdul Rachman Manga\', S.Kom., M.T., MTA., MCF', '0931018001', 'Kepala Laboratorium Jaringan Dan Pemrograman', 'manajemen_1767850035_5059.jpg', 0),
(5, 'Ir. Huzain Azis, S.Kom., M.Cs. MTA', '0920098801', 'Kepala Laboratorium Komputasi Dasar', 'manajemen_1767600806_3284.jpg', 0),
(6, 'Herdianti, S.Si., M.Eng., MTA.', '0924069001', 'Kepala Laboratorium Riset', 'manajemen_1767600880_4656.JPG', 0),
(7, 'Fatimah AR. Tuasamu, S.Kom., MTA, MOS', NULL, 'Laboran', 'manajemen_1767600916_8750.JPG', 0);

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
(8, 'TI_MICRO', 'Microcontroller', 5, 3),
(9, 'TI_SD', 'Struktur Data', 3, 3),
(10, 'SI_PBO', 'Pemrograman Berorientasi Objek', 4, 3),
(11, 'TI_BD2', 'Basis Data II', 3, 3),
(12, 'TI_ALPRO', 'Algoritma Pemrograman', 1, 3),
(13, 'SI_PTI', 'Pengantar Teknologi Informasi', 1, 3),
(14, 'SI_JARKOM', 'Jaringan Komputer', 4, 3),
(15, 'TI_PP', 'Pengenalan Pemrograman', 1, 3),
(16, 'TI_MOBILE', 'Pemrograman Mobile', 5, 5);

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kategori` varchar(100) DEFAULT 'umum',
  `display_format` varchar(20) DEFAULT 'list' COMMENT 'Format tampilan: list atau plain'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peraturan_lab`
--

INSERT INTO `peraturan_lab` (`id`, `judul`, `deskripsi`, `gambar`, `urutan`, `created_at`, `updated_at`, `kategori`, `display_format`) VALUES
(2, 'Kehadiran', 'Mahasiswa wajib mengikuti pertemuan minimal 10 kali (75% dari total 14 pertemuan). Jika kurang dari 10 kali, mahasiswa tidak diperkenankan mengikuti UAS', NULL, 2, '2025-12-18 23:53:18', '2026-01-16 15:31:01', 'Kehadiran & Akademik', 'plain'),
(3, 'Durasi Kurikulum 2025', 'Sesi 1: 07.00–10.20\r\nSesi 2: 10.30–14.20\r\nSesi 3: 14.30–18.20', NULL, 3, '2025-12-18 23:53:18', '2026-01-16 15:33:21', 'kehadiran-akademik', 'list'),
(4, 'Durasi Kurikulum 2021', 'Sesi 1: 07.00-09.30\r\nSesi 2: 09.40-12.10\r\nSesi 3: 13.00-15.30\r\nSesi 4: 15.40-18.10', NULL, 4, '2025-12-18 23:53:18', '2026-01-16 15:34:24', 'larangan-umum', 'list'),
(11, 'Larangan umum', 'Tidak diperbolehkan mengganggu praktikan lain yang sedang melaksanakan praktikum.\r\nDilarang merokok, membawa makanan, minuman, senjata tajam, dan senjata api ke dalam ruangan.\r\nTidak diperbolehkan membawa handphone ke meja praktikum dan handphone harus dalam mode senyap.\r\nTidak diperbolehkan membawa media penyimpanan eksternal (flashdisk) ke meja praktikum tanpa izin Dosen atau Asisten Lab.\r\nDilarang membawa, mengambil, serta memindahkan perangkat praktikum tanpa instruksi.', NULL, 0, '2026-01-16 15:29:20', '2026-01-16 15:29:20', 'Larangan Umum', 'list');

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `display_format` varchar(20) DEFAULT 'list' COMMENT 'Format tampilan: list atau plain'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sanksi_lab`
--

INSERT INTO `sanksi_lab` (`id`, `judul`, `deskripsi`, `gambar`, `urutan`, `created_at`, `updated_at`, `display_format`) VALUES
(2, 'Merusak Fasilitas', 'Praktikan merusak peralatan praktikum (Personal Computer) secara sengaja, maka praktikan bertanggung jawab untuk mengganti kerusakan tersebut.', NULL, 0, '2025-12-15 10:11:55', '2026-01-10 13:22:35', 'plain'),
(12, 'Melanggar Aturan', 'Praktikan tidak mematuhi dan mentaati aturan praktikum maka tidak diperkenankan mengikuti praktikum.', NULL, 2, '2026-01-10 14:01:35', '2026-01-10 14:02:19', 'plain'),
(13, 'Sanksi lain', 'Pelanggaran point lainnya dikenakan sanksi teguran, dikeluarkan/dicoret namanya dalam kegiatan praktikum (mengulang mata kuliah sesuai dengan semester berjalan) sampai sanksi akademik.', NULL, 3, '2026-01-10 14:03:55', '2026-01-10 14:03:55', 'plain');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop`
--

CREATE TABLE `sop` (
  `id_sop` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `icon` varchar(50) DEFAULT 'ri-file-text-line',
  `warna` varchar(50) DEFAULT 'icon-blue',
  `file` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sop`
--

INSERT INTO `sop` (`id_sop`, `judul`, `icon`, `warna`, `file`, `deskripsi`, `urutan`, `created_at`, `updated_at`) VALUES
(2, 'Pemutakhiran Data Aset Laboratorium', 'ri-file-list-3-line', 'icon-blue', '6961ffa4efcd3.pdf', 'Prosedur standar untuk memperbarui data inventaris dan aset laboratorium secara berkala setiap semester.', 0, '2026-01-10 07:28:36', '2026-01-10 07:28:36'),
(3, 'Penanganan Barang Rusak', 'ri-tools-line', 'icon-blue', '6961ffda1425e.pdf', 'Tata cara pelaporan, pengecekan, dan tindak lanjut perbaikan atau penggantian perangkat laboratorium yang mengalami kerusakan.', 0, '2026-01-10 07:29:30', '2026-01-10 07:29:30'),
(4, 'Pengembalian Barang ke Fakultas', 'ri-share-forward-box-line', 'icon-blue', '6961fffaef594.pdf', 'Alur administrasi pengembalian aset atau peminjaman barang inventaris kembali ke pihak Fakultas Ilmu Komputer.', 0, '2026-01-10 07:30:02', '2026-01-10 07:30:02'),
(5, 'Pemeliharaan Perangkat Laboratorium', 'ri-computer-line', 'icon-blue', '6962001b97cfc.pdf', 'Jadwal dan standar perawatan rutin (maintenance) untuk PC, jaringan, dan kelistrikan di dalam laboratorium.', 0, '2026-01-10 07:30:35', '2026-01-10 07:30:35'),
(6, 'Pemutakhiran Modul Praktikum', 'ri-book-open-line', 'icon-blue', '6962006a3db5b.pdf', 'Mekanisme revisi dan update materi modul praktikum agar sesuai dengan perkembangan teknologi terbaru.', 0, '2026-01-10 07:31:54', '2026-01-10 07:31:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `last_login`, `created_at`) VALUES
(5, '13020230100@student.umi.ac.id', '$2y$10$iVDxzPWLuukG7qlOxo1WFORcqFPKWD68EapvAP/Lf4ruJ6cqaMD.6', 'super_admin', '2026-01-16 20:21:28', '2026-01-09 22:25:18'),
(6, '13020230217@student.umi.ac.id', '$2y$10$khY/qBg0XaAE/x0apN54CuiPJeYczQVAvEPd4qJDK13hBEe7DIDh2', 'admin', '2026-01-09 22:32:12', '2026-01-09 22:26:27'),
(7, '13020230187@student.umi.ac.id', '$2y$10$FhUh8hCCg6noMBFw.YEsLuVuzUa.4jSNDVJgk3Q6oCJBD0k058ugi', 'admin', '2026-01-09 22:33:44', '2026-01-09 22:28:15'),
(8, 'superadmin@student.umi.ac.id', '$2y$10$UzYX.F.BjuMC8s1nsl/1pe0l9j6tO1Go2hIaxAwvSo0nOw.PCA7WG', 'super_admin', '2026-01-10 11:32:04', '2026-01-09 22:35:55');

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
-- Indeks untuk tabel `format_penulisan`
--
ALTER TABLE `format_penulisan`
  ADD PRIMARY KEY (`id_format`);

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
-- Indeks untuk tabel `jadwalupk`
--
ALTER TABLE `jadwalupk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laboratorium`
--
ALTER TABLE `laboratorium`
  ADD PRIMARY KEY (`idLaboratorium`),
  ADD KEY `idKordinatorAsisten` (`idKordinatorAsisten`);

--
-- Indeks untuk tabel `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  ADD PRIMARY KEY (`idGambar`),
  ADD KEY `idLaboratorium` (`idLaboratorium`);

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
-- Indeks untuk tabel `sop`
--
ALTER TABLE `sop`
  ADD PRIMARY KEY (`id_sop`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `asisten`
--
ALTER TABLE `asisten`
  MODIFY `idAsisten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `format_penulisan`
--
ALTER TABLE `format_penulisan`
  MODIFY `id_format` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `integrsiweb`
--
ALTER TABLE `integrsiweb`
  MODIFY `idIntegrasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwalpraktikum`
--
ALTER TABLE `jadwalpraktikum`
  MODIFY `idJadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;

--
-- AUTO_INCREMENT untuk tabel `jadwalupk`
--
ALTER TABLE `jadwalupk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `laboratorium`
--
ALTER TABLE `laboratorium`
  MODIFY `idLaboratorium` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  MODIFY `idGambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `manajemen`
--
ALTER TABLE `manajemen`
  MODIFY `idManajemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `idMatakuliah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `peraturan_lab`
--
ALTER TABLE `peraturan_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `sanksi_lab`
--
ALTER TABLE `sanksi_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `sop`
--
ALTER TABLE `sop`
  MODIFY `id_sop` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

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

--
-- Ketidakleluasaan untuk tabel `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  ADD CONSTRAINT `fk_lab_gambar_lab` FOREIGN KEY (`idLaboratorium`) REFERENCES `laboratorium` (`idLaboratorium`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
