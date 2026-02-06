-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3309
-- Tiempo de generación: 06-02-2026 a las 14:40:42
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
-- Base de datos: `prueba1tiendaonline`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_sesion` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','abandonado','convertido') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id_carrito`, `id_usuario`, `id_sesion`, `fecha_creacion`, `estado`) VALUES
(92, 10, NULL, '2026-02-06 09:37:08', 'convertido'),
(93, 10, NULL, '2026-02-06 09:37:17', 'convertido'),
(98, 5, NULL, '2026-02-06 11:27:15', 'activo'),
(99, 5, NULL, '2026-02-06 11:27:17', 'activo'),
(100, 5, NULL, '2026-02-06 11:42:17', 'activo'),
(101, 12, NULL, '2026-02-06 11:48:00', 'activo'),
(102, 12, NULL, '2026-02-06 11:48:01', 'activo'),
(103, 12, NULL, '2026-02-06 11:48:39', 'activo'),
(104, 5, NULL, '2026-02-06 12:03:34', 'activo'),
(105, 5, NULL, '2026-02-06 12:04:50', 'activo'),
(106, 5, NULL, '2026-02-06 12:04:51', 'activo'),
(107, 5, NULL, '2026-02-06 12:17:08', 'activo'),
(108, 5, NULL, '2026-02-06 12:17:31', 'activo'),
(109, 5, NULL, '2026-02-06 12:17:33', 'activo'),
(110, 10, NULL, '2026-02-06 12:26:19', 'activo'),
(111, 10, NULL, '2026-02-06 12:26:19', 'activo'),
(112, 10, NULL, '2026-02-06 12:31:14', 'activo'),
(113, 10, NULL, '2026-02-06 12:31:16', 'activo'),
(114, 5, NULL, '2026-02-06 12:34:43', 'activo'),
(115, 10, NULL, '2026-02-06 12:35:28', 'activo'),
(116, 10, NULL, '2026-02-06 12:35:29', 'activo'),
(117, 5, NULL, '2026-02-06 13:27:39', 'activo'),
(118, 5, NULL, '2026-02-06 13:27:40', 'activo'),
(119, 5, NULL, '2026-02-06 13:27:40', 'activo'),
(120, 5, NULL, '2026-02-06 13:28:14', 'activo'),
(121, 5, NULL, '2026-02-06 13:28:15', 'activo'),
(122, 5, NULL, '2026-02-06 13:28:15', 'activo'),
(123, 5, NULL, '2026-02-06 13:28:16', 'activo'),
(124, 5, NULL, '2026-02-06 13:28:16', 'activo'),
(125, 10, NULL, '2026-02-06 13:38:01', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Electrónica', 'Gadgets, smartphones y accesorios tecnológicos'),
(2, 'Ropa', 'Prendas de vestir para todas las edades'),
(3, 'Hogar', 'Muebles y decoración para el hogar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_carrito`
--

CREATE TABLE `detalles_carrito` (
  `id_detalle_carrito` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL CHECK (`cantidad` > 0),
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_carrito`
--

INSERT INTO `detalles_carrito` (`id_detalle_carrito`, `id_carrito`, `id_producto`, `cantidad`, `fecha_agregado`) VALUES
(101, 101, 18, 1, '2026-02-06 11:48:00'),
(103, 101, 15, 1, '2026-02-06 11:48:40'),
(115, 92, 15, 2, '2026-02-06 12:35:28'),
(120, 98, 15, 5, '2026-02-06 13:28:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL CHECK (`cantidad` > 0),
  `precio_unitario` decimal(10,2) NOT NULL CHECK (`precio_unitario` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(4, 3, 15, 1, 55.00),
(5, 3, 18, 1, 10.00),
(6, 4, 15, 1, 55.00),
(8, 5, 15, 1, 55.00),
(10, 6, 15, 1, 55.00),
(12, 7, 15, 1, 55.00);

--
-- Disparadores `detalles_pedido`
--
DELIMITER $$
CREATE TRIGGER `trg_total_delete` AFTER DELETE ON `detalles_pedido` FOR EACH ROW BEGIN
    UPDATE Pedidos
    SET total = IFNULL((
        SELECT SUM(cantidad * precio_unitario)
        FROM Detalles_Pedido
        WHERE id_pedido = OLD.id_pedido
    ),0)
    WHERE id_pedido = OLD.id_pedido;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_total_insert` AFTER INSERT ON `detalles_pedido` FOR EACH ROW BEGIN
    UPDATE Pedidos
    SET total = (
        SELECT SUM(cantidad * precio_unitario)
        FROM Detalles_Pedido
        WHERE id_pedido = NEW.id_pedido
    )
    WHERE id_pedido = NEW.id_pedido;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_total_update` AFTER UPDATE ON `detalles_pedido` FOR EACH ROW BEGIN
    UPDATE Pedidos
    SET total = (
        SELECT SUM(cantidad * precio_unitario)
        FROM Detalles_Pedido
        WHERE id_pedido = NEW.id_pedido
    )
    WHERE id_pedido = NEW.id_pedido;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disenadores`
--

CREATE TABLE `disenadores` (
  `id_disenador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `biografia` text DEFAULT NULL,
  `web_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disenadores`
--

INSERT INTO `disenadores` (`id_disenador`, `nombre`, `biografia`, `web_url`) VALUES
(1, 'Elena Modista', 'Diseñadora especializada en tejidos sostenibles.', 'https://elenamoda.com'),
(2, 'Marco Zapatero', 'Artesano del cuero con 20 años de experiencia.', 'https://marcoleather.it'),
(3, 'Sonia Joyas', 'Joyera contemporánea basada en Barcelona.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_estados_pedido`
--

CREATE TABLE `historial_estados_pedido` (
  `id_historial` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `estado` enum('pendiente','pagado','enviado','cancelado','completado') NOT NULL,
  `fecha_cambio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_espera`
--

CREATE TABLE `lista_espera` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_suscripcion` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','enviado') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lista_espera`
--

INSERT INTO `lista_espera` (`id`, `id_producto`, `email`, `fecha_suscripcion`, `estado`) VALUES
(2, 15, 'usuario_interesado@tienda.com', '2026-02-06 13:29:21', ''),
(3, 15, 'usuario_interesado@tienda.com', '2026-02-06 13:29:24', ''),
(4, 15, 'usuario_interesado@tienda.com', '2026-02-06 13:31:07', ''),
(5, 18, 'usuario_interesado@tienda.com', '2026-02-06 13:34:38', ''),
(6, 18, 'usuario_interesado@tienda.com', '2026-02-06 14:30:19', ''),
(7, 18, 'usuario_interesado@tienda.com', '2026-02-06 14:30:21', ''),
(8, 19, 'usuario_interesado@tienda.com', '2026-02-06 14:30:23', ''),
(9, 19, 'usuario_interesado@tienda.com', '2026-02-06 14:30:23', ''),
(10, 19, 'usuario_interesado@tienda.com', '2026-02-06 14:30:49', ''),
(11, 19, 'usuario_interesado@tienda.com', '2026-02-06 14:30:50', ''),
(12, 19, 'usuario_interesado@tienda.com', '2026-02-06 14:30:50', ''),
(13, 19, 'usuario_interesado@tienda.com', '2026-02-06 14:30:50', ''),
(14, 19, 'usuario_interesado@tienda.com', '2026-02-06 14:30:50', ''),
(15, 18, 'usuario_interesado@tienda.com', '2026-02-06 14:31:11', ''),
(16, 15, 'usuario_interesado@tienda.com', '2026-02-06 14:38:01', ''),
(17, 18, 'usuario_interesado@tienda.com', '2026-02-06 14:38:13', ''),
(18, 18, 'usuario_interesado@tienda.com', '2026-02-06 14:38:45', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `monto` decimal(10,2) NOT NULL CHECK (`monto` >= 0),
  `metodo_pago` enum('tarjeta','paypal','transferencia','bizum') NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00 CHECK (`total` >= 0),
  `estado` enum('pendiente','pagado','enviado','cancelado','completado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `fecha_pedido`, `total`, `estado`) VALUES
(3, 10, '2026-02-06 13:23:29', 65.00, 'pendiente'),
(4, 10, '2026-02-06 13:26:51', 55.00, 'pendiente'),
(5, 10, '2026-02-06 13:27:08', 55.00, 'pendiente'),
(6, 10, '2026-02-06 13:27:10', 55.00, 'pendiente'),
(7, 10, '2026-02-06 13:27:13', 55.00, 'pendiente');

--
-- Disparadores `pedidos`
--
DELIMITER $$
CREATE TRIGGER `trg_descontar_stock` BEFORE UPDATE ON `pedidos` FOR EACH ROW BEGIN
    IF NEW.estado = 'pagado' AND OLD.estado <> 'pagado' THEN

        IF EXISTS (
            SELECT 1
            FROM Detalles_Pedido dp
            JOIN Productos p ON dp.id_producto = p.id_producto
            WHERE dp.id_pedido = NEW.id_pedido
            AND p.stock < dp.cantidad
        ) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Stock insuficiente';
        END IF;

        UPDATE Productos p
        JOIN Detalles_Pedido dp 
            ON p.id_producto = dp.id_producto
        SET p.stock = p.stock - dp.cantidad
        WHERE dp.id_pedido = NEW.id_pedido;

    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_devolver_stock` BEFORE UPDATE ON `pedidos` FOR EACH ROW BEGIN
    IF NEW.estado = 'cancelado' AND OLD.estado = 'pagado' THEN

        UPDATE Productos p
        JOIN Detalles_Pedido dp 
            ON p.id_producto = dp.id_producto
        SET p.stock = p.stock + dp.cantidad
        WHERE dp.id_pedido = NEW.id_pedido;

    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_historial_estado` AFTER UPDATE ON `pedidos` FOR EACH ROW BEGIN
    IF NEW.estado <> OLD.estado THEN
        INSERT INTO Historial_Estados_Pedido (id_pedido, estado)
        VALUES (NEW.id_pedido, NEW.estado);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL CHECK (`precio` >= 0),
  `stock` int(11) NOT NULL CHECK (`stock` >= 0),
  `imagen_url` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `imagen_url`, `id_categoria`) VALUES
(15, 'Calsetin', 'Calsetin', 55.00, 74, '1770295851_6984922b1afb6.jpg', 2),
(18, 'jaja cambiando para pruebas', 'jaja cambiando para pruebas', 10.00, 23, '1770370733_images.jpg', 1),
(19, '321', '321', 123.00, 132, '1770380188_6985db9ce5152.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `contrasena`, `rol`, `fecha_registro`) VALUES
(5, 'Lucas', 'admin@gmail.com', '$2y$10$FJa0ZMmQBa5LlinGHY0uQ.VfcUeFA2he0i4bIb/9aC.B.Oa1wicqG', 'admin', '2026-02-04 13:37:23'),
(10, 'jose', 'jose@gmail.com', '$2y$10$7gl7dPWfS60qrfchra7nv.0.Ow6SMsotPO8W83rZSM3EI3U.7KGue', 'cliente', '2026-02-06 09:36:57'),
(12, 'uprueba', 'prueba2@gmail.com', '$2y$10$fBaaF3ouh6a9pC0VAoZOv.fcAUzr0ZlXF2oYIE/1EmEwGvOoAQlZm', 'cliente', '2026-02-06 11:47:45');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_reporte_ventas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_reporte_ventas` (
`id_pedido` int(11)
,`cliente` varchar(100)
,`fecha_pedido` datetime
,`total` decimal(10,2)
,`estado` enum('pendiente','pagado','enviado','cancelado','completado')
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_reporte_ventas`
--
DROP TABLE IF EXISTS `vista_reporte_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_reporte_ventas`  AS SELECT `p`.`id_pedido` AS `id_pedido`, `u`.`nombre` AS `cliente`, `p`.`fecha_pedido` AS `fecha_pedido`, `p`.`total` AS `total`, `p`.`estado` AS `estado` FROM (`pedidos` `p` join `usuarios` `u` on(`p`.`id_usuario` = `u`.`id_usuario`)) WHERE `p`.`estado` = 'pagado' ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  ADD PRIMARY KEY (`id_detalle_carrito`),
  ADD UNIQUE KEY `id_carrito_producto` (`id_carrito`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD UNIQUE KEY `id_pedido` (`id_pedido`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `idx_detalle_pedido` (`id_pedido`);

--
-- Indices de la tabla `disenadores`
--
ALTER TABLE `disenadores`
  ADD PRIMARY KEY (`id_disenador`);

--
-- Indices de la tabla `historial_estados_pedido`
--
ALTER TABLE `historial_estados_pedido`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `idx_pedido_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `idx_producto_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  MODIFY `id_detalle_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `disenadores`
--
ALTER TABLE `disenadores`
  MODIFY `id_disenador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `historial_estados_pedido`
--
ALTER TABLE `historial_estados_pedido`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  ADD CONSTRAINT `detalles_carrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carritos` (`id_carrito`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `fk_detalles_carrito_carritos` FOREIGN KEY (`id_carrito`) REFERENCES `carritos` (`id_carrito`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `historial_estados_pedido`
--
ALTER TABLE `historial_estados_pedido`
  ADD CONSTRAINT `historial_estados_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  ADD CONSTRAINT `lista_espera_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
