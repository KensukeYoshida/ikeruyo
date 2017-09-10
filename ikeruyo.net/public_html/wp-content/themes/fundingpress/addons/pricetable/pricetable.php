<?php
define('PRICETABLE_FEATURED_WEIGHT', 1.175);
define('PRICETABLE_VERSION', '0.2.2');
/**
 * Add custom columns to pricetable post list in the admin
 * @param $cols
 * @return array
 */
function siteorigin_pricetable_register_custom_columns($cols){
	unset($cols['title']);
	unset($cols['date']);
	$cols['title'] = esc_html__('Title', 'fundingpress');
	$cols['options'] = esc_html__('Options', 'fundingpress');
	$cols['features'] = esc_html__('Features', 'fundingpress');
	$cols['featured'] = esc_html__('Featured Option', 'fundingpress');
	$cols['date'] = esc_html__('Date', 'fundingpress');
	return $cols;
}
add_filter( 'manage_pricetable_posts_columns', 'siteorigin_pricetable_register_custom_columns');
/**
 * Render the contents of the admin columns
 * @param $column_name
 */
function siteorigin_pricetable_custom_column($column_name){
	global $post;
	switch($column_name){
	case 'options' :
		$table = get_post_meta($post->ID, 'price_table', true);
		print count($table);
		break;
	case 'features' :
	case 'featured' :
		$table = get_post_meta($post->ID, 'price_table', true);
		foreach($table as $col){
		if(!empty($col['featured']) && $col['featured'] == 'true'){
			if($column_name == 'featured') print $col['title'];
			else print count($col['features']);
			break;
		}
		}
		break;
	}
}
add_action( 'manage_pricetable_posts_custom_column', 'siteorigin_pricetable_custom_column');
/**
 * @return string The URL of the CSS file to use
 */
function siteorigin_pricetable_css_url(){
	// Find the best price table file to use
	if(file_exists(get_stylesheet_directory().'/addons/pricetable/css/pricetable.css')) return get_stylesheet_directory_uri().'/addons/pricetable/css/pricetable.css';
	elseif(file_exists(get_template_directory().'/addons/pricetable/css/pricetable.css')) return get_template_directory_uri().'/addons/pricetable/css/pricetable.css';
	else return get_template_directory_uri().'/addons/pricetable/css/pricetable.css';
}
/**
 * Enqueue the pricetable scripts
 */
function siteorigin_pricetable_scripts(){
	global $post, $pricetable_queued, $pricetable_displayed;
	if(is_singular() && (($post->post_type == 'pricetable') || ($post->post_type != 'pricetable' && preg_match( '#\[ *price_table([^\]])*\]#i', $post->post_content ))) || !empty($pricetable_displayed)){
		wp_enqueue_style('pricetable',  siteorigin_pricetable_css_url(), null, PRICETABLE_VERSION);
		$pricetable_queued = true;
	}
}
add_action('wp_enqueue_scripts', 'siteorigin_pricetable_scripts');
/**
 * Add administration scripts
 * @param $page
 */
function siteorigin_pricetable_admin_scripts($page){
	if($page == 'post-new.php' || $page == 'post.php'){
		global $post;
		if(!empty($post) && $post->post_type == 'pricetable'){
			// Scripts for building the pricetable
			wp_enqueue_script('placeholder', get_template_directory_uri().'/addons/pricetable/js/placeholder.jquery.js', array('jquery'), '1.1.1', true);
			wp_enqueue_script('jquery-ui');
			wp_enqueue_script('pricetable-admin',get_template_directory_uri().'/addons/pricetable/js/pricetable.build.js', array('jquery'), PRICETABLE_VERSION, true);
			wp_localize_script('pricetable-admin', 'pt_messages', array(
				'delete_column' => esc_html__('Are you sure you want to delete this column?', 'fundingpress'),
				'delete_feature' => esc_html__('Are you sure you want to delete this feature?', 'fundingpress'),
			));
			wp_enqueue_style('pricetable-admin',  get_template_directory_uri().'/addons/pricetable/css/pricetable.admin.css', array(), PRICETABLE_VERSION);
			wp_enqueue_style('pricetable-icon', get_template_directory_uri().'/addons/pricetable/css/pricetable.icon.css', array(), PRICETABLE_VERSION);

		}
	}
	// The light weight CSS for changing the icon
	if(isset($_GET['post_type'])){
	if(@$_GET['post_type'] == 'pricetable'){
		wp_enqueue_style('pricetable-icon',  get_template_directory_uri().'/addons/pricetable/css/pricetable.icon.css', array(), PRICETABLE_VERSION);
	}
	}
}
add_action('admin_enqueue_scripts', 'siteorigin_pricetable_admin_scripts');
/**
 * Metaboxes because we're boss
 *
 * @action add_meta_boxes
 */
