-- Fichier d'initialisation de la base de données Résineo
-- Importez ce fichier dans une base de données vide.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Structure de la base de données
--

-- Désactivation temporaire des contraintes de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Nettoyage préalable (si les tables existent déjà)
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `conversation_items`;
DROP TABLE IF EXISTS `conversations`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `categories`;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'client',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL, -- Renommé de img à image
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `scent` varchar(50) DEFAULT NULL,
  `tool_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT 'open',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `conversation_items`
--

CREATE TABLE `conversation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `conversation_items_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversation_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `sender_id` (`sender_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- DONNÉES DE TEST (FIXTURES)
-- --------------------------------------------------------

-- Categories
INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Résines'),
(2, 'Entretien'),
(3, 'Outillage');

-- Users
-- ATTENTION : Remplace la chaîne ci-dessous par le VRAI hash que tu as généré pour "demo"
INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `role`) VALUES
('Clarisse', 'Ferand', 'clarisse.ferand@gmail.com', '$2y$10$jT3SOP68XLJxxf9SVd0qUOlRPSbkp2raRYdtUbTJXjv6ttgrEQA/6', 'admin'),
('Jean', 'Dupont', 'jean.dupont@gmail.com', '$2y$10$jT3SOP68XLJxxf9SVd0qUOlRPSbkp2raRYdtUbTJXjv6ttgrEQA/6', 'client'),
('Marc', 'Martin', 'marc.martin@pro-btp.fr', '$2y$10$jT3SOP68XLJxxf9SVd0qUOlRPSbkp2raRYdtUbTJXjv6ttgrEQA/6', 'client'),
('Sophie', 'Lefevre', 'sophie.design@gmail.com', '$2y$10$jT3SOP68XLJxxf9SVd0qUOlRPSbkp2raRYdtUbTJXjv6ttgrEQA/6', 'client');

-- Products (avec colonne image)
INSERT INTO `products` (`category_id`, `name`, `image`, `description`, `price`, `color`) VALUES
(1, 'Résineo Drain 10kg', 'img/resineo-drain.png', 'Le revêtement drainant par excellence. Idéal pour les plages de piscine et allées piétonnes.', 52.00, 'Sable'),
(1, 'Résineo Grip 10kg', 'img/resineo-grip.png', 'Une formulation spécifique pour une adhérence maximale. Recommandé pour les rampes d\'accès.', 54.00, 'Sable'),
(1, 'Résineo Arbre 10kg', 'img/resineo-arbre.png', 'Protégez et embellissez vos entourages d\'arbres. Perméable à l\'eau et à l\'air.', 50.00, 'Terracotta'),
(1, 'Résineo Renov 10kg', 'img/resineo-renov.png', 'La solution idéale pour la rénovation de sols anciens. S\'applique directement sur béton.', 56.00, 'Ardoise'),
(1, 'Résineo Jeux 10kg', 'img/resineo-jeux.png', 'Un revêtement souple et amortissant conçu pour les aires de jeux pour enfants.', 47.00, 'Sable'),
(1, 'Résineo Marbre 10kg', 'img/resineo-marbre.png', 'L\'élégance du marbre roulé pour vos extérieurs. Granulats sélectionnés pour leur noblesse.', 24.00, 'Galet'),
(1, 'Résineo Minerall 10kg', 'img/resineo-minerall.png', 'Aspect minéral brut pour un rendu contemporain et naturel.', 56.00, 'Galet'),
(1, 'Résineo Quartz 10kg', 'img/resineo-quartz.png', 'La résistance du quartz coloré pour des sols décoratifs haute performance.', 36.00, 'Galet');

INSERT INTO `products` (`category_id`, `name`, `image`, `description`, `price`, `scent`) VALUES
(2, 'Résineo Badigeon 5kg', 'img/resineo-badigeon.png', 'Raviveur de couleurs professionnel. Redonne de l\'éclat aux anciennes réalisations.', 36.00, 'Oui'),
(2, 'Résineo Lissant 5L', 'img/resineo-lissant.png', 'Entretenez efficacement et durablement votre résine de sol avec le Résineo Lissant.', 26.00, 'Oui'),
(2, 'Résineo Nettoyant Outils', 'img/resineo-nettoyant.png', 'Solvant puissant sans odeur pour le nettoyage efficace de vos platoirs.', 24.00, 'Non'),
(2, 'Résineo Entretien', 'img/resineo-entretien.png', 'Shampoing dégraissant pour l\'entretien courant de vos surfaces en résine.', 23.00, 'Oui');

INSERT INTO `products` (`category_id`, `name`, `image`, `description`, `price`, `tool_type`) VALUES
(3, 'Platoir en Komadur', 'img/platoir.png', 'Platoir spécifique en plastique Komadur évitant les traces noires lors du lissage.', 16.00, 'Platoir'),
(3, 'Rateau à 2 vis', 'img/rateau.png', 'Râteau réglable spécial résine. Permet de tirer et régler l\'épaisseur du mélange.', 24.00, 'Rateau'),
(3, 'Spatule dentée (250/7mm)', 'img/spatule-dentee.png', 'Spatule crantée B3 pour l\'application de la résine d\'accroche (primaire).', 18.00, 'Spatule'),
(3, 'Spatule dentée (260/8mm)', 'img/spatule-dentee.png', 'Spatule large crantée pour les surfaces importantes.', 22.00, 'Spatule');

-- Conversations
INSERT INTO `conversations` (`id`, `user_id`, `title`, `address`, `postal_code`, `city`, `status`, `created_at`) VALUES
(1, 2, 'Devis Terrasse Piscine 40m2', '12 Rue des Lilas', '31000', 'Toulouse', 'open', '2023-10-25 09:30:00'),
(2, 3, 'Rénovation allée garage', '5 Avenue du Progrès', '69002', 'Lyon', 'quoted', '2023-10-26 14:15:00');

-- Conversation Items
INSERT INTO `conversation_items` (`conversation_id`, `product_id`, `quantity`) VALUES
(1, 1, 4),
(1, 13, 1),
(2, 4, 10),
(2, 14, 1);

-- Messages
INSERT INTO `messages` (`conversation_id`, `sender_id`, `content`, `created_at`) VALUES
(1, 2, 'Bonjour, je souhaite un devis pour une terrasse de piscine de 40m2 environ. J\'ai besoin de 4 kits de Résineo Drain et d\'un platoir.', '2023-10-25 09:30:00'),
(1, 1, 'Bonjour Monsieur Dupont. Je vous confirme que nous avons bien ces produits en stock. Je vous prépare l\'offre commerciale.', '2023-10-25 10:45:00'),
(1, 2, 'Parfait, merci pour votre réactivité. J\'attends votre retour.', '2023-10-25 11:00:00'),
(2, 3, 'Bonjour, je dois rénover une allée carrelée très abîmée. Est-ce que le Résineo Renov accroche bien sur du carrelage lisse ?', '2023-10-26 14:15:00'),
(2, 1, 'Bonjour Marc. Oui tout à fait, le Résineo Renov est conçu pour cela. Il faut cependant bien nettoyer le support avant application.', '2023-10-26 15:20:00');

-- Réactivation des contraintes
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;