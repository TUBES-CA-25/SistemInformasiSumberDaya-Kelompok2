-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jan 2026 pada 10.41
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
(18, 'Arisa Tien Hardianti, S.Kom', '2020', 'Asisten', 'Basis Data I', NULL, '', 'Basis Data, SQL, Pengajaran', '', '2026-01-08 15:23:35', '2026-01-10 11:30:22', 0),
(19, 'Syartina Elfarika Basri, S.Kom', '2020', 'Asisten', '[\"Pemrograman Dasar\"]', NULL, '', '[\"Web Development\",\"Javascript\",\"PHP\",\"Laravel\",\"Tailwind CSS\"]', '', '2026-01-17 01:04:43', '2026-01-17 01:04:43', 0),
(20, 'Irwan Ardyansyah, S.Kom', '2020', 'Asisten', '[\"Elektronika Dasar\"]', NULL, '', '[\"Internet of Things (IoT)\",\"Arduino\"]', '', '2026-01-17 01:07:16', '2026-01-17 01:07:16', 0),
(21, 'Furqaan Ismail, S.Kom', '2020', 'Asisten', '[\"Pemrograman Berorientasi Objek\"]', NULL, '', '[\"java \",\"C++\"]', '', '2026-01-17 01:10:02', '2026-01-17 01:10:02', 0),
(22, 'Rifqatul Mukarramah, S.Kom', '2020', 'Asisten', '[\"Pemrograman Berorientasi Objek\"]', NULL, '', '[\"Java\",\"C++\"]', '', '2026-01-17 01:11:02', '2026-01-17 01:11:02', 0),
(23, 'Widyas, S.Kom', '2020', 'Asisten', '[\"Algoritma & Pemrograman\"]', NULL, '', '[\"C++\",\"MySQL\"]', '', '2026-01-17 01:12:49', '2026-01-17 01:12:49', 0),
(24, 'Ayu Ashari Ramadhan, S.Kom', '2020', 'Asisten', '[\"Desain Grafis\"]', NULL, '', '[\"UI/UX Design\"]', '', '2026-01-17 01:13:56', '2026-01-17 01:13:56', 0),
(25, 'Muhammad Dhiya Ulhaq, S.Kom', '2020', 'Asisten', '[\"Desain Grafis\"]', NULL, '', '[\"UI/UX Design\"]', '', '2026-01-17 01:14:37', '2026-01-17 01:14:37', 0),
(26, 'Muh. Syawal, S.Kom', '2020', 'Asisten', '[\"Algoritma & Pemrograman\"]', NULL, '', '[\"C++\"]', '', '2026-01-17 01:15:30', '2026-01-17 01:15:30', 0),
(27, 'Taufik Baharsyah, S.Kom', '2020', 'Asisten', '[\"Elektronika Dasar\"]', NULL, '', '[\"Internet of Things (IoT)\",\"Arduino\"]', '', '2026-01-17 01:16:49', '2026-01-17 01:16:49', 0),
(28, 'Muhammad Trisnandar, S.Kom', '2020', 'Asisten', '[\"Elektronika Dasar\",\"Algoritma & Pemrograman\"]', NULL, '', '[\"Internet of Things (IoT)\",\"C++\"]', '', '2026-01-17 01:18:17', '2026-01-17 01:18:17', 0),
(29, 'Yudha Islami Sulistya, S.Kom', '2020', 'Asisten', '[\"Elektronika Dasar\"]', NULL, '', '[\"Arduino\",\"Internet of Things (IoT)\"]', '', '2026-01-17 01:19:14', '2026-01-17 01:19:14', 0),
(30, 'Nurul A\'ayunnisa, S.Kom', '2020', 'Asisten', '[\"Algoritma & Pemrograman\"]', NULL, '', '[\"C++\"]', '', '2026-01-17 01:20:00', '2026-01-17 01:20:00', 0),
(31, 'Ericha Apriliyani, S.Kom', '2020', 'Asisten', '[\"Basis Data\"]', NULL, '', '[\"MySQL\",\"Database Management\"]', '', '2026-01-17 01:20:45', '2026-01-17 01:20:45', 0),
(32, 'Kasmira, S.Kom', '2020', 'Asisten', '[\"Basis Data\"]', NULL, '', '[\"MySQL\",\"Database Management\"]', '', '2026-01-17 01:21:35', '2026-01-17 01:21:35', 0),
(33, 'M. Ikraam Ar Razaaq, S.Kom', '2020', 'Asisten', '[\"Basis Data\"]', NULL, '', '[\"Database Management\",\"MySQL\"]', '', '2026-01-17 01:22:38', '2026-01-17 01:22:38', 0),
(34, 'Andi Muchlisa, S.Kom', '2020', '', '[\"Pemrograman Web\"]', NULL, '', '[\"Web Development\",\"MySQL\",\"Tailwind CSS\",\"Javascript\"]', '', '2026-01-17 01:23:38', '2026-01-17 01:23:38', 0),
(35, 'Naufal Abiyyu Supriadi, S.Kom', '2021', 'Asisten', '[\"Microcontroller\",\"Algoritma & Pemrograman\",\"Pengantar Teknologi Informasi\",\"Pemrograman Web\",\"Elektronika Dasar\",\"Jaringan Komputer\",\"Pemrograman Berorientasi Objek\"]', 'alumni/alumni_naufal-abiyyu-supriadi-s-kom_1768631335_337.png', '', '[\"Arduino\",\"Internet of Things (IoT)\",\"C++\"]', '', '2026-01-17 14:28:55', '2026-01-17 14:43:14', 0),
(36, 'Nasrullah, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Pemrograman Web\",\"Pengantar Teknologi Informasi\",\"Struktur Data\",\"Pemrograman Berorientasi Objek\"]', 'alumni/alumni_nasrullah-s-kom_1768631585_930.png', '', '[\"Laravel\",\"PHP\",\"Web Development\",\"MySQL\",\"Database Management\",\"Data Science\",\"Machine Learning\",\"Artificial Intelligence\"]', '', '2026-01-17 14:33:05', '2026-01-17 14:40:37', 0),
(37, 'Furqon Fatahillah, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Pengatar Teknologi Informasi\",\"Pemrograman Berorientasi Objek\",\"Pemrograman Web\",\"Design Grafis\",\"Multemedia System\",\"Pemrograman Mobile\"]', 'alumni/alumni_furqon-fatahillah-s-kom_1768631762_255.png', '', '[\"Web Development\",\"PHP\",\"Tailwind CSS\",\"Laravel\",\"Javascript\"]', '', '2026-01-17 14:36:02', '2026-01-17 14:42:14', 0),
(38, 'Nurul Azmi, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Struktur Data\",\"Pengantar Teknologi Informasi\",\"Basis Data\",\"Sistem dan Teknologi Informasi\",\"Elektronika Dasar\",\"Jaringan Komputer\"]', 'alumni/alumni_nurul-azmi-s-kom_1768631991_953.png', '', '[\"Database Management\",\"Cisco\",\"C++\"]', '', '2026-01-17 14:39:51', '2026-01-17 14:39:51', 0),
(39, 'Ahmad Rendi, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Microcontroller\",\"Struktur Data\",\"Pemrograman Web\",\"Pemrograman Berorientasi Objek\"]', 'alumni/alumni_ahmad-rendi-s-kom_1768632508_800.png', '', '[\"Java\",\"c++\",\"Web Development\",\"Android Studio\"]', '', '2026-01-17 14:48:28', '2026-01-17 14:48:28', 0),
(40, 'As\'syahrin Nanda, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Pemrograman Web\",\"Pengantar Teknologi Informasi\",\"Struktur Data\"]', 'alumni/alumni_as-syahrin-nanda-s-kom_1768632688_557.png', '', '[\"Web Development\",\"Laravel\",\"PHP\",\"Tailwind CSS\",\"Javascript\",\"Database Management\",\"MySQL\"]', '', '2026-01-17 14:51:28', '2026-01-17 14:51:44', 0),
(41, 'Annisa Pratama Putri, S.Kom', '2021', 'Asisten', '[\"Pengenalan Teknologi Informasi\",\"Struktur Data\"]', 'alumni/alumni_annisa-pratama-putri-s-kom_1768632945_437.png', '', '[\"C++\",\"MySQL\"]', '', '2026-01-17 14:55:45', '2026-01-17 14:55:45', 0),
(42, 'Nirmala, S.Kom', '2021', '', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Aplikasi Akuntansi\",\"Pemrograman Berorientasi Objek\",\"Pemrograman Web\"]', 'alumni/alumni_nirmala-s-kom_1768633199_598.png', '', '[\"C++\",\"MySQL\",\"Laravel\",\"PHP\",\"Javascript\",\"Bootstrap\"]', '', '2026-01-17 14:59:59', '2026-01-17 14:59:59', 0),
(43, 'Athar Fathana Rakasyah, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Pemrograman Web\",\"Struktur Data\"]', 'alumni/alumni_athar-fathana-rakasyah-s-kom_1768633408_284.png', '', '[\"MySQL\",\"C++\",\"Javascript\",\"PHP\",\"Laravel\",\"Bootstrap\",\"Database Management\"]', '', '2026-01-17 15:02:49', '2026-01-17 15:03:28', 0),
(44, 'Muhammad Dani Arya Putra, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Pengenalan Teknologi Informasi\",\"Aplikasi Akuntansi\",\"Sistem Operasi\",\"Pemrograman Berorientasi Objek\",\"Multimedia System\",\"Pemrograman Mobile\"]', 'alumni/alumni_muhammad-dani-arya-putra-s-kom_1768633687_530.png', '', '[\"Android Studio\",\"C++\",\"java\",\"Mobile Development\"]', '', '2026-01-17 15:08:07', '2026-01-17 15:08:07', 0),
(45, 'Muhammad Akbar, S.Kom', '2021', 'Asisten', '[\"Algoritma & Pemrograman\",\"Basis Data\",\"Pemrograman Mobile\",\"Pengantar Teknologi Informasi\",\"Struktur Data\",\"Sistem Operasi\"]', 'alumni/alumni_muhammad-akbar-s-kom_1768633975_214.png', '', '[\"Web Development\",\"PHP\",\"Laravel\",\"Bootstrap\",\"MySQL\",\"Database Management\",\"Mobile Development\",\"Tailwind CSS\",\"React\"]', '', '2026-01-17 15:12:55', '2026-01-17 15:12:55', 0),
(46, 'Imran Afdillah Dahlan, S.Kom', '2021', 'Asisten', '[\"Jaringan Komputer\",\"Basis Data\",\"Pengantar Teknologi Informasi\",\"Sistem dan Teknologi Informasi\",\"Struktur Data\"]', 'alumni/alumni_imran-afdillah-dahlan-s-kom_1768634238_596.png', '', '[\"Network Engineer\",\"Cisco\"]', '', '2026-01-17 15:17:18', '2026-01-17 15:17:18', 0);

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
(13, 'Adam Adnan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Koordinator Laboratorium', '', 'adamadnan.iclabs@umi.ac.id', 'asisten/asisten_adam-adnan_1768638132_989.jpg', 'Asisten', 0, 0),
(14, 'Dewi Ernita Rahma', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Basisdata, Struktur Data, Algoritma Pemrograman', '', 'dewiernitarahma.iclabs@umi.ac.id', 'asisten/asisten_dewi-ernita-rahma_1768638255_157.png', 'Asisten', 0, 0),
(15, 'Farid Wadji Mufti', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Basisdata, Microcontroller', '', 'faridwajdimufli.iclabs@umi.ac.id', 'asisten/asisten_farid-wadji-mufti_1768638322_317.jpg', 'Asisten', 0, 0),
(16, 'Julisa', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Basisdata, Algoritma Pemrograman, Pengenalan Pemrograman', '', 'julisa.iclabs@umi.ac.id', 'asisten/asisten_julisa_1768638421_414.jpg', 'Asisten', 0, 0),
(17, 'Maharani Safwa Andini', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Microcontroller, Pengenalan Pemrograman', '', 'maharanisahwaandini.iclabs@umi.ac.id', 'asisten/asisten_maharani-safwa-andini_1768638534_729.jpg', 'Asisten', 0, 0),
(18, 'Ahmad Mufli Ramadhan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Pengenalan Pemrograman, Struktur Data, PBO', '', 'ahmadmufliramadhan.iclabs@umi.ac.id', 'asisten/asisten_ahmad-mufli-ramadhan_1768638173_117.jpeg', 'Asisten', 0, 0),
(19, 'Muhammad Alif Maulana. R', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Jaringan Komputer, Basisdata, Pengenalan Pemrograman', '', 'muhalifmaulanaar.iclabs@umi.ac.id', 'asisten/asisten_muhammad-alif-maulana-r_1768638571_873.png', 'Asisten', 0, 0),
(20, 'Tazkira Amalia', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Pengantar Teknologi Informasi, Struktur Data, PBO', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', 'tazkirahamalia.iclabs@umi.ac.id', 'asisten/asisten_tazkira-amalia_1768638762_188.jpg', 'Asisten', 0, 0),
(21, 'Wahyu Kadri Rahmat Suat', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Microcontroller, Basisdata, Struktur Data', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', 'wahyukadrirahmatsuat.iclabs@umi.ac.id', 'asisten/asisten_wahyu-kadri-rahmat-suat_1768638789_507.png', 'Asisten', 0, 0),
(22, 'Aan Maulana Sampe', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pemrograman Berorientasi Objek, Jaringan Komputer', '[\"Mobile\"]', '13020230081@student.umi.ac.id', 'asisten/asisten_aan-maulana-sampe_1768638820_770.png', 'CA', 0, 3),
(23, 'Andi Ahsan Ashuri', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[\"Mobile\"]', '13020230224@student.umi.ac.id', 'asisten/asisten_andi-ahsan-ashuri_1768638851_622.png', 'CA', 0, 3),
(24, 'Andi Ikhlas Mallomo', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Struktur Data, Basisdata', '[\"Mobile\"]', '13020230251@student.umi.ac.id', 'asisten/asisten_andi-ikhlas-mallomo_1768638836_169.jpg', 'CA', 0, 3),
(25, 'Andi Rifqi Aunur Rahman', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten Pengenalan Pemrograman, Basisdata', '', '13020230219@student.umi.ac.id', 'asisten/asisten_andi-rifqi-aunur-rahman_1768638235_110.png', 'CA', 0, 0),
(26, 'Farah Tsabitaputri Az Zahra', 'Teknik Informatika', NULL, NULL, NULL, NULL, '', '', '13020230268@student.umi.ac.id', 'asisten/asisten_farah-tsabitaputri-az-zahra_1768638292_465.jpg', 'CA', 0, 0),
(27, 'Firli Anastasya Hafid', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata, Algoritma Pemrograman', '', '13020230241@student.umi.ac.id', 'asisten/asisten_firli-anastasya-hafid_1768638348_865.jpg', 'CA', 0, 0),
(28, 'Hendrawan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pengenalan Pemrograman', '', '13020230309@student.umi.ac.id', 'asisten/asisten_hendrawan_1768638372_941.jpg', 'CA', 0, 0),
(29, 'Ichwal', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Jaringan Komputer, PBO, Struktur Data', '', '13020230049@student.umi.ac.id', 'asisten/asisten_ichwal_1768638394_794.jpg', 'CA', 0, 0),
(30, 'Laode Muhammad Dhaifan Kasyfillah', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pengenalan Pemrograman', '', '13020230232@student.umi.ac.id', 'asisten/asisten_laode-muhammad-dhaifan-kasyfillah_1768638455_997.jpg', 'CA', 0, 0),
(31, 'Muh. Fatwah Fajriansyah M.', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pengantar Teknologi Informasi', '', '13020230319@student.umi.ac.id', 'asisten/asisten_muh-fatwah-fajriansyah-m_1768638556_133.jpg', 'CA', 0, 0),
(32, 'Muhammad Nur Fuad', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '', '13020230030@student.umi.ac.id', 'asisten/asisten_muhammad-nur-fuad_1768638587_110.jpg', 'CA', 0, 0),
(33, 'Muhammad Rafli', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '', '13020230290@student.umi.ac.id', 'asisten/asisten_muhammad-rafli_1768638601_321.jpg', 'CA', 0, 0),
(34, 'Muhammad Rifky Saputra Scania', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '', '13020230193@student.umi.ac.id', 'asisten/asisten_muhammad-rifky-saputra-scania_1768638617_594.jpg', 'CA', 0, 0),
(35, 'M. Rizwan', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman, Basisdata', '', '13020230100@student.umi.ac.id', 'asisten/asisten_m-rizwan_1768638524_885.png', 'CA', 0, 0),
(36, 'Nahwa Kaka Saputra Anggareksa', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230187@student.umi.ac.id', 'asisten/asisten_nahwa-kaka-saputra-anggareksa_1768638664_831.jpg', 'CA', 0, 0),
(37, 'Nurfajri Mukmin Saputra', 'Sistem Informasi', NULL, NULL, NULL, NULL, 'Asisten 2 Struktur Data, Pengantar Teknologi Informasi', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13120230033@student.umi.ac.id', 'asisten/asisten_nurfajri-mukmin-saputra_1768638683_151.jpg', 'CA', 0, 0),
(38, 'Raihan Nur Rizqillah', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Microcontroller', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230306@student.umi.ac.id', 'asisten/asisten_raihan-nur-rizqillah_1768638700_597.png', 'CA', 0, 0),
(39, 'Rizqi Ananda Jalil', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata, Pengenalan Pemrograman', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230244@student.umi.ac.id', 'asisten/asisten_rizqi-ananda-jalil_1768638717_257.jpg', 'CA', 0, 0),
(40, 'Siti Safira Tawetubun', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Algoritma Pemrograman', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230217@student.umi.ac.id', 'asisten/asisten_siti-safira-tawetubun_1768638728_262.png', 'CA', 0, 0),
(41, 'Sitti Lutfia', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230255@student.umi.ac.id', 'asisten/asisten_sitti-lutfia_1768638738_708.jpeg', 'CA', 0, 0),
(42, 'Sitti Nurhalimah', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230297@student.umi.ac.id', 'asisten/asisten_sitti-nurhalimah_1768638749_142.jpeg', 'CA', 0, 0),
(43, 'Thalita Sherly Putri Jasmin', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Pemrograman Berorientasi Objek', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230096@student.umi.ac.id', 'asisten/asisten_thalita-sherly-putri-jasmin_1768638778_923.png', 'CA', 0, 0),
(44, 'Zaki Falihin Ayyubi', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Asisten 2 Basisdata', '[\"Design Grafis\",\"UI/UX\",\"React\",\"Web Development\"]', '13020230253@student.umi.ac.id', 'asisten/asisten_zaki-falihin-ayyubi_1768638800_581.jpg', 'CA', 0, 0);

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
(17, 'Kelengkapan Sampul', 'Judul: Kapital, Bold, Font 14 (Tengah Atas).\r\nLogo UMI ukuran 5x6 cm (300 dpi).\r\nData: Nama, Stambuk, Frekuensi, Dosen, & Asisten.', NULL, 'pedoman', '', '2026-01-17', 'ri-file-list-3-line', 'icon-red', 1);

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
(23, 'Start Up', 'Laboratorium', 18, 'Laboratorium Startup adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 36 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif', 'laboratorium/lab_start-up_1768639559_164.jpg', 36, NULL, NULL, 36, 'Inter core i7-9700F', 'DDR4 16 GB', 'SSD SATA 500 GB', 'VGA MSI GeForce GTX 1650', 'Monitor LG 22\" Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After, Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '3 TV TCL 75” Inch, 1 Buah Speaker Samsung, Spliter HDMI, Kabel HDMI', NULL, NULL),
(24, 'IoT', 'Laboratorium', 21, 'Laboratorium IOT adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 24 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 24 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'laboratorium/lab_iot_1768639632_573.jpg', 24, NULL, NULL, 24, 'CPU [Intel Core i5-7100]', 'RAM DDR4 [8 GB]', 'HDD [1 TB]', 'VGA NVIDIA Geforce GT 1030', 'Monitor LG 22” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(25, 'Computer Network', 'Laboratorium', 21, 'Laboratorium Computer Network adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 15 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 24 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'laboratorium/lab_computer-network_1768639782_134.png', 15, NULL, NULL, 24, 'CPU [Intel Core i7-10700k]', 'DDR4 16 GB', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor AOC 27” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(26, 'Data Science', 'Laboratorium', 18, 'Laboratorium Data Science adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 26 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'laboratorium/lab_data-science_1768639929_894.jpg', 26, NULL, NULL, 26, 'CPU [Intel i7-12700f]', 'RAM DDR4 [16 GB]', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(27, 'Computer Vision', 'Laboratorium', 19, 'Laboratorium Computer Vision adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 26 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'laboratorium/lab_computer-vision_1768639968_375.jpg', 26, NULL, NULL, 26, 'CPU [Intel i7-12700f]', 'RAM DDR4 [16 GB]', 'SSD NVME 512 GB', 'VGA NVIDIA Geforce GT 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '1 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(28, 'Multimedia', 'Laboratorium', 19, 'Laboratorium Multimedia adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 30 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 30 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'laboratorium/lab_multimedia_1768640031_383.jpg', 30, NULL, NULL, 30, 'CPU [Intel i7-12700f]', 'RAM DDR4 16 GB', 'SSD NVME 512 GB', 'VGA MSI GeForce GTX 1650', 'Monitor Mi 23.8” Inch', 'Apache Netbeans, Embarcadero Dev-Cpp / Dev C++, Visual Studio Code, Xampp, Git, Adobe After Effects, Adobe Illustrator, Adobe Photoshop, Adobe Premiere Pro, Postman, Android Studio, Emulator Android, Cisco Packet Tracer, VirtualBox, Geany, MySQL Workbench, Microsoft Office, Browser', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(29, 'Microcontroller', 'Laboratorium', 18, 'Laboratorium Microcontroler adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal. Dengan kapasitas maksimal 25 mahasiswa, laboratorium ini memastikan suasana belajar yang kondusif dan interaktif.', 'laboratorium/lab_microcontroller_1768640093_501.jpg', 25, NULL, NULL, 25, 'CPU [Intel Core i5-4460]', 'RAM DDR4 [8 GB]', 'HDD [1 TB]', 'VGA NVIDIA Geforce GT 1650', 'Monitor LG 20” Inch', 'Livewire, Arduino, Microsoft Office, Google Chrome', '1 TV TCL 75” Inch, USB to HDMI, Kabel HDMI', NULL, NULL),
(30, 'Research Room 1', 'Riset', 17, 'Research Room 1 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'laboratorium/lab_research-room-1_1768640174_766.png', 0, NULL, NULL, 12, '', '', '', '', '', '', '', NULL, NULL),
(31, 'Research Room 2', 'Riset', 20, 'Research Room 2 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'laboratorium/lab_research-room-2_1768640269_731.jpg', 0, NULL, NULL, 12, '', '', '', '', '', '', '2 TV TCL 75” Inch, Spliter HDMI, Kabel HDMI', NULL, NULL),
(32, 'Research Room 3', 'Riset', 16, 'Research Room 3 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang. dengan kategori Laboratorium Research.', 'laboratorium/lab_research-room-3_1768640323_903.png', 0, NULL, NULL, 12, '', '', '', '', '', '', '', NULL, NULL);

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
(55, 23, 'laboratorium/lab_start-up_1768639559_164.jpg', NULL, 1, 0, '2026-01-17 16:45:59'),
(56, 23, 'laboratorium/lab_start-up_1768639559_453.jpg', NULL, 0, 1, '2026-01-17 16:45:59'),
(57, 23, 'laboratorium/lab_start-up_1768639559_933.jpg', NULL, 0, 2, '2026-01-17 16:45:59'),
(58, 24, 'laboratorium/lab_iot_1768639632_573.jpg', NULL, 1, 0, '2026-01-17 16:47:12'),
(59, 24, 'laboratorium/lab_iot_1768639632_395.jpg', NULL, 0, 1, '2026-01-17 16:47:12'),
(60, 24, 'laboratorium/lab_iot_1768639632_470.jpg', NULL, 0, 2, '2026-01-17 16:47:12'),
(61, 25, 'laboratorium/lab_computer-network_1768639782_134.png', NULL, 1, 0, '2026-01-17 16:49:42'),
(62, 25, 'laboratorium/lab_computer-network_1768639782_782.png', NULL, 0, 1, '2026-01-17 16:49:42'),
(63, 25, 'laboratorium/lab_computer-network_1768639782_623.png', NULL, 0, 2, '2026-01-17 16:49:42'),
(64, 25, 'laboratorium/lab_computer-network_1768639782_725.png', NULL, 0, 3, '2026-01-17 16:49:42'),
(69, 26, 'laboratorium/lab_data-science_1768639929_894.jpg', NULL, 1, 0, '2026-01-17 16:52:09'),
(70, 26, 'laboratorium/lab_data-science_1768639929_893.jpg', NULL, 0, 1, '2026-01-17 16:52:09'),
(71, 26, 'laboratorium/lab_data-science_1768639929_872.jpg', NULL, 0, 2, '2026-01-17 16:52:09'),
(72, 26, 'laboratorium/lab_data-science_1768639929_566.jpg', NULL, 0, 3, '2026-01-17 16:52:09'),
(73, 27, 'laboratorium/lab_computer-vision_1768639968_375.jpg', NULL, 1, 0, '2026-01-17 16:52:48'),
(74, 27, 'laboratorium/lab_computer-vision_1768639968_839.jpg', NULL, 0, 1, '2026-01-17 16:52:48'),
(75, 28, 'laboratorium/lab_multimedia_1768640031_383.jpg', NULL, 1, 0, '2026-01-17 16:53:51'),
(76, 28, 'laboratorium/lab_multimedia_1768640031_282.jpg', NULL, 0, 1, '2026-01-17 16:53:51'),
(77, 28, 'laboratorium/lab_multimedia_1768640031_320.jpg', NULL, 0, 2, '2026-01-17 16:53:51'),
(78, 28, 'laboratorium/lab_multimedia_1768640031_584.jpg', NULL, 0, 3, '2026-01-17 16:53:51'),
(79, 28, 'laboratorium/lab_multimedia_1768640031_514.jpg', NULL, 0, 4, '2026-01-17 16:53:51'),
(80, 29, 'laboratorium/lab_microcontroller_1768640093_501.jpg', NULL, 1, 0, '2026-01-17 16:54:53'),
(81, 29, 'laboratorium/lab_microcontroller_1768640093_384.jpg', NULL, 0, 1, '2026-01-17 16:54:53'),
(82, 29, 'laboratorium/lab_microcontroller_1768640093_298.jpg', NULL, 0, 2, '2026-01-17 16:54:53'),
(83, 29, 'laboratorium/lab_microcontroller_1768640093_991.jpg', NULL, 0, 3, '2026-01-17 16:54:53'),
(84, 29, 'laboratorium/lab_microcontroller_1768640093_704.jpg', NULL, 0, 4, '2026-01-17 16:54:53'),
(85, 30, 'laboratorium/lab_research-room-1_1768640174_766.png', NULL, 1, 0, '2026-01-17 16:56:14'),
(86, 31, 'laboratorium/lab_research-room-2_1768640269_731.jpg', NULL, 1, 0, '2026-01-17 16:57:49'),
(89, 31, 'laboratorium/lab_research-room-2_1768640269_112.jpg', NULL, 0, 3, '2026-01-17 16:57:49'),
(90, 31, 'laboratorium/lab_research-room-2_1768640269_389.jpg', NULL, 0, 4, '2026-01-17 16:57:49'),
(91, 32, 'laboratorium/lab_research-room-3_1768640323_903.png', NULL, 1, 0, '2026-01-17 16:58:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `manajemen`
--

CREATE TABLE `manajemen` (
  `idManajemen` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nidn` varchar(20) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `manajemen`
--

INSERT INTO `manajemen` (`idManajemen`, `nama`, `nidn`, `jabatan`, `email`, `foto`) VALUES
(1, 'Ir. Abdul Rachman Manga\', S.Kom., M.T., MTA., MCF', '0931018001', 'Kepala Laboratorium Jaringan Dan Pemrograman', 'abdulrachman.manga@umi.ac.id', 'manajemen/manajemen_ir-abdul-rachman-manga-s-kom-m-t-mta-mcf_1768640672_324.jpg'),
(5, 'Ir. Huzain Azis, S.Kom., M.Cs. MTA', '0920098801', 'Kepala Laboratorium Komputasi Dasar', 'huzain.azis@umi.ac.id', 'manajemen/manajemen_ir-huzain-azis-s-kom-m-cs-mta_1768640702_175.jpg'),
(6, 'Herdianti, S.Si., M.Eng., MTA.', '0924069001', 'Kepala Laboratorium Riset', 'herdianti.darwis@umi.ac.id', 'manajemen/manajemen_herdianti-s-si-m-eng-mta_1768640724_134.jpg'),
(7, 'Fatimah AR. Tuasamu, S.Kom., MTA, MOS', '-', 'Laboran', 'fatimahar@umi.ac.id', 'manajemen/manajemen_fatimah-ar-tuasamu-s-kom-mta-mos_1768640737_655.jpg');

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
-- Struktur dari tabel `modul`
--

CREATE TABLE `modul` (
  `id_modul` int(11) NOT NULL,
  `jurusan` enum('TI','SI') NOT NULL,
  `nama_matakuliah` varchar(100) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `modul`
--

INSERT INTO `modul` (`id_modul`, `jurusan`, `nama_matakuliah`, `judul`, `deskripsi`, `file`, `created_at`) VALUES
(1, 'SI', 'Aplikasi Akuntansi', 'Modul Aplikasi Akuntansi', '', '696a40c299cb4.pdf', '2026-01-16 13:44:34'),
(5, 'SI', 'Algoritma dan Pemrograman I', 'Modul Algoritma dan Pemrograman I', '', '696a58ca54cbc.pdf', '2026-01-16 15:27:06'),
(6, 'SI', 'Basis Data II', 'Modul Basis Data II', '', '696a597133c96.pdf', '2026-01-16 15:29:53'),
(7, 'SI', 'Jaringan Komputer', 'Modul Jaringan Komputer', '', '696a598e4c9cc.pdf', '2026-01-16 15:30:22'),
(8, 'SI', 'Pemrograman Web', 'Modul Pemrograman Web', '', '696a59ab0d1c5.pdf', '2026-01-16 15:30:51'),
(9, 'SI', 'Sistem dan Teknologi Informasi', 'Modul Sistem dan Teknologi Informasi', '', '696a59f4bfb12.pdf', '2026-01-16 15:32:04'),
(10, 'SI', 'Sistem Operasi', 'Modul Sistem Operasi', '', '696a5a13a2c57.pdf', '2026-01-16 15:32:35'),
(11, 'SI', 'Algoritma dan Struktur Data', 'Modul Algoritma dan Struktur Data', '', '696a5a5f080ed.pdf', '2026-01-16 15:33:51'),
(12, 'SI', 'Desain Grafis', 'Modul Desain Grafis', '', '696a5a7a971a7.pdf', '2026-01-16 15:34:18'),
(13, 'SI', 'Multimedia System', 'Modul Multimedia System', '', '696a5aa19be94.pdf', '2026-01-16 15:34:57'),
(14, 'SI', 'Pemrograman Mobile', 'Modul Pemrograman Mobile', '', '696a5abb7e90f.pdf', '2026-01-16 15:35:23'),
(15, 'TI', 'Algoritma dan Pemrograman I', 'Modul Algoritma dan Pemrograman I', '', '696a5b2b0949c.pdf', '2026-01-16 15:37:15'),
(16, 'TI', 'Basis Data II', 'Modul Basis Data II', '', '696a5b40a4123.pdf', '2026-01-16 15:37:36'),
(17, 'TI', 'Microcontroller', 'Modul Microcontroller', '', '696a5b5c8fe3c.pdf', '2026-01-16 15:38:04'),
(18, 'TI', 'Pemrograman Mobile', 'Modul Pemrograman Mobile', '', '696a5b729aeac.pdf', '2026-01-16 15:38:26'),
(19, 'TI', 'Pengantar Teknologi Informasi', 'Modul Pengantar Teknologi Informasi', '', '696a5b8c9fda3.pdf', '2026-01-16 15:38:52'),
(20, 'TI', 'Stuktur Data', 'Modul Stuktur Data', '', '696a5ba361e29.pdf', '2026-01-16 15:39:15'),
(21, 'TI', 'Algoritma dan Pemrograman II', 'Modul Algoritma dan Pemrograman II', '', '696a5bccf18a3.pdf', '2026-01-16 15:39:56'),
(22, 'TI', 'Basis Data I', 'Modul Basis Data I', '', '696a5be0db37f.pdf', '2026-01-16 15:40:16'),
(23, 'TI', 'Elektronika Dasar', 'Modul Elektronika Dasar', '', '696a5bfd939b2.pdf', '2026-01-16 15:40:45'),
(24, 'TI', 'Jaringan Komputer', 'Modul Jaringan Komputer', '', '696a5c11bed24.pdf', '2026-01-16 15:41:05'),
(25, 'TI', 'Pemrograman Berorientasi Objek', 'Modul Pemrograman Berorientasi Objek', '', '696a5c2d80201.pdf', '2026-01-16 15:41:33'),
(26, 'TI', 'Pemrograman Web', 'Modul Pemrograman Web', '', '696a5c40d46bd.pdf', '2026-01-16 15:41:52');

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
(2, 'Kehadiran & Akademik', 'Mahasiswa wajib mengikuti pertemuan di laboratorium minimal 10 kali pertemuan atau 75% dari total 14 kali pertemuan.\r\n\r\nMahasiswa yang tidak memenuhi syarat kehadiran minimal 10 kali pertemuan tidak diperkenankan mengikuti UAS.\r\n\r\nPelaksanaan praktikum di laboratorium dibagi berdasarkan kurikulum yang berlaku dengan durasi waktu yang berbeda.\r\n\r\nPenggunaan fasilitas Laboratorium menyesuaikan dengan kapasitas ruang Laboratorium.', NULL, 2, '2025-12-18 23:53:18', '2026-01-17 00:46:21', 'Kehadiran & Akademik', 'list'),
(3, 'Durasi & Toleransi Waktu', 'Setiap sesi praktikum untuk Mata Kuliah Kurikulum 2025 berlangsung selama 200 menit.\r\n\r\nSetiap sesi praktikum untuk Mata Kuliah Kurikulum 2021 berlangsung selama 150 menit.\r\n\r\nPraktikum harus dimulai sesuai jadwal yang telah ditentukan.\r\n\r\nToleransi keterlambatan bagi praktikan adalah maksimal 5 menit pada semua sesi.\r\n\r\nKhusus hari Jumat sesi siang setelah Salat Jumat, toleransi keterlambatan maksimal adalah 10 menit.', NULL, 3, '2025-12-18 23:53:18', '2026-01-17 00:46:51', 'kehadiran-akademik', 'list'),
(11, 'Larangan Umum & Etika', 'Praktikan diwajibkan bersikap tenang, tertib, dan sopan selama berada dalam ruangan.\r\n\r\nPraktikan dilarang mengganggu praktikan lain yang sedang melaksanakan praktikum.\r\n\r\nDilarang merokok, membawa makanan, minuman, senjata tajam, dan senjata api ke dalam ruangan praktikum.\r\n\r\nHandphone tidak diperbolehkan dibawa ke meja praktikum dan wajib diatur dalam mode senyap.\r\n\r\nFlashdisk atau media penyimpanan eksternal dilarang dibawa ke meja praktikum tanpa seizin Dosen atau Asisten.\r\n\r\nDilarang membawa, mengambil, serta memindahkan perangkat praktikum tanpa instruksi dari pengawas.', NULL, 0, '2026-01-16 15:29:20', '2026-01-17 00:47:08', 'Larangan Umum', 'list');

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
(12, 'Melanggar Aturan', 'Praktikan tidak mematuhi dan mentaati aturan praktikum maka tidak\r\ndiperkenankan mengikuti praktikum.', NULL, 2, '2026-01-10 14:01:35', '2026-01-17 00:50:56', 'plain'),
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
(2, 'Pemutakhiran Data Aset Laboratorium', 'ri-file-list-3-line', 'icon-blue', 'pemutakhiran_data_aset_laboratorium_1768642542.pdf', 'Prosedur standar untuk memperbarui data inventaris dan aset laboratorium secara berkala setiap semester.', 0, '2026-01-10 07:28:36', '2026-01-17 09:35:42'),
(3, 'Penanganan Barang Rusak', 'ri-tools-line', 'icon-blue', 'penanganan_barang_rusak_1768642660.pdf', 'Tata cara pelaporan, pengecekan, dan tindak lanjut perbaikan atau penggantian perangkat laboratorium yang mengalami kerusakan.', 0, '2026-01-10 07:29:30', '2026-01-17 09:37:40'),
(4, 'Pengembalian Barang ke Fakultas', 'ri-share-forward-box-line', 'icon-blue', 'pengembalian_barang_ke_fakultas_1768642622.pdf', 'Alur administrasi pengembalian aset atau peminjaman barang inventaris kembali ke pihak Fakultas Ilmu Komputer.', 0, '2026-01-10 07:30:02', '2026-01-17 09:37:02'),
(5, 'Pemeliharaan Perangkat Laboratorium', 'ri-computer-line', 'icon-blue', 'pemeliharaan_perangkat_laboratorium_1768642596.pdf', 'Jadwal dan standar perawatan rutin (maintenance) untuk PC, jaringan, dan kelistrikan di dalam laboratorium.', 0, '2026-01-10 07:30:35', '2026-01-17 09:36:36'),
(6, 'Pemutakhiran Modul Praktikum', 'ri-book-open-line', 'icon-blue', 'pemutakhiran_modul_praktikum_1768642469.pdf', 'Mekanisme revisi dan update materi modul praktikum agar sesuai dengan perkembangan teknologi terbaru.', 0, '2026-01-10 07:31:54', '2026-01-17 09:34:29');

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
(5, '13020230100@student.umi.ac.id', '$2y$10$iVDxzPWLuukG7qlOxo1WFORcqFPKWD68EapvAP/Lf4ruJ6cqaMD.6', 'super_admin', '2026-01-17 16:11:39', '2026-01-09 22:25:18'),
(6, '13020230217@student.umi.ac.id', '$2y$10$khY/qBg0XaAE/x0apN54CuiPJeYczQVAvEPd4qJDK13hBEe7DIDh2', 'admin', '2026-01-16 23:00:47', '2026-01-09 22:26:27'),
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
-- Indeks untuk tabel `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id_modul`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  MODIFY `idGambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

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
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `id_modul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
