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



CREATE TABLE `tstech_test`.`deposit` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `$account_id` INT NULL,
  `$balance` DOUBLE(20,2) NULL,
  `$deposit_percent` INT NULL,
  PRIMARY KEY (`id`));


CREATE TABLE `tstech_test`.`account_deposit` (
  `deposit_id` INT NOT NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`deposit_id`, `account_id`));


CREATE TABLE `tstech_test`.`account` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `balance` DECIMAL(20,2) NULL,
  PRIMARY KEY (`id`));
