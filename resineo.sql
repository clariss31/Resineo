-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 20 déc. 2025 à 14:11
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
-- Base de données : `resineo`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Résines'),
(2, 'Entretien'),
(3, 'Outillage');

-- --------------------------------------------------------

--
-- Structure de la table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `created_at`) VALUES
(11, 26, '2025-12-20 13:41:23');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT 'text',
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `type`, `content`, `created_at`) VALUES
(31, 11, 26, 'text', 'Bonjour, je voudrais devenir applicateur', '2025-12-20 13:41:27'),
(32, 11, 1, 'text', 'Bonjour, bien sûr ! Je vous envoie le formulaire d\'inscription', '2025-12-20 13:42:04'),
(33, 11, 26, 'quote_request', '{\"user_message\":\"Bonjour, je voudrais un devis pour ces produits s\'il vous pla\\u00eet. Pouvez-vous me proposer un tarif pr\\u00e9f\\u00e9rentiel et me conseiller sur les produits d\'entretien ?\",\"items\":[{\"name\":\"Rateau \\u00e0 2 vis\",\"price\":24,\"image\":\"img\\/rateau.png\",\"quantity\":1},{\"name\":\"Platoir en Komadur\",\"price\":16,\"image\":\"img\\/platoir.png\",\"quantity\":2},{\"name\":\"Spatule dent\\u00e9e (250\\/7mm)\",\"price\":18,\"image\":\"img\\/spatule-dentee.png\",\"quantity\":1}]}', '2025-12-20 13:43:23'),
(34, 11, 1, 'offer', '{\"type\":\"offer\",\"items\":[{\"name\":\"Rateau à 2 vis\",\"price\":24,\"quantity\":1,\"image\":\"img/rateau.png\"},{\"name\":\"Platoir en Komadur\",\"price\":15,\"quantity\":2,\"image\":\"img/platoir.png\"},{\"name\":\"Spatule dentée (250/7mm)\",\"price\":17,\"quantity\":1,\"image\":\"img/spatule-dentee.png\"},{\"name\":\"Résineo Lissant 5L\",\"price\":26,\"quantity\":1,\"image\":\"img/resineo-lissant.png\"}],\"user_message\":\"Bonjour, voici une offre. Je vous propose le résineo lissant pour l\'entretien.\"}', '2025-12-20 13:47:11'),
(35, 11, 26, 'text', 'J\'achète !', '2025-12-20 13:47:40'),
(36, 11, 1, 'text', 'Très bien je vous envoie le devis par mail à signer.', '2025-12-20 13:48:10');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `scent` varchar(50) DEFAULT NULL,
  `tool_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `image`, `description`, `price`, `color`, `scent`, `tool_type`) VALUES
(1, 1, 'Résineo Drain 10kg', 'img/resineo-drain.png', 'Le revêtement drainant par excellence. Idéal pour les plages de piscine et allées piétonnes.', 52.00, 'Sable', NULL, NULL),
(2, 1, 'Résineo Grip 10kg', 'img/resineo-grip.png', 'Une formulation spécifique pour une adhérence maximale. Recommandé pour les rampes d\'accès.', 54.00, 'Sable', NULL, NULL),
(3, 1, 'Résineo Arbre 10kg', 'img/resineo-arbre.png', 'Protégez et embellissez vos entourages d\'arbres. Perméable à l\'eau et à l\'air.', 50.00, 'Terracotta', NULL, NULL),
(4, 1, 'Résineo Renov 10kg', 'img/resineo-renov.png', 'La solution idéale pour la rénovation de sols anciens. S\'applique directement sur béton.', 56.00, 'Ardoise', NULL, NULL),
(5, 1, 'Résineo Jeux 10kg', 'img/resineo-jeux.png', 'Un revêtement souple et amortissant conçu pour les aires de jeux pour enfants.', 47.00, 'Sable', NULL, NULL),
(6, 1, 'Résineo Marbre 10kg', 'img/resineo-marbre.png', 'L\'élégance du marbre roulé pour vos extérieurs. Granulats sélectionnés pour leur noblesse.', 24.00, 'Galet', NULL, NULL),
(7, 1, 'Résineo Minerall 10kg', 'img/resineo-minerall.png', 'Aspect minéral brut pour un rendu contemporain et naturel.', 56.00, 'Galet', NULL, NULL),
(8, 1, 'Résineo Quartz 10kg', 'img/resineo-quartz.png', 'La résistance du quartz coloré pour des sols décoratifs haute performance.', 36.00, 'Galet', NULL, NULL),
(9, 2, 'Résineo Badigeon 5kg', 'img/resineo-badigeon.png', 'Raviveur de couleurs professionnel. Redonne de l\'éclat aux anciennes réalisations.', 36.00, NULL, 'Oui', NULL),
(10, 2, 'Résineo Lissant 5L', 'img/resineo-lissant.png', 'Entretenez efficacement et durablement votre résine de sol avec le Résineo Lissant.', 26.00, NULL, 'Oui', NULL),
(11, 2, 'Résineo Nettoyant Outils', 'img/resineo-nettoyant.png', 'Solvant puissant sans odeur pour le nettoyage efficace de vos platoirs.', 24.00, NULL, 'Non', NULL),
(12, 2, 'Résineo Entretien', 'img/resineo-entretien.png', 'Shampoing dégraissant pour l\'entretien courant de vos surfaces en résine.', 23.00, NULL, 'Oui', NULL),
(13, 3, 'Platoir en Komadur', 'img/platoir.png', 'Platoir spécifique en plastique Komadur évitant les traces noires lors du lissage.', 16.00, NULL, NULL, 'Platoir'),
(14, 3, 'Rateau à 2 vis', 'img/rateau.png', 'Râteau réglable spécial résine. Permet de tirer et régler l\'épaisseur du mélange.', 24.00, NULL, NULL, 'Rateau'),
(15, 3, 'Spatule dentée (250/7mm)', 'img/spatule-dentee.png', 'Spatule crantée B3 pour l\'application de la résine d\'accroche (primaire).', 18.00, NULL, NULL, 'Spatule'),
(16, 3, 'Spatule dentée (260/8mm)', 'img/spatule-dentee.png', 'Spatule large crantée pour les surfaces importantes.', 22.00, NULL, NULL, 'Spatule');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'client',
  `created_at` datetime DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`, `role`, `created_at`, `image`) VALUES
(1, 'clarisse.ferand@gmail.com', '$2y$10$lTESjTrO7BwBT2ctAFwKrusHlFXjXj2bvIywqC/4UsU5d7Eoz5/Ru', 'Clarisse', 'Ferand', 'admin', '2025-11-28 15:07:25', 'img/avatar-resineo.png'),
(26, 'jean.dupont@gmail.com', '$2y$10$Ga6CZ1kMBipHp5P.o1t64uLBpfrjrXDhgD4cQz7cRXjyhCxvIkZOC', 'Jean', 'Dupont', 'client', '2025-12-20 13:41:17', 'img/avatar-default.png');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `name` (`name`,`description`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
