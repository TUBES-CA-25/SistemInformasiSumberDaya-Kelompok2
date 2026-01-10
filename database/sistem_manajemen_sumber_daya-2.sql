-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 10, 2026 at 03:46 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `alumni`
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
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`id`, `nama`, `angkatan`, `divisi`, `mata_kuliah`, `foto`, `kesan_pesan`, `keahlian`, `email`, `created_at`, `updated_at`, `urutanTampilan`) VALUES
(18, 'Arisa Tien Hardianti, S.Kom', '2020', 'Asisten', 'Basis Data I', NULL, '', '', '', '2026-01-08 15:23:35', '2026-01-08 15:23:35', 0);

-- --------------------------------------------------------

--
-- Table structure for table `asisten`
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
-- Dumping data for table `asisten`
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
(22, 'Aan Maulana Sampe', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pemrograman Berorientasi Objek, Jaringan Komputer', '[]', '13020230081@student.umi.ac.id', 'asisten_1767847118_5230.png', 'CA', 0, 0),
(23, 'Andi Ahsan Ashuri', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[]', '13020230224@student.umi.ac.id', 'asisten_1767847163_2915.png', 'CA', 0, 0),
(24, 'Andi Ikhlas Mallomo', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Struktur Data, Basisdata', '[]', '13020230251@student.umi.ac.id', 'asisten_1767847230_1778.jpg', 'CA', 0, 0),
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
(35, 'M. Rizwan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman, Basisdata', '[]', '13020230100@student.umi.ac.id', 'asisten_1767848152_4734.png', 'CA', 0, 0),
(36, 'Nahwa Kaka Saputra Anggareksa', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[]', '13020230187@student.umi.ac.id', 'asisten_1767848210_2417.jpg', 'CA', 0, 0),
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
-- Table structure for table `format_penulisan`
--

CREATE TABLE `format_penulisan` (
  `id_format` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `kategori` enum('pedoman','unduhan') DEFAULT 'pedoman',
  `link_external` varchar(255) DEFAULT NULL,
  `tanggal_update` date DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'ri-file-info-line',
  `warna` varchar(30) DEFAULT 'icon-blue',
  `urutan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `format_penulisan`
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
-- Table structure for table `integrsiweb`
--

CREATE TABLE `integrsiweb` (
  `idIntegrasi` int(11) NOT NULL,
  `namaWeb` varchar(100) DEFAULT NULL,
  `urlWeb` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwalpraktikum`
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
-- Dumping data for table `jadwalpraktikum`
--

INSERT INTO `jadwalpraktikum` (`idJadwal`, `idMatakuliah`, `kelas`, `idLaboratorium`, `hari`, `waktuMulai`, `waktuSelesai`, `dosen`, `asisten1`, `asisten2`, `frekuensi`, `tanggal`, `status`) VALUES
(61, 8, 'A1,A2,A3', 33, 'Senin', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Nur Fuad', 'TI_MICRO-5', NULL, 'Aktif'),
(62, 9, 'A3', 34, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'TI_SD-3', NULL, 'Aktif'),
(63, 9, 'A4', 35, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Sitti Lutfia', 'TI_SD-4', NULL, 'Aktif'),
(64, 10, 'A1', 36, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Thalita Sherly Putri Jasmin', 'SI_PBO-1', NULL, 'Aktif'),
(65, 10, 'B1', 37, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Tazkirah Amaliah', 'SI_PBO-2', NULL, 'Aktif'),
(66, 8, 'A3', 33, 'Senin', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Maharani Safwa Andini', 'Farid Wajdi Mufti', 'TI_MICRO-3', NULL, 'Aktif'),
(67, 11, 'A7', 34, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_BD2-7', NULL, 'Aktif'),
(68, 11, 'A8', 35, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Maharani Safwa Andini', 'Andi Rifqi Aunur Rahman', 'TI_BD2-8', NULL, 'Aktif'),
(69, 8, 'A5', 33, 'Senin', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_MICRO-6', NULL, 'Aktif'),
(70, 9, 'B2', 37, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Farid Wajdi Mufti', 'Aan Maulana Sampe', 'TI_SD-10', NULL, 'Aktif'),
(71, 9, 'B1', 36, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Dewi Ernita Rahma', 'Andi Ahsan Ashuri', 'TI_SD-9', NULL, 'Aktif'),
(72, 8, 'A6', 33, 'Senin', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Raihan Nur Rizqillah', 'TI_MICRO-7', NULL, 'Aktif'),
(73, 9, 'A1', 36, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_SD-1', NULL, 'Aktif'),
(74, 9, 'A2', 37, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Wahyu Kadri Rahmat Suat', 'Maharani Safwa Andini', 'TI_SD-2', NULL, 'Aktif'),
(75, 11, 'A3', 34, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Sitti Lutfia', 'TI_BD2-3', NULL, 'Aktif'),
(76, 11, 'A5', 35, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Farah Tsabitaputri Az Zahra', 'TI_BD2-5', NULL, 'Aktif'),
(77, 13, 'A1', 36, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'SI_PTI-1', NULL, 'Aktif'),
(78, 13, 'B1', 37, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Ahmad Mufli Ramadhan', 'Muh. Fatwah Fajriansyah M.', 'SI_PTI-2', NULL, 'Aktif'),
(79, 8, 'B2', 33, 'Selasa', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Muhammad Nur Fuad', 'TI_MICRO-11', NULL, 'Aktif'),
(80, 14, 'B1', 34, 'Selasa', '09:40:00', '12:10:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Aan Maulana Sampe', 'SI_JARKOM-2', NULL, 'Aktif'),
(81, 12, 'A3', 36, 'Selasa', '10:30:00', '14:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-3', NULL, 'Aktif'),
(82, 14, 'A1', 34, 'Selasa', '13:00:00', '15:30:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'SI_JARKOM-1', NULL, 'Aktif'),
(83, 11, 'A1', 36, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_BD2-1', NULL, 'Aktif'),
(84, 11, 'A2', 37, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Rizqi Ananda Jalil', 'TI_BD2-2', NULL, 'Aktif'),
(85, 8, 'B1', 33, 'Selasa', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Muhammad Rafli', 'TI_MICRO-10', NULL, 'Aktif'),
(86, 9, 'B3', 34, 'Sabtu', '07:00:00', '09:30:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-11', NULL, 'Aktif'),
(87, 12, 'A2', 36, 'Sabtu', '07:00:00', '10:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-2', NULL, 'Aktif'),
(88, 9, 'B4', 34, 'Sabtu', '09:40:00', '12:10:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Wahyu Kadri Rahmat Suat', 'Muhammad Rifky Saputra Scania', 'TI_SD-12', NULL, 'Aktif'),
(89, 12, 'A4', 36, 'Sabtu', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nahwa Kaka Saputra Anggareksa', 'TI_ALPRO-4', NULL, 'Aktif'),
(90, 11, 'A4', 34, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Andi Ikhlas Mallomo', 'TI_BD2-4', NULL, 'Aktif'),
(91, 11, 'A6', 35, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Thalita Sherly Putri Jasmin', 'TI_BD2-6', NULL, 'Aktif'),
(92, 8, 'A5,A7,B1,B2,B3', 33, 'Sabtu', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Muhammad Rafli', 'TI_MICRO-13', NULL, 'Aktif'),
(93, 12, 'B1', 36, 'Sabtu', '14:30:00', '18:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Ichwal', 'TI_ALPRO-5', NULL, 'Aktif'),
(94, 8, 'B3', 33, 'Rabu', '07:00:00', '09:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Rifky Saputra Scania', 'TI_MICRO-12', NULL, 'Aktif'),
(95, 11, 'B4', 35, 'Rabu', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Dewi Ernita Rahma', 'Muh. Fatwah Fajriansyah M.', 'TI_BD2-12', NULL, 'Aktif'),
(96, 8, 'A7', 33, 'Rabu', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-8', NULL, 'Aktif'),
(97, 9, 'A7', 34, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muhammad Rifky Saputra Scania', 'TI_SD-7', NULL, 'Aktif'),
(98, 9, 'A8', 35, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-8', NULL, 'Aktif'),
(99, 15, 'A1', 36, 'Rabu', '10:30:00', '14:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_PP-1', NULL, 'Aktif'),
(100, 9, 'A5', 34, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Zaki Falihin Ayyubi', 'TI_SD-5', NULL, 'Aktif'),
(101, 9, 'A6', 35, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muh. Fatwah Fajriansyah M.', 'TI_SD-6', NULL, 'Aktif'),
(102, 8, 'A8', 33, 'Rabu', '13:00:00', '15:30:00', 'Tasrif Hasanuddin, S.T., M.Cs.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-9', NULL, 'Aktif'),
(103, 15, 'A2', 36, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_PP-2', NULL, 'Aktif'),
(104, 15, 'A4', 37, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Muhammad Alif Maulana. R', 'Andi Ikhlas Mallomo', 'TI_PP-4', NULL, 'Aktif'),
(105, 11, 'B2', 37, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Farid Wajdi Mufti', 'Rizqi Ananda Jalil', 'TI_BD2-10', NULL, 'Aktif'),
(106, 11, 'B1', 36, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Andi Rifqi Aunur Rahman', 'TI_BD2-9', NULL, 'Aktif'),
(107, 16, 'C2', 34, 'Kamis', '07:00:00', '09:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Muhammad Nur Fuad', 'TI_MOBILE-1', NULL, 'Aktif'),
(108, 12, 'B2', 36, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Dewi Ernita Rahma', 'M. Rizwan', 'TI_ALPRO-6', NULL, 'Aktif'),
(109, 12, 'B3', 37, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_ALPRO-7', NULL, 'Aktif'),
(110, 15, 'A3', 36, 'Kamis', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'TI_PP-3', NULL, 'Aktif'),
(111, 11, 'B3', 35, 'Jumat', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_BD2-11', NULL, 'Aktif'),
(112, 8, 'A1', 33, 'Jumat', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Andi Ahsan Ashuri', 'TI_MICRO-1', NULL, 'Aktif'),
(113, 12, 'A1', 36, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Andi Rifqi Aunur Rahman', 'SI_ALPRO-1', NULL, 'Aktif'),
(114, 12, 'B1', 37, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'SI_ALPRO-2', NULL, 'Aktif'),
(115, 8, 'A2', 33, 'Jumat', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Muhammad Alif Maulana. R', 'Zaki Falihin Ayyubi', 'TI_MICRO-2', NULL, 'Aktif'),
(116, 15, 'B2', 34, 'Jumat', '10:30:00', '14:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nurfajri Mukmin Saputra', 'TI_PP-6', NULL, 'Aktif'),
(117, 12, 'A1', 36, 'Jumat', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Julisa', 'TI_ALPRO-1', NULL, 'Aktif'),
(118, 15, 'B1', 36, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_PP-5', NULL, 'Aktif'),
(119, 15, 'B3', 37, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_PP-7', NULL, 'Aktif'),
(120, 8, 'A4', 33, 'Jumat', '15:40:00', '18:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Farah Tsabitaputri Az Zahra', 'TI_MICRO-4', NULL, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `laboratorium`
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
-- Dumping data for table `laboratorium`
--

INSERT INTO `laboratorium` (`idLaboratorium`, `nama`, `jenis`, `idKordinatorAsisten`, `deskripsi`, `gambar`, `jumlahPc`, `jumlahKursi`, `lokasi`, `kapasitas`, `processor`, `ram`, `storage`, `gpu`, `monitor`, `software`, `fasilitas_pendukung`, `koordinator_nama`, `koordinator_foto`) VALUES
(23, 'Laboratorium StartUp', 'Laboratorium', 18, 'Laboratorium Startup adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 36 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif', 'lab_1767850945_1246.jpg', 36, NULL, NULL, 36, 'Inter core i7-9700F', 'DDR4 16 GB', 'SSD SATA 500 GB', 'VGA MSI GeForce GTX 1650', 'Monitor LG 22\" Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After, Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '3 TV TCL 75” Inch, 1 Buah Speaker Samsung, Spliter HDMI, Kabel HDMI', NULL, NULL),
(24, 'Laboratorium IoT (Internet of Things)', 'Laboratorium', 21, 'Laboratorium IOT adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 24 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 24 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767852021_9734.jpg', 24, NULL, NULL, 24, 'CPU [Intel Core i5-7100]', 'RAM DDR4 [8 GB]', 'HDD [1 TB]', 'VGA NVIDIA Geforce GT 1030', 'Monitor LG 22” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(25, 'Laboratorium Computer Network', 'Laboratorium', 21, 'Laboratorium Computer Network adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 15 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 24 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767853201_6100.png', 15, NULL, NULL, 24, 'CPU [Intel Core i7-10700k]', 'DDR4 16 GB', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor AOC 27” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(26, 'Laboratorium Data Science', 'Laboratorium', 18, 'Laboratorium Data Science adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 26 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854477_4975.jpg', 26, NULL, NULL, 26, 'CPU [Intel i7-12700f]', 'RAM DDR4 [16 GB]', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(27, 'Laboratorium Computer Vision', 'Laboratorium', 19, 'Laboratorium Computer Vision adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 26 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854621_5299.jpg', 26, NULL, NULL, 0, 'CPU [Intel i7-12700f]', 'RAM DDR4 [16 GB]', 'SSD NVME 512 GB', 'VGA NVIDIA Geforce GT 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(28, 'Laboratorium Multimedia', 'Laboratorium', 19, 'Laboratorium Multimedia adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 30 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 30 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854751_1846.jpg', 30, NULL, NULL, 0, 'CPU [Intel i7-12700f]', 'RAM DDR4 16 GB', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(29, 'Laboratorium Microcontroler', 'Laboratorium', 18, 'Laboratorium Microcontroler adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 25 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'lab_1767854892_3342.jpg', 25, NULL, NULL, 0, 'CPU [Intel Core i5-4460]', 'RAM DDR4 [8 GB]', 'HDD [1 TB]', 'VGA NVIDIA Geforce GT 1650', 'Monitor LG 20” Inch', 'Livewire, Arduino, Microsoft Office, Google Chrome', '1 TV TCL 75” Inch, USB to HDMI, Kabel HDMI', NULL, NULL),
(30, 'Research Room 1', 'Riset', 17, 'Research Room 1 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'lab_1767854958_8098.png', 0, NULL, NULL, 0, '', '', '', '', '', '', '', NULL, NULL),
(31, 'Research Room 2', 'Riset', 20, 'Research Room 2 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'lab_1767855066_4115.jpg', 0, NULL, NULL, 0, '', '', '', '', '', '', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(32, 'Research Room 3', 'Riset', 16, 'Research Room 3 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'lab_1767855107_4991.png', 0, NULL, NULL, 0, '', '', '', '', '', '', '', NULL, NULL),
(33, 'Microcontroller', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'Computer Vision', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'Data Science', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'Start Up', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'IoT', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `laboratorium_gambar`
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
-- Dumping data for table `laboratorium_gambar`
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
-- Table structure for table `manajemen`
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
-- Dumping data for table `manajemen`
--

INSERT INTO `manajemen` (`idManajemen`, `nama`, `nidn`, `jabatan`, `foto`, `urutanTampilan`) VALUES
(1, 'Ir. Abdul Rachman Manga\', S.Kom., M.T., MTA., MCF', '0931018001', 'Kepala Laboratorium Jaringan Dan Pemrograman', 'manajemen_1767850035_5059.jpg', 0),
(5, 'Ir. Huzain Azis, S.Kom., M.Cs. MTA', '0920098801', 'Kepala Laboratorium Komputasi Dasar', 'manajemen_1767600806_3284.jpg', 0),
(6, 'Herdianti, S.Si., M.Eng., MTA.', '0924069001', 'Kepala Laboratorium Riset', 'manajemen_1767600880_4656.JPG', 0),
(7, 'Fatimah AR. Tuasamu, S.Kom., MTA, MOS', NULL, 'Laboran', 'manajemen_1767600916_8750.JPG', 0);

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `idMatakuliah` int(11) NOT NULL,
  `kodeMatakuliah` varchar(20) NOT NULL,
  `namaMatakuliah` varchar(150) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `sksKuliah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matakuliah`
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
-- Table structure for table `peraturan_lab`
--

CREATE TABLE `peraturan_lab` (
  `id` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kategori` varchar(100) DEFAULT 'umum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peraturan_lab`
--

INSERT INTO `peraturan_lab` (`id`, `judul`, `deskripsi`, `gambar`, `urutan`, `created_at`, `updated_at`, `kategori`) VALUES
(1, 'Tata Tertib Umum Laboratorium', 'Peraturan dasar yang harus dipatuhi oleh semua pengguna laboratorium teknologi informasi', NULL, 1, '2025-12-18 23:53:18', '2026-01-03 00:23:49', 'Larangan Umum'),
(2, 'Penggunaan Peralatan Lab', 'Ketentuan penggunaan komputer, jaringan, dan peralatan elektronik di laboratorium', NULL, 2, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'larangan-umum'),
(3, 'Jadwal dan Reservasi Ruangan', 'Prosedur pemesanan dan penggunaan ruang laboratorium untuk kegiatan praktikum', NULL, 3, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'kehadiran-akademik'),
(4, 'Keamanan dan Kebersihan', 'Aturan menjaga keamanan data, perangkat, dan kebersihan lingkungan laboratorium', NULL, 4, '2025-12-18 23:53:18', '2026-01-03 00:10:41', 'larangan-umum'),
(5, 'Sanksi dan Pelanggaran', 'Konsekuensi yang berlaku bagi mahasiswa yang melanggar tata tertib laboratorium', NULL, 5, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'larangan-umum');

-- --------------------------------------------------------

--
-- Table structure for table `sanksi_lab`
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
-- Dumping data for table `sanksi_lab`
--

INSERT INTO `sanksi_lab` (`id`, `judul`, `deskripsi`, `gambar`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 'Keterlambatan Hadir', 'Praktikan yang terlambat lebih dari 10 menit tidak diperkenankan mengikuti praktikum pada sesi tersebut. Status kehadiran akan dicatat sebagai Alpa (Tidak Hadir), yang dapat mempengaruhi kelulusan mata kuliah praktikum.', 'sanksi_1765767567_5524.png', 0, '2025-12-15 10:11:55', '2025-12-15 10:59:27'),
(2, 'Merusak Fasilitas', 'Jika terjadi kerusakan pada hardware (monitor, keyboard, mouse, dll) akibat kelalaian atau kesengajaan, praktikan wajib melakukan Penggantian Barang dengan spesifikasi yang sama atau setara dalam kurun waktu 1x24 jam.', NULL, 0, '2025-12-15 10:11:55', '2025-12-15 10:11:55'),
(3, 'Pelanggaran Seragam & Atribut', 'Praktikan yang tidak mengenakan seragam sesuai ketentuan atau tidak membawa Kartu Tanda Mahasiswa (KTM) akan mendapatkan Teguran Keras dan pengurangan poin nilai kedisiplinan pada sesi tersebut.', NULL, 0, '2025-12-15 10:11:55', '2025-12-15 10:11:55'),
(4, 'Kecurangan (Plagiasi/Mencontek)', 'Segala bentuk kecurangan seperti mencontek saat ujian atau menyalin tugas teman (plagiasi) akan dikenakan sanksi Nilai E (Tidak Lulus) secara otomatis pada mata kuliah praktikum yang bersangkutan.', NULL, 0, '2025-12-15 10:11:55', '2025-12-15 10:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `last_login`, `created_at`) VALUES
(2, '$2y$10$l710L0YPlhSBRxW23TpS..A8o9pbUd1RLcPLFY77Zm3', 'admin', 'admin', NULL, '2025-12-20 17:28:27'),
(4, 'admin', '$2y$10$9dZeOKeyCyGLbICQl4l2S.rhW9VQd7Tj5iqbdSe43yG1YKUv3Utey', 'admin', '2026-01-08 16:10:21', '2025-12-22 14:47:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asisten`
--
ALTER TABLE `asisten`
  ADD PRIMARY KEY (`idAsisten`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_asisten_email` (`email`);

--
-- Indexes for table `format_penulisan`
--
ALTER TABLE `format_penulisan`
  ADD PRIMARY KEY (`id_format`);

--
-- Indexes for table `integrsiweb`
--
ALTER TABLE `integrsiweb`
  ADD PRIMARY KEY (`idIntegrasi`);

--
-- Indexes for table `jadwalpraktikum`
--
ALTER TABLE `jadwalpraktikum`
  ADD PRIMARY KEY (`idJadwal`),
  ADD KEY `idx_jadwal_matakuliah` (`idMatakuliah`),
  ADD KEY `idx_jadwal_lab` (`idLaboratorium`);

--
-- Indexes for table `laboratorium`
--
ALTER TABLE `laboratorium`
  ADD PRIMARY KEY (`idLaboratorium`),
  ADD KEY `idKordinatorAsisten` (`idKordinatorAsisten`);

--
-- Indexes for table `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  ADD PRIMARY KEY (`idGambar`),
  ADD KEY `idLaboratorium` (`idLaboratorium`);

--
-- Indexes for table `manajemen`
--
ALTER TABLE `manajemen`
  ADD PRIMARY KEY (`idManajemen`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`idMatakuliah`),
  ADD UNIQUE KEY `kodeMatakuliah` (`kodeMatakuliah`),
  ADD KEY `idx_matakuliah_kode` (`kodeMatakuliah`);

--
-- Indexes for table `peraturan_lab`
--
ALTER TABLE `peraturan_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sanksi_lab`
--
ALTER TABLE `sanksi_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `asisten`
--
ALTER TABLE `asisten`
  MODIFY `idAsisten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `format_penulisan`
--
ALTER TABLE `format_penulisan`
  MODIFY `id_format` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `integrsiweb`
--
ALTER TABLE `integrsiweb`
  MODIFY `idIntegrasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwalpraktikum`
--
ALTER TABLE `jadwalpraktikum`
  MODIFY `idJadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `laboratorium`
--
ALTER TABLE `laboratorium`
  MODIFY `idLaboratorium` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  MODIFY `idGambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `manajemen`
--
ALTER TABLE `manajemen`
  MODIFY `idManajemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `idMatakuliah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `peraturan_lab`
--
ALTER TABLE `peraturan_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sanksi_lab`
--
ALTER TABLE `sanksi_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwalpraktikum`
--
ALTER TABLE `jadwalpraktikum`
  ADD CONSTRAINT `jadwalpraktikum_ibfk_1` FOREIGN KEY (`idMatakuliah`) REFERENCES `matakuliah` (`idMatakuliah`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwalpraktikum_ibfk_2` FOREIGN KEY (`idLaboratorium`) REFERENCES `laboratorium` (`idLaboratorium`) ON DELETE CASCADE;

--
-- Constraints for table `laboratorium`
--
ALTER TABLE `laboratorium`
  ADD CONSTRAINT `laboratorium_ibfk_1` FOREIGN KEY (`idKordinatorAsisten`) REFERENCES `asisten` (`idAsisten`) ON DELETE SET NULL;

--
-- Constraints for table `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  ADD CONSTRAINT `fk_lab_gambar_lab` FOREIGN KEY (`idLaboratorium`) REFERENCES `laboratorium` (`idLaboratorium`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
