-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : sql107.infinityfree.com
-- Généré le :  mar. 16 juin 2026 à 09:49
-- Version du serveur :  11.4.12-MariaDB
-- Version de PHP :  7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `if0_42137935_coupez`
--

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

CREATE TABLE `films` (
  `id` int(11) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `annee` year(4) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL DEFAULT 0,
  `cache` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id`, `titre`, `description`, `annee`, `genre`, `image`, `created_at`) VALUES
(1, 'Parasite', 'film prenant qui joue sur la culpabilite', 2019, 'horeur/thriller', NULL, '2026-06-09 04:25:04'),
(2, 'Le Village', 'epoustouflant', 2004, 'horreur/film a enigme', NULL, '2026-06-09 04:25:04');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `films`
--
ALTER TABLE `films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
