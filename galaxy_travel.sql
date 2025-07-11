-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2025 a las 22:34:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `galaxy_travel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `cedula_identidad` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `usuario`, `contrasena`, `nombre`, `edad`, `cedula_identidad`, `fecha_registro`) VALUES
(1, 'kevin123', '$2y$10$pnRMMwqtMw0z28EFcFM1J.BYcasFQn2mvbaYGaMsxX.pYBvRgzt5.', 'Kevin Rozo', 22, 'V-12345678', '2025-07-07 04:00:00'),
(2, 'kevin', '$2y$10$vgMLZoytkoEzntQt3X3VWOcNH4sUAzQIZ5V67ovtl.C25CjjNKZIK', 'Kevin Jesus', 24, '28109034', '2025-07-07 04:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destinos`
--

CREATE TABLE `destinos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_base` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `destinos`
--

INSERT INTO `destinos` (`id`, `nombre`, `descripcion`, `precio_base`) VALUES
(1, 'Mercurio', NULL, 0.00),
(2, 'Venus', NULL, 0.00),
(3, 'Órbita Terrestre', NULL, 0.00),
(4, 'Marte', NULL, 0.00),
(5, 'Lunas de Júpiter', NULL, 0.00),
(6, 'Lunas de Saturno', NULL, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `reservacion_id` int(11) NOT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `destino_id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_salida` date NOT NULL,
  `duracion_dias` int(11) DEFAULT NULL,
  `cupos_disponibles` int(11) DEFAULT NULL,
  `precio_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`id`, `nombre`, `destino_id`, `descripcion`, `fecha_salida`, `duracion_dias`, `cupos_disponibles`, `precio_total`) VALUES
(1, 'Escapada Extrema a Mercurio - El Mensajero Solar', 1, 'Estancia en base subterránea. Incluye: observación solar desde mirador blindado, exploración guiada en vehículos radioprotegidos, participación en experimentos geológicos, vistas planetarias desde órbita de Mercurio.', '2026-01-10', 180, 4, 99999999.99),
(2, 'Aventura Atmosférica en Venus - El Velo Naranja Flotante', 2, 'Estancia en ciudad flotante. Incluye: observación de nubes de ácido sulfúrico, exploración en vehículos aéreos, estudios del efecto invernadero y vistas atmosféricas únicas.', '2026-03-20', 120, 6, 99999999.99),
(3, 'Retiro en Órbita Terrestre - El Oasis Azul', 3, 'Estancia en la ISS o hotel espacial. Incluye: vistas panorámicas de la Tierra, experimentación en microgravedad, comidas espaciales, caminatas opcionales.', '2025-11-01', 14, 10, 60000000.00),
(4, 'Pioneros en Marte - La Nueva Frontera Roja', 4, 'Hábitats presurizados en la superficie marciana. Incluye: exploración en rover, construcción de base, investigaciones científicas, cultivo en invernaderos, observación de Fobos y Deimos.', '2027-06-15', 600, 2, 99999999.99),
(5, 'Exploración Lunar Joviana - Los Océanos Escondidos', 5, 'Estancia en bases blindadas en Europa, Ganímedes o Calisto. Incluye: perforación de hielo, exploración oceánica robótica, estudios de vida microbiana, observación de Júpiter.', '2028-09-30', 1000, 1, 99999999.99),
(6, 'Descubrimiento en las Lunas de Saturno - Los Mundos Anillados', 6, 'Hábitats protegidos en Titán o Encélado. Incluye: navegación por lagos de metano, exploración de dunas, recolección de penachos de hielo, observación de anillos.', '2029-04-22', 1500, 1, 99999999.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservaciones`
--

CREATE TABLE `reservaciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `paquete_id` int(11) NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','confirmado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `cedula_identidad` (`cedula_identidad`);

--
-- Indices de la tabla `destinos`
--
ALTER TABLE `destinos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservacion_id` (`reservacion_id`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destino_id` (`destino_id`);

--
-- Indices de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `paquete_id` (`paquete_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `destinos`
--
ALTER TABLE `destinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`reservacion_id`) REFERENCES `reservaciones` (`id`);

--
-- Filtros para la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD CONSTRAINT `paquetes_ibfk_1` FOREIGN KEY (`destino_id`) REFERENCES `destinos` (`id`);

--
-- Filtros para la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD CONSTRAINT `reservaciones_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `reservaciones_ibfk_2` FOREIGN KEY (`paquete_id`) REFERENCES `paquetes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
