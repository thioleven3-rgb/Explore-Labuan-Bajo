-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jul 2026 pada 01.44
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `labuan_bajo_rekomendasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `items`
--

CREATE TABLE `items` (
  `item_id` varchar(10) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `avg_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `price_range` varchar(5) NOT NULL,
  `location` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `category`, `avg_rating`, `total_reviews`, `price_range`, `location`, `description`) VALUES
('I001', 'Taman Nasional Komodo', 'island_beach', 4.83, 6, '$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I002', 'Pink Beach (Pantai Merah)', 'island_beach', 4.33, 3, '$$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I003', 'Pulau Padar', 'island_beach', 3.83, 6, '$$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I004', 'Gili Lawa', 'island_beach', 4.11, 9, '$$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I005', 'Pulau Kanawa', 'island_beach', 4.00, 4, '$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I006', 'Pulau Rinca', 'island_beach', 4.67, 3, '$$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I007', 'Taka Makassar', 'island_beach', 3.80, 5, '$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I008', 'Pulau Kelor', 'island_beach', 4.11, 9, '$', 'Labuan Bajo, Flores, NTT', 'Destinasi pantai dan pulau eksotis dengan pemandangan menakjubkan'),
('I009', 'Manta Point', 'diving_snorkeling', 4.00, 7, '$$$', 'Labuan Bajo, Flores, NTT', 'Spot diving dan snorkeling kelas dunia dengan biodiversitas tinggi'),
('I010', 'Batu Bolong Dive Site', 'diving_snorkeling', 4.33, 3, '$$', 'Labuan Bajo, Flores, NTT', 'Spot diving dan snorkeling kelas dunia dengan biodiversitas tinggi'),
('I011', 'Crystal Rock', 'diving_snorkeling', 4.00, 3, '$$$', 'Labuan Bajo, Flores, NTT', 'Spot diving dan snorkeling kelas dunia dengan biodiversitas tinggi'),
('I012', 'Siaba Besar', 'diving_snorkeling', 3.67, 3, '$$$', 'Labuan Bajo, Flores, NTT', 'Spot diving dan snorkeling kelas dunia dengan biodiversitas tinggi'),
('I013', 'Castle Rock', 'diving_snorkeling', 4.00, 5, '$$$', 'Labuan Bajo, Flores, NTT', 'Spot diving dan snorkeling kelas dunia dengan biodiversitas tinggi'),
('I014', 'Air Terjun Cunca Wulang', 'nature_adventure', 4.40, 5, '$$', 'Labuan Bajo, Flores, NTT', 'Petualangan alam dan budaya yang memukau'),
('I015', 'Gua Batu Cermin', 'nature_adventure', 3.80, 5, '$$', 'Labuan Bajo, Flores, NTT', 'Petualangan alam dan budaya yang memukau'),
('I016', 'Gua Rangko', 'nature_adventure', 4.33, 3, '$$', 'Labuan Bajo, Flores, NTT', 'Petualangan alam dan budaya yang memukau'),
('I017', 'Wae Rebo Traditional Village', 'nature_adventure', 4.00, 3, '$', 'Labuan Bajo, Flores, NTT', 'Petualangan alam dan budaya yang memukau'),
('I018', 'Ayana Komodo Resort', 'accommodation_culinary', 3.88, 8, '$$', 'Labuan Bajo, Flores, NTT', 'Akomodasi dan kuliner berkualitas dengan pengalaman istimewa'),
('I019', 'Le Pirate Beach Club', 'accommodation_culinary', 3.67, 3, '$$$', 'Labuan Bajo, Flores, NTT', 'Akomodasi dan kuliner berkualitas dengan pengalaman istimewa'),
('I020', 'Made in Italy Restaurant', 'accommodation_culinary', 3.67, 3, '$$', 'Labuan Bajo, Flores, NTT', 'Akomodasi dan kuliner berkualitas dengan pengalaman istimewa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `rating_timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ratings`
--

INSERT INTO `ratings` (`rating_id`, `user_id`, `item_id`, `rating`, `rating_timestamp`) VALUES
(1, 'U001', 'I002', 5, '2026-05-15 12:12:30'),
(2, 'U001', 'I001', 4, '2026-03-12 12:12:30'),
(3, 'U002', 'I017', 5, '2026-03-22 12:12:30'),
(4, 'U002', 'I001', 5, '2025-08-03 12:12:30'),
(5, 'U002', 'I018', 4, '2025-07-07 12:12:30'),
(6, 'U003', 'I008', 4, '2025-09-03 12:12:30'),
(7, 'U003', 'I015', 5, '2026-02-09 12:12:30'),
(8, 'U004', 'I006', 5, '2025-07-09 12:12:30'),
(9, 'U005', 'I011', 4, '2026-04-13 12:12:30'),
(10, 'U005', 'I009', 5, '2026-03-13 12:12:30'),
(11, 'U006', 'I004', 4, '2025-12-30 12:12:30'),
(12, 'U006', 'I003', 4, '2026-01-06 12:12:30'),
(13, 'U006', 'I013', 4, '2025-08-26 12:12:30'),
(14, 'U006', 'I020', 3, '2026-02-16 12:12:30'),
(15, 'U007', 'I015', 3, '2025-12-20 12:12:30'),
(16, 'U007', 'I018', 4, '2026-05-22 12:12:30'),
(17, 'U007', 'I004', 3, '2025-09-22 12:12:30'),
(18, 'U008', 'I020', 4, '2026-06-08 12:12:30'),
(19, 'U008', 'I012', 3, '2025-07-28 12:12:30'),
(20, 'U008', 'I007', 3, '2026-03-07 12:12:30'),
(21, 'U008', 'I003', 5, '2026-02-03 12:12:30'),
(22, 'U009', 'I008', 4, '2026-02-09 12:12:30'),
(23, 'U009', 'I004', 4, '2025-11-11 12:12:30'),
(24, 'U009', 'I013', 3, '2025-08-10 12:12:30'),
(25, 'U010', 'I006', 4, '2026-01-01 12:12:30'),
(26, 'U010', 'I012', 4, '2026-03-16 12:12:30'),
(27, 'U011', 'I003', 3, '2025-10-01 12:12:30'),
(28, 'U011', 'I006', 5, '2026-02-26 12:12:30'),
(29, 'U012', 'I015', 4, '2025-12-19 12:12:30'),
(30, 'U013', 'I018', 4, '2025-07-16 12:12:30'),
(31, 'U013', 'I008', 4, '2026-01-16 12:12:30'),
(32, 'U014', 'I008', 5, '2026-06-15 12:12:30'),
(33, 'U015', 'I013', 4, '2025-09-14 12:12:30'),
(34, 'U015', 'I009', 3, '2026-01-21 12:12:30'),
(35, 'U015', 'I003', 5, '2026-03-15 12:12:30'),
(36, 'U015', 'I007', 3, '2025-07-31 12:12:30'),
(37, 'U016', 'I013', 4, '2026-04-21 12:12:30'),
(38, 'U016', 'I015', 3, '2026-02-25 12:12:30'),
(39, 'U016', 'I005', 3, '2025-09-17 12:12:30'),
(40, 'U016', 'I009', 4, '2025-09-29 12:12:30'),
(41, 'U017', 'I019', 5, '2025-09-06 12:12:30'),
(42, 'U017', 'I014', 4, '2025-12-09 12:12:30'),
(43, 'U018', 'I008', 4, '2026-05-16 12:12:30'),
(44, 'U018', 'I005', 4, '2026-06-07 12:12:30'),
(45, 'U018', 'I017', 3, '2026-05-06 12:12:30'),
(46, 'U018', 'I016', 4, '2026-04-14 12:12:30'),
(47, 'U019', 'I014', 4, '2025-08-30 12:12:30'),
(48, 'U020', 'I013', 5, '2025-12-18 12:12:30'),
(49, 'U021', 'I017', 4, '2025-09-21 12:12:30'),
(50, 'U021', 'I009', 3, '2026-06-26 12:12:30'),
(51, 'U022', 'I018', 4, '2026-02-15 12:12:30'),
(52, 'U023', 'I004', 4, '2025-11-21 12:12:30'),
(53, 'U023', 'I010', 4, '2026-04-12 12:12:30'),
(54, 'U024', 'I001', 5, '2025-10-18 12:12:30'),
(55, 'U024', 'I009', 5, '2026-04-01 12:12:30'),
(56, 'U025', 'I010', 5, '2025-08-08 12:12:30'),
(57, 'U026', 'I005', 4, '2025-12-22 12:12:30'),
(58, 'U027', 'I018', 4, '2025-10-03 12:12:30'),
(59, 'U028', 'I020', 4, '2026-01-17 12:12:30'),
(60, 'U029', 'I001', 5, '2025-12-28 12:12:30'),
(61, 'U029', 'I004', 4, '2026-01-25 12:12:30'),
(62, 'U030', 'I002', 4, '2026-02-28 12:12:30'),
(63, 'U031', 'I003', 3, '2025-10-26 12:12:30'),
(64, 'U032', 'I018', 3, '2026-04-28 12:12:30'),
(65, 'U033', 'I016', 5, '2025-09-23 12:12:30'),
(66, 'U034', 'I009', 5, '2025-10-04 12:12:30'),
(67, 'U035', 'I014', 4, '2025-12-09 12:12:30'),
(68, 'U035', 'I007', 5, '2025-07-23 12:12:30'),
(69, 'U035', 'I018', 4, '2025-08-03 12:12:30'),
(70, 'U035', 'I019', 4, '2025-12-22 12:12:30'),
(71, 'U035', 'I010', 4, '2025-11-19 12:12:30'),
(72, 'U036', 'I004', 5, '2026-03-08 12:12:30'),
(73, 'U036', 'I008', 4, '2026-05-30 12:12:30'),
(74, 'U037', 'I001', 5, '2025-09-21 12:12:30'),
(75, 'U037', 'I019', 2, '2026-03-06 12:12:30'),
(76, 'U038', 'I001', 5, '2026-05-26 12:12:30'),
(77, 'U039', 'I008', 4, '2026-05-28 12:12:30'),
(78, 'U040', 'I011', 4, '2026-05-26 12:12:30'),
(79, 'U041', 'I008', 4, '2025-09-12 12:12:30'),
(80, 'U041', 'I009', 3, '2025-09-09 12:12:30'),
(81, 'U041', 'I016', 4, '2025-11-01 12:12:30'),
(82, 'U041', 'I007', 4, '2026-02-27 12:12:30'),
(83, 'U041', 'I005', 5, '2025-11-01 12:12:30'),
(84, 'U042', 'I007', 4, '2026-05-13 12:12:30'),
(85, 'U042', 'I004', 4, '2025-07-29 12:12:30'),
(86, 'U043', 'I012', 4, '2025-12-03 12:12:30'),
(87, 'U043', 'I014', 5, '2025-11-04 12:12:30'),
(88, 'U044', 'I004', 4, '2026-05-31 12:12:30'),
(89, 'U045', 'I011', 4, '2026-02-24 12:12:30'),
(90, 'U045', 'I004', 5, '2026-03-25 12:12:30'),
(91, 'U046', 'I018', 4, '2025-11-14 12:12:30'),
(92, 'U047', 'I014', 5, '2026-03-30 12:12:30'),
(93, 'U048', 'I015', 4, '2026-05-24 12:12:30'),
(94, 'U048', 'I008', 4, '2025-11-17 12:12:30'),
(95, 'U049', 'I002', 4, '2025-08-02 12:12:30'),
(96, 'U050', 'I003', 3, '2026-03-02 12:12:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` varchar(10) NOT NULL,
  `display_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `display_name`) VALUES
('U001', 'Wisatawan U001'),
('U002', 'Wisatawan U002'),
('U003', 'Wisatawan U003'),
('U004', 'Wisatawan U004'),
('U005', 'Wisatawan U005'),
('U006', 'Wisatawan U006'),
('U007', 'Wisatawan U007'),
('U008', 'Wisatawan U008'),
('U009', 'Wisatawan U009'),
('U010', 'Wisatawan U010'),
('U011', 'Wisatawan U011'),
('U012', 'Wisatawan U012'),
('U013', 'Wisatawan U013'),
('U014', 'Wisatawan U014'),
('U015', 'Wisatawan U015'),
('U016', 'Wisatawan U016'),
('U017', 'Wisatawan U017'),
('U018', 'Wisatawan U018'),
('U019', 'Wisatawan U019'),
('U020', 'Wisatawan U020'),
('U021', 'Wisatawan U021'),
('U022', 'Wisatawan U022'),
('U023', 'Wisatawan U023'),
('U024', 'Wisatawan U024'),
('U025', 'Wisatawan U025'),
('U026', 'Wisatawan U026'),
('U027', 'Wisatawan U027'),
('U028', 'Wisatawan U028'),
('U029', 'Wisatawan U029'),
('U030', 'Wisatawan U030'),
('U031', 'Wisatawan U031'),
('U032', 'Wisatawan U032'),
('U033', 'Wisatawan U033'),
('U034', 'Wisatawan U034'),
('U035', 'Wisatawan U035'),
('U036', 'Wisatawan U036'),
('U037', 'Wisatawan U037'),
('U038', 'Wisatawan U038'),
('U039', 'Wisatawan U039'),
('U040', 'Wisatawan U040'),
('U041', 'Wisatawan U041'),
('U042', 'Wisatawan U042'),
('U043', 'Wisatawan U043'),
('U044', 'Wisatawan U044'),
('U045', 'Wisatawan U045'),
('U046', 'Wisatawan U046'),
('U047', 'Wisatawan U047'),
('U048', 'Wisatawan U048'),
('U049', 'Wisatawan U049'),
('U050', 'Wisatawan U050');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indeks untuk tabel `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_item` (`item_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_ratings_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ratings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
