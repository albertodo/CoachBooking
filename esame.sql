-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 29, 2018 alle 08:51
-- Versione del server: 10.1.32-MariaDB
-- Versione PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esame`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `email` text NOT NULL,
  `password` text NOT NULL,
  `richiesto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`email`, `password`, `richiesto`) VALUES
('u1@p.it', 'ec6ef230f1828039ee794566b9c58adc', 'si'),
('u2@p.it', '1d665b9b1467944c128a5575119d1cfd', 'si'),
('u3@p.it', '7bc3ca68769437ce986455407dab2a1f', 'si'),
('u4@p.it', '13207e3d5722030f6c97d69b4904d39d', 'si');

-- --------------------------------------------------------

--
-- Struttura della tabella `viaggi`
--

CREATE TABLE `viaggi` (
  `partenza` text NOT NULL,
  `arrivo` text NOT NULL,
  `utente` text NOT NULL,
  `passeggeri` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `viaggi`
--

INSERT INTO `viaggi` (`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES
('DD', 'EE', 'u2@p.it', 1),
('BB', 'DD', 'u2@p.it', 1),
('DD', 'EE', 'u3@p.it', 1),
('AL', 'BB', 'u4@p.it', 1),
('BB', 'DD', 'u4@p.it', 1),
('FF', 'KK', 'u1@p.it', 4);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
