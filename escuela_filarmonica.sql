-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-05-2026 a las 21:51:57
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
-- Base de datos: `escuela_filarmonica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuncios`
--

CREATE TABLE `anuncios` (
  `id_anuncio` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`id_anuncio`, `id_asignatura`, `id_profesor`, `titulo`, `contenido`, `fecha_publicacion`, `id_curso`) VALUES
(2, 3, 2, 'Entrega de ejercicios y dictados', 'Se recuerda que el próximo martes finaliza el plazo de entrega de los ejercicios correspondientes al bloque de lenguaje musical. Además, en clase se realizará un dictado rítmico y melódico evaluable. Se recomienda repasar las últimas actividades trabajadas en el aula.', '2026-04-18', 2),
(3, 4, 2, 'Ensayo extraordinario sección instrumental', 'El viernes se realizará un ensayo extraordinario con todo el alumnado matriculado en las asignaturas instrumentales del curso. La asistencia será muy importante para preparar la actuación conjunta prevista para el mes de junio. El alumnado deberá acudir con atril y partituras completas.', '2026-05-02', 2),
(4, 5, 2, 'Cambio temporal de aula', 'Durante esta semana algunas clases se impartirán en aulas alternativas debido a trabajos de mantenimiento en la planta principal. El profesorado indicará al alumnado la ubicación correspondiente al inicio de cada sesión. Rogamos puntualidad para evitar interrupciones durante las clases.', '2026-05-11', 2),
(5, 2, 2, 'Audiciones individuales del tercer trimestre', 'A partir de la próxima semana comenzarán las audiciones individuales de evaluación del tercer trimestre. Cada alumno interpretará las obras trabajadas durante el curso y se valorará especialmente la evolución técnica, musicalidad y preparación general del repertorio.', '2026-05-20', 2),
(6, 3, 2, 'Recordatorio asistencia y puntualidad', 'Se recuerda a todo el alumnado la importancia de mantener una asistencia regular y llegar puntualmente a las clases. La continuidad en el trabajo semanal es fundamental para el correcto desarrollo del curso y para la preparación de las actividades finales del trimestre.', '2026-05-27', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id_asignatura` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_profesor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id_asignatura`, `nombre`, `id_curso`, `id_profesor`) VALUES
(1, 'Jardín musical', 1, NULL),
(2, 'Lenguaje musical 1', 2, 2),
(3, 'Iniciación instrumental 1', 2, 2),
(4, 'Lenguaje Musical 2', 3, 2),
(5, 'Instrumento 2', 3, 2),
(6, 'Conjunto instrumental 2', 3, NULL),
(7, 'Lenguaje Musical 3', 4, NULL),
(8, 'Instrumento 3', 4, NULL),
(9, 'Conjunto instrumental 3', 4, NULL),
(10, 'Coro 3', 4, NULL),
(11, 'Lenguaje Musical 4', 5, NULL),
(12, 'Instrumento 4', 5, NULL),
(13, 'Conjunto instrumental 4', 5, NULL),
(14, 'Coro 4', 5, NULL),
(15, 'Lenguaje Musical 5', 6, NULL),
(16, 'Instrumento 5', 6, NULL),
(17, 'Conjunto instrumental 5', 6, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(5,2) DEFAULT NULL,
  `curso_academico` varchar(20) DEFAULT NULL,
  `plazas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre`, `descripcion`, `precio`, `curso_academico`, `plazas`) VALUES
(1, 'Jardín Musical (4-5 años)', 'Iniciación musical para niños de 4 y 5 años mediante juego, ritmo y audición.', 200.00, '2026/2027', 15),
(2, '1º Enseñanzas Elementales', 'Lenguaje musical e iniciación al instrumento.', 210.00, '2026/2027', 15),
(3, '2º Enseñanzas Elementales', 'Lenguaje musical, instrumento y práctica en agrupaciones.', 210.00, '2026/2027', 15),
(4, '3º Enseñanzas Elementales', 'Lenguaje musical, instrumento, coro y conjunto instrumental.', 210.00, '2026/2027', 15),
(5, '4º Enseñanzas Elementales', 'Lenguaje musical, instrumento, coro, conjunto instrumental y preparación a acceso a grado profesional.', 210.00, '2026/2027', 15),
(6, 'Enseñanza para Adultos', 'Formación musical adaptada a adultos con opción de instrumento y agrupaciones.', 465.00, '2026/2027', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `lugar` varchar(150) DEFAULT NULL,
  `publicado` tinyint(1) NOT NULL,
  `cartel` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `titulo`, `descripcion`, `fecha`, `hora`, `lugar`, `publicado`, `cartel`) VALUES
