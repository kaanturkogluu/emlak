CREATE TABLE `about_us_team_members` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 `position` varchar(255) NOT NULL,
 `description` text DEFAULT NULL,
 `image_url` varchar(500) DEFAULT NULL,
 `sort_order` int(11) DEFAULT 0,
 `is_active` tinyint(1) DEFAULT 1,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
CREATE TABLE `admin_users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `password` varchar(255) NOT NULL,
 `email` varchar(100) NOT NULL,
 `full_name` varchar(100) NOT NULL,
 `role` enum('admin','moderator','editor') DEFAULT 'admin',
 `status` enum('active','inactive') DEFAULT 'active',
 `last_login` datetime DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `cities` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `slug` varchar(100) NOT NULL,
 `status` enum('active','inactive') DEFAULT 'active',
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `plate_code` varchar(2) DEFAULT NULL,
 `region` varchar(50) DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=531 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `districts` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `city_id` int(11) NOT NULL,
 `name` varchar(100) NOT NULL,
 `slug` varchar(100) NOT NULL,
 `status` enum('active','inactive') DEFAULT 'active',
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`),
 UNIQUE KEY `unique_district` (`city_id`,`slug`),
 CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=782 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `neighborhoods` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `district_id` int(11) NOT NULL,
 `name` varchar(100) NOT NULL,
 `status` enum('active','deleted') DEFAULT 'active',
 `population` int(11) DEFAULT 0,
 `area` decimal(10,2) DEFAULT 0.00,
 `postal_code` varchar(10) DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 KEY `idx_district_id` (`district_id`),
 KEY `idx_name` (`name`),
 CONSTRAINT `neighborhoods_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=587 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `page_contents` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `page_type` varchar(50) NOT NULL,
 `section_type` varchar(50) NOT NULL,
 `title` varchar(255) DEFAULT NULL,
 `subtitle` text DEFAULT NULL,
 `content` longtext DEFAULT NULL,
 `image_url` varchar(500) DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 UNIQUE KEY `page_section` (`page_type`,`section_type`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
CREATE TABLE `properties` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `slug` varchar(255) NOT NULL,
 `description` text DEFAULT NULL,
 `price` decimal(15,2) NOT NULL,
 `property_type` enum('daire','villa','arsa','isyeri','ofis','depo') NOT NULL,
 `transaction_type` enum('satilik','kiralik','gunluk-kiralik') NOT NULL,
 `city_id` int(11) DEFAULT NULL,
 `district_id` int(11) DEFAULT NULL,
 `address` text DEFAULT NULL,
 `area` decimal(10,2) DEFAULT NULL,
 `room_count` int(11) DEFAULT NULL,
 `living_room_count` int(11) DEFAULT NULL,
 `bathroom_count` int(11) DEFAULT NULL,
 `floor` int(11) DEFAULT NULL,
 `building_age` int(11) DEFAULT NULL,
 `heating_type` varchar(100) DEFAULT NULL,
 `main_image` varchar(255) DEFAULT NULL,
 `images` text DEFAULT NULL,
 `features` text DEFAULT NULL,
 `contact_name` varchar(100) DEFAULT NULL,
 `contact_phone` varchar(20) DEFAULT NULL,
 `contact_email` varchar(100) DEFAULT NULL,
 `featured` tinyint(1) DEFAULT 0,
 `urgent` tinyint(1) DEFAULT 0,
 `status` enum('active','inactive','pending','sold','rented') DEFAULT 'pending',
 `views` int(11) DEFAULT 0,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 `featured_highlighted` tinyint(1) DEFAULT 0 COMMENT 'Ã–ne Ã§Ä±kan ilanlar iÃ§in iÅŸaretleme',
 PRIMARY KEY (`id`),
 UNIQUE KEY `slug` (`slug`),
 KEY `idx_featured_highlighted` (`featured_highlighted`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `property_contacts` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `property_id` int(11) NOT NULL,
 `name` varchar(100) NOT NULL,
 `phone` varchar(20) NOT NULL,
 `email` varchar(100) DEFAULT NULL,
 `message` text DEFAULT NULL,
 `status` enum('new','read','replied') DEFAULT 'new',
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 KEY `idx_property_id` (`property_id`),
 KEY `idx_status` (`status`),
 KEY `idx_created_at` (`created_at`),
 CONSTRAINT `property_contacts_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `quarters` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `neighborhood_id` int(11) DEFAULT NULL,
 `district_id` int(11) NOT NULL,
 `name` varchar(100) NOT NULL,
 `population` int(11) DEFAULT 0,
 `postal_code` varchar(10) DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 KEY `idx_neighborhood_id` (`neighborhood_id`),
 KEY `idx_district_id` (`district_id`),
 KEY `idx_name` (`name`),
 CONSTRAINT `quarters_ibfk_1` FOREIGN KEY (`neighborhood_id`) REFERENCES `neighborhoods` (`id`) ON DELETE CASCADE,
 CONSTRAINT `quarters_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `site_settings` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `setting_key` varchar(100) NOT NULL,
 `setting_value` text DEFAULT NULL,
 `setting_type` enum('text','number','boolean','json') DEFAULT 'text',
 `category` varchar(50) DEFAULT 'general',
 `description` text DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=601 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `sliders` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `subtitle` varchar(500) DEFAULT NULL,
 `button_text` varchar(100) DEFAULT NULL,
 `button_url` varchar(255) DEFAULT NULL,
 `image` varchar(255) NOT NULL,
 `sort_order` int(11) DEFAULT 0,
 `status` enum('active','inactive') DEFAULT 'active',
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `email` varchar(100) NOT NULL,
 `password` varchar(255) NOT NULL,
 `full_name` varchar(100) NOT NULL,
 `phone` varchar(20) DEFAULT NULL,
 `status` enum('active','inactive') DEFAULT 'active',
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci