-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jan 2026 pada 20.19
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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alumni`
--

INSERT INTO `alumni` (`id`, `nama`, `angkatan`, `divisi`, `mata_kuliah`, `foto`, `kesan_pesan`, `keahlian`, `email`, `created_at`, `updated_at`) VALUES
(2, 'Siti Nurhaliza bapak', '2023', 'Divisi Jaringan', NULL, 'https://placehold.co/300x300/667eea/white?text=Siti', 'Awal yang baik untuk karir di dunia telekomunikasi. Terima kasih atas bimbingan selama menjadi asisten.', 'Cisco, Networking, Linux', 'siti@email.com', '2025-12-15 02:28:26', '2025-12-19 11:05:25'),
(3, 'Ahmad Pratama', '2022', 'Divisi Multimedia', NULL, 'https://placehold.co/300x300/667eea/white?text=Ahmad', 'Laboratorium membentuk kreativitas saya dan membuat saya percaya bahwa desain itu penting.', 'Figma, Adobe XD, UI/UX Design, Web Design', 'ahmad@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(4, 'Dwi Putri Lestari', '2022', 'Koordinator Lab', NULL, 'https://placehold.co/300x300/667eea/white?text=Dwi', 'Bangga bisa melayani negara dengan keahlian yang didapat dari laboratorium. Semoga lab terus berkembang.', 'Public Policy, IT Governance, Java', 'dwi@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(5, 'Budi Santoso', '2021', 'Divisi Database', NULL, 'https://placehold.co/300x300/667eea/white?text=Budi', 'Belajar banyak tentang data dan database management. Menjadi asisten adalah keputusan terbaik saya.', 'SQL, Python, Data Analysis, PostgreSQL', 'budi@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(6, 'Eka Sulistyaningrum', '2021', 'Divisi Web', NULL, 'https://placehold.co/300x300/667eea/white?text=Eka', 'Pengalaman di lab mengajarkan saya tentang deadline management dan code quality yang baik.', 'JavaScript, React, Vue, HTML/CSS', 'eka@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(7, 'Fajar Wijaya', '2020', 'Divisi Sistem', NULL, 'https://placehold.co/300x300/667eea/white?text=Fajar', 'Infrastruktur yang kami bangun di lab menjadi fondasi pengetahuan saya tentang system administration.', 'AWS, Docker, Kubernetes, Linux', 'fajar@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(8, 'Hani Khoirunisa', '2020', 'Divisi Multimedia', NULL, 'https://placehold.co/300x300/667eea/white?text=Hani', 'Lab memberikan saya portofolio yang solid untuk masuk ke industri kreatif.', 'After Effects, Premiere Pro, Animation, UI Design', 'hani@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26'),
(9, 'Rizwan Ardiyan', '2023', 'Koordinator Lab', NULL, 'alumni_1766078894_8729.png', 'Pengalaman yang luar biasa menjadi asisten di lab ini. Banyak ilmu yang saya dapatkan terutama dalam teamwork dan kepemimpinan.', 'PHP, Laravel, React, MySQL, Docker', 'rizwan@email.com', '2025-12-19 01:20:54', '2025-12-19 01:28:14'),
(10, 'Rizwan Alfian', '2023', 'Koordinator Lab', NULL, 'https://placehold.co/300x300/667eea/white?text=Rizwan', 'Pengalaman yang luar biasa menjadi asisten di lab ini. Banyak ilmu yang saya dapatkan terutama dalam teamwork dan kepemimpinan.', 'PHP, Laravel, React, MySQL, Docker', 'rizwan@email.com', '2025-12-20 15:43:11', '2025-12-20 15:43:11'),
(11, 'Siti Nurhaliza', '2023', 'Divisi Jaringan', NULL, 'https://placehold.co/300x300/667eea/white?text=Siti', 'Awal yang baik untuk karir di dunia telekomunikasi. Terima kasih atas bimbingan selama menjadi asisten.', 'Cisco, Networking, Linux', 'siti@email.com', '2025-12-20 15:43:11', '2025-12-20 15:43:11');

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

INSERT INTO `asisten` (`idAsisten`, `nama`, `jurusan`, `jabatan`, `kategori`, `lab`, `spesialisasi`, `bio`, `skills`, `email`, `foto`, `statusAktif`, `isKoordinator`) VALUES
(2, 'Budi Santoso', 'Teknik Informatika', NULL, NULL, '', '', '', '', 'budi@mail.com', NULL, 'CA', 0),
(3, 'Siti Nurhaliza', 'Sistem Informasi', NULL, NULL, NULL, NULL, '', '', 'siti@mail.com', NULL, 'Asisten', 0),
(4, 'Ahmad Wijaya', 'Teknik Komputer', NULL, NULL, NULL, NULL, NULL, NULL, 'ahmad@mail.com', NULL, '1', 0),
(5, 'Rina Puspita', 'Informatika', NULL, NULL, NULL, NULL, NULL, NULL, 'rina@mail.com', NULL, '1', 0),
(9, 'M RIZWAN', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'halo saya rizwan', '[\"PHP\",\"Laravel\"]', 'rizwan@example.com', 'asisten_1766495391_7606.png', 'Asisten', 1),
(10, 'Abbas Asis', 'Teknik Informatika', NULL, NULL, NULL, NULL, '', '[\"Jago tidur\"]', 'abbas@contoh.com', NULL, 'CA', 0),
(11, 'Savier', 'Teknik Informatika', NULL, NULL, NULL, NULL, 'Saya adalah calon asisten', '[\"PHP\",\"Laravel\"]', 'Savier@umi.ac.id', 'asisten_1767282345_8755.jpg', 'CA', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `format_penulisan`
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
-- Dumping data untuk tabel `format_penulisan`
--

INSERT INTO `format_penulisan` (`id_format`, `judul`, `deskripsi`, `file`, `kategori`, `link_external`, `tanggal_update`, `icon`, `warna`, `urutan`) VALUES
(11, 'Lembar Kerja & Margin', 'Kertas: A4 (21,0 x 29,7 cm), 70 gram.\r\nMargin: Kiri 4cm, Atas 4cm, Kanan 3cm, Bawah 3cm.\r\nWajib menggunakan Watermark logo ICLabs.', NULL, 'pedoman', '', '2026-01-07', 'ri-article-line', 'icon-blue', 3),
(12, 'Teknik Penulisan', 'Wajib tulis tangan dengan pulpen warna HITAM.\r\nSetiap soal dari modul wajib ditulis ulang.\r\nJawaban ditulis tepat di bawah soal terkait.\r\nGambar harus jelas dan pas di dalam margin.', NULL, 'pedoman', '', '2026-01-07', 'ri-pencil-line', 'icon-pink', 2),
(14, 'Buku Panduan Lengkap', '', '1767721899_2e3c1187.pdf', 'unduhan', '', '2026-01-07', '', 'icon-blue', 1),
(15, 'Watermark ICLabs', '', '1767721938_534c43bf.png', 'unduhan', '', '2026-01-07', '', 'icon-blue', 2),
(16, 'Logo Resmi UMI', '', '1767721596_24f2a8ee.jpeg', 'unduhan', '', '2026-01-07', '', 'icon-blue', 3),
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
(1, 8, 'A1,A2,A3', 7, 'Senin', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Nur Fuad', 'TI_MICRO-5', NULL, 'Aktif'),
(2, 9, 'A3', 19, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'TI_SD-3', NULL, 'Aktif'),
(3, 9, 'A4', 20, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Sitti Lutfia', 'TI_SD-4', NULL, 'Aktif'),
(4, 10, 'A1', 21, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Thalita Sherly Putri Jasmin', 'SI_PBO-1', NULL, 'Aktif'),
(5, 10, 'B1', 17, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Tazkirah Amaliah', 'SI_PBO-2', NULL, 'Aktif'),
(6, 8, 'A3', 7, 'Senin', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Maharani Safwa Andini', 'Farid Wajdi Mufti', 'TI_MICRO-3', NULL, 'Aktif'),
(7, 11, 'A7', 19, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_BD2-7', NULL, 'Aktif'),
(8, 11, 'A8', 20, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Maharani Safwa Andini', 'Andi Rifqi Aunur Rahman', 'TI_BD2-8', NULL, 'Aktif'),
(9, 8, 'A5', 7, 'Senin', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_MICRO-6', NULL, 'Aktif'),
(10, 9, 'B2', 17, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Farid Wajdi Mufti', 'Aan Maulana Sampe', 'TI_SD-10', NULL, 'Aktif'),
(11, 9, 'B1', 21, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Dewi Ernita Rahma', 'Andi Ahsan Ashuri', 'TI_SD-9', NULL, 'Aktif'),
(12, 8, 'A6', 7, 'Senin', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Raihan Nur Rizqillah', 'TI_MICRO-7', NULL, 'Aktif'),
(13, 9, 'A1', 21, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_SD-1', NULL, 'Aktif'),
(14, 9, 'A2', 17, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Wahyu Kadri Rahmat Suat', 'Maharani Safwa Andini', 'TI_SD-2', NULL, 'Aktif'),
(15, 11, 'A3', 19, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Sitti Lutfia', 'TI_BD2-3', NULL, 'Aktif'),
(16, 11, 'A5', 20, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Farah Tsabitaputri Az Zahra', 'TI_BD2-5', NULL, 'Aktif'),
(17, 13, 'A1', 21, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'SI_PTI-1', NULL, 'Aktif'),
(18, 13, 'B1', 17, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Ahmad Mufli Ramadhan', 'Muh. Fatwah Fajriansyah M.', 'SI_PTI-2', NULL, 'Aktif'),
(19, 8, 'B2', 7, 'Selasa', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Muhammad Nur Fuad', 'TI_MICRO-11', NULL, 'Aktif'),
(20, 14, 'B1', 19, 'Selasa', '09:40:00', '12:10:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Aan Maulana Sampe', 'SI_JARKOM-2', NULL, 'Aktif'),
(21, 12, 'A3', 21, 'Selasa', '10:30:00', '14:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-3', NULL, 'Aktif'),
(22, 14, 'A1', 19, 'Selasa', '13:00:00', '15:30:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'SI_JARKOM-1', NULL, 'Aktif'),
(23, 11, 'A1', 21, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_BD2-1', NULL, 'Aktif'),
(24, 11, 'A2', 17, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Rizqi Ananda Jalil', 'TI_BD2-2', NULL, 'Aktif'),
(25, 8, 'B1', 7, 'Selasa', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Muhammad Rafli', 'TI_MICRO-10', NULL, 'Aktif'),
(26, 9, 'B3', 19, 'Sabtu', '07:00:00', '09:30:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-11', NULL, 'Aktif'),
(27, 12, 'A2', 21, 'Sabtu', '07:00:00', '10:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-2', NULL, 'Aktif'),
(28, 9, 'B4', 19, 'Sabtu', '09:40:00', '12:10:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Wahyu Kadri Rahmat Suat', 'Muhammad Rifky Saputra Scania', 'TI_SD-12', NULL, 'Aktif'),
(29, 12, 'A4', 21, 'Sabtu', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nahwa Kaka Saputra Anggareksa', 'TI_ALPRO-4', NULL, 'Aktif'),
(30, 11, 'A4', 19, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Andi Ikhlas Mallomo', 'TI_BD2-4', NULL, 'Aktif'),
(31, 11, 'A6', 20, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Thalita Sherly Putri Jasmin', 'TI_BD2-6', NULL, 'Aktif'),
(32, 8, 'A5,A7,B1,B2,B3', 7, 'Sabtu', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Muhammad Rafli', 'TI_MICRO-13', NULL, 'Aktif'),
(33, 12, 'B1', 21, 'Sabtu', '14:30:00', '18:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Ichwal', 'TI_ALPRO-5', NULL, 'Aktif'),
(34, 8, 'B3', 7, 'Rabu', '07:00:00', '09:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Rifky Saputra Scania', 'TI_MICRO-12', NULL, 'Aktif'),
(35, 11, 'B4', 20, 'Rabu', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Dewi Ernita Rahma', 'Muh. Fatwah Fajriansyah M.', 'TI_BD2-12', NULL, 'Aktif'),
(36, 8, 'A7', 7, 'Rabu', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-8', NULL, 'Aktif'),
(37, 9, 'A7', 19, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muhammad Rifky Saputra Scania', 'TI_SD-7', NULL, 'Aktif'),
(38, 9, 'A8', 20, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-8', NULL, 'Aktif'),
(39, 15, 'A1', 21, 'Rabu', '10:30:00', '14:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_PP-1', NULL, 'Aktif'),
(40, 9, 'A5', 19, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Zaki Falihin Ayyubi', 'TI_SD-5', NULL, 'Aktif'),
(41, 9, 'A6', 20, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muh. Fatwah Fajriansyah M.', 'TI_SD-6', NULL, 'Aktif'),
(42, 8, 'A8', 7, 'Rabu', '13:00:00', '15:30:00', 'Tasrif Hasanuddin, S.T., M.Cs.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-9', NULL, 'Aktif'),
(43, 15, 'A2', 21, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_PP-2', NULL, 'Aktif'),
(44, 15, 'A4', 17, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Muhammad Alif Maulana. R', 'Andi Ikhlas Mallomo', 'TI_PP-4', NULL, 'Aktif'),
(45, 11, 'B2', 17, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Farid Wajdi Mufti', 'Rizqi Ananda Jalil', 'TI_BD2-10', NULL, 'Aktif'),
(46, 11, 'B1', 21, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Andi Rifqi Aunur Rahman', 'TI_BD2-9', NULL, 'Aktif'),
(47, 16, 'C2', 19, 'Kamis', '07:00:00', '09:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Muhammad Nur Fuad', 'TI_MOBILE-1', NULL, 'Aktif'),
(48, 12, 'B2', 21, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Dewi Ernita Rahma', 'M. Rizwan', 'TI_ALPRO-6', NULL, 'Aktif'),
(49, 12, 'B3', 17, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_ALPRO-7', NULL, 'Aktif'),
(50, 15, 'A3', 21, 'Kamis', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'TI_PP-3', NULL, 'Aktif'),
(51, 11, 'B3', 20, 'Jumat', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_BD2-11', NULL, 'Aktif'),
(52, 8, 'A1', 7, 'Jumat', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Andi Ahsan Ashuri', 'TI_MICRO-1', NULL, 'Aktif'),
(53, 12, 'A1', 21, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Andi Rifqi Aunur Rahman', 'SI_ALPRO-1', NULL, 'Aktif'),
(54, 12, 'B1', 17, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'SI_ALPRO-2', NULL, 'Aktif'),
(55, 8, 'A2', 7, 'Jumat', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Muhammad Alif Maulana. R', 'Zaki Falihin Ayyubi', 'TI_MICRO-2', NULL, 'Aktif'),
(56, 15, 'B2', 19, 'Jumat', '10:30:00', '14:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nurfajri Mukmin Saputra', 'TI_PP-6', NULL, 'Aktif'),
(57, 12, 'A1', 21, 'Jumat', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Julisa', 'TI_ALPRO-1', NULL, 'Aktif'),
(58, 15, 'B1', 21, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_PP-5', NULL, 'Aktif'),
(59, 15, 'B3', 17, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_PP-7', NULL, 'Aktif'),
(60, 8, 'A4', 7, 'Jumat', '15:40:00', '18:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Farah Tsabitaputri Az Zahra', 'TI_MICRO-4', NULL, 'Aktif');

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
(7, 'Microcontroller', 'Laboratorium', NULL, 'Tempat untuk belajar Microcontroller', 'lab_1766758039_2354.jpg', 30, 25, 'Gedung Fakultas Ilmu Komputer lantai 2', 25, 'Inter core i7', '16GB RAM', '256GB SSD', 'NVDIA RTX 5060', '24inch ', 'Cisco', 'AC Central', 'M. Rizwan', 'koordinator_1766067968_69440f0027653.png'),
(14, 'Ruangan Riset 1', 'Riset', 2, 'fda', 'lab_1766758916_1873.jpg', 0, NULL, NULL, 0, '', '', '', '', '', '', '', NULL, NULL),
(17, 'IoT', 'Laboratorium', 9, 'Ruangan untuk melakukan pembelajaran IoT', 'lab_1767285997_4783.jpg', 24, NULL, NULL, 0, 'Inter core i7', '16GB RAM', '256GB SSD', 'NVDIA RTX 5060', '24inch ', 'Visual Studio Code, Xampp, Arduiono IDE', 'AC Central', NULL, NULL),
(18, 'Riset 2', 'Riset', NULL, 'Ruangan Riset 2 Laboratorium atau Ruangan Meeting', 'lab_1767439910_3095.jpg', 0, NULL, NULL, 0, '', '', '', '', '', '', '', NULL, NULL),
(19, 'Computer Vision', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'Data Science', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'Start Up', 'Laboratorium', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
(1, 7, 'lab_1766584749_5435.jpg', 'Gambar ruangan utama', 1, 0, '2025-12-26 20:47:37'),
(5, 7, 'lab_1766758039_2354.jpg', NULL, 1, 0, '2025-12-26 22:07:19'),
(6, 7, 'lab_1766758039_5470.jpg', NULL, 0, 1, '2025-12-26 22:07:19'),
(10, 14, 'lab_1766758916_1873.jpg', NULL, 1, 0, '2025-12-26 22:21:56'),
(11, 14, 'lab_1766758916_7564.jpg', NULL, 0, 1, '2025-12-26 22:21:56'),
(12, 17, 'lab_1767285321_3208.jpg', NULL, 1, 0, '2026-01-02 00:35:21'),
(13, 17, 'lab_1767285321_5993.jpg', NULL, 0, 1, '2026-01-02 00:35:21'),
(14, 17, 'lab_1767285321_7083.jpg', NULL, 0, 2, '2026-01-02 00:35:21'),
(15, 17, 'lab_1767285997_4783.jpg', NULL, 1, 0, '2026-01-02 00:46:37'),
(16, 17, 'lab_1767285997_3584.jpg', NULL, 0, 1, '2026-01-02 00:46:37'),
(17, 17, 'lab_1767285997_5317.jpg', NULL, 0, 2, '2026-01-02 00:46:37'),
(18, 18, 'lab_1767439910_3095.jpg', NULL, 1, 0, '2026-01-03 19:31:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `manajemen`
--

CREATE TABLE `manajemen` (
  `idManajemen` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,  `email` varchar(255) DEFAULT NULL,  `nidn` varchar(20) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `manajemen`
--

INSERT INTO `manajemen` (`idManajemen`, `nama`, `email`, `nidn`, `jabatan`, `foto`) VALUES
(1, 'Ir. Abdul Rachman Manga\', S.Kom., M.T., MTA., MCF', NULL, NULL, 'Kepala Laboratorium Jaringan Dan Pemrograman', 'manajemen_1767600768_7768.jpg'),
(5, 'Ir. Huzain Azis, S.Kom., M.Cs. MTA', NULL, NULL, 'Kepala Laboratorium Komputasi Dasar', 'manajemen_1767600806_3284.jpg'),
(6, 'Herdianti, S.Si., M.Eng., MTA.', NULL, NULL, 'Kepala Laboratorium Riset', 'manajemen_1767600880_4656.JPG'),
(7, 'Fatimah AR. Tuasamu, S.Kom., MTA, MOS', NULL, NULL, 'Laboran', 'manajemen_1767600916_8750.JPG');

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
  `kategori` varchar(100) DEFAULT 'umum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peraturan_lab`
