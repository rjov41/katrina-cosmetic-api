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

-- Volcando datos para la tabla api_maquillaje.categorias: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`id`, `tipo`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'B', 'esta es descripcion', 1, NULL, '2022-01-27 01:01:26'),
	(7, 'A', 'esta es descripcion', 1, '2022-01-27 00:38:41', '2022-01-27 00:38:41');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.clientes: ~14 rows (aproximadamente)
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` (`id`, `categoria_id`, `frecuencia_id`, `user_id`, `nombreCompleto`, `nombreEmpresa`, `celular`, `telefono`, `direccion_casa`, `direccion_negocio`, `cedula`, `dias_cobro`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 18, 'weefwfwe', 'ewfewefw', 1131905210, 1131905210, 'Av.francisco beiro 3360', 'Av.francisco beiro 3360', '23223232323233', 'jueves,miércoles,viernes,sábado', 1, '2022-02-19 22:59:13', '2022-02-20 00:05:05'),
	(2, 1, 2, 4, 'asfasfas', 'asfasf', 1131905212, 1131905210, 'Av.francisco beiro 3360', 'Av.francisco beiro 3360', '13133113131314', 'jueves', 1, '2022-02-19 23:01:34', '2022-02-19 23:01:34'),
	(3, 7, 2, 7, 'asfassfa', 'bffbfffb', 433343343434, 344334433434, 'Av.francisco beiro 3360', 'Av.francisco beiro 3360', '34343434433434', 'jueves,miércoles', 1, '2022-02-19 23:07:05', '2022-02-19 23:07:05'),
	(4, 1, 1, 12, 'refegqfweqdwqwd', 'fafasfasfasreggeweg', 4344343344, 533535533543, 'Av.Francisco Beiró 3360', 'Av.Francisco Beiró 3360', '12213343434554', 'viernes', 1, '2022-02-19 23:10:57', '2022-02-19 23:10:57'),
	(5, 1, 1, NULL, 'weeftbr', 'gwegwe', 13133113313, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '12233113311313', 'viernes', 1, '2022-02-19 23:12:17', '2022-02-19 23:12:17'),
	(6, 1, 1, NULL, 'weeftbr', 'gwegwe', 13133113416, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '19933113311313', 'viernes', 1, '2022-02-19 23:14:52', '2022-02-19 23:14:52'),
	(7, 1, 1, NULL, 'weeftbr', 'gwegwe', 13138113416, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '19939113311313', 'viernes', 1, '2022-02-19 23:19:06', '2022-02-19 23:19:06'),
	(8, 1, 1, NULL, 'weeftbr', 'gwegwe', 13138113916, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '19939113911313', 'viernes', 1, '2022-02-19 23:19:32', '2022-02-19 23:19:32'),
	(9, 1, 1, NULL, 'weeftbr', 'gwegwe', 13138113919, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '19939113911913', 'viernes', 1, '2022-02-19 23:21:12', '2022-02-19 23:21:12'),
	(10, 1, 1, NULL, 'weeftbr', 'gwegwe', 13138193919, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '19939103911913', 'viernes', 1, '2022-02-19 23:23:49', '2022-02-19 23:23:49'),
	(11, 1, 1, NULL, 'weeftbr', 'gwegwe', 13198193919, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '19999103911913', 'viernes', 1, '2022-02-19 23:24:31', '2022-02-19 23:24:31'),
	(12, 1, 1, 9, 'weeftbr', 'gwegwe', 13198993919, 434343434433, '3360 Francisco Bei', '3360 Francisco Beiró', '19999903911913', 'viernes', 1, '2022-02-19 23:26:59', '2022-02-19 23:26:59'),
	(13, 1, 2, 9, 'sdfvzdcvsd', 'dggsdgsd', 97989789798, 979897897988, 'Av.francisco beiro 336', 'Av.francisco beiro 336', '97989789798885', 'sábado', 1, '2022-02-19 23:29:58', '2022-02-19 23:49:08'),
	(14, 1, 1, 8, 'cliente 2', 'afasfa', 123443435454, 123443435454, 'afsfasfasfas', 'asfaasasf', '12344343545466', 'viernes', 1, '2022-02-19 23:54:54', '2022-02-19 23:54:54');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.facturas: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `facturas` DISABLE KEYS */;
INSERT INTO `facturas` (`id`, `user_id`, `cliente_id`, `monto`, `fecha_vencimiento`, `iva`, `tipo_venta`, `status_pagado`, `status`, `created_at`, `updated_at`) VALUES
	(8, 18, 4, 4350.00, '2022-04-30 12:00:00', 0.00, 2, 1, 1, '2022-02-28 16:25:40', '2022-02-28 16:25:40'),
	(12, 18, 2, 2850.00, '2022-04-30 12:00:00', 0.00, 2, 1, 1, '2022-02-28 17:14:17', '2022-02-28 17:14:17');
/*!40000 ALTER TABLE `facturas` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.factura_detalles: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `factura_detalles` DISABLE KEYS */;
INSERT INTO `factura_detalles` (`id`, `producto_id`, `factura_id`, `cantidad`, `precio`, `created_at`, `updated_at`) VALUES
	(7, 2, 8, 3, 3600.00, '2022-02-28 16:25:40', '2022-02-28 16:25:40'),
	(8, 1, 8, 5, 750.00, '2022-02-28 16:25:40', '2022-02-28 16:25:40'),
	(9, 3, 12, 3, 2400.00, '2022-02-28 17:14:17', '2022-02-28 17:14:17'),
	(10, 1, 12, 3, 450.00, '2022-02-28 17:14:17', '2022-02-28 17:14:17');
/*!40000 ALTER TABLE `factura_detalles` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.factura_historials: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `factura_historials` DISABLE KEYS */;
/*!40000 ALTER TABLE `factura_historials` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.failed_jobs: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.frecuencias: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `frecuencias` DISABLE KEYS */;
INSERT INTO `frecuencias` (`id`, `descripcion`, `dias`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'quincenal', 15, 1, '2022-01-24 21:38:13', '2022-01-27 01:36:20'),
	(2, 'mensual', 30, 1, '2022-01-27 01:28:49', '2022-02-08 18:35:49');
/*!40000 ALTER TABLE `frecuencias` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.migrations: ~48 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(110, '2014_10_12_000000_create_users_table', 1),
	(111, '2014_10_12_100000_create_password_resets_table', 1),
	(112, '2019_08_19_000000_create_failed_jobs_table', 1),
	(113, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(114, '2022_01_20_233955_create_permission_tables', 1),
	(115, '2022_01_21_011111_create_categorias_table', 1),
	(116, '2022_01_21_011120_create_frecuencias_table', 1),
	(117, '2022_01_21_141652_create_clientes_table', 1),
	(118, '2022_01_21_145924_create_facturas_table', 1),
	(119, '2022_01_21_153506_create_productos_table', 1),
	(120, '2022_01_21_161644_create_factura_detalles_table', 1),
	(121, '2022_01_22_135510_create_factura_historials_table', 1),
	(122, '2014_10_12_000000_create_users_table', 1),
	(123, '2014_10_12_100000_create_password_resets_table', 1),
	(124, '2019_08_19_000000_create_failed_jobs_table', 1),
	(125, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(126, '2022_01_20_233955_create_permission_tables', 1),
	(127, '2022_01_21_011111_create_categorias_table', 1),
	(128, '2022_01_21_011120_create_frecuencias_table', 1),
	(129, '2022_01_21_141652_create_clientes_table', 1),
	(130, '2022_01_21_145924_create_facturas_table', 1),
	(131, '2022_01_21_153506_create_productos_table', 1),
	(132, '2022_01_21_161644_create_factura_detalles_table', 1),
	(133, '2022_01_22_135510_create_factura_historials_table', 1),
	(134, '2014_10_12_000000_create_users_table', 1),
	(135, '2014_10_12_100000_create_password_resets_table', 1),
	(136, '2019_08_19_000000_create_failed_jobs_table', 1),
	(137, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(138, '2022_01_20_233955_create_permission_tables', 1),
	(139, '2022_01_21_011111_create_categorias_table', 1),
	(140, '2022_01_21_011120_create_frecuencias_table', 1),
	(141, '2022_01_21_141652_create_clientes_table', 1),
	(142, '2022_01_21_145924_create_facturas_table', 1),
	(143, '2022_01_21_153506_create_productos_table', 1),
	(144, '2022_01_21_161644_create_factura_detalles_table', 1),
	(145, '2022_01_22_135510_create_factura_historials_table', 1),
	(206, '2014_10_12_000000_create_users_table', 1),
	(207, '2014_10_12_100000_create_password_resets_table', 1),
	(208, '2019_08_19_000000_create_failed_jobs_table', 1),
	(209, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(210, '2022_01_20_233955_create_permission_tables', 1),
	(211, '2022_01_21_011111_create_categorias_table', 1),
	(212, '2022_01_21_011120_create_frecuencias_table', 1),
	(213, '2022_01_21_141652_create_clientes_table', 1),
	(214, '2022_01_21_145924_create_facturas_table', 1),
	(215, '2022_01_21_153506_create_productos_table', 1),
	(216, '2022_01_21_161644_create_factura_detalles_table', 1),
	(217, '2022_01_22_135510_create_factura_historials_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.model_has_permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.model_has_roles: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(2, 'App\\Models\\User', 6),
	(3, 'App\\Models\\User', 18),
	(3, 'App\\Models\\User', 19);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.password_resets: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.personal_access_tokens: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
	(1, 'App\\Models\\User', 18, 'tokens', '542377f36a759959b225a9d1050fc73b3f9b1d628a188c58c4cb96d7954eb1ba', '["*"]', NULL, '2022-02-20 01:08:49', '2022-02-20 01:08:49'),
	(2, 'App\\Models\\User', 19, 'tokens', '1037f773e18d1b49ec0d2fda200bbe36f28b79da86acf2160b17a217133a5c0d', '["*"]', NULL, '2022-02-20 03:19:44', '2022-02-20 03:19:44'),
	(3, 'App\\Models\\User', 6, 'tokens', '4134f8ceefc11ce5e0a614ce04e9cf5a0d28fde6e307e5854a065babdaad27b7', '["*"]', NULL, '2022-02-20 18:14:26', '2022-02-20 18:14:26'),
	(4, 'App\\Models\\User', 6, 'tokens', '473566e86d963d674a483491ad44cf351f256f7b9ae0c2f90745d0b9a2a0bfd9', '["*"]', NULL, '2022-02-20 19:40:33', '2022-02-20 19:40:33'),
	(5, 'App\\Models\\User', 6, 'tokens', '6e1746b5b2feea1136271f569431f087669828b9a4fc0dc5bc82ac48a1dd71fd', '["*"]', NULL, '2022-02-20 19:41:52', '2022-02-20 19:41:52');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.productos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` (`id`, `marca`, `modelo`, `stock`, `precio`, `linea`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'Fiat', 'Modelo', 10, 150.00, 'Recta', 'descripcion', 1, '2022-02-20 00:38:30', '2022-02-20 00:42:17'),
	(2, 'Asus', 'Gtx-100', 10, 1200.00, 'Gamer', 'Laptop gammer', 1, '2022-02-20 00:49:19', '2022-02-20 00:49:19'),
	(3, 'Dell', '3600L', 4, 800.00, 'Negra', 'Laptop test', 1, '2022-02-20 22:39:52', '2022-02-20 22:39:52'),
	(4, 'Acer', 'Slim', 9, 600.00, 'L5', 'Laptop Slim gamer', 1, '2022-02-20 22:40:53', '2022-02-20 22:40:53');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.roles: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(2, 'administrador', 'web', '2022-01-25 00:27:11', '2022-01-25 00:27:11'),
	(3, 'vendedor', 'web', '2022-02-13 23:31:21', '2022-02-13 23:31:21'),
	(4, 'supervisor', 'web', '2022-03-02 09:23:04', '2022-03-02 09:23:06');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.role_has_permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;