(2, 'Prueba', 'Prueba', '2026-05-29', '10:00:00', 'Callosa', 1, 'subidas/eventos/evento_6a1883ea99936.jpg'),
(3, 'Audiciones Alumnado', '', '2026-06-12', '20:00:00', 'Vega Baja', 1, 'subidas/eventos/evento_6a19d8e329491.jpg'),
(4, 'Festival Percusión', 'Descripción festival percusión', '2026-06-04', '20:00:00', 'Auditorio Municipal de Callosa de Segura', 1, 'subidas/eventos/evento_6a18858dcf1db.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instrumentos`
--

CREATE TABLE `instrumentos` (
  `id_instrumento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instrumentos`
--

INSERT INTO `instrumentos` (`id_instrumento`, `nombre`) VALUES
(1, 'Piano'),
(2, 'Flauta'),
(3, 'Oboe'),
(4, 'Clarinete'),
(5, 'Dulzaina Valenciana'),
(6, 'Fagot'),
(7, 'Saxofón Alto'),
(8, 'Saxofón Tenor'),
(9, 'Trompeta'),
(10, 'Fliscorno'),
(11, 'Trompa'),
(12, 'Trombón'),
(13, 'Bombardino'),
(14, 'Tuba'),
(15, 'Percusión'),
(16, 'Violín / Viola'),
(17, 'Violonchelo'),
(18, 'Contrabajo'),
(19, 'Arpa'),
(20, 'Canto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `id_matricula` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_instrumento` int(15) DEFAULT NULL,
  `fecha_matricula` date DEFAULT NULL,
  `estado` enum('activa','finalizada','cancelada') NOT NULL,
  `observaciones` text DEFAULT NULL,
  `tutor_nombre` varchar(255) DEFAULT NULL,
  `tutor_dni` varchar(20) DEFAULT NULL,
  `tutor_email` varchar(50) DEFAULT NULL,
  `tutor_telefono` varchar(20) DEFAULT NULL,
  `tutor_consentimiento` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `matriculas`
--

INSERT INTO `matriculas` (`id_matricula`, `id_alumno`, `id_curso`, `id_instrumento`, `fecha_matricula`, `estado`, `observaciones`, `tutor_nombre`, `tutor_dni`, `tutor_email`, `tutor_telefono`, `tutor_consentimiento`) VALUES
(1, 3, 2, 1, '2026-05-28', 'activa', 'Alumno de prueba', 'Tutor Prueba', '12345678l', 'tutor@email.com', '600999999', 1),
(2, 4, 2, 1, '2026-05-28', 'activa', 'Alumno de prueba', 'Tutor 1', NULL, NULL, '600111111', 0),
(3, 5, 2, 1, '2026-05-28', 'activa', 'Alumno de prueba', 'Tutor 2', NULL, NULL, '600222222', 0),
(4, 6, 2, 1, '2026-05-28', 'activa', 'Alumno de prueba', 'Tutor 3', NULL, NULL, '600333333', 0),
(5, 7, 2, 1, '2026-05-28', 'activa', 'Alumno de prueba', 'Tutor 4', NULL, NULL, '600444444', 0),
(6, 8, 2, 1, '2026-05-28', 'activa', 'Alumno de prueba', 'Tutor 5', NULL, NULL, '600555555', 0),
(7, 4, 3, 2, '2026-04-12', 'activa', 'Alumno matriculado en curso avanzado', 'Tutor Curso 3', NULL, NULL, '600666661', 0),
(8, 5, 4, 3, '2026-04-15', 'activa', 'Alumno matriculado en enseñanzas profesionales', 'Tutor Curso 4', NULL, NULL, '600666662', 0),
(9, 6, 5, 4, '2026-04-18', 'activa', 'Alumno matriculado en curso superior', 'Tutor Curso 5', NULL, NULL, '600666663', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_matricula` int(11) NOT NULL,
  `concepto` varchar(150) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagado','vencido') NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo` enum('efectivo','transferencia','tarjeta') DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_alumno`, `id_matricula`, `concepto`, `importe`, `estado`, `fecha_pago`, `metodo`, `observaciones`) VALUES
(3, 3, 1, 'Matrícula curso 2026/2027', 210.00, 'pendiente', '2026-05-28', 'efectivo', 'Pago de prueba'),
(4, 3, 1, 'Matrícula 1º Enseñanzas Elementales', 150.00, 'pendiente', NULL, 'transferencia', NULL),
(5, 4, 2, 'Matrícula 1º Enseñanzas Elementales', 150.00, 'pagado', '2026-05-01', 'efectivo', NULL),
(6, 5, 3, 'Matrícula 1º Enseñanzas Elementales', 150.00, 'pagado', '2026-05-03', 'tarjeta', NULL),
(7, 6, 4, 'Matrícula 1º Enseñanzas Elementales', 150.00, 'vencido', NULL, 'transferencia', NULL),
(8, 7, 5, 'Matrícula 1º Enseñanzas Elementales', 150.00, 'pagado', '2026-05-05', 'efectivo', NULL),
(9, 8, 6, 'Matrícula 1º Enseñanzas Elementales', 150.00, 'pendiente', NULL, 'tarjeta', NULL),
(10, 4, 7, 'Matrícula 2º Enseñanzas Elementales', 170.00, 'pagado', '2026-04-12', 'transferencia', NULL),
(11, 5, 8, 'Matrícula 3º Enseñanzas Elementales', 180.00, 'pagado', '2026-04-15', 'efectivo', NULL),
(12, 6, 9, 'Matrícula 4º Enseñanzas Elementales', 190.00, 'pendiente', NULL, 'transferencia', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prematriculas`
--

CREATE TABLE `prematriculas` (
  `id_prematricula` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` int(15) NOT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `id_curso` int(11) NOT NULL,
  `id_instrumento` int(15) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL,
  `fecha_solicitud` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `tutor_nombre` varchar(255) DEFAULT NULL,
  `tutor_dni` varchar(20) DEFAULT NULL,
  `tutor_email` varchar(50) DEFAULT NULL,
  `tutor_telefono` varchar(20) DEFAULT NULL,
  `tutor_consentimiento` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prematriculas`
--

INSERT INTO `prematriculas` (`id_prematricula`, `nombre`, `apellidos`, `email`, `telefono`, `dni`, `fecha_nacimiento`, `id_curso`, `id_instrumento`, `observaciones`, `estado`, `fecha_solicitud`, `direccion`, `tutor_nombre`, `tutor_dni`, `tutor_email`, `tutor_telefono`, `tutor_consentimiento`) VALUES
(1, 'Juan', 'García Estañ', 'juan@email.com', 123456789, '12345678l', '2020-01-01', 1, 15, '', 'pendiente', '2026-05-28', 'Dirección prematrícula prueba', 'TutorName', '12345678l', 'Tutor@email.com', '123456789', 1),
(2, 'Nombre', 'Apellidos', 'email@email.c', 123456789, '12345678l', '1993-01-01', 2, 18, '', 'pendiente', '2026-05-28', 'dirección', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `id_recurso` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `fecha_subida` date DEFAULT NULL,
  `publicado_por` int(11) DEFAULT NULL,
  `archivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recursos`
--

INSERT INTO `recursos` (`id_recurso`, `id_asignatura`, `titulo`, `fecha_subida`, `publicado_por`, `archivo`) VALUES
(1, 2, 'Ficha Escalas básicas', '2026-04-10', 2, 'ficha_escalas.pdf'),
(4, 4, 'Partitura Himno de la Alegría', '2026-05-01', 2, 'himno_alegria.jpg'),
(5, 5, 'Canal recomendado técnica instrumental', '2026-05-12', 2, 'https://www.youtube.com'),
(6, 2, 'Teoría musical interactiva', '2026-05-14', 2, 'https://www.teoria.com/es/'),
(7, 2, 'Afinador online gratuito', '2026-05-16', 2, 'https://tuner-online.com/es/'),
(8, 3, 'Metrónomo online avanzado', '2026-05-18', 2, 'https://www.musicca.com/es/metronomo'),
(10, 4, 'Canal de ejercicios de técnica instrumental', '2026-05-22', 2, 'https://www.youtube.com/@tonebasePiano'),
(11, 4, 'Biblioteca de partituras IMSLP', '2026-05-24', 2, 'https://imslp.org/'),
(12, 5, 'Ejercicios rítmicos interactivos', '2026-05-26', 2, 'https://www.classicsforkids.com/games/rhythm'),
(13, 5, 'Guía básica de respiración musical', '2026-05-28', 2, 'https://www.wikihow.com/Breathe-While-Playing-an-Instrument');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `rol` enum('admin','profesor','alumno') NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellidos`, `email`, `password`, `telefono`, `dni`, `fecha_nacimiento`, `direccion`, `rol`, `fecha_registro`, `activo`) VALUES
(1, 'Admin', 'Prueba', 'admin@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600111111', '11111111A', '1980-01-01', 'Dirección admin', 'admin', NULL, 1),
(2, 'Profesor', 'Prueba', 'profesor@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600222222', '22222222B', '1985-03-10', 'Dirección Profesor', 'profesor', NULL, 1),
(3, 'Alumno', 'Prueba', 'alumno@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600333333', '33333333C', '2008-05-20', 'Direccion Alumno', 'alumno', NULL, 1),
(4, 'Alumno', 'Musica Uno', 'alumno4@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600444441', '44444441D', '2009-02-10', 'Calle Sol 1', 'alumno', NULL, 1),
(5, 'Alumno', 'Musica Dos', 'alumno5@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600444442', '44444442D', '2008-06-18', 'Calle Sol 2', 'alumno', NULL, 1),
(6, 'Alumno', 'Musica Tres', 'alumno6@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600444443', '44444443D', '2007-11-03', 'Calle Sol 3', 'alumno', NULL, 1),
(7, 'Alumno', 'Musica Cuatro', 'alumno7@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600444444', '44444444D', '2010-01-21', 'Calle Sol 4', 'alumno', NULL, 1),
(8, 'Alumno', 'Musica Cinco', 'alumno8@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '600444445', '44444445D', '2008-09-15', 'Calle Sol 5', 'alumno', NULL, 1),
(9, 'Sergio', 'Luna', 'sergio@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '611111111', '11111111A', '1985-03-15', 'Calle Norte 12', 'profesor', NULL, 1),
(10, 'Andrea', 'Campos', 'andrea@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '622222222', '22222222B', '1990-07-20', 'Avenida Central 8', 'profesor', NULL, 1),
(11, 'Iván', 'Mora', 'ivan@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '633333333', '33333333C', '1978-11-05', 'Plaza Mayor 3', 'profesor', NULL, 1),
(12, 'Laura', 'Vega', 'laura@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '644444444', '44444444D', '1988-02-10', 'Calle Sol 14', 'profesor', NULL, 1),
(13, 'Rubén', 'Gil', 'ruben@email.com', '$2y$10$NfcF5Z9TG.668L6o/66jKOkU46GOMurudUjcXR2JCnXEi/7EHt5EC', '655555555', '55555555E', '1982-09-28', 'Avenida Sur 21', 'profesor', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`id_anuncio`),
  ADD KEY `id_profesor` (`id_profesor`),
  ADD KEY `id_asignatura` (`id_asignatura`) USING BTREE,
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id_asignatura`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`);

--
-- Indices de la tabla `instrumentos`
--
ALTER TABLE `instrumentos`
  ADD PRIMARY KEY (`id_instrumento`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id_matricula`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_instrumento` (`id_instrumento`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `pagos_ibfk_2` (`id_matricula`);

--
-- Indices de la tabla `prematriculas`
--
ALTER TABLE `prematriculas`
  ADD PRIMARY KEY (`id_prematricula`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_instrumento` (`id_instrumento`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`id_recurso`),
  ADD KEY `publicado_por` (`publicado_por`),
  ADD KEY `recursos_ibfk_1` (`id_asignatura`);

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
-- AUTO_INCREMENT de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `id_anuncio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id_asignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `instrumentos`
--
ALTER TABLE `instrumentos`
  MODIFY `id_instrumento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id_matricula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `prematriculas`
--
ALTER TABLE `prematriculas`
  MODIFY `id_prematricula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `id_recurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD CONSTRAINT `anuncios_ibfk_1` FOREIGN KEY (`id_asignatura`) REFERENCES `asignaturas` (`id_asignatura`) ON DELETE CASCADE,
  ADD CONSTRAINT `anuncios_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `anuncios_ibfk_3` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Filtros para la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD CONSTRAINT `asignaturas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `asignaturas_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriculas_ibfk_3` FOREIGN KEY (`id_instrumento`) REFERENCES `instrumentos` (`id_instrumento`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_matricula`) REFERENCES `matriculas` (`id_matricula`) ON DELETE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_3` FOREIGN KEY (`id_matricula`) REFERENCES `matriculas` (`id_matricula`);

--
-- Filtros para la tabla `prematriculas`
--
ALTER TABLE `prematriculas`
  ADD CONSTRAINT `prematriculas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `prematriculas_ibfk_2` FOREIGN KEY (`id_instrumento`) REFERENCES `instrumentos` (`id_instrumento`);

--
-- Filtros para la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD CONSTRAINT `recursos_ibfk_1` FOREIGN KEY (`id_asignatura`) REFERENCES `asignaturas` (`id_asignatura`) ON DELETE CASCADE,
  ADD CONSTRAINT `recursos_ibfk_2` FOREIGN KEY (`publicado_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
