<?php

class ID_Affiliate_WP {
	
	function __construct() {
		self::set_filters();
	}

	function set_filters() {
		add_action('plugins_loaded', array($this, 'affiliate_wp_extend'));
	}

	function affiliate_wp_extend() {
		// load during wp hook to ensure Affiliate_WP_Base class is present
		require('class-affiliate_wp_extend.php');
		new ID_Affiliate_WP_Extend();
	}

}
new ID_Affiliate_WP();
?>