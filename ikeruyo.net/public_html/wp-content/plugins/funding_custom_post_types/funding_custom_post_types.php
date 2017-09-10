<?php
/**
* Plugin Name: Fundingpress types
* Plugin URI: http://skywarriorthemes.com/
* Description: Custom post types for Fundingpress theme.
* Version: 1.3
* Author: Skywarrior themes
* Author URI: http://www.skywarriorthemes.com/
* License: GPL
*/



class Fundingpress_Types {

    function __construct() {
        register_activation_hook( __FILE__,array( $this,'activate' ) );
        add_action( 'init', array( $this, 'fundingpress_create_post_types' ), 1 );
    }

    function activate() {
        $this->fundingpress_create_post_types();
		$this->funding_add_pp();
    }

	/***** add paypal_email to database *****/
	function funding_add_pp(){
	global $wpdb;
	$wpdb->query("ALTER IGNORE TABLE `".$wpdb->prefix."users` ADD `paypal_email` VARCHAR(100)");
	}

    function fundingpress_create_post_types() {

	register_post_type('pricetable',array(
		'labels' => array(
			'name' => esc_html__('Price Tables', 'fundingpress'),
			'singular_name' => esc_html__('Price Table', 'fundingpress'),
			'add_new' => esc_html__('Add New', 'fundingpress'),
			'add_new_item' => esc_html__('Add New Price Table', 'fundingpress'),
			'edit_item' => esc_html__('Edit Price Table', 'fundingpress'),
			'new_item' => esc_html__('New Price Table', 'fundingpress'),
			'all_items' => esc_html__('All Price Tables', 'fundingpress'),
			'view_item' => esc_html__('View Price Table', 'fundingpress'),
			'search_items' => esc_html__('Search Price Tables', 'fundingpress'),
			'not_found' =>  esc_html__('No Price Tables found', 'fundingpress'),
		),
		'public' => true,
		'capability_type' => 'page',
		'has_archive' => false,
		'supports' => array( 'title'),
		'menu_icon' => plugin_dir_url(__FILE__).'img/price_tables.png',
	));

	/**
 * The price table shortcode.
 * @param array $atts
 * @return string
 *
 *
 */
function siteorigin_pricetable_shortcode($atts = array()) {
	global $post, $pricetable_displayed;
	$pricetable_displayed = true;
	extract( shortcode_atts( array(
		'id' => null,
		'width' => 100,
	), $atts ) );
	if($id == null) $id = $post->ID;
	$table = get_post_meta($id , 'price_table', true);
	if(empty($table)) $table = array();
	// Set all the classes
	$featured_index = null;
	foreach($table as $i => $column) {
		$table[$i]['classes'] = array('pricetable-column');
		$table[$i]['classes'][] = (@$table[$i]['featured'] === 'true') ? 'pricetable-featured' : 'pricetable-standard';
		if(@$table[$i]['featured'] == 'true') $featured_index = $i;
		if(@$table[$i+1]['featured'] == 'true') $table[$i]['classes'][] = 'pricetable-before-featured';
		if(@$table[$i-1]['featured'] == 'true') $table[$i]['classes'][] = 'pricetable-after-featured';
	}
	$table[0]['classes'][] = 'pricetable-first';
	$table[count($table)-1]['classes'][] = 'pricetable-last';
	// Calculate the widths
	$width_total = 0;
	foreach($table as $i => $column){
		if(@$column['featured'] === 'true') $width_total += PRICETABLE_FEATURED_WEIGHT;
		else $width_total++;
	}
	$width_sum = 0;
	foreach($table as $i => $column){
		if(@$column['featured'] === 'true'){
			// The featured column takes any width left over after assigning to the normal columns
			$table[$i]['width'] = 100 - (floor(100/$width_total) * ($width_total-PRICETABLE_FEATURED_WEIGHT));
		}
		else{
			$table[$i]['width'] = floor(100/$width_total);
		}
		$width_sum += $table[$i]['width'];
	}
	// Create fillers
	if(!empty($table[0]['features'])){
		for($i = 0; $i < count($table[0]['features']); $i++){
			$has_title = false;
			$has_sub = false;
			$has_icon = false;
			foreach($table as $column){
				$has_title = ($has_title || !empty($column['features'][$i]['title']));
				$has_sub = ($has_sub || !empty($column['features'][$i]['sub']));
				$has_icon = ($has_icon || !empty($column['features'][$i]['icon']));
			}
			foreach($table as $j => $column){
				if($has_title && empty($table[$j]['features'][$i]['title'])) $table[$j]['features'][$i]['title'] = '&nbsp;';
				if($has_sub && empty($table[$j]['features'][$i]['sub'])) $table[$j]['features'][$i]['sub'] = '&nbsp;';
				if($has_icon && empty($table[$j]['features'][$i]['icon'])) $table[$j]['features'][$i]['icon'] = '&nbsp;';
			}
		}
	}
	// Find the best pricetable file to use
		$template = get_template_directory().'/addons/pricetable/tpl/pricetable.phtml';
		// Render the pricetable
		ob_start();
		include($template);
		$pricetable = ob_get_clean();
		if($width != 100) $pricetable = '<div style="width:'.$width.'%; margin: 0 auto;">'.$pricetable.'</div>';
		$post->pricetable_inserted = true;
		return $pricetable;
	}
	add_shortcode( 'price_table', 'siteorigin_pricetable_shortcode' );

	register_post_type( 'optionsframework', array(
			'labels' => array(
				'name' => esc_html__( 'Options Framework Internal Container' , 'fundingpress'),
			),
			'public' => true,
			'show_ui' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'supports' => array( 'title', 'editor' ),
			'query_var' => false,
			'can_export' => true,
			'show_in_nav_menus' => false
		) );


		$args = array(
		'label'               => esc_html__( 'comments_holder', 'fundingpress' ),
		'description'         => esc_html__( 'Holds comments', 'fundingpress' ),
		'supports'            => array( 'title', 'comments', ),
		'taxonomies'          => array( 'comments_holder' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => false,
		'show_in_menu'        => false,
		'menu_position'       => 5,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'comments_holder', $args );


	register_post_type('project',array(
            'label' => esc_html__('Projects', 'fundingpress'),
            'taxonomies' => array('project-category', 'fundit_project'),
            'labels' => array(
                'name' => esc_html__('Projects', 'fundingpress'),
                'singular_name' => esc_html__('Project', 'fundingpress'),
                'add_new' => esc_html__('Create Project', 'fundingpress'),
                'edit_item' => esc_html__('Edit Project', 'fundingpress'),
                'add_new_item' => esc_html__('Add New Project', 'fundingpress'),
                'edit_item' => esc_html__('Edit Project', 'fundingpress'),
                'new_item' => esc_html__('New Project', 'fundingpress'),
                'view_item' => esc_html__('View Project', 'fundingpress'),
                'search_items' => esc_html__('Search Projects', 'fundingpress'),
                'not_found' => esc_html__('No Projects Found', 'fundingpress'),
            ),
            'description' => esc_html__('A fundable project.', 'fundingpress'),
            'public' => true,
            '_builtin' =>  false,
            'supports' => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments',
                'revisions',
            ),
            'rewrite' => true,
            'query_var' => 'project',
            'menu_icon' => get_template_directory_uri().'/funding/admin/images/project.png',
        ));


        register_taxonomy(
            'project-category',
            'project',
            array(
                'label' => esc_html__( 'Categories', 'fundingpress' ),
                'rewrite' => array( 'slug' => 'categories' ),
                'hierarchical' => true,
            )
        );

        register_taxonomy_for_object_type('tag', 'project');
        // Create reward custom post type
        register_post_type('reward',array(
            'label' => esc_html__('Reward', 'fundingpress'),
            'description' => esc_html__('A reward for funding a project.', 'fundingpress'),
            'public' => false,
        ));
        // Create funder custom post type
        register_post_type('funder',array(
            'label' => esc_html__('Funder', 'fundingpress'),
            'description' => esc_html__('Funder of a project', 'fundingpress'),
            'public' => false,
        ));



}


}
$Fundingpress_Types = new Fundingpress_Types();

?>