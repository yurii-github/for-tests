CREATE TABLE `views` (
  `ip_address` bigint(20) NOT NULL,
  `view_date` datetime NOT NULL,
  `page_url` varchar(500) NOT NULL COMMENT 'w3c limit is 2000',
  `views_count` int(11) NOT NULL DEFAULT '0',
  `ip_version` enum('4','6') NOT NULL,
  `user_agent` varchar(500) NOT NULL DEFAULT 'unknown',
  PRIMARY KEY (`ip_address`,`page_url`,`user_agent`,`ip_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
