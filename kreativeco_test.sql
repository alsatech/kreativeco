-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2025 at 06:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kreativeco_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `user_id`, `titulo`, `descripcion`, `created_at`, `deleted_at`) VALUES
(1, 2, 'Nueva publicación sin token', 'Esta publicación fue creada sin usar JWT.', '2025-02-15 03:35:26', NULL),
(2, 2, 'prueba 1 en el front', 'esto es una prueba', '2025-02-15 03:38:08', NULL),
(3, 2, 'PRUEBA 2 EN EL FRONT', 'ESTO ES UNA PRUEBA ', '2025-02-15 03:38:37', NULL),
(4, 2, 'Nueva publicación ', 'Esta publicación fue una prueba.', '2025-02-15 03:39:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `acceso` tinyint(1) DEFAULT 0,
  `consulta` tinyint(1) DEFAULT 0,
  `agregar` tinyint(1) DEFAULT 0,
  `actualizar` tinyint(1) DEFAULT 0,
  `eliminar` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `roles` (`id`, `nombre`, `acceso`, `consulta`, `agregar`, `actualizar`, `eliminar`) VALUES
(1, 'Básico', 1, 0, 0, 0, 0),
(2, 'Medio', 1, 1, 0, 0, 0),
(3, 'Medio Alto', 1, 1, 1, 0, 0),
(4, 'Alto Medio', 1, 1, 1, 1, 0),
(5, 'Alto', 1, 1, 1, 1, 1);

-- --------------------------------------------------------



CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `password`, `role_id`, `created_at`) VALUES
(1, 'admin', 'Admin', 'admin@example.com', '0192023a7bbd73250516f069df18b500', 1, '2025-02-14 23:10:47'),
(2, 'user1', 'User', 'user1@example.com', 'b5b73fae0d87d8b4e2573105f8fbe7bc', 3, '2025-02-14 23:10:47'),
(3, 'Alfredo', 'Salas', 'asalashdz@example.com', '$2y$10$UHUebSKWora1OMb3eVAnju4GAERHUG4m9GfEQhLu2Kt0s4r8FW0aW', 3, '2025-02-14 23:33:16'),
(4, 'Publish', 'Role', 'publish@example.com', '$2y$10$dya2z9ymSpA2kxllfJ6/Uu8aeItlcmnSt4z392KIdPl5qLkfpS4Yi', 3, '2025-02-15 00:20:31'),
(5, 'tester', 'testerpublish', 'tester@example.com', '$2y$10$9580KCZf5j7YZV92GQO/3e6W0fnezSmOE0tk8dxqbLHhZhV5O6OZa', 3, '2025-02-15 00:28:37');




ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);


ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);


ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);




ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;


ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;


ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
