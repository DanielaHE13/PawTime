-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-06-2025 a las 20:58:49
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
  `direccion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `propietario`
--

INSERT INTO `propietario` (`idPropietario`, `nombre`, `apellido`, `telefono`, `correo`, `clave`, `direccion`) VALUES
(123, 'Laura', 'Perez', '1234567890', 'lp@gmail.com', '202cb962ac59075b964b07152d234b70', 'Calle 1 # 2-3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `raza`
--

CREATE TABLE `raza` (
  `idRaza` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
