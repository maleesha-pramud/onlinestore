-- Migration to add status functionality

CREATE TABLE IF NOT EXISTS `status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Insert initial data for `status`
INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Active'),
(2, 'Inactive');

-- Add status_id to users
ALTER TABLE `users` ADD COLUMN `status_id` INT NOT NULL DEFAULT 1;
ALTER TABLE `users` ADD CONSTRAINT `fk_users_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

-- Add status_id to properties
ALTER TABLE `properties` ADD COLUMN `status_id` INT NOT NULL DEFAULT 1;
ALTER TABLE `properties` ADD CONSTRAINT `fk_properties_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);
