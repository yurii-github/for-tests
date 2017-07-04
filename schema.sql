CREATE DATABASE tstech_test;

CREATE TABLE `tstech_test`.`client` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `sex` ENUM('male', 'female') NULL,
  `birth_date` DATETIME NULL,
  PRIMARY KEY (`id`));


ALTER TABLE `tstech_test`.`client`
  CHANGE COLUMN `first_name` `firstname` VARCHAR(45) NULL DEFAULT NULL ,
  CHANGE COLUMN `last_name` `lastname` VARCHAR(45) NULL DEFAULT NULL ,
  CHANGE COLUMN `birth_date` `birthdate` DATETIME NULL DEFAULT NULL ;
