-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `esdras_library` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `esdras_library`;

-- Estructura de la tabla `books`
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `publication_year` int(4) NOT NULL,
  `category` varchar(100) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `available` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Estructura de la tabla `clients`
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Estructura de la tabla `rentals`
CREATE TABLE IF NOT EXISTS `rentals` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int(11) UNSIGNED NOT NULL,
  `book_id` int(11) UNSIGNED NOT NULL,
  `rental_date` date NOT NULL,
  `return_date` date NOT NULL,
  `status` enum('active','returned','overdue') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rentals_client_id_foreign` (`client_id`),
  KEY `rentals_book_id_foreign` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Estructura de la tabla `fines`
CREATE TABLE IF NOT EXISTS `fines` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rental_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fines_rental_id_foreign` (`rental_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Estructura de la tabla `users` (si no existe)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertar usuario de prueba con contraseña correcta
INSERT INTO `users` (`username`, `email`, `password`, `created_at`, `updated_at`) 
VALUES ('biblioteca', 'biblioteca@esdras.com', '$2y$12$mskrDkrqm7augQU9csAODemQmvagIwGbec1C1la5aUvlkHIj2s5jK', NOW(), NOW());
-- Nota: La contraseña es 'biblioteca123'

-- Insertar libros de ejemplo
INSERT INTO `books` (`title`, `author`, `isbn`, `publication_year`, `category`, `quantity`, `available`, `created_at`, `updated_at`) VALUES
('Cien años de soledad', 'Gabriel García Márquez', '9780307474728', 1967, 'Novela', 3, 3, NOW(), NOW()),
('El principito', 'Antoine de Saint-Exupéry', '9788478887194', 1943, 'Ficción', 2, 2, NOW(), NOW()),
('Don Quijote de la Mancha', 'Miguel de Cervantes', '9788420412146', 1605, 'Clásico', 1, 1, NOW(), NOW()),
('Harry Potter y la piedra filosofal', 'J.K. Rowling', '9788478884452', 1997, 'Fantasía', 5, 5, NOW(), NOW()),
('1984', 'George Orwell', '9788499890944', 1949, 'Ciencia ficción', 2, 2, NOW(), NOW());

-- Insertar clientes de ejemplo
INSERT INTO `clients` (`name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
('Juan Pérez', 'juan.perez@ejemplo.com', '555-123-4567', 'Calle Principal 123', NOW(), NOW()),
('María García', 'maria.garcia@ejemplo.com', '555-987-6543', 'Avenida Central 456', NOW(), NOW()),
('Carlos Rodríguez', 'carlos.rodriguez@ejemplo.com', '555-456-7890', 'Plaza Mayor 789', NOW(), NOW()),
('Ana Martínez', 'ana.martinez@ejemplo.com', '555-234-5678', 'Calle Secundaria 321', NOW(), NOW()),
('Luis Sánchez', 'luis.sanchez@ejemplo.com', '555-876-5432', 'Avenida Norte 654', NOW(), NOW());

-- Añadir las restricciones de clave foránea después de crear todas las tablas
ALTER TABLE `rentals`
ADD CONSTRAINT `rentals_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `rentals_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fines`
ADD CONSTRAINT `fines_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Insertar algunos préstamos de ejemplo (uno activo, uno devuelto y uno vencido)
-- Préstamo activo
INSERT INTO `rentals` (`client_id`, `book_id`, `rental_date`, `return_date`, `status`, `created_at`, `updated_at`) 
SELECT 1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'active', NOW(), NOW()
FROM dual WHERE EXISTS (SELECT 1 FROM `clients` WHERE id = 1) AND EXISTS (SELECT 1 FROM `books` WHERE id = 1);

-- Actualizar disponibilidad del libro
UPDATE `books` SET `available` = `available` - 1 WHERE id = 1 AND `available` > 0;

-- Préstamo devuelto
INSERT INTO `rentals` (`client_id`, `book_id`, `rental_date`, `return_date`, `status`, `created_at`, `updated_at`) 
SELECT 2, 2, DATE_SUB(CURDATE(), INTERVAL 14 DAY), DATE_SUB(CURDATE(), INTERVAL 7 DAY), 'returned', NOW(), NOW()
FROM dual WHERE EXISTS (SELECT 1 FROM `clients` WHERE id = 2) AND EXISTS (SELECT 1 FROM `books` WHERE id = 2);

-- Préstamo vencido
INSERT INTO `rentals` (`client_id`, `book_id`, `rental_date`, `return_date`, `status`, `created_at`, `updated_at`) 
SELECT 3, 3, DATE_SUB(CURDATE(), INTERVAL 14 DAY), DATE_SUB(CURDATE(), INTERVAL 7 DAY), 'overdue', NOW(), NOW()
FROM dual WHERE EXISTS (SELECT 1 FROM `clients` WHERE id = 3) AND EXISTS (SELECT 1 FROM `books` WHERE id = 3);

-- Actualizar disponibilidad del libro
UPDATE `books` SET `available` = `available` - 1 WHERE id = 3 AND `available` > 0;

-- Insertar una multa para el préstamo vencido
INSERT INTO `fines` (`rental_id`, `amount`, `status`, `created_at`, `updated_at`) 
SELECT id, 5.00, 'pending', NOW(), NOW() FROM `rentals` WHERE `client_id` = 3 AND `book_id` = 3 LIMIT 1;