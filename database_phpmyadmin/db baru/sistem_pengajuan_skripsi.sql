-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2026 at 01:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pengajuan_skripsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `arsip_skripsi`
--

CREATE TABLE `arsip_skripsi` (
  `id_arsip` int(11) NOT NULL,
  `npm` varchar(20) DEFAULT NULL,
  `nama_mahasiswa` varchar(100) DEFAULT NULL,
  `angkatan` varchar(10) DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `abstrak` text DEFAULT NULL,
  `kata_kunci` varchar(255) DEFAULT NULL,
  `id_topik` int(11) DEFAULT NULL,
  `nama_topik_arsip` varchar(100) DEFAULT NULL,
  `pembimbing_1` varchar(20) DEFAULT NULL,
  `pembimbing_2` varchar(20) DEFAULT NULL,
  `nama_pembimbing1_arsip` varchar(100) DEFAULT NULL,
  `nama_pembimbing2_arsip` varchar(100) DEFAULT NULL,
  `tahun_lulus` year(4) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `tgl_arsip` datetime DEFAULT NULL,
  `kategori_arsip` enum('alumni','lulus_tahun_ini') DEFAULT 'alumni',
  `nilai_skripsi` varchar(5) NOT NULL,
  `file_skripsi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `bidang_keahlian` varchar(100) DEFAULT NULL,
  `kuota_bimbingan` int(11) DEFAULT 5,
  `total_bimbingan` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `total_p1` int(11) DEFAULT 0,
  `total_p2` int(11) DEFAULT 0,
  `maks_p1` int(11) DEFAULT 5,
  `maks_p2` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`nidn`, `nama`, `bidang_keahlian`, `kuota_bimbingan`, `total_bimbingan`, `user_id`, `total_p1`, `total_p2`, `maks_p1`, `maks_p2`) VALUES
('100001', 'Dr. Ahmad Fauzi', 'Sistem Informasi', 10, 3, NULL, 2, 1, 5, 5),
('100002', 'Dr. Budi Santoso', 'Artificial Intelligence', 8, 8, NULL, 4, 4, 4, 4),
('100003', 'Dr. Siti Aminah', 'Data Mining', 12, 5, NULL, 3, 2, 6, 6),
('100004', 'Dr. Rina Kartika', 'Jaringan Komputer', 10, 2, NULL, 1, 1, 5, 5),
('100005', 'Dr. Andi Pratama', 'Keamanan Siber', 6, 6, NULL, 3, 3, 3, 3),
('100006', 'Dr. Dewi Lestari', 'Rekayasa Perangkat Lunak', 15, 4, NULL, 2, 2, 8, 7),
('100007', 'Dr. Muhammad Rizki', 'Internet of Things', 10, 1, NULL, 1, 0, 5, 5),
('100008', 'Dr. Yuni Astuti', 'Machine Learning', 8, 7, NULL, 4, 3, 4, 4),
('100009', 'Dr. Agus Setiawan', 'Basis Data', 12, 9, NULL, 5, 4, 6, 6),
('100010', 'Dr. Nur Hidayah', 'Sistem Pendukung Keputusan', 10, 0, NULL, 0, 0, 5, 5),
('D001', 'Dr. Budi Santoso', NULL, 5, 0, NULL, 0, 0, 5, 5),
('D002', 'Andi', NULL, 5, 0, NULL, 0, 0, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `logbook_bimbingan`
--

CREATE TABLE `logbook_bimbingan` (
  `id_logbook` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `npm` varchar(50) NOT NULL,
  `nidn_dosen` varchar(50) NOT NULL,
  `tanggal_bimbingan` date NOT NULL,
  `materi_bimbingan` text NOT NULL,
  `catatan_dosen` text DEFAULT NULL,
  `status_validasi` enum('Menunggu','Revisi','Disetujui') NOT NULL DEFAULT 'Menunggu',
  `created_at` datetime DEFAULT current_timestamp(),
  `file_review_url` varchar(255) DEFAULT NULL,
  `file_mahasiswa_url` varchar(255) DEFAULT NULL,
  `waktu_review` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `npm` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `angkatan` varchar(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`npm`, `nama`, `angkatan`, `user_id`) VALUES
('220001', 'Budi Santoso', '2022', NULL),
('220002', 'Siti Aminah', '2022', NULL),
('230001', 'Andi Saputra nawa', '2023', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembimbing`
--

CREATE TABLE `pembimbing` (
  `id` int(11) NOT NULL,
  `id_pengajuan` int(11) DEFAULT NULL,
  `pembimbing_1` varchar(20) DEFAULT NULL,
  `pembimbing_2` varchar(20) DEFAULT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `tgl_penetapan` datetime DEFAULT NULL,
  `status_bimbingan` varchar(20) DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pembimbing`
--

INSERT INTO `pembimbing` (`id`, `id_pengajuan`, `pembimbing_1`, `pembimbing_2`, `file_pdf`, `tgl_penetapan`, `status_bimbingan`) VALUES
(18, 999, 'D001', 'D002', 'dummy_sk.pdf', '2026-06-16 17:35:25', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id_pengajuan` int(11) NOT NULL,
  `npm` varchar(20) DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `abstrak` text DEFAULT NULL,
  `id_topik` int(11) DEFAULT NULL,
  `calon_dosen` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `kondisi_data` varchar(20) DEFAULT 'Aktif',
  `tgl_pengajuan` datetime DEFAULT NULL,
  `feedback_admin` varchar(225) DEFAULT NULL,
  `tgl_dibatalkan_ditolak` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pengajuan`
--

INSERT INTO `pengajuan` (`id_pengajuan`, `npm`, `judul`, `abstrak`, `id_topik`, `calon_dosen`, `status`, `kondisi_data`, `tgl_pengajuan`, `feedback_admin`, `tgl_dibatalkan_ditolak`) VALUES
(17, '220001', 'Sistem Presensi Berbasis Face Recognition', 'Sistem presensi menggunakan teknologi pengenalan wajah untuk meningkatkan keamanan dan efisiensi absensi mahasiswa.', 1, '100001', 'Ditolak Permanen', 'Aktif', '2026-06-14 05:37:10', 'terlalu banyak yg membuat itu', '2026-06-14 11:50:01'),
(18, '220002', 'Sistem Rekomendasi Buku Menggunakan AI', 'Sistem rekomendasi buku berbasis kecerdasan buatan menggunakan metode collaborative filtering.', 2, '100002', 'Pending', 'Aktif', '2026-06-14 05:37:10', NULL, NULL),
(19, '230001', 'Deteksi Malware Menggunakan Machine Learning', 'Penelitian deteksi malware dengan algoritma Random Forest.', 4, '100003', 'Pending', 'Aktif', '2026-06-14 05:37:10', NULL, NULL),
(999, '220002', 'Implementasi Artificial Intelligence Untuk Analisis Sentimen Media Sosial', NULL, 1, NULL, 'Ditetapkan', 'Aktif', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review_pengajuan`
--

CREATE TABLE `review_pengajuan` (
  `id_review` int(11) NOT NULL,
  `id_pengajuan` int(11) DEFAULT NULL,
  `nidn` varchar(20) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `tgl_review` date DEFAULT NULL,
  `sudah_direvisi` tinyint(4) DEFAULT 0,
  `feedback_lama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `review_pengajuan`
--

INSERT INTO `review_pengajuan` (`id_review`, `id_pengajuan`, `nidn`, `feedback`, `status`, `tgl_review`, `sudah_direvisi`, `feedback_lama`) VALUES
(20, 17, NULL, 'Layak dilanjutkan', 'ACC', '2026-06-14', 0, NULL),
(21, 18, NULL, 'Judul sesuai bidang', 'ACC', '2026-06-14', 0, NULL),
(22, 19, NULL, 'Topik kurang sesuai', 'Ditolak', '2026-06-14', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `topik_skripsi`
--

CREATE TABLE `topik_skripsi` (
  `id_topik` int(11) NOT NULL,
  `nama_topik` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `topik_skripsi`
--

INSERT INTO `topik_skripsi` (`id_topik`, `nama_topik`) VALUES
(2, 'Artificial Intelligence'),
(9, 'Basis Data'),
(3, 'Data Mining'),
(8, 'Internet of Things'),
(6, 'Jaringan Komputer'),
(5, 'Keamanan Siber'),
(4, 'Machine Learning'),
(7, 'Rekayasa Perangkat Lunak'),
(1, 'Sistem Informasi'),
(10, 'Sistem Pendukung Keputusan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$0bWVq0b0PZlNOOMm34nXZ.c8HtIwOjofz6AsiVTo0EP9.gGkC1TPy', 'admin'),
(2, '220001', '$2y$10$0bWVq0b0PZlNOOMm34nXZ.c8HtIwOjofz6AsiVTo0EP9.gGkC1TPy', 'mahasiswa'),
(3, '100001', '$2y$10$0bWVq0b0PZlNOOMm34nXZ.c8HtIwOjofz6AsiVTo0EP9.gGkC1TPy', 'dosen');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arsip_skripsi`
--
ALTER TABLE `arsip_skripsi`
  ADD PRIMARY KEY (`id_arsip`),
  ADD KEY `id_topik` (`id_topik`),
  ADD KEY `pembimbing_1` (`pembimbing_1`),
  ADD KEY `pembimbing_2` (`pembimbing_2`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`nidn`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `logbook_bimbingan`
--
ALTER TABLE `logbook_bimbingan`
  ADD PRIMARY KEY (`id_logbook`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`npm`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembimbing`
--
ALTER TABLE `pembimbing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pengajuan` (`id_pengajuan`),
  ADD KEY `pembimbing_1` (`pembimbing_1`),
  ADD KEY `pembimbing_2` (`pembimbing_2`);

--
-- Indexes for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `npm` (`npm`),
  ADD KEY `id_topik` (`id_topik`),
  ADD KEY `calon_dosen` (`calon_dosen`);

--
-- Indexes for table `review_pengajuan`
--
ALTER TABLE `review_pengajuan`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_pengajuan` (`id_pengajuan`),
  ADD KEY `nidn` (`nidn`);

--
-- Indexes for table `topik_skripsi`
--
ALTER TABLE `topik_skripsi`
  ADD PRIMARY KEY (`id_topik`),
  ADD UNIQUE KEY `nama_topik` (`nama_topik`);

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
-- AUTO_INCREMENT for table `arsip_skripsi`
--
ALTER TABLE `arsip_skripsi`
  MODIFY `id_arsip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `logbook_bimbingan`
--
ALTER TABLE `logbook_bimbingan`
  MODIFY `id_logbook` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembimbing`
--
ALTER TABLE `pembimbing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `review_pengajuan`
--
ALTER TABLE `review_pengajuan`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arsip_skripsi`
--
ALTER TABLE `arsip_skripsi`
  ADD CONSTRAINT `arsip_skripsi_ibfk_1` FOREIGN KEY (`id_topik`) REFERENCES `topik_skripsi` (`id_topik`) ON DELETE SET NULL,
  ADD CONSTRAINT `arsip_skripsi_ibfk_2` FOREIGN KEY (`pembimbing_1`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `arsip_skripsi_ibfk_3` FOREIGN KEY (`pembimbing_2`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL;

--
-- Constraints for table `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembimbing`
--
ALTER TABLE `pembimbing`
  ADD CONSTRAINT `pembimbing_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id_pengajuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembimbing_ibfk_2` FOREIGN KEY (`pembimbing_1`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembimbing_ibfk_3` FOREIGN KEY (`pembimbing_2`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE;

--
-- Constraints for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `pengajuan_ibfk_1` FOREIGN KEY (`npm`) REFERENCES `mahasiswa` (`npm`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_ibfk_2` FOREIGN KEY (`id_topik`) REFERENCES `topik_skripsi` (`id_topik`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_ibfk_3` FOREIGN KEY (`calon_dosen`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE;

--
-- Constraints for table `review_pengajuan`
--
ALTER TABLE `review_pengajuan`
  ADD CONSTRAINT `review_pengajuan_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id_pengajuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_pengajuan_ibfk_2` FOREIGN KEY (`nidn`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
