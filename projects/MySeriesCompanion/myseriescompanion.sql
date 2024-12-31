-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 14 sep. 2024 à 09:45
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `myseriescompanion`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_cat` int NOT NULL AUTO_INCREMENT,
  `nom_cat` varchar(150) NOT NULL,
  `agemin_cat` int NOT NULL,
  `vignette_cat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_cat`, `nom_cat`, `agemin_cat`, `vignette_cat`) VALUES
(1, 'Drame', 18, 'assets/images/-18.png'),
(2, 'Science Fiction', 14, NULL),
(3, 'Horreur', 18, 'assets/images/-18.png'),
(4, 'Humour ', 10, NULL),
(5, 'Action', 16, NULL),
(6, 'Télé-réalité', 18, 'assets/images/-18.png'),
(7, 'Fantastique', 18, 'assets/images/-18.png'),
(8, 'Comédie', 12, 'assets/images/-12.png'),
(9, 'Thriller', 16, NULL),
(10, 'Mystère', 13, NULL),
(11, 'Historique', 16, NULL),
(12, 'Aventure', 10, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `serie`
--

DROP TABLE IF EXISTS `serie`;
CREATE TABLE IF NOT EXISTS `serie` (
  `id_serie` int NOT NULL AUTO_INCREMENT,
  `nom_serie` varchar(150) NOT NULL,
  `resume_serie` text NOT NULL,
  `vignette_serie` varchar(255) NOT NULL,
  `dateSortie_serie` date NOT NULL,
  `episodeActuel_serie` int NOT NULL,
  `saisonActuelle_serie` int NOT NULL,
  `terminee_serie` char(1) NOT NULL,
  `categorie_serie` int NOT NULL,
  PRIMARY KEY (`id_serie`),
  KEY `FK_Serie_Categorie` (`categorie_serie`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `serie`
--

INSERT INTO `serie` (`id_serie`, `nom_serie`, `resume_serie`, `vignette_serie`, `dateSortie_serie`, `episodeActuel_serie`, `saisonActuelle_serie`, `terminee_serie`, `categorie_serie`) VALUES
(1, 'Breaking Bad', 'Un enseignant de chimie se tourne vers la fabrication de méthamphétamine après avoir été diagnostiqué avec un cancer.', 'assets/images/breaking_bad.jfif', '2008-01-20', 62, 5, 'N', 5),
(2, 'Game of Thrones', 'Lutte pour le trône de fer dans un royaume fantastique rempli de magie et de dragons.', 'assets/images/game_of_thrones.jfif', '2011-04-17', 73, 8, 'O', 1),
(3, 'Stranger Things', 'Dans une petite ville des années 80, un groupe d\'enfants découvre des phénomènes paranormaux et un monde parallèle appelé \"The Upside Down\".', 'assets/images/stranger_things.jfif', '2016-07-15', 0, 0, '', 3),
(4, 'The Office (US)', 'Une comédie suivant le quotidien des employés de Dunder Mifflin sous la forme d\'un faux documentaire.', 'assets/images/the_office.jfif', '2005-03-24', 0, 0, '', 8),
(5, 'La Casa de Papel', 'Un mystérieux homme appelé \"Le Professeur\" planifie le plus grand braquage de l\'histoire en recrutant huit criminels.', 'assets/images/la_casa_de_papel.jfif', '2017-05-02', 0, 0, '', 4),
(6, 'Black Mirror', 'Une série d\'anthologie qui explore la dépendance de l\'humanité à la technologie à travers des histoires indépendantes.', 'assets/images/black_mirror.jfif', '2011-12-04', 0, 0, '', 3),
(7, 'Sherlock', 'Une adaptation moderne des aventures de Sherlock Holmes, le détective consultant, et de son partenaire le Dr Watson.', 'assets/images/sherlock.jfif', '2010-07-25', 0, 0, '', 10),
(8, 'Friends', 'Une sitcom classique qui suit les péripéties d\'un groupe d\'amis vivant à New York.', 'assets/images/friends.jfif', '1994-09-22', 0, 0, '', 8),
(9, 'The Crown', 'Une série dramatique qui retrace le règne de la reine Elizabeth II.', 'assets/images/the_crown.jfif', '2016-11-04', 0, 0, '', 11),
(10, 'The Mandalorian', 'Un chasseur de primes solitaire parcourt la galaxie loin de l\'autorité de la Nouvelle République.', 'assets/images/the_mandalorian.jfif', '2019-11-12', 0, 0, '', 7);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `serie`
--
ALTER TABLE `serie`
  ADD CONSTRAINT `FK_Serie_Categorie` FOREIGN KEY (`categorie_serie`) REFERENCES `categorie` (`id_cat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
