CREATE SCHEMA `guest` ;

CREATE TABLE `guest`.`employees` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `Age` INT NOT NULL,
  `Project` VARCHAR(45) NULL,
  `Department` VARCHAR(45) NULL,
  `isActive` TINYINT(1) NULL,
  PRIMARY KEY (`id`));


INSERT INTO `guest`.`employees` (`Name`, `Age`, `Project`, `Department`, `isActive`) VALUES ('Goran', '31', 'Onboarding', 'PHP', '1');
