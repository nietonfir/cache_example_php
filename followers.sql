CREATE TABLE IF NOT EXISTS `Followers` (
`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`url` varchar( 100 ) NOT NULL ,
`data` longtext NOT NULL ,
`followers` int( 5 ) NOT NULL ,
`last_update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;