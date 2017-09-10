<?php
$wpdb->query("DROP TABLE IF EXISTS {$table_prefix}terms");
$wpdb->query("CREATE TABLE {$table_prefix}terms (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8");
$wpdb->query("
INSERT INTO `{$table_prefix}terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(1, 'Uncategorized', 'uncategorized', 0),
(2, 'simple', 'simple', 0),
(3, 'grouped', 'grouped', 0),
(4, 'variable', 'variable', 0),
(5, 'external', 'external', 0),
(8, 'Fantasy', 'fantasy', 0),
(9, 'Online', 'online', 0),
(10, 'RPG', 'rpg-1', 0),
(11, 'Shooter', 'shooter', 0),
(12, 'Strategy', 'strategy-1', 0),
(13, 'Strategy', 'strategy', 0),
(14, 'Classics', 'classics', 0),
(15, 'Shooters', 'shooters', 0),
(16, 'Racing', 'racing', 0),
(17, 'Adventure', 'adventure-1', 0),
(18, 'RPG', 'rpg', 0),
(19, 'Adventure', 'adventure', 0),
(20, 'Machine motion', 'machine-motion', 0),
(21, 'Photography', 'photography-1', 0),
(22, 'Prints 3D', 'prints-3d', 0),
(23, 'Comics', 'comics', 0),
(24, 'Art', 'art', 0),
(25, 'Film &amp; Video', 'film-video', 0),
(26, 'Games', 'games', 0),
(27, 'Music', 'music', 0),
(28, 'Photography', 'photography', 0),
(29, 'Technology', 'technology', 0),
(30, 'Menu 1', 'menu-1', 0)");
?>