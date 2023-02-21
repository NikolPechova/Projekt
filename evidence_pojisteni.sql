-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 21. úno 2023, 23:33
-- Verze serveru: 10.4.24-MariaDB
-- Verze PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `evidence_pojisteni`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `pojistenci`
--

CREATE TABLE `pojistenci` (
  `pojistenci_id` int(11) NOT NULL,
  `jmeno` varchar(60) NOT NULL,
  `prijmeni` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `telefon` varchar(16) NOT NULL,
  `ulice` varchar(60) NOT NULL,
  `cislo_popisne` varchar(10) NOT NULL,
  `mesto` varchar(60) NOT NULL,
  `psc` varchar(6) NOT NULL,
  `heslo` varchar(60) DEFAULT NULL,
  `admin` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `pojistenci`
--

INSERT INTO `pojistenci` (`pojistenci_id`, `jmeno`, `prijmeni`, `email`, `telefon`, `ulice`, `cislo_popisne`, `mesto`, `psc`, `heslo`, `admin`) VALUES
(1, 'Nikol', 'Pechová', 'nikky@outlook.cz', '+420736704736', 'Bělidla', '1106', 'Bystřice pod Hostýnem', '768 61', '$2y$10$Q.uBD8WbdHAvBEO9/dC5qeEO4xRCWWdMJ0zXQOyx7ynTrAzylK00W', 1),
(2, 'David', 'Hrdlička', 'mauslinux@outlook.com', '+420774841524', 'Bělidla', '1106', 'Bystřice pod Hostýnem', '768 61', '$2y$10$4F4u8QTxLrrJxBd/f0UdC.F4tROolo8WNQuCv.Nl8anPCEQrEI/Ya', 0),
(3, 'Nikol', 'Hrdličková', 'pechova.nikki@seznam.cz', '+420736704736', 'Bělidla', '1106', 'Bystřice pod Hostýnem', '768 61', '$2y$10$9T8238C8LTRw63OUu0Dtu.Vja4uhg4tT4o/zF.n6aH7lE3sBqxqTy', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pojisteni`
--

CREATE TABLE `pojisteni` (
  `pojisteni_id` int(11) NOT NULL,
  `pojistenci_id` int(11) NOT NULL,
  `nazev` varchar(60) NOT NULL,
  `castka` int(10) NOT NULL,
  `predmet` varchar(60) NOT NULL,
  `platnost_od` date NOT NULL,
  `platnost_do` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `pojisteni`
--

INSERT INTO `pojisteni` (`pojisteni_id`, `pojistenci_id`, `nazev`, `castka`, `predmet`, `platnost_od`, `platnost_do`) VALUES
(1, 1, 'Pojištění mazlíčků', 20000, 'Agama', '2023-02-01', '2024-01-31'),
(2, 2, 'Pojištění odpovědnosti', 50000, 'Na blbost', '2023-02-01', '2024-08-31'),
(3, 2, 'Pojištění majetku', 2400000, 'Byt', '2022-01-01', '2023-01-31');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `pojistenci`
--
ALTER TABLE `pojistenci`
  ADD PRIMARY KEY (`pojistenci_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexy pro tabulku `pojisteni`
--
ALTER TABLE `pojisteni`
  ADD PRIMARY KEY (`pojisteni_id`),
  ADD KEY `pojistenipojistenci` (`pojistenci_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `pojistenci`
--
ALTER TABLE `pojistenci`
  MODIFY `pojistenci_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `pojisteni`
--
ALTER TABLE `pojisteni`
  MODIFY `pojisteni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `pojisteni`
--
ALTER TABLE `pojisteni`
  ADD CONSTRAINT `pojistenipojistenci` FOREIGN KEY (`pojistenci_id`) REFERENCES `pojistenci` (`pojistenci_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
