<?php
$wpdb->query("DROP TABLE IF EXISTS {$table_prefix}term_taxonomy");
$wpdb->query("CREATE TABLE {$table_prefix}term_taxonomy (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8");
$wpdb->query("
INSERT INTO `{$table_prefix}term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(1, 1, 'category', '', 0, 0),
(2, 2, 'product_type', '', 0, 0),
(3, 3, 'product_type', '', 0, 0),
(4, 4, 'product_type', '', 0, 0),
(5, 5, 'product_type', '', 0, 0),
(10, 10, 'post_tag', '', 0, 9),
(8, 8, 'post_tag', '', 0, 7),
(9, 9, 'post_tag', '', 0, 7),
(11, 11, 'post_tag', '', 0, 6),
(12, 12, 'post_tag', '', 0, 6),
(13, 13, 'category', '', 0, 4),
(14, 14, 'post_tag', '', 0, 4),
(15, 15, 'category', '', 0, 3),
(16, 16, 'category', '', 0, 5),
(17, 17, 'post_tag', '', 0, 3),
(18, 18, 'category', '', 0, 3),
(19, 19, 'category', '', 0, 1),
(20, 20, 'post_tag', '', 0, 0),
(21, 21, 'post_tag', '', 0, 0),
(22, 22, 'post_tag', '', 0, 0),
(23, 23, 'project-category', '', 0, 2),
(24, 24, 'project-category', '', 0, 4),
(25, 25, 'project-category', '', 0, 4),
(26, 26, 'project-category', '', 0, 3),
(27, 27, 'project-category', '', 0, 4),
(28, 28, 'project-category', '', 0, 5),
(29, 29, 'project-category', '', 0, 5),
(30, 30, 'nav_menu', '', 0, 12)");
?>