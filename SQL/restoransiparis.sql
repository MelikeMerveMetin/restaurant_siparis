-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 11 Oca 2024, 13:59:54
-- Sunucu sürümü: 10.4.27-MariaDB
-- PHP Sürümü: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `restoransiparis`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `anliksiparis`
--

CREATE TABLE `anliksiparis` (
  `id` int(11) NOT NULL,
  `masaid` int(11) NOT NULL,
  `garsonid` int(11) NOT NULL,
  `urunid` int(11) NOT NULL,
  `urunad` varchar(20) NOT NULL,
  `urunfiyat` float NOT NULL,
  `adet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `anliksiparis`
--

INSERT INTO `anliksiparis` (`id`, `masaid`, `garsonid`, `urunid`, `urunad`, `urunfiyat`, `adet`) VALUES
(341, 17, 1, 7, 'Bol Malzeme(Sucuk,Sa', 20, 1),
(342, 17, 1, 50, 'Gül Böreği', 8, 3),
(343, 18, 1, 57, 'Yayla Çorbası', 9, 4),
(344, 17, 1, 21, 'Limonata', 6, 2),
(345, 17, 1, 4, 'Soda', 2.75, 1),
(349, 4, 1, 37, 'Karışık Pizza(Sucuk,', 18, 2),
(350, 7, 1, 20, 'Sahlep', 5, 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `doluluk`
--

CREATE TABLE `doluluk` (
  `id` int(11) NOT NULL,
  `bos` int(11) NOT NULL DEFAULT 0,
  `dolu` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `doluluk`
--

INSERT INTO `doluluk` (`id`, `bos`, `dolu`) VALUES
(1, 14, 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `garson`
--

CREATE TABLE `garson` (
  `id` int(11) NOT NULL,
  `ad` varchar(20) NOT NULL,
  `sifre` int(11) NOT NULL,
  `durum` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `garson`
--

INSERT INTO `garson` (`id`, `ad`, `sifre`, `durum`) VALUES
(1, 'melike', 1, 1),
(22, 'ayşe', 8, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gecicigarson`
--

CREATE TABLE `gecicigarson` (
  `id` int(11) NOT NULL,
  `garsonid` int(11) NOT NULL,
  `garsonad` varchar(20) NOT NULL,
  `adet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `gecicigarson`
--

INSERT INTO `gecicigarson` (`id`, `garsonid`, `garsonad`, `adet`) VALUES
(1, 1, 'melike', 255);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gecicimasa`
--

CREATE TABLE `gecicimasa` (
  `id` int(11) NOT NULL,
  `masaid` int(11) NOT NULL,
  `masaad` varchar(20) NOT NULL,
  `hasilat` float NOT NULL,
  `adet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `geciciurun`
--

CREATE TABLE `geciciurun` (
  `id` int(11) NOT NULL,
  `urunid` int(11) NOT NULL,
  `urunad` varchar(20) NOT NULL,
  `hasilat` float NOT NULL,
  `adet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL,
  `ad` varchar(20) NOT NULL,
  `mutfakdurum` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `ad`, `mutfakdurum`) VALUES
(1, 'Sıcak İçecekler', 0),
(2, 'Soğuk İçecekler', 0),
(3, 'Tatlılar', 0),
(4, 'Pizzalar', 0),
(5, 'Tostlar', 0),
(8, 'Kahvaltı', 0),
(9, 'Salatalar', 0),
(10, 'Hamburgerler', 0),
(14, 'Börekler-Poğaçalar', 0),
(15, 'Çorbalar', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `masabakiye`
--

CREATE TABLE `masabakiye` (
  `id` int(11) NOT NULL,
  `masaid` int(11) NOT NULL,
  `tutar` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `masabakiye`
--

INSERT INTO `masabakiye` (`id`, `masaid`, `tutar`) VALUES
(36, 12, 10),
(37, 18, 6),
(38, 7, 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `masalar`
--

CREATE TABLE `masalar` (
  `id` int(11) NOT NULL,
  `ad` varchar(20) NOT NULL,
  `durum` int(11) NOT NULL DEFAULT 0,
  `saat` int(11) NOT NULL DEFAULT 0,
  `dakika` int(11) NOT NULL DEFAULT 0,
  `rezervedurum` int(11) NOT NULL DEFAULT 0,
  `kisi` varchar(50) NOT NULL DEFAULT 'bos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `masalar`
--

INSERT INTO `masalar` (`id`, `ad`, `durum`, `saat`, `dakika`, `rezervedurum`, `kisi`) VALUES
(1, 'MASA-1', 0, 0, 0, 0, 'Yok'),
(2, 'MASA-2', 0, 0, 0, 0, 'bos'),
(3, 'MASA-3', 1, 0, 0, 1, 'melike'),
(4, 'MASA-4', 1, 22, 26, 0, 'bos'),
(5, 'MASA-5', 0, 0, 0, 0, 'bos'),
(6, 'MASA-6', 0, 0, 0, 0, 'bos'),
(7, 'MASA-7', 1, 0, 0, 0, 'bos'),
(8, 'MASA-8', 0, 0, 0, 0, 'bos'),
(9, 'MASA-9', 0, 0, 0, 0, 'bos'),
(10, 'MASA-10', 0, 0, 0, 0, 'bos'),
(11, 'MASA-11', 0, 0, 0, 0, 'bos'),
(12, 'MASA-12', 0, 0, 0, 0, 'bos'),
(13, 'MASA-13', 0, 0, 0, 0, 'bos'),
(14, 'MASA-14', 0, 0, 0, 0, 'bos'),
(15, 'MASA-15', 0, 0, 0, 0, 'bos'),
(16, 'MASA-16', 0, 0, 0, 0, 'bos'),
(17, 'MASA-17', 1, 18, 16, 0, 'bos'),
(18, 'MASA-18', 1, 18, 25, 0, 'bos');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mutfaksiparis`
--

CREATE TABLE `mutfaksiparis` (
  `id` int(11) NOT NULL,
  `masaid` int(11) NOT NULL,
  `urunid` int(11) NOT NULL,
  `urunad` varchar(20) NOT NULL,
  `adet` int(11) NOT NULL,
  `saat` int(11) NOT NULL DEFAULT 0,
  `dakika` int(11) NOT NULL DEFAULT 0,
  `metin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `mutfaksiparis`
--

INSERT INTO `mutfaksiparis` (`id`, `masaid`, `urunid`, `urunad`, `adet`, `saat`, `dakika`, `metin`) VALUES
(144, 4, 7, 'Bol Malzeme(Sucuk,Sa', 2, 17, 57, 'Bol malzeme pizzanın birinde mantar olmasın.'),
(145, 4, 24, 'Bardak Çay', 2, 17, 58, 'Çaylar açık olsun.\r\n'),
(151, 12, 50, 'Gül Böreği', 3, 18, 24, ''),
(152, 18, 57, 'Yayla Çorbası', 4, 18, 25, ''),
(153, 5, 42, 'Mercimek Çorbası', 1, 23, 47, ''),
(156, 4, 37, 'Karışık Pizza(Sucuk,', 2, 22, 26, 'Karışık Pizzanın birinin içinde mantar olmasın.'),
(157, 9, 20, 'Sahlep', 4, 20, 35, '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rapor`
--

CREATE TABLE `rapor` (
  `id` int(11) NOT NULL,
  `masaid` int(11) NOT NULL,
  `garsonid` int(11) NOT NULL,
  `urunid` int(11) NOT NULL,
  `urunad` varchar(20) NOT NULL,
  `urunfiyat` float NOT NULL,
  `adet` int(11) NOT NULL,
  `tarih` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `rapor`
--

INSERT INTO `rapor` (`id`, `masaid`, `garsonid`, `urunid`, `urunad`, `urunfiyat`, `adet`, `tarih`) VALUES
(84, 12, 1, 3, 'Su', 3, 1, '2021-12-10'),
(85, 9, 1, 11, 'Şarap', 27.9, 1, '2021-12-10'),
(86, 9, 1, 21, 'Limonata', 6, 3, '2021-12-10'),
(87, 18, 1, 12, 'Kaşarlı Tost', 5, 3, '2021-12-10'),
(88, 18, 1, 20, 'Sahlep', 5, 1, '2021-12-10'),
(89, 18, 1, 4, 'Soda', 2.75, 2, '2021-12-10'),
(90, 18, 1, 16, 'Kahvaltı', 20, 2, '2021-12-10'),
(91, 11, 1, 19, 'Tavuklu Ham.', 8, 2, '2021-12-18'),
(92, 13, 1, 20, 'Sahlep', 5, 2, '2021-12-22'),
(93, 7, 1, 23, 'Türk Kahvesi', 6, 1, '2021-12-22'),
(94, 12, 1, 16, 'Kahvaltı', 20, 1, '2021-12-22'),
(95, 16, 1, 20, 'Sahlep', 5, 6, '2021-12-22'),
(96, 16, 1, 19, 'Tavuklu Ham.', 8, 1, '2021-12-22'),
(97, 1, 1, 5, 'Keşkül', 12, 5, '2021-12-22'),
(98, 18, 1, 45, 'Lüfer Izgara', 30, 5, '2021-12-22'),
(99, 18, 1, 53, 'Köfte Menü', 9, 3, '2021-12-22'),
(100, 18, 1, 16, 'Kahvaltı', 20, 5, '2021-12-22'),
(101, 18, 1, 35, 'Sprite', 4, 3, '2021-12-22'),
(102, 18, 1, 31, 'M. Suyu - Şeftali', 5, 4, '2021-12-22'),
(103, 18, 1, 43, 'Domates Ç.', 9, 1, '2021-12-22'),
(104, 2, 1, 23, 'Türk Kahvesi', 6, 2, '2021-12-24'),
(105, 10, 1, 33, 'M. Suyu - Kayısı', 5, 2, '2021-12-24'),
(326, 8, 1, 45, 'Lüfer Izgara', 30, 3, '2021-12-27'),
(327, 4, 1, 23, 'Türk Kahvesi', 6, 16, '2021-12-27'),
(328, 4, 1, 24, 'Bardak Çay', 2, 6, '2021-12-27'),
(329, 4, 1, 21, 'Limonata', 6, 3, '2021-12-27'),
(330, 4, 1, 19, 'Tavuklu Ham.', 8, 2, '2021-12-27'),
(331, 4, 1, 17, 'Ton Balıklı', 9, 3, '2021-12-27'),
(332, 4, 1, 23, 'Türk Kahvesi', 6, 1, '2021-12-27'),
(333, 1, 1, 21, 'Limonata', 6, 5, '2021-12-27'),
(334, 12, 1, 50, 'Gül Böreği', 8, 2, '2022-01-01'),
(335, 1, 1, 17, 'Ton Balıklı', 9, 2, '2022-01-01'),
(336, 2, 1, 17, 'Ton Balıklı', 9, 2, '2022-01-01'),
(337, 16, 1, 62, 'Oralet', 3, 3, '2022-01-01'),
(338, 16, 1, 5, 'Keşkül', 12, 1, '2022-01-01'),
(339, 16, 1, 45, 'Lüfer Izgara', 30, 1, '2022-01-01'),
(340, 16, 1, 17, 'Ton Balıklı', 9, 5, '2022-01-01'),
(341, 16, 1, 15, 'Sade Makarna', 8.95, 3, '2022-01-01'),
(342, 16, 1, 21, 'Limonata', 6, 3, '2022-01-01'),
(343, 16, 1, 20, 'Sahlep', 5, 2, '2022-01-01'),
(344, 15, 1, 51, 'Humus', 6, 3, '2022-01-01'),
(345, 12, 1, 23, 'Türk Kahvesi', 6, 2, '2022-01-01'),
(346, 11, 1, 17, 'Ton Balıklı', 9, 3, '2022-01-01'),
(347, 11, 1, 16, 'Kahvaltı', 20, 6, '2022-01-01'),
(348, 11, 1, 45, 'Lüfer Izgara', 30, 2, '2022-01-01'),
(349, 11, 1, 25, 'Sucuklu Tost', 7, 4, '2022-01-01'),
(350, 10, 1, 18, 'Sezar Salata', 8, 4, '2022-01-01'),
(351, 9, 1, 4, 'Soda', 2.75, 5, '2022-01-01'),
(352, 9, 1, 16, 'Kahvaltı', 20, 9, '2022-01-01'),
(353, 9, 1, 45, 'Lüfer Izgara', 30, 5, '2022-01-01'),
(354, 4, 1, 16, 'Kahvaltı', 20, 1, '2022-01-01'),
(355, 5, 1, 19, 'Tavuklu Ham.', 8, 2, '2022-01-01'),
(356, 5, 1, 38, 'Köfte Burger', 12, 5, '2022-01-01'),
(357, 5, 1, 49, 'Paçanga', 6, 3, '2022-01-01'),
(358, 5, 1, 20, 'Sahlep', 5, 3, '2022-01-01'),
(359, 5, 1, 23, 'Türk Kahvesi', 6, 5, '2022-01-01'),
(360, 6, 1, 21, 'Limonata', 6, 2, '2022-01-01'),
(361, 1, 1, 17, 'Ton Balıklı', 9, 4, '2022-01-08'),
(362, 1, 1, 23, 'Türk Kahvesi', 6, 1, '2022-01-08'),
(363, 1, 1, 49, 'Patatesli Poğaça', 6, 4, '2022-01-08'),
(364, 1, 1, 50, 'Gül Böreği', 8, 3, '2022-01-08'),
(365, 1, 1, 16, 'Kahvaltı', 20, 1, '2022-01-08'),
(366, 1, 1, 17, 'Ton Balıklı', 9, 1, '2022-01-08'),
(367, 10, 1, 17, 'Ton Balıklı', 9, 2, '2022-01-08'),
(368, 4, 1, 56, 'Ezogelin Çorbası', 9, 4, '2022-01-08'),
(369, 4, 1, 19, 'Tavuklu Hamburger', 8, 2, '2022-01-08'),
(370, 4, 1, 38, 'Köfte Burger', 12, 2, '2022-01-08'),
(371, 2, 1, 19, 'Tavuklu Hamburger', 8, 1, '2022-01-08'),
(372, 17, 1, 19, 'Tavuklu Hamburger', 8, 4, '2022-01-08'),
(373, 16, 1, 50, 'Gül Böreği', 8, 3, '2022-01-08'),
(374, 15, 1, 20, 'Sahlep', 5, 1, '2022-01-08'),
(375, 14, 1, 17, 'Ton Balıklı', 9, 2, '2022-01-08'),
(376, 14, 1, 19, 'Tavuklu Hamburger', 8, 2, '2022-01-08'),
(377, 9, 1, 17, 'Ton Balıklı', 9, 1, '2022-01-08'),
(378, 12, 1, 43, 'Domates Çorbası', 9, 4, '2022-01-08'),
(379, 12, 1, 50, 'Gül Böreği', 8, 4, '2022-01-08'),
(380, 1, 1, 50, 'Gül Böreği', 8, 2, '2022-01-08'),
(381, 4, 1, 19, 'Tavuklu Hamburger', 8, 2, '2022-01-08'),
(382, 4, 1, 49, 'Patatesli Poğaça', 6, 2, '2022-01-08'),
(383, 4, 1, 7, 'Bol Malzeme(Sucuk,Sa', 20, 2, '2022-01-08'),
(384, 4, 1, 24, 'Bardak Çay', 2, 2, '2022-01-08'),
(385, 6, 1, 12, 'Kaşarlı Tost', 5, 2, '2022-01-08'),
(386, 6, 1, 17, 'Ton Balıklı', 9, 3, '2022-01-08'),
(387, 5, 1, 56, 'Ezogelin Çorbası', 9, 5, '2022-01-08'),
(388, 11, 1, 19, 'Tavuklu Hamburger', 8, 2, '2022-01-08'),
(390, 15, 1, 19, 'Tavuklu Hamburger', 8, 2, '2022-01-11'),
(391, 16, 1, 4, 'Soda', 2.75, 1, '2022-01-11'),
(392, 5, 1, 42, 'Mercimek Çorbası', 9, 1, '2022-01-11');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `id` int(11) NOT NULL,
  `katid` int(11) NOT NULL,
  `ad` varchar(150) NOT NULL,
  `fiyat` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`id`, `katid`, `ad`, `fiyat`) VALUES
(3, 2, 'Su', 3),
(4, 2, 'Soda', 2.75),
(5, 3, 'Keşkül', 12),
(6, 3, 'Sütlaç', 12.5),
(7, 4, 'Bol Malzeme(Sucuk,Salam,Mantar,Zeytin,Mozeralla,Mısır,Tavuk)', 20),
(12, 5, 'Kaşarlı Tost', 5),
(13, 5, 'Karışık Tost', 7),
(16, 8, 'Kahvaltı Tabağı(Simit,Zeytin,Peynir,Salatalık,Domates,Reçel,Bal,Tereyağı)', 20),
(17, 9, 'Ton Balıklı', 9),
(18, 9, 'Sezar Salata', 8),
(19, 10, 'Tavuklu Hamburger', 8),
(20, 1, 'Sahlep', 5),
(21, 2, 'Limonata', 6),
(23, 1, 'Türk Kahvesi', 6),
(24, 1, 'Bardak Çay', 2),
(25, 5, 'Sucuklu Tost', 7),
(26, 9, 'Çoban Salata', 7),
(27, 2, 'Cola', 3),
(29, 1, 'Nescafe', 4),
(31, 2, 'M. Suyu - Şeftali', 5),
(32, 2, 'M. Suyu - Vişne', 5),
(33, 2, 'M. Suyu - Kayısı', 5),
(34, 2, 'Fanta', 4),
(35, 2, 'Sprite', 4),
(36, 3, 'Profitol', 9),
(37, 4, 'Karışık Pizza(Sucuk,Salam,Sosis,Mantar,Zeytin,Biber)', 18),
(38, 10, 'Köfte Burger', 12),
(40, 2, 'Ayran', 2),
(41, 1, 'Ihlamur', 4),
(42, 15, 'Mercimek Çorbası', 9),
(43, 15, 'Domates Çorbası', 9),
(44, 2, 'Şalgam', 4),
(49, 14, 'Patatesli Poğaça', 6),
(50, 14, 'Gül Böreği', 8),
(51, 14, 'Su Böreği', 6),
(52, 14, 'Kol Böreği', 6),
(56, 15, 'Ezogelin Çorbası', 9),
(57, 15, 'Yayla Çorbası', 9),
(61, 1, 'Fincan Çay', 3),
(62, 1, 'Oralet', 3),
(63, 1, 'Ada Çayı', 4),
(65, 8, 'Patates Kızartması', 10);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yonetim`
--

CREATE TABLE `yonetim` (
  `id` int(11) NOT NULL,
  `kulad` varchar(40) NOT NULL,
  `sifre` varchar(40) NOT NULL,
  `yetki` int(11) NOT NULL DEFAULT 0,
  `aktif` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `yonetim`
--

INSERT INTO `yonetim` (`id`, `kulad`, `sifre`, `yetki`, `aktif`) VALUES
(3, 'merve', '96de4eceb9a0c2b9b52c0b618819821b', 1, 0),
(14, 'ayşe', '469c094a54ece24fb7311614788f248c', 0, 0),
(15, 'melike', '96de4eceb9a0c2b9b52c0b618819821b', 0, 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `anliksiparis`
--
ALTER TABLE `anliksiparis`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `doluluk`
--
ALTER TABLE `doluluk`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `garson`
--
ALTER TABLE `garson`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `gecicigarson`
--
ALTER TABLE `gecicigarson`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `gecicimasa`
--
ALTER TABLE `gecicimasa`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `geciciurun`
--
ALTER TABLE `geciciurun`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `masabakiye`
--
ALTER TABLE `masabakiye`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `masalar`
--
ALTER TABLE `masalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `mutfaksiparis`
--
ALTER TABLE `mutfaksiparis`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rapor`
--
ALTER TABLE `rapor`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yonetim`
--
ALTER TABLE `yonetim`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `anliksiparis`
--
ALTER TABLE `anliksiparis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- Tablo için AUTO_INCREMENT değeri `doluluk`
--
ALTER TABLE `doluluk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `garson`
--
ALTER TABLE `garson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Tablo için AUTO_INCREMENT değeri `gecicigarson`
--
ALTER TABLE `gecicigarson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `gecicimasa`
--
ALTER TABLE `gecicimasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `geciciurun`
--
ALTER TABLE `geciciurun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `masabakiye`
--
ALTER TABLE `masabakiye`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Tablo için AUTO_INCREMENT değeri `masalar`
--
ALTER TABLE `masalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `mutfaksiparis`
--
ALTER TABLE `mutfaksiparis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- Tablo için AUTO_INCREMENT değeri `rapor`
--
ALTER TABLE `rapor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=393;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Tablo için AUTO_INCREMENT değeri `yonetim`
--
ALTER TABLE `yonetim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