--

INSERT INTO `peraturan_lab` (`id`, `judul`, `deskripsi`, `gambar`, `urutan`, `created_at`, `updated_at`, `kategori`) VALUES
(1, 'Tata Tertib Umum Laboratorium', 'Peraturan dasar yang harus dipatuhi oleh semua pengguna laboratorium teknologi informasi', NULL, 1, '2025-12-18 23:53:18', '2026-01-03 00:23:49', 'Larangan Umum'),
(2, 'Penggunaan Peralatan Lab', 'Ketentuan penggunaan komputer, jaringan, dan peralatan elektronik di laboratorium', NULL, 2, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'larangan-umum'),
(3, 'Jadwal dan Reservasi Ruangan', 'Prosedur pemesanan dan penggunaan ruang laboratorium untuk kegiatan praktikum', NULL, 3, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'kehadiran-akademik'),
(4, 'Keamanan dan Kebersihan', 'Aturan menjaga keamanan data, perangkat, dan kebersihan lingkungan laboratorium', NULL, 4, '2025-12-18 23:53:18', '2026-01-03 00:10:41', 'larangan-umum'),
(5, 'Sanksi dan Pelanggaran', 'Konsekuensi yang berlaku bagi mahasiswa yang melanggar tata tertib laboratorium', NULL, 5, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'larangan-umum'),
(6, 'test', 'halo tkajgkla\r\nketuhanan yang maha esa\r\nkemanusian yang adil dan beradap\r\npersatuan indonesia\r\nkerakyatan yang di pimpin oleh hikmat dan kebijaksanaan', NULL, 0, '2026-01-07 03:04:42', '2026-01-07 03:04:42', 'Kehadiran & Akademik');

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
(6, 'Test Update', 'Test update desc', 'sanksi_1765767514_3994.png', 0, '2025-12-15 10:16:08', '2025-12-15 10:58:34');

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
(2, '$2y$10$l710L0YPlhSBRxW23TpS..A8o9pbUd1RLcPLFY77Zm3', 'admin', 'admin', NULL, '2025-12-20 17:28:27'),
(4, 'admin', '$2y$10$9dZeOKeyCyGLbICQl4l2S.rhW9VQd7Tj5iqbdSe43yG1YKUv3Utey', 'admin', '2026-01-06 23:41:08', '2025-12-22 14:47:00');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `asisten`
--
ALTER TABLE `asisten`
  MODIFY `idAsisten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `idJadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `laboratorium`
--
ALTER TABLE `laboratorium`
  MODIFY `idLaboratorium` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  MODIFY `idGambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `sanksi_lab`
--
ALTER TABLE `sanksi_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
