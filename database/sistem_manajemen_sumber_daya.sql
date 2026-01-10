-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jan 2026 pada 16.21
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
(2, 'Siti Nurhaliza bapak', '2023', 'Divisi Jaringan', NULL, 'https://placehold.co/300x300/667eea/white?text=Siti', 'Awal yang baik untuk karir di dunia telekomunikasi. Terima kasih atas bimbingan selama menjadi asisten.', 'Cisco, Networking, Linux', 'siti@email.com', '2025-12-15 02:28:26', '2025-12-19 11:05:25', 0),
(3, 'Ahmad Pratama', '2022', 'Divisi Multimedia', '', 'https://placehold.co/300x300/667eea/white?text=Ahmad', 'Laboratorium membentuk kreativitas saya dan membuat saya percaya bahwa desain itu penting.', 'Figma, Adobe XD, UI/UX Design, Web Design', 'ahmad@email.com', '2025-12-15 02:28:26', '2026-01-09 01:16:37', 1),
(4, 'Dwi Putri Lestari', '2022', 'Koordinator Lab', NULL, 'https://placehold.co/300x300/667eea/white?text=Dwi', 'Bangga bisa melayani negara dengan keahlian yang didapat dari laboratorium. Semoga lab terus berkembang.', 'Public Policy, IT Governance, Java', 'dwi@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26', 0),
(5, 'Budi Santoso', '2021', 'Divisi Database', NULL, 'https://placehold.co/300x300/667eea/white?text=Budi', 'Belajar banyak tentang data dan database management. Menjadi asisten adalah keputusan terbaik saya.', 'SQL, Python, Data Analysis, PostgreSQL', 'budi@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26', 0),
(6, 'Eka Sulistyaningrum', '2021', 'Divisi Web', NULL, 'https://placehold.co/300x300/667eea/white?text=Eka', 'Pengalaman di lab mengajarkan saya tentang deadline management dan code quality yang baik.', 'JavaScript, React, Vue, HTML/CSS', 'eka@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26', 0),
(7, 'Fajar Wijaya', '2020', 'Divisi Sistem', NULL, 'https://placehold.co/300x300/667eea/white?text=Fajar', 'Infrastruktur yang kami bangun di lab menjadi fondasi pengetahuan saya tentang system administration.', 'AWS, Docker, Kubernetes, Linux', 'fajar@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26', 0),
(8, 'Hani Khoirunisa', '2020', 'Divisi Multimedia', NULL, 'https://placehold.co/300x300/667eea/white?text=Hani', 'Lab memberikan saya portofolio yang solid untuk masuk ke industri kreatif.', 'After Effects, Premiere Pro, Animation, UI Design', 'hani@email.com', '2025-12-15 02:28:26', '2025-12-15 02:28:26', 0),
(9, 'Rizwan Ardiyan', '2023', 'Koordinator Lab', '', 'alumni/alumni_1767890039_1626.png', 'Pengalaman yang luar biasa menjadi asisten di lab ini. Banyak ilmu yang saya dapatkan terutama dalam teamwork dan kepemimpinan.', 'PHP, Laravel, React, MySQL, Docker', 'rizwan@email.com', '2025-12-19 01:20:54', '2026-01-09 00:33:59', 0),
(10, 'Rizwan Alfian', '2023', 'Koordinator Lab', '', 'alumni/alumni_rizwan-alfian_1767890758_194.jpeg', 'Pengalaman yang luar biasa menjadi asisten di lab ini. Banyak ilmu yang saya dapatkan terutama dalam teamwork dan kepemimpinan.', 'PHP, Laravel, React, MySQL, Docker', 'rizwan@email.com', '2025-12-20 15:43:11', '2026-01-09 00:45:58', 0),
(11, 'Siti Nurhaliza', '2023', 'Divisi Jaringan', NULL, 'https://placehold.co/300x300/667eea/white?text=Siti', 'Awal yang baik untuk karir di dunia telekomunikasi. Terima kasih atas bimbingan selama menjadi asisten.', 'Cisco, Networking, Linux', 'siti@email.com', '2025-12-20 15:43:11', '2025-12-20 15:43:11', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `asisten`
--

CREATE TABLE `asisten` (
  `idAsisten` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
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

INSERT INTO `asisten` (`idAsisten`, `nama`, `jurusan`, `bio`, `skills`, `email`, `foto`, `statusAktif`, `isKoordinator`, `urutanTampilan`) VALUES
(9, 'M RIZWAN', 'Teknik Informatika', '', NULL, 'rizwan@example.com', 'asisten/asisten_1767890003_4149.png', 'Asisten', 1, 0),
(13, 'Nahwa Kaka Saputra Anggareksa', 'Teknik Informatika', 'Halo saya nahwa, berasal dari negeri sebrang yaitu Gowa', '[\"Design Grafis\",\"UI\\/UX\"]', 'nahwakaka@example.com', 'asisten/asisten_nahwa-kaka-saputra-anggareksa_1767965225_226.jpg', 'Asisten', 0, 2);

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
(61, 8, 'A1,A2,A3', 7, 'Senin', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Nur Fuad', 'TI_MICRO-5', NULL, 'Aktif'),
(62, 9, 'A3', 19, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'TI_SD-3', NULL, 'Aktif'),
(63, 9, 'A4', 20, 'Senin', '07:00:00', '09:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Sitti Lutfia', 'TI_SD-4', NULL, 'Aktif'),
(64, 10, 'A1', 21, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Thalita Sherly Putri Jasmin', 'SI_PBO-1', NULL, 'Aktif'),
(65, 10, 'B1', 17, 'Senin', '09:40:00', '12:10:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Tazkirah Amaliah', 'SI_PBO-2', NULL, 'Aktif'),
(66, 8, 'A3', 7, 'Senin', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Maharani Safwa Andini', 'Farid Wajdi Mufti', 'TI_MICRO-3', NULL, 'Aktif'),
(67, 11, 'A7', 19, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_BD2-7', NULL, 'Aktif'),
(68, 11, 'A8', 20, 'Senin', '13:00:00', '15:30:00', 'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.', 'Maharani Safwa Andini', 'Andi Rifqi Aunur Rahman', 'TI_BD2-8', NULL, 'Aktif'),
(69, 8, 'A5', 7, 'Senin', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_MICRO-6', NULL, 'Aktif'),
(70, 9, 'B2', 17, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Farid Wajdi Mufti', 'Aan Maulana Sampe', 'TI_SD-10', NULL, 'Aktif'),
(71, 9, 'B1', 21, 'Senin', '13:00:00', '15:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Dewi Ernita Rahma', 'Andi Ahsan Ashuri', 'TI_SD-9', NULL, 'Aktif'),
(72, 8, 'A6', 7, 'Senin', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Raihan Nur Rizqillah', 'TI_MICRO-7', NULL, 'Aktif'),
(73, 9, 'A1', 21, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'M. Rizwan', 'TI_SD-1', NULL, 'Aktif'),
(74, 9, 'A2', 17, 'Senin', '15:40:00', '18:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Wahyu Kadri Rahmat Suat', 'Maharani Safwa Andini', 'TI_SD-2', NULL, 'Aktif'),
(75, 11, 'A3', 19, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Sitti Lutfia', 'TI_BD2-3', NULL, 'Aktif'),
(76, 11, 'A5', 20, 'Selasa', '07:00:00', '09:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Farah Tsabitaputri Az Zahra', 'TI_BD2-5', NULL, 'Aktif'),
(77, 13, 'A1', 21, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'SI_PTI-1', NULL, 'Aktif'),
(78, 13, 'B1', 17, 'Selasa', '07:00:00', '10:20:00', 'Dr. Ir. Dolly Indra, S.Kom.,M.MSi.,MTA.', 'Ahmad Mufli Ramadhan', 'Muh. Fatwah Fajriansyah M.', 'SI_PTI-2', NULL, 'Aktif'),
(79, 8, 'B2', 7, 'Selasa', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Muhammad Nur Fuad', 'TI_MICRO-11', NULL, 'Aktif'),
(80, 14, 'B1', 19, 'Selasa', '09:40:00', '12:10:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Aan Maulana Sampe', 'SI_JARKOM-2', NULL, 'Aktif'),
(81, 12, 'A3', 21, 'Selasa', '10:30:00', '14:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-3', NULL, 'Aktif'),
(82, 14, 'A1', 19, 'Selasa', '13:00:00', '15:30:00', 'Fahmi, S.Kom., M.T.', 'Muhammad Alif Maulana. R', 'Ichwal', 'SI_JARKOM-1', NULL, 'Aktif'),
(83, 11, 'A1', 21, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_BD2-1', NULL, 'Aktif'),
(84, 11, 'A2', 17, 'Selasa', '15:40:00', '18:10:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Rizqi Ananda Jalil', 'TI_BD2-2', NULL, 'Aktif'),
(85, 8, 'B1', 7, 'Selasa', '15:40:00', '18:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Muhammad Rafli', 'TI_MICRO-10', NULL, 'Aktif'),
(86, 9, 'B3', 19, 'Sabtu', '07:00:00', '09:30:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-11', NULL, 'Aktif'),
(87, 12, 'A2', 21, 'Sabtu', '07:00:00', '10:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Dewi Ernita Rahma', 'Siti Safira Tawetubun', 'TI_ALPRO-2', NULL, 'Aktif'),
(88, 9, 'B4', 19, 'Sabtu', '09:40:00', '12:10:00', 'Nurul Fadhillah, S.Kom., M.Kom', 'Wahyu Kadri Rahmat Suat', 'Muhammad Rifky Saputra Scania', 'TI_SD-12', NULL, 'Aktif'),
(89, 12, 'A4', 21, 'Sabtu', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nahwa Kaka Saputra Anggareksa', 'TI_ALPRO-4', NULL, 'Aktif'),
(90, 11, 'A4', 19, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Julisa', 'Andi Ikhlas Mallomo', 'TI_BD2-4', NULL, 'Aktif'),
(91, 11, 'A6', 20, 'Sabtu', '13:00:00', '15:30:00', 'Amaliah Faradibah, S.Kom.,M.Kom.,MTA.', 'Dewi Ernita Rahma', 'Thalita Sherly Putri Jasmin', 'TI_BD2-6', NULL, 'Aktif'),
(92, 8, 'A5,A7,B1,B2,B3', 7, 'Sabtu', '13:00:00', '15:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Muhammad Rafli', 'TI_MICRO-13', NULL, 'Aktif'),
(93, 12, 'B1', 21, 'Sabtu', '14:30:00', '18:20:00', 'Ramdaniah, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Ichwal', 'TI_ALPRO-5', NULL, 'Aktif'),
(94, 8, 'B3', 7, 'Rabu', '07:00:00', '09:30:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Farid Wajdi Mufti', 'Muhammad Rifky Saputra Scania', 'TI_MICRO-12', NULL, 'Aktif'),
(95, 11, 'B4', 20, 'Rabu', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Dewi Ernita Rahma', 'Muh. Fatwah Fajriansyah M.', 'TI_BD2-12', NULL, 'Aktif'),
(96, 8, 'A7', 7, 'Rabu', '09:40:00', '12:10:00', 'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-8', NULL, 'Aktif'),
(97, 9, 'A7', 19, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muhammad Rifky Saputra Scania', 'TI_SD-7', NULL, 'Aktif'),
(98, 9, 'A8', 20, 'Rabu', '09:40:00', '12:10:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Muhammad Alif Maulana. R', 'Nahwa Kaka Saputra Anggareksa', 'TI_SD-8', NULL, 'Aktif'),
(99, 15, 'A1', 21, 'Rabu', '10:30:00', '14:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Sitti Nurhalimah', 'TI_PP-1', NULL, 'Aktif'),
(100, 9, 'A5', 19, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Ahmad Mufli Ramadhan', 'Zaki Falihin Ayyubi', 'TI_SD-5', NULL, 'Aktif'),
(101, 9, 'A6', 20, 'Rabu', '13:00:00', '15:30:00', 'Syariful Mujaddid, S.Kom.,M.T.', 'Tazkirah Amaliah', 'Muh. Fatwah Fajriansyah M.', 'TI_SD-6', NULL, 'Aktif'),
(102, 8, 'A8', 7, 'Rabu', '13:00:00', '15:30:00', 'Tasrif Hasanuddin, S.T., M.Cs.', 'Maharani Safwa Andini', 'Raihan Nur Rizqillah', 'TI_MICRO-9', NULL, 'Aktif'),
(103, 15, 'A2', 21, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_PP-2', NULL, 'Aktif'),
(104, 15, 'A4', 17, 'Rabu', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Muhammad Alif Maulana. R', 'Andi Ikhlas Mallomo', 'TI_PP-4', NULL, 'Aktif'),
(105, 11, 'B2', 17, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Farid Wajdi Mufti', 'Rizqi Ananda Jalil', 'TI_BD2-10', NULL, 'Aktif'),
(106, 11, 'B1', 21, 'Kamis', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Andi Rifqi Aunur Rahman', 'TI_BD2-9', NULL, 'Aktif'),
(107, 16, 'C2', 19, 'Kamis', '07:00:00', '09:30:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Muhammad Nur Fuad', 'TI_MOBILE-1', NULL, 'Aktif'),
(108, 12, 'B2', 21, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Dewi Ernita Rahma', 'M. Rizwan', 'TI_ALPRO-6', NULL, 'Aktif'),
(109, 12, 'B3', 17, 'Kamis', '10:30:00', '14:20:00', 'Siska Anraeni, S.Kom.,M.T.,MCF.', 'Berlian Septiani, S.Kom., MCF', 'Hendrawan', 'TI_ALPRO-7', NULL, 'Aktif'),
(110, 15, 'A3', 21, 'Kamis', '14:30:00', '18:20:00', 'Lutfi Budi Ilmawan, S.Kom.,M.Cs.,MTA.', 'Tazkirah Amaliah', 'Nurfajri Mukmin Saputra', 'TI_PP-3', NULL, 'Aktif'),
(111, 11, 'B3', 20, 'Jumat', '07:00:00', '09:30:00', 'Ir. Dedy Atmajaya, S.Kom.,M.Eng.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_BD2-11', NULL, 'Aktif'),
(112, 8, 'A1', 7, 'Jumat', '07:00:00', '09:30:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Andi Ahsan Ashuri', 'TI_MICRO-1', NULL, 'Aktif'),
(113, 12, 'A1', 21, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Andi Rifqi Aunur Rahman', 'SI_ALPRO-1', NULL, 'Aktif'),
(114, 12, 'B1', 17, 'Jumat', '07:00:00', '10:20:00', 'Ir. St. Hajrah Mansyur, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'SI_ALPRO-2', NULL, 'Aktif'),
(115, 8, 'A2', 7, 'Jumat', '09:40:00', '12:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Muhammad Alif Maulana. R', 'Zaki Falihin Ayyubi', 'TI_MICRO-2', NULL, 'Aktif'),
(116, 15, 'B2', 19, 'Jumat', '10:30:00', '14:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Berlian Septiani, S.Kom., MCF', 'Nurfajri Mukmin Saputra', 'TI_PP-6', NULL, 'Aktif'),
(117, 12, 'A1', 21, 'Jumat', '10:30:00', '14:20:00', 'Suwito Pomalingo, S.Kom.,M.Kom.,MTA.', 'Wahyu Kadri Rahmat Suat', 'Julisa', 'TI_ALPRO-1', NULL, 'Aktif'),
(118, 15, 'B1', 21, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Julisa', 'Firli Anastasya Hafid', 'TI_PP-5', NULL, 'Aktif'),
(119, 15, 'B3', 17, 'Jumat', '14:30:00', '18:20:00', 'Ir. Huzain Azis, S.Kom.,M.Cs.,MTA.', 'Ahmad Mufli Ramadhan', 'Laode Muhammad Dhaifan Kasyfillah', 'TI_PP-7', NULL, 'Aktif'),
(120, 8, 'A4', 7, 'Jumat', '15:40:00', '18:10:00', 'Muhammad Arfah Asis, S.Kom., M.T.,MTA.', 'Farid Wajdi Mufti', 'Farah Tsabitaputri Az Zahra', 'TI_MICRO-4', NULL, 'Aktif');

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
(7, 'Microcontroller', 'Laboratorium', NULL, 'Tempat untuk belajar Microcontroller', 'laboratorium/lab_microcontroller_1767891501_778.jpg', 30, 25, 'Gedung Fakultas Ilmu Komputer lantai 2', 25, 'Inter core i7', '16GB RAM', '256GB SSD', 'NVDIA RTX 5060', '24inch ', 'Cisco', 'AC Central', 'M. Rizwan', 'koordinator_1766067968_69440f0027653.png'),
(14, 'Ruangan Riset 1', 'Riset', NULL, 'fda', 'lab_1766758916_1873.jpg', 0, NULL, NULL, 0, '', '', '', '', '', '', '', NULL, NULL),
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
(10, 14, 'lab_1766758916_1873.jpg', NULL, 1, 0, '2025-12-26 22:21:56'),
(11, 14, 'lab_1766758916_7564.jpg', NULL, 0, 1, '2025-12-26 22:21:56'),
(12, 17, 'lab_1767285321_3208.jpg', NULL, 1, 0, '2026-01-02 00:35:21'),
(13, 17, 'lab_1767285321_5993.jpg', NULL, 0, 1, '2026-01-02 00:35:21'),
(14, 17, 'lab_1767285321_7083.jpg', NULL, 0, 2, '2026-01-02 00:35:21'),
(15, 17, 'lab_1767285997_4783.jpg', NULL, 1, 0, '2026-01-02 00:46:37'),
(16, 17, 'lab_1767285997_3584.jpg', NULL, 0, 1, '2026-01-02 00:46:37'),
(17, 17, 'lab_1767285997_5317.jpg', NULL, 0, 2, '2026-01-02 00:46:37'),
(18, 18, 'lab_1767439910_3095.jpg', NULL, 1, 0, '2026-01-03 19:31:50'),
(20, 7, 'laboratorium/lab_microcontroller_1767891501_778.jpg', NULL, 1, 0, '2026-01-09 00:58:21');

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
(1, 'Ir. Abdul Rachman Manga\', S.Kom., M.T., MTA., MCF', '839459', 'Kepala Laboratorium Jaringan Dan Pemrograman', 'admin@example.com', 'manajemen/manajemen_1767889920_9979.jpg'),
(5, 'Ir. Huzain Azis, S.Kom., M.Cs. MTA', '', 'Kepala Laboratorium Komputasi Dasar', 'admin@example.com', 'manajemen/manajemen_1767889969_4980.jpg'),
(6, 'Herdianti, S.Si., M.Eng., MTA.', NULL, 'Kepala Laboratorium Riset', NULL, 'manajemen_1767600880_4656.JPG'),
(7, 'Fatimah AR. Tuasamu, S.Kom., MTA, MOS', NULL, 'Laboran', NULL, 'manajemen_1767600916_8750.JPG');

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
  `urutan` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kategori` varchar(100) DEFAULT 'umum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peraturan_lab`
--

INSERT INTO `peraturan_lab` (`id`, `judul`, `deskripsi`, `urutan`, `created_at`, `updated_at`, `kategori`) VALUES
(1, 'Tata Tertib Umum Laboratorium', 'Peraturan dasar yang harus dipatuhi oleh semua pengguna laboratorium teknologi informasi', 1, '2025-12-18 23:53:18', '2026-01-03 00:23:49', 'Larangan Umum'),
(2, 'Penggunaan Peralatan Lab', 'Ketentuan penggunaan komputer, jaringan, dan peralatan elektronik di laboratorium', 2, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'larangan-umum'),
(3, 'Jadwal dan Reservasi Ruangan', 'Prosedur pemesanan dan penggunaan ruang laboratorium untuk kegiatan praktikum', 3, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'kehadiran-akademik'),
(4, 'Keamanan dan Kebersihan', 'Aturan menjaga keamanan data, perangkat, dan kebersihan lingkungan laboratorium', 4, '2025-12-18 23:53:18', '2026-01-03 00:10:41', 'larangan-umum'),
(5, 'Sanksi dan Pelanggaran', 'Konsekuensi yang berlaku bagi mahasiswa yang melanggar tata tertib laboratorium', 5, '2025-12-18 23:53:18', '2026-01-03 00:11:18', 'larangan-umum'),
(6, 'test', 'halo tkajgkla\r\nketuhanan yang maha esa\r\nkemanusian yang adil dan beradap\r\npersatuan indonesia\r\nkerakyatan yang di pimpin oleh hikmat dan kebijaksanaan', 0, '2026-01-07 03:04:42', '2026-01-07 03:04:42', 'Kehadiran & Akademik');

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
(5, '13020230100@student.umi.ac.id', '$2y$10$iVDxzPWLuukG7qlOxo1WFORcqFPKWD68EapvAP/Lf4ruJ6cqaMD.6', 'super_admin', '2026-01-09 23:17:53', '2026-01-09 22:25:18'),
(6, '13020230217@student.umi.ac.id', '$2y$10$khY/qBg0XaAE/x0apN54CuiPJeYczQVAvEPd4qJDK13hBEe7DIDh2', 'admin', '2026-01-09 22:32:12', '2026-01-09 22:26:27'),
(7, '13020230187@student.umi.ac.id', '$2y$10$FhUh8hCCg6noMBFw.YEsLuVuzUa.4jSNDVJgk3Q6oCJBD0k058ugi', 'admin', '2026-01-09 22:33:44', '2026-01-09 22:28:15'),
(8, 'superadmin@student.umi.ac.id', '$2y$10$UzYX.F.BjuMC8s1nsl/1pe0l9j6tO1Go2hIaxAwvSo0nOw.PCA7WG', 'super_admin', '2026-01-09 22:36:19', '2026-01-09 22:35:55');

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
  MODIFY `idAsisten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `idJadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT untuk tabel `laboratorium`
--
ALTER TABLE `laboratorium`
  MODIFY `idLaboratorium` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `laboratorium_gambar`
--
ALTER TABLE `laboratorium_gambar`
  MODIFY `idGambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
