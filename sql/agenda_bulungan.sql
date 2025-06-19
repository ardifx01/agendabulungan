-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 04:39 PM
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
-- Database: `agenda_bulungan`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `lokasi` varchar(200) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu` time DEFAULT NULL,
  `pejabat_id` int(11) DEFAULT NULL,
  `undangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id`, `judul`, `lokasi`, `tanggal`, `waktu`, `pejabat_id`, `undangan`, `created_at`) VALUES
(4, 'Tarung Gladiator', 'Arena UFC Bulungan', '2025-06-15', '09:00:00', 10, NULL, '2025-06-19 02:34:25'),
(5, 'Olahraga Bersama', 'Mapolresta Bulungan', '2025-06-20', '07:00:00', 13, NULL, '2025-06-19 14:15:46'),
(6, 'Sannipata Waisak Bersama se-Kalimantan Utara 2569BE/2025', 'Hotel Tarakan Plaza', '2025-06-20', '18:00:00', 13, NULL, '2025-06-19 14:17:39'),
(7, 'Pelepasan Rombongan DPC PEPABRI Bulungan ke Balikpapan', 'Kantor DPC Pepabri Bulungan', '2025-06-20', '16:45:00', 13, NULL, '2025-06-19 14:19:27'),
(8, 'Rakor perkebunan Besar Swasta se-Kabupaten Bulungan', 'Hotel Pangeran Khar Tanjung Selor', '2025-06-20', '13:30:00', 13, NULL, '2025-06-19 14:22:03'),
(9, 'Grand Opening Toko Nuansa Elektronik Tanjung Selor', 'Toko Nuansa Elektronik Tanjung Selor', '2025-06-21', '09:00:00', 13, NULL, '2025-06-19 14:22:52'),
(10, 'Gerakan Ekonomi Kreatif Nasional', 'Chinatown Tanjung Selor', '2025-06-21', '16:30:00', 13, NULL, '2025-06-19 14:23:35'),
(11, 'Apresiasi Atlet dan Pelatih Berprestasi KONI Kabupaten Bulungan dan Launching Bulungan Unggul', 'Gedung HANDAL Tanjung Selor', '2025-06-21', '10:00:00', 13, NULL, '2025-06-19 14:24:43'),
(12, 'Hari Krida Pertanian', 'Area TEBUKAYAN', '2025-06-22', '07:00:00', 13, NULL, '2025-06-19 14:25:19'),
(13, 'Pelantikan Pengurus BPC HIPMI Kabupaten Bulungan Periode 2025-2028', 'Hotel Pangeran Khar Tanjung Selor', '2025-06-22', '14:00:00', 13, NULL, '2025-06-19 14:26:57'),
(14, 'Penghargaan SIWO Awards II 2025', 'Lt. 1 Gedung Gadis Kaltara Jalan Rambutan', '2025-06-24', '09:00:00', 13, NULL, '2025-06-19 14:27:53'),
(15, 'Penjemputan Jemaah Haji Kabupaten Bulungan', 'Pelabuhan VIP Tanjung Selor', '2025-06-24', '13:30:00', 13, NULL, '2025-06-19 14:28:38'),
(16, 'Though Leader Forum (TLF) ke-34', 'Hotel Gran Mahakam', '2025-06-25', '09:00:00', 10, NULL, '2025-06-19 14:29:29'),
(17, 'Rakor Administrasi Pembangunan se-Provinsi Kalimantan Utara', 'Penginapan 5 Putra Tanah Kuning', '2025-06-25', '08:30:00', 13, NULL, '2025-06-19 14:30:25'),
(18, 'Penghargaan Budaya Pemberian Gelar Ningrat Kehormatan', 'Keraton Kasunanan Solo', '2025-06-26', '16:00:00', 13, NULL, '2025-06-19 14:31:13'),
(19, 'Munas I Wakil Kepala Daerah Seluruh Indonesia', 'Hotel Shafir Jl. Laksda Adisucipto Yogyakarta', '2025-07-02', '09:00:00', 11, NULL, '2025-06-19 14:32:29'),
(20, 'Audiensi Dengan Kementerian HAM Kalimantan Timur', 'Kantor Bupati Bulungan', '2025-07-02', '09:00:00', 13, NULL, '2025-06-19 14:33:17'),
(21, 'Munas V ADPMET', 'JW Marriot Hotel Jakarta', '2025-07-10', '08:00:00', 13, NULL, '2025-06-19 14:34:00'),
(22, 'Upacara Pembukaan Jambore Cabang Gerakan Pramuka Bulungan', 'Halaman Kantor Bupati Bulungan', '2025-07-30', '08:00:00', 13, NULL, '2025-06-19 14:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `agenda_peliput`
--

CREATE TABLE `agenda_peliput` (
  `id` int(11) NOT NULL,
  `agenda_id` int(11) NOT NULL,
  `peliput_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenda_peliput`
--

INSERT INTO `agenda_peliput` (`id`, `agenda_id`, `peliput_id`) VALUES
(7, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `agenda_protokoler`
--

CREATE TABLE `agenda_protokoler` (
  `id` int(11) NOT NULL,
  `agenda_id` int(11) NOT NULL,
  `protokoler_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenda_protokoler`
--

INSERT INTO `agenda_protokoler` (`id`, `agenda_id`, `protokoler_id`) VALUES
(7, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `keberadaan`
--

CREATE TABLE `keberadaan` (
  `id` int(11) NOT NULL,
  `pejabat_id` int(11) NOT NULL,
  `status` enum('Hadir','Dinas Luar','Sakit','Lainnya') DEFAULT 'Hadir',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keberadaan`
