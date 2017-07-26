-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-07-2016 a las 17:26:28
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

CREATE TABLE IF NOT EXISTS `dfw_access` (
  `id` int(11) NOT NULL,
  `code` varchar(4) NOT NULL,
  `name` varchar(25) NOT NULL,
  `root` char(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dfw_config`
--

CREATE TABLE IF NOT EXISTS `dfw_config` (
  `keyword` varchar(60) NOT NULL,
  `value` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dfw_files`
--

CREATE TABLE IF NOT EXISTS `dfw_files` (
  `id` int(11) NOT NULL,
  `path` varchar(100) NOT NULL,
  `type` varchar(6) NOT NULL,
  `time_r` datetime NOT NULL,
  `param` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dfw_sessions`
--

CREATE TABLE IF NOT EXISTS `dfw_sessions` (
  `user_id` int(11) NOT NULL,
  `browser` varchar(150) NOT NULL,
  `code` varchar(10) NOT NULL,
  `last_time` datetime NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dfw_users`
--

CREATE TABLE IF NOT EXISTS `dfw_users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mail` varchar(120) NOT NULL,
  `time_r` datetime NOT NULL,
  `password` varchar(70) NOT NULL,
  `last_access` date NOT NULL,
  `d_birth` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dfw_users_access`
--

CREATE TABLE IF NOT EXISTS `dfw_users_access` (
  `user` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  `sponsor` int(11) NOT NULL,
  `time_r` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dfw_users_online`
--

CREATE TABLE IF NOT EXISTS `dfw_users_online` (
  `ip` varchar(15) NOT NULL,
  `browser` varchar(150) NOT NULL,
  `last_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dfw_view_counter`
--

CREATE TABLE IF NOT EXISTS `dfw_view_counter` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `views` int(15) NOT NULL,
  `type` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `dfw_access` (`id`, `code`, `name`, `root`) VALUES
(1, 'SADM', 'Super Admin', '1'),
(2, 'ADM', 'Admin', '1'),
(3, 'BAN', 'Banned', '0'),
(4, 'MOD', 'Moderador', '0'),
(5, 'EDT', 'Editor', '0'),
(6, 'VIP', 'VIP', '0'),
(7, 'SUL', 'session_user_lock', '0');

INSERT INTO `dfw_config` (`keyword`, `value`) VALUES
('EXPIRE_SESION_TIME', '604800'),
('MAX_SESION_TIME', '150'),
('ONLINE_STATISTICS', '1'),
('SESSION_COOKIES', '0'),
('SESSION_LOCK_TIME', '180'),
('SESSION_MAX_TRY_LOGIN', '5');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `dfw_access`
--
ALTER TABLE `dfw_access`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD UNIQUE KEY `code` (`code`), ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `dfw_config`
--
ALTER TABLE `dfw_config`
  ADD PRIMARY KEY (`keyword`);

--
-- Indices de la tabla `dfw_files`
--
ALTER TABLE `dfw_files`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `dfw_sessions`
--
ALTER TABLE `dfw_sessions`
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `dfw_users`
--
ALTER TABLE `dfw_users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `e-mail` (`mail`);

--
-- Indices de la tabla `dfw_users_access`
--
ALTER TABLE `dfw_users_access`
  ADD KEY `user` (`user`), ADD KEY `access` (`access`), ADD KEY `sponsor` (`sponsor`), ADD KEY `user_2` (`user`);

--
-- Indices de la tabla `dfw_view_counter`
--
ALTER TABLE `dfw_view_counter`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `dfw_access`
--
ALTER TABLE `dfw_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `dfw_files`
--
ALTER TABLE `dfw_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `dfw_users`
--
ALTER TABLE `dfw_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `dfw_view_counter`
--
ALTER TABLE `dfw_view_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dfw_sessions`
--
ALTER TABLE `dfw_sessions`
ADD CONSTRAINT `dfw_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dfw_users` (`id`);

--
-- Filtros para la tabla `dfw_users_access`
--
ALTER TABLE `dfw_users_access`
ADD CONSTRAINT `dfw_users_access_ibfk_1` FOREIGN KEY (`user`) REFERENCES `dfw_users` (`id`),
ADD CONSTRAINT `dfw_users_access_ibfk_2` FOREIGN KEY (`access`) REFERENCES `dfw_access` (`id`);