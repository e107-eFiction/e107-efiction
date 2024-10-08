CREATE TABLE `fanfiction_pagelinks` (
`link_id` int NOT NULL AUTO_INCREMENT,
`link_name` varchar(50) NOT NULL DEFAULT '',
`link_text` varchar(100) NOT NULL DEFAULT '',
`link_url` varchar(250) NOT NULL DEFAULT '',
`link_target` tinyint NOT NULL DEFAULT '0',
`link_access` tinyint NOT NULL DEFAULT '0',
PRIMARY KEY (`link_id`),
KEY `link_name` (`link_name`)
) ENGINE=InnoDB;
