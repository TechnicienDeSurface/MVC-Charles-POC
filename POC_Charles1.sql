-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 28 sep. 2025 à 19:47
-- Version du serveur : 8.0.43-0ubuntu0.24.04.2
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `POC_Charles1`
--

-- --------------------------------------------------------

--
-- Structure de la table `albums`
--

CREATE TABLE `albums` (
  `id` int NOT NULL,
  `track_number` int NOT NULL,
  `editor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ;

--
-- Déchargement des données de la table `albums`
--

INSERT INTO `albums` (`id`, `track_number`, `editor`) VALUES
(7, 17, 'Apple Records'),
(8, 9, 'Harvest Records'),
(9, 9, 'Epic Records');

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `page_number` int NOT NULL
) ;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `page_number`) VALUES
(1, 96),
(2, 328),
(3, 123);

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

CREATE TABLE `emprunts` (
  `id` int NOT NULL,
  `media_id` int NOT NULL,
  `user_id` int NOT NULL,
  `date_emprunt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_retour` timestamp NULL DEFAULT NULL,
  `statut` enum('en_cours','rendu','en_retard') COLLATE utf8mb4_unicode_ci DEFAULT 'en_cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  `type_media` enum('book','movie','album') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `titre`, `auteur`, `disponible`, `type_media`, `date_creation`, `date_modification`) VALUES
(1, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 1, 'book', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(2, '1984', 'George Orwell', 1, 'book', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(3, 'L\'Étranger', 'Albert Camus', 1, 'book', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(4, 'Inception', 'Christopher Nolan', 1, 'movie', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(5, 'Pulp Fiction', 'Quentin Tarantino', 1, 'movie', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(6, 'Le Fabuleux Destin d\'Amélie Poulain', 'Jean-Pierre Jeunet', 1, 'movie', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(7, 'Abbey Road', 'The Beatles', 1, 'album', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(8, 'The Dark Side of the Moon', 'Pink Floyd', 1, 'album', '2025-09-22 18:16:07', '2025-09-22 18:16:07'),
(9, 'Thriller', 'Michael Jackson', 1, 'album', '2025-09-22 18:16:07', '2025-09-22 18:16:07');

-- --------------------------------------------------------

--
-- Structure de la table `movies`
--

CREATE TABLE `movies` (
  `id` int NOT NULL,
  `duration` decimal(4,2) NOT NULL,
  `genre` enum('Action','Comédie','Drame','Horreur','Romance','Thriller','Science-Fiction','Documentaire','Animation','Fantastique') COLLATE utf8mb4_unicode_ci NOT NULL
) ;

--
-- Déchargement des données de la table `movies`
--

INSERT INTO `movies` (`id`, `duration`, `genre`) VALUES
(4, 2.48, 'Science-Fiction'),
(5, 2.58, 'Thriller'),
(6, 2.02, 'Romance');

-- --------------------------------------------------------

--
-- Structure de la table `songs`
--

CREATE TABLE `songs` (
  `id` int NOT NULL,
  `album_id` int NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duree` decimal(5,2) NOT NULL,
  `note` int DEFAULT '0',
  `ordre_dans_album` int DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Déchargement des données de la table `songs`
--

INSERT INTO `songs` (`id`, `album_id`, `titre`, `duree`, `note`, `ordre_dans_album`, `date_creation`) VALUES
(1, 7, 'Come Together', 4.20, 5, 1, '2025-09-22 18:16:07'),
(2, 7, 'Something', 3.03, 4, 2, '2025-09-22 18:16:07'),
(3, 7, 'Maxwell\'s Silver Hammer', 3.27, 3, 3, '2025-09-22 18:16:07'),
(4, 7, 'Oh! Darling', 3.26, 4, 4, '2025-09-22 18:16:07'),
(5, 7, 'Here Comes the Sun', 3.05, 5, 5, '2025-09-22 18:16:07'),
(6, 8, 'Speak to Me', 1.30, 4, 1, '2025-09-22 18:16:07'),
(7, 8, 'Breathe', 2.43, 5, 2, '2025-09-22 18:16:07'),
(8, 8, 'Time', 6.53, 5, 3, '2025-09-22 18:16:07'),
(9, 8, 'Money', 6.23, 5, 4, '2025-09-22 18:16:07');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`) VALUES
(1, 'admin', 'admin@example.com', 'HASH_ADMIN'),
(2, 'qsdplqkds', 'lqskdjq@emial.com', '$argon2i$v=19$m=65536,t=4,p=1$ZE9iM3V0QUR5TXA2Wlk0Sg$VG+yQRtZl3Z3Oe3lubHQ120XoBZ2n5ragCaLlkh1G3g'),
(3, 'test', 'test@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$anl3Q3kzN0Ntbkc4NXFMcw$wYxM95+herLQE4mm35AMmvIR61OypRoTlwiSsVXFX4s');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_editor` (`editor`);

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_media_emprunt` (`media_id`),
  ADD KEY `idx_user_emprunt` (`user_id`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_date_emprunt` (`date_emprunt`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_titre` (`titre`),
  ADD KEY `idx_auteur` (`auteur`),
  ADD KEY `idx_disponible` (`disponible`),
  ADD KEY `idx_type` (`type_media`);

--
-- Index pour la table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_genre` (`genre`),
  ADD KEY `idx_duration` (`duration`);

--
-- Index pour la table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_song_order` (`album_id`,`ordre_dans_album`),
  ADD KEY `idx_album` (`album_id`),
  ADD KEY `idx_titre_song` (`titre`),
  ADD KEY `idx_note` (`note`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `emprunts`
--
ALTER TABLE `emprunts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD CONSTRAINT `emprunts_ibfk_1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emprunts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
