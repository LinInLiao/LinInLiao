DROP TABLE IF EXISTS `drinks`;
CREATE TABLE `drinks` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` char(36) CHARACTER SET utf8mb4 NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `drinks_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `drink_categories`;
CREATE TABLE `drink_categories` (
  `drink_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_category_id` char(36) CHARACTER SET utf8mb4 NOT NULL,
  `status` enum('active','deleted') CHARACTER SET utf8mb4 NOT NULL,
  KEY `drink_id` (`drink_id`),
  KEY `store_category_id` (`store_category_id`),
  CONSTRAINT `drink_categories_ibfk_1` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`),
  CONSTRAINT `drink_categories_ibfk_2` FOREIGN KEY (`store_category_id`) REFERENCES `store_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `drink_coldheats`;
CREATE TABLE `drink_coldheats` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drink_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_coldheat_id` char(36) CHARACTER SET utf8mb4 NOT NULL,
  `status` enum('active','deleted') CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `drink_id` (`drink_id`),
  KEY `store_coldheat_id` (`store_coldheat_id`),
  CONSTRAINT `drink_coldheats_ibfk_1` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`),
  CONSTRAINT `drink_coldheats_ibfk_2` FOREIGN KEY (`store_coldheat_id`) REFERENCES `store_coldheats` (`id`),
  CONSTRAINT `drink_coldheats_ibfk_3` FOREIGN KEY (`store_coldheat_id`) REFERENCES `store_coldheats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `drink_coldheats_levels`;
CREATE TABLE `drink_coldheats_levels` (
  `drink_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drink_coldheat_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_coldheat_level_id` char(36) CHARACTER SET utf8mb4 NOT NULL,
  `status` enum('active','deleted') CHARACTER SET utf8mb4 NOT NULL,
  KEY `drink_id` (`drink_id`),
  KEY `drink_coldheat_id` (`drink_coldheat_id`),
  KEY `store_coldheat_level_id` (`store_coldheat_level_id`),
  CONSTRAINT `drink_coldheats_levels_ibfk_1` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`),
  CONSTRAINT `drink_coldheats_levels_ibfk_3` FOREIGN KEY (`drink_coldheat_id`) REFERENCES `drink_coldheats` (`id`),
  CONSTRAINT `drink_coldheats_levels_ibfk_4` FOREIGN KEY (`store_coldheat_level_id`) REFERENCES `store_coldheats_levels` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `drink_extras`;
CREATE TABLE `drink_extras` (
  `drink_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drink_coldheat_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_extra_id` char(36) CHARACTER SET utf8mb4 NOT NULL,
  `status` enum('active','deleted') COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `drink_id` (`drink_id`),
  KEY `drink_coldheat_id` (`drink_coldheat_id`),
  KEY `store_extra_id` (`store_extra_id`),
  CONSTRAINT `drink_extras_ibfk_1` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`),
  CONSTRAINT `drink_extras_ibfk_2` FOREIGN KEY (`drink_coldheat_id`) REFERENCES `drink_coldheats` (`id`),
  CONSTRAINT `drink_extras_ibfk_3` FOREIGN KEY (`store_extra_id`) REFERENCES `store_extras` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `drink_sizes`;
CREATE TABLE `drink_sizes` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drink_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drink_coldheat_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(10) NOT NULL,
  `status` enum('active','deleted') COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `drink_id` (`drink_id`),
  KEY `drink_coldheat_id` (`drink_coldheat_id`),
  CONSTRAINT `drink_sizes_ibfk_1` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`),
  CONSTRAINT `drink_sizes_ibfk_2` FOREIGN KEY (`drink_coldheat_id`) REFERENCES `drink_coldheats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `drink_sugars`;
CREATE TABLE `drink_sugars` (
  `drink_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drink_coldheat_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_sugar_id` char(36) CHARACTER SET utf8mb4 NOT NULL,
  `status` enum('active','deleted') COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `drink_id` (`drink_id`),
  KEY `drink_coldheat_id` (`drink_coldheat_id`),
  KEY `store_sugar_id` (`store_sugar_id`),
  CONSTRAINT `drink_sugars_ibfk_1` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`),
  CONSTRAINT `drink_sugars_ibfk_2` FOREIGN KEY (`drink_coldheat_id`) REFERENCES `drink_coldheats` (`id`),
  CONSTRAINT `drink_sugars_ibfk_3` FOREIGN KEY (`store_sugar_id`) REFERENCES `store_sugars` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id` char(36) NOT NULL,
  `alias` varchar(191) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cover` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `store_name` (`name`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `store_categories`;
CREATE TABLE `store_categories` (
  `id` char(36) NOT NULL,
  `store_id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `store_categories_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `store_coldheats`;
CREATE TABLE `store_coldheats` (
  `id` char(36) NOT NULL,
  `store_id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `store_coldheats_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `store_coldheats_levels`;
CREATE TABLE `store_coldheats_levels` (
  `id` char(36) NOT NULL,
  `store_id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `store_coldheats_levels_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `store_extras`;
CREATE TABLE `store_extras` (
  `id` char(36) NOT NULL,
  `store_id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(10) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `store_extras_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `store_sugars`;
CREATE TABLE `store_sugars` (
  `id` char(36) NOT NULL,
  `store_id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `store_sugars_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
