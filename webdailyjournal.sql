-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 08:24 AM
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
-- Database: `webdailyjournal`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `judul` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `isi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `gambar` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `judul`, `isi`, `gambar`, `tanggal`, `username`) VALUES
(1, 'Udinus', 'This is a wider card with supporting text', 'download.jpg', '2024-12-11 13:09:14', 'admin'),
(2, 'Marba Kota Lama', 'This is another content', 'indra-projects-iA58QK2RF0Q-unsplash.jpg', '2024-12-11 13:09:15', 'admin'),
(3, 'Ruang Baca', 'Another description here', 'inna-safa-XbJx7F4Lv1A-unsplash.jpg', '2024-12-11 13:09:16', 'admin'),
(4, 'Tugu Muda', 'Historical spot details', 'istockphoto-1718640308-1024x1024.jpg', '2024-12-11 13:09:17', 'admin'),
(5, 'Gereja', 'Landmark description', '20250108135432.jpg', '2025-01-08 13:54:32', 'admin'),
(6, 'Stadion', 'Sports complex overview', 'fikri-rasyid-b84nM5W-AF0-unsplash.jpg', '2024-12-11 13:09:19', 'admin'),
(8, 'Saya', 'ini saya', '20250108135417.png', '2025-01-08 13:54:17', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `gambar` text NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `tanggal`, `gambar`, `username`) VALUES
(1, '2025-01-05', 'img/logo.png', 'admin'),
(3, '2025-01-08', 'img/fikri-rasyid-b84nM5W-AF0-unsplash.jpg', 'admin'),
(4, '2025-01-08', 'img/Screenshot 2024-02-24 122232.png', 'admin'),
(6, '2025-01-08', 'img/inna-safa-XbJx7F4Lv1A-unsplash.jpg', 'admin'),
(7, '2025-01-08', 'img/istockphoto-1718640308-1024x1024.jpg', 'admin'),
(16, '2025-01-08', 'img/indra-projects-iA58QK2RF0Q-unsplash.jpg', 'diva'),
(17, '2025-01-08', 'img/indra-projects-iA58QK2RF0Q-unsplash.jpg', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `foto`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', ''),
(4, 'diva', '859a37720c27b9f70e11b79bab9318fe', 'img/20250108135417.png'),
(5, 'nanda', '4d186321c1a7f0f354b297e8914ab240', ''),
(7, 'hahaha', '202cb962ac59075b964b07152d234b70', ''),
(8, 'akucintaug', 'a407d02fdfa345a6757a3b7f86483014', ''),
(9, 'a112315484', 'b9e588f017f9bc991baf9d230989608f', ''),
(10, 'danny', '21232f297a57a5a743894a0e4a801fc3', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
