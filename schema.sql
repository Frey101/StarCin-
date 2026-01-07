-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 09 déc. 2025 à 09:42
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cinema`
--

CREATE DATABASE IF NOT EXISTS cinema;
USE cinema;

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `film`
--

DROP TABLE IF EXISTS `film`;
CREATE TABLE IF NOT EXISTS `film` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `synopsis` text NOT NULL,
  `duree` int NOT NULL COMMENT 'Durée en minutes',
  `bandeannonce` varchar(255) DEFAULT NULL COMMENT 'URL de la bande annonce',
  `datediffusion` date DEFAULT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  PRIMARY KEY (`id_film`),
  KEY `idx_film_titre` (`titre`(250))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `film_resultat`
--

DROP TABLE IF EXISTS `film_resultat`;
CREATE TABLE IF NOT EXISTS `film_resultat` (
  `id_film` int NOT NULL,
  `id_resultat` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_resultat`),
  KEY `id_resultat` (`id_resultat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `forum`
--

DROP TABLE IF EXISTS `forum`;
CREATE TABLE IF NOT EXISTS `forum` (
  `id_forum` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `dateCreation` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_admin` int NOT NULL COMMENT 'Administrateur qui crée le forum',
  PRIMARY KEY (`id_forum`),
  KEY `id_admin` (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `contenu` text NOT NULL,
  `datePublication` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_utilisateur` int NOT NULL COMMENT 'Relation Ecrit avec Utilisateur',
  `id_forum` int NOT NULL COMMENT 'Message appartient à un Forum',
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_forum` (`id_forum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `navigateur`
--

DROP TABLE IF EXISTS `navigateur`;
CREATE TABLE IF NOT EXISTS `navigateur` (
  `id_navigateur` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_navigateur`),
  UNIQUE KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id_note` int NOT NULL AUTO_INCREMENT,
  `valeur` int NOT NULL COMMENT 'Note de 1 à 5',
  `id_voteur` int NOT NULL COMMENT 'Relation Enregistre_dans avec Voteur',
  `id_film` int NOT NULL COMMENT 'Relation Evalue avec Film',
  PRIMARY KEY (`id_note`),
  UNIQUE KEY `unique_voteur_film` (`id_voteur`,`id_film`),
  KEY `id_film` (`id_film`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `proposition_film`
--

DROP TABLE IF EXISTS `proposition_film`;
CREATE TABLE IF NOT EXISTS `proposition_film` (
  `id_propositionFilm` int NOT NULL AUTO_INCREMENT,
  `statut` enum('en_attente','acceptee','refusee') NOT NULL DEFAULT 'en_attente',
  `dateProposition` date NOT NULL,
  `dateReponse` date DEFAULT NULL,
  `commentaire` text,
  `id_admin` int NOT NULL COMMENT 'Relation Verifie avec Administrateur',
  `id_realisateur` int DEFAULT NULL COMMENT 'Realisateur lié à la proposition',
  PRIMARY KEY (`id_propositionFilm`),
  KEY `id_admin` (`id_admin`),
  KEY `id_realisateur` (`id_realisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `realisateur`
--

DROP TABLE IF EXISTS `realisateur`;
CREATE TABLE IF NOT EXISTS `realisateur` (
  `id_realisateur` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_realisateur`),
  UNIQUE KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

DROP TABLE IF EXISTS `resultat`;
CREATE TABLE IF NOT EXISTS `resultat` (
  `id_resultat` int NOT NULL AUTO_INCREMENT,
  `nbVotes` int NOT NULL DEFAULT '0',
  `pourcentage` float NOT NULL DEFAULT '0',
  `datePublication` date DEFAULT NULL,
  `estGagnant` tinyint(1) NOT NULL DEFAULT '0',
  `id_sessionvote` int NOT NULL COMMENT 'Relation Passe avec SessionVote',
  PRIMARY KEY (`id_resultat`),
  KEY `id_sessionvote` (`id_sessionvote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `session_vote`
--

DROP TABLE IF EXISTS `session_vote`;
CREATE TABLE IF NOT EXISTS `session_vote` (
  `id_sessionvote` int NOT NULL AUTO_INCREMENT,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `duree` int NOT NULL COMMENT 'Durée en jours',
  `annee` int NOT NULL,
  `id_admin` int NOT NULL COMMENT 'Administrateur qui gère la session',
  PRIMARY KEY (`id_sessionvote`),
  KEY `id_admin` (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `session_vote_film`
--

DROP TABLE IF EXISTS `session_vote_film`;
CREATE TABLE IF NOT EXISTS `session_vote_film` (
  `id_sessionvote` int NOT NULL,
  `id_film` int NOT NULL,
  PRIMARY KEY (`id_sessionvote`,`id_film`),
  KEY `id_film` (`id_film`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_film`
--

DROP TABLE IF EXISTS `utilisateur_film`;
CREATE TABLE IF NOT EXISTS `utilisateur_film` (
  `id_utilisateur` int NOT NULL,
  `id_film` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_film`),
  KEY `id_film` (`id_film`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_resultat`
--

DROP TABLE IF EXISTS `utilisateur_resultat`;
CREATE TABLE IF NOT EXISTS `utilisateur_resultat` (
  `id_utilisateur` int NOT NULL,
  `id_resultat` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_resultat`),
  KEY `id_resultat` (`id_resultat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `voteur`
--

DROP TABLE IF EXISTS `voteur`;
CREATE TABLE IF NOT EXISTS `voteur` (
  `id_voteur` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_voteur`),
  UNIQUE KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `ville`, `role`) VALUES
(1, 'Admin', 'StarCin', 'admin@starcin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin'),
(2, '', '', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'user');

INSERT INTO `administrateur` (`id_admin`, `id_utilisateur`) VALUES
(1, 1);

INSERT INTO `film` (`id_film`, `titre`, `synopsis`, `duree`, `bandeannonce`, `datediffusion`, `categorie`, `annee`) VALUES
(1, 'Inception', 'A mind-bending thriller', 148, NULL, NULL, 'Science-Fiction', 2010),
(2, 'Interstellar', 'A space adventure', 169, NULL, NULL, 'Science-Fiction', 2014);

INSERT INTO `forum` (`id_forum`, `titre`, `dateCreation`, `id_admin`) VALUES
(1, 'General Discussion', CURRENT_TIMESTAMP, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `film_resultat`
--
ALTER TABLE `film_resultat`
  ADD CONSTRAINT `film_resultat_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `film_resultat_ibfk_2` FOREIGN KEY (`id_resultat`) REFERENCES `resultat` (`id_resultat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrateur` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_forum`) REFERENCES `forum` (`id_forum`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `navigateur`
--
ALTER TABLE `navigateur`
  ADD CONSTRAINT `navigateur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`id_voteur`) REFERENCES `voteur` (`id_voteur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `proposition_film`
--
ALTER TABLE `proposition_film`
  ADD CONSTRAINT `proposition_film_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrateur` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proposition_film_ibfk_2` FOREIGN KEY (`id_realisateur`) REFERENCES `realisateur` (`id_realisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `realisateur`
--
ALTER TABLE `realisateur`
  ADD CONSTRAINT `realisateur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD CONSTRAINT `resultat_ibfk_1` FOREIGN KEY (`id_sessionvote`) REFERENCES `session_vote` (`id_sessionvote`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `session_vote`
--
ALTER TABLE `session_vote`
  ADD CONSTRAINT `session_vote_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrateur` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `session_vote_film`
--
ALTER TABLE `session_vote_film`
  ADD CONSTRAINT `session_vote_film_ibfk_1` FOREIGN KEY (`id_sessionvote`) REFERENCES `session_vote` (`id_sessionvote`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `session_vote_film_ibfk_2` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur_film`
--
ALTER TABLE `utilisateur_film`
  ADD CONSTRAINT `utilisateur_film_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `utilisateur_film_ibfk_2` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur_resultat`
--
ALTER TABLE `utilisateur_resultat`
  ADD CONSTRAINT `utilisateur_resultat_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `utilisateur_resultat_ibfk_2` FOREIGN KEY (`id_resultat`) REFERENCES `resultat` (`id_resultat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `voteur`
--
ALTER TABLE `voteur`
  ADD CONSTRAINT `voteur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;