-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Ago 26, 2012 alle 17:06
-- Versione del server: 5.5.24-log
-- Versione PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fantamanajer-vuoto`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo`
--

CREATE TABLE IF NOT EXISTS `articolo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sottoTitolo` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `testo` text COLLATE utf8_unicode_ci NOT NULL,
  `dataCreazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUtente` int(11) NOT NULL,
  `idGiornata` int(11) NOT NULL,
  `idLega` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idSquadra` (`idUtente`),
  KEY `idGiornata` (`idGiornata`),
  KEY `idLega` (`idLega`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `articolo`
--

INSERT INTO `articolo` (`id`, `titolo`, `sottoTitolo`, `testo`, `dataCreazione`, `idUtente`, `idGiornata`, `idLega`) VALUES
(1, 'test', NULL, 'aa', '2012-08-24 11:52:48', 2, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `calendario`
--

CREATE TABLE IF NOT EXISTS `calendario` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idLega` int(11) NOT NULL,
  `idGiornata` int(11) NOT NULL,
  `idHome` int(11) NOT NULL,
  `idAway` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `club`
--

CREATE TABLE IF NOT EXISTS `club` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `partitivo` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'del',
  `determinativo` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'il',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dump dei dati per la tabella `club`
--

INSERT INTO `club` (`id`, `nome`, `partitivo`, `determinativo`) VALUES
(1, 'Atalanta', 'dell''', 'l'''),
(2, 'Bologna', 'del', 'il'),
(3, 'Cagliari', 'del', 'il'),
(5, 'Cesena', 'del', 'il'),
(6, 'Chievo', 'del', 'il'),
(7, 'Fiorentina', 'della', 'la'),
(8, 'Genoa', 'del', 'il'),
(9, 'Inter', 'dell''', 'l'''),
(10, 'Juventus', 'della', 'la'),
(11, 'Lazio', 'della', 'la'),
(12, 'Lecce', 'del', 'il'),
(13, 'Milan', 'del', 'il'),
(14, 'Napoli', 'del', 'il'),
(15, 'Novara', 'del', 'il'),
(16, 'Palermo', 'del', 'il'),
(17, 'Parma', 'del', 'il'),
(18, 'Roma', 'della', 'la'),
(19, 'Siena', 'del', 'il'),
(20, 'Udinese', 'dell''', 'l''');

-- --------------------------------------------------------

--
-- Struttura della tabella `evento`
--

CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idUtente` int(11) DEFAULT NULL,
  `idLega` int(11) DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` tinyint(4) NOT NULL,
  `idExternal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idExternal` (`idExternal`),
  KEY `idUtente` (`idUtente`),
  KEY `idLega` (`idLega`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `evento`
--

INSERT INTO `evento` (`id`, `idUtente`, `idLega`, `data`, `tipo`, `idExternal`) VALUES
(1, 2, 1, '2012-08-24 11:52:48', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `formazione`
--

CREATE TABLE IF NOT EXISTS `formazione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idGiornata` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `modulo` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `idCapitano` int(11) DEFAULT NULL,
  `idVCapitano` int(11) DEFAULT NULL,
  `idVVCapitano` int(11) DEFAULT NULL,
  `jolly` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `C` (`idCapitano`),
  KEY `VC` (`idVCapitano`),
  KEY `VVC` (`idVVCapitano`),
  KEY `idGiornata` (`idGiornata`),
  KEY `idUtente` (`idUtente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `formazione`
--

INSERT INTO `formazione` (`id`, `idGiornata`, `idUtente`, `modulo`, `idCapitano`, `idVCapitano`, `idVVCapitano`, `jolly`) VALUES
(1, 1, 2, '1-4-3-3', 309, 257, 223, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `giocatore`
--

CREATE TABLE IF NOT EXISTS `giocatore` (
  `id` int(11) NOT NULL,
  `nome` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cognome` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `ruolo` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `idClub` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `club` (`idClub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `giocatore`
--

INSERT INTO `giocatore` (`id`, `nome`, `cognome`, `ruolo`, `idClub`, `status`) VALUES
(101, 'Christian', 'Abbiati', 'P', 13, 1),
(102, 'Micheal', 'Agazzi', 'P', 3, 1),
(103, 'Federico', 'Agliardi', 'P', 2, 1),
(104, 'Marco', 'Amelia', 'P', 13, 1),
(105, 'Mariano Gonzalo', 'Andujar', 'P', NULL, 1),
(106, 'Francesco', 'Antonioli', 'P', 5, 1),
(107, 'Vlada', 'Avramov', 'P', 3, 1),
(108, 'Emanuele', 'Belardi', 'P', 20, 1),
(109, 'Massimiliano', 'Benassi', 'P', 12, 1),
(110, 'Francesco', 'Benussi', 'P', 16, 1),
(111, 'Benjamin', 'Bizzarri', 'P', 11, 1),
(112, 'Artur', 'Boruc', 'P', 7, 1),
(113, 'Zeljko', 'Brkic', 'P', 19, 1),
(114, 'Gianluigi', 'Buffon', 'P', 10, 1),
(115, 'Andrea', 'Campagnolo', 'P', NULL, 1),
(116, 'Luca', 'Castellazzi', 'P', 9, 1),
(117, 'Roberto', 'Colombo', 'P', 14, 1),
(118, 'Andrea', 'Consigli', 'P', 1, 1),
(119, 'Achille', 'Coser', 'P', 15, 1),
(120, 'Gianluca', 'Curci', 'P', 18, 1),
(121, 'Morgan', 'De Sanctis', 'P', 14, 1),
(122, 'Simone', 'Farelli', 'P', 19, 1),
(123, 'Alberto', 'Fontana', 'P', 15, 1),
(124, 'Sebastian', 'Frey', 'P', 8, 1),
(125, 'Giorgio', 'Frezzolini', 'P', 1, 1),
(126, 'Jean Francois', 'Gillet', 'P', 2, 1),
(127, 'Samir', 'Handanovic', 'P', 20, 1),
(128, 'Soares De Espinola', 'Julio Cesar', 'P', 9, 1),
(129, 'Bertagnolli', 'Julio Sergio', 'P', 12, 1),
(130, 'Tomas', 'Kosicky', 'P', NULL, 1),
(131, 'Bogdan', 'Lobont', 'P', 18, 1),
(132, 'Cristiano', 'Lupatelli', 'P', 8, 1),
(133, 'Alexander', 'Manninger', 'P', 10, 1),
(134, 'Federico', 'Marchetti', 'P', 11, 1),
(135, 'Antonio', 'Mirante', 'P', 17, 1),
(136, NULL, 'Neto', 'P', 7, 1),
(137, 'Paolo', 'Orlandoni', 'P', 9, 1),
(138, 'Nicola', 'Pavarini', 'P', 17, 1),
(139, 'Gianluca', 'Pegolo', 'P', 19, 1),
(140, 'Davide', 'Petrachi', 'P', 12, 1),
(141, 'Antonio', 'Rosati', 'P', 14, 1),
(142, 'Alessio', 'Scarpi', 'P', 8, 1),
(143, 'Aldo', 'Simoncini', 'P', 5, 1),
(144, 'Salvatore', 'Sirigu', 'P', 16, 0),
(145, 'Stefano', 'Sorrentino', 'P', 6, 1),
(146, 'Lorenzo', 'Squizzi', 'P', 6, 1),
(147, 'Maarten', 'Stekelenburg', 'P', 18, 1),
(148, 'Marco', 'Storari', 'P', 10, 1),
(149, 'Samir', 'Ujkani', 'P', 15, 1),
(150, 'Mauro', 'Vigorito', 'P', 3, 1),
(151, 'Emiliano', 'Viviano', 'P', 8, 1),
(152, 'Nicola', 'Ravaglia', 'P', 5, 1),
(153, 'Jacopo', 'Coletta', 'P', 6, 1),
(154, 'Marcos', 'Miranda', 'P', 7, 0),
(155, 'Alessandro', 'Berardi', 'P', 11, 1),
(156, 'Flavio', 'Roma', 'P', 13, 1),
(157, NULL, 'Rubinho', 'P', 16, 1),
(158, 'Alberto', 'Gallinetta', 'P', 17, 1),
(159, 'Jan', 'Koprivec', 'P', 20, 0),
(160, 'Christian', 'Puggioni', 'P', 6, 1),
(161, 'Alexandros', 'Tzorvas', 'P', 16, 1),
(162, 'Daniele', 'Padelli', 'P', 20, 1),
(163, 'Edoardo', 'Pazzagli', 'P', 7, 1),
(201, 'Ignazio', 'Abate', 'D', 13, 1),
(202, 'Francesco', 'Acerbi', 'D', 6, 1),
(203, 'Alessandro', 'Agostini', 'D', 3, 1),
(204, 'Pablo Sebastian', 'Alvarez', 'D', NULL, 1),
(205, 'Sinisa', 'Andelkovic', 'D', 16, 0),
(206, 'Marco', 'Andreolli', 'D', 6, 1),
(207, 'Gabriele', 'Angella', 'D', 19, 1),
(208, 'Mariano De Almeida', 'Angelo', 'D', 19, 1),
(209, 'Luca', 'Antonelli', 'D', 8, 1),
(210, 'Luca', 'Antonini', 'D', 13, 1),
(211, 'Mikael', 'Antonsson', 'D', 2, 1),
(212, 'Lorenzo', 'Ariaudo', 'D', 3, 1),
(213, 'Salvatore', 'Aronica', 'D', 14, 1),
(214, 'Davide', 'Astori', 'D', 3, 1),
(215, 'Blazej', 'Augustyn', 'D', NULL, 0),
(216, 'Federico', 'Balzaretti', 'D', 16, 1),
(217, 'Andrea', 'Barzagli', 'D', 10, 1),
(218, 'Dusan', 'Basta', 'D', 20, 1),
(219, 'Gianpaolo', 'Bellini', 'D', 1, 1),
(220, 'Giuseppe', 'Bellusci', 'D', NULL, 1),
(221, 'Nicola', 'Belmonte', 'D', 19, 1),
(222, 'Yohan', 'Benalouane', 'D', 5, 1),
(223, 'Mehdi', 'Benatia', 'D', 20, 1),
(224, 'Giuseppe', 'Biava', 'D', 11, 1),
(225, 'Daniele', 'Bonera', 'D', 13, 1),
(226, 'Leonardo', 'Bonucci', 'D', 10, 1),
(227, 'Cesare', 'Bovo', 'D', 8, 1),
(228, 'Goncalo Jardim', 'Brandao', 'D', 17, 1),
(229, 'Miguel Angel', 'Britos', 'D', 14, 1),
(230, 'Davide', 'Brivio', 'D', 12, 1),
(231, 'Nicolas Andres', 'Burdisso', 'D', 18, 1),
(232, 'Luca', 'Caldirola', 'D', 9, 1),
(233, 'Hugo', 'Campagnaro', 'D', 14, 1),
(234, 'Michele', 'Camporese', 'D', 7, 1),
(235, 'Michele', 'Canini', 'D', 3, 1),
(236, 'Paolo', 'Cannavaro', 'D', 14, 1),
(237, 'Daniele', 'Capelli', 'D', 1, 1),
(238, 'Ciro', 'Capuano', 'D', NULL, 1),
(239, 'Moris', 'Carrozzieri', 'D', 12, 1),
(240, 'Mattia', 'Cassani', 'D', 7, 1),
(241, 'Marco', 'Cassetti', 'D', 18, 1),
(242, 'Luis Pedro', 'Cavanda', 'D', 11, 1),
(243, 'Luca', 'Ceccarelli', 'D', 5, 1),
(244, 'Matteo', 'Centurioni', 'D', 15, 1),
(245, 'Bostjan', 'Cesar', 'D', 6, 1),
(246, 'Mauro Dario Jesus', 'Cetto', 'D', 16, 1),
(247, 'Nicolo', 'Cherubin', 'D', 2, 1),
(248, 'Jose Manuel', 'Chico', 'D', 8, 0),
(249, 'Giorgio', 'Chiellini', 'D', 10, 1),
(250, 'Cristian', 'Chivu', 'D', 9, 1),
(251, 'Joao De Cezare', 'Cicinho', 'D', 18, 1),
(252, 'Andrea', 'Coda', 'D', 20, 1),
(253, 'Gianluca', 'Comotto', 'D', 5, 1),
(254, 'Ivan Ramiro', 'Cordoba', 'D', 9, 1),
(255, 'Alessandro', 'Crescenzi', 'D', 18, 0),
(256, 'Jose Angel Rincon', 'Crespo', 'D', 2, 1),
(257, 'Dario', 'Dainelli', 'D', 8, 1),
(258, 'Larangeira', 'Danilo', 'D', 20, 1),
(259, 'Paolo', 'De Ceglie', 'D', 10, 1),
(260, 'Lorenzo', 'De Silvestri', 'D', 7, 1),
(261, 'Cristiano', 'Del Grosso', 'D', 19, 1),
(262, 'Lorenzo', 'Del Prete', 'D', 19, 0),
(263, 'Mobido', 'Diakite''', 'D', 11, 1),
(264, 'Souleymane', 'Diamoutene', 'D', 12, 1),
(265, 'Andre', 'Dias', 'D', 11, 1),
(266, 'Maurizio', 'Domizzi', 'D', 20, 1),
(267, 'Joel', 'Ekstrand', 'D', 20, 1),
(268, 'Andrea', 'Esposito', 'D', 12, 1),
(269, 'Ivan', 'Fatic', 'D', 6, 0),
(270, 'Da Silva Dalbelo Dias', 'Felipe', 'D', 7, 1),
(271, 'Rolf', 'Feltscher', 'D', 17, 1),
(272, 'Federico', 'Fernandez', 'D', 14, 1),
(273, 'Stefano', 'Ferrario', 'D', 12, 1),
(274, 'Michele', 'Ferri', 'D', 1, 1),
(275, 'Damiano', 'Ferronetti', 'D', 20, 1),
(276, 'Nicolas', 'Frey', 'D', 6, 1),
(277, 'Alessandro', 'Gamberini', 'D', 7, 1),
(278, 'Santiago', 'Garcia', 'D', 15, 1),
(279, 'Gyorgy', 'Garics', 'D', 2, 1),
(280, 'Javier', 'Garrido', 'D', 11, 1),
(281, 'Giuseppe', 'Gemiti', 'D', 15, 1),
(282, 'Guillaume', 'Gigliotti', 'D', 15, 0),
(283, 'Massimo', 'Gobbi', 'D', 17, 1),
(284, 'Andreas', 'Granqvist', 'D', 8, 1),
(285, 'Gianluca', 'Grava', 'D', 14, 1),
(286, 'Fabio', 'Grosso', 'D', 10, 1),
(287, 'Zdenek', 'Grygera', 'D', 10, 0),
(288, 'Gabriel', 'Heinze', 'D', 18, 1),
(289, 'Bojan', 'Jokic', 'D', 6, 1),
(290, 'Cicero Moreira', 'Jonathan', 'D', 9, 1),
(291, NULL, 'Jose Angel', 'D', 18, 1),
(292, 'Silveira Dos Santos', 'Juan', 'D', 18, 1),
(293, 'Kakha', 'Kaladze', 'D', 8, 1),
(294, 'Abdoulay', 'Konko', 'D', 11, 1),
(295, 'Per', 'Kroldrup', 'D', 7, 1),
(296, 'Maurizio', 'Lauro', 'D', 5, 1),
(297, 'Stephan', 'Lichtsteiner', 'D', 10, 1),
(298, 'Andrea', 'Lisuzzo', 'D', 15, 1),
(299, 'Alessandro', 'Lucarelli', 'D', 17, 1),
(300, 'Stefano', 'Lucchini', 'D', 1, 1),
(301, NULL, 'Lucio', 'D', 9, 1),
(302, 'Carlo Alberto', 'Ludi', 'D', 15, 1),
(303, 'Senad', 'Lulic', 'D', 11, 1),
(304, 'Douglas', 'Maicon', 'D', 9, 1),
(305, 'Davide', 'Mandelli', 'D', 6, 1),
(306, 'Thomas', 'Manfredini', 'D', 1, 1),
(307, 'Andrea', 'Mantovani', 'D', 16, 1),
(308, 'Giovanni', 'Marchese', 'D', NULL, 1),
(309, 'Andrea', 'Masiello', 'D', 1, 1),
(310, 'Antonio', 'Mazzotta', 'D', 12, 0),
(311, 'Giandomenico', 'Mesto', 'D', 8, 1),
(312, 'Philippe', 'Mexes', 'D', 13, 1),
(313, 'Milan', 'Milanovic', 'D', 19, 1),
(314, 'Santiago', 'Morero', 'D', 6, 1),
(315, 'Emiliano', 'Moretti', 'D', 8, 1),
(316, 'Michel', 'Morganella', 'D', 15, 1),
(317, 'Archimede', 'Morleo', 'D', 2, 1),
(318, 'Marco', 'Motta', 'D', 10, 1),
(319, 'Ezequiel', 'Munoz', 'D', 16, 1),
(320, 'Yuto', 'Nagatomo', 'D', 9, 1),
(321, 'Matija', 'Nastasic', 'D', 7, 1),
(322, 'Cesare', 'Natali', 'D', 7, 1),
(323, 'Alessandro', 'Nesta', 'D', 13, 1),
(324, NULL, 'Neuton', 'D', 20, 1),
(325, 'Massimo', 'Oddo', 'D', 12, 1),
(326, 'Massimo', 'Paci', 'D', 15, 1),
(327, 'Gabriel Alejandro', 'Paletta', 'D', 17, 1),
(328, 'Manuel', 'Pasqual', 'D', 7, 1),
(329, 'Giovanni', 'Pasquale', 'D', 20, 1),
(330, 'Federico', 'Peluso', 'D', 1, 1),
(331, 'Gabriele', 'Perico', 'D', 3, 1),
(332, 'Eros', 'Pisano', 'D', 16, 1),
(333, 'Francesco', 'Pisano', 'D', 3, 1),
(334, 'Daniele', 'Portanova', 'D', 2, 1),
(335, 'Alessandro', 'Potenza', 'D', NULL, 1),
(336, 'Stefan', 'Radu', 'D', 11, 1),
(337, 'Cristian', 'Raimondi', 'D', 1, 1),
(338, 'Andrea', 'Ranocchia', 'D', 9, 1),
(339, 'Cesare', 'Rickler', 'D', 2, 1),
(340, 'Souza Orestes Caldeira', 'Romulo', 'D', 7, 1),
(341, 'Aleandro', 'Rosi', 'D', 18, 1),
(342, 'Luca', 'Rossettini', 'D', 19, 1),
(343, 'Andrea', 'Rossi', 'D', 19, 1),
(344, 'Marco', 'Rossi', 'D', 5, 1),
(345, 'Victor', 'Ruiz', 'D', 14, 0),
(346, 'Walter', 'Samuel', 'D', 9, 1),
(347, 'Fabiano', 'Santacroce', 'D', 17, 1),
(348, 'Davide', 'Santon', 'D', 9, 0),
(349, 'Gennaro', 'Sardo', 'D', 6, 1),
(350, 'Lionel', 'Scaloni', 'D', 11, 1),
(351, 'Matias', 'Silvestre', 'D', 16, 1),
(352, 'Frederic', 'Sorensen', 'D', 10, 1),
(353, 'Nicolas', 'Spolli', 'D', NULL, 1),
(354, 'Marius', 'Stankevicius', 'D', 11, 1),
(355, 'Guglielmo', 'Stendardo', 'D', 11, 1),
(356, 'Taye Ismaila', 'Taiwo', 'D', 13, 1),
(357, 'Claudio', 'Terzi', 'D', 19, 1),
(358, NULL, 'Thiago Silva', 'D', 13, 1),
(359, 'Nenad', 'Tomovic', 'D', 12, 1),
(360, 'Luigi', 'Vitale', 'D', 2, 1),
(361, 'Roberto', 'Vitiello', 'D', 19, 1),
(362, 'Steve', 'Von Bergen', 'D', 5, 1),
(363, 'Mario Alberto', 'Yepes', 'D', 13, 1),
(364, 'Cristian', 'Zaccardo', 'D', 17, 1),
(365, 'Gianluca', 'Zambrotta', 'D', 13, 1),
(366, 'Reto', 'Ziegler', 'D', 10, 0),
(367, 'Emanuele', 'Pesoli', 'D', 19, 1),
(368, 'Matteo', 'Rubin', 'D', 17, 1),
(369, 'Luciano', 'Zauri', 'D', 11, 1),
(370, 'Andrea', 'Raggi', 'D', 2, 1),
(371, 'Matteo', 'Contini', 'D', 19, 1),
(372, 'Pablo Hernan', 'Dellafiore', 'D', 15, 1),
(373, 'Simone', 'Gozzi', 'D', 3, 1),
(374, 'Simone', 'Loria', 'D', 2, 1),
(375, 'Boukary', 'Drame''', 'D', 6, 1),
(376, 'Matias', 'Aguirregaray', 'D', 16, 1),
(377, 'Nicola', 'Legrottaglie', 'D', NULL, 1),
(378, 'Ignacio David', 'Fideleff', 'D', 14, 1),
(379, 'Simon', 'Kjaer', 'D', 18, 1),
(380, 'Guillermo', 'Rodriguez', 'D', 5, 1),
(501, 'Almen', 'Abdi', 'C', 20, 1),
(502, 'Afriyie', 'Acquah', 'C', 16, 1),
(503, 'Ricardo', 'Alvarez', 'C', 9, 1),
(504, 'Massimo', 'Ambrosini', 'C', 13, 1),
(505, 'Alberto', 'Aquilani', 'C', 13, 1),
(506, 'Kwadwo', 'Asamoah', 'C', 20, 1),
(507, 'Armin', 'Bacinovic', 'C', 16, 1),
(508, 'Emmanuel Agyemang', 'Badu', 'C', 20, 1),
(509, 'Edgar', 'Barreto', 'C', 16, 1),
(510, 'Pablo', 'Barrientos', 'C', NULL, 1),
(511, 'Valon', 'Behrami', 'C', 7, 1),
(512, 'Simone', 'Bentivoglio', 'C', 6, 0),
(513, 'Andrea', 'Bertolacci', 'C', 12, 1),
(514, 'Jonathan', 'Biabiany', 'C', 17, 1),
(515, 'Marco', 'Biagianti', 'C', NULL, 1),
(516, 'Davide', 'Biondini', 'C', 3, 1),
(517, 'Valter', 'Birsa', 'C', 8, 1),
(518, 'Manuele', 'Blasi', 'C', 17, 1),
(519, 'Kevin Prince', 'Boateng', 'C', 13, 1),
(520, 'Mariano', 'Bogliacino', 'C', 14, 0),
(521, 'Francesco', 'Bolzoni', 'C', 19, 1),
(522, 'Giacomo', 'Bonaventura', 'C', 1, 1),
(523, 'Mark', 'Bresciano', 'C', 11, 0),
(524, 'Matteo', 'Brighi', 'C', 1, 1),
(525, 'Christian', 'Brocchi', 'C', 11, 1),
(526, 'Esteban Matias', 'Cambiasso', 'C', 9, 1),
(527, 'Lorik', 'Cana', 'C', 11, 1),
(528, 'Antonio', 'Candreva', 'C', 5, 1),
(529, 'Carlos E.', 'Carmona', 'C', 1, 1),
(530, 'Mirko', 'Carretta', 'C', 6, 0),
(531, 'Federico', 'Casarini', 'C', 2, 1),
(532, 'Fabio', 'Caserta', 'C', 1, 1),
(533, 'Pablo', 'Ceppelini', 'C', 3, 1),
(534, 'Alessio', 'Cerci', 'C', 7, 1),
(535, 'Luca', 'Cigarini', 'C', 1, 1),
(536, 'Paul Costantin', 'Codrea', 'C', 19, 1),
(537, 'Giuseppe', 'Colucci', 'C', 5, 1),
(538, 'Kevin', 'Constant', 'C', 8, 1),
(539, 'Daniele', 'Conti', 'C', 3, 1),
(540, 'Manuel', 'Coppola', 'C', 17, 0),
(541, 'Andrea', 'Cossu', 'C', 3, 1),
(542, 'Philippe', 'Coutinho', 'C', 9, 1),
(543, 'Rinaldo', 'Cruzado', 'C', 6, 1),
(544, 'Gaetano', 'D''agostino', 'C', 19, 1),
(545, 'Andrea', 'De Falco', 'C', 6, 0),
(546, 'Giuseppe', 'De Feudis', 'C', 5, 0),
(547, 'Daniele', 'De Rossi', 'C', 18, 1),
(548, 'Simone', 'Del Nero', 'C', 11, 1),
(549, 'Francesco', 'Della Rocca', 'C', 16, 1),
(550, 'Francesco', 'Dettori', 'C', 6, 0),
(551, 'Luca', 'Di Matteo', 'C', 16, 1),
(552, 'Marco', 'Donadel', 'C', 14, 1),
(553, 'Cristiano', 'Doni', 'C', 1, 1),
(554, 'Andrea', 'Dossena', 'C', 14, 1),
(555, 'Pascal', 'Doubai', 'C', 20, 1),
(556, 'Blerim', 'Dzemaili', 'C', 14, 1),
(557, 'Albin', 'Ekdal', 'C', 3, 1),
(558, 'Stephan', 'El Shaarawy', 'C', 13, 1),
(559, 'Urby', 'Emanuelson', 'C', 13, 1),
(560, 'Diego', 'Fabbrini', 'C', 20, 1),
(561, 'Adriano', 'Ferreira Pinto', 'C', 1, 1),
(562, 'Mathieu', 'Flamini', 'C', 13, 1),
(563, 'Pasquale', 'Foggia', 'C', 11, 0),
(564, 'Daniele', 'Galloppa', 'C', 17, 1),
(565, 'Walter', 'Gargano', 'C', 14, 1),
(566, 'Gennaro Ivan', 'Gattuso', 'C', 13, 1),
(567, 'Guillermo', 'Giacomazzi', 'C', 12, 1),
(568, 'Luigi', 'Giorgi', 'C', 15, 1),
(569, 'Alejandro', 'Gomez', 'C', NULL, 1),
(570, 'Alvaro', 'Gonzalez', 'C', 11, 1),
(571, 'Leandro', 'Greco', 'C', 18, 1),
(572, 'Simone', 'Grippo', 'C', 6, 1),
(573, 'Paolo', 'Grossi', 'C', 19, 1),
(574, 'Carlos Javier', 'Grossmuller', 'C', 12, 1),
(575, 'Roberto', 'Guana', 'C', 5, 1),
(576, 'Marek', 'Hamsik', 'C', 14, 1),
(577, NULL, 'Hernanes', 'C', 11, 1),
(578, 'Perparim', 'Hetemaj', 'C', 6, 1),
(579, 'Victor', 'Ibarbo', 'C', 3, 1),
(580, 'Josip', 'Ilicic', 'C', 16, 1),
(581, 'Gokan', 'Inler', 'C', 14, 1),
(582, 'Mauricio', 'Isla', 'C', 20, 1),
(583, 'Mariano Julio', 'Izco', 'C', NULL, 1),
(584, 'Bosko', 'Jankovic', 'C', 8, 1),
(585, NULL, 'Joao Pedro', 'C', 16, 0),
(586, 'Cristobal', 'Jorquera', 'C', 8, 1),
(587, 'Stevan', 'Jovetic', 'C', 7, 1),
(588, 'Pedro', 'Kamata', 'C', 19, 0),
(589, 'Houssine', 'Kharja', 'C', 7, 1),
(590, 'Milos', 'Krasic', 'C', 10, 1),
(591, 'Rene', 'Krhin', 'C', 2, 1),
(592, 'Juray', 'Kucka', 'C', 8, 1),
(593, 'Jasmin', 'Kurtic', 'C', 8, 0),
(594, 'Erik', 'Lamela', 'C', 18, 1),
(595, 'Andrea', 'Lazzari', 'C', 7, 1),
(596, 'Cristian', 'Ledesma', 'C', 11, 1),
(597, 'Pablo Martin', 'Ledesma', 'C', NULL, 1),
(598, 'Adem', 'Ljajic', 'C', 7, 1),
(599, 'Christian Ezequeil', 'Llama', 'C', NULL, 1),
(600, 'Francesco', 'Lodi', 'C', NULL, 1),
(601, 'Siqueira De Oliveira', 'Luciano', 'C', 6, 1),
(602, 'Nicola', 'Madonna', 'C', 1, 0),
(603, 'Christian', 'Maggio', 'C', 14, 1),
(604, 'Marco', 'Mancosu', 'C', 3, 0),
(605, 'Daniele', 'Mannini', 'C', 19, 1),
(606, 'Marco', 'Marchionni', 'C', 7, 1),
(607, 'Claudio', 'Marchisio', 'C', 10, 1),
(608, 'Francesco', 'Marianini', 'C', 15, 1),
(609, 'Mcdonald', 'Mariga', 'C', 9, 0),
(610, 'Luca', 'Marrone', 'C', 10, 1),
(611, 'Jorge Andres', 'Martinez', 'C', 5, 1),
(612, 'Raphael', 'Martinho', 'C', 5, 1),
(613, 'Francelino', 'Matuzalem', 'C', 11, 1),
(614, 'Stefano', 'Mauri', 'C', 11, 1),
(615, 'Andrea', 'Mazzarani', 'C', 15, 1),
(616, 'Ledian', 'Memushaj', 'C', 6, 0),
(617, 'Alexander', 'Merkel', 'C', 8, 1),
(618, 'Djamel', 'Mesbah', 'C', 12, 1),
(619, 'Giulio', 'Migliaccio', 'C', 16, 1),
(620, 'Francesco', 'Modesto', 'C', 17, 1),
(621, 'Riccardo', 'Montolivo', 'C', 7, 1),
(622, 'Federico', 'Moretti', 'C', NULL, 0),
(623, 'Stefano', 'Morrone', 'C', 17, 1),
(624, 'Gaby', 'Mudingayi', 'C', 2, 1),
(625, 'Gianni', 'Munari', 'C', 7, 1),
(626, 'Sulley', 'Muntari', 'C', 9, 1),
(627, 'Radja', 'Nainggolan', 'C', 3, 1),
(628, 'Antonio', 'Nocerino', 'C', 13, 1),
(629, 'Obiorah', 'Nwankwo', 'C', 17, 1),
(630, 'Joel', 'Obi', 'C', 9, 1),
(631, 'Christian', 'Obodo', 'C', 12, 1),
(632, 'Ruben', 'Olivera', 'C', 12, 1),
(633, 'Simone', 'Padoin', 'C', 1, 1),
(634, 'Raffaele', 'Palladino', 'C', 17, 1),
(635, 'Gabriele', 'Paonessa', 'C', 17, 0),
(636, 'Marco', 'Parolo', 'C', 5, 1),
(637, 'Cristian', 'Pasquato', 'C', 12, 1),
(638, 'Javier', 'Pastore', 'C', 16, 0),
(639, 'Michele', 'Pazienza', 'C', 10, 1),
(640, 'Simone', 'Pepe', 'C', 10, 1),
(641, 'Diego', 'Perez', 'C', 2, 1),
(642, 'Simone', 'Perrotta', 'C', 18, 1),
(643, 'Simone', 'Pesce', 'C', 15, 1),
(644, 'Leonardo', 'Pettinari', 'C', 1, 0),
(645, 'Ignacio', 'Piatti', 'C', 12, 1),
(646, 'Alex', 'Pinardi', 'C', 15, 1),
(647, 'Giampiero', 'Pinzi', 'C', 20, 1),
(648, 'Andrea', 'Pirlo', 'C', 10, 1),
(649, 'Andrea', 'Pisanu', 'C', 2, 0),
(650, 'David Marcelo Cortes', 'Pizarro', 'C', 18, 1),
(651, 'Filippo', 'Porcari', 'C', 15, 1),
(652, 'Nico', 'Pulzetti', 'C', 2, 1),
(653, 'Ivan', 'Radovanovic', 'C', 15, 1),
(654, 'Gaston', 'Ramirez', 'C', 2, 1),
(655, 'Ferreira', 'Reginaldo', 'C', 19, 1),
(656, 'Adrian', 'Ricchiuti', 'C', NULL, 1),
(657, 'Luca', 'Rigoni', 'C', 6, 1),
(658, 'Marco', 'Rigoni', 'C', 15, 1),
(659, 'Marco', 'Rossi', 'C', 8, 1),
(660, 'Mario Alberto', 'Santana', 'C', 14, 1),
(661, 'Matias Ezquiel', 'Schelotto', 'C', 1, 1),
(662, 'Fabio', 'Sciacca', 'C', NULL, 1),
(663, 'Giuseppe', 'Sculli', 'C', 11, 1),
(664, 'Clarence', 'Seedorf', 'C', 13, 1),
(665, 'Alessio', 'Sestu', 'C', 19, 1),
(666, 'Felipe Ignacio', 'Seymour', 'C', 8, 1),
(667, 'Rijat', 'Shala', 'C', 15, 0),
(668, 'Adam', 'Simon', 'C', 16, 1),
(669, 'Fabio Henrique', 'Simplicio', 'C', 18, 1),
(670, 'Abdou', 'Sissoko', 'C', 20, 1),
(671, 'Mohamed', 'Sissoko', 'C', 10, 0),
(672, 'Mikhail', 'Sivakov', 'C', 3, 0),
(673, 'Wesley', 'Sneijder', 'C', 9, 1),
(674, 'Dejan', 'Stankovic', 'C', 9, 1),
(675, 'Rodney', 'Strasser', 'C', 12, 1),
(676, 'Rodrigo', 'Taddei', 'C', 18, 1),
(677, NULL, 'Thiago Motta', 'C', 9, 1),
(678, 'Gennaro', 'Troianiello', 'C', 19, 1),
(679, 'Jaime Andres Zapata', 'Valdes', 'C', 17, 1),
(680, 'Francesco', 'Valiani', 'C', 17, 1),
(681, 'Mark', 'Van Bommel', 'C', 13, 1),
(682, 'Ignacio', 'Varela Lores', 'C', 16, 1),
(683, 'Juan', 'Vargas', 'C', 7, 1),
(684, 'Miguel', 'Veloso', 'C', 8, 1),
(685, 'Simone', 'Vergassola', 'C', 19, 1),
(686, 'Arturo', 'Vidal', 'C', 10, 1),
(687, 'Eran', 'Zahavi', 'C', 16, 1),
(688, 'Javier Aldemar', 'Zanetti', 'C', 9, 1),
(689, NULL, 'Ze Eduardo', 'C', 17, 1),
(690, 'Juan Camilo', 'Zuniga', 'C', 14, 1),
(691, 'Juan', 'Cuadrado', 'C', 12, 1),
(692, 'Maxi', 'Moralez', 'C', 1, 1),
(693, 'Saphir', 'Taider', 'C', 2, 1),
(694, 'Davide', 'Lanzafame', 'C', NULL, 1),
(695, 'Pablo Estifer', 'Armero', 'C', 20, 1),
(696, 'Alessandro', 'Diamanti', 'C', 2, 1),
(697, 'Mario Angel', 'Paglialunga', 'C', NULL, 1),
(698, 'Fernando', 'Marques', 'C', 17, 1),
(699, 'Alessandro', 'Gazzi', 'C', 19, 1),
(700, 'Federico', 'Viviani', 'C', 18, 1),
(701, 'Gianluca', 'Caprari', 'C', 18, 1),
(702, 'Paolo', 'Sammarco', 'C', 6, 1),
(703, 'Kamil', 'Vacek', 'C', 6, 1),
(704, 'Sergio', 'Almiron', 'C', NULL, 1),
(705, 'Marcelo', 'Estigarribia', 'C', 10, 1),
(706, 'Andrea', 'Poli', 'C', 9, 1),
(707, 'Sebastian', 'Eriksson', 'C', 3, 1),
(708, 'Gabriel', 'Torje', 'C', 20, 1),
(709, NULL, 'Rui Sampaio', 'C', 3, 1),
(710, 'Michael', 'Bradley', 'C', 6, 1),
(711, 'Elijiero', 'Elia', 'C', 10, 1),
(712, 'Roberto Maximiliano', 'Pereyra', 'C', 20, 1),
(713, 'Panagiotis', 'Kone', 'C', 2, 1),
(714, 'Manuel', 'Giandonato', 'C', 12, 1),
(715, 'Edgar', 'Alvarez', 'C', 16, 1),
(716, 'Fernando', 'Gago', 'C', 18, 1),
(717, 'Miralem', 'Pjanic', 'C', 18, 1),
(718, 'Damian', 'Djokovic', 'C', 5, 1),
(719, 'Nicolas', 'Bertolo', 'C', 16, 1),
(720, 'Gennaro', 'Delvecchio', 'C', NULL, 1),
(721, 'Cesar Daniel', 'Meza Colli', 'C', 5, 1),
(722, 'Abderrazzak', 'Jadid', 'C', 17, 1),
(801, 'Robert', 'Acquafresca', 'A', 2, 1),
(802, 'Dominic', 'Adiyiah', 'A', 13, 0),
(803, 'Carvalho De Oliveira', 'Amauri', 'A', 10, 1),
(804, 'Matteo', 'Ardemagni', 'A', 1, 1),
(805, 'Khouma El', 'Babacar', 'A', 7, 1),
(806, 'De Souza Paulo', 'Barreto', 'A', 20, 1),
(807, 'Sasa', 'Bjelanovic', 'A', 1, 0),
(808, 'Erjon', 'Bogdani', 'A', 5, 1),
(809, 'Krkic', 'Bojan', 'A', 18, 1),
(810, 'Fabio', 'Borini', 'A', 18, 1),
(811, 'Marco', 'Borriello', 'A', 18, 1),
(812, 'Franco', 'Brienza', 'A', 19, 1),
(813, 'Daniele', 'Cacia', 'A', 12, 0),
(814, 'Emanuele', 'Calaio''', 'A', 19, 1),
(815, 'Antonio', 'Cassano', 'A', 13, 1),
(816, 'Luc', 'Castaignos', 'A', 9, 1),
(817, 'Andrea', 'Catellani', 'A', NULL, 1),
(818, 'Edinson', 'Cavani', 'A', 14, 1),
(819, 'Tommaso', 'Ceccarelli', 'A', 11, 0),
(820, 'Djibril', 'Cisse', 'A', 11, 1),
(821, 'Daniele', 'Corvia', 'A', 12, 1),
(822, 'Hernan', 'Crespo', 'A', 17, 1),
(823, 'Marco Ariel', 'De Paula', 'A', 6, 0),
(824, 'Alessandro', 'Del Piero', 'A', 10, 1),
(825, 'German', 'Denis', 'A', 1, 1),
(826, 'Mattia', 'Destro', 'A', 19, 1),
(827, 'David', 'Di Michele', 'A', 12, 1),
(828, 'Antonio', 'Di Natale', 'A', 20, 1),
(829, 'Marco', 'Di Vaio', 'A', 2, 1),
(830, 'Citadin Martins', 'Eder', 'A', 5, 1),
(831, 'Moestafa', 'El Kabir', 'A', 3, 1),
(832, 'Andres Ramiro', 'Escobar', 'A', 8, 0),
(833, 'Samuel', 'Eto''o', 'A', 9, 0),
(834, 'Sergio', 'Floccari', 'A', 17, 1),
(835, 'Antonio', 'Floro Flores', 'A', 20, 1),
(836, 'Manolo', 'Gabbiadini', 'A', 1, 1),
(837, 'Emanuele', 'Giaccherini', 'A', 10, 1),
(838, 'Alberto', 'Gilardino', 'A', 7, 1),
(839, 'Henry Damian', 'Gimenez', 'A', 2, 1),
(840, 'Sebastian', 'Giovinco', 'A', 17, 1),
(841, NULL, 'Gonzalez Pablo', 'A', 19, 1),
(842, 'Pablo', 'Granoche', 'A', 15, 1),
(843, 'Linus', 'Hallenius', 'A', 8, 0),
(844, 'Abel', 'Hernandez', 'A', 16, 1),
(845, 'Vincenzo', 'Iaquinta', 'A', 10, 1),
(846, 'Zlatan', 'Ibrahimovic', 'A', 13, 1),
(847, 'Filippo', 'Inzaghi', 'A', 13, 1),
(848, 'Antimo', 'Iunco', 'A', 6, 0),
(849, 'Capucho Neves', 'Jeda', 'A', 15, 1),
(850, NULL, 'Keko', 'A', NULL, 1),
(851, 'Miroslav', 'Klose', 'A', 11, 1),
(852, 'Libor', 'Kozak', 'A', 11, 1),
(853, 'Joaquin', 'Larrivey', 'A', 3, 1),
(854, 'Paez Marcelo', 'Larrondo', 'A', 19, 1),
(855, 'Ezequiel', 'Lavezzi', 'A', 14, 1),
(856, 'Cristiano', 'Lucarelli', 'A', 14, 1),
(857, 'Dominic', 'Malonga', 'A', 5, 1),
(858, 'Guido', 'Marilungo', 'A', 1, 1),
(859, 'Giuseppe', 'Mascara', 'A', 14, 1),
(860, 'Alessandro', 'Matri', 'A', 10, 1),
(861, 'Gaston', 'Maxi Lopez', 'A', NULL, 1),
(862, 'Riccardo', 'Meggiorini', 'A', 15, 1),
(863, 'Fabrizio', 'Miccoli', 'A', 16, 1),
(864, 'Diego', 'Milito', 'A', 9, 1),
(865, 'Takayuki', 'Morimoto', 'A', 15, 1),
(866, 'Davide', 'Moscardelli', 'A', 6, 1),
(867, 'Simone', 'Motta', 'A', 15, 0),
(868, 'Adrian', 'Mutu', 'A', 5, 1),
(869, 'Anderson Miguel', 'Nene''', 'A', 3, 1),
(870, 'Edward', 'Ofere', 'A', 12, 1),
(871, 'Stefano', 'Okaka', 'A', 18, 1),
(872, 'Rodrigo', 'Palacio', 'A', 8, 1),
(873, 'Alberto', 'Paloschi', 'A', 6, 1),
(874, 'Goran', 'Pandev', 'A', 14, 1),
(875, 'Michele', 'Paolucci', 'A', 19, 0),
(876, 'Ndiaye', 'Papa Waigo', 'A', 7, 0),
(877, 'Daniele', 'Paponi', 'A', 2, 1),
(878, 'Alexandre', 'Pato', 'A', 13, 1),
(879, 'Giampaolo', 'Pazzini', 'A', 9, 1),
(880, 'Graziano', 'Pelle''', 'A', 17, 1),
(881, 'Sergio', 'Pellissier', 'A', 6, 1),
(882, 'Mauricio', 'Pinilla', 'A', 16, 1),
(883, 'Lucas', 'Pratto', 'A', 8, 1),
(884, 'Fabio', 'Quagliarella', 'A', 10, 1),
(885, 'Vincenzo', 'Rennella', 'A', 5, 1),
(886, 'Sebastian Cesar Helios', 'Ribas', 'A', 8, 1),
(887, 'Roope', 'Riski', 'A', 5, 0),
(888, 'Robson De Souza', 'Robinho', 'A', 13, 1),
(889, 'Tommaso', 'Rocchi', 'A', 11, 1),
(890, 'Federico', 'Rodriguez', 'A', 2, 1),
(891, 'Raffaele', 'Rubino', 'A', 15, 1),
(892, 'Gergely', 'Rudolf', 'A', 8, 0),
(893, 'Francesco', 'Ruopolo', 'A', 1, 0),
(894, 'Amadou', 'Samb', 'A', 6, 0),
(895, 'Nicola', 'Sansone', 'A', 17, 0),
(896, 'Haris', 'Seferovic', 'A', 7, 0),
(897, 'David', 'Suazo', 'A', NULL, 1),
(898, 'Cyril', 'Thereau', 'A', 6, 1),
(899, 'Simone', 'Tiribocchi', 'A', 1, 1),
(900, 'Luca', 'Toni', 'A', 10, 1),
(901, 'Francesco', 'Totti', 'A', 18, 1),
(902, 'Fernando', 'Uribe', 'A', 6, 1),
(903, 'Daniele', 'Vantaggiato', 'A', 2, 1),
(904, 'Mirko', 'Vucinic', 'A', 10, 1),
(905, 'Matej', 'Vydra', 'A', 20, 0),
(906, 'Mauro Matias', 'Zarate', 'A', 9, 1),
(907, 'De Almeida', 'Ze Eduardo', 'A', 8, 1),
(908, 'Cristian', 'Chavez', 'A', 14, 1),
(909, 'Pablo Daniel', 'Osvaldo', 'A', 18, 1),
(910, 'Gonzalo', 'Bergessio', 'A', NULL, 1),
(911, 'Abdelkader', 'Ghezzal', 'A', 5, 1),
(912, 'Francesco', 'Grandolfo', 'A', 6, 1),
(913, 'Silva', 'Santiago', 'A', 7, 1),
(914, 'Luis Fernando', 'Muriel', 'A', 12, 1),
(915, 'Diego', 'Forlan', 'A', 9, 1),
(916, 'Ribeiro', 'Thiago', 'A', 3, 1),
(917, 'Joel', 'Acosta', 'A', 19, 1),
(918, 'Andrea', 'Caracciolo', 'A', 8, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `giornata`
--

CREATE TABLE IF NOT EXISTS `giornata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataInizio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dataFine` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

--
-- Dump dei dati per la tabella `giornata`
--

INSERT INTO `giornata` (`id`, `dataInizio`, `dataFine`) VALUES
(1, '2012-07-30 22:00:00', '2012-08-31 16:00:00'),
(2, '2011-08-28 20:45:00', '2011-09-09 18:45:00'),
(3, '2011-09-11 20:45:00', '2011-09-17 16:00:00'),
(4, '2011-09-18 20:45:00', '2011-09-20 18:45:00'),
(5, '2011-09-22 20:45:00', '2011-09-24 16:00:00'),
(6, '2011-09-25 20:45:00', '2011-10-01 22:00:00'),
(7, '2011-10-02 00:00:00', '2011-10-15 22:00:00'),
(8, '2011-10-16 00:00:00', '2011-10-22 22:00:00'),
(9, '2011-10-23 00:00:00', '2011-10-25 22:00:00'),
(10, '2011-10-25 22:00:00', '2011-10-29 22:00:00'),
(11, '2011-10-30 01:00:00', '2011-11-05 23:00:00'),
(12, '2011-11-06 01:00:00', '2011-11-19 23:00:00'),
(13, '2011-11-20 01:00:00', '2011-11-26 23:00:00'),
(14, '2011-11-27 01:00:00', '2011-12-03 23:00:00'),
(15, '2011-12-04 01:00:00', '2011-12-10 23:00:00'),
(16, '2011-12-11 01:00:00', '2011-12-17 23:00:00'),
(17, '2011-12-18 01:00:00', '2012-01-07 23:00:00'),
(18, '2012-01-08 01:00:00', '2012-01-14 23:00:00'),
(19, '2012-01-15 01:00:00', '2012-01-21 23:00:00'),
(20, '2012-01-22 01:00:00', '2012-01-28 23:00:00'),
(21, '2012-01-29 01:00:00', '2012-01-31 23:00:00'),
(22, '2012-02-01 01:00:00', '2012-02-04 23:00:00'),
(23, '2012-02-05 01:00:00', '2012-02-11 23:00:00'),
(24, '2012-02-12 01:00:00', '2012-02-18 23:00:00'),
(25, '2012-02-19 01:00:00', '2012-02-25 23:00:00'),
(26, '2012-02-26 01:00:00', '2012-03-03 23:00:00'),
(27, '2012-03-04 01:00:00', '2012-03-10 23:00:00'),
(28, '2012-03-11 01:00:00', '2012-03-17 23:00:00'),
(29, '2012-03-18 01:00:00', '2012-03-24 23:00:00'),
(30, '2012-03-25 01:00:00', '2012-03-31 22:00:00'),
(31, '2012-04-01 00:00:00', '2012-04-06 22:00:00'),
(32, '2012-04-07 00:00:00', '2012-04-10 22:00:00'),
(33, '2012-04-11 00:00:00', '2012-04-14 22:00:00'),
(34, '2012-04-15 00:00:00', '2012-04-21 22:00:00'),
(35, '2012-04-22 00:00:00', '2012-04-28 22:00:00'),
(36, '2012-04-29 00:00:00', '2012-05-01 22:00:00'),
(37, '2012-05-02 00:00:00', '2012-05-05 22:00:00'),
(38, '2012-05-06 00:00:00', '2012-08-28 22:00:00'),
(39, '2012-06-30 22:00:00', '2012-08-29 22:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `lega`
--

CREATE TABLE IF NOT EXISTS `lega` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `capitano` tinyint(1) NOT NULL DEFAULT '1',
  `numTrasferimenti` tinyint(4) NOT NULL DEFAULT '15',
  `numSelezioni` tinyint(4) NOT NULL DEFAULT '2',
  `minFormazione` smallint(6) NOT NULL DEFAULT '10',
  `premi` text COLLATE utf8_unicode_ci,
  `punteggioFormazioneDimenticata` smallint(6) NOT NULL DEFAULT '66',
  `jolly` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `lega`
--

INSERT INTO `lega` (`id`, `nome`, `capitano`, `numTrasferimenti`, `numSelezioni`, `minFormazione`, `premi`, `punteggioFormazioneDimenticata`, `jolly`) VALUES
(1, 'Alzano Sopra', 1, 15, 2, 10, NULL, 66, 1),
(2, 'uva town', 1, 15, 3, 10, NULL, 100, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `punteggio`
--

CREATE TABLE IF NOT EXISTS `punteggio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `punteggio` float NOT NULL,
  `penalità` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idGiornata` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idLega` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idGiornata` (`idGiornata`),
  KEY `idUtente` (`idUtente`),
  KEY `idLega` (`idLega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `schieramento`
--

CREATE TABLE IF NOT EXISTS `schieramento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFormazione` int(11) NOT NULL,
  `idGiocatore` int(11) NOT NULL,
  `posizione` int(11) NOT NULL,
  `considerato` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idFormazione` (`idFormazione`),
  KEY `idGiocatore` (`idGiocatore`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dump dei dati per la tabella `schieramento`
--

INSERT INTO `schieramento` (`id`, `idFormazione`, `idGiocatore`, `posizione`, `considerato`) VALUES
(1, 1, 102, 1, 0),
(2, 1, 208, 2, 0),
(3, 1, 223, 3, 0),
(4, 1, 257, 4, 0),
(5, 1, 309, 5, 0),
(6, 1, 686, 6, 0),
(7, 1, 679, 7, 0),
(8, 1, 683, 8, 0),
(9, 1, 828, 9, 0),
(10, 1, 864, 10, 0),
(11, 1, 830, 11, 0),
(12, 1, 511, 12, 0),
(13, 1, 528, 13, 0),
(14, 1, 627, 14, 0),
(15, 1, 304, 15, 0),
(16, 1, 249, 16, 0),
(17, 1, 344, 17, 0),
(18, 1, 909, 18, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `selezione`
--

CREATE TABLE IF NOT EXISTS `selezione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idLega` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idGiocatoreOld` int(11) DEFAULT NULL,
  `idGiocatoreNew` int(11) DEFAULT NULL,
  `numSelezioni` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `giocOld` (`idGiocatoreOld`),
  KEY `giocNew` (`idGiocatoreNew`),
  KEY `idLega` (`idLega`),
  KEY `idUtente` (`idUtente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra`
--

CREATE TABLE IF NOT EXISTS `squadra` (
  `idLega` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idGiocatore` int(11) NOT NULL,
  PRIMARY KEY (`idLega`,`idUtente`,`idGiocatore`),
  KEY `idUtente` (`idUtente`),
  KEY `idGioc` (`idGiocatore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `squadra`
--

INSERT INTO `squadra` (`idLega`, `idUtente`, `idGiocatore`) VALUES
(1, 1, 108),
(1, 1, 112),
(1, 1, 127),
(1, 1, 217),
(1, 1, 235),
(1, 1, 299),
(1, 1, 301),
(1, 1, 320),
(1, 1, 353),
(1, 1, 364),
(1, 1, 368),
(1, 1, 526),
(1, 1, 539),
(1, 1, 558),
(1, 1, 582),
(1, 1, 596),
(1, 1, 600),
(1, 1, 636),
(1, 1, 673),
(1, 1, 811),
(1, 1, 815),
(1, 1, 846),
(1, 1, 869),
(1, 1, 883),
(1, 1, 884),
(1, 2, 102),
(1, 2, 103),
(1, 2, 126),
(1, 2, 208),
(1, 2, 214),
(1, 2, 223),
(1, 2, 249),
(1, 2, 257),
(1, 2, 304),
(1, 2, 309),
(1, 2, 344),
(1, 2, 511),
(1, 2, 528),
(1, 2, 541),
(1, 2, 627),
(1, 2, 637),
(1, 2, 679),
(1, 2, 683),
(1, 2, 686),
(1, 2, 828),
(1, 2, 830),
(1, 2, 864),
(1, 2, 873),
(1, 2, 880),
(1, 2, 909),
(1, 3, 114),
(1, 3, 129),
(1, 3, 149),
(1, 3, 209),
(1, 3, 216),
(1, 3, 224),
(1, 3, 231),
(1, 3, 311),
(1, 3, 326),
(1, 3, 336),
(1, 3, 379),
(1, 3, 544),
(1, 3, 590),
(1, 3, 594),
(1, 3, 640),
(1, 3, 646),
(1, 3, 648),
(1, 3, 674),
(1, 3, 711),
(1, 3, 809),
(1, 3, 814),
(1, 3, 824),
(1, 3, 826),
(1, 3, 851),
(1, 3, 901),
(1, 4, 106),
(1, 4, 116),
(1, 4, 128),
(1, 4, 245),
(1, 4, 266),
(1, 4, 292),
(1, 4, 294),
(1, 4, 306),
(1, 4, 323),
(1, 4, 356),
(1, 4, 372),
(1, 4, 504),
(1, 4, 509),
(1, 4, 522),
(1, 4, 535),
(1, 4, 580),
(1, 4, 581),
(1, 4, 603),
(1, 4, 633),
(1, 4, 818),
(1, 4, 825),
(1, 4, 836),
(1, 4, 844),
(1, 4, 860),
(1, 4, 861),
(1, 5, 118),
(1, 5, 134),
(1, 5, 148),
(1, 5, 203),
(1, 5, 226),
(1, 5, 227),
(1, 5, 261),
(1, 5, 291),
(1, 5, 338),
(1, 5, 362),
(1, 5, 374),
(1, 5, 506),
(1, 5, 514),
(1, 5, 538),
(1, 5, 547),
(1, 5, 569),
(1, 5, 576),
(1, 5, 692),
(1, 5, 717),
(1, 5, 834),
(1, 5, 837),
(1, 5, 838),
(1, 5, 872),
(1, 5, 904),
(1, 5, 918),
(1, 6, 135),
(1, 6, 138),
(1, 6, 145),
(1, 6, 201),
(1, 6, 236),
(1, 6, 240),
(1, 6, 241),
(1, 6, 268),
(1, 6, 334),
(1, 6, 349),
(1, 6, 351),
(1, 6, 519),
(1, 6, 537),
(1, 6, 595),
(1, 6, 607),
(1, 6, 614),
(1, 6, 621),
(1, 6, 654),
(1, 6, 688),
(1, 6, 827),
(1, 6, 840),
(1, 6, 862),
(1, 6, 863),
(1, 6, 865),
(1, 6, 888),
(1, 7, 102),
(1, 7, 113),
(1, 7, 147),
(1, 7, 250),
(1, 7, 253),
(1, 7, 256),
(1, 7, 258),
(1, 7, 260),
(1, 7, 265),
(1, 7, 290),
(1, 7, 346),
(1, 7, 503),
(1, 7, 517),
(1, 7, 534),
(1, 7, 592),
(1, 7, 664),
(1, 7, 695),
(1, 7, 696),
(1, 7, 708),
(1, 7, 820),
(1, 7, 855),
(1, 7, 868),
(1, 7, 881),
(1, 7, 882),
(1, 7, 915),
(1, 8, 105),
(1, 8, 121),
(1, 8, 124),
(1, 8, 206),
(1, 8, 233),
(1, 8, 277),
(1, 8, 293),
(1, 8, 297),
(1, 8, 300),
(1, 8, 342),
(1, 8, 358),
(1, 8, 505),
(1, 8, 556),
(1, 8, 560),
(1, 8, 567),
(1, 8, 577),
(1, 8, 587),
(1, 8, 598),
(1, 8, 687),
(1, 8, 801),
(1, 8, 829),
(1, 8, 878),
(1, 8, 879),
(1, 8, 897),
(1, 8, 906);

-- --------------------------------------------------------

--
-- Struttura della tabella `trasferimento`
--

CREATE TABLE IF NOT EXISTS `trasferimento` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idGiocatoreOld` int(11) DEFAULT NULL,
  `idGiocatoreNew` int(11) DEFAULT NULL,
  `idUtente` int(11) NOT NULL,
  `idGiornata` int(11) NOT NULL,
  `obbligato` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IdGiocOld` (`idGiocatoreOld`),
  KEY `IdGiocNew` (`idGiocatoreNew`),
  KEY `idSquadra` (`idUtente`),
  KEY `idGiornata` (`idGiornata`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE IF NOT EXISTS `utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomeSquadra` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cognome` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `abilitaMail` tinyint(1) NOT NULL DEFAULT '1',
  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `chiave` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amministratore` tinyint(1) NOT NULL,
  `idLega` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idLega` (`idLega`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `nomeSquadra`, `nome`, `cognome`, `email`, `abilitaMail`, `username`, `password`, `chiave`, `amministratore`, `idLega`) VALUES
(1, 'Not Ready To Die', 'Milo', 'Azuki', NULL, 0, 'ThoMasTurbat', '67dc4a2e143cd618d496d58af9a07c59', NULL, 2, 1),
(2, 'Bronchispögna FC', 'Stefano', 'Sonzogni', 'stefano788@gmail.com', 1, 'Fiste788', '08b5411f848a2581a41672a759c87380', 'b71b50e6f9e4a704461e04624a749ede', 2, 1),
(3, 'Cicciciciacciacia F.C..', 'Riccardo', 'Zambelli', NULL, 0, 'riki', 'fb1ad6b24e67a920c26b13f004e1232f', NULL, 0, 1),
(4, 'Barcollo Ma Non Mollo', 'Patrick', 'Skeggia', NULL, 0, 'reds10', '1e48c4420b7073bc11916c6c1de226bb', NULL, 0, 1),
(5, 'Libidine Assicurata', 'Calogero', 'Calà', NULL, 0, 'gio', 'dc1d252540c77435ae6d4d9090df0a85', NULL, 0, 1),
(6, 'Vasco team', 'Mirko', 'Austoni', NULL, 0, 'austo', 'a40ecdc86e45a0b48b41cef757f47b4b', NULL, 0, 1),
(7, 'SbrodolaniSalotti', 'Andrea', 'Ghilardi', NULL, 0, 'ghilo', '179524cec1a4c8333b631534b929050b', NULL, 0, 1),
(8, 'Scioperiamo', 'Andrea', 'Gutierrez', NULL, 0, 'guti', '442ecda3aaf3ce806b6245a4ab7f58c0', NULL, 0, 1),
(10, 'i greggi incalliti', 'luca', 'radici', NULL, 0, 'budel93', '9d68edb3a129aa5dc7cd60d3b1e879f6', NULL, 1, 2),
(11, 'bebo 4 president', 'marco', 'pacchiana', NULL, 0, 'uvaiolo', '827ccb0eea8a706c4c34a16891f84e7b', NULL, 0, 2),
(12, 'Beboooo', 'andrea', 'piccoli', NULL, 0, 'pitch', 'af076e227e4093a0000b1dfca6cad0c1', NULL, 0, 2),
(14, 'cioker ciups', 'alessandro', 'cortesi', NULL, 0, 'cioker', '3310ef8c3b3f3f6663f4b5da3acba696', NULL, 0, 2),
(15, 'rosales', 'Alex', 'Rossi', NULL, 0, 'redsalex', '5a8b74f294f1ba077d9baa5997bc2688', NULL, 0, 2),
(16, 'giaby team', 'Gianpaolo', 'arrigoni', NULL, 0, 'giaby', '153547d40c4320a3f7bb11f3435d2d49', NULL, 1, 2);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `view_0_formazionestatistiche`
--
CREATE TABLE IF NOT EXISTS `view_0_formazionestatistiche` (
`idFormazione` int(11)
,`posizione` int(11)
,`idUtente` int(11)
,`considerato` tinyint(1)
,`nome` varchar(30)
,`ruolo` char(1)
,`cognome` varchar(30)
,`idGiocatore` int(11)
,`idGiornata` int(11)
,`valutato` tinyint(1)
,`punti` float
,`voto` float
,`gol` tinyint(4)
,`golSubiti` tinyint(4)
,`golVittoria` tinyint(4)
,`golPareggio` tinyint(4)
,`assist` tinyint(4)
,`ammonizioni` tinyint(4)
,`espulsioni` tinyint(4)
,`rigoriSegnati` tinyint(4)
,`rigoriSubiti` tinyint(4)
,`presenza` tinyint(1)
,`titolare` tinyint(1)
,`quotazione` tinyint(4)
,`id` int(11)
,`nomeClub` varchar(15)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `view_0_giocatoristatistiche`
--
CREATE TABLE IF NOT EXISTS `view_0_giocatoristatistiche` (
`id` int(11)
,`nome` varchar(30)
,`cognome` varchar(30)
,`ruolo` char(1)
,`idClub` int(11)
,`status` tinyint(1)
,`nomeClub` varchar(15)
,`presenze` decimal(25,0)
,`presenzeVoto` decimal(25,0)
,`avgPunti` double(19,2)
,`avgVoti` double(19,2)
,`gol` decimal(25,0)
,`golSubiti` decimal(25,0)
,`assist` decimal(25,0)
,`ammonizioni` decimal(25,0)
,`espulsioni` decimal(25,0)
,`quotazione` tinyint(4)
,`idUtente` int(11)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `view_0_punteggisenzajolly`
--
CREATE TABLE IF NOT EXISTS `view_0_punteggisenzajolly` (
`idLega` int(11)
,`idutente` int(11)
,`idGiornata` int(11)
,`punteggio` double
,`jolly` tinyint(1)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `view_1_clubstatistiche`
--
CREATE TABLE IF NOT EXISTS `view_1_clubstatistiche` (
`id` int(11)
,`nome` varchar(15)
,`partitivo` varchar(10)
,`determinativo` varchar(3)
,`totaleGol` decimal(47,0)
,`totaleGolSubiti` decimal(47,0)
,`totaleAssist` decimal(47,0)
,`totaleAmmonizioni` decimal(47,0)
,`totaleEspulsioni` decimal(47,0)
,`avgPunti` double(19,2)
,`avgVoti` double(19,2)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `view_1_punteggimassimi`
--
CREATE TABLE IF NOT EXISTS `view_1_punteggimassimi` (
`idLega` int(11)
,`idGiornata` int(11)
,`punteggio` double
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `view_2_giornatevinte`
--
CREATE TABLE IF NOT EXISTS `view_2_giornatevinte` (
`idUtente` int(11)
,`giornateVinte` bigint(21)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `view_3_squadrastatistiche`
--
CREATE TABLE IF NOT EXISTS `view_3_squadrastatistiche` (
`id` int(11)
,`nomeSquadra` varchar(64)
,`nome` varchar(32)
,`cognome` varchar(32)
,`email` varchar(32)
,`abilitaMail` tinyint(1)
,`username` varchar(15)
,`password` varchar(35)
,`amministratore` tinyint(1)
,`idLega` int(11)
,`totaleGol` decimal(47,0)
,`totaleGolSubiti` decimal(47,0)
,`totaleAssist` decimal(47,0)
,`totaleAmmonizioni` decimal(47,0)
,`totaleEspulsioni` decimal(47,0)
,`avgPunti` double(19,2)
,`avgVoti` double(19,2)
,`punteggioMax` float
,`punteggioMin` double
,`punteggioMed` double(19,2)
,`giornateVinte` bigint(21)
);
-- --------------------------------------------------------

--
-- Struttura della tabella `voto`
--

CREATE TABLE IF NOT EXISTS `voto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idGiocatore` int(11) NOT NULL,
  `idGiornata` int(11) NOT NULL,
  `valutato` tinyint(1) NOT NULL,
  `punti` float NOT NULL,
  `voto` float NOT NULL,
  `gol` tinyint(4) NOT NULL,
  `golSubiti` tinyint(4) NOT NULL,
  `golVittoria` tinyint(4) NOT NULL,
  `golPareggio` tinyint(4) NOT NULL,
  `assist` tinyint(4) NOT NULL,
  `ammonizioni` tinyint(4) NOT NULL,
  `espulsioni` tinyint(4) NOT NULL,
  `rigoriSegnati` tinyint(4) NOT NULL,
  `rigoriSubiti` tinyint(4) NOT NULL,
  `presenza` tinyint(1) NOT NULL,
  `titolare` tinyint(1) NOT NULL,
  `quotazione` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idGiocatore` (`idGiocatore`,`idGiornata`),
  KEY `idGiocatore_2` (`idGiocatore`),
  KEY `idGiornata` (`idGiornata`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura per la vista `view_0_formazionestatistiche`
--
DROP TABLE IF EXISTS `view_0_formazionestatistiche`;

CREATE ALGORITHM=MERGE DEFINER=`fantamanajerUser`@`%` SQL SECURITY DEFINER VIEW `view_0_formazionestatistiche` AS select `schieramento`.`idFormazione` AS `idFormazione`,`schieramento`.`posizione` AS `posizione`,`formazione`.`idUtente` AS `idUtente`,`schieramento`.`considerato` AS `considerato`,`giocatore`.`nome` AS `nome`,`giocatore`.`ruolo` AS `ruolo`,`giocatore`.`cognome` AS `cognome`,`voto`.`idGiocatore` AS `idGiocatore`,`voto`.`idGiornata` AS `idGiornata`,`voto`.`valutato` AS `valutato`,`voto`.`punti` AS `punti`,`voto`.`voto` AS `voto`,`voto`.`gol` AS `gol`,`voto`.`golSubiti` AS `golSubiti`,`voto`.`golVittoria` AS `golVittoria`,`voto`.`golPareggio` AS `golPareggio`,`voto`.`assist` AS `assist`,`voto`.`ammonizioni` AS `ammonizioni`,`voto`.`espulsioni` AS `espulsioni`,`voto`.`rigoriSegnati` AS `rigoriSegnati`,`voto`.`rigoriSubiti` AS `rigoriSubiti`,`voto`.`presenza` AS `presenza`,`voto`.`titolare` AS `titolare`,`voto`.`quotazione` AS `quotazione`,`club`.`id` AS `id`,`club`.`nome` AS `nomeClub` from (((`schieramento` join `formazione` on((`schieramento`.`idFormazione` = `formazione`.`id`))) left join (`giocatore` left join `club` on((`giocatore`.`idClub` = `club`.`id`))) on((`schieramento`.`idGiocatore` = `giocatore`.`id`))) left join `voto` on(((`voto`.`idGiocatore` = `giocatore`.`id`) and (`voto`.`idGiornata` = `formazione`.`idGiornata`))));

-- --------------------------------------------------------

--
-- Struttura per la vista `view_0_giocatoristatistiche`
--
DROP TABLE IF EXISTS `view_0_giocatoristatistiche`;

CREATE ALGORITHM=UNDEFINED DEFINER=`fantamanajerUser`@`%` SQL SECURITY DEFINER VIEW `view_0_giocatoristatistiche` AS select `giocatore`.`id` AS `id`,`giocatore`.`nome` AS `nome`,`giocatore`.`cognome` AS `cognome`,`giocatore`.`ruolo` AS `ruolo`,`giocatore`.`idClub` AS `idClub`,`giocatore`.`status` AS `status`,`club`.`nome` AS `nomeClub`,sum(`voto`.`presenza`) AS `presenze`,sum(`voto`.`valutato`) AS `presenzeVoto`,round((sum(`voto`.`punti`) / sum(`voto`.`valutato`)),2) AS `avgPunti`,round((sum(`voto`.`voto`) / sum(`voto`.`valutato`)),2) AS `avgVoti`,sum(`voto`.`gol`) AS `gol`,sum(`voto`.`golSubiti`) AS `golSubiti`,sum(`voto`.`assist`) AS `assist`,sum(`voto`.`ammonizioni`) AS `ammonizioni`,sum(`voto`.`espulsioni`) AS `espulsioni`,`voto`.`quotazione` AS `quotazione`,`squadra`.`idUtente` AS `idUtente` from (((`giocatore` left join `voto` on((`giocatore`.`id` = `voto`.`idGiocatore`))) left join `club` on((`club`.`id` = `giocatore`.`idClub`))) left join `squadra` on((`giocatore`.`id` = `squadra`.`idGiocatore`))) group by `giocatore`.`id`;

-- --------------------------------------------------------

--
-- Struttura per la vista `view_0_punteggisenzajolly`
--
DROP TABLE IF EXISTS `view_0_punteggisenzajolly`;

CREATE ALGORITHM=MERGE DEFINER=`fantamanajerUser`@`%` SQL SECURITY DEFINER VIEW `view_0_punteggisenzajolly` AS select `punteggio`.`idLega` AS `idLega`,`punteggio`.`idUtente` AS `idutente`,`punteggio`.`idGiornata` AS `idGiornata`,if(isnull(`formazione`.`jolly`),`punteggio`.`punteggio`,(`punteggio`.`punteggio` / 2)) AS `punteggio`,`formazione`.`jolly` AS `jolly` from (`punteggio` join `formazione` on(((`punteggio`.`idGiornata` = `formazione`.`idGiornata`) and (`punteggio`.`idUtente` = `formazione`.`idUtente`))));

-- --------------------------------------------------------

--
-- Struttura per la vista `view_1_clubstatistiche`
--
DROP TABLE IF EXISTS `view_1_clubstatistiche`;

CREATE ALGORITHM=UNDEFINED DEFINER=`fantamanajerUser`@`%` SQL SECURITY DEFINER VIEW `view_1_clubstatistiche` AS select `club`.`id` AS `id`,`club`.`nome` AS `nome`,`club`.`partitivo` AS `partitivo`,`club`.`determinativo` AS `determinativo`,coalesce(sum(`view_0_giocatoristatistiche`.`gol`),0) AS `totaleGol`,coalesce(sum(`view_0_giocatoristatistiche`.`golSubiti`),0) AS `totaleGolSubiti`,coalesce(sum(`view_0_giocatoristatistiche`.`assist`),0) AS `totaleAssist`,coalesce(sum(`view_0_giocatoristatistiche`.`ammonizioni`),0) AS `totaleAmmonizioni`,coalesce(sum(`view_0_giocatoristatistiche`.`espulsioni`),0) AS `totaleEspulsioni`,round(coalesce(avg(`view_0_giocatoristatistiche`.`avgPunti`),0),2) AS `avgPunti`,round(coalesce(avg(`view_0_giocatoristatistiche`.`avgVoti`),0),2) AS `avgVoti` from (`club` join `view_0_giocatoristatistiche` on((`club`.`id` = `view_0_giocatoristatistiche`.`idClub`))) group by `club`.`id`;

-- --------------------------------------------------------

--
-- Struttura per la vista `view_1_punteggimassimi`
--
DROP TABLE IF EXISTS `view_1_punteggimassimi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`fantamanajerUser`@`%` SQL SECURITY DEFINER VIEW `view_1_punteggimassimi` AS select `view_0_punteggisenzajolly`.`idLega` AS `idLega`,`view_0_punteggisenzajolly`.`idGiornata` AS `idGiornata`,max(`view_0_punteggisenzajolly`.`punteggio`) AS `punteggio` from `view_0_punteggisenzajolly` group by `view_0_punteggisenzajolly`.`idLega`,`view_0_punteggisenzajolly`.`idGiornata`;

-- --------------------------------------------------------

--
-- Struttura per la vista `view_2_giornatevinte`
--
DROP TABLE IF EXISTS `view_2_giornatevinte`;

CREATE ALGORITHM=UNDEFINED DEFINER=`fantamanajerUser`@`%` SQL SECURITY DEFINER VIEW `view_2_giornatevinte` AS select `view_0_punteggisenzajolly`.`idutente` AS `idUtente`,count(`view_0_punteggisenzajolly`.`idutente`) AS `giornateVinte` from (`view_0_punteggisenzajolly` join `view_1_punteggimassimi` on(((`view_0_punteggisenzajolly`.`idGiornata` = `view_1_punteggimassimi`.`idGiornata`) and (`view_0_punteggisenzajolly`.`punteggio` = `view_1_punteggimassimi`.`punteggio`) and (`view_0_punteggisenzajolly`.`idLega` = `view_1_punteggimassimi`.`idLega`)))) group by `view_0_punteggisenzajolly`.`idutente`;

-- --------------------------------------------------------

--
-- Struttura per la vista `view_3_squadrastatistiche`
--
DROP TABLE IF EXISTS `view_3_squadrastatistiche`;

CREATE ALGORITHM=UNDEFINED DEFINER=`fantamanajerUser`@`%` SQL SECURITY DEFINER VIEW `view_3_squadrastatistiche` AS select `utente`.`id` AS `id`,`utente`.`nomeSquadra` AS `nomeSquadra`,`utente`.`nome` AS `nome`,`utente`.`cognome` AS `cognome`,`utente`.`email` AS `email`,`utente`.`abilitaMail` AS `abilitaMail`,`utente`.`username` AS `username`,`utente`.`password` AS `password`,`utente`.`amministratore` AS `amministratore`,`utente`.`idLega` AS `idLega`,coalesce(sum(`view_0_giocatoristatistiche`.`gol`),0) AS `totaleGol`,coalesce(sum(`view_0_giocatoristatistiche`.`golSubiti`),0) AS `totaleGolSubiti`,coalesce(sum(`view_0_giocatoristatistiche`.`assist`),0) AS `totaleAssist`,coalesce(sum(`view_0_giocatoristatistiche`.`ammonizioni`),0) AS `totaleAmmonizioni`,coalesce(sum(`view_0_giocatoristatistiche`.`espulsioni`),0) AS `totaleEspulsioni`,round(coalesce(avg(`view_0_giocatoristatistiche`.`avgPunti`),0),2) AS `avgPunti`,round(coalesce(avg(`view_0_giocatoristatistiche`.`avgVoti`),0),2) AS `avgVoti`,max(`punteggio`.`punteggio`) AS `punteggioMax`,(select min(`punteggio`.`punteggio`) from `punteggio` where ((`punteggio`.`punteggio` > 0) and (`punteggio`.`idUtente` = `utente`.`id`))) AS `punteggioMin`,round(avg(`punteggio`.`punteggio`),2) AS `punteggioMed`,`view_2_giornatevinte`.`giornateVinte` AS `giornateVinte` from (((`utente` join `view_0_giocatoristatistiche` on((`utente`.`id` = `view_0_giocatoristatistiche`.`idUtente`))) left join `view_2_giornatevinte` on((`utente`.`id` = `view_2_giornatevinte`.`idUtente`))) left join `punteggio` on((`utente`.`id` = `punteggio`.`idUtente`))) group by `utente`.`id`;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `articolo`
--
ALTER TABLE `articolo`
  ADD CONSTRAINT `articolo_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `articolo_ibfk_2` FOREIGN KEY (`idGiornata`) REFERENCES `giornata` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `articolo_ibfk_3` FOREIGN KEY (`idLega`) REFERENCES `lega` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`idLega`) REFERENCES `lega` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `formazione`
--
ALTER TABLE `formazione`
  ADD CONSTRAINT `formazione_ibfk_1` FOREIGN KEY (`idGiornata`) REFERENCES `giornata` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formazione_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formazione_ibfk_3` FOREIGN KEY (`idCapitano`) REFERENCES `giocatore` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `formazione_ibfk_4` FOREIGN KEY (`idVCapitano`) REFERENCES `giocatore` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `formazione_ibfk_5` FOREIGN KEY (`idVVCapitano`) REFERENCES `giocatore` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `giocatore`
--
ALTER TABLE `giocatore`
  ADD CONSTRAINT `giocatore_ibfk_1` FOREIGN KEY (`idClub`) REFERENCES `club` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `punteggio`
--
ALTER TABLE `punteggio`
  ADD CONSTRAINT `punteggio_ibfk_1` FOREIGN KEY (`idGiornata`) REFERENCES `giornata` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `punteggio_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `punteggio_ibfk_3` FOREIGN KEY (`idLega`) REFERENCES `lega` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `schieramento`
--
ALTER TABLE `schieramento`
  ADD CONSTRAINT `schieramento_ibfk_1` FOREIGN KEY (`idFormazione`) REFERENCES `formazione` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schieramento_ibfk_2` FOREIGN KEY (`idGiocatore`) REFERENCES `giocatore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `selezione`
--
ALTER TABLE `selezione`
  ADD CONSTRAINT `selezione_ibfk_1` FOREIGN KEY (`idLega`) REFERENCES `lega` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selezione_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selezione_ibfk_3` FOREIGN KEY (`idGiocatoreOld`) REFERENCES `giocatore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selezione_ibfk_4` FOREIGN KEY (`idGiocatoreNew`) REFERENCES `giocatore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `squadra`
--
ALTER TABLE `squadra`
  ADD CONSTRAINT `squadra_ibfk_1` FOREIGN KEY (`idLega`) REFERENCES `lega` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `squadra_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `squadra_ibfk_3` FOREIGN KEY (`idGiocatore`) REFERENCES `giocatore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `trasferimento`
--
ALTER TABLE `trasferimento`
  ADD CONSTRAINT `trasferimento_ibfk_1` FOREIGN KEY (`idGiocatoreOld`) REFERENCES `giocatore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trasferimento_ibfk_2` FOREIGN KEY (`idGiocatoreNew`) REFERENCES `giocatore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trasferimento_ibfk_3` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trasferimento_ibfk_4` FOREIGN KEY (`idGiornata`) REFERENCES `giornata` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `utente_ibfk_1` FOREIGN KEY (`idLega`) REFERENCES `lega` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `voto`
--
ALTER TABLE `voto`
  ADD CONSTRAINT `voto_ibfk_1` FOREIGN KEY (`idGiocatore`) REFERENCES `giocatore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `voto_ibfk_2` FOREIGN KEY (`idGiornata`) REFERENCES `giornata` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
