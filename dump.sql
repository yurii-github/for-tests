
---------------- 3 keys --------------------------
CREATE TABLE `views` (
  `ip_address` bigint(20) NOT NULL,
  `view_date` datetime NOT NULL,
  `page_url` varchar(500) NOT NULL COMMENT 'w3c limit is 2000',
  `views_count` int(11) NOT NULL DEFAULT '0',
  `ip_version` enum('4','6') NOT NULL,
  `user_agent` varchar(500) NOT NULL DEFAULT 'unknown',
  PRIMARY KEY (`ip_address`,`page_url`,`user_agent`,`ip_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


UPDATE views SET view_date = NOW(), views_count = views_count + 1
WHERE ip_address = 2130706433 AND page_url = 'http://127.0.0.1/for-tests/index1.html'
AND user_agent = 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36'
AND ip_version = 4;

------------- hash key ----------------------------
CREATE TABLE `views` (
  `ip_address` bigint(20) NOT NULL,
  `view_date` datetime NOT NULL,
  `page_url` varchar(500) NOT NULL COMMENT 'w3c limit is 2000',
  `views_count` int(11) NOT NULL DEFAULT '0',
  `ip_version` enum('4','6') NOT NULL,
  `user_agent` varchar(500) NOT NULL,
  `view_hash` char(40) NOT NULL COMMENT 'sha1',
  PRIMARY KEY (`view_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# SET HASH
UPDATE views v1 SET v1.view_hash = SHA1(CONCAT(v1.ip_address,v1.page_url,v1.user_agent,v1.ip_version));

UPDATE views SET view_date = NOW(), views_count = views_count + 1 WHERE view_hash = '9191cb7f0d9cc46955214b2eff23ed7f27d62e04';


------------- test queries ---------

DROP PROCEDURE IF EXISTS generate_data;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_data`()
BEGIN
  DECLARE i INT DEFAULT 4000000;
  WHILE i < 10000000 DO
    INSERT INTO `views` (`ip_address`,`view_date`, page_url, user_agent) VALUES (2130706433, NOW(), CONCAT('http://127.0.0.1/for-tests/index222.html',i), CONCAT('some-agent',i));
    SET i = i + 1;
  END WHILE;
END$$
DELIMITER ;



#0 insert
SET AUTOCOMMIT = 0;
START TRANSACTION;
CALL generate_data();
COMMIT;

#1
SELECT count(*) FROM views;

#2
UPDATE views SET
view_date = NOW(),
views_count = views_count + 1
WHERE ip_address = 2130706433
AND page_url = 'http://127.0.0.1/for-tests/index1.html'
AND user_agent = 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36'
AND ip_version = 4;
