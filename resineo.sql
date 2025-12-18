-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 18 déc. 2025 à 16:45
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
  `title` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'open',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `title`, `status`, `created_at`) VALUES
(1, 2, 'Devis Terrasse Piscine 40m2', 'open', '2023-10-25 09:30:00'),
(3, 7, 'Support Client', 'open', '2025-12-18 14:04:51');

-- --------------------------------------------------------

--
-- Structure de la table `conversation_items`
--

CREATE TABLE `conversation_items` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conversation_items`
--

INSERT INTO `conversation_items` (`id`, `conversation_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 4),
(2, 1, 13, 1);

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
(1, 1, 2, 'text', 'Bonjour, je souhaite un devis pour une terrasse de piscine de 40m2 environ. J\'ai besoin de 4 kits de Résineo Drain et d\'un platoir.', '2023-10-25 09:30:00'),
(2, 1, 1, 'text', 'Bonjour Monsieur Dupont. Je vous confirme que nous avons bien ces produits en stock. Je vous prépare l\'offre commerciale.', '2023-10-25 10:45:00'),
(3, 1, 2, 'text', 'Parfait, merci pour votre réactivité. J\'attends votre retour.', '2023-10-25 11:00:00'),
(6, 1, 2, 'text', 'test', '2025-12-12 15:59:42'),
(7, 1, 2, 'quote_request', '{\"user_message\":\"test\",\"items\":[{\"name\":\"R\\u00e9sineo Drain 10kg\",\"price\":52,\"image\":\"img\\/resineo-drain.png\",\"quantity\":1}]}', '2025-12-12 16:33:22'),
(8, 1, 1, 'offer', '{\"type\":\"offer\",\"items\":[{\"name\":\"Résineo Drain 10kg\",\"price\":52,\"quantity\":3,\"image\":\"img/resineo-drain.png\"},{\"name\":\"Résineo Nettoyant Outils\",\"price\":22,\"quantity\":1,\"image\":\"img/resineo-nettoyant.png\"}],\"user_message\":\"Je vous propose ceci\"}', '2025-12-18 10:32:01'),
(9, 1, 2, 'text', 'J\'achète !\r\n', '2025-12-18 10:33:59'),
(10, 1, 2, 'quote_request', '{\"user_message\":\"test\",\"items\":[{\"name\":\"Rateau \\u00e0 2 vis\",\"price\":24,\"image\":\"img\\/rateau.png\",\"quantity\":1},{\"name\":\"Platoir en Komadur\",\"price\":16,\"image\":\"img\\/platoir.png\",\"quantity\":1}]}', '2025-12-18 13:55:46'),
(11, 3, 7, 'quote_request', '{\"user_message\":\"test\",\"items\":[{\"name\":\"R\\u00e9sineo Grip 10kg\",\"price\":54,\"image\":\"img\\/resineo-grip.png\",\"quantity\":1},{\"name\":\"R\\u00e9sineo Arbre 10kg\",\"price\":50,\"image\":\"img\\/resineo-arbre.png\",\"quantity\":1}]}', '2025-12-18 14:04:51'),
(12, 3, 1, 'offer', '{\"type\":\"offer\",\"items\":[{\"name\":\"Résineo Grip 10kg\",\"price\":54,\"quantity\":1,\"image\":\"img/resineo-grip.png\"},{\"name\":\"Résineo Arbre 10kg\",\"price\":50,\"quantity\":1,\"image\":\"img/resineo-arbre.png\"}],\"user_message\":\"\"}', '2025-12-18 14:46:10');

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
(2, 'jean.dupont@gmail.com', '$2y$10$jT3SOP68XLJxxf9SVd0qUOlRPSbkp2raRYdtUbTJXjv6ttgrEQA/6', 'Jean', 'Dupont', 'client', '2025-11-28 15:07:25', 'img/applicateur3.png'),
(7, 'clarisse@gmail.com', '$2y$10$Tw9HKXZLM8CqUIJUTDRJDOo8YuJbsvORGgSw07Yqn8EHZ4c/IJvhq', 'Clarisse', 'Ferand', 'client', '2025-12-18 13:53:05', 'img/avatar-default.png');

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
-- Index pour la table `conversation_items`
--
ALTER TABLE `conversation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `product_id` (`product_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `conversation_items`
--
ALTER TABLE `conversation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `conversation_items`
--
ALTER TABLE `conversation_items`
  ADD CONSTRAINT `conversation_items_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

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
