-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-01-2025 a las 22:07:47
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hackatondb`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `buscar_chats` (IN `p_idusu1` INT, IN `p_idusu2` INT)  COMMENT 'procedimiento que busca los chats que han tenido dos usuarios' select * from mensaje where id_entrada = p_idusu1 and id_salida = p_idusu2 or id_entrada = p_idusu2 and id_salida = p_idusu1 order by fecha$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `chats_usuario` (IN `p_id_usuario` INT)  COMMENT 'procedimiento para buscar los chats  de un usuario' select * from mensaje where id_entrada = p_id_usuario or id_salida = p_id_usuario order by idpublicacion$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DetalleProducto` (IN `p_id_producto` INT)   BEGIN
    SELECT 
        objeto.id AS id_producto,
        objeto.nombre AS nombre_producto,
        objeto.id_estado,                            -- El estado del producto
        objeto.id_categoria,                         -- La categoría del producto
        usuario.id AS id_vendedor,                   -- El vendedor (usuario) asociado
        estado.tipo AS estado_producto,              -- Tipo de estado (usado, nuevo, etc.)
        categorias_objetos.Nombre AS categoria_producto -- La categoría del producto
    FROM 
        objeto
    JOIN usuario ON objeto.id_usuario = usuario.id
    JOIN estado ON objeto.id_estado = estado.id
    JOIN categorias_objetos ON objeto.id_categoria = categorias_objetos.Id
    WHERE objeto.id = p_id_producto;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `informar_objetos_inactivos` ()   BEGIN
  -- Declaración de variables
  DECLARE v_id_publicacion INT;
  DECLARE v_fecha DATE;
  DECLARE v_id_usuario INT;
  DECLARE done INT DEFAULT 0;

  -- Declaración del cursor
  DECLARE c_informador CURSOR FOR 
  SELECT id_publicacion, fecha_visita, id_usuario 
  FROM usuario_publicaciones 
  WHERE fecha_visita <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH) group by id_publicacion 
 ;

  -- Manejo de fin del cursor
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

  -- Abrir el cursor
  OPEN c_informador;

  -- Loop para recorrer los registros
  read_loop: LOOP
    FETCH c_informador INTO v_id_publicacion, v_fecha, v_id_usuario;

    -- Salir del loop si no hay más datos
    IF done = 1 THEN 
      LEAVE read_loop; 
    END IF;

    -- Acción a realizar con cada registro
    SELECT CONCAT(
      'La publicación con ID ', v_id_publicacion,
      ' no ha sido vista desde ', v_fecha,
      ' vista por el usuario ', v_id_usuario
    ) AS mensaje;
  END LOOP;

  -- Cerrar el cursor
  CLOSE c_informador;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_objetos` (`p_id` INT, `p_id_categoria` INT, `p_id_estado` INT, `p_id_usuario` INT, `p_imagen1` VARCHAR(300), `p_imagen2` VARCHAR(300), `p_imagen3` VARCHAR(300), `p_imagen4` VARCHAR(300), `p_imagen5` VARCHAR(300), `p_nombre` VARCHAR(300))   insert into objeto (id, id_categoria, id_estado, id_usuario, imagen, imagen2, imagen3, imagen4, imagen5, nombre, validado) values (p_id, p_id_categoria, p_id_estado, p_id_usuario, p_imagen1, p_imagen2, p_imagen3, p_imagen4, p_imagen5, p_nombre, 0)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `contraseña` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `nombre`, `contraseña`) VALUES
(1, 'admin1', '3427932t7498gwfiubdekvb'),
(2, 'admin2', 'dewkvfdwYIVPIDSAA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_objetos`
--

CREATE TABLE `categorias_objetos` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_objetos`
--

INSERT INTO `categorias_objetos` (`Id`, `Nombre`) VALUES
(1, 'Prendas'),
(2, 'Muebles'),
(3, 'Electrónica'),
(4, 'Juguetes'),
(5, 'Deportes'),
(6, 'Hogar'),
(7, 'Herramientas'),
(8, 'Libros'),
(16, 'Juguete');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidad madrid`
--

