-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 08. pro 2015, 11:03
-- Verze serveru: 5.6.15-log
-- Verze PHP: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `forum`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `post_name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `content` text COLLATE utf8_czech_ci NOT NULL,
  `file` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_name` (`post_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=66 ;

--
-- Vypisuji data pro tabulku `posts`
--

INSERT INTO `posts` (`id`, `id_user`, `post_name`, `content`, `file`) VALUES
(34, 2, 'Za chvili to bude!', 'Chtel bych vam vsem povedet ze ze semestralkou z KIV/WEB uz finisuju. A jelikoz je to nejdelsi prispevek, ktery jsem sem pridal, tak na nem urcite nebudu zkouset delete button. Diky za pozornost a uzijte si let.', NULL),
(51, 3, 'Nevím co!', 'Chtěla jsem sem napsat něco kloudného, ale asi protože jsem blondýna mě nic nenapadá.', NULL),
(50, 2, 'Matějův druhý', 'Toto je můj druhý příspěvek. Hodnotte ho kladne!', NULL),
(52, 3, 'Už vím!', 'Přebarvila jsem se a tak jsem si vzpoměla co psát. Jenže už mi to vybledlo, takže zase nevim :-(', NULL),
(60, 2, 'a', 'b', '12277981_10204932970994782_708447864_n.jpg'),
(65, 12, 'zkouska', 'Je to super', '12277981_10204932970994782_708447864_n.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_post` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `topic` int(11) DEFAULT NULL,
  `quality` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=94 ;

--
-- Vypisuji data pro tabulku `review`
--

INSERT INTO `review` (`id`, `id_post`, `id_user`, `topic`, `quality`) VALUES
(91, 60, 10, NULL, NULL),
(90, 50, 10, NULL, NULL),
(89, 52, 10, 3, 5),
(93, 51, 12, 5, 5),
(92, 65, 12, 1, 1),
(88, 51, 11, 4, 3),
(85, 50, 11, 3, 2),
(84, 34, 10, 2, 1),
(87, 51, 10, 4, 3),
(82, 34, 10, 2, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `nick` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=13 ;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `position`, `first_name`, `email`, `nick`, `password`) VALUES
(1, 'Administrator', 'Tony', 'tran@vietnam.vn', 'tran', '1234'),
(2, 'Autor', 'Matej', 'matej@seznam.cz', 'mates', '1234'),
(3, 'Autor', 'Petra', 'petra@andel.com', 'edita', '1234'),
(10, 'Recenzent', 'Johny', 'recenzent1@nejlepsi.com', 'rec1', '1234'),
(11, 'Recenzent', 'English', 'recenzent2@nejhorsi.com', 'rec2', '1234'),
(12, 'Recenzent', 'petr', 'petra@milacek.muj', 'Petan', 'petan');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
