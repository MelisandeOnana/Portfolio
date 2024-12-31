-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 23 mai 2024 à 16:58
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mathindex`
--

-- --------------------------------------------------------

--
-- Structure de la table `classroom`
--

CREATE TABLE `classroom` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classroom`
--

INSERT INTO `classroom` (`id`, `name`) VALUES
(1, 'Seconde'),
(2, 'Première'),
(3, 'Terminale');

-- --------------------------------------------------------

--
-- Structure de la table `exercise`
--

CREATE TABLE `exercise` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `classroom_id` int(11) DEFAULT NULL,
  `thematic_id` int(11) DEFAULT NULL,
  `chapter` varchar(100) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `difficulty` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `origin_name` varchar(100) DEFAULT NULL,
  `origin_information` text DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `exercice_file_id` int(11) DEFAULT NULL,
  `correction_file_id` int(11) DEFAULT NULL,
  `origin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `exercise`
--

INSERT INTO `exercise` (`id`, `name`, `classroom_id`, `thematic_id`, `chapter`, `keywords`, `difficulty`, `duration`, `origin_name`, `origin_information`, `created_by_id`, `date`, `exercice_file_id`, `correction_file_id`, `origin_id`) VALUES
(1, 'Systèmes d\'équations linéaires', 2, 1, 'Algèbre', 'systèmes d\'équations linéaires, algèbre linéaire', 2, 1, 'Livre de Mathématique', 'Exercice sur la résolution de systèmes d\'équations linéaires.', 1, '2024-04-01', 1, 2, 1),
(2, 'Fractales et auto-similarité', 3, 2, 'Géométrie', 'fractales, auto-similarité, géométrie fractale', 5, 2, 'Manuel scolaire', 'Exercice sur les fractales et l\'auto-similarité en géométrie.', 1, '2024-04-02', 3, 4, 2),
(3, 'Congruences modulo n', 2, 2, 'Arithmétique', 'congruences, arithmétique modulaire, théorie des nombres', 6, 1, 'Livre de Mathématique', 'Exercice sur les congruences modulo n en théorie des nombres.', 1, '2024-04-02', 1, 2, 1),
(4, 'Tenseurs métriques', 3, 3, 'Analyse', 'tenseurs métriques, calcul différentiel, variétés différentielles', 8, 3, 'Manuel scolaire', 'Exercice sur les tenseurs métriques en calcul différentiel sur les variétés.', 1, '2024-03-11', 3, 4, 2),
(5, 'Opérations sur les matrices', 2, 5, 'Algèbre linéaire', 'matrices, opérations, algèbre linéaire', 4, 2, 'Livre de Mathématique', 'Exercice sur les opérations de base sur les matrices.', 1, '2024-03-03', 1, 2, 1),
(6, 'Géométrie hyperbolique', 3, 6, 'Géométrie', 'géométrie hyperbolique, espaces non euclidiens', 7, 2, 'Manuel scolaire', 'Exercice sur la géométrie non euclidienne et les espaces hyperboliques.', 1, '2024-03-18', 3, 4, 2),
(7, 'Intégrales généralisées', 1, 7, 'Analyse', 'intégrales, analyse fonctionnelle', 6, 1, 'Livre de Mathématique', 'Exercice sur les intégrales généralisées en analyse fonctionnelle.', 1, '2024-04-01', 1, 2, 1),
(8, 'Algorithmes de parcours de graphe', 2, 8, 'Algorithmes', 'graphes, algorithmes, théorie des graphes', 5, 1, 'Manuel scolaire', 'Exercice sur les algorithmes de parcours de graphe.', 1, '2024-04-03', 3, 4, 2),
(9, 'Cryptographie à clé publique', 3, 9, 'Cryptographie', 'cryptographie, sécurité, clé publique', 7, 2, 'Livre de Mathématique', 'Exercice sur la cryptographie à clé publique.', 1, '2024-04-06', 1, 2, 1),
(10, 'Fonctions holomorphes avancées', 1, 10, 'Fonctions holomorphes', 'fonctions, analyse complexe, holomorphes', 8, 3, 'Manuel scolaire', 'Exercice sur les fonctions holomorphes avancées.', 1, '2024-04-07', 3, 4, 2),
(11, 'Processus de Poisson', 2, 11, 'Processus stochastiques', 'processus de Poisson, probabilité, stochastique', 6, 2, 'Livre de Mathématique', 'Exercice sur les processus de Poisson en calcul stochastique.', 1, '2024-04-08', 1, 2, 1),
(12, 'Catégories abéliennes', 3, 12, 'Algèbre', 'catégories abéliennes, algèbre, théorie des catégories', 8, 4, 'Manuel scolaire', 'Exercice sur les catégories abéliennes.', 1, '2024-04-08', 3, 4, 2),
(13, 'Systèmes flous', 1, 13, 'Logique', 'logique floue, systèmes flous', 7, 1, 'Livre de Mathématique', 'Exercice sur les systèmes flous en logique floue.', 1, '2024-04-08', 1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `extension` varchar(10) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `file`
--

INSERT INTO `file` (`id`, `name`, `original_name`, `extension`, `size`) VALUES
(1, 'exercice-1', 'exercice-1-la-fonction-exponentielle-premiere', 'pdf', 123456),
(2, 'correction-fractales-et-auto-similarite', 'correction-exercice-1-second-degre-et-polynomes-premiere-2603', 'pdf', 123456),
(3, 'congruences-modulo-n', 'exercice-2-la-fonction-exponentielle-terminale-pdf', 'pdf', 123456),
(4, 'correction-congruences-modulo-n', 'correction-exercice-2-second-degre-et-polynomes-terminale-2604', 'pdf', 123456);

-- --------------------------------------------------------

--
-- Structure de la table `origin`
--

CREATE TABLE `origin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `origin`
--

