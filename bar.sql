-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 20, 2021 alle 18:26
-- Versione del server: 10.4.16-MariaDB
-- Versione PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bar`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `account`
--

CREATE TABLE `account` (
  `IDlogin` int(10) UNSIGNED NOT NULL,
  `password` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `account`
--

INSERT INTO `account` (`IDlogin`, `password`) VALUES
(1, '827ccb0eea8a706c4c34a16891f84e7b'),
(10, '827ccb0eea8a706c4c34a16891f84e7b'),
(11, '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE `categorie` (
  `ID_categoria` int(11) UNSIGNED NOT NULL,
  `titolo` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `categorie`
--

INSERT INTO `categorie` (`ID_categoria`, `titolo`) VALUES
(1, 'panineria'),
(2, 'rosticceria'),
(3, 'bevande');

-- --------------------------------------------------------

--
-- Struttura della tabella `classi`
--

CREATE TABLE `classi` (
  `ID` int(2) UNSIGNED NOT NULL,
  `anno` int(1) UNSIGNED NOT NULL,
  `sezione` char(1) NOT NULL,
  `sede` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `classi`
--

INSERT INTO `classi` (`ID`, `anno`, `sezione`, `sede`) VALUES
(1, 5, 'a', 'succ. 2'),
(3, 5, 'b', 'succ. 2');

-- --------------------------------------------------------

--
-- Struttura della tabella `dettaglio_ordini`
--

CREATE TABLE `dettaglio_ordini` (
  `ID_ordine` int(11) UNSIGNED NOT NULL,
  `ID_prodotto` int(11) UNSIGNED NOT NULL,
  `quantita` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `dettaglio_ordini`
--

INSERT INTO `dettaglio_ordini` (`ID_ordine`, `ID_prodotto`, `quantita`) VALUES
(21, 6, 3),
(21, 7, 4),
(22, 7, 1),
(23, 1, 3),
(23, 7, 1),
(24, 1, 20),
(24, 5, 1),
(24, 6, 14),
(25, 6, 1),
(25, 7, 1),
(26, 7, 1),
(26, 9, 4),
(27, 7, 3),
(27, 9, 1),
(28, 6, 1),
(28, 7, 3),
(29, 5, 1),
(30, 7, 1),
(31, 7, 5),
(32, 6, 1),
(33, 7, 4),
(36, 7, 1),
(36, 10, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE `ordini` (
  `ID` int(11) UNSIGNED NOT NULL,
  `luogo_consegna` char(50) NOT NULL,
  `data_ora_ordine` datetime NOT NULL,
  `totale` decimal(6,2) UNSIGNED NOT NULL,
  `ID_studente` char(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ordini`
--

INSERT INTO `ordini` (`ID`, `luogo_consegna`, `data_ora_ordine`, `totale`, `ID_studente`) VALUES
(21, 'Classe', '2018-06-03 18:42:53', '12.60', 'prova@hotmail.it'),
(22, 'Palestra succ. 1', '2018-06-03 18:49:34', '1.80', 'prova@hotmail.it'),
(23, 'Lab. piano terra', '2018-06-03 19:48:58', '3.30', 'prova@hotmail.it'),
(24, 'Lab. piano terra', '2018-06-04 08:17:59', '36.40', 'prova@hotmail.it'),
(25, 'Classe', '2018-06-09 16:14:12', '3.60', 'prova@hotmail.it'),
(26, 'Classe', '2018-06-09 16:18:57', '9.00', 'prova@hotmail.it'),
(27, 'Classe', '2018-06-09 16:19:58', '7.20', 'prova@hotmail.it'),
(28, 'Classe', '2018-06-09 16:20:44', '7.20', 'prova@hotmail.it'),
(29, 'Lab. piano 1', '2018-06-09 16:22:53', '1.20', 'prova@hotmail.it'),
(30, 'Classe', '2018-06-09 16:26:24', '1.80', 'prova@hotmail.it'),
(31, 'Classe', '2018-06-09 16:27:47', '9.00', 'prova@hotmail.it'),
(32, 'Classe', '2018-06-09 16:29:46', '1.80', 'prova@hotmail.it'),
(33, 'Classe', '2018-06-09 16:30:54', '7.20', 'prova@hotmail.it'),
(36, 'Classe', '2021-01-20 17:45:25', '2.80', 'fra_1999_@hotmail.it');

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti`
--

CREATE TABLE `prodotti` (
  `ID` int(11) UNSIGNED NOT NULL,
  `nome` char(30) NOT NULL,
  `immagine` char(20) DEFAULT NULL,
  `prezzo` decimal(6,2) UNSIGNED NOT NULL,
  `disponibile` int(1) UNSIGNED NOT NULL,
  `in_produzione` int(1) UNSIGNED NOT NULL DEFAULT 1,
  `ID_categoria` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `prodotti`
--

INSERT INTO `prodotti` (`ID`, `nome`, `immagine`, `prezzo`, `disponibile`, `in_produzione`, `ID_categoria`) VALUES
(1, 'Acqua 500ml', 'naturale.png', '0.50', 1, 1, 3),
(2, 'Fanta 33cl', 'fanta.png', '0.80', 1, 1, 3),
(4, 'Pizza', '', '1.20', 1, 1, 2),
(5, 'Calzone', '', '1.20', 1, 1, 2),
(6, 'Panino prosciutto', '', '1.80', 1, 1, 1),
(7, 'Panino cotoletta', 'cotoletta.png', '1.80', 1, 1, 1),
(9, 'Panino salame piccante', NULL, '1.80', 1, 1, 1),
(10, 'Coca-cola', 'cocacola.png', '1.00', 1, 1, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `studenti`
--

CREATE TABLE `studenti` (
  `email` char(25) NOT NULL,
  `nome` char(20) NOT NULL,
  `cognome` char(20) NOT NULL,
  `ID_classe` int(2) UNSIGNED DEFAULT NULL,
  `IDlogin` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `studenti`
--

INSERT INTO `studenti` (`email`, `nome`, `cognome`, `ID_classe`, `IDlogin`) VALUES
('fra_1999_@hotmail.it', 'francesco', 'murolo', 3, 11),
('prova@hotmail.it', 'mario', 'rossi', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `titolari`
--

CREATE TABLE `titolari` (
  `email` char(25) NOT NULL,
  `nome` char(20) NOT NULL,
  `cognome` char(20) NOT NULL,
  `IDlogin` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dump dei dati per la tabella `titolari`
--

INSERT INTO `titolari` (`email`, `nome`, `cognome`, `IDlogin`) VALUES
('titolare@gmail.com', 'mario', 'rossi', 10);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`IDlogin`);

--
-- Indici per le tabelle `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`ID_categoria`);

