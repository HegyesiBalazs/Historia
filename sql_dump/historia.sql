-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Ápr 18. 10:12
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `historia`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jegyzetek`
--

CREATE TABLE `jegyzetek` (
  `id` int(11) NOT NULL,
  `reg_email` varchar(100) NOT NULL,
  `jegyzet` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jelszomodosit`
--

CREATE TABLE `jelszomodosit` (
  `id` int(11) NOT NULL,
  `kod` varchar(8) DEFAULT NULL,
  `uj_jelszo` varchar(100) DEFAULT NULL,
  `kod_lejar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `regisztralas`
--

CREATE TABLE `regisztralas` (
  `id` int(11) NOT NULL,
  `keresztnev` varchar(100) NOT NULL,
  `vezeteknev` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `jelszo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `regisztralas`
--

INSERT INTO `regisztralas` (`id`, `keresztnev`, `vezeteknev`, `email`, `jelszo`) VALUES
(1, 'Balázs', 'Hegyesi', '72450655207@gmail.com', '$2y$10$CHOPQszah54MLxSl2EiykulwYX/rFBAD99S30NmBsxQqNMJlIm0/K'),
(2, 'Csongi', 'Gösser', 'csongi@szily.hu', '$2y$10$RZoipFQFYvJy0tfOkMIg.O3JEt0T6qlWVHwBV5PpBhS3kpWyc1RFC'),
(3, 'Kerekes', 'Pisti', 'adonisz@szily.hu', '$2y$10$D7KPhjs.oZnYivsNh2olLeZi1jD9G6UDNYJYOZwjWE9pRdZY4We9C'),
(4, 'Elek', 'Teszt', 'barmi@gmail.com', '$2y$10$NmZqZLdJlx5xEMgX4WQ5We.j3igIWZPVJhvVY4D28Cjv.ARRG.HyG');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `jegyzetek`
--
ALTER TABLE `jegyzetek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reg_email` (`reg_email`);

--
-- A tábla indexei `jelszomodosit`
--
ALTER TABLE `jelszomodosit`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `regisztralas`
--
ALTER TABLE `regisztralas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `jegyzetek`
--
ALTER TABLE `jegyzetek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jelszomodosit`
--
ALTER TABLE `jelszomodosit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `regisztralas`
--
ALTER TABLE `regisztralas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `jegyzetek`
--
ALTER TABLE `jegyzetek`
  ADD CONSTRAINT `jegyzetek_ibfk_1` FOREIGN KEY (`reg_email`) REFERENCES `regisztralas` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
