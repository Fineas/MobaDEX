-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Gazdă: db
-- Timp de generare: nov. 30, 2020 la 03:32 PM
-- Versiune server: 8.0.21
-- Versiune PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `mobadex_db`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `Friends`
--

CREATE TABLE `Friends` (
  `user` varchar(40) NOT NULL,
  `friend` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Eliminarea datelor din tabel `Friends`
--

INSERT INTO `Friends` (`user`, `friend`) VALUES
('User1', 'gigi3');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `Mobs`
--

CREATE TABLE `Mobs` (
  `Mob_From` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Mob_To` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Mob_Data` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Mob_Status` tinyint(1) NOT NULL,
  `Moba_ID` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Eliminarea datelor din tabel `Mobs`
--

INSERT INTO `Mobs` (`Mob_From`, `Mob_To`, `Mob_Data`, `Mob_Status`, `Moba_ID`) VALUES
('User1', 'gigi3', 'CEAU BAIETE', 0, 1),
('User1', 'gigi3', 'CEAU BAIETE', 0, 2),
('User1', 'gigi3', 'MESAJ secret xoxo', 0, 3);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `Users`
--

CREATE TABLE `Users` (
  `Username` varchar(30) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Token` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Eliminarea datelor din tabel `Users`
--

INSERT INTO `Users` (`Username`, `Email`, `Password`, `Token`) VALUES
('User1', 'user@test.com', 'user1', 'XXXXXXXX'),
('gigi', 'gigi@email.com', 'ey_gigi', '930933894456870'),
('gigi2', 'gigi@email.com', 'ey_gigi', '445225140987538'),
('gigi3', 'gigi@email.com', '$2y$10$LzHgl0NesW67s03ikr3f7.NEaJ4FAZX/BSEwsOyKgoBANzVVBLsAe', '237142612385468'),
('numelelele', 'gigi@email.com', '$2y$10$NhKwILd2EnZUkZlCCFp.JeX00x3e.5Cnl6gH0UVyYwZZBp63qcSIy', '639121178257713'),
('xxxxx', 'blabla@qwwe.com', '$2y$10$pNcojeTCcBh343iaGN721eBSQPt4j3FDSLvjaeThj8FZnRz48rKxC', '318317081760960');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `Mobs`
--
ALTER TABLE `Mobs`
  ADD PRIMARY KEY (`Moba_ID`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `Mobs`
--
ALTER TABLE `Mobs`
  MODIFY `Moba_ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
