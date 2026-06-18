-- Migration to add cart functionality

CREATE TABLE IF NOT EXISTS `cart` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `properties_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cart_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_properties` FOREIGN KEY (`properties_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
);