CREATE TABLE `comunidad madrid` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comunidad madrid`
--

INSERT INTO `comunidad madrid` (`id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `tipo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `tipo`) VALUES
(1, 'Excelente'),
(2, 'Bien'),
(3, 'Defectuoso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_publicacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id`, `id_usuario`, `id_publicacion`) VALUES
(10003, 1, 3),
(10004, 2, 1),
(10005, 3, 2),
(10006, 4, 4),
(10007, 5, 1),
(10008, 6, 3),
(10009, 7, 5),
(10010, 8, 2),
(10011, 9, 4),
(10012, 10, 5),
(10013, 11, 6),
(10014, 12, 1),
(10015, 13, 2),
(10016, 14, 3),
(10017, 15, 4),
(10018, 16, 5),
(10019, 17, 6),
(10020, 18, 3),
(10021, 19, 2),
(10022, 20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id` int(11) NOT NULL,
  `id_entrada` int(11) NOT NULL,
  `id_salida` int(11) NOT NULL,
  `idpublicacion` int(11) NOT NULL,
  `contenido` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`id`, `id_entrada`, `id_salida`, `idpublicacion`, `contenido`, `fecha`, `hora`) VALUES
(1, 1, 2, 1, 'Hola, me interesa', '0000-00-00', '13:10:00'),
(2, 2, 1, 1, 'lo tengo.', '2022-09-30', '14:05:00'),
(3, 3, 1, 2, '¿Todavía está disponible?', '2024-10-02', '10:15:00'),
(4, 1, 3, 2, 'Sí, sigue disponible.', '2024-10-02', '10:30:00'),
(5, 4, 2, 3, 'Me interesa el juguete.', '2024-10-03', '11:45:00'),
(6, 2, 4, 3, 'Te paso más detalles.', '2024-10-03', '12:00:00'),
(7, 5, 3, 4, '¿Puedo recogerlo mañana?', '2024-10-04', '09:20:00'),
(8, 3, 5, 4, 'Sí, sin problema.', '2024-10-04', '09:35:00'),
(9, 6, 4, 5, '¿En qué estado está?', '2024-10-05', '15:50:00'),
(10, 4, 6, 5, 'Está en buen estado.', '2024-10-05', '16:05:00'),
(11, 7, 5, 6, '¿Tienes más fotos?', '2024-10-06', '17:20:00'),
(12, 5, 7, 6, 'Sí, te envío algunas.', '2024-10-06', '17:45:00'),
(13, 8, 6, 7, 'Quiero confirmar el intercambio.', '2024-10-07', '18:10:00'),
(14, 6, 8, 7, 'Confirmado.', '2024-10-07', '18:25:00'),
(15, 9, 7, 8, '¿Cuándo puedo pasar?', '2024-10-08', '14:00:00'),
(16, 7, 9, 8, 'Mañana por la tarde.', '2024-10-08', '14:15:00'),
(17, 10, 8, 9, 'Estoy interesado en el objeto.', '2024-10-09', '13:30:00'),
(18, 8, 10, 9, 'Te lo reservo.', '2024-10-09', '13:45:00'),
(19, 11, 9, 10, '¿Está disponible para donación?', '2024-10-10', '12:00:00'),
(20, 9, 11, 10, 'Sí, aún está disponible.', '2024-10-10', '12:15:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objeto`
--

CREATE TABLE `objeto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `imagen` text NOT NULL,
  `imagen2` varchar(300) NOT NULL,
  `imagen3` varchar(300) NOT NULL,
  `imagen4` varchar(300) NOT NULL,
  `imagen5` varchar(300) NOT NULL,
  `validado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objeto`
--

INSERT INTO `objeto` (`id`, `nombre`, `id_usuario`, `id_estado`, `id_categoria`, `imagen`, `imagen2`, `imagen3`, `imagen4`, `imagen5`, `validado`) VALUES
(1, 'Mochila', 1, 1, 1, 'foudwboavbdasjbcodsb', '', '', '', '', 0),
(2, 'cuna', 2, 2, 2, 'jjgt:://fewibviudwbaoufivbwiy', '', '', '', '', 0),
(3, 'Bicicleta', 3, 1, 1, 'imagen_bici1.jpg', 'imagen_bici2.jpg', '', '', '', 0),
(4, 'Chaqueta', 4, 2, 2, 'imagen_chaqueta1.jpg', '', '', '', '', 0),
(5, 'Pelota', 5, 1, 1, 'imagen_pelota1.jpg', 'imagen_pelota2.jpg', 'imagen_pelota3.jpg', '', '', 0),
(6, 'Carrito de bebé', 6, 3, 2, 'imagen_carrito1.jpg', '', '', '', '', 0),
(7, 'Muñeca', 7, 1, 1, 'imagen_muneca1.jpg', 'imagen_muneca2.jpg', '', '', '', 0),
(8, 'Zapatillas', 8, 2, 2, 'imagen_zapatillas1.jpg', '', '', '', '', 0),
(9, 'Microondas', 9, 3, 3, 'imagen_microondas1.jpg', 'imagen_microondas2.jpg', '', '', '', 0),
(10, 'Cuna de viaje', 10, 1, 2, 'imagen_cuna_viaje1.jpg', '', '', '', '', 0),
(11, 'Camión de juguete', 11, 1, 1, 'imagen_camion1.jpg', '', '', '', '', 0),
(12, 'Mesa pequeña', 12, 2, 3, 'imagen_mesa1.jpg', '', '', '', '', 0),
(13, 'Peluche', 13, 1, 1, 'imagen_peluche1.jpg', 'imagen_peluche2.jpg', '', '', '', 0),
(14, 'Silla infantil', 14, 3, 3, 'imagen_silla1.jpg', '', '', '', '', 0),
(15, 'Juego de construcción', 15, 1, 1, 'imagen_juego1.jpg', '', '', '', '', 0),
(16, 'Ropa de abrigo', 16, 2, 2, 'imagen_ropa_abrigo1.jpg', '', '', '', '', 0),
(17, 'Coche de juguete', 17, 1, 1, 'imagen_coche1.jpg', '', '', '', '', 0),
(18, 'Tablet infantil', 18, 3, 3, 'imagen_tablet1.jpg', '', '', '', '', 0),
(19, 'Puzzle', 19, 1, 1, 'imagen_puzzle1.jpg', '', '', '', '', 0),
(20, 'Patinete', 20, 1, 1, 'imagen_patinete1.jpg', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicacion`
--

CREATE TABLE `publicacion` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idobjeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicacion`
--

INSERT INTO `publicacion` (`id`, `fecha`, `idusuario`, `idobjeto`) VALUES
(1, '2024-10-02', 1, 1),
(2, '2024-10-13', 2, 2),
(3, '2024-10-15', 3, 3),
(4, '2024-10-16', 4, 4),
(5, '2024-10-17', 5, 5),
(6, '2024-10-18', 6, 6),
(7, '2024-10-19', 7, 7),
(8, '2024-10-20', 8, 8),
(9, '2024-10-21', 9, 9),
(10, '2024-10-22', 10, 10),
(11, '2024-10-23', 11, 11),
(12, '2024-10-24', 12, 12),
(13, '2024-10-25', 13, 13),
(14, '2024-10-26', 14, 14),
(15, '2024-10-27', 15, 15),
(16, '2024-10-28', 16, 16),
(17, '2024-10-29', 17, 17),
(18, '2024-10-30', 18, 18),
(19, '2024-10-31', 19, 19),
(20, '2024-11-01', 20, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id_ubicacion` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT INTO `ubicaciones` (`id_ubicacion`, `nombre`) VALUES
(1, 'Valdemorillo'),
(2, 'Fuenlabrada'),
(3, 'Centro'),
(4, 'Chamberí'),
(5, 'Retiro'),
(6, 'Salamanca'),
(7, 'Moncloa-Aravaca'),
(8, 'Arganzuela'),
(9, 'Latina'),
(10, 'Carabanchel'),
(11, 'Puente de Vallecas'),
(12, 'Usera'),
(13, 'San Blas-Canillejas'),
(14, 'Hortaleza'),
(15, 'Tetuán'),
(16, 'Ciudad Lineal'),
(17, 'Villaverde'),
(18, 'Barajas'),
(19, 'Moratalaz'),
(20, 'Vicálvaro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `codigo_CAM` int(11) NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `idCAM` int(11) NOT NULL,
  `id_ubicacion` int(11) NOT NULL,
  `correo` varchar(60) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `usu_validado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `codigo_CAM`, `fecha_inscripcion`, `idCAM`, `id_ubicacion`, `correo`, `Nombre`, `usu_validado`) VALUES
(1, 3926569, '2024-10-01', 1, 1, '', '', 0),
(2, 673492, '2024-09-26', 1, 2, '', '', 0),
(3, 4527893, '2024-10-05', 1, 3, 'maria.gomez@email.com', '', 0),
(4, 9823745, '2024-10-06', 1, 4, 'luis.perez@email.com', '', 0),
(5, 1234567, '2024-10-07', 1, 5, 'ana.lopez@email.com', '', 0),
(6, 7654321, '2024-10-08', 1, 6, 'carlos.ruiz@email.com', '', 0),
(7, 3498576, '2024-10-09', 1, 7, 'laura.martin@email.com', '', 0),
(8, 2389475, '2024-10-10', 1, 8, 'david.sanchez@email.com', '', 0),
(9, 1092837, '2024-10-11', 1, 9, 'cristina.fernandez@email.com', '', 0),
(10, 5647382, '2024-10-12', 1, 10, 'jose.rodriguez@email.com', '', 0),
(11, 9876543, '2024-10-13', 1, 11, 'sara.garcia@email.com', '', 0),
(12, 8765432, '2024-10-14', 1, 12, 'juan.moreno@email.com', '', 0),
(13, 7654320, '2024-10-15', 1, 13, 'natalia.diaz@email.com', '', 0),
(14, 6543219, '2024-10-16', 1, 14, 'miguel.herrera@email.com', '', 0),
(15, 5432108, '2024-10-17', 1, 15, 'paula.molina@email.com', '', 0),
(16, 4321097, '2024-10-18', 1, 16, 'alberto.gil@email.com', '', 0),
(17, 3210986, '2024-10-19', 1, 17, 'patricia.vera@email.com', '', 0),
(18, 2109875, '2024-10-20', 1, 18, 'raul.castillo@email.com', '', 0),
(19, 1098764, '2024-10-21', 1, 19, 'lucia.ramos@email.com', '', 0),
(20, 1987654, '2024-10-22', 1, 20, 'adrian.blanco@email.com', '', 0),
(21, 2876543, '2024-10-23', 1, 3, 'eva.martinez@email.com', '', 0),
(22, 3765432, '2024-10-24', 1, 4, 'marcos.villa@email.com', '', 0),
(23, 4654321, '2024-10-25', 1, 5, 'ines.rojas@email.com', '', 0),
(24, 5543210, '2024-10-26', 1, 6, 'victor.luna@email.com', '', 0),
(25, 6432109, '2024-10-27', 1, 7, 'elena.cortes@email.com', '', 0),
(26, 7321098, '2024-10-28', 1, 8, 'manuel.torres@email.com', '', 0),
(27, 8210987, '2024-10-29', 1, 9, 'noelia.suarez@email.com', '', 0),
(28, 9109876, '2024-10-30', 1, 10, 'oscar.navarro@email.com', '', 0),
(29, 1098765, '2024-11-01', 1, 11, 'silvia.reyes@email.com', '', 0),
(30, 2198765, '2024-11-02', 1, 12, 'pablo.alonso@email.com', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_publicaciones`
--

CREATE TABLE `usuario_publicaciones` (
  `id_visita` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_publicacion` int(11) DEFAULT NULL,
  `fecha_visita` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_publicaciones`
--

INSERT INTO `usuario_publicaciones` (`id_visita`, `id_usuario`, `id_publicacion`, `fecha_visita`) VALUES
(1, 1, 2, '2024-07-25'),
(2, 2, 1, '2024-10-30'),
(3, 3, 2, '2024-08-01'),
(4, 4, 2, '2024-08-02'),
(5, 5, 3, '2024-09-05'),
(6, 6, 3, '2024-09-06'),
(7, 7, 4, '2024-09-10'),
(8, 8, 4, '2024-09-11'),
(9, 9, 5, '2024-10-01'),
(10, 10, 5, '2024-10-02'),
(11, 11, 6, '2024-10-05'),
(12, 12, 6, '2024-10-06'),
(13, 13, 1, '2024-11-01'),
(14, 14, 2, '2024-11-02'),
(15, 15, 3, '2024-11-03'),
(16, 16, 4, '2024-11-04'),
(17, 17, 5, '2024-11-05'),
(18, 18, 6, '2024-11-06'),
(19, 19, 1, '2024-11-10'),
(20, 20, 2, '2024-11-11'),
(123, 1, 1, '2024-07-25'),
(234, 2, 1, '2024-07-26');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_mensaje`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_mensaje` (
`idmensaje` int(11)
,`emisor` int(11)
,`receptor` int(11)
,`publicacion` int(11)
,`mensaje` varchar(255)
,`fecha` date
,`hora` time
,`objeto` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_objeto`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_objeto` (
`id_objeto` int(11)
,`nombre` varchar(40)
,`estado` varchar(30)
,`id_usuario` int(11)
,`imagen1` text
,`imagen2` varchar(300)
,`imagen3` varchar(300)
,`imagen4` varchar(300)
,`imagen5` varchar(300)
,`categoria_objeto` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_publicacion`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_publicacion` (
`IDautor` int(11)
,`idobjeto` int(11)
,`objeto` varchar(40)
,`id_publicacion` int(11)
,`fecha` date
,`ubicacion_usuario` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_publicaciones_recientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_publicaciones_recientes` (
`IDautor` int(11)
,`idobjeto` int(11)
,`objeto` varchar(40)
,`id_publicacion` int(11)
,`fecha` date
,`ubicacion_usuario` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_mensaje`
--
DROP TABLE IF EXISTS `vista_mensaje`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_mensaje`  AS SELECT `m`.`id` AS `idmensaje`, `m`.`id_entrada` AS `emisor`, `m`.`id_salida` AS `receptor`, `m`.`idpublicacion` AS `publicacion`, `m`.`contenido` AS `mensaje`, `m`.`fecha` AS `fecha`, `m`.`hora` AS `hora`, `p`.`idobjeto` AS `objeto` FROM (`mensaje` `m` join `publicacion` `p` on(`m`.`idpublicacion` = `p`.`id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_objeto`
--
DROP TABLE IF EXISTS `vista_objeto`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_objeto`  AS SELECT `o`.`id` AS `id_objeto`, `o`.`nombre` AS `nombre`, `e`.`tipo` AS `estado`, `u`.`id` AS `id_usuario`, `o`.`imagen` AS `imagen1`, `o`.`imagen2` AS `imagen2`, `o`.`imagen3` AS `imagen3`, `o`.`imagen4` AS `imagen4`, `o`.`imagen5` AS `imagen5`, `c`.`Nombre` AS `categoria_objeto` FROM (((`objeto` `o` join `estado` `e` on(`o`.`id_estado` = `e`.`id`)) join `usuario` `u` on(`o`.`id_usuario` = `u`.`id`)) join `categorias_objetos` `c` on(`o`.`id_categoria` = `c`.`Id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_publicacion`
--
DROP TABLE IF EXISTS `vista_publicacion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_publicacion`  AS SELECT `p`.`idusuario` AS `IDautor`, `p`.`idobjeto` AS `idobjeto`, `o`.`nombre` AS `objeto`, `p`.`id` AS `id_publicacion`, `p`.`fecha` AS `fecha`, `h`.`nombre` AS `ubicacion_usuario` FROM (((`publicacion` `p` join `usuario` `u` on(`p`.`idusuario` = `u`.`id`)) join `objeto` `o` on(`p`.`idobjeto` = `o`.`id`)) join `ubicaciones` `h` on(`h`.`id_ubicacion` = `u`.`id_ubicacion`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_publicaciones_recientes`
--
DROP TABLE IF EXISTS `vista_publicaciones_recientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_publicaciones_recientes`  AS SELECT `p`.`idusuario` AS `IDautor`, `p`.`idobjeto` AS `idobjeto`, `o`.`nombre` AS `objeto`, `p`.`id` AS `id_publicacion`, `p`.`fecha` AS `fecha`, `h`.`nombre` AS `ubicacion_usuario` FROM (((`publicacion` `p` join `usuario` `u` on(`p`.`idusuario` = `u`.`id`)) join `objeto` `o` on(`p`.`idobjeto` = `o`.`id`)) join `ubicaciones` `h` on(`h`.`id_ubicacion` = `u`.`id_ubicacion`)) ORDER BY `p`.`fecha` DESC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias_objetos`
--
ALTER TABLE `categorias_objetos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `comunidad madrid`
--
ALTER TABLE `comunidad madrid`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_publicacion` (`id_publicacion`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_entrada` (`id_entrada`),
  ADD KEY `id_salida` (`id_salida`),
  ADD KEY `idpublicacion` (`idpublicacion`);

--
-- Indices de la tabla `objeto`
--
ALTER TABLE `objeto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idobjeto` (`idobjeto`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id_ubicacion`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCAM` (`idCAM`),
  ADD KEY `id_ubicacion` (`id_ubicacion`);

--
-- Indices de la tabla `usuario_publicaciones`
--
ALTER TABLE `usuario_publicaciones`
  ADD PRIMARY KEY (`id_visita`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_publicacion` (`id_publicacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias_objetos`
--
ALTER TABLE `categorias_objetos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `comunidad madrid`
--
ALTER TABLE `comunidad madrid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10023;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `objeto`
--
ALTER TABLE `objeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_publicacion`) REFERENCES `publicacion` (`id`);

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`idpublicacion`) REFERENCES `publicacion` (`id`),
  ADD CONSTRAINT `mensaje_ibfk_2` FOREIGN KEY (`id_entrada`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `mensaje_ibfk_3` FOREIGN KEY (`id_salida`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `objeto`
--
ALTER TABLE `objeto`
  ADD CONSTRAINT `objeto_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `objeto_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `objeto_ibfk_4` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_objetos` (`Id`);

--
-- Filtros para la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD CONSTRAINT `publicacion_ibfk_1` FOREIGN KEY (`idobjeto`) REFERENCES `objeto` (`id`),
  ADD CONSTRAINT `publicacion_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idCAM`) REFERENCES `comunidad madrid` (`id`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id_ubicacion`);

--
-- Filtros para la tabla `usuario_publicaciones`
--
ALTER TABLE `usuario_publicaciones`
  ADD CONSTRAINT `usuario_publicaciones_ibfk_1` FOREIGN KEY (`id_publicacion`) REFERENCES `publicacion` (`id`),
  ADD CONSTRAINT `usuario_publicaciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
