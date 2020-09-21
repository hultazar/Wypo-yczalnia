-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 07 Cze 2017, 14:21
-- Wersja serwera: 5.6.26
-- Wersja PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `wyporzyczalnia`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `administratorzy`
--

CREATE TABLE IF NOT EXISTS `administratorzy` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `haslo` char(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `administratorzy`
--

INSERT INTO `administratorzy` (`id`, `login`, `haslo`) VALUES
(1, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `filmy`
--

CREATE TABLE IF NOT EXISTS `filmy` (
  `id_filmu` int(11) NOT NULL,
  `tytul` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `rok_wydania` date NOT NULL,
  `rodzaj` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `filmy`
--

INSERT INTO `filmy` (`id_filmu`, `tytul`, `rok_wydania`, `rodzaj`) VALUES
(1, 'Sami Swoi', '2017-06-07', 'Komedia'),
(2, 'Jak rozpentalem II wojne', '2017-06-07', 'Komedia'),
(3, 'Och Karol 2', '2017-06-07', 'Komedia'),
(4, 'Zróbmy sobie wnuka', '2017-06-07', 'Komedia'),
(5, 'Zejscie', '2017-06-07', 'Horror'),
(6, 'Wzgórza maja oczy', '2017-06-07', 'Horror'),
(7, 'Pirania 3D', '2017-06-07', 'Horror'),
(8, 'Ostatni dom po lewej', '2017-06-07', 'Horror'),
(9, 'Przełęcz Ocalonych', '2017-06-07', 'Wojenne'),
(10, 'Rambo', '2017-06-07', 'Wojenne'),
(11, 'Karbala', '2017-06-07', 'Wojenne'),
(12, 'Król Lew', '2017-06-07', 'Bajki'),
(13, 'Shrek', '2017-06-07', 'Bajki'),
(14, 'Piekna i Bestia', '2017-06-07', 'Bajki'),
(15, 'Tarzan', '2017-06-07', 'Bajki'),
(16, 'Hobbit I', '2017-06-07', 'Przygodowe'),
(17, 'Hobbit II', '2017-06-07', 'Przygodowe'),
(18, 'Hobbit III', '2017-06-07', 'Przygodowe'),
(19, 'Ksiega Ocalenia', '2017-06-07', 'Przygodowe'),
(20, 'Harry Potter', '2017-06-07', 'Przygodowe'),
(21, 'W pustyni i w puszczy', '2017-06-07', 'Przygodowe');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klienci`
--

CREATE TABLE IF NOT EXISTS `klienci` (
  `id_klienta` int(11) NOT NULL,
  `login` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `haslo` char(64) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `data_urodzenia` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `klienci`
--

INSERT INTO `klienci` (`id_klienta`, `login`, `haslo`, `email`, `data_urodzenia`) VALUES
(1, 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', 'test@test.com', '2017-06-07'),
(2, 'alamakota', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'aaa@example.com', '2017-06-16');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wypozyczenia`
--

CREATE TABLE IF NOT EXISTS `wypozyczenia` (
  `id_wypozyczenia` int(11) NOT NULL,
  `data_startu_wypoz` date NOT NULL,
  `data_konca_wyporzyczenia` date NOT NULL,
  `id_klienta` int(11) NOT NULL,
  `id_filmu` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `administratorzy`
--
ALTER TABLE `administratorzy`
  ADD PRIMARY KEY (`id`,`login`);

--
-- Indexes for table `filmy`
--
ALTER TABLE `filmy`
  ADD PRIMARY KEY (`id_filmu`);

--
-- Indexes for table `klienci`
--
ALTER TABLE `klienci`
  ADD PRIMARY KEY (`id_klienta`);

--
-- Indexes for table `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  ADD PRIMARY KEY (`id_wypozyczenia`),
  ADD KEY `id_klienta` (`id_klienta`),
  ADD KEY `id_filmu` (`id_filmu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `administratorzy`
--
ALTER TABLE `administratorzy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `filmy`
--
ALTER TABLE `filmy`
  MODIFY `id_filmu` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT dla tabeli `klienci`
--
ALTER TABLE `klienci`
  MODIFY `id_klienta` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  MODIFY `id_wypozyczenia` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  ADD CONSTRAINT `wypozyczenia_filmy_FK` FOREIGN KEY (`id_filmu`) REFERENCES `filmy` (`id_filmu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wypozyczenia_klienci_FK` FOREIGN KEY (`id_klienta`) REFERENCES `klienci` (`id_klienta`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