--

INSERT INTO `keberadaan` (`id`, `pejabat_id`, `status`, `updated_at`) VALUES
(18, 10, 'Hadir', '2025-06-19 12:49:02'),
(19, 11, 'Dinas Luar', '2025-06-19 14:12:57'),
(20, 12, 'Lainnya', '2025-06-19 14:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `pejabat`
--

CREATE TABLE `pejabat` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `unit_kerja` varchar(100) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pejabat`
--

INSERT INTO `pejabat` (`id`, `nama`, `nip`, `jabatan`, `unit_kerja`, `kontak`, `email`, `foto`, `created_at`) VALUES
(10, 'Syarwani, S.Pd., M.Si', '197402062020021001', 'Bupati', 'Kabupaten Bulungan', '08111111111', 'bupati@bulungan.go.id', 'pejabat_1750299635.jpg', '2025-06-19 02:20:35'),
(11, 'Kilat, A.Md', '197603212025021001', 'Wakil Bupati', 'Kabupaten Bulungan', '08222233333', 'wabup@bulungan.go.id', 'pejabat_1750299670.jpg', '2025-06-19 02:21:10'),
(12, 'Ir. Risdianto, S.Pi., M.Si', '197908032005021007', 'Sekda', 'Kabupaten Bulungan', '08333333333', 'sekda@bulungan.go.id', 'pejabat_1750299717.jpg', '2025-06-19 02:21:57'),
(13, 'Belum Ditentukan', '1990000000888999', 'Tidak Ada', 'Kabupaten Bulungan', '0854334466667', 'tidakada@bulungan.go.id', 'pejabat_1750342851.jpg', '2025-06-19 14:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `peliput`
--

CREATE TABLE `peliput` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `unit_kerja` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peliput`
--

INSERT INTO `peliput` (`id`, `nama`, `jabatan`, `unit_kerja`, `created_at`) VALUES
(1, 'Seggaf', 'Staff', 'DKIP Bulungan', '2025-06-19 02:04:26'),
(2, 'Hongky', 'Staff', 'DKIP Bulungan', '2025-06-19 02:04:39'),
(3, 'Samsul', 'Staff', 'DKIP Bulungan', '2025-06-19 02:04:54'),
(4, 'Purnomo', 'Staff', 'DKIP Bulungan', '2025-06-19 02:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `pidato`
--

CREATE TABLE `pidato` (
  `id` int(11) NOT NULL,
  `id_agenda` int(11) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `protokoler`
--

CREATE TABLE `protokoler` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `unit_kerja` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `protokoler`
--

INSERT INTO `protokoler` (`id`, `nama`, `jabatan`, `unit_kerja`, `created_at`) VALUES
(1, 'Aning', 'Staff', 'Prokompim', '2025-06-19 02:05:27'),
(2, 'Dea', 'Anna', 'Prokompim', '2025-06-19 02:05:41'),
(3, 'Anna', 'Staff', 'Prokompim', '2025-06-19 02:06:13'),
(4, 'Fajar Derwanto', 'Staff', 'Prokompim', '2025-06-19 02:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `level` enum('superadmin','admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','operator') NOT NULL DEFAULT 'operator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `level`, `created_at`, `role`) VALUES
(1, 'dkipbul', '$2y$10$12YcvJP06jq6pHlJEfot2ekJ/BFDavAgIbLW5ZobJVEIwcphgpDjy', 'DKIP Bulungan', 'admin', '2025-06-19 01:15:01', 'admin'),
(2, 'hongky', '$2y$10$NOJZdpiNnOz.HxjXD1JNSuIW2RfmJ.uGV1K5RmvC1sY8/QKY1CboK', 'Hongky', 'admin', '2025-06-19 02:10:38', 'operator'),
(3, 'sanji', '$2y$10$fqZhFKsxGk2hq57FOxBqqeLcISUOej8dmjiydFz.zNTo7EA/dTXry', 'Sanji', 'admin', '2025-06-19 05:24:32', 'operator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pejabat_id` (`pejabat_id`);

--
-- Indexes for table `agenda_peliput`
--
ALTER TABLE `agenda_peliput`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agenda_id` (`agenda_id`),
  ADD KEY `peliput_id` (`peliput_id`);

--
-- Indexes for table `agenda_protokoler`
--
ALTER TABLE `agenda_protokoler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agenda_id` (`agenda_id`),
  ADD KEY `protokoler_id` (`protokoler_id`);

--
-- Indexes for table `keberadaan`
--
ALTER TABLE `keberadaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pejabat_id` (`pejabat_id`);

--
-- Indexes for table `pejabat`
--
ALTER TABLE `pejabat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peliput`
--
ALTER TABLE `peliput`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pidato`
--
ALTER TABLE `pidato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pidato_agenda` (`id_agenda`);

--
-- Indexes for table `protokoler`
--
ALTER TABLE `protokoler`
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
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `agenda_peliput`
--
ALTER TABLE `agenda_peliput`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `agenda_protokoler`
--
ALTER TABLE `agenda_protokoler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `keberadaan`
--
ALTER TABLE `keberadaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pejabat`
--
ALTER TABLE `pejabat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `peliput`
--
ALTER TABLE `peliput`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pidato`
--
ALTER TABLE `pidato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `protokoler`
--
ALTER TABLE `protokoler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`pejabat_id`) REFERENCES `pejabat` (`id`);

--
-- Constraints for table `agenda_peliput`
--
ALTER TABLE `agenda_peliput`
  ADD CONSTRAINT `agenda_peliput_ibfk_1` FOREIGN KEY (`agenda_id`) REFERENCES `agenda` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agenda_peliput_ibfk_2` FOREIGN KEY (`peliput_id`) REFERENCES `peliput` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `agenda_protokoler`
--
ALTER TABLE `agenda_protokoler`
  ADD CONSTRAINT `agenda_protokoler_ibfk_1` FOREIGN KEY (`agenda_id`) REFERENCES `agenda` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agenda_protokoler_ibfk_2` FOREIGN KEY (`protokoler_id`) REFERENCES `protokoler` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `keberadaan`
--
ALTER TABLE `keberadaan`
  ADD CONSTRAINT `keberadaan_ibfk_1` FOREIGN KEY (`pejabat_id`) REFERENCES `pejabat` (`id`);

--
-- Constraints for table `pidato`
--
ALTER TABLE `pidato`
  ADD CONSTRAINT `fk_pidato_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
