-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 16 Février 2012 à 16:17
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `france`
--

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE IF NOT EXISTS `departements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `departements`
--

INSERT INTO `departements` (`id`, `nom`) VALUES
(1, 'AIN'),
(2, 'AISNE');

-- --------------------------------------------------------

--
-- Structure de la table `villes`
--

CREATE TABLE IF NOT EXISTS `villes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commune` varchar(255) NOT NULL,
  `CP` int(5) NOT NULL,
  `INSEE` int(5) NOT NULL,
  `departement` smallint(1) NOT NULL,
  `habitant` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `villes`
--

INSERT INTO `villes` (`id`, `commune`, `CP`, `INSEE`, `departement`, `habitant`) VALUES
(2, 'L ABERGEMENT CLEMENCIAT', 14000, 1001, 1, 787),
(3, 'L ABERGEMENT DE VAREY', 16400, 1002, 1, 207),
(4, 'AMAREINS', 10900, 1003, 1, 1270),
(5, 'AMBERIEU EN BUGEY', 15000, 1004, 1, 13350),
(6, 'AMBERIEUX EN DOMBES', 13300, 1005, 1, 1592),
(7, 'AMBLEON', 13000, 1006, 1, 120),
(8, 'AMBRONAY', 15000, 1007, 1, 2328),
(9, 'AMBUTRIX', 15000, 1008, 2, 660),
(10, 'ANDERT ET CONDON', 13000, 1009, 2, 336),
(11, 'ANGLEFORT', 13500, 1010, 2, 960),
(12, 'APREMONT', 11000, 1011, 2, 352),
(13, 'ARANC', 11100, 1012, 2, 297),
(14, 'ARANDAS', 12300, 1013, 2, 165),
(15, 'ARBENT', 11000, 1014, 2, 3493),
(16, 'ARBIGNIEU', 13000, 1015, 2, 471);