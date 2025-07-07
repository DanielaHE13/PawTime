-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-07-2025 a las 22:39:48
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
-- Base de datos: `pawtime`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `idAdministrador` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `correo` varchar(20) NOT NULL,
  `clave` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`idAdministrador`, `nombre`, `apellido`, `telefono`, `correo`, `clave`) VALUES
(1014738649, 'Dana', 'Macias', '3103124264', 'ds@gmail.com', '250cf8b51c773f3f8dc8b4be867a9a02'),
(1030521677, 'Daniela', 'Huertas', '3197159542', 'dh@gmail.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `idEstado` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`idEstado`, `nombre`) VALUES
(1, 'Programado'),
(2, 'En curso'),
(3, 'Completado'),
(4, 'Cancelado'),
(5, 'Aceptado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paseador`
--

CREATE TABLE `paseador` (
  `idPaseador` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `correo` varchar(20) NOT NULL,
  `clave` varchar(45) NOT NULL,
  `foto` varchar(45) NOT NULL,
  `tarifa` double NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paseador`
--

INSERT INTO `paseador` (`idPaseador`, `nombre`, `apellido`, `telefono`, `correo`, `clave`, `foto`, `tarifa`, `estado`) VALUES
(1, 'Carlos', 'Ramírez', '3001234567', 'carlos@gmail.com', 'clave123', 'carlos.jpg', 15000, 1),
(34553075, 'lucyy', 'erazo', '3203252369', 'lucy@gmail.com', '202cb962ac59075b964b07152d234b70', 'default.jpg', 1, 1),
(1234567987, 'Carlos', 'Rodríguez', '3127834333', 'cr@gmail.com', '202cb962ac59075b964b07152d234b70', '1751859412.jpg', 20000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paseo`
--

CREATE TABLE `paseo` (
  `idPaseo` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `precio_total` double NOT NULL,
  `Paseador_idPaseador` int(11) NOT NULL,
  `Perro_idPerro` int(11) NOT NULL,
  `Estado_idEstado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paseo`
--

INSERT INTO `paseo` (`idPaseo`, `fecha`, `hora_inicio`, `hora_fin`, `precio_total`, `Paseador_idPaseador`, `Perro_idPerro`, `Estado_idEstado`) VALUES
(1, '2025-07-06', '08:00:00', '09:00:00', 15000, 1, 6, 4),
(2, '2025-07-21', '12:30:00', '13:30:00', 15000, 1, 6, 1),
(3, '2025-07-06', '10:00:00', '11:00:00', 15000, 1, 6, 1),
(4, '2025-07-06', '10:00:00', '11:00:00', 15000, 1, 7, 1),
(5, '2025-07-04', '11:00:00', '13:00:00', 15000, 1, 7, 3),
(6, '2025-07-04', '11:00:00', '13:00:00', 15000, 1, 7, 3),
(7, '2025-07-07', '07:00:00', '09:00:00', 20000, 1234567987, 1, 5),
(8, '2025-07-08', '09:30:00', '11:30:00', 20000, 1234567987, 1, 5),
(9, '2025-07-08', '09:30:00', '11:30:00', 20000, 1234567987, 4, 4),
(10, '2025-07-08', '12:00:00', '13:00:00', 20000, 1234567987, 5, 1),
(11, '2025-07-08', '12:00:00', '13:00:00', 20000, 1234567987, 4, 2),
(12, '2025-07-08', '12:00:00', '13:00:00', 20000, 1234567987, 1, 4),
(13, '2025-07-06', '08:00:00', '09:00:00', 20000, 1234567987, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perro`
--

CREATE TABLE `perro` (
  `idPerro` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `observaciones` text NOT NULL,
  `foto` varchar(45) NOT NULL,
  `Raza_idRaza` int(11) NOT NULL,
  `Propietario_idPropietario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perro`
--

INSERT INTO `perro` (`idPerro`, `nombre`, `observaciones`, `foto`, `Raza_idRaza`, `Propietario_idPropietario`) VALUES
(1, 'snoopy', 'perro amable ', '1751752109.jfif', 1, 123),
(4, 'mora', '', '1751752492.jfif', 3, 123),
(5, 'lola', 'es juguetona', 'lola.jfif', 13, 123),
(6, 'paul', '', 'paul.jfif', 8, 1030521677),
(7, 'leo', 'le gusta morder ', 'lola.jfif', 1, 1030521677);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietario`
--

CREATE TABLE `propietario` (
  `idPropietario` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `correo` varchar(20) NOT NULL,
  `clave` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `foto` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `propietario`
--

INSERT INTO `propietario` (`idPropietario`, `nombre`, `apellido`, `telefono`, `correo`, `clave`, `direccion`, `foto`) VALUES
(123, 'Laura', 'Perez', '1234567890', 'lp@gmail.com', '202cb962ac59075b964b07152d234b70', 'Calle 1 # 2-3', 'laura.jpg'),
(1030521677, 'DANIELAaaa', 'ERAZOoo', '3197159542', 'daniezhs@gmail.com', '202cb962ac59075b964b07152d234b70', 'kra72jbis#40c-50sur', '1751769008.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `raza`
--

CREATE TABLE `raza` (
  `idRaza` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `raza`
--

INSERT INTO `raza` (`idRaza`, `nombre`) VALUES
(1, 'labrador'),
(2, 'Pastor Alemán'),
(3, 'Bulldog Francés'),
(4, 'Golden Retriever'),
(5, 'Poodle'),
(6, 'Chihuahua'),
(7, 'Pug'),
(8, 'Boxer'),
(9, 'Dálmata'),
(10, 'Husky Siberiano'),
(11, 'Beagle'),
(12, 'Shih Tzu'),
(13, 'Cocker Spaniel'),
(14, 'Rottweiler'),
(15, 'Terrier');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`idAdministrador`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`idEstado`);

--
-- Indices de la tabla `paseador`
--
ALTER TABLE `paseador`
  ADD PRIMARY KEY (`idPaseador`);

--
-- Indices de la tabla `paseo`
--
ALTER TABLE `paseo`
  ADD PRIMARY KEY (`idPaseo`),
  ADD KEY `fk_Paseo_Paseador1` (`Paseador_idPaseador`),
  ADD KEY `fk_Paseo_Perro1` (`Perro_idPerro`),
  ADD KEY `fk_Paseo_Estado1` (`Estado_idEstado`);

--
-- Indices de la tabla `perro`
--
ALTER TABLE `perro`
  ADD PRIMARY KEY (`idPerro`),
  ADD KEY `fk_Perro_Raza1` (`Raza_idRaza`),
  ADD KEY `fk_Perro_Propietario1` (`Propietario_idPropietario`);

--
-- Indices de la tabla `propietario`
--
ALTER TABLE `propietario`
  ADD PRIMARY KEY (`idPropietario`);

--
-- Indices de la tabla `raza`
--
ALTER TABLE `raza`
  ADD PRIMARY KEY (`idRaza`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `paseo`
--
ALTER TABLE `paseo`
  MODIFY `idPaseo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `perro`
--
ALTER TABLE `perro`
  MODIFY `idPerro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `raza`
--
ALTER TABLE `raza`
  MODIFY `idRaza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `paseo`
--
ALTER TABLE `paseo`
  ADD CONSTRAINT `fk_Paseo_Estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Paseo_Paseador1` FOREIGN KEY (`Paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Paseo_Perro1` FOREIGN KEY (`Perro_idPerro`) REFERENCES `perro` (`idPerro`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `perro`
--
ALTER TABLE `perro`
  ADD CONSTRAINT `fk_Perro_Propietario1` FOREIGN KEY (`Propietario_idPropietario`) REFERENCES `propietario` (`idPropietario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Perro_Raza1` FOREIGN KEY (`Raza_idRaza`) REFERENCES `raza` (`idRaza`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