INSERT INTO `origin` (`id`, `name`) VALUES
(1, 'Livre de mathématiques'),
(2, 'Manuel scolaire de mathématiques'),
(179, 'Manuel scolaire'),
(180, '2');

-- --------------------------------------------------------

--
-- Structure de la table `thematic`
--

CREATE TABLE `thematic` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `thematic`
--

INSERT INTO `thematic` (`id`, `name`) VALUES
(1, 'Algèbre linéaire'),
(2, 'Géométrie des fractales'),
(3, 'Théorie des nombres'),
(4, 'Calcul différentiel sur les variétés'),
(5, 'Algèbre linéaire avancée'),
(6, 'Géométrie non euclidienne'),
(7, 'Analyse fonctionnelle'),
(8, 'Théorie des graphes'),
(9, 'Cryptographie'),
(10, 'Analyse complexe avancée'),
(11, 'Calcul stochastique'),
(12, 'Théorie des catégories'),
(13, 'Logique floue');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `last_name`, `first_name`, `role`, `password`, `profile_photo_file`) VALUES
(1, 'melisande.onana@orange.fr', 'Onana', 'Mélisande', 'Administrateur', 'motdepasse123', 'Melisande_Onana.webp'),
(2, 'dauguet.mathis@gmail.com', 'Dauguet', 'Mathis', 'Administrateur', 'Mathis123#', 'Mathis_Dauguet.jpg'),
(3, 'morel.quentin@gmail.com', 'Morel', 'Quentin', 'Contributeur', 'password3', 'Quentin_Morel.png'),
(4, 'decaux.allan@gmail.com', 'Decaux', 'Allan', 'Administrateur', 'password', 'Allan_Decaux.jpg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `classroom`
--
ALTER TABLE `classroom`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `exercise`
--
ALTER TABLE `exercise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_id` (`classroom_id`),
  ADD KEY `thematic_id` (`thematic_id`),
  ADD KEY `fk_id_file_exercice` (`exercice_file_id`),
  ADD KEY `fk_id_file_correction` (`correction_file_id`),
  ADD KEY `fk_exercise_origin` (`origin_id`);

--
-- Index pour la table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `origin`
--
ALTER TABLE `origin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `thematic`
--
ALTER TABLE `thematic`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `classroom`
--
ALTER TABLE `classroom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `exercise`
--
ALTER TABLE `exercise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `origin`
--
ALTER TABLE `origin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT pour la table `thematic`
--
ALTER TABLE `thematic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `exercise`
--
ALTER TABLE `exercise`
  ADD CONSTRAINT `fk_classroom_id` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `fk_exercise_origin` FOREIGN KEY (`origin_id`) REFERENCES `origin` (`id`),
  ADD CONSTRAINT `fk_id_file_correction` FOREIGN KEY (`correction_file_id`) REFERENCES `file` (`id`),
  ADD CONSTRAINT `fk_id_file_exercice` FOREIGN KEY (`exercice_file_id`) REFERENCES `file` (`id`),
  ADD CONSTRAINT `fk_thematic_id` FOREIGN KEY (`thematic_id`) REFERENCES `thematic` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
