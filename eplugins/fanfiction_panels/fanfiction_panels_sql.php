CREATE TABLE `fanfiction_panels` (
`panel_id` int NOT NULL AUTO_INCREMENT,
`panel_name` varchar(50) NOT NULL DEFAULT 'unknown',
`panel_title` varchar(100) NOT NULL DEFAULT 'Unnamed Panel',
`panel_url` varchar(100) DEFAULT NULL,
`panel_level` tinyint NOT NULL DEFAULT '3',
`panel_order` tinyint NOT NULL DEFAULT '0',
`panel_hidden` tinyint(1) NOT NULL DEFAULT '0',
`panel_type` varchar(20) NOT NULL DEFAULT 'A',
PRIMARY KEY (`panel_id`),
KEY `panel_type` (`panel_type`,`panel_name`)
) ENGINE=InnoDB;

 