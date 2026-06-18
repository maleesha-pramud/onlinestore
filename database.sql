CREATE DATABASE IF NOT EXISTS `online_store`;
USE `online_store`;

-- Table structure for `user_types`
CREATE TABLE `user_types` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Insert initial data for `user_types`
INSERT INTO `user_types` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Producer'),
(3, 'Customer');

-- Table structure for `status`
CREATE TABLE `status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Insert initial data for `status`
INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Active'),
(2, 'Inactive');

-- Table structure for `users`
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(50) NOT NULL,
  `lname` VARCHAR(50) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `vcode` VARCHAR(255) DEFAULT NULL,
  `user_type_id` INT NOT NULL,
  `status_id` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
);

-- Table structure for `categories`
CREATE TABLE `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Table structure for `amenities`
CREATE TABLE `amenities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `icon_cls` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Insert initial data for `amenities`
INSERT INTO `amenities` (`name`, `icon_cls`) VALUES
('Wifi', 'bi bi-wifi'),
('Swimming Pool', 'bi bi-water'),
('Air Conditioning', 'bi bi-snow'),
('Kitchen', 'bi bi-cup-hot'),
('Free Parking', 'bi bi-p-circle'),
('TV', 'bi bi-tv');

-- Table structure for `properties`
CREATE TABLE `properties` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `categories_id` INT NOT NULL,
  `description` TEXT NOT NULL,
  `address` TEXT NOT NULL,
  `guests` INT NOT NULL,
  `bedrooms` INT NOT NULL,
  `beds` INT NOT NULL,
  `bathrooms` INT NOT NULL,
  `images` TEXT NOT NULL,
  `base_price` DECIMAL(10,2) NOT NULL,
  `users_id` INT NOT NULL,
  `status_id` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
);

-- Table structure for `properties_has_amenities`
CREATE TABLE `properties_has_amenities` (
  `properties_id` INT NOT NULL,
  `amenities_id` INT NOT NULL,
  PRIMARY KEY (`properties_id`, `amenities_id`)
);

-- Table structure for `bookings`
CREATE TABLE `bookings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `checkIn` DATE NOT NULL,
  `checkOut` DATE NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `nic` VARCHAR(20) NOT NULL,
  `contact` VARCHAR(20) NOT NULL,
  `guests` INT NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `special_requests` TEXT,
  `properties_id` INT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `order_id` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Table structure for `reviews`
CREATE TABLE `reviews` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `properties_id` INT NOT NULL,
  `users_id` INT NOT NULL,
  `rating` INT NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_reviews_properties` FOREIGN KEY (`properties_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- Table structure for `cart`
CREATE TABLE `cart` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `properties_id` INT NOT NULL,
  `checkIn` DATE NOT NULL,
  `checkOut` DATE NOT NULL,
  `guests` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cart_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_properties` FOREIGN KEY (`properties_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
);
