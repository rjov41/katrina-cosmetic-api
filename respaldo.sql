-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.33 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando datos para la tabla api_maquillaje.categorias: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`id`, `tipo`, `descripcion`, `estado`, `valor_dias`, `created_at`, `updated_at`) VALUES
	(1, 'A', '65 dias', 1, 65, '2022-03-04 20:38:04', '2022-03-04 20:38:05'),
	(2, 'B', '45 dias', 1, 45, '2022-03-04 20:38:04', '2022-03-04 20:38:05'),
	(3, 'C', '35 dias', 1, 35, '2022-03-04 20:38:04', '2022-03-04 20:38:05'),
	(4, 'E', 'desce', 0, 10, '2022-03-13 20:16:49', '2022-03-13 20:34:48');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.clientes: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` (`id`, `categoria_id`, `frecuencia_id`, `user_id`, `nombreCompleto`, `nombreEmpresa`, `celular`, `telefono`, `direccion_casa`, `direccion_negocio`, `cedula`, `dias_cobro`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, 20, 'Cliente perfecto', 'Salon PR', 1131905210, 1131905212, 'Av.francisco beiro 3360', 'Av.francisco beiro 3360', '12142412221344', 'viernes,jueves,miércoles', 1, '2022-03-06 17:55:37', '2022-03-11 14:28:45'),
	(2, 2, 2, 20, 'asffasfs', '95930630', 1131905211, 1131905210, 'Av.Francisco Beiró 3360', 'Av.Francisco Beiró 3360', '13133141241242', 'viernes,jueves,miércoles', 1, '2022-03-11 11:55:40', '2022-03-11 11:55:40'),
	(3, 2, 1, 20, 'afAlejandro', 'Sanz', 1131905210, 1331331311331, 'Av.francisco beiro 3360', 'Av.francisco beiro 3360', '1234567890123455678', 'martes,miércoles', 1, '2022-03-16 13:45:39', '2022-03-16 13:45:39'),
	(4, 2, 2, 21, 'Alejandro', 'test 334234', 11221412141212000000, 11221412141212000000, 'qwqwr', 'arasffas', '0011909880050T', 'miércoles', 1, '2022-03-18 16:15:49', '2022-03-18 16:15:49');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.facturas: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `facturas` DISABLE KEYS */;
INSERT INTO `facturas` (`id`, `user_id`, `cliente_id`, `monto`, `fecha_vencimiento`, `iva`, `tipo_venta`, `status_pagado`, `status`,`despachado`, `created_at`, `updated_at`) VALUES
	(1, 20, 1, 3500.00, '2022-05-07 12:00:00', 0.00, 1, 1, 0,null, '2022-03-06 17:55:53', '2022-03-25 16:33:20'),
	(2, 20, 1, 6000.00, '2022-05-07 12:00:00', 0.00, 2, 1, 1,null, '2022-03-06 18:26:12', '2022-03-06 18:26:12'),
	(3, 20, 1, 1085.00, '2022-05-10 12:00:00', 0.00, 1, 1, 1,null, '2022-03-09 00:18:01', '2022-03-12 23:46:59'),
	(4, 20, 2, 80.00, '2022-04-25 12:00:00', 0.00, 2, 1, 1,null, '2022-03-14 12:47:04', '2022-03-14 12:47:04'),
	(5, 20, 2, 2760.00, '2022-04-25 12:00:00', 0.00, 1, 0, 0,null, '2022-03-14 14:21:36', '2022-03-25 16:34:27'),
	(6, 20, 1, 50.00, '2022-04-07 12:00:00', 0.00, 2, 1, 1,null, '2022-03-18 00:53:34', '2022-03-24 21:13:51'),
	(7, 20, 3, 200.00, '2022-04-14 12:00:00', 0.00, 2, 1, 0,null, '2022-03-24 23:57:40', '2022-03-25 16:32:20');
/*!40000 ALTER TABLE `facturas` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.factura_detalles: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `factura_detalles` DISABLE KEYS */;
INSERT INTO `factura_detalles` (`id`, `producto_id`, `factura_id`, `cantidad`, `precio`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 5, 1, 2, 300.00, 1, '2022-03-06 17:55:53', '2022-03-23 02:18:29'),
	(2, 6, 1, 2, 3200.00, 1, '2022-03-06 17:55:53', '2022-03-21 14:56:48'),
	(3, 6, 2, 2, 6000.00, 1, '2022-03-06 18:26:12', '2022-03-06 18:26:12'),
	(4, 5, 3, 7, 1085.00, 1, '2022-03-09 00:18:01', '2022-03-09 00:18:01'),
	(5, 7, 4, 2, 80.00, 1, '2022-03-14 12:47:04', '2022-03-14 12:47:04'),
	(6, 7, 5, 3, 120.00, 1, '2022-03-14 14:21:36', '2022-03-23 01:31:00'),
	(7, 8, 5, 4, 600.00, 1, '2022-03-14 14:21:36', '2022-03-23 01:32:36'),
	(8, 7, 6, 1, 50.00, 1, '2022-03-18 00:53:34', '2022-03-18 00:53:34'),
	(9, 8, 7, 1, 200.00, 1, '2022-03-24 23:57:40', '2022-03-24 23:57:40');
/*!40000 ALTER TABLE `factura_detalles` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.factura_historials: ~18 rows (aproximadamente)
/*!40000 ALTER TABLE `factura_historials` DISABLE KEYS */;
INSERT INTO `factura_historials` (`id`, `factura_id`, `user_id`, `precio`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 1, 20, 150.00, 1, '2022-03-06 15:05:17', '2022-03-12 20:40:41'),
	(2, 1, 20, 150.00, 0, '2022-03-06 15:05:17', '2022-03-12 20:11:23'),
	(3, 1, 20, 150.00, 1, '2022-03-07 10:52:37', '2022-03-07 11:15:34'),
	(4, 1, 20, 960.00, 1, '2022-03-07 14:48:16', '2022-03-07 14:48:16'),
	(5, 1, 20, 400.00, 1, '2022-03-07 15:24:49', '2022-03-12 18:39:52'),
	(6, 1, 20, 500.00, 0, '2022-03-07 15:25:35', '2022-03-07 15:25:35'),
	(7, 1, 20, 1600.00, 1, '2022-03-07 15:44:06', '2022-03-07 15:44:06'),
	(8, 3, 20, 885.00, 1, '2022-03-12 16:42:17', '2022-03-12 20:21:00'),
	(9, 3, 20, 200.00, 0, '2022-03-12 16:49:44', '2022-03-12 23:46:59'),
	(10, 1, 20, 10.00, 1, '2022-03-12 20:42:55', '2022-03-12 20:43:07'),
	(11, 1, 20, 40.00, 0, '2022-03-12 20:58:39', '2022-03-12 23:33:07'),
	(12, 3, 20, 190.00, 1, '2022-03-12 23:48:03', '2022-03-12 23:48:19'),
	(13, 3, 20, 10.00, 1, '2022-03-12 23:48:39', '2022-03-12 23:48:39'),
	(14, 1, 20, 40.00, 0, '2022-03-12 23:52:33', '2022-03-13 00:02:31'),
	(15, 1, 20, 40.00, 0, '2022-03-13 19:00:13', '2022-03-13 19:02:52'),
	(16, 1, 20, 40.00, 0, '2022-03-13 19:03:02', '2022-03-13 19:04:10'),
	(17, 1, 20, 40.00, 0, '2022-03-13 19:04:18', '2022-03-13 19:04:28'),
	(18, 1, 20, 40.00, 1, '2022-03-13 19:04:36', '2022-03-13 19:04:36');
/*!40000 ALTER TABLE `factura_historials` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.failed_jobs: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.frecuencias: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `frecuencias` DISABLE KEYS */;
INSERT INTO `frecuencias` (`id`, `descripcion`, `dias`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'quincenal', 15, 1, '2022-01-25 01:38:13', '2022-01-27 05:36:20'),
	(2, 'mensual', 30, 1, '2022-01-27 05:28:49', '2022-02-08 22:35:49'),
	(3, 'Anual', 365, 1, '2022-03-14 12:11:23', '2022-03-14 12:24:15');
/*!40000 ALTER TABLE `frecuencias` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.migrations: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.model_has_permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.model_has_roles: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(2, 'App\\Models\\User', 20),
	(2, 'App\\Models\\User', 21),
	(3, 'App\\Models\\User', 22),
	(3, 'App\\Models\\User', 23),
	(3, 'App\\Models\\User', 24);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.password_resets: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.personal_access_tokens: ~19 rows (aproximadamente)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
	(1, 'App\\Models\\User', 18, 'tokens', '542377f36a759959b225a9d1050fc73b3f9b1d628a188c58c4cb96d7954eb1ba', '["*"]', NULL, '2022-02-20 05:08:49', '2022-02-20 05:08:49'),
	(2, 'App\\Models\\User', 19, 'tokens', '1037f773e18d1b49ec0d2fda200bbe36f28b79da86acf2160b17a217133a5c0d', '["*"]', NULL, '2022-02-20 07:19:44', '2022-02-20 07:19:44'),
	(3, 'App\\Models\\User', 6, 'tokens', '4134f8ceefc11ce5e0a614ce04e9cf5a0d28fde6e307e5854a065babdaad27b7', '["*"]', NULL, '2022-02-20 22:14:26', '2022-02-20 22:14:26'),
	(4, 'App\\Models\\User', 6, 'tokens', '473566e86d963d674a483491ad44cf351f256f7b9ae0c2f90745d0b9a2a0bfd9', '["*"]', NULL, '2022-02-20 23:40:33', '2022-02-20 23:40:33'),
	(5, 'App\\Models\\User', 6, 'tokens', '6e1746b5b2feea1136271f569431f087669828b9a4fc0dc5bc82ac48a1dd71fd', '["*"]', NULL, '2022-02-20 23:41:52', '2022-02-20 23:41:52'),
	(6, 'App\\Models\\User', 20, 'tokens', '15615d97e8c6da2cac9f739c92ed14e15cf96bb9bf45cb8502835d78d1a91d44', '["*"]', NULL, '2022-03-04 19:27:40', '2022-03-04 19:27:40'),
	(7, 'App\\Models\\User', 20, 'tokens', 'b13fa9edcf5b6e9885a84fb9245bb64e2bc9b82567293c7243909a9477d58388', '["*"]', NULL, '2022-03-04 19:31:39', '2022-03-04 19:31:39'),
	(8, 'App\\Models\\User', 21, 'tokens', '7052753e7b40852447ec8046f8aaea48631513d974409fad7b94715df59d457a', '["*"]', NULL, '2022-03-04 19:34:04', '2022-03-04 19:34:04'),
	(9, 'App\\Models\\User', 20, 'tokens', '625ca44a3b9c022112e3bcff1a27e14781ffdd82f071a88d6f2b1ecd7aaba163', '["*"]', NULL, '2022-03-11 14:36:37', '2022-03-11 14:36:37'),
	(10, 'App\\Models\\User', 22, 'tokens', '72cd51187a5dc93dd366da9490859da560edd24dd9307b1ad3f18af0ec6ef25f', '["*"]', NULL, '2022-03-11 14:37:40', '2022-03-11 14:37:40'),
	(11, 'App\\Models\\User', 22, 'tokens', '06825a8d8f29580cc04f9250d1b5363d07877c091fdaaead8a333c079ad96366', '["*"]', NULL, '2022-03-11 14:38:00', '2022-03-11 14:38:00'),
	(12, 'App\\Models\\User', 22, 'tokens', 'd7a46f2b0b813c729abe9cd404d029f6756f1a434a7770c1db07d56d291b95ea', '["*"]', NULL, '2022-03-11 14:38:14', '2022-03-11 14:38:14'),
	(13, 'App\\Models\\User', 22, 'tokens', '0283fc2984c1542ac9c847ea4d5b25828ee715c4d43db8b0389ec58ebf57ecb1', '["*"]', NULL, '2022-03-11 14:38:23', '2022-03-11 14:38:23'),
	(14, 'App\\Models\\User', 22, 'tokens', 'f3eba9ca6f79d6a07c45a20f97ed031568a8dd0e8df5f16713fc1a726b0e33c0', '["*"]', NULL, '2022-03-11 14:45:27', '2022-03-11 14:45:27'),
	(15, 'App\\Models\\User', 20, 'tokens', '2fdbdd2da319f9ace58690d59c266bcf3d6f444461fa85fb65c33dae5d527a6b', '["*"]', NULL, '2022-03-11 14:45:43', '2022-03-11 14:45:43'),
	(16, 'App\\Models\\User', 20, 'tokens', '6774eb4b4611e15daf817013059197d441e263c4d509b06567740dec60b0e861', '["*"]', NULL, '2022-03-15 01:38:02', '2022-03-15 01:38:02'),
	(17, 'App\\Models\\User', 23, 'tokens', 'fecd3c9f166f80b1d2464ddfc0ff488d41c8d76f84ede20e8725b7f75067a482', '["*"]', NULL, '2022-03-15 01:43:35', '2022-03-15 01:43:35'),
	(18, 'App\\Models\\User', 20, 'tokens', 'be9c7832f57c77907f409ce7b7020a6305970c6e0d785c35a98095b1e1179314', '["*"]', NULL, '2022-03-15 01:44:12', '2022-03-15 01:44:12'),
	(19, 'App\\Models\\User', 23, 'tokens', '1b2afe3ac52c06e81b381bd038acc3a69e7e1f86cbf192fe07df53cd4a242481', '["*"]', NULL, '2022-03-15 01:45:40', '2022-03-15 01:45:40'),
	(20, 'App\\Models\\User', 24, 'tokens', 'e1ae3d36d4ddb30500bfd099e9784d0292ed8d6e11f6618ebe12596a4713c810', '["*"]', NULL, '2022-03-18 00:56:28', '2022-03-18 00:56:28'),
	(21, 'App\\Models\\User', 24, 'tokens', 'c2421a75fc486b36bcb7f372a3221cb1c1094f7167e0b2d8e9205e42641c1216', '["*"]', NULL, '2022-03-18 00:56:46', '2022-03-18 00:56:46'),
	(22, 'App\\Models\\User', 20, 'tokens', '0bd0e66d4e6050eb83a4ac23c7a57fd35a6d555fdf31f50f28dcc0d5a4f43789', '["*"]', NULL, '2022-03-20 21:02:59', '2022-03-20 21:02:59'),
	(23, 'App\\Models\\User', 20, 'tokens', 'f95557168510ce71a48f2930218eb924a2809dfc601d67995ea39a25467a3917', '["*"]', NULL, '2022-03-25 16:29:23', '2022-03-25 16:29:23');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.productos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` (`id`, `marca`, `modelo`, `stock`, `precio`, `linea`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
	(5, 'Lizz Professional', '0000001', 3, 155.00, 'Planchas', 'Plancha Ultra 500', 0, '2022-03-03 19:54:15', '2022-03-25 16:33:20'),
	(6, 'test', 'test', 11, 3000.00, 'blanca', 'test nada mas', 0, '2022-03-04 03:06:00', '2022-03-25 16:33:20'),
	(7, 'Adidas', '200', 297, 40.00, 'recta', 'desc', 1, '2022-03-11 12:41:48', '2022-03-25 16:34:27'),
	(8, 'Titan', 'colosal', 16, 200.00, 'Roja', 'Acorazado', 1, '2022-03-14 14:19:12', '2022-03-25 16:34:27');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.roles: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(2, 'administrador', 'web', '2022-01-25 04:27:11', '2022-01-25 04:27:11'),
	(3, 'vendedor', 'web', '2022-02-14 03:31:21', '2022-02-14 03:31:21'),
	(4, 'supervisor', 'web', '2022-03-02 13:23:04', '2022-03-02 13:23:06');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.role_has_permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.users: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `apellido`, `cargo`, `email`, `email_verified_at`, `password`, `remember_token`, `estado`, `created_at`, `updated_at`) VALUES
	(20, 'Alejandro', 'sanchez', 'Programador', 'alejosb13@gmail.com', NULL, '$2y$10$ycLD6AVSbqAZmd/qgpuy0OfiHeL7d8vDmsO.xo2OR6o8gV8xlqwZ.', NULL, 0, '2022-03-04 19:27:40', '2022-03-11 12:56:38'),
	(21, 'Rigoberto', 'Gallo', 'Programador', 'rigoberto.gallo@regxi.com', NULL, '$2y$10$pCduaZHii83COKb/Etlrxu0u4VAyxWzCLMIqqTMMo.FAEHHl69.Qe', NULL, 1, '2022-03-04 19:34:04', '2022-03-04 19:34:04'),
	(22, 'Bender', 'Topacio', 'Vendedor', 'alejo@gmail.com', NULL, '$2y$10$7E.t0K1QX8R/pbnvte63jezH/JO7A0CgL0Iqm14pVFHcbYGOJn50W', NULL, 1, '2022-03-11 14:37:40', '2022-03-11 14:37:40'),
	(23, 'test', 'test', '123', 'alejosb1asfasfa3@gmail.com', NULL, '$2y$10$9VcjGO8/xOaT8DbDsZ3NDeJFogeNBt1mXEpGtu9G8PFMDgLOYOltG', NULL, 1, '2022-03-15 01:43:35', '2022-03-15 01:45:02'),
	(24, 'afsafasfas', 'asfasfasf', 'cccss', 'alejosb12@gmail.com', NULL, '$2y$10$c49K62sUGic.otuYvyuJcewc3gZyuggiuLfRyCjwDr3s/UB8QiV5q', NULL, 1, '2022-03-18 00:56:28', '2022-03-18 00:56:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
