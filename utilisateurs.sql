-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : sql107.infinityfree.com
-- Généré le :  mar. 09 juin 2026 à 16:37
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
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `email`, `mot_de_passe`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@cinemasite.fr', '3eb3fe66b31e3b4d10fa70b5cad49c7112294af6ae4e476a1c405155d45aa121', 'admin', '2026-06-09 04:25:04'),
(2, 'alice', 'alice@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(3, 'bob', 'bob@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(4, 'charlie', 'charlie@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(5, 'diana', 'diana@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(6, 'ethan', 'ethan@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(7, 'fanny', 'fanny@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(8, 'gabriel', 'gabriel@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(9, 'hugo', 'hugo@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(10, 'inès', 'ines@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(11, 'julian', 'julian@mail.fr', '46708f23d682fef9aa996ecbb139bfb6c9ffdc039905ad6ad5c85a88b9411d97', 'user', '2026-06-09 04:25:04'),
(12, 'malcom', 'malcom@mail.fr', 'c0d935501290eb3afceaae4001db98b400c2e61340deed19e0692123f2b33392', 'user', '2026-06-09 13:31:13');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