--
-- Indici per le tabelle `classi`
--
ALTER TABLE `classi`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `num_sezione` (`anno`,`sezione`) USING BTREE;

--
-- Indici per le tabelle `dettaglio_ordini`
--
ALTER TABLE `dettaglio_ordini`
  ADD PRIMARY KEY (`ID_ordine`,`ID_prodotto`),
  ADD KEY `FK_dettaglio_ordini_prodotti` (`ID_prodotto`);

--
-- Indici per le tabelle `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_ordini_studenti` (`ID_studente`);

--
-- Indici per le tabelle `prodotti`
--
ALTER TABLE `prodotti`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_prodotti_categorie` (`ID_categoria`);

--
-- Indici per le tabelle `studenti`
--
ALTER TABLE `studenti`
  ADD PRIMARY KEY (`email`),
  ADD KEY `FK_studenti_classi` (`ID_classe`),
  ADD KEY `FK_studenti_account` (`IDlogin`);

--
-- Indici per le tabelle `titolari`
--
ALTER TABLE `titolari`
  ADD PRIMARY KEY (`email`) USING BTREE,
  ADD KEY `FK_titolari_account` (`IDlogin`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `account`
--
ALTER TABLE `account`
  MODIFY `IDlogin` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `categorie`
--
ALTER TABLE `categorie`
  MODIFY `ID_categoria` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `classi`
--
ALTER TABLE `classi`
  MODIFY `ID` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ordini`
--
ALTER TABLE `ordini`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT per la tabella `prodotti`
--
ALTER TABLE `prodotti`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `dettaglio_ordini`
--
ALTER TABLE `dettaglio_ordini`
  ADD CONSTRAINT `FK_dettaglio_ordini_ordini` FOREIGN KEY (`ID_ordine`) REFERENCES `ordini` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_dettaglio_ordini_prodotti` FOREIGN KEY (`ID_prodotto`) REFERENCES `prodotti` (`ID`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `FK_ordini_studenti` FOREIGN KEY (`ID_studente`) REFERENCES `studenti` (`email`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `prodotti`
--
ALTER TABLE `prodotti`
  ADD CONSTRAINT `FK_prodotti_categorie` FOREIGN KEY (`ID_categoria`) REFERENCES `categorie` (`ID_categoria`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `studenti`
--
ALTER TABLE `studenti`
  ADD CONSTRAINT `FK_studenti_account` FOREIGN KEY (`IDlogin`) REFERENCES `account` (`IDlogin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_studenti_classi` FOREIGN KEY (`ID_classe`) REFERENCES `classi` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `titolari`
--
ALTER TABLE `titolari`
  ADD CONSTRAINT `FK_titolari_account` FOREIGN KEY (`IDlogin`) REFERENCES `account` (`IDlogin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
