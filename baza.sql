-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Maj 27, 2026 at 10:25 AM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `infocar`
--
DROP DATABASE IF EXISTS `infocar`;
CREATE DATABASE IF NOT EXISTS `infocar` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `infocar`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `terminy_egzaminow`
--

DROP TABLE IF EXISTS `terminy_egzaminow`;
CREATE TABLE IF NOT EXISTS `terminy_egzaminow` (
  `id_terminu` int(11) NOT NULL AUTO_INCREMENT,
  `data_godzina` datetime NOT NULL,
  `miejsce` varchar(100) DEFAULT NULL,
  `id_egzaminatora` int(11) DEFAULT NULL,
  `max_osob` int(11) DEFAULT 1,
  PRIMARY KEY (`id_terminu`),
  KEY `id_egzaminatora` (`id_egzaminatora`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terminy_egzaminow`
--

INSERT INTO `terminy_egzaminow` (`id_terminu`, `data_godzina`, `miejsce`, `id_egzaminatora`, `max_osob`) VALUES
(1, '2026-05-10 08:00:00', 'Sala 101', 3, 1),
(2, '2026-05-12 10:30:00', 'Sala 102', 3, 1),
(3, '2026-06-01 09:00:00', 'Sala 101', 4, 1),
(4, '2026-06-05 12:00:00', 'Sala 105', 4, 1),
(5, '2026-06-10 14:00:00', 'Sala 101', 3, 1),
(6, '6767-07-06 06:59:00', 'Husarz', 3, 1),
(7, '6767-07-06 06:59:00', 'Husarz', 3, 1),
(8, '6767-07-06 06:59:00', 'Husarz', 3, 1),
(9, '2026-05-08 09:31:00', 'husqrz', 3, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

DROP TABLE IF EXISTS `uzytkownicy`;
CREATE TABLE IF NOT EXISTS `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT,
  `imie` varchar(50) DEFAULT NULL,
  `nazwisko` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `rola` enum('uzytkownik','egzaminator','sekretarz') DEFAULT 'uzytkownik',
  PRIMARY KEY (`id_uzytkownika`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id_uzytkownika`, `imie`, `nazwisko`, `email`, `rola`) VALUES
(1, 'Marek', 'Kowalski', 'marek.k@example.com', 'uzytkownik'),
(2, 'Anna', 'Nowak', 'a.nowak@example.com', 'uzytkownik'),
(3, 'Robert', 'Egzaminatorski', 'robert.e@word.pl', 'egzaminator'),
(4, 'Janusz', 'Srogi', 'janusz.s@word.pl', 'egzaminator'),
(5, 'Halina', 'Biuro', 'halinka@word.pl', 'sekretarz');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zapisy`
--

DROP TABLE IF EXISTS `zapisy`;
CREATE TABLE IF NOT EXISTS `zapisy` (
  `id_zapisu` int(11) NOT NULL AUTO_INCREMENT,
  `id_uzytkownika` int(11) DEFAULT NULL,
  `id_terminu` int(11) DEFAULT NULL,
  `wynik` enum('pozytywny','negatywny','nieobecny','oczekuje') DEFAULT 'oczekuje',
  `punkty` int(11) DEFAULT NULL,
  `data_zapisu` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_zapisu`),
  KEY `id_uzytkownika` (`id_uzytkownika`),
  KEY `id_terminu` (`id_terminu`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zapisy`
--

INSERT INTO `zapisy` (`id_zapisu`, `id_uzytkownika`, `id_terminu`, `wynik`, `punkty`, `data_zapisu`) VALUES
(1, 1, 1, 'negatywny', 45, '2026-05-13 07:43:45'),
(2, 1, 2, 'pozytywny', 72, '2026-05-13 07:43:45'),
(3, 2, 3, 'oczekuje', NULL, '2026-05-13 07:43:45'),
(4, 1, 4, 'negatywny', NULL, '2026-05-13 07:59:15'),
(5, 1, 5, 'negatywny', NULL, '2026-05-13 07:59:39'),
(6, 1, 5, 'negatywny', NULL, '2026-05-13 08:01:50'),
(7, 1, 6, 'oczekuje', NULL, '2026-05-13 08:15:48'),
(8, 1, 7, 'negatywny', NULL, '2026-05-13 08:15:50'),
(9, 1, 8, 'oczekuje', NULL, '2026-05-13 08:15:51'),
(10, 1, 9, 'oczekuje', NULL, '2026-05-19 07:31:45');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `terminy_egzaminow`
--
ALTER TABLE `terminy_egzaminow`
  ADD CONSTRAINT `terminy_egzaminow_ibfk_1` FOREIGN KEY (`id_egzaminatora`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Constraints for table `zapisy`
--
ALTER TABLE `zapisy`
  ADD CONSTRAINT `zapisy_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `zapisy_ibfk_2` FOREIGN KEY (`id_terminu`) REFERENCES `terminy_egzaminow` (`id_terminu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
