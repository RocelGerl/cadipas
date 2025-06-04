-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-06-2025 a las 13:02:10
-- Versión del servidor: 10.6.16-MariaDB-cll-lve
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mcovrcis_cadipas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `encargado` int(11) DEFAULT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cocina`
--

CREATE TABLE `cocina` (
  `id_cocina` int(11) NOT NULL,
  `id_dirigente` int(11) DEFAULT NULL,
  `id_patrulla` int(11) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `hora` varchar(200) DEFAULT NULL,
  `higiene` int(11) DEFAULT 0,
  `presentacion` int(11) DEFAULT 0,
  `trabajo_equipo` int(11) DEFAULT 0,
  `sabor` int(11) DEFAULT 0,
  `tiempo` int(11) DEFAULT 0,
  `puntaje` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dirigentes`
--

CREATE TABLE `dirigentes` (
  `id_dirigente` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion_actividades`
--

CREATE TABLE `evaluacion_actividades` (
  `id_ea` int(11) NOT NULL,
  `id_actividad` int(11) DEFAULT NULL,
  `id_dirigente` int(11) DEFAULT NULL,
  `id_patrulla` int(11) DEFAULT NULL,
  `resultado` int(11) DEFAULT 0,
  `trabajo_equipo` int(11) DEFAULT 0,
  `reglas` int(11) DEFAULT 0,
  `creatividad` int(11) DEFAULT 0,
  `espiritu` int(11) DEFAULT 0,
  `puntaje` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion_sub`
--

CREATE TABLE `evaluacion_sub` (
  `id_es` int(11) NOT NULL,
  `id_subcampo` int(11) DEFAULT NULL,
  `id_patrulla` int(11) DEFAULT NULL,
  `id_dirigente` int(11) DEFAULT NULL,
  `dia` int(11) NOT NULL,
  `formacion` int(11) DEFAULT 0,
  `uniformidad` int(11) DEFAULT 0,
  `limpieza` int(11) DEFAULT 0,
  `sistema_equipos` int(11) DEFAULT 0,
  `espiritu` int(11) DEFAULT 0,
  `puntaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion_talleres`
--

CREATE TABLE `evaluacion_talleres` (
  `id_t` int(11) NOT NULL,
  `id_dirigente` int(11) DEFAULT NULL,
  `id_patrulla` int(11) DEFAULT NULL,
  `id_actividad` int(11) DEFAULT NULL,
  `asistencia` int(11) DEFAULT 0,
  `practica` int(11) DEFAULT 0,
  `trabajo_equipo` int(11) DEFAULT 0,
  `creatividad` int(11) DEFAULT 0,
  `participacion` int(11) DEFAULT 0,
  `puntaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patrullas`
--

CREATE TABLE `patrullas` (
  `id_patrulla` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_subcampo` int(11) DEFAULT NULL,
  `puntuacion1` float NOT NULL,
  `puntuacion2` float NOT NULL,
  `puntuacion3` float NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcampos`
--

CREATE TABLE `subcampos` (
  `id_subcampo` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`);

--
-- Indices de la tabla `cocina`
--
ALTER TABLE `cocina`
  ADD PRIMARY KEY (`id_cocina`);

--
-- Indices de la tabla `dirigentes`
--
ALTER TABLE `dirigentes`
  ADD PRIMARY KEY (`id_dirigente`);

--
-- Indices de la tabla `evaluacion_actividades`
--
ALTER TABLE `evaluacion_actividades`
  ADD PRIMARY KEY (`id_ea`);

--
-- Indices de la tabla `evaluacion_sub`
--
ALTER TABLE `evaluacion_sub`
  ADD PRIMARY KEY (`id_es`);

--
-- Indices de la tabla `evaluacion_talleres`
--
ALTER TABLE `evaluacion_talleres`
  ADD PRIMARY KEY (`id_t`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`);

--
-- Indices de la tabla `patrullas`
--
ALTER TABLE `patrullas`
  ADD PRIMARY KEY (`id_patrulla`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_subcampo` (`id_subcampo`);

--
-- Indices de la tabla `subcampos`
--
ALTER TABLE `subcampos`
  ADD PRIMARY KEY (`id_subcampo`),
  ADD KEY `id_encargado` (`id_encargado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cocina`
--
ALTER TABLE `cocina`
  MODIFY `id_cocina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dirigentes`
--
ALTER TABLE `dirigentes`
  MODIFY `id_dirigente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluacion_actividades`
--
ALTER TABLE `evaluacion_actividades`
  MODIFY `id_ea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluacion_sub`
--
ALTER TABLE `evaluacion_sub`
  MODIFY `id_es` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluacion_talleres`
--
ALTER TABLE `evaluacion_talleres`
  MODIFY `id_t` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `patrullas`
--
ALTER TABLE `patrullas`
  MODIFY `id_patrulla` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subcampos`
--
ALTER TABLE `subcampos`
  MODIFY `id_subcampo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `patrullas`
--
ALTER TABLE `patrullas`
  ADD CONSTRAINT `patrullas_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`),
  ADD CONSTRAINT `patrullas_ibfk_2` FOREIGN KEY (`id_subcampo`) REFERENCES `subcampos` (`id_subcampo`);

--
-- Filtros para la tabla `subcampos`
--
ALTER TABLE `subcampos`
  ADD CONSTRAINT `subcampos_ibfk_1` FOREIGN KEY (`id_encargado`) REFERENCES `dirigentes` (`id_dirigente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
