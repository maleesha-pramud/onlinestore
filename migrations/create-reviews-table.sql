-- Migration to add reviews functionality

CREATE TABLE IF NOT EXISTS `reviews` (
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
