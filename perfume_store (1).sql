-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 09:17 PM
-- Server version: 8.0.43
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perfume_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'men'),
(3, 'unisex'),
(2, 'women');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `rating` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `message`, `rating`, `created_at`) VALUES
(2, 6, 'fuyftyd', 4, '2026-05-25 17:31:30'),
(3, 6, 'sfvyuads', 5, '2026-05-25 18:30:02');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `items` text NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `email`, `phone`, `address`, `total`, `items`, `status`, `created_at`) VALUES
(1, 6, 'alketa.thaci@student.uni-pr.edu', '00000000000', 'dsd', 220.00, '[{\"name\":\"Acqua di Gio\",\"price\":\"110.00\",\"qty\":2,\"subtotal\":220}]', 'pending', '2026-05-26 08:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `image`, `created_at`) VALUES
(1, 1, 'Dior Sauvage testim', 120.00, '../assets/images/product_6a0f6f1ef28ab3.79854417.jpg', '2026-05-20 08:14:30'),
(2, 1, 'Bleu de Chanel', 140.00, '../assets/images/image12.jpg', '2026-05-20 08:14:30'),
(3, 1, 'Acqua di Gio', 110.00, '../assets/images/image4.webp', '2026-05-20 08:14:30'),
(4, 1, 'Versace Eros', 100.00, '../assets/images/image14.avif', '2026-05-20 08:14:30'),
(6, 2, 'Gisada', 150.00, '../assets/images/image9.jpg', '2026-05-20 08:14:30'),
(7, 2, 'YSL Libre', 130.00, '../assets/images/image44.avif', '2026-05-20 08:14:30'),
(8, 2, 'Valentino', 125.00, '../assets/images/image23.jpg', '2026-05-20 08:14:30'),
(9, 2, 'Angel\'s Share', 145.00, '../assets/images/image3.jpg', '2026-05-20 08:14:30'),
(10, 2, 'Armani My Way', 135.00, '../assets/images/image1.jpg', '2026-05-20 08:14:30'),
(11, 3, 'Jasmin Noir', 190.00, '../assets/images/image5.jpg', '2026-05-20 08:14:30'),
(12, 3, 'Maison Fragrance', 160.00, '../assets/images/image15.jpg', '2026-05-20 08:14:30'),
(13, 1, 'Amuage', 110.00, '../assets/images/image10.jpg', '2026-05-20 08:14:30'),
(14, 3, 'Versace', 180.00, '../assets/images/image14.jpg', '2026-05-20 08:14:30'),
(15, 3, 'Montale Paris', 210.00, '../assets/images/image4.jpg', '2026-05-20 08:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `admin_code` varchar(50) DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `verification_code_hash` varchar(255) DEFAULT NULL,
  `verification_expires_at` datetime DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `admin_code`, `email_verified`, `verification_code_hash`, `verification_expires_at`, `profile_image`) VALUES
(1, 'admin', 'admin@maison.com', '$2y$10$8uQIsMbJBvqeqiykBQ3uHeh.NVEIONvaltLMX.xO6vffQV8Q0xb6q', 'admin', 'MAISON-2026', 1, NULL, NULL, NULL),
(2, 'user', 'user@perfume-store.test', '$2y$10$8H8mZx8vaFYsBsznLXjXkeJc.RoOjfeHq3WbeUkcGcyXGrBpzWoG.', 'user', NULL, 1, NULL, NULL, NULL),
(6, 'alketa2026', 'alketa.thaci@student.uni-pr.edu', '$2y$10$cbqdEbDfJJdIbaoc3smLje57BzYpyDmm6MirO/a4HNRa3KwOQ0IeS', 'user', NULL, 1, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_name` (`name`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
