-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 12 avr. 2023 à 08:27
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test3`
--

-- --------------------------------------------------------

--
-- Structure de la table `apm_category_list`
--

DROP TABLE IF EXISTS `apm_category_list`;
CREATE TABLE IF NOT EXISTS `apm_category_list` (
  `categoryId` int NOT NULL AUTO_INCREMENT,
  `categoryTitle` varchar(255) NOT NULL,
  `categoryDescription` varchar(255) NOT NULL,
  `groupId` int NOT NULL,
  `categoryRank` int NOT NULL,
  PRIMARY KEY (`categoryId`),
  KEY `category_entite_FK` (`groupId`)
) ENGINE=InnoDB AUTO_INCREMENT=310 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `apm_admin_list`
--

DROP TABLE IF EXISTS `apm_admin_list`;
CREATE TABLE IF NOT EXISTS `apm_admin_list` (
  `adminId` int NOT NULL AUTO_INCREMENT,
  `adminEmail` varchar(255) NOT NULL,
  `adminPassword` varchar(255) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `adminSuper` tinyint(1) NOT NULL,
  PRIMARY KEY (`adminId`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb3;


-- --------------------------------------------------------

--
-- Structure de la table `apm_group_list`
--

DROP TABLE IF EXISTS `apm_group_list`;
CREATE TABLE IF NOT EXISTS `apm_group_list` (
  `groupId` int NOT NULL AUTO_INCREMENT,
  `groupTitle` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `groupDescription` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `apm_item_list`
--

DROP TABLE IF EXISTS `apm_item_list`;
CREATE TABLE IF NOT EXISTS `apm_item_list` (
  `itemId` int NOT NULL AUTO_INCREMENT,
  `itemTitle` varchar(255) NOT NULL,
  `itemDescription` varchar(255) NOT NULL,
  `itemPrice` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `categoryId` int NOT NULL,
  `itemImagePath` varchar(255) NOT NULL,
  PRIMARY KEY (`itemId`),
  KEY `item_category_FK` (`categoryId`)
) ENGINE=InnoDB AUTO_INCREMENT=427 DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `category_list`
--
ALTER TABLE `apm_category_list`
  ADD CONSTRAINT `category_entite_FK` FOREIGN KEY (`groupId`) REFERENCES `apm_group_list` (`groupId`);

--
-- Contraintes pour la table `item_list`
--
ALTER TABLE `apm_item_list`
  ADD CONSTRAINT `item_category_FK` FOREIGN KEY (`categoryId`) REFERENCES `apm_category_list` (`categoryId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
