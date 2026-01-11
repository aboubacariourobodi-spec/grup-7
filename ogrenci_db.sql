-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2025 at 09:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oğrenci_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `kullanici`
--

CREATE TABLE `kullanici` (
  `id` int(11) NOT NULL,
  `adiSoyadi` varchar(255) NOT NULL,
  `e_posta` varchar(255) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kullanici`
--

INSERT INTO `kullanici` (`id`, `adiSoyadi`, `e_posta`, `sifre`, `created_at`) VALUES
(1, 'Yahya Juma', 'yahyally18@gmail.com', '$2y$10$Cvz/PNhBMwqu5Rl06dA8v.tnKVKYyCpkerfoUutOVAhC8FuoFIbae', '2025-12-28 07:04:02'),
(2, 'Muhd', 'Muhammed18@gmail.com', '$2y$10$ZPNCagqqC96Hvv3tl/dXTuJwZ60toJlsblfq68fx/jno9NJtsvCdy', '2025-12-28 07:20:09');

-- --------------------------------------------------------

--
-- Table structure for table `ogrenci_detaylar`
--

CREATE TABLE `ogrenci_detaylar` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `adiSoyadi` varchar(255) NOT NULL,
  `e_posta` varchar(255) NOT NULL,
  `numara` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ogrenci_detaylar`
--

INSERT INTO `ogrenci_detaylar` (`id`, `kullanici_id`, `adiSoyadi`, `e_posta`, `numara`, `created_at`) VALUES
(2, 2, 'Asma', 'asma@hotmail.com', '2147483647', '2025-12-28 07:20:49'),
(3, 2, 'Jumaff', '3@gmail.com', '05518393578', '2025-12-28 07:24:49');

-- --------------------------------------------------------

--
-- Table structure for table `sunumlar`
--

CREATE TABLE `sunumlar` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `baslik` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `dosya_adi` varchar(255) NOT NULL,
  `dosya_yolu` varchar(500) NOT NULL,
  `yukleme_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sunumlar`
--

INSERT INTO `sunumlar` (`id`, `kullanici_id`, `baslik`, `kategori`, `aciklama`, `dosya_adi`, `dosya_yolu`, `yukleme_tarihi`) VALUES
(1, 2, 'Passport', 'Mühendislik', 'asdasd', 'TomerLetter.pdf', 'uploads/sunum_2_1766907241_6950dd694901b.pdf', '2025-12-28 07:34:01'),
(2, 1, 'Tömer Letter', 'Tarih', 'Kabul Mektup', 'TomerLetter.pdf', 'uploads/sunum_1_1766909243_6950e53b4f353.pdf', '2025-12-28 08:07:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kullanici`
--
ALTER TABLE `kullanici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `e_posta` (`e_posta`),
  ADD KEY `idx_email` (`e_posta`);

--
-- Indexes for table `ogrenci_detaylar`
--
ALTER TABLE `ogrenci_detaylar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`e_posta`),
  ADD KEY `idx_kullanici_id` (`kullanici_id`);

--
-- Indexes for table `sunumlar`
--
ALTER TABLE `sunumlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kullanici_id` (`kullanici_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kullanici`
--
ALTER TABLE `kullanici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ogrenci_detaylar`
--
ALTER TABLE `ogrenci_detaylar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sunumlar`
--
ALTER TABLE `sunumlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ogrenci_detaylar`
--
ALTER TABLE `ogrenci_detaylar`
  ADD CONSTRAINT `ogrenci_detaylar_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sunumlar`
--
ALTER TABLE `sunumlar`
  ADD CONSTRAINT `sunumlar_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanici` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
