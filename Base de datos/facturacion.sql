-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-09-2023 a las 06:48:05
-- Versión del servidor: 8.0.32
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `facturacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_clientes`
--

CREATE TABLE `fac_clientes` (
  `cli_id` int NOT NULL,
  `cli_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cli_numero_identificacion` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cli_celular` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cli_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cli_direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cli_tipo_identificacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fac_clientes`
--

INSERT INTO `fac_clientes` (`cli_id`, `cli_nombre`, `cli_numero_identificacion`, `cli_celular`, `cli_email`, `cli_direccion`, `cli_tipo_identificacion`) VALUES
(9, 'MIGUEL INTRIAGO TOBAR', '0803338532001', '0996193351', 'jmti4884@gmail.com', 'Luis Tufiño', 'ruc'),
(10, 'ALEJANDRO INTRIAGO', '9999999999', '065656546', 'alejandro@gmail.com', 'El tingo', 'cedula');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_detalle_fac`
--

CREATE TABLE `fac_detalle_fac` (
  `det_id` int NOT NULL,
  `fac_id` int NOT NULL,
  `prod_id` int DEFAULT NULL,
  `det_cantidad` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `det_precio` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `det_subtotal` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `det_iva` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `det_total` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `fac_detalle_fac`
--

INSERT INTO `fac_detalle_fac` (`det_id`, `fac_id`, `prod_id`, `det_cantidad`, `det_precio`, `det_subtotal`, `det_iva`, `det_total`) VALUES
(69, 57, 6, '1', '25.25', '25.25', '0', '25.25'),
(70, 57, 7, '4', '0.58', '2.32', '0.2784', '2.5984'),
(71, 57, 8, '1', '0.79', '0.79', '0.0948', '0.8848'),
(72, 57, 9, '2', '7.25', '14.5', '0', '14.5'),
(73, 58, 7, '1', '0.58', '0.58', '0.0696', '0.6496'),
(74, 59, 6, '1', '25.25', '25.25', '0', '25.25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_empresa`
--

CREATE TABLE `fac_empresa` (
  `emp_id` int NOT NULL,
  `emp_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `emp_ruc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `emp_telefono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `emp_direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `emp_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `emp_logo` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `fac_empresa`
--

INSERT INTO `fac_empresa` (`emp_id`, `emp_nombre`, `emp_ruc`, `emp_telefono`, `emp_direccion`, `emp_email`, `emp_logo`) VALUES
(4, 'Pallint Soft', '0803338532001', '0996193352', 'Quito', 'jmti@gmail.com.ec', '1691458069_Sin título-1.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_factura`
--

CREATE TABLE `fac_factura` (
  `fac_id` int NOT NULL,
  `cli_id` int NOT NULL,
  `fac_fecha_aut` datetime NOT NULL,
  `fac_fecha_emi` datetime NOT NULL,
  `fac_subtotal_iva_vig` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fac_subtotal_cer` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fac_subtotal` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fac_iva_doc` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fac_valor_tot` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fac_forma_pago` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `fac_factura`
--

INSERT INTO `fac_factura` (`fac_id`, `cli_id`, `fac_fecha_aut`, `fac_fecha_emi`, `fac_subtotal_iva_vig`, `fac_subtotal_cer`, `fac_subtotal`, `fac_iva_doc`, `fac_valor_tot`, `fac_forma_pago`) VALUES
(57, 10, '2023-09-05 22:40:12', '2023-09-13 00:00:00', '3.11', '39.75', '42.86', '0.3732', '43.2332', 'Dinero Electrónico'),
(58, 9, '2023-09-05 23:02:18', '2023-09-02 00:00:00', '0.58', '0', '0.58', '0.0696', '0.6496', 'Dinero Electrónico'),
(59, 10, '2023-09-05 23:26:42', '2023-09-06 00:00:00', '0', '25.25', '25.25', '0', '25.25', 'Otros con utilización del sistema financiero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_productos`
--

CREATE TABLE `fac_productos` (
  `prod_id` int NOT NULL,
  `prod_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `prod_codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `prod_precio` decimal(10,2) NOT NULL,
  `prod_iva` varchar(2) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `fac_productos`
--

INSERT INTO `fac_productos` (`prod_id`, `prod_nombre`, `prod_codigo`, `prod_precio`, `prod_iva`) VALUES
(6, 'PRODUCTO', '001', '25.25', '0'),
(7, 'COCACOLA', '002', '0.58', '12'),
(8, 'CAFE', '003', '0.79', '12'),
(9, 'PRODUCTO IVA 0', '004', '7.25', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_usuarios`
--

CREATE TABLE `fac_usuarios` (
  `usu_id` int NOT NULL,
  `usu_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `usu_usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `usu_password` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `fac_usuarios`
--

INSERT INTO `fac_usuarios` (`usu_id`, `usu_nombre`, `usu_usuario`, `usu_password`) VALUES
(1, 'Miguel Intriago', 'Miguel', '$2y$10$q5YRQm.XwB3mVBNfHz8jrekxVmEkaWAOf1Xe5Tw9ody8vfrya9qpu');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `fac_clientes`
--
ALTER TABLE `fac_clientes`
  ADD PRIMARY KEY (`cli_id`),
  ADD UNIQUE KEY `cli_nombre` (`cli_nombre`),
  ADD UNIQUE KEY `cli_numero_identificacion` (`cli_numero_identificacion`);

--
-- Indices de la tabla `fac_detalle_fac`
--
ALTER TABLE `fac_detalle_fac`
  ADD PRIMARY KEY (`det_id`),
  ADD KEY `fac_id` (`fac_id`),
  ADD KEY `pro_id` (`prod_id`);

--
-- Indices de la tabla `fac_empresa`
--
ALTER TABLE `fac_empresa`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indices de la tabla `fac_factura`
--
ALTER TABLE `fac_factura`
  ADD PRIMARY KEY (`fac_id`),
  ADD KEY `cli_id` (`cli_id`);

--
-- Indices de la tabla `fac_productos`
--
ALTER TABLE `fac_productos`
  ADD PRIMARY KEY (`prod_id`),
  ADD UNIQUE KEY `prod_codigo` (`prod_codigo`),
  ADD UNIQUE KEY `prod_nombre` (`prod_nombre`);

--
-- Indices de la tabla `fac_usuarios`
--
ALTER TABLE `fac_usuarios`
  ADD PRIMARY KEY (`usu_id`),
  ADD UNIQUE KEY `usu_usuario` (`usu_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `fac_clientes`
--
ALTER TABLE `fac_clientes`
  MODIFY `cli_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `fac_detalle_fac`
--
ALTER TABLE `fac_detalle_fac`
  MODIFY `det_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `fac_empresa`
--
ALTER TABLE `fac_empresa`
  MODIFY `emp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `fac_factura`
--
ALTER TABLE `fac_factura`
  MODIFY `fac_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `fac_productos`
--
ALTER TABLE `fac_productos`
  MODIFY `prod_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `fac_usuarios`
--
ALTER TABLE `fac_usuarios`
  MODIFY `usu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `fac_detalle_fac`
--
ALTER TABLE `fac_detalle_fac`
  ADD CONSTRAINT `fac_detalle_fac_ibfk_1` FOREIGN KEY (`fac_id`) REFERENCES `fac_factura` (`fac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fac_detalle_fac_ibfk_2` FOREIGN KEY (`prod_id`) REFERENCES `fac_productos` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `fac_factura`
--
ALTER TABLE `fac_factura`
  ADD CONSTRAINT `fac_factura_ibfk_1` FOREIGN KEY (`cli_id`) REFERENCES `fac_clientes` (`cli_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
