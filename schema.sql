CREATE TABLE `users` (
  `user_id` char(36) NOT NULL COMMENT 'guid',
  `username` varchar(45) NOT NULL,
  `password` char(128) NOT NULL,
  `email` varchar(45) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `lastlogin_date` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `session` char(128) DEFAULT NULL COMMENT 'sha512',
  `lastaccess_date` datetime DEFAULT NULL,
  `failed_attempts` int(11) NOT NULL DEFAULT '0',
  `block_date` datetime DEFAULT NULL COMMENT 'blocked untill this date',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `session` (`session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `login_attempts` (
  `attempt_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(36) NOT NULL,
  `status` enum('failed','logged','blocked') NOT NULL,
  `user_ip` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`attempt_id`,`user_id`),
  KEY `FK_users_idx` (`user_id`),
  CONSTRAINT `FK_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;


INSERT INTO `users` (user_id,created_date,updated_date,`username`,`password`,`email`,`fullname`,`sex`)
VALUES (UUID(),NOW(),NOW(),'root',
-- password is 'root'
'2ab1059a968d12c34c84535dd55c5d4f4a8a6668c882dad78664907e571ef2c3525d49fe9462741e830f833f6c2b35012f2201e45305b695ee961c7cfef76899'
,'my@email.com','name surname','male');