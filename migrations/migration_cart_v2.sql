-- Update cart table to support booking details for multiple properties
ALTER TABLE `cart` 
ADD COLUMN `checkIn` DATE NOT NULL,
ADD COLUMN `checkOut` DATE NOT NULL,
ADD COLUMN `guests` INT NOT NULL;
