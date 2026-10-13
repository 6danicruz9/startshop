-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-10-2026 a las 13:40:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `startshop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `precio` decimal(10,2) UNSIGNED NOT NULL,
  `precio_rebajado` decimal(10,2) UNSIGNED DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `valoracion` decimal(2,1) UNSIGNED DEFAULT 0.0,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `referencia`, `nombre`, `precio`, `precio_rebajado`, `descripcion`, `valoracion`, `imagen`) VALUES
(1, 'REF-001', 'Red Dead Redemption 2', 75.99, 59.99, 'Adéntrate en el salvaje Oeste con Red Dead Redemption 2, una épica aventura de mundo abierto protagonizada por Arthur Morgan. Explora paisajes impresionantes, participa en intensos tiroteos y vive una historia profunda sobre la lealtad, la libertad y la supervivencia.', 4.7, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSz5VUL0rswkka6NA8DU7n53xwnTTMr0ExasMSBKUwVsA&s=10'),
(2, 'REF-002', 'The Last of Us', 39.99, 0.00, 'Acompaña a Joel y Ellie en un peligroso viaje por un mundo devastado por una pandemia. The Last of Us combina una historia emocionante, exploración y acción intensa en una aventura marcada por la supervivencia y los vínculos humanos.', 4.3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQQ5dQxbRReZLmxevJflWoKr1rARqGppzjDIUKdJArgkw&s=10'),
(3, 'REF-003', 'Minecraft', 25.00, 15.00, 'Explora un mundo infinito lleno de posibilidades en Minecraft. Construye, crea, explora y sobrevive a tu manera, desde levantar enormes construcciones hasta enfrentarte a criaturas y descubrir nuevos lugares.', 3.7, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcREPZlTnbiICEOVa7qm_djlgy_9HqJy57FfInvVKg_P9A&s=10'),
(4, 'REF-004', 'Grand Theft Auto V', 40.00, 12.99, 'Sumérgete en Los Santos, una ciudad llena de oportunidades, peligros y caos. Grand Theft Auto V ofrece una historia protagonizada por tres personajes, un enorme mundo abierto y todo tipo de actividades, desde persecuciones y tiroteos hasta exploración y aventuras.', 4.1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYCko8-pXn3JeoV5rRU2nvZA7eAU-LZv7MmVXLkqY9M4PeWK6CWPdPD0_b&s=10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referencia` (`referencia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