function siteorigin_pricetable_meta_boxes(){
	add_meta_box('pricetable', esc_html__('Price Table', 'fundingpress'), 'siteorigin_pricetable_render_metabox', 'pricetable', 'normal', 'high');
	add_meta_box('pricetable-shortcode', esc_html__('Shortcode', 'fundingpress'), 'siteorigin_pricetable_render_metabox_shortcode', 'pricetable', 'side', 'low');
}
add_action( 'add_meta_boxes', 'siteorigin_pricetable_meta_boxes' );
/**
 * Render the price table building interface
 *
 * @param $post
 * @param $metabox
 */
function siteorigin_pricetable_render_metabox($post, $metabox){
	wp_nonce_field( plugin_basename( __FILE__ ), 'siteorigin_pricetable_nonce' );
	$table = get_post_meta($post->ID, 'price_table', true);
	if(empty($table)) $table = array();
	include(dirname(__FILE__).'/tpl/pricetable.build.phtml');
}
/**
 * Render the shortcode metabox
 * @param $post
 * @param $metabox
 */
function siteorigin_pricetable_render_metabox_shortcode($post, $metabox){
	?>
		<code>[price_table id=<?php print $post->ID ?>]</code>
		<small class="description"><?php esc_html_e('Displays price table on another page.', 'fundingpress') ?></small>
	<?php
}
/**
 * Save the price table
 * @param $post_id
 * @return
 *
 * @action save_post
 */
function siteorigin_pricetable_save($post_id){
	// Authorization, verification this is my vocation
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if(isset($_POST['siteorigin_pricetable_nonce'])){
		if ( !wp_verify_nonce( @$_POST['siteorigin_pricetable_nonce'], plugin_basename( __FILE__ ) ) ) return;
	}
	if ( !current_user_can( 'edit_post', $post_id ) ) return;
	// Create the price table from the post variables
	$table = array();
	foreach($_POST as $name => $val){
		if(substr($name,0,6) == 'price_'){
			$parts = explode('_', $name);
			$i = intval($parts[1]);
			if(@$parts[2] == 'feature'){
				// Adding a feature
				$fi = intval($parts[3]);
				$fn = $parts[4];
				if(empty($table[$i]['features'])) $table[$i]['features'] = array();
				$table[$i]['features'][$fi][$fn] = $val;
			}
			elseif(isset($parts[2])){
				// Adding a field
				$table[$i][$parts[2]] = $val;
			}
		}
	}
	// Clean up the features
	foreach($table as $i => $col){
		if(empty($col['features'])) continue;
		foreach($col['features'] as $fi => $feature){
			if(empty($feature['title']) && empty($feature['sub'])  && empty($feature['icon']) && empty($feature['description'])){
				unset($table[$i]['features'][$fi]);
			}
		}
		$table[$i]['features'] = array_values($table[$i]['features']);
	}
	if(isset($_POST['price_recommend'])){
		$table[intval($_POST['price_recommend'])]['featured'] = 'true';
	}
	$table = array_values($table);
	update_post_meta($post_id,'price_table', $table);
}
add_action( 'save_post', 'siteorigin_pricetable_save' );

/**
 * Add the pricetable to the content.
 *
 * @param $the_content
 * @return string
 *
 * @filter the_content
 */
function siteorigin_pricetable_the_content_filter($the_content){
	global $post;
	if(is_single() && $post->post_type == 'pricetable' && empty($post->pricetable_inserted)){
		$the_content = siteorigin_pricetable_shortcode().$the_content;
	}
	return $the_content;
}
// Filter the content after WordPress has had a chance to do shortcodes (priority 10)
add_filter('the_content', 'siteorigin_pricetable_the_content_filter',11);
/**
 * @action wp_footer
 */
function siteorigin_pricetable_footer(){
	global $pricetable_queued, $pricetable_displayed;
	if(!empty($pricetable_displayed) && empty($pricetable_queued)){
		$pricetable_queued = true;
		// The pricetable has been rendered, but its CSS not enqueued (happened with some themes)
		?><link rel="stylesheet" type="text/css" href="<?php print siteorigin_pricetable_css_url() ?>" /><?php
	}
}
add_action('wp_footer', 'siteorigin_pricetable_footer');