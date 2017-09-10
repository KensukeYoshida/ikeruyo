<?php
$wpdb->query("DROP TABLE IF EXISTS {$table_prefix}users");
$wpdb->query("CREATE TABLE IF NOT EXISTS `{$table_prefix}users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `paypal_email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;");
$wpdb->query("
INSERT INTO `{$table_prefix}users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`, `paypal_email`)
VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'aaaaa@gmdsaail.com', '', '2016-08-31 16:40:24', '', 0, 'admin', NULL),
(4, 'KayC', '21232f297a57a52a743894a0e4a801fc3', 'kayc', 'asdasda@mlnfjk.com', '', '2016-09-02 09:56:07', '', 0, 'KayC', NULL),
(5, 'Bennet44', '21232f297a57a35a743894a0e4a801fc3', 'bennet44', 'rben@asdas.com', '', '2016-09-03 15:15:26', '', 0, 'Rolland Bennet', NULL),
(6, 'Ace', '21232f297a57a5a743894ea0e4a801fc3', 'ace', 'asddaag@sadas.com', '', '2016-09-05 09:39:55', '', 0, 'Ace Judd', ''),
(7, 'SerLang', '21232f297a57a5a743w894a0e4a801fc3', 'serlang', 'asdasdgh@hgfhgf.com', '', '2016-09-05 13:28:42', '', 0, 'Serge Langlois', NULL),
(8, 'eric', '21232f297a57a5a743894as0e4a801fc3', 'eric', 'arikrock@grwemail.com', '', '2016-09-07 08:10:57', '', 0, 'eric', NULL)
");
?>