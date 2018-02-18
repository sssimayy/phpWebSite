-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 18 Şub 2018, 09:30:18
-- Sunucu sürümü: 5.7.14
-- PHP Sürümü: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `website`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `adminId` int(11) NOT NULL,
  `adminName` varchar(30) NOT NULL,
  `adminPass` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`adminId`, `adminName`, `adminPass`) VALUES
(1, 'simay', 'simay');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `basket`
--

CREATE TABLE `basket` (
  `basketId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `piece` int(11) NOT NULL,
  `situation` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `basket`
--

INSERT INTO `basket` (`basketId`, `userId`, `productId`, `piece`, `situation`) VALUES
(1, 4, 10, 1, 1),
(2, 4, 10, 1, 1),
(3, 5, 8, 1, 1),
(4, 5, 7, 1, 1),
(5, 5, 6, 1, 1),
(6, 3, 10, 1, 1),
(7, 3, 9, 1, 1),
(8, 3, 10, 1, 1),
(9, 3, 7, 1, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product`
--

CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `product` varchar(30) NOT NULL,
  `price` double NOT NULL,
  `piece` int(11) NOT NULL,
  `productPic` varchar(100) DEFAULT NULL,
  `typee` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `product`
--

INSERT INTO `product` (`productId`, `product`, `price`, `piece`, `productPic`, `typee`) VALUES
(1, 'car1', 5000, 10, 'products/car1.png', 'car'),
(2, 'car2', 7000, 8, 'products/car2.jpg', 'car'),
(3, 'phone1', 700, 5, 'products/phone1.png', 'phone'),
(4, 'phone2', 500, 4, 'products/phone2.jpg', 'phone'),
(5, 'saat1', 5000, 2, 'products/saat1.jpg', 'saat'),
(6, 'saat2', 5000, 1, 'products/saat2.jpg', 'saat'),
(7, 'computer1', 350, 2, 'products/computer1.png', 'computer'),
(8, 'computer2', 450, 6, 'products/computer2.jpg', 'computer'),
(9, 'ha1', 200, 1, 'products/ha1.jpg', 'ha'),
(10, 'ha2', 150, 2, 'products/ha2.jpg', 'ha');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `passwrd` varchar(30) NOT NULL,
  `coin` double NOT NULL DEFAULT '10000',
  `email` varchar(30) NOT NULL,
  `picture` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`userId`, `userName`, `passwrd`, `coin`, `email`, `picture`) VALUES
(1, 'Simay', '555a', 10000, 'simay@gmail.com', NULL),
(2, 'asd', 'asd', 10000, 'asd@asd.com', NULL),
(3, 'QQQ', 'QQQ', 9500, 'QQQ@QQQ.com', 'profile_pics/QQQ.jpg'),
(4, 'HJK', 'HJK', 10000, 'HKQ@jhasd.com', 'profile_pics/HJK.png'),
(5, 'SSS', 'SSS', 10000, 'SSS@ss.com', NULL),
(6, 'simayy', 'smy', 10000, 'smy@gmail.com', NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Tablo için indeksler `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`basketId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `productId` (`productId`);

--
-- Tablo için indeksler `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Tablo için AUTO_INCREMENT değeri `basket`
--
ALTER TABLE `basket`
  MODIFY `basketId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Tablo için AUTO_INCREMENT değeri `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