-- Volcando datos para la tabla api_maquillaje.users: ~16 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `apellido`, `cargo`, `email`, `email_verified_at`, `password`, `remember_token`, `estado`, `created_at`, `updated_at`) VALUES
	(4, 'PEPE', 'sanchez', 'vendedor', 'alejosb16@gmail.com', NULL, '$2y$10$.mU3bdHHOSKWSwA88D2IHebkZx4MLi.0vhLiuO3HiGlx6PC/VLeGG', NULL, 1, '2022-01-25 00:29:44', '2022-02-15 01:20:55'),
	(5, 'PEPE', 'sanchez', 'vendedor', 'alejosb1333@gmail.com', NULL, '$2y$10$EjyBwIUoo5AvTL6sR0oYSOlsv.tTULcmFmX8rBYHejE80qrNuqaHa', NULL, 1, '2022-01-26 22:48:28', '2022-01-26 22:48:28'),
	(6, 'carlos', 'parra', 'vendedor', 'alejandro@gmail.com', NULL, '$2y$10$0XqPnjqVR6agNcS1rWgqv.k7Qzcp1Ko6ZRXzV4MsC.bGNQYyM1Yx.', NULL, 1, '2022-01-26 22:48:28', '2022-02-20 18:14:13'),
	(7, 'PEPE', 'sanchez', 'vendedor', 'alejosb13333@gmail.com', NULL, '$2y$10$04sYnfcyObDx32gOTH4VnOAS0uYFLjZO0LOtgHMKvhw80SSC6trKS', NULL, 1, '2022-02-13 22:01:26', '2022-02-13 22:01:26'),
	(8, 'Ricardo', 'Panza', 'Tester', 'panza2@gmail.com', NULL, '$2y$10$GTd3JK8f./1jJdLap8SvTOoEtlpll93X2e5q.WhoAidplV3nEVdvS', NULL, 1, '2022-02-15 01:29:01', '2022-02-15 02:10:20'),
	(9, 'Alejandro Javier', 'Sanchez', 'Programador', 'alejosb13@gmail.com', NULL, '$2y$10$ibOemSnIlehLaD4ye2V1Y.1HeZTBufN.TD4g0UJqMgJ/QpOq/kHM.', NULL, 1, '2022-02-15 02:11:21', '2022-02-15 02:11:21'),
	(10, 'PEPE', 'sanchez', 'vendedor', 'alejosb133333@gmail.com', NULL, '$2y$10$TOqhQ2fwC6npP/FwmRXOLeOFmk8qhuJOsRsDct2MStUVPwugLjldm', NULL, 1, '2022-02-15 02:12:39', '2022-02-15 02:12:39'),
	(11, 'PEPE1', 'sanchez', 'vendedor', 'alej3@gmail.com', NULL, '$2y$10$8ytCbbStEJrR0vo3lE556eXmyFUUyO8KQ8RpJ5iVUBjCHeQRrNRnG', NULL, 1, '2022-02-15 02:12:58', '2022-02-15 02:12:58'),
	(12, 'PEPE1', 'sanchez', 'vendedor', 'alej33@gmail.com', NULL, '$2y$10$qY1gik8ztSDmZh.DufxLt.03PhCWSQBmFHaFQ2XpSeRdO9taqOUd6', NULL, 1, '2022-02-15 02:14:52', '2022-02-15 02:14:52'),
	(13, 'PEPE1', 'sanchez', 'vendedor', 'alej333@gmail.com', NULL, '$2y$10$06cIWTNpSkNOjO.J5u8/OeqKx5E6LqlDp9/eT1NARnWW7XyrsBaqK', NULL, 1, '2022-02-15 02:15:24', '2022-02-15 02:15:24'),
	(14, 'PEPE1', 'sanchez', 'vendedor', 'alej3333@gmail.com', NULL, '$2y$10$bniJd3IbMPGLfCH3NpiGD.rDdkO8aWchYsJqkFzvDczzIt64QHW52', NULL, 1, '2022-02-15 02:16:23', '2022-02-15 02:16:23'),
	(15, 'PEPE1', 'sanchez', 'vendedor', 'alej33333@gmail.com', NULL, '$2y$10$WXcyo0hGJXxikqVe.WV1hePVto8eE9eDG7fh2o/8r7PkU6FBtwiCu', NULL, 1, '2022-02-15 02:16:31', '2022-02-15 02:16:31'),
	(16, 'tes2', 'tes2', 'Test', 'test2@gmail.com', NULL, '$2y$10$DOhpBD3Sxia3s1HZypcOBOsGtD7ACwbl6yHunEbvj3j/gJzsXwmKm', NULL, 1, '2022-02-15 02:18:16', '2022-02-15 02:18:16'),
	(17, 'Alejandro Sanhce', 'asfasfsa', 'TEST', 'afsasf@gmail.com', NULL, '$2y$10$EH19BjS4qEgD.0dXdpU5kOkQxSKQGiBxCCGmoXuDOmwRuKAM9itIi', NULL, 1, '2022-02-15 02:20:28', '2022-02-15 02:20:28'),
	(18, 'alejandro', 'sanchez', 'Vendedor', 'alejand@gmail.com', NULL, '$2y$10$Ip9tuCdYw6cRhUZTS5whluWXBtaVZvjyjikjVQbeVfY54QWH9oVd6', NULL, 1, '2022-02-20 01:08:49', '2022-02-20 03:19:04'),
	(19, 'Vendedor', 'VEnde', 'Vende', 'vende@gmail.com', NULL, '$2y$10$llz6CA8rEqtONxshSzcqDuP/kGgW3IVT3ROWibtLchMoGV2hXLTmW', NULL, 1, '2022-02-20 03:19:44', '2022-02-20 03:19:44');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
