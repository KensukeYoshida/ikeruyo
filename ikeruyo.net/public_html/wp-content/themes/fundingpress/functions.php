<?php
/***** start session *****/

ob_start();

/***** translatable theme *****/
load_theme_textdomain( 'fundingpress', get_template_directory() . '/langs');


/***** include files *****/
require_once (get_template_directory() . '/themeOptions/functions.php');
require_once (get_template_directory() . '/themeOptions/rating.php');
require_once (get_template_directory() . '/theme-functions/page-templates.php' );
require_once (get_template_directory() . '/addons/google-fonts/google-fonts.php');
require_once (get_template_directory() . '/addons/pricetable/pricetable.php');
require_once (get_template_directory() . '/addons/smartmetabox/SmartMetaBox.php');
require_once (get_template_directory() . '/funding/funding.php');
require_once (get_template_directory() . '/widgets/latest_twitter/latest_twitter_widget.php');
require_once (get_template_directory() . '/pluginactivation.php');
require_once (get_template_directory() . '/post_templates.php');
require_once (get_template_directory() . '/widgets/rating/popular-widget.php');
require_once (get_template_directory() . '/widgets/projectcategory/projectcat-widget.php');

require_once (get_template_directory() . '/vc.php');
require_once ( ABSPATH . 'wp-admin/includes/plugin.php');


add_action( 'after_setup_theme', 'funding_theme_setup' );
function funding_theme_setup() {

	/*****THEME-SUPPORTED FEATURES*****/

	/*****ACTIONS*****/

	/*init*/
	add_action( 'init', 'fundingpress_post_templates_plugin_init' );
	add_action('init', 'funding_allowed_tags');
	add_action('init', 'funding_do_output_buffer');
	add_action( 'init', 'funding_register_my_menus' );

	if ( current_user_can('contributor') && !current_user_can('upload_files') )
	add_action('admin_init', 'funding_allow_contributor_uploads');

	/*admin-init*/
	add_action( 'admin_init', 'funding_update_caps');
	add_action( 'admin_init', 'funding_restrict_admin_area_to_contributors');

	/*project columns*/
	add_action( 'manage_project_posts_custom_column', 'funding_my_manage_project_columns', 10, 2 );

	/*theme scripts*/
	add_action('wp_enqueue_scripts', 'funding_my_scripts');
	add_action('wp_enqueue_scripts', 'funding_my_style' );
	add_action('admin_enqueue_scripts', 'funding_admin_scripts');
	add_action('admin_enqueue_scripts', 'funding_styles_admin');


	/*ajax functions*/
	add_action( 'wp_ajax_nopriv_funding_capture_card', 'funding_capture_card' );
	add_action( 'wp_ajax_funding_capture_card', 'funding_capture_card' );
	add_action( 'wp_ajax_nopriv_funding_unlink_stripe', 'funding_unlink_stripe' );
	add_action( 'wp_ajax_funding_unlink_stripe', 'funding_unlink_stripe' );
	add_action( 'wp_ajax_nopriv_load-filter-all', 'funding_prefix_load_cat_posts_all' );
	add_action( 'wp_ajax_load-filter-all', 'funding_prefix_load_cat_posts_all' );
	add_action( 'wp_ajax_nopriv_funding_delete_projects', 'funding_delete_projects' );
	add_action( 'wp_ajax_funding_delete_projects', 'funding_delete_projects' );
	add_action( 'wp_ajax_nopriv_funding_unlink_wepay', 'funding_unlink_wepay' );
	add_action( 'wp_ajax_funding_unlink_wepay', 'funding_unlink_wepay' );
	add_action( 'wp_ajax_nopriv_funding_get_wepay_token', 'funding_get_wepay_token' );
	add_action( 'wp_ajax_funding_get_wepay_token', 'funding_get_wepay_token' );
	add_action( 'wp_ajax_nopriv_delcomments', 'funding_delete_comments' );
	add_action( 'wp_ajax_delcomments', 'funding_delete_comments' );
	add_action( 'wp_ajax_nopriv_load-filter', 'funding_prefix_load_cat_posts' );
	add_action( 'wp_ajax_load-filter', 'funding_prefix_load_cat_posts' );
	add_action( 'wp_ajax_nopriv_return_currency', 'funding_return_currency' );
	add_action( 'wp_ajax_return_currency', 'funding_return_currency' );
	add_action( 'wp_ajax_nopriv_ajaxlogin', 'funding_ajax_login' );
	add_action( 'wp_ajax_ajaxlogin', 'funding_ajax_login' );
	add_action( 'wp_ajax_nopriv_charge_funder', 'funding_frontend_charge_funder' );
	add_action( 'wp_ajax_charge_funder', 'funding_frontend_charge_funder' );

	/*sessions*/
	add_action('init', 'funding_myStartSession', 1);
	add_action('wp_logout', 'funding_myEndSession');
	add_action('wp_login', 'funding_myEndSession');

	/*vc*/
	add_action( 'vc_before_init', 'funding_vc_remove_be_pointers' );
	add_action( 'vc_before_init', 'funding_vc_remove_fe_pointers' );

	/*date var*/
	add_action( 'admin_head', 'funding_dateformat_var' );

	/*required plugins*/
	add_action( 'tgmpa_register', 'funding_register_required_plugins' );

	/*restrict  media*/
	add_action('pre_get_posts','funding_restrict_media_library');

	/*meta boxes*/
	add_action( 'add_meta_boxes', 'funding_add_projects_custom_box' );

	/*admin class*/
	add_action('admin_body_class', 'funding_adminclass');

	/*menu*/
	add_action( 'admin_menu', 'fundingpress_create_menu' );

	/*notifications*/
	add_action( 'transition_post_status', 'funding_notify_user_on_publish', 10, 3 );


	/*****FILTERS*****/


	/***** add custom columns for projects in back end *****/
	add_filter( 'manage_edit-project_columns', 'funding_my_edit_project_columns' ) ;

	/*category pagination*/
	add_filter('request', 'funding_fix_category_pagination');

	/*comments*/
	add_filter('comment_form_defaults', 'funding_comment_form_defaults');
	add_filter( 'comment_text', 'funding_oembed_comments', 0 );

	/*avatars*/
 	add_filter( 'get_avatar', 'funding_be_gravatar_filter', 1, 5 );

	/*content*/
	add_filter("the_content", "funding_the_content_filter");

	/*meta filters*/
	add_filter( 'postmeta_form_limit', 'funding_hide_meta_start' );

	/*excerpt*/
	add_filter( 'excerpt_length', 'funding_custom_excerpt_length', 999 );
	add_filter('excerpt_more', 'funding_excerpt_more');


 /*add custom menu support*/
    add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-background' );

}


/*Post templates*/
function fundingpress_post_templates_plugin_init() {
    new fundingpress_Single_Post_Template_Plugin;
}



/***** get id by slug function *****/
function funding_get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);

    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

/***** add my-projects page *****/
if(funding_get_ID_by_slug('all-projects') == ""){
//create my-projects page
$post = array(
	'post_name'     => 'all-projects',
	'post_status'   => 'publish',
	'post_title'    =>  esc_html__('All projects', 'fundingpress'),
	'post_type'     => 'page',
	'page_template' => 'tmp-all-projects.php',
	'post_author'   => 1
);
wp_insert_post( $post );}

/***** add blog page *****/
if(funding_get_ID_by_slug('blog') == ""){
//create my-projects page
$post = array(
	'post_name'     => 'blog',
	'post_status'   => 'publish',
	'post_title'    =>  esc_html__('Blog', 'fundingpress'),
	'post_type'     => 'page',
	'page_template' => 'tmp-blog-right.php',
	'post_author'   => 1
);
wp_insert_post( $post );}
/**** add video meta box for posts *****/
add_smart_meta_box('my-meta-box77', array(
'title' => esc_html__('Video url', 'fundingpress'), // the title of the meta box
'pages' => array('post'),
'context' => 'normal', // meta box context (see above)
'priority' => 'high', // meta box priority (see above)
'fields' => array( // array describing our fields
array(
'name' => esc_html__('Put your embed video URL here', 'fundingpress'),
'id' => 'video',
'type' => 'textarea',
),)));


function funding_allow_contributor_uploads() {
    $role = get_role('contributor');
    $role->add_cap('upload_files');
    $role->add_cap('delete_others_posts');
    $role->add_cap('delete_posts');
    $role->add_cap('delete_published_posts');
    }

function funding_my_edit_project_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => esc_html__( "Project Title", 'fundingpress' ),
        "status" => esc_html__( 'Project status', 'fundingpress' ),
        "funding-progress" => esc_html__( "Progress", 'fundingpress' ),
        "funding-time" => esc_html__( "Time Remaining", 'fundingpress' ),
        "ppal" => esc_html__( "Paypal", 'fundingpress' ),
        "wepay" => esc_html__( "Wepay", 'fundingpress' ),
        "author" => esc_html__( "Creator", 'fundingpress' ),
        "comments" => '<img src="'.site_url().'/wp-admin/images/comment-grey-bubble.png" alt="Comments" />',
        'date' => esc_html__( 'Date', 'fundingpress' ),
    );
    return $columns;
}


function funding_my_manage_project_columns( $column, $post_id ) {
    global $post;
    switch( $column ) {
        /* If displaying the 'duration' column. */
        case 'funding-time' :
            $project_settings = (array) get_post_meta($post_id, 'settings', true);
			if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

			if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

			if(isset($project_settings['target']))
            $target = $project_settings['target'];
            	if (isset($project_settings['date']) && strpos( $project_settings['date'] , "/") !== false) {
			  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
						}else{
							if(isset($project_settings['date'])){
								$parseddate = $project_settings['date'];
							}else{
								$parseddate = '';
							}
						}
            $project_expired = strtotime($parseddate) < time();
            $funded_amount = 0;
            $rewards = get_children(array('post_parent' => $post -> ID, 'post_type' => 'reward', 'order' => 'ASC', 'orderby' => 'meta_value_num', 'meta_key' => 'funding_amount', ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach ($rewards as $this_reward) {
                $these_funders = get_children(array('post_parent' => $this_reward -> ID, 'post_type' => 'funder', 'post_status' => 'publish'));
                foreach ($these_funders as $this_funder) {
                    $funding_amount = get_post_meta($this_funder -> ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }

			                    if(!$project_expired) : ?>
			                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?> <strong> <?php esc_html_e('< 24', 'fundingpress'); ?></strong> <?php }else{ ?>
			                        <strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong>
			                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
			                         <?php esc_html_e('hours to go', 'fundingpress'); ?>
			                        <?php }else{ ?>
			                        	<?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
			                        		 <?php esc_html_e('day to go', 'fundingpress'); ?>
			                        	<?php }else{ ?>
			                        		 <?php esc_html_e('days to go', 'fundingpress'); ?>
			                        	<?php } ?>


			                        <?php } ?>
			                    <?php endif;
            break;
        /* If displaying the 'genre' column. */
        case 'status' :
            $project_settings = (array) get_post_meta($post_id, 'settings', true);

			if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

			if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}


			if(isset($project_settings['target']))
            $target = $project_settings['target'];
            	if (isset($project_settings['date']) && strpos( $project_settings['date'] , "/") !== false) {
			  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
						}else{
							if(isset($project_settings['date'])){
							$parseddate = $project_settings['date'];
							}else{
								$parseddate = '';
							}
						}
            $project_expired = strtotime($parseddate) < time();
            $funded_amount = 0;
            $rewards = get_children(array('post_parent' => $post -> ID, 'post_type' => 'reward', 'order' => 'ASC', 'orderby' => 'meta_value_num', 'meta_key' => 'funding_amount', ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach ($rewards as $this_reward) {
                $these_funders = get_children(array('post_parent' => $this_reward -> ID, 'post_type' => 'funder', 'post_status' => 'publish'));
                foreach ($these_funders as $this_funder) {
                    $funding_amount = get_post_meta($this_funder -> ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }
            if(get_post_status( $post_id ) == 'pending') { global $a; $a =1; ?>
            <strong><?php esc_html_e('Pending!', 'fundingpress') ?></strong>
             <?php }elseif(get_post_status( $post_id ) == 'draft'){ global $a; $a =2;?>
            <strong><?php esc_html_e('Draft!', 'fundingpress') ?></strong>
            <?php }elseif( $funded_amount > $target or $funded_amount == $target){  global $a; $a =3;?>
            <strong><?php esc_html_e('Successful!', 'fundingpress') ?></strong>
            <?php }elseif($project_expired){  global $a; $a =4;?>
            <strong><?php esc_html_e('Unsuccessful!', 'fundingpress') ?></strong>
            <?php }else{ global $a; $a =5;?>
            <strong><?php esc_html_e('Active!', 'fundingpress') ?></strong>
            <?php }
            break;
            case 'ppal':
            $usr = get_userdata(get_the_author_meta( 'ID' )); echo esc_attr($usr->paypal_email);
            break;
            case 'wepay':
            echo esc_attr(get_the_author_meta('wepay_account_id', get_the_author_meta( 'ID' )));
            break;
            case 'funding-progress':
            $project_settings = (array) get_post_meta($post_id, 'settings', true);

				if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}


				if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
					$project_settings['date'] = implode('/', $array);
				}
			}

			if(isset($project_settings['target']))
            $target = $project_settings['target'];

			if(!isset($f_currency_signs[$project_settings['currency']]))$f_currency_signs[$project_settings['currency']] = '';

			if(isset($project_settings['currency']))
            $project_currency_sign = $f_currency_signs[$project_settings['currency']];
            	if (isset($project_settings['date']) && strpos( $project_settings['date'] , "/") !== false) {
			  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
						}else{
							if(isset($project_settings['date'])){
								$parseddate = $project_settings['date'];
							}else{
								$parseddate = '';
							}
						}
            $project_expired = strtotime($parseddate) < time();
            $funded_amount = 0;
            $rewards = get_children(array('post_parent' => $post -> ID, 'post_type' => 'reward', 'order' => 'ASC', 'orderby' => 'meta_value_num', 'meta_key' => 'funding_amount', ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach ($rewards as $this_reward) {
                $these_funders = get_children(array('post_parent' => $this_reward -> ID, 'post_type' => 'funder', 'post_status' => 'publish'));
                foreach ($these_funders as $this_funder) {
                    $funding_amount = get_post_meta($this_funder -> ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }
            if($funded_amount == 0)
            {echo '0%';}else{
            printf('%u%', round($funded_amount/$target*100), $project_currency_sign, round($target));echo '%';}
            break;
        /* Just break out of the switch statement for everything else. */
        default :
            break;
    }
}


/***** add custom columns for projects in back end *****/

function funding_fix_category_pagination($qs){
    if(isset($qs['category_name']) && isset($qs['paged'])){
        $qs['post_type'] = get_post_types($args = array(
            'public'   => true,
            '_builtin' => false
        ));
        array_push($qs['post_type'],'post');
    }
    return $qs;
}



/***** theme scripts *****/
function funding_my_scripts(){
    wp_enqueue_script( 'bootstrap1', get_template_directory_uri().'/js/bootstrap.js','','',true);
    wp_enqueue_script( 'bootstrap2', get_template_directory_uri().'/js/bootstrap-tooltip.js','','',true);
    wp_enqueue_script( 'bootstrap3', get_template_directory_uri().'/js/bootstrap-tab.js','','',true);
    wp_enqueue_script( 'tiny_js', get_template_directory_uri().'/js/jquery.carouFredSel-6.1.0.js');
    wp_enqueue_script( 'easing',  get_template_directory_uri().'/js/easing.js','','',true);
	wp_enqueue_script( 'totop',  get_template_directory_uri().'/js/jquery.ui.totop.min.js','','',true);
    wp_enqueue_script( 'imagescale',   get_template_directory_uri().'/js/imagescale.js','','',true);

	wp_enqueue_script( 'custom_js2',  get_template_directory_uri().'/js/login-with-ajax.js','','',true);
	wp_localize_script( 'custom_js2', 'ajax_login_object', array(
			    'ajaxurl' => admin_url( 'admin-ajax.php' ),
			    'redirecturl' => get_site_url() ,
			    'loadingmessage' => esc_html__('Signing in, please wait...', 'fundingpress')
			));


    wp_enqueue_script( 'custom_js4',  get_template_directory_uri().'/js/jquery.validate.min.js','','',true);
	wp_enqueue_script( 'custom_js5',  get_template_directory_uri().'/js/picker.js','','',true);
    wp_enqueue_script( 'custom_js6',   get_template_directory_uri().'/js/jquery-ui-1.10.2.custom.js','','',true);
    wp_enqueue_script( 'custom_js11',    get_template_directory_uri().'/js/isotope.js','','',true);
    wp_enqueue_script( 'custom_js99',   get_template_directory_uri().'/js/global.js','','',true);
    wp_enqueue_script( 'fball', '//connect.facebook.net/en_US/all.js','','',true);
    $siteurl = get_site_url(); //set location to the auth folder script
    if (substr($siteurl, -1) != "/") {
        $siteurl .= "/";
    }  //add trailing slash
    $baseurl = get_template_directory_uri().'/include/';
    $settings = array('authlocation' => $baseurl, 'ajax' => admin_url( 'admin-ajax.php' )); //pass wp settings we need to array
    wp_enqueue_script( 'social_js',   get_template_directory_uri().'/js/social.js','','',true);
    wp_localize_script('social_js', 'settings', $settings); //pass any php settings to javascript

//tmp-my-account.php
    if(strpos(get_page_template(), "tmp-my-account.php") !== false){
        wp_enqueue_script( 'custom_ajaxupload1',   get_template_directory_uri().'/js/jquery.iframe-transport.js','','',true);
        wp_enqueue_script( 'custom_ajaxupload2',   get_template_directory_uri().'/js/jquery.fileupload.js','','',true);
        wp_enqueue_script( 'custom_ajaxupload3',   get_template_directory_uri().'/js/jquery.fileupload-ui.js','','',true);
        wp_enqueue_script( 'custom_ajaxupload4',   get_template_directory_uri().'/js/jquery.fileupload-process.js','','',true);
        wp_enqueue_script( 'custom_jcrop',   get_template_directory_uri().'/js/jquery.Jcrop.min.js','','',true);
        wp_enqueue_style( 'custom-styleHcrop',  get_template_directory_uri().'/css/jquery.Jcrop.css',  array(), '20130401');
        wp_enqueue_script( 'campaign_script',   get_template_directory_uri().'/js/addphoto.js','','',true);
            $settingsCustom = array('dateformat' => of_get_option('datef'), 'NowDay' => date('d'), 'NowMonth' => date('m'), 'NowYear' => date('Y'),
            'uploadfileformat' => esc_html__('Only JPG, JPEG and PNG files are allowed', 'fundingpress'),
            'uploadphotoscu' => esc_html__('Photo Is Uploading...', 'fundingpress'),
            'uploaderror' => esc_html__('Error!', 'fundingpress'),
            'pleasefinishcrop' => esc_html__('Please finish cropping the other image first!', 'fundingpress'),
            'urlcheckbad' => esc_html__('URL is not valid!', 'fundingpress'),
            'urlnumbercheck' => esc_html__('Valid number!', 'fundingpress'),
            'urlnumberrange' => esc_html__('Number must be in 1-500 range!', 'fundingpress'),
            'urlnumberinputcheck' => esc_html__('Input must be a number in 1-500 range!', 'fundingpress')
            ); //pass wp settings we need to array
            $upload_dir = wp_upload_dir();
            $settings1 = array('wp_upload_dir_path' => $upload_dir['path']."/", 'wp_upload_dir_url' => $upload_dir['url']."/"); //pass wp settings we need to array
            wp_localize_script('campaign_script', 'uploadsettings', $settings1); //pass any php settings to javascript
            wp_localize_script('campaign_script', 'settingsCustom', $settingsCustom); //pass any php settings to javascript
        wp_enqueue_script( 'custom_ui3',  get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.min.js','','',true);
    }

    if(strpos(get_page_template(), "tmp-submit-project.php") !== false){
        wp_enqueue_script( 'custom_ajaxupload1',   get_template_directory_uri().'/js/jquery.iframe-transport.js','','',true);
        wp_enqueue_script( 'custom_ajaxupload2',   get_template_directory_uri().'/js/jquery.fileupload.js','','',true);
        wp_enqueue_script( 'custom_ajaxupload3',   get_template_directory_uri().'/js/jquery.fileupload-ui.js','','',true);
        wp_enqueue_script( 'custom_ajaxupload4',   get_template_directory_uri().'/js/jquery.fileupload-process.js','','',true);
        wp_enqueue_script( 'custom_jcrop',   get_template_directory_uri().'/js/jquery.Jcrop.min.js','','',true);
        wp_enqueue_style( 'custom-styleHcrop',  get_template_directory_uri().'/css/jquery.Jcrop.css',  array(), '20130401');
        wp_enqueue_script( 'submit_project_js',   get_template_directory_uri().'/js/submitproject.js','','',true);
            $settingsCustom = array('dateformat' => of_get_option('datef'), 'NowDay' => date('d'), 'NowMonth' => date('m'), 'NowYear' => date('Y'),
            'uploadfileformat' => esc_html__('Only JPG, JPEG and PNG files are allowed', 'fundingpress'),
            'uploadphotoscu' => esc_html__('Photo Is Uploading...', 'fundingpress'),
            'uploaderror' => esc_html__('Error!', 'fundingpress'),
            'pleasefinishcrop' => esc_html__('Please finish cropping the other image first!', 'fundingpress'),
            'urlcheckbad' => esc_html__('URL is not valid!', 'fundingpress'),
            'urlnumbercheck' => esc_html__('Valid number!', 'fundingpress'),
            'urlnumberrange' => esc_html__('Number must be in 1-500 range!', 'fundingpress'),
            'urlnumberinputcheck' => esc_html__('Input must be a number in 1-500 range!', 'fundingpress'),
            'minimumammount' => esc_html__('Minimum Amount', 'fundingpress'),
            'available' => esc_html__('Number Available', 'fundingpress'),
            'rewtitle' => esc_html__('Reward title', 'fundingpress'),
            'rewdesc' => esc_html__('Reward description', 'fundingpress'),
            'remove' => esc_html__('REMOVE ME', 'fundingpress'),
            ); //pass wp settings we need to array
            $upload_dir = wp_upload_dir();
            $settings1 = array('wp_upload_dir_path' => $upload_dir['path']."/", 'wp_upload_dir_url' => $upload_dir['url']."/"); //pass wp settings we need to array
            wp_localize_script('submit_project_js', 'uploadsettings', $settings1); //pass any php settings to javascript
            wp_localize_script('submit_project_js', 'settingsCustom', $settingsCustom); //pass any php settings to javascript
            wp_enqueue_script('submit_project_js');
        wp_enqueue_script( 'custom_ui3',  get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.min.js','','',true);
   }
}


/***** admin scripts *****/
function funding_admin_scripts(){
wp_enqueue_script( 'custom22',   get_template_directory_uri().'/js/admin.js','','',true);
}


/***** theme styles *****/
function funding_my_style() {
  wp_enqueue_style( 'mytheme-style',  get_bloginfo( 'stylesheet_url' ), array(), '20150401' );

     if ( is_rtl() )
    {
        wp_enqueue_style('funding-rtl',  get_template_directory_uri() . '/css/rtl.css', array(), '20150401');
    }


	wp_enqueue_style('font-awesome',  get_template_directory_uri() . '/css/font-awesome.css', array(), '20160930');
	wp_enqueue_style('font-awesome-min',  get_template_directory_uri() . '/css/font-awesome.min.css', array(), '20160930');
	wp_enqueue_style('ui-totop',  get_template_directory_uri() . '/css/ui.totop.css', array(), '20160930');
}

function funding_fonts() {
    $protocol = is_ssl() ? 'https' : 'http';
    wp_enqueue_style( 'funding-opens', "$protocol://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" );
}


/***** admin styles *****/
function funding_styles_admin(){
  wp_enqueue_style( 'custom-style11',  get_template_directory_uri().'/css/font-awesome.css',  array(), '20130401');
  wp_enqueue_style( 'custom-style22',  get_template_directory_uri().'/css/gf-style.css',  array(), '20130401');
  wp_enqueue_style( 'custom-style33',  get_template_directory_uri().'/css/jquery-ui.min.css',  array(), '20130401');
}


/***** add sidebars *****/
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => esc_html__( 'Footer area widgets', 'fundingpress' ),
'id' => 'one',
'description' => esc_html__( 'Widgets in this area will be shown in the footer.' , 'fundingpress'),
'before_widget' => '<div class="footer_widget col-lg-4">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>', ));
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => esc_html__( 'Blog sidebar', 'fundingpress' ),
'id' => 'blog',
'description' => esc_html__( 'Widgets in this area will be shown in the blog sidebar.' , 'fundingpress'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>', ));



if(is_plugin_active('funding_custom_post_types/funding_custom_post_types.php')){
	/***** When this theme is activated send the user to the theme options *****/
	if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	// Call action that sets
	add_action('admin_head','gp_setup');
	// Do redirect
	header( 'Location: '.admin_url().'themes.php?page=options-framework' ) ;
	}
}

/***** Register menus *****/
function funding_register_my_menus() {
  register_nav_menus(
    array(
      'header-menu' => esc_html__( 'Header Menu' , 'fundingpress'),
      )
  );
}

/***** add Theme option menu in admin *****/
function fundingpress_create_menu(){
$themeicon1 = get_template_directory_uri()."/img/favicon.png";
add_menu_page("Theme Options", "Theme Options", 'edit_theme_options', 'options-framework', 'optionsframework_page',$themeicon1,1800 );
}


/***** fix post counts *****/
function funding_fix_post_counts($views) {
    global $current_user, $wp_query;
    unset($views['mine']);
    $types = array(
        array( 'status' =>  NULL ),
        array( 'status' => 'publish' ),
        array( 'status' => 'draft' ),
        array( 'status' => 'pending' ),
        array( 'status' => 'trash' )
    );
    foreach( $types as $type ) {
        $query = array(
            'author'      => $current_user->ID,
            'post_type'   => 'post',
            'post_status' => $type['status']
        );
        $result = new WP_Query($query);
        if( $type['status'] == NULL ):
            $class = ($wp_query->query_vars['post_status'] == NULL) ? ' class="current"' : '';
            $views['all'] = sprintf('<a href="%s"'. esc_attr($class) .' >'.esc_html__("All","fundingpress").' <span class="count">(%d)</span></a>',
                admin_url('edit.php?post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'publish' ):
            $class = ($wp_query->query_vars['post_status'] == 'publish') ? ' class="current"' : '';
            $views['publish'] = sprintf('<a href="%s"'. esc_attr($class) .' >'.esc_html__("Published", "fundingpress").' <span class="count">(%d)</span></a>',
                admin_url('edit.php?post_status=publish&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'draft' ):
            $class = ($wp_query->query_vars['post_status'] == 'draft') ? ' class="current"' : '';
            $views['draft'] = sprintf('<a href="%s"'. esc_attr($class) .' >'.esc_html__("Draft","fundingpress"). ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>',
                admin_url('edit.php?post_status=draft&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'pending' ):
            $class = ($wp_query->query_vars['post_status'] == 'pending') ? ' class="current"' : '';
            $views['pending'] = sprintf('<a href="%s"'. esc_attr($class) .' >'.esc_html__("Pending", "fundingpress").' <span class="count">(%d)</span></a>',
                admin_url('edit.php?post_status=pending&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'trash' ):
            $class = ($wp_query->query_vars['post_status'] == 'trash') ? ' class="current"' : '';
            $views['trash'] = sprintf('<a href="%s"'. esc_attr($class) .' >'.esc_html__("Trash", "fundingpress").' <span class="count">(%d)</span></a>',
                admin_url('edit.php?post_status=trash&post_type=post'),
                $result->found_posts);
        endif;
    }
    return $views;
}


/***** fix media counts *****/
function funding_fix_media_counts($views) {
    global $wpdb, $current_user, $post_mime_types, $avail_post_mime_types;
    $views = array();
    $count = $wpdb->get_results($wpdb->prepare( "
        SELECT post_mime_type, COUNT( * ) AS num_posts
        FROM $wpdb->posts
        WHERE post_type = 'attachment'
        AND post_author = %s
        AND post_status != 'trash'
        GROUP BY post_mime_type
    ",$current_user->ID ),ARRAY_A );
    foreach( $count as $row )
        $_num_posts[$row['post_mime_type']] = $row['num_posts'];
    $_total_posts = array_sum($_num_posts);
    $detached = isset( $_REQUEST['detached'] ) || isset( $_REQUEST['find_detached'] );
    if ( !isset( $total_orphans ) )
        $total_orphans = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT( * )
            FROM $wpdb->posts
            WHERE post_type = 'attachment'
            AND post_author = %s
            AND post_status != 'trash'
            AND post_parent < 1
        ",$current_user->ID ));
    $matches = wp_match_mime_types(array_keys($post_mime_types), array_keys($_num_posts));
    foreach ( $matches as $type => $reals )
        foreach ( $reals as $real )
            $num_posts[$type] = ( isset( $num_posts[$type] ) ) ? $num_posts[$type] + $_num_posts[$real] : $_num_posts[$real];
    $class = ( empty($_GET['post_mime_type']) && !$detached && !isset($_GET['status']) ) ? ' class="current"' : '';
    $views['all'] = "<a href='upload.php'$class>" . sprintf( esc_html__('All <span class="count">(%s)</span>', 'fundingpress' ), number_format_i18n( $_total_posts )) . '</a>';
    foreach ( $post_mime_types as $mime_type => $label ) {
        $class = '';
        if ( !wp_match_mime_types($mime_type, $avail_post_mime_types) )
            continue;
        if ( !empty($_GET['post_mime_type']) && wp_match_mime_types($mime_type, $_GET['post_mime_type']) )
            $class = ' class="current"';
        if ( !empty( $num_posts[$mime_type] ) )
            $views[$mime_type] = "<a href='upload.php?post_mime_type=$mime_type'$class>" . sprintf( translate_nooped_plural( $label[2], $num_posts[$mime_type] ), $num_posts[$mime_type] ) . '</a>';
    }
    $views['detached'] = '<a href="upload.php?detached=1"' . ( $detached ? ' class="current"' : '' ) . '>' . sprintf( esc_html__( 'Unattached <span class="count">(%s)</span>', 'fundingpress' ), $total_orphans ) . '</a>';
    return $views;
}

/***** login with ajax *****/
class funding_LoginWithAjax {
    /**
     * If logged in upon instantiation, it is a user object.
     * @var WP_User
     */
    var $current_user;
    /**
     * List of templates available in the plugin dir and theme (populated in init())
     * @var array
     */
    var $templates = array();
    /**
     * Name of selected template (if selected)
     * @var string
     */
    var $template;
    /**
     * lwa_data option
     * @var array
     */
    var $data;
    /**
     * Location of footer file if one is found when generating a widget, for use in loading template footers.
     * @var string
     */
    var $footer_loc;
    /**
     * URL for the AJAX Login procedure in templates (including callback and template parameters)
     * @var string
     */
    var $url_login;
    /**
     * URL for the AJAX Remember Password procedure in templates (including callback and template parameters)
     * @var string
     */
    var $url_remember;
    /**
     * URL for the AJAX Registration procedure in templates (including callback and template parameters)
     * @var string
     */
    var $url_register;
    // Class initialization
    function __construct() {
        //Set when to run the plugin
        add_action('widgets_init', array(&$this, 'init'));
    }
    // Actions to take upon initial action hook
    function init() {
        //Load LWA options
        $this -> data = get_option('lwa_data');
        //Remember the current user, in case there is a logout
        $this -> current_user = wp_get_current_user();
        //Generate URLs for login, remember, and register
        $this -> url_login = $this -> template_link(site_url('wp-login.php', 'login_post'));
        $this -> url_register = $this -> template_link(site_url('wp-login.php?action=register', 'login_post'));
        $this -> url_remember = $this -> template_link(site_url('wp-login.php?action=lostpassword', 'login_post'));
        //Make decision on what to display
        if (isset($_REQUEST["login-with-ajax"])) {//AJAX Request
            $this -> ajax();
        } elseif (isset($_REQUEST["login-with-ajax-widget"])) {//Widget Request via AJAX
            $instance = (!empty($_REQUEST["template"])) ? array('template' => $_REQUEST["template"]) : array();
            $instance['is_widget'] = false;
            $instance['profile_link'] = (!empty($_REQUEST["lwa_profile_link"])) ? $_REQUEST['lwa_profile_link'] : 0;
            $this -> widget(array(), $instance);
            exit();
        }
    }
    /*
     * LOGIN OPERATIONS
     */
    // Decides what action to take from the ajax request
    function ajax() {
        switch ( $_REQUEST["login-with-ajax"] ) {
            case 'login' :
                //A login has been requested
                $return = $this -> json_encode($this -> login());
                break;
            case 'register' :
                //A login has been requested
                $return = $this -> json_encode($this -> register());
                break;
            case 'remember' :
                //Remember the password
                $return = $this -> json_encode($this -> remember());
                break;
            default :
                //Don't know
                $return = $this -> json_encode(array('result' => 0, 'error' => 'Unknown command requested'));
                break;
        }
        echo  $return;
        exit();
    }
    // Reads ajax login creds via POSt, calls the login script and interprets the result
    function login() {
        $return = array();
        //What we send back
        if (!empty($_REQUEST['log']) && !empty($_REQUEST['pwd']) && trim($_REQUEST['log']) != '' && trim($_REQUEST['pwd'] != '')) {
            $loginResult = wp_signon();
            $user_role = 'null';
            if (strtolower(get_class($loginResult)) == 'wp_user') {
                //User login successful
                $this -> current_user = $loginResult;
                /* @var $loginResult WP_User */
                $return['result'] = true;
                $return['message'] = esc_html__("Login Successful, redirecting...", 'fundingpress');
                //Do a redirect if necessary
                $redirect = $this -> getLoginRedirect($this -> current_user);
                if ($redirect != '') {
                    $return['redirect'] = $redirect;
                }
                //If the widget should just update with ajax, then supply the URL here.
                if (!empty($this -> data['no_login_refresh']) && $this -> data['no_login_refresh'] == 1) {
                    //Is this coming from a template?
                    $query_vars = ($_GET['template'] != '') ? "&template={$_GET['template']}" : '';
                    $query_vars .= ($_REQUEST['lwa_profile_link'] == '1') ? "&lwa_profile_link=1" : '';
                    $return['widget'] =  site_url(). "?login-with-ajax-widget=1$query_vars";
                    $return['message'] = esc_html__("Login successful, updating...", 'fundingpress');
                }
            } elseif (strtolower(get_class($loginResult)) == 'wp_error') {
                //User login failed
                /* @var WP_Error $loginResult */
                $return['result'] = false;
                $return['error'] = $loginResult -> get_error_message();
            } else {
                //Undefined Error
                $return['result'] = false;
                $return['error'] = esc_html__('An undefined error has ocurred', 'fundingpress');
            }
        } else {
            $return['result'] = false;
            $return['error'] = esc_html__('Please supply your username and password.', 'fundingpress');
        }
        //Return the result array with errors etc.
        return $return;
    }
    /**
     * Checks post data and registers user
     * @return string
     */
    function register() {
        if (!empty($_REQUEST['lwa'])) {
            $return = array();
            if ('POST' == $_SERVER['REQUEST_METHOD']) {
                require_once (ABSPATH . WPINC . '/registration.php');
                $errors = register_new_user($_POST['user_login'], $_POST['user_email']);
                if (!is_wp_error($errors)) {
                    //Success
                    $return['result'] = true;
                    $return['message'] = esc_html__('Registration complete. Please check your e-mail.', 'fundingpress');
                } else {
                    //Something's wrong
                    $return['result'] = false;
                    $return['error'] = $errors -> get_error_message();
                }
            }
            echo $this -> json_encode($return);
            exit();
        }
    }
    // Reads ajax login creds via POSt, calls the login script and interprets the result
    function remember() {
        $return = array();
        //What we send back
        $result = retrieve_password();
        if ($result === true) {
            //Password correctly remembered
            $return['result'] = true;
            $return['message'] = esc_html__("We have sent you an email", 'fundingpress');
        } elseif (strtolower(get_class($result)) == 'wp_error') {
            //Something went wrong
            /* @var $result WP_Error */
            $return['result'] = false;
            $return['error'] = $result -> get_error_message();
        } else {
            //Undefined Error
            $return['result'] = false;
            $return['error'] = esc_html__('An undefined error has ocurred', 'fundingpress');
        }
        //Return the result array with errors etc.
        return $return;
    }
    /*
     * Redirect Functions
     */
    function logoutRedirect() {
        $redirect = $this -> getLogoutRedirect();
        if ($redirect != '') {
            wp_redirect($redirect);
            exit();
        }
    }
    function getLogoutRedirect() {
        $data = $this -> data;
        if (!empty($data['logout_redirect'])) {
            $redirect = $data['logout_redirect'];
        }
        if (strtolower(get_class($this -> current_user)) == "wp_user") {
            //Do a redirect if necessary
            $data = $this -> data;
            $user_role = array_shift($this -> current_user -> roles);
            //Checking for role-based redirects
            if (!empty($data["role_logout"]) && is_array($data["role_logout"]) && isset($data["role_logout"][$user_role])) {
                $redirect = $data["role_logout"][$user_role];
            }
        }
        $redirect = str_replace("%LASTURL%", $_SERVER['HTTP_REFERER'], $redirect);
        return $redirect;
    }
    function loginRedirect($redirect, $redirect_notsurewhatthisis, $user) {
        $data = $this -> data;
        if (is_user_logged_in()) {
            $lwa_redirect = $this -> getLoginRedirect($user);
            if ($lwa_redirect != '') {
                wp_redirect($lwa_redirect);
                exit();
            }
        }
        return $redirect;
    }
    function getLoginRedirect($user) {
        $data = $this -> data;
        if ($data['login_redirect'] != '') {
            $redirect = $data["login_redirect"];
        }
        if (strtolower(get_class($user)) == "wp_user") {
            $user_role = array_shift($user -> roles);
            //Checking for role-based redirects
            if (isset($data["role_login"][$user_role])) {
                $redirect = $data["role_login"][$user_role];
            }
        }
        //Do string replacements
        $redirect = str_replace('%USERNAME%', $user -> user_login, $redirect);
        $redirect = str_replace("%LASTURL%", $_SERVER['HTTP_REFERER'], $redirect);
        return $redirect;
    }
    /*
     * Auxillary Functions
     */
    //Checks a directory for folders and populates the template file
    function find_templates($dir) {
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (is_dir($dir . $file) && $file != '.' && $file != '..' && $file != '.svn') {
                        //Template dir found, add it to the template array
                        $this -> templates[$file] = path_join($dir, $file);
                    }
                }
                closedir($dh);
            }
        }
    }
    //Add template link and JSON callback var to the URL
    function template_link($content) {
        if (strstr($content, '?')) {
            $content .= '&amp;callback=?&amp;template=' . $this -> template;
        } else {
            $content .= '?callback=?&amp;template=' . $this -> template;
        }
        return $content;
    }
    //PHP4 Safe JSON encoding
    function json_encode($array) {
        if (!function_exists("json_encode")) {
            $return = json_encode($array);
        } else {
            $return = $this -> array_to_json($array);
        }
        if (isset($_REQUEST['callback']) && preg_match("/^jQuery[_a-zA-Z0-9]+$/", $_REQUEST['callback'])) {
            $return = $_GET['callback'] . "($return)";
        }
        return $return;
    }
    //PHP4 Compatible json encoder function
    function array_to_json($array) {
        //PHP4 Comapatability - This encodes the array into JSON. Thanks go to Andy - http://www.php.net/manual/en/function.json-encode.php#89908
        if (!is_array($array)) {
            return false;
        }
        $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));
        if ($associative) {
            $construct = array();
            foreach ($array as $key => $value) {
                // We first copy each key/value pair into a staging array,
                // formatting each key and value properly as we go.
                // Format the key:
                if (is_numeric($key)) {
                    $key = "key_$key";
                }
                $key = "'" . addslashes($key) . "'";
                // Format the value:
                if (is_array($value)) {
                    $value = $this -> array_to_json($value);
                } else if (is_bool($value)) {
                    $value = ($value) ? "true" : "false";
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "'" . addslashes($value) . "'";
                }
                // Add to staging array:
                $construct[] = "$key: $value";
            }
            // Then we collapse the staging array into the JSON form:
            $result = "{ " . implode(", ", $construct) . " }";
        } else {// If the array is a vector (not associative):
            $construct = array();
            foreach ($array as $value) {
                // Format the value:
                if (is_array($value)) {
                    $value = $this -> array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "'" . addslashes($value) . "'";
                }
                // Add to staging array:
                $construct[] = $value;
            }
            // Then we collapse the staging array into the JSON form:
            $result = "[ " . implode(", ", $construct) . " ]";
        }
        return $result;
    }
}//Template Tag

// Start plugin
global $LoginWithAjax;
$LoginWithAjax = new funding_LoginWithAjax();


/* Breadcrumbs */
function funding_breadcrumbs_inner(){


        function funding_get_page_id($name){
        global $wpdb;
        /* get page id using custom query */
        $page_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ( post_name = %s or post_title = %s ) and post_status = 'publish' and post_type='page' ", $name));
        return $page_id;
        }
        function funding_get_page_permalink($name){
        $page_id = funding_get_page_id($name);
        return get_permalink($page_id);
        }

        if (!is_home()) {
        echo '<a href="';
        echo esc_url(home_url());
        echo '">';
        echo esc_html__('Home', 'fundingpress');
        echo "</a> / ";
        if(get_post_type() == 'project'){
        echo '<a href="';
        echo funding_get_permalink_for_template( 'tmp-all-projects' );
        echo '">';
        echo esc_html__('Projects', 'fundingpress');
        echo "</a> ";
            if (is_single()) {
                echo " / ";
                the_title();
            }elseif(is_tax()){
                 echo " / ";
                echo str_replace('Categories', '', wp_title('',false,'left')) ;
            }elseif(is_search()){
                 echo " / ";
        echo esc_html__('Search: ', 'fundingpress');
        echo get_search_query();
         }
        }elseif (is_single()) {
        if(get_post_type( get_the_ID() ) == 'project'){
        echo esc_html__('Project', 'fundingpress');
            if (is_single()) {
                echo " / ";
                the_title();
            }
        }else{
        echo '<a href="';
        echo funding_get_permalink_for_template( 'tmp-blog-right' );
        echo '">';
        echo esc_html__('Blog', 'fundingpress');
        echo "</a> ";
            if (is_single()) {
                echo " / ";
                the_title();
            }
        }
        }elseif(is_category()){
        echo  esc_html__('Category: ', 'fundingpress');
        echo esc_attr(single_cat_title());
        }elseif(is_404()){
        echo '404';
        }elseif(is_search()){
        echo esc_html__('Search: ', 'fundingpress');
        echo esc_attr(get_search_query());
         }elseif(is_author()){
        $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author')); echo esc_attr($curauth->user_nicename);
        } elseif (is_page()) {
            echo esc_attr(the_title());
        }elseif(is_tag()){
         echo   esc_html__('Tag: ', 'fundingpress');
             echo esc_attr(funding_GetTagName(get_query_var('tag_id')));
        }elseif( function_exists( 'is_shop' ) && is_shop() ){
        	 esc_html_e('Shop', 'fundingpress');
        }elseif( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
        	?><a href="<?php echo get_permalink(funding_get_ID_by_slug ('shop')); ?>"> <?php esc_html_e('Shop', 'fundingpress');?></a> <?php
        	   echo " / ";
                the_title();
        } elseif(is_tax()){
          echo   esc_html__('Category', 'fundingpress');
        }elseif(is_archive()){
          echo   esc_html__('Archive', 'fundingpress');
        }
    }
}

function funding_breadcrumbs(){

if(function_exists('is_bbpress')){
    if(is_bbpress()){
        bbp_breadcrumb();
    }else{
        funding_breadcrumbs_inner();}
}else{
        funding_breadcrumbs_inner();
  }
}


/*get tag name*/
function funding_GetTagName($meta){
    if (is_string($meta) || (is_numeric($meta) && !is_double($meta))
            || is_int($meta)){
                if (is_numeric($meta))
                    $meta = (int)$meta;
                        if (is_int($meta))
                            $TagSlug = get_term_by('id', $meta, 'post_tag');
                        else
                            $TagSlug = get_term_by('slug', $meta, 'post_tag');
                    return $TagSlug->name;
            }
}


/***** allow redirection, even if my theme starts to send output to the browser *****/

function funding_do_output_buffer() {
        ob_start();
}


/***** add admin body class *****/
function funding_adminclass(){
    $current_user= wp_get_current_user();
    $level = $current_user->user_level;
    if($level == 1){
      $classes = 'user_project';
      return $classes;
    }
}


/***** add country option to profile *****/
add_action('after_setup_theme', 'funding_country', 1 );
function funding_country() {
      include_once(TEMPLATEPATH.'/themeOptions/admin/country/usercountry.php');
}


/***** custom excerpt lenght *****/
function funding_custom_excerpt_length( $length ) {
    return 30;
}


/***** pagination *****/
function funding_kriesi_pagination($pages = '', $range = 1)
{
$showitems = ($range * 1)+1;
$general_show_page  = of_get_option('general_post_show');
global $paged;
global $paginate;
if(empty($paged)) $paged = 1;
if($pages == '')
{
global $wp_query;
$pages = $wp_query->max_num_pages;
if(!$pages)
{
$pages = 1;
}
}
if(1 != $pages)
{
$url= get_template_directory_uri();
$leftpager= '&laquo;';
$rightpager= '&raquo;';
if($paged > 2 && $paged > $range+1 && $showitems < $pages) $paginate.=  "";
if($paged > 1 ) $paginate.=  "<a class='page-selector' href='".get_pagenum_link($paged - 1)."'>". $leftpager. "</a>";
for ($i=1; $i <= $pages; $i++)
{
if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
{
$paginate.=  ($paged == $i)? "<li><a href='".get_pagenum_link($i)."'  class='active'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
}
}
if ($paged < $pages ) $paginate.=  "<li><a class='page-selector' href='".get_pagenum_link($paged + 1)."' >". $rightpager. "</a></li>";
}
return $paginate;
}


/***** add different image sizes *****/
if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'category-thumb', 320, 200, true );
    add_image_size( 'projects', 200, 150, true );
    add_image_size( 'medium-img', 200, 150, true );
}


function funding_add_projects_custom_box() {
add_meta_box('postcustom', esc_html__('Update Fields', 'fundingpress'), 'post_custom_meta_box', 'project', 'normal', 'core');
}


function funding_hide_meta_start( $num )
{
    add_filter( 'query', 'funding_hide_meta_filter' );
    return $num;
}


function funding_hide_meta_filter( $query )
{
    // Protect further queries.
    remove_filter( current_filter(), __FUNCTION__ );
    $forbidden = array ( 'aq_block_1' ,'aq_block_2','aq_block_3','aq_block_4','aq_block_5','aq_block_6','aq_block_7','aq_block_8','aq_block_9','aq_block_10','project_video_link','preapproval_key',
    'allorany', 'charged', 'date','datum','field_1', 'field_2','hide_on_screen','layout','my_meta_box_check','my_meta_box_select','my_meta_box_text', 'notified','paypal_email','position',
    'available' ,'funder','funding_amount','page-option-choose-left-sidebar','page-option-choose-right-sidebar', 'page-option-item-xml', 'page-option-show-content','rule',
    'page-option-sidebar-template', 'page-option-show-title', 'page-option-top-slider-height', 'page-option-top-slider-types', 'page-option-top-slider-xml', 'reward', 'settings');
    $where     = "WHERE meta_key NOT IN('" . join( "', '", $forbidden ) . "') ";
    $find      = "GROUP BY";
    $query     = str_replace( $find, "$where\n$find", $query );
  return $query;
}


function funding_custom_comments_post($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
    $GLOBALS['comment_depth'] = $depth;
	$allowed_tags = array(
	'span' => array(
		'class' => array()
		)
	);
  ?>
   <div class="project-comment row">
        <div class="comment-author cl-lg-1 vcard"><?php funding_commenter_avatar(); ?></div>
  <?php if ($comment->comment_approved == '0') wp_kses(_e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'fundingpress'), $allowed_tags ); ?>
          <div class="comment-content cl-lg-6">
             <div class="comment-info"> <?php funding_commenter_link() ?> <?php esc_html_e("on", 'fundingpress');?> <?php the_title(); ?> <?php comment_time('M j, Y @ G:i'); ?> </div>
            <div class="comment-content"> <?php comment_text() ?></div>
        </div>
</div>
<?php } // end custom_comments


function funding_custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
	   $allowed_tags = array(
		'span' => array(
			'class' => array()
			)
		);
        ?>
         <div class="project-comment row">
                <div class="comment-author"><?php printf(esc_html__('By %1$s on %2$s at %3$s', 'fundingpress'),
                        get_comment_author_link(),
                        get_comment_date(),
                        get_comment_time() );
                        edit_comment_link(esc_html__('Edit', 'fundingpress'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0') wp_kses(_e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'fundingpress'), $allowed_tags ); ?>
            <div class="comment-content cl-lg-6">
                <?php comment_text() ?>
            </div>
            </div>
<?php
} // end custom_pings


// Produces an avatar image with the hCard-compliant photo class
function funding_commenter_link() {
 $commenter = get_comment_author_link();
    if ( preg_match( '/<a[^>]* class=[^>]+>/', $commenter ) ) {
        $commenter = preg_replace( '/(<a[^>]* class=[\'"]?)/', '\\1url ' , $commenter );
    } else {
        $commenter = preg_replace( '/(<a )/', '\\1class="url "/' , $commenter );
    }
    echo ' <span class="comment-info">' . $commenter . '</span>';
}

function funding_commenter_avatar() {
	$allowed_tags = array(
	'img' => array(
		'class' => array(),
		'src' => array(),
	),
);
    $avatar_email = get_comment_author_email();
    $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 100 ) );
    echo wp_kses($avatar, $allowed_tags );
}



/***** add meta boxes *****/
add_smart_meta_box('my-meta-box', array(
'title' => esc_html__('Video url', 'fundingpress'), // the title of the meta box
'pages' => array('project'),  // post types on which you want the metabox to appear
'context' => 'normal', // meta box context (see above)
'priority' => 'high', // meta box priority (see above)
'fields' => array( // array describing our fields
array(
'name' => esc_html__('Put your project embed video URL here', 'fundingpress'),
'id' => 'video-link-field',
'type' => 'textarea',
),)));

//add staff checkbox
add_smart_meta_box('my-meta-box2', array(
'title' => esc_html__('Staff picks', 'fundingpress'), // the title of the meta box
'pages' => array('project'),  // post types on which you want the metabox to appear
'context' => 'side', // meta box context (see above)
'priority' => 'core', // meta box priority (see above)
'fields' => array( // array describing our fields
array(
'name' => esc_html__('Staff picks', 'fundingpress'),
'id' => 'staff-check-field',
'type' => 'checkbox',
),)));

/*add featured checbox*/
add_smart_meta_box('my-meta-box3', array(
'title' => esc_html__('Featured', 'fundingpress'), // the title of the meta box
'pages' => array('project'),  // post types on which you want the metabox to appear
'context' => 'side', // meta box context (see above)
'priority' => 'core', // meta box priority (see above)
'fields' => array( // array describing our fields
array(
'name' => esc_html__('Featured', 'fundingpress'),
'id' => 'featured',
'type' => 'checkbox',
),)));


/***** limit media to logged in user *****/

function funding_restrict_media_library( $wp_query_obj ) {
    global $current_user, $pagenow;
	$user = wp_get_current_user();

    if( !is_a( $current_user, 'WP_User') )
    return;
    if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
    return;

    if( in_array( "contributor", (array) $user->roles ))
    $wp_query_obj->set('author', $current_user->ID );
    return;
}



/*creates options for the hybridauth so they are flexible*/
function funding_social_createoptions() {
    $siteurl = get_site_url();
    $holdtheme = get_template_directory();
    $thetheme = explode("/", $holdtheme);
    $nametheme = $thetheme[count($thetheme) -1];
    if (substr($siteurl, -1) != "/") {
        $siteurl .= "/";
    }  //add trailing slash

    if ( !empty( $_SERVER['HTTPS'] ) ) {
            $siteurl = str_replace("http://", "https://", $siteurl);
        }

    $baseurl = content_url().'/themes/'.$nametheme.'/include/';
    if ( !empty( $_SERVER['HTTPS'] ) ) {
            $baseurl = str_replace("http://", "https://", $baseurl);
        }
    $redirecturl = content_url().'/themes/'.$nametheme.'/include/handler/index.php';
    //"keys"    => array ( "key" =>  of_get_option('linkedin_app'), "secret" =>  of_get_option('linkedin_secret'))
    //"keys"    => array ( "id" =>  of_get_option('vkontakte_app'), "secret" =>  of_get_option('vkontakte_secret'))
    //"keys"    => array ( "id" => of_get_option('google_app'), "secret" => of_get_option('google_secret') ),
    return
    array(
        "base_url" => $baseurl,
        "redirect_url" => $redirecturl,
        "providers" => array (
            // openid providers
            "OpenID" => array (
                "enabled" => false
            ),

            "AOL"  => array (
                "enabled" => false
            ),

            "Yahoo" => array (
                "enabled" => false,
                "keys"    => array ( "id" => "", "secret" => "" )
            ),


            "Facebook" => array (
                "enabled" => true,
                "keys"    => array ( "id" => of_get_option('facebook_app'), "secret" => of_get_option('facebook_secret') )
            ),

            "Twitter" => array (
                "enabled" => true,
                "keys"    => array ( "key" =>  of_get_option('twitter_app'), "secret" =>  of_get_option('twitter_secret'))
            ),

            // Google+ish
            "Google" => array (
                "enabled" => true,
                "keys"    => array ( "id" => of_get_option('google_app'), "secret" => of_get_option('google_secret') )
            ),

            "MySpace" => array (
                "enabled" => false,
                "keys"    => array ( "key" => "", "secret" => "" )
            ),

            "LinkedIn" => array (
                "enabled" => false,
                "keys"    => array ( "key" =>  of_get_option('linkedin_app'), "secret" =>   of_get_option('linkedin_secret'))
            ),

            "Tumblr" =>   array (
                       "enabled"   => false,
                       "keys"   => array ( "key" =>  "", "secret" =>  "" )
            ),
            "Foursquare" => array (
                "enabled" => false,
                "keys"    => array ( "id" => "", "secret" => "" )
            ),
        ),


        // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
        "debug_mode" => false,

        "debug_file" => ""
    );
}
function check_table() {

    global $wpdb;
    //check if theme needs updating
    $table_name = $wpdb->prefix . "access_tokens";
    $count = $wpdb->get_var('SELECT count(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table_name.'" AND COLUMN_NAME = "access_token" AND TABLE_SCHEMA="'.DB_NAME.'"');

    if ($count == 0) {
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
              `uid` int(11) NOT NULL AUTO_INCREMENT,
              `access_token` TEXT NULL DEFAULT NULL,
              `access_token_expires` int(11) DEFAULT NULL,
              `access_token_session` TEXT NULL DEFAULT NULL,
              `access_token_name` TEXT NULL DEFAULT NULL,
              `access_token_photo` TEXT NULL DEFAULT NULL,
              `access_token_id` TEXT NULL DEFAULT NULL,
              `access_token_provider` TEXT NULL DEFAULT NULL,
              `access_token_secret` TEXT NULL DEFAULT NULL,
              PRIMARY KEY (`uid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $wpdb->query($sql);
    }

}


/*handle sessions in wordpress*/

function funding_myStartSession() {
    global $wpdb; // hook the wp db
    if(!session_id()) { //if there's no session, start it
       @session_start();
    }
    if (!isset($_SESSION['auth_cfg'])) {
        //put config data for social auth in session variable, we do it once to reduce the site load on session construct
        $_SESSION['auth_cfg'] = funding_social_createoptions();
    }

    if (!isset($_SESSION['checkedtables'])) {
        check_table();
    }
    $table_name = $wpdb->prefix . "access_tokens";
    if (isset($_SESSION['social_login_new'])) {
        if (isset($_SESSION['social_user'])) {
            unset ($_SESSION['social_user']);
        }
        switch ($_SESSION['social_login_new']['provider']) {
                case "facebook":
                    $provider = "facebook";
                    break;
                case "twitter":
                    $provider = "twitter";
                    break;
                case "google":
                    $provider = "google";
                    break;
            }
        // provider and prefix set
        $numrows = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ". $table_name .' WHERE access_token_id = %s LIMIT 1', $_SESSION['social_login_new']['id']));
        $_SESSION['social_user']['name'] = $_SESSION['social_login_new']['name'];
		$_SESSION['social_user']['firstName'] = $_SESSION['social_login_new']['firstName'];
		$_SESSION['social_user']['lastName'] = $_SESSION['social_login_new']['lastName'];
        $_SESSION['social_user']['photo'] = $_SESSION['social_login_new']['photo'];
        $_SESSION['social_user']['provider'] = $_SESSION['social_login_new']['provider'];
        if (count($numrows) != 0) {
            $_SESSION['social_user']['uid'] = $numrows[0]->uid;
            $db = $wpdb->query($wpdb->prepare('UPDATE '.$table_name.' SET access_token = %s,
            access_token_session= %s,
            access_token_expires =  %s,
            access_token_name = %s,
            access_token_photo = %s,
            access_token_id = %s,
            access_token_provider  = %s
            WHERE uid = %s',
			esc_sql($_SESSION['social_login_new']['token']['access_token']),
			esc_sql($_SESSION['social_login_new']['sessiondata']),
			esc_sql($_SESSION['social_login_new']['token']['expires_at']),
			esc_sql($_SESSION['social_login_new']['name']),
			esc_sql($_SESSION['social_login_new']['photo']),
			esc_sql($_SESSION['social_login_new']['id']),
			esc_sql($_SESSION['social_login_new']['provider']),
			esc_sql($_SESSION['social_user']['uid'])
			)); //update db
            if (($_SESSION['social_login_new']['provider'] == "twitter") OR ($_SESSION['social_login_new']['provider'] == "tumblr") OR ($_SESSION['social_login_new']['provider'] == "google")) {
                $wpdb->query($wpdb->prepare('UPDATE '.$table_name.' SET
                access_token_secret= %s
                WHERE uid = %s',
				esc_sql($_SESSION['social_login_new']['token']['access_token_secret']),
				esc_sql($_SESSION['social_user']['uid'])));
            }
        } else {
            $wpdb->insert($table_name, array("access_token"=>$_SESSION['social_login_new']['token']['access_token'], "access_token_expires" => $_SESSION['social_login_new']['token']['expires_at'], 'access_token_name' => $_SESSION['social_login_new']['name'], 'access_token_photo'=> $_SESSION['social_login_new']['photo'], "access_token_id" => $_SESSION['social_login_new']['id'], "access_token_session" => esc_sql($_SESSION['social_login_new']['sessiondata']), "access_token_provider" => $_SESSION['social_login_new']['provider'])); //update db
            $_SESSION['social_user']['uid'] = $wpdb->insert_id;
            if (($provider == "twitter") OR ($provider == "tumblr") OR ($provider == "linkedin")) {
                $wpdb->query($wpdb->prepare('UPDATE '.$table_name.' SET '.$prefix.'_secret= %s WHERE uid = %s', esc_sql($_SESSION['social_login_new']['token']['access_token_secret']), esc_sql($_SESSION['social_user']['uid'])));
            }
        }
        funding_do_the_login($_SESSION['social_user']['uid'] );
        //WE LOGGED IN HERE
        $_SESSION['needtorefresh'] = true;
        unset($_SESSION['social_login_new']); //everything done, remove the flag

        $newuid = 10000 + $_SESSION['social_user']['uid'];
        $login = "socialuser"+ $_SESSION['social_user']['uid'];;
        $userobj = new WP_User();
        $user = $userobj->get_data_by( 'login', $login );
        $premium = get_user_meta( $user->ID, '_checkbox_premium_user', true);
        if ($premium == "yes") {
            $_SESSION['theuser']['premium'] = 1;
        } else {
            $_SESSION['theuser']['premium'] = 0;
        }

        $current_photo = get_user_meta( $user->ID, 'profile_pic', true);
        if (strlen($current_photo) < 5) {
            update_user_meta($user->ID, 'profile_pic', $_SESSION['social_user']['photo']);
        }

    }

    if (isset($_SESSION['loggedout'])) {
        unset($_SESSION['loggedout']);
        wp_logout();
        $_SESSION['needtorefresh'] = true;
    }

}


function funding_do_the_login($uid) {
    //special uids set
    $newuid = 10000 + $uid;
    $login = "socialuser"+$uid;
    // External user exists, try to load the user info from the WordPress user table
    $userobj = new WP_User();
    $user = $userobj->get_data_by( 'login', $login ); // Does not return a WP_User object <img src="http://ben.lobaugh.net/blog/wp-includes/images/smilies/icon_sad.gif" alt=":(" class="wp-smiley" />
    $user = new WP_User($user->ID); // Attempt to load up the user with that ID
    if( $user->ID == 0 ) {
         // The user does not currently exist in the WordPress user table.
         // You have arrived at a fork in the road, choose your destiny wisely

         // If you do not want to add new users to WordPress if they do not
         // already exist uncomment the following line and remove the user creation code
         //$user = new WP_Error( 'denied', esc_html__("<strong>ERROR</strong>: Not a valid user for this system") );

         // Setup the minimum required user information for this example
         $userdata = array( 'user_email' => $newuid."@randomsocialuser.rnd",
                            'user_login' => $login,
                            'nickname' => $_SESSION['social_user']['name'],
                            'first_name' =>$_SESSION['social_user']['firstName'],
                            'last_name' =>$_SESSION['social_user']['lastName'],
                            'display_name' => $_SESSION['social_user']['name'],
                            'rich_editing' => true,
                            'role' => 'contributor'
                            );
        $new_user_id = wp_insert_user( $userdata ); // A new user has been created

         // Load the new user info
        $user = new WP_User ($new_user_id);
        $user->add_cap( 'upload_files');
        $user->add_cap( 'read');
        $user->add_cap( 'edit_posts');
        $user->add_cap( 'edit_published_pages');
        $user->add_cap( 'edit_others_pages');
        $user->add_cap( 'level_0');
        $user->add_cap( 'level_1');
        $user->add_cap('delete_page');
        $premium = add_user_meta($user->ID , '_checkbox_premium_user', 'no');
    } else {
        $userdata = array(  'ID' => $user->ID,
                            'rich_editing' => true,
                            'role' => 'contributor'
                            );
        $role = get_role( 'contributor' ); // gets the author role
        $role->add_cap('delete_posts'); // delete own posts
        $role->add_cap('delete_project'); // delete own posts
        $new_user_id = wp_update_user($userdata);
        $user = new WP_User ($new_user_id);
    }

    wp_set_current_user( $user->ID, $user->user_login );
    wp_set_auth_cookie( $user->ID );


    return $user;
}

/*ends session*/
function funding_myEndSession() {
    session_destroy ();
}



/***** plugin installation suggestion *****/

function funding_register_required_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to include a plugin pre-packaged with a theme
        array(
            'name'                  => esc_html__('LayerSlider', 'fundingpress'), // The plugin name
            'slug'                  => 'LayerSlider', // The plugin slug (typically the folder name)
            'source'                => 'http://skywarriorthemes.com/plugins/layerslider.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => esc_html__('Visual composer', 'fundingpress'), // The plugin name
            'slug'                  => 'js_composer', // The plugin slug (typically the folder name)
            'source'                => get_template_directory_uri() .'/plugins/js_composer.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => esc_html__('Parallax backgrounds for VC', 'fundingpress'), // The plugin name
            'slug'                  => 'parallax-backgrounds-for-vc', // The plugin slug (typically the folder name)
            'source'                =>  get_template_directory_uri() .'/plugins/parallax-backgrounds-for-vc.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
         array(
            'name'                  => esc_html__('Easy WP SMTP', 'arcane'), // The plugin name
            'slug'                  => 'easy-wp-smtp', // The plugin slug (typically the folder name)
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
        ),
         array(
            'name'                  => esc_html__('Fundingpress custom post types', 'fundingpress'), // The plugin name
            'slug'                  => 'funding_custom_post_types', // The plugin slug (typically the folder name)
            'source'                => 'http://skywarriorthemes.com/plugins/funding_custom_post_types.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => esc_html__('Contact form 7', 'arcane'), // The plugin name
            'slug'                  => 'contact-form-7', // The plugin slug (typically the folder name)
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
        ),
		array(
			'name'                  => esc_html__('WordPress Social Login', 'arcane'), // The plugin name
            'slug'                  => 'wordpress-social-login', // The plugin slug (typically the folder name)
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
		),

    );
    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = 'fundingpress';
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'            => $theme_text_domain,          // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                          // Default absolute path to pre-packaged plugins
        'menu'              => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'is_automatic'      => true,                       // Automatically activate plugins after installation or not
        'message'           => '',                          // Message to output right before the plugins table
        'strings'           => array(
            'page_title'                                => esc_html__( 'Install Required Plugins', 'fundingpress' ),
            'menu_title'                                => esc_html__( 'Install Plugins', 'fundingpress' ),
            'installing'                                => esc_html__( 'Installing Plugin: %s', 'fundingpress' ), // %1$s = plugin name
            'oops'                                      => esc_html__( 'Something went wrong with the plugin API.', 'fundingpress' ),
            'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'fundingpress' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'fundingpress' ), // %1$s = plugin name(s)
            'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'fundingpress' ), // %1$s = plugin name(s)
            'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' , 'fundingpress'), // %1$s = plugin name(s)
            'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'fundingpress' ), // %1$s = plugin name(s)
            'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'fundingpress' ), // %1$s = plugin name(s)
            'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'fundingpress' ), // %1$s = plugin name(s)
            'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'fundingpress' ), // %1$s = plugin name(s)
            'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'fundingpress' ),
            'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'fundingpress' ),
            'return'                                    => esc_html__( 'Return to Required Plugins Installer', 'fundingpress' ),
            'plugin_activated'                          => esc_html__( 'Plugin activated successfully.', 'fundingpress' ),
            'complete'                                  => esc_html__( 'All plugins installed and activated successfully. %s', 'fundingpress' ), // %1$s = dashboard link
            'nag_type'                                  => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );
    tgmpa( $plugins, $config );
}



/***** Get post for ajax currency *****/

function funding_return_currency () {

        global $f_currency_signs;
        $cur_id = $_POST[ 'curr' ];
        echo esc_attr($f_currency_signs[$cur_id]);
        die(1);
}


/***** Get post for ajax category *****/
function funding_prefix_load_cat_posts () {

        global $post;
        $cat_id = $_POST[ 'cat' ];
		$display = $_POST[ 'display' ];
        $term = get_term( $cat_id, 'project-category' );
        $args = array (
            'showposts' => -1,
            'post_type' => 'project',
            'orderby' => 'post_date',
            'post_status' => 'publish',
              'tax_query' => array(
                    array(
                    'taxonomy' => 'project-category',
                    'field' => 'id',
                    'terms' => $term->term_id
                     )
                  ));
        $posts = get_posts($args);

        ob_start ();
if( $display == 1){ //////////////////////////latest

        foreach ( $posts as $post ) {
            global $post;
                global $f_currency_signs;
                $project_settings = (array) get_post_meta($post->ID, 'settings', true);

				if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

				if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

                	if (strpos( $project_settings['date'] , "/") !== false) {
			  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
						}else{
							$parseddate = $project_settings['date'];
						}
            	$project_expired = strtotime($parseddate) < time();
                $project_currency_sign = $f_currency_signs[$project_settings['currency']];
                $target= $project_settings['target'];
                $rewards = get_children(array(
                'post_parent' => $post->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                    'post_parent' => $this_reward->ID,
                    'post_type' => 'funder',
                    'post_status' => 'publish'
                ));
                foreach($these_funders as $this_funder){
                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }
            if(empty($target) or $target == 0){$target = 1;}
                   setup_postdata( $post );?>
            <div id="post-<?php echo esc_attr($post->ID); ?> <?php post_class(); ?>>

            	<div class="project-thumb-wrapper">


             	<?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
                ?>

	              	<a href="<?php the_permalink(); ?>">
	              		<img src="<?php echo esc_url($image[0]); ?>" />
	              	</a>

                <?php }else{ ?>

	                <a href="<?php the_permalink(); ?>">
	                	<img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
	                </a>

                <?php } ?>

                </div>

               <div class="category-container">
            <h3 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div id="post-content">

            	<p>
	            	<?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?>
	            		<span>
		            		<?php esc_html_e('by ','fundingpress');?>

			            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
			            		<?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?>
			            	</a>
		            	</span>
	            	<?php }else{ ?>
						<span>
							<?php esc_html_e('by ','fundingpress');?>

			            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
			            		<?php echo esc_attr(get_the_author_meta('display_name',get_the_author_meta('ID'))); ?>
			            	</a>
		            	</span>

					<?php } ?>
	 				<?php $term_list = wp_get_post_terms($post->ID, 'project-category');
	                if(!empty($term_list)){ ?>
	                	<span>
	                		<i class="fa fa-tag" ></i> <?php }

			                $lastElement = end($term_list); foreach ( $term_list as $cat ) { ?>

			                <a id="click" class="<?php echo esc_attr($cat->slug); ?> ajax" href="<?php echo esc_url(get_term_link( $cat )); ?>"  >
			                	<?php echo esc_attr($cat->name); ?>
			                </a>
		                </span>

	                <?php if($cat != $lastElement){echo ', ';} } ?>


	                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
	                <span>
	                	<i class="fa fa-map-marker" ></i> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b>
	                	<?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
	                	<?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
	                </span>
	                <?php } ?>
            	</p>

              		<div class="the_excerpt">
              			<?php $excerpt = get_the_excerpt();
                		echo mb_substr($excerpt, 0,200);echo '...'; ?>
					</div>
              <div class="project_collected"><strong><?php echo esc_attr($project_currency_sign); echo number_format(round((int)$funded_amount), 0, '.', ',');echo ' '; ?></strong><?php  esc_html_e('Collected', 'fundingpress');?></div>

            <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

            <ul class="project-stats">
                <li class="first funded">
                     <strong><?php printf('%u%%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?></strong><?php esc_html_e("funded", 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                         <?php echo esc_attr($project_currency_sign); echo number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e("target", 'fundingpress'); ?>
                </li>
                <li data-end_time="2013-02-24T08:41:18Z" class="last ksr_page_timer">
 				<?php       if(!$project_expired){ ?>
                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
                        	<strong> <?php esc_html_e('< 24', 'fundingpress'); ?></strong>
                        <?php }else{ ?>
                        	<strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong>

                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
                         <?php esc_html_e('hours to go', 'fundingpress'); ?>
                        <?php }else{ ?>

                        <?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
                        		 <?php esc_html_e('day to go', 'fundingpress'); ?>
                        	<?php }else{ ?>
                        		 <?php esc_html_e('days to go', 'fundingpress'); ?>
                        	<?php } ?>
                        <?php } ?>

                    <?php }else{ ?>
							<strong> <?php esc_html_e('Ended', 'fundingpress'); ?></strong>
							<?php if($funded_amount > $target or $funded_amount == $target){ ?>
								<?php esc_html_e('Successful', 'fundingpress'); ?>
							<?php }else{ ?>
								<?php esc_html_e('Unsuccessful', 'fundingpress'); ?>
							<?php } ?>
                    <?php  } ?>
                </li>
            </ul>
            <a class="edit-button button-small button-green" href="<?php echo get_permalink(apply_filters( 'wpml_object_id', get_page_by_path( 'all-projects' )->ID, 'page' ))?>"><?php esc_html_e("View all projects", 'fundingpress'); ?></a>

          </div> <!--post-content -->
           </div> <!-- category-container -->
 <?php
            break;  }
            wp_reset_postdata();
            $response = ob_get_contents();
            ob_end_clean();
            echo $response;
            die(1);

}elseif($display == 2){ ///////////////////////////////////staff

        foreach ( $posts as $post ) {
                global $post;
                global $f_currency_signs;
                $project_settings = (array) get_post_meta($post->ID, 'settings', true);

				if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

				if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}


            	if (strpos( $project_settings['date'] , "/") !== false) {
		  			$parseddate = str_replace('/' , '.' , $project_settings['date']);
				}else{
					$parseddate = $project_settings['date'];
				}

            	$project_expired = strtotime($parseddate) < time();
                $project_currency_sign = $f_currency_signs[$project_settings['currency']];
                $target= $project_settings['target'];
                $rewards = get_children(array(
                'post_parent' => $post->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                    'post_parent' => $this_reward->ID,
                    'post_type' => 'funder',
                    'post_status' => 'publish'
                ));
                foreach($these_funders as $this_funder){
                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }
           if(empty($target) or $target == 0){$target = 1;}

           if(get_post_meta($post->ID, '_smartmeta_staff-check-field', true) == 'true'){
                   setup_postdata( $post );?>
            <div id="post-<?php echo esc_attr($post->ID); ?> <?php post_class(); ?>>


             <div class="project-thumb-wrapper">


             	<?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
                ?>

	              	<a href="<?php the_permalink(); ?>">
	              		<img src="<?php echo esc_url($image[0]); ?>" />
	              	</a>

                <?php }else{ ?>

	                <a href="<?php the_permalink(); ?>">
	                	<img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
	                </a>

                <?php } ?>

                </div>



               <div class="category-container">
            <h3 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="post-author">
            <?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?><?php esc_html_e('by ','fundingpress');?> <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?></a><?php } ?>

           </div>
            <div id="post-content">

            <p>
	            	<?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?>

	            		<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?>
		            	</a>
	            	<?php }else{ ?>

						<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('display_name',get_the_author_meta('ID'))); ?>
		            	</a>

					<?php } ?>
	 				<?php $term_list = wp_get_post_terms($post->ID, 'project-category');
	                if(!empty($term_list)){ ?><span class="fa fa-tag" ></span> <?php }

	                $lastElement = end($term_list); foreach ( $term_list as $cat ) { ?>

	                <a id="click" class="<?php echo esc_attr($cat->slug); ?> ajax" href="<?php echo esc_url(get_term_link( $cat )); ?>"  >
	                	<?php echo esc_attr($cat->name); ?>
	                </a>

	                <?php if($cat != $lastElement){echo ', ';} } ?>


	                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
	                <span class="fa fa-map-marker" ></span> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b>
	                <?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
	                <?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
	                <?php } ?>
            	</p>

              <?php $excerpt = get_the_excerpt();
                	echo mb_substr($excerpt, 0,200);echo '...'; ?>

              <div class="project_collected"><strong><?php echo esc_attr($project_currency_sign); echo number_format(round((int)$funded_amount), 0, '.', ','); echo ' '; ?> </strong><?php  esc_html_e('Collected', 'fundingpress');?></div>


            <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

            <ul class="project-stats">
                <li class="first funded">
                     <strong><?php printf('%u%%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?></strong><?php esc_html_e("funded", 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                         <?php echo esc_attr($project_currency_sign); echo number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e("Target", 'fundingpress'); ?>
                </li>
                <li data-end_time="2013-02-24T08:41:18Z" class="last ksr_page_timer">
                        <?php       if(!$project_expired){ ?>
						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                        	<strong> <?php esc_html_e('< 24', 'fundingpress'); ?></strong>
						                        <?php }else{ ?>
						                        	<strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong>

						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                         <?php esc_html_e('hours to go', 'fundingpress'); ?>
						                        <?php }else{ ?>

						                        <?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
						                        		 <?php esc_html_e('day to go', 'fundingpress'); ?>
						                        	<?php }else{ ?>
						                        		 <?php esc_html_e('days to go', 'fundingpress'); ?>
						                        	<?php } ?>
						                        <?php } ?>

						                    <?php }else{ ?>
													<strong> <?php esc_html_e('Ended', 'fundingpress'); ?></strong>
													<?php if($funded_amount > $target or $funded_amount == $target){ ?>
														<?php esc_html_e('Successful', 'fundingpress'); ?>
													<?php }else{ ?>
														<?php esc_html_e('Unsuccessful', 'fundingpress'); ?>
													<?php } ?>
						                    <?php  } ?>
                </li>
            </ul>
          <a class="edit-button button-small button-green" href="<?php echo get_permalink( get_page_by_path( 'all-projects' ) ); ?>"><?php esc_html_e("View all projects", 'fundingpress'); ?></a>
          </div> <!--post-content -->
           </div> <!-- category-container -->
           <?php
                break;
             }} wp_reset_postdata();
    $response = ob_get_contents();
    ob_end_clean();
    echo $response;
    die(1);


}elseif($display == 3){ ///////////////////////////////////featured

        foreach ( $posts as $post ) {
                global $post, $f_currency_signs;
                setup_postdata( $post );

            if(get_post_meta($post->ID, '_smartmeta_featured', true) == 'true'){

                $project_settings = (array) get_post_meta($post->ID, 'settings', true);

				if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

				if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}


            	if (strpos( $project_settings['date'] , "/") !== false) {
		  			$parseddate = str_replace('/' , '.' , $project_settings['date']);
				}else{
					$parseddate = $project_settings['date'];
				}

            	$project_expired = strtotime($parseddate) < time();
                $project_currency_sign = $f_currency_signs[$project_settings['currency']];
                $target= $project_settings['target'];
                $rewards = get_children(array(
                'post_parent' => $post->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));
            $funders = array();
           $funded_amount = 0;
            $chosen_reward = null;
            foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                    'post_parent' => $this_reward->ID,
                    'post_type' => 'funder',
                    'post_status' => 'publish'
                ));
                foreach($these_funders as $this_funder){
                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }
           if(empty($target) or $target == 0){$target = 1;} ?>

            <div class="project-thumb-wrapper">

             	<?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
                ?>

	              	<a href="<?php the_permalink(); ?>">
	              		<img src="<?php echo esc_url($image[0]); ?>" />
	              	</a>

                <?php }else{ ?>

	                <a href="<?php the_permalink(); ?>">
	                	<img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
	                </a>

                <?php } ?>

                </div>

                <div class="category-container">
            <h3 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="post-author">
            <?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?><?php esc_html_e('by ','fundingpress');?> <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?></a><?php } ?>

           </div>
            <div id="post-content">

            <p>
	            	<?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?>

	            		<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?>
		            	</a>
	            	<?php }else{ ?>

						<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('display_name',get_the_author_meta('ID'))); ?>
		            	</a>

					<?php } ?>
	 				<?php $term_list = wp_get_post_terms($post->ID, 'project-category');
	                if(!empty($term_list)){ ?><span class="fa fa-tag" ></span> <?php }

	                $lastElement = end($term_list); foreach ( $term_list as $cat ) { ?>

	                <a id="click" class="<?php echo esc_attr($cat->slug); ?> ajax" href="<?php echo esc_url(get_term_link( $cat )); ?>"  >
	                	<?php echo esc_attr($cat->name); ?>
	                </a>

	                <?php if($cat != $lastElement){echo ', ';} } ?>


	                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
	                <span class="fa fa-map-marker" ></span> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b>
	                <?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
	                <?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
	                <?php } ?>
            	</p>

              <?php $excerpt = get_the_excerpt();
                	echo mb_substr($excerpt, 0,200);echo '...'; ?>

              <div class="project_collected"><strong><?php echo esc_attr($project_currency_sign); echo number_format(round((int)$funded_amount), 0, '.', ','); echo ' '; ?> </strong><?php  esc_html_e('Collected', 'fundingpress');?></div>


            <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

            <ul class="project-stats">
                <li class="first funded">
                     <strong><?php printf('%u%%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?></strong><?php esc_html_e("funded", 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                         <?php echo esc_attr($project_currency_sign); echo number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e("Target", 'fundingpress'); ?>
                </li>
                <li data-end_time="2013-02-24T08:41:18Z" class="last ksr_page_timer">
                        <?php       if(!$project_expired){ ?>
						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                        	<strong> <?php esc_html_e('< 24', 'fundingpress'); ?></strong>
						                        <?php }else{ ?>
						                        	<strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong>

						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                         <?php esc_html_e('hours to go', 'fundingpress'); ?>
						                        <?php }else{ ?>

						                        <?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
						                        		 <?php esc_html_e('day to go', 'fundingpress'); ?>
						                        	<?php }else{ ?>
						                        		 <?php esc_html_e('days to go', 'fundingpress'); ?>
						                        	<?php } ?>
						                        <?php } ?>

						                    <?php }else{ ?>
													<strong> <?php esc_html_e('Ended', 'fundingpress'); ?></strong>
													<?php if($funded_amount > $target or $funded_amount == $target){ ?>
														<?php esc_html_e('Successful', 'fundingpress'); ?>
													<?php }else{ ?>
														<?php esc_html_e('Unsuccessful', 'fundingpress'); ?>
													<?php } ?>
						                    <?php  } ?>
                </li>
            </ul>
          <a class="edit-button button-small button-green" href="<?php echo get_permalink( get_page_by_path( 'all-projects' ) ); ?>"><?php esc_html_e("View all projects", 'fundingpress'); ?></a>
          </div> <!--post-content -->
           </div> <!-- category-container -->

            <?php
            break; }  }
            wp_reset_postdata();
            $response = ob_get_contents();
            ob_end_clean();
            echo $response;
            die(1);

}elseif($display == 4){ ////////////////////////////////////sucessful
?>
 <?php foreach ( $posts as $post ) {

            global $post;
               setup_postdata( $post );
                global $f_currency_signs;
                $project_settings = (array) get_post_meta($post->ID, 'settings', true);

				if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}


			if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}



                	if (strpos( $project_settings['date'] , "/") !== false) {
			  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
						}else{
							$parseddate = $project_settings['date'];
						}
            	$project_expired = strtotime($parseddate) < time();
                $project_currency_sign = $f_currency_signs[$project_settings['currency']];
                $target= $project_settings['target'];
                $rewards = get_children(array(
                'post_parent' => $post->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                    'post_parent' => $this_reward->ID,
                    'post_type' => 'funder',
                    'post_status' => 'publish'
                ));
                foreach($these_funders as $this_funder){
                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }
                  if(empty($target) or $target == 0){$target = 1;}


                   if($funded_amount == $target or $funded_amount > $target){?>
             <div id="post-<?php echo esc_attr($post->ID); ?> <?php post_class(); ?>>

             <div class="project-thumb-wrapper">


             	<?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
                ?>

	              	<a href="<?php the_permalink(); ?>">
	              		<img src="<?php echo esc_url($image[0]); ?>" />
	              	</a>

                <?php }else{ ?>

	                <a href="<?php the_permalink(); ?>">
	                	<img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
	                </a>

                <?php } ?>

                </div>

               <div class="category-container">
            <h3 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="post-author">
            <?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?><?php esc_html_e('by ','fundingpress');?> <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?></a><?php } ?>

           </div>
            <div id="post-content">
             <p>
	            	<?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?>

	            		<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?>
		            	</a>
	            	<?php }else{ ?>

						<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('display_name',get_the_author_meta('ID'))); ?>
		            	</a>

					<?php } ?>
	 				<?php $term_list = wp_get_post_terms($post->ID, 'project-category');
	                if(!empty($term_list)){ ?><span class="fa fa-tag" ></span> <?php }

	                $lastElement = end($term_list); foreach ( $term_list as $cat ) { ?>

	                <a id="click" class="<?php echo esc_attr($cat->slug); ?> ajax" href="<?php echo esc_url(get_term_link( $cat )); ?>"  >
	                	<?php echo esc_attr($cat->name); ?>
	                </a>

	                <?php if($cat != $lastElement){echo ', ';} } ?>


	                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
	                <span class="fa fa-map-marker" ></span> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b>
	                <?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
	                <?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
	                <?php } ?>
            	</p>

              <?php $excerpt = get_the_excerpt();
                	echo mb_substr($excerpt, 0,200);echo '...'; ?>

              <div class="project_collected"><strong><?php echo esc_attr($project_currency_sign); echo number_format(round((int)$funded_amount), 0, '.', ',');echo ' '; ?></strong><?php  esc_html_e('Collected', 'fundingpress');?></div>


            <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

            <ul class="project-stats">
                <li class="first funded">
                     <strong><?php printf('%u%%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?></strong><?php esc_html_e("funded", 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                         <?php echo esc_attr($project_currency_sign); echo number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e("target", 'fundingpress'); ?>
                </li>
                <li data-end_time="2013-02-24T08:41:18Z" class="last ksr_page_timer">
                        <?php       if(!$project_expired){ ?>
						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                        	<strong> <?php esc_html_e('< 24', 'fundingpress'); ?></strong>
						                        <?php }else{ ?>
						                        	<strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong>

						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                         <?php esc_html_e('hours to go', 'fundingpress'); ?>
						                        <?php }else{ ?>

						                        <?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
						                        		 <?php esc_html_e('day to go', 'fundingpress'); ?>
						                        	<?php }else{ ?>
						                        		 <?php esc_html_e('days to go', 'fundingpress'); ?>
						                        	<?php } ?>
						                        <?php } ?>

						                    <?php }else{ ?>
													<strong> <?php esc_html_e('Ended', 'fundingpress'); ?></strong>
													<?php if($funded_amount > $target or $funded_amount == $target){ ?>
														<?php esc_html_e('Successful', 'fundingpress'); ?>
													<?php }else{ ?>
														<?php esc_html_e('Unsuccessful', 'fundingpress'); ?>
													<?php } ?>
						                    <?php  } ?>
                </li>
            </ul>
          <a class="edit-button button-small button-green" href="<?php echo get_permalink( get_page_by_path( 'all-projects' ) ); ?>"><?php esc_html_e("View all projects", 'fundingpress'); ?></a>
          </div> <!--post-content -->
           </div> <!-- category-container -->
            <?php break;
            }
             } wp_reset_postdata();
    $response = ob_get_contents();
    ob_end_clean();
    echo $response;
    die(1);

}elseif($display == 5){//////////////////////////////ending

             global $post;
             $cat_id = $_POST[ 'cat' ];
             $term = get_term( $cat_id, 'project-category' );
             $args = array (
            'showposts' => -1,
            'post_type' => 'project',
            'orderby' => 'meta_value',
            'meta_key' => 'datum',
            'order' => 'ASC',
            'post_status' => 'publish',
            'project-category' => $term->slug,
            'meta_query' => array(
                array(
                    'key' => 'datum',
                    'value' => date(get_option('date_format'),time()),
                    'compare' => '>=',
                ))
            );
        $posts = get_posts( $args );
        ob_start ();
        foreach ( $posts as $post ) {
                global $post;
                global $f_currency_signs;
                $project_settings = (array) get_post_meta($post->ID, 'settings', true);


				if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

			if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}


               	if (strpos( $project_settings['date'] , "/") !== false) {
			  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
						}else{
							$parseddate = $project_settings['date'];
						}
            	$project_expired = strtotime($parseddate) < time();
                $project_currency_sign = $f_currency_signs[$project_settings['currency']];
                $target= $project_settings['target'];
                $rewards = get_children(array(
                'post_parent' => $post->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                    'post_parent' => $this_reward->ID,
                    'post_type' => 'funder',
                    'post_status' => 'publish'
                ));
                foreach($these_funders as $this_funder){
                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }

                  if(empty($target) or $target == 0){$target = 1;}
                   setup_postdata( $post );?>
             <div id="post-<?php echo esc_attr($post->ID); ?> <?php post_class(); ?>">


			<div class="project-thumb-wrapper">


             	<?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
                ?>

	              	<a href="<?php the_permalink(); ?>">
	              		<img src="<?php echo esc_url($image[0]); ?>" />
	              	</a>

                <?php }else{ ?>

	                <a href="<?php the_permalink(); ?>">
	                	<img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
	                </a>

                <?php } ?>

                </div>

               <div class="category-container">
            <h3 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="post-author">
            <?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?><?php esc_html_e('by ','fundingpress');?> <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?></a><?php } ?>

           </div>
            <div id="post-content">
             <p>
	            	<?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?>

	            		<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID'))); ?>
		            	</a>
	            	<?php }else{ ?>

						<?php esc_html_e('by ','fundingpress');?>

		            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		            		<?php echo esc_attr(get_the_author_meta('display_name',get_the_author_meta('ID'))); ?>
		            	</a>

					<?php } ?>
	 				<?php $term_list = wp_get_post_terms($post->ID, 'project-category');
	                if(!empty($term_list)){ ?><span class="fa fa-tag" ></span> <?php }

	                $lastElement = end($term_list); foreach ( $term_list as $cat ) { ?>

	                <a id="click" class="<?php echo esc_attr($cat->slug); ?> ajax" href="<?php echo esc_url(get_term_link( $cat )); ?>"  >
	                	<?php echo esc_attr($cat->name); ?>
	                </a>

	                <?php if($cat != $lastElement){echo ', ';} } ?>


	                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
	                <span class="fa fa-map-marker" ></span> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b>
	                <?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
	                <?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
	                <?php } ?>
            	</p>

              <?php $excerpt = get_the_excerpt();
                	echo mb_substr($excerpt, 0,200);echo '...'; ?>

              <div class="project_collected"><strong><?php echo esc_attr($project_currency_sign); echo number_format(round((int)$funded_amount), 0, '.', ',');echo ' '; ?></strong><?php  esc_html_e('Collected', 'fundingpress');?></div>



            <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

            <ul class="project-stats">
                <li class="first funded">
                     <strong><?php printf('%u%%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?></strong><?php esc_html_e("funded", 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                         <?php echo esc_attr($project_currency_sign); echo number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e("target", 'fundingpress'); ?>
                </li>
                <li data-end_time="2013-02-24T08:41:18Z" class="last ksr_page_timer">
                        <?php       if(!$project_expired){ ?>
						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                        	<strong> <?php esc_html_e('< 24', 'fundingpress'); ?></strong>
						                        <?php }else{ ?>
						                        	<strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong>

						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                         <?php esc_html_e('hours to go', 'fundingpress'); ?>
						                        <?php }else{ ?>

						                        <?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
						                        		 <?php esc_html_e('day to go', 'fundingpress'); ?>
						                        	<?php }else{ ?>
						                        		 <?php esc_html_e('days to go', 'fundingpress'); ?>
						                        	<?php } ?>
						                        <?php } ?>

						                    <?php }else{ ?>
													<strong> <?php esc_html_e('Ended', 'fundingpress'); ?></strong>
													<?php if($funded_amount > $target or $funded_amount == $target){ ?>
														<?php esc_html_e('Successful', 'fundingpress'); ?>
													<?php }else{ ?>
														<?php esc_html_e('Unsuccessful', 'fundingpress'); ?>
													<?php } ?>
						                    <?php  } ?>
                </li>
            </ul>
          <a class="edit-button button-small button-green" href="<?php echo get_permalink( get_page_by_path( 'all-projects' ) ); ?>"><?php esc_html_e("View all projects", 'fundingpress'); ?></a>
          </div> <!--post-content -->
           </div> <!-- category-container -->
             <?php
  break;} wp_reset_postdata();
    $response = ob_get_contents();
    ob_end_clean();
    echo $response;
    die(1);

}}


/********load campaigns with ajax in all projects page*****/
//get post for ajax category

function funding_prefix_load_cat_posts_all() { ?>
<div class="isoprblckall">
        <?php
        global $post;
        $cat_id = $_POST[ 'cat' ];
        $term = get_term( $cat_id, 'project-category' );
            $args = array (
            'showposts' => -1,
            'post_type' => 'project',
            'orderby' => 'post_date',
            'post_status' => 'publish',
            'project-category' => $term->slug,
            );
        $posts = get_posts($args);

        ob_start ();

       foreach ( $posts as $post ) {
                global $post;
                global $f_currency_signs;
                $project_settings = (array) get_post_meta($post->ID, 'settings', true);


				if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				if(!isset($array[1]))$array[1]='';
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}

				if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
				$array = explode('/', $project_settings['date']);
				$tmp = $array[0];
				if(!isset($array[1]))$array[1]='';
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$project_settings['date'] = $array[1];
				}else{
				$project_settings['date'] = implode('/', $array);
				}
			}


                	if (strpos( $project_settings['date'] , "/") !== false) {
			  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
						}else{
							$parseddate = $project_settings['date'];
						}
            	$project_expired = strtotime($parseddate) < time();
                $project_currency_sign = $f_currency_signs[$project_settings['currency']];
                $target= $project_settings['target'];
                $rewards = get_children(array(
                'post_parent' => $post->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                    'post_parent' => $this_reward->ID,
                    'post_type' => 'funder',
                    'post_status' => 'publish'
                ));
                foreach($these_funders as $this_funder){
                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }

                  if(empty($target) or $target == 0){$target = 1;}
                   setup_postdata( $post );?>
			      <div class="project-card col-lg-4">
			      			<div class="project-thumb-wrapper">
            	<?php
              	$autorpic = get_the_author_meta('profile_pic',  get_the_author_id());
              	if(!empty($autorpic)){
               	$image = aq_resize( $autorpic,  250, 250, true, true, true ); //resize & crop img
              	if (!isset ($image[0])) {
              		$theimage = $autorpic;
              	} else {
              		$theimage = $image;
              	} ?>
               	<img class="userimg" src="<?php echo esc_url($theimage); ?>" />
               <?php }else{ ?>
               	<img class="userimg" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_user.png" />
               <?php } ?>


             	<?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
                ?>

	              	<a href="<?php the_permalink(); ?>">
	              		<img src="<?php echo esc_url($image[0]); ?>" />
	              	</a>

                <?php }else{ ?>

	                <a href="<?php the_permalink(); ?>">
	                	<img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
	                </a>

                <?php } ?>

                </div>
			                 <h5 class="bbcard_name"><a href="<?php the_permalink(); ?>"><?php $title = get_the_title(); echo esc_attr(mb_substr($title, 0,20)); if(strlen($title) > 23){echo '...';}?></a></h5>
			                 <?php if(get_the_author_meta('first_name',get_the_author_meta('ID')) or get_the_author_meta('last_name',get_the_author_meta('ID'))){ ?>
		                 	<span><?php esc_html_e("by", 'fundingpress'); ?>

		                 		<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		                 			<?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID')).' '.get_the_author_meta('last_name',get_the_author_meta('ID'))); ?>
		                 		</a>
		                 	</span>
		                 <?php }else{ ?>
		              		<span><?php esc_html_e("by", 'fundingpress'); ?>

		                 		<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
		                 			<?php echo esc_attr(get_the_author_meta('display_name',get_the_author_meta('ID'))); ?>
		                 		</a>
		                 	</span>

		              	<?php } ?>

			                 <p> <?php
			                $excerpt = get_the_excerpt();
			                echo mb_substr($excerpt, 0,110);echo '...';
			             ?></p>

			                <?php
			                global $post;
			                global $f_currency_signs;
			                $project_settings = (array) get_post_meta($post->ID, 'settings', true);

							if(get_option('date_format') == 'd/m/Y' && strtotime($project_settings['date']) != false){
							$array = explode('/', $project_settings['date']);
							$tmp = $array[0];
							$array[0] = $array[1];
							$array[1] = $tmp;
							unset($tmp);
							if($array[0] == NULL){
								$project_settings['date'] = $array[1];
							}else{
							$project_settings['date'] = implode('/', $array);
							}
						}


						if(get_option('date_format') == 'm/d/Y' && strtotime($project_settings['date']) != false){
							$array = explode('/', $project_settings['date']);
							$tmp = $array[0];
							$array[0] = $array[1];
							$array[1] = $tmp;
							unset($tmp);
							if($array[0] == NULL){
								$project_settings['date'] = $array[1];
							}else{
							$project_settings['date'] = implode('/', $array);
							}
						}

			                	if (strpos( $project_settings['date'] , "/") !== false) {
						  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
									}else{
										$parseddate = $project_settings['date'];
									}
			            	$project_expired = strtotime($parseddate) < time();
			                $project_currency_sign = $f_currency_signs[$project_settings['currency']];
			                $target= $project_settings['target'];
			                $rewards = get_children(array(
			                'post_parent' => $post->ID,
			                'post_type' => 'reward',
			                'order' => 'ASC',
			                'orderby' => 'meta_value_num',
			                'meta_key' => 'funding_amount',
			            ));
			            $funders = array();
			            $funded_amount = 0;
			            $chosen_reward = null;
			            foreach($rewards as $this_reward){
			                $these_funders = get_children(array(
			                    'post_parent' => $this_reward->ID,
			                    'post_type' => 'funder',
			                    'post_status' => 'publish'
			                ));
			                foreach($these_funders as $this_funder){
			                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
			                    $funders[] = $this_funder;
			                    $funded_amount += $funding_amount;
			                }
			            }?>
			             <p class="plocation">
			                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
			                <span class="fa fa-map-marker" ></span> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b>
			                <?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
			                <?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
			                <?php } ?>
			            </p>

			            <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?>%" class="bar"></div></div>

			            <ul class="project-stats">
			                <li class="first funded">
			                     <strong><?php printf('%u%%', round($funded_amount/$target*100), $project_currency_sign, round($target)) ?></strong><?php esc_html_e('funded', 'fundingpress'); ?>
			                </li>
			                <li class="pledged">
			                    <strong>
			                         <?php echo esc_attr($project_currency_sign); echo number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e('target', 'fundingpress'); ?>
			                </li>
			                <li data-end_time="2013-02-24T08:41:18Z" class="last ksr_page_timer">
			                        <?php       if(!$project_expired){ ?>
						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                        	<strong> <?php esc_html_e('< 24', 'fundingpress'); ?></strong>
						                        <?php }else{ ?>
						                        	<strong><?php print F_Controller::timesince(time(), strtotime($parseddate), 1, ''); } ?></strong>

						                        <?php if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){ ?>
						                         <?php esc_html_e('hours to go', 'fundingpress'); ?>
						                        <?php }else{ ?>

						                        <?php if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){ ?>
						                        		 <?php esc_html_e('day to go', 'fundingpress'); ?>
						                        	<?php }else{ ?>
						                        		 <?php esc_html_e('days to go', 'fundingpress'); ?>
						                        	<?php } ?>
						                        <?php } ?>

						                    <?php }else{ ?>
													<strong> <?php esc_html_e('Ended', 'fundingpress'); ?></strong>
													<?php if($funded_amount > $target or $funded_amount == $target){ ?>
														<?php esc_html_e('Successful', 'fundingpress'); ?>
													<?php }else{ ?>
														<?php esc_html_e('Unsuccessful', 'fundingpress'); ?>
													<?php } ?>
						                    <?php  } ?>
			                </li>
			            </ul>
			                <div class="clear"></div>
			       </div>

            <?php
            } ?>
             </div><script>

/******************** Isotope all project block***********************/
var blog = jQuery(".isoprblckall");
if(blog.length !== 0){
if(jQuery.isFunction(jQuery.fn.imagesLoaded)){

    //isotope
    var container = jQuery('.isoprblckall');

    container.imagesLoaded( function(){

    // initialize Isotope
    container.isotope({
        // options...
        layoutMode : 'fitRows',
        resizable: false, // disable normal resizing
        // set columnWidth to a percentage of container width
        masonry: {
            columnWidth: container.width() / 3
        }
    });
    });
    // start new block
    jQuery('.cat a').click(function(){

        var selector = jQuery(this).attr('href');
        container.isotope({ filter: selector });
        return false;
    });
    // end new block

    // update columnWidth on window resize
    jQuery(window).smartresize(function(){
        //console.log(container.width());
        // set the widths on resize
        setWidthsAll();
        container.isotope({
            // update columnWidth to a percentage of container width
            masonry: {
                columnWidth: getUnitWidthAll()
            }
        });
    }).resize();
}
}
/*  Isotope utility GetUnitWidth
    ========================================================================== */
function getUnitWidthAll() {
    var container = jQuery('.isoprblckall');
    var width;
    if (container.width() <= 320) {
        //console.log("320");
        width = Math.floor((container.width() - 20) / 1);
    } else if (container.width() >= 321 && container.width() <= 480) {
        //console.log("321 - 480");
        width = Math.floor((container.width() - 30) / 1);
    } else if (container.width() >= 481 && container.width() <= 662) {
       // console.log("481 - 768");
        width = Math.floor((container.width() - 100) / 2);
    } else if (container.width() >= 663 && container.width() <= 768) {
        //console.log("663 - 768");
        width = Math.floor((container.width() - 90) / 2);
    } else if (container.width() >= 769 && container.width() <= 979) {
        //console.log("769 - 979");
        width = Math.floor((container.width() - 135) /3);
    } else if (container.width() >= 980 && container.width() <= 1200) {
        //console.log("980 - 1200");
        width = Math.floor((container.width() - 135) / 3);
    } else if (container.width() >= 1201 && container.width() <= 1600) {
       // console.log("1201 - 1600");
        width = Math.floor((container.width() - 135) / 3);
    } else if (container.width() >= 1601 && container.width() <= 1824) {
       // console.log("1601 - 1824");
        width = Math.floor((container.width() - 135) / 3);
    } else if (container.width() >= 1825) {
       // console.log("1825");
        width = Math.floor((container.width() - 135) / 3);
    }
    return width;
}
/*  Isotope utility SetWidths
    ========================================================================== */
function setWidthsAll() {
    var container = jQuery('.isoprblckall');
    var unitWidth = getUnitWidthAll() - 0;
    container.children(":not(.width2)").css({
        width: unitWidth
    });

    if (container.width() >= 321 && container.width() <= 480) {
        //console.log("eccoci 321");
        container.children(".width2").css({
            width: unitWidth * 1
        });
        container.children(".width4").css({
            width: unitWidth * 2
        });
        container.children(".width6").css({
            width: unitWidth * 3
        });
    }
    if (container.width() >= 481) {
        //console.log("480");
        container.children(".width6").css({
            width: unitWidth * 4
        });
        container.children(".width4").css({
            width: unitWidth * 3
        });
        container.children(".width2").css({
            width: unitWidth * 2
        });
    } else {
        container.children(".width2").css({
            width: unitWidth
        });
    }
}

             </script>
           <?php wp_reset_postdata();
            $response = ob_get_contents();
            ob_end_clean();
            echo $response;
            die(1);

}


/*image resize*/
function aq_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {

    // Validate inputs.
    if ( ! $url || ( ! $width && ! $height ) ) return false;

    // Caipt'n, ready to hook.
    if ( true === $upscale ) add_filter( 'image_resize_dimensions', 'aq_upscale', 10, 6 );

    // Define upload path & dir.
    $upload_info = wp_upload_dir();
    $upload_dir = $upload_info['basedir'];
    $upload_url = $upload_info['baseurl'];

    $http_prefix = "http://";
    $https_prefix = "https://";

    /* if the $url scheme differs from $upload_url scheme, make them match
       if the schemes differe, images don't show up. */
    if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
        $upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
    }
    elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
        $upload_url = str_replace($https_prefix,$http_prefix,$upload_url);
    }


    // Check if $img_url is local.
    if ( false === strpos( $url, $upload_url ) ) return false;

    // Define path of image.
    $rel_path = str_replace( $upload_url, '', $url );
    $img_path = $upload_dir . $rel_path;

    // Check if img path exists, and is an image indeed.
    if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

    // Get image info.
    $info = pathinfo( $img_path );
    $ext = $info['extension'];
    list( $orig_w, $orig_h ) = getimagesize( $img_path );

    // Get image size after cropping.
    $dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
    $dst_w = $dims[4];
    $dst_h = $dims[5];

    // Return the original image only if it exactly fits the needed measures.
    if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
        $img_url = $url;
        $dst_w = $orig_w;
        $dst_h = $orig_h;
    } else {
        // Use this to check if cropped image already exists, so we can return that instead.
        $suffix = "{$dst_w}x{$dst_h}";
        $dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
        $destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

        if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
            // Can't resize, so return false saying that the action to do could not be processed as planned.
            return false;
        }
        // Else check if cache exists.
        elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
            $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
        }
        // Else, we resize the image and return the new resized image url.
        else {

            // Note: This pre-3.5 fallback check will edited out in subsequent version.
            if ( function_exists( 'wp_get_image_editor' ) ) {

                $editor = wp_get_image_editor( $img_path );

                if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
                    return false;

                $resized_file = $editor->save();

                if ( ! is_wp_error( $resized_file ) ) {
                    $resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
                    $img_url = $upload_url . $resized_rel_path;
                } else {
                    return false;
                }

            } else {

                $resized_img_path = wp_get_image_editor( $img_path, $width, $height, $crop ); // Fallback foo.
                if ( ! is_wp_error( $resized_img_path ) ) {
                    $resized_rel_path = str_replace( $upload_dir, '', $resized_img_path );
                    $img_url = $upload_url . $resized_rel_path;
                } else {
                    return false;
                }

            }

        }
    }

    // Okay, leave the ship.
    if ( true === $upscale ) remove_filter( 'image_resize_dimensions', 'aq_upscale' );

    // Return the output.
    if ( $single ) {
        // str return.
        $image = $img_url;
    } else {
        // array return.
        $image = array (
            0 => $img_url,
            1 => $dst_w,
            2 => $dst_h
        );
    }

    return $image;
}


function aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
    if ( ! $crop ) return null; // Let the wordpress default function handle this.

    // Here is the point we allow to use larger image size than the original one.
    $aspect_ratio = $orig_w / $orig_h;
    $new_w = $dest_w;
    $new_h = $dest_h;

    if ( ! $new_w ) {
        $new_w = intval( $new_h * $aspect_ratio );
    }

    if ( ! $new_h ) {
        $new_h = intval( $new_w / $aspect_ratio );
    }

    $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

    $crop_w = round( $new_w / $size_ratio );
    $crop_h = round( $new_h / $size_ratio );

    $s_x = floor( ( $orig_w - $crop_w ) / 2 );
    $s_y = floor( ( $orig_h - $crop_h ) / 2 );

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}

/*functions for user paypal*/
function funding_return_user_pp_address($user_id){
         global $wpdb;

        $pp_address = $wpdb->get_var($wpdb->prepare("SELECT paypal_email FROM ".$wpdb->prefix."users WHERE ID = %s",$user_id));
        return $pp_address;
}

function funding_save_user_pp_address($user_id, $user_email){
      global $wpdb;
      $result = $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."users SET paypal_email = '".$user_email."' WHERE ID = %s",$user_id));
}

/*funding comments form*/
function funding_comments($comment, $args, $depth) {
$GLOBALS['comment'] = $comment;

?>
 <li <?php comment_class(); ?> id="li-comment-update-<?php comment_ID() ?>">
 <div id="comment-<?php comment_ID(); ?>">

 <?php if ($comment->comment_approved == '0') : ?>
 <em><?php esc_html_e('Your update is awaiting approval.', 'fundingpress') ?></em>
 <br />
 <br />
 <?php endif; ?>
  <div class="update-date-stamp"><a><i class="fa fa-clock-o"></i></a> <?php date(get_option('date_format'), comment_date( $comment_ID )); ?>
&nbsp;<?php date(get_option('time_format'), comment_time()); ?></div>
 <?php comment_text() ?>

 </div>
 <?php  funding_delete_comment_link(get_comment_ID(), 'update'); ?>
 </li>

<?php
 }


function funding_comments_child($comment, $args, $depth) {
	$owner_id = $comment->user_id;
	//get_current_user_id();
	$usermeta = get_user_meta($owner_id);
	if ((strlen($usermeta['first_name'][0]) == 0) AND (strlen($usermeta['last_name'][0]) == 0)) {
		$name = $usermeta['nickname'][0];
	} else {
		$name = $usermeta['first_name'][0]." ".$usermeta['last_name'][0];
	}
 $GLOBALS['comment'] = $comment;
 if (($comment->comment_approved == '0') AND  ($owner_id != get_current_user_id())) {
 	return true;
 }


 ?>

 <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
 <div id="comment-<?php comment_ID(); ?>">

 <?php if ($comment->comment_approved == '0') {
	 ?>
 <em><?php esc_html_e('This comment is awaiting approval:', 'fundingpress') ?></em>
 <br />

 <?php } ?>
  <div class="update-date-stamp"><a><i class="fa fa-clock-o"></i></a> <?php date(get_option('date_format'), comment_date( $comment_ID )); ?>
&nbsp;<?php date(get_option('time_format'), comment_time()); ?></div>
<div class="comment_author_name"><?php echo esc_attr($name); ?></div>
 <?php comment_text() ?>

 </div>
 <?php  funding_delete_comment_link(get_comment_ID(), 'comment'); ?>
 </li>

<?php
 }


function funding_custom_comments($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment; ?>
 <li <?php comment_class(); ?> id="li-comment-custom-<?php comment_ID() ?>">
 <div id="comment-<?php comment_ID(); ?>">

 <?php if ($comment->comment_approved == '0') : ?>
 <em><?php esc_html_e('Your update is awaiting approval.', 'fundingpress') ?></em>
 <br />
 <br />
 <?php endif; ?>
  <div class="update-date-stamp"><a><i class="fa fa-clock-o"></i></a> <?php date(get_option('date_format'), comment_date( $comment_ID )); ?>
<?php date(get_option('time_format'), comment_time()); ?></div>
 <?php comment_text() ?>

 </div>
 <?php  funding_delete_comment_link(get_comment_ID()); ?>
 </li>

<?php
 }


/*comments delete and spam link*/
function funding_delete_comment_link($id, $type="none") {
   global $post;
   if ($post->post_author == get_current_user_id()) {
			 //echo '<a href="javascript:;" data-type="'.$type.'" onclick="delpost('.get_comment_ID().')">'.esc_html__("Delete comment", "fundingpress").'</a> ';
			 echo '<a data-type="'.esc_attr($type).'" data-cid="'.get_comment_ID().'" class="comment_deletor">'.esc_html__("Delete comment", "fundingpress").'</a> ';
  }
}

/*delete comments*/
function funding_delete_comments() {
    global $wpdb, $post;
		$cid = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
		$comment = get_comment($cid);
		$uinfo = get_currentuserinfo();


		if ($comment->comment_author_email == $uinfo->data->user_email) {
			if (wp_delete_comment($cid)) {
				//success
				wp_die();
			}
		}
		header("HTTP/1.1 500 Internal Server Error");
		echo "Unexpected error!";
		wp_die();
}


function funding_the_content_filter($content) {

    // array of custom shortcodes requiring the fix
    $block = join("|",array("col","shortcode2","shortcode3", "layerslider"));

    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);

    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);

    return $rep;

}


function funding_restrict_admin_area_to_contributors() {

      if( defined('DOING_AJAX') && DOING_AJAX ) {
            //Allow ajax calls
            return;
      }

      $user = wp_get_current_user();
      if( empty( $user ) || in_array( "contributor", (array) $user->roles ) ) {
           //Redirect to main page if no user or if the user has no "administrator" role assigned
           wp_redirect( get_site_url( ) );
           exit();
      }

 }


function funding_update_caps() {
    $role = get_role( 'contributor' );
    $caps_to_add =  array(
        'edit_others_pages',
        'edit_published_pages',
        'upload_files'
    );
    foreach( $caps_to_add as $cap )
        $role->add_cap( $cap );
}




function funding_delete_projects() {
	$prID = $_POST[ 'idp' ];
	wp_delete_post($prID);
	echo "ok";
	die();

}



/*set email header to use html*/
function funding_set_html_content_type() {
	return 'text/html';
}
remove_filter( 'wp_mail_content_type', 'funding_set_html_content_type' );

/*function to convert dateformat to js for picker*/
function funding_date_format_php_to_js( $sFormat ) {

    switch( $sFormat ) {
        //Predefined WP date formats
         case 'j F, Y':
            return( 'MM dd, yy' );
            break;
        case 'F j, Y':
            return( 'MM dd, yy' );
            break;
        case 'Y/m/d':
            return( 'yy/mm/dd' );
            break;
        case 'm/d/Y':
            return( 'mm/dd/yy' );
            break;
        case 'd/m/Y':
            return( 'dd/mm/yy' );
            break;
		case 'Y-m-d':
            return( 'yy-mm-dd' );
            break;
		default:
			 return( 'MM dd, yy' );
    }
}


function funding_dateformat_var() {
	echo '<script>var formatdatuma = "'.funding_date_format_php_to_js(get_option("date_format")).'";</script>';
}



function funding_wp_editor(){
    ob_start();
    wp_editor( '', 'comment', array(
        'media_buttons' => false,
        'textarea_rows' => '3',
        'tinymce' => array(
            'plugins' => 'wordpress, wplink, wpdialogs',
            'theme_advanced_buttons1' => 'bold, italic, underline, strikethrough, forecolor, separator, bullist, numlist, separator, link, unlink, image',
            'theme_advanced_buttons2' => ''
            ),
        'quicktags' => array('buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close')
        )
    );

    return ob_get_clean();
}

function funding_comment_form_defaults($args) {
    $args['comment_field'] = funding_wp_editor();
    return $args;
}


function funding_allowed_tags() {
    global $allowedtags;
    $allowedtags['ul'] = array();
    $allowedtags['ol'] = array();
    $allowedtags['li'] = array();
    $allowedtags['strong'] = array();
    $allowedtags['ins'] = array(
        'datetime' => true
    );
    $allowedtags['del'] = array(
        'datetime' => true
    );
    $allowedtags['pre'] = array(
        'lang' => true,
        'line' => true
    );
    $allowedtags['span'] = array(
        'style' => true
    );
    $allowedtags['img'] = array(
        'width' => true,
        'height' => true,
        'src' => true,
        'alt' => true
    );
    $allowedtags['a'] = array(
        'target' => true,
        'href' => true,
        'title' => true,
    );
}


/*add vc tempaltes*/

function funding_vc_remove_be_pointers() {
   remove_action( 'admin_init', 'vc_add_admin_pointer' );
}

function funding_vc_remove_fe_pointers() {
   remove_action( 'admin_init', 'vc_frontend_editor_pointer' );
}


if(is_plugin_active('js_composer/js_composer.php')){
class WPBakeryShortCode_VC_Column_news extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_Column_news_horizontal extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_contact extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_social extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_projects extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_project_highlight extends WPBakeryShortCode {}
}





/****************FUNDING PART******************/


/***** Get wepay tokens and accounts *****/
function funding_get_wepay_token () {
	require 'funding/lib/WePay/wepay.php';
	global $f_paypal;
	$user = get_current_user_id();
	$code = $_POST['code'];
    $redirect_uri = $_POST['redirURL']; // this is the redirect_uri you used in step 1

    // application settings
    $client_id = $f_paypal['wepay-client_id'];
    $client_secret = $f_paypal['wepay-client_secret'];

    // change to useProduction for live environments
    if ($f_paypal['wepay-staging'] != 'Yes')  {
		// change to useProduction for live environments
		Wepay::useProduction($client_id, $client_secret);
	} else {
		Wepay::useStaging($client_id, $client_secret);
	}



    $wepay = new WePay(NULL); // we don't have an access_token yet so we can pass NULL here

    // create an account for a user
    $response = WePay::getToken($code, $redirect_uri);

	update_user_meta($user, "wepay_token", $response->access_token);
	$thetoken = $response->access_token;

	$wepay2 = new WePay($thetoken);
	$response = $wepay2->request('account/create/', array(
        'name'          => get_bloginfo('name'). ' account',
        'description'   => 'Account for '.get_bloginfo('name').' withdrawls'
    ));

	update_user_meta($user, "wepay_account_id", $response->account_id);
	update_user_meta($user, "linked_wepay", "1");
	echo "ok";
	die();

}

function funding_unlink_wepay () {
	$user = get_current_user_id();
	delete_user_meta($user, 'linked_wepay');
	echo "ok";
	die();

}


function funding_unlink_stripe () {
	$user = get_current_user_id();
	delete_user_meta($user, 'stripe_data');
	echo "ok";
	die();
}


function funding_send_mail($uid_or_email, $title, $content) {
	$headers = array('Content-Type: text/html; charset=UTF-8');

	if(is_numeric($uid_or_email)){
		$user = get_user_by('id', $uid_or_email);
		wp_mail($user->data->user_email, $title, $content, $headers);
	}else{
		wp_mail($uid_or_email, $title, $content, $headers);
	}
	return true;
}



function funding_capture_card () {
	global $f_paypal;


	$chosen_reward = $_POST['reward'];

	$funding_id = wp_insert_post(array(
			'post_parent' => filter_var($chosen_reward, FILTER_SANITIZE_NUMBER_INT),
			'post_type' => 'funder',
			'post_status' => 'publish',
			'post_content' => filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS),
	));
	$ammount = filter_var($_POST['ammount'], FILTER_SANITIZE_NUMBER_INT);

	update_post_meta($funding_id, 'funder', array(
			'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
			'email' => filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL)
	));

	update_post_meta($funding_id, 'funding_amount', floatval($ammount), true);

	update_post_meta($funding_id, 'funding_method', 'stripe' , true);

	update_post_meta($funding_id, 'stripe_card_data', $_POST['token'] , true);
	//preapproval managed, now update the base

	$funder = get_post($funding_id); //funder post
	$reward = get_post($funder->post_parent); //the reward post
	$project = get_post($reward->post_parent); //the project post

	$project_settings = (array) get_post_meta($project->ID, 'settings', true);
	$notified = get_post_meta($funder->ID, 'notified', true);

	global $f_currency_signs;

	$project_currency_sign = $f_currency_signs[$project_settings['currency']];
	if (empty($notified)){
	// Email the funder and the author
		$author = get_userdata($project->post_author);
		$rewards = get_children(array(
			'post_parent' => $project->ID,
			'post_type' => 'reward',
			'order' => 'ASC',
			'orderby' => 'meta_value_num',
			'meta_key' => 'funding_amount',
		));
		$funders = array();
		$funded_amount = 0;
		$chosen_reward = null;
		foreach($rewards as $this_reward){
			$these_funders = get_children(array(
			'post_parent' => $this_reward->ID,
			'post_type' => 'funder',
			'post_status' => 'publish'
			));
			foreach($these_funders as $this_funder){
				$funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
				$funders[] = $this_funder;
				$funded_amount += $funding_amount;
			}
		}
		$site = site_url();
		$funder_details = get_post_meta($funder->ID, 'funder', true);
		$funding_owner = get_user_by("ID", $funder->post_author);
		$funding_amount = get_post_meta($funder->ID, 'funding_amount', true);
		$preapproval_key = get_post_meta($funder->ID, 'preapproval_key',true);


				if (strpos( $project_settings['date'] , "/") !== false) {
	  				$parseddate = str_replace('/' , '.' , $project_settings['date']);
				}else{
					$parseddate = $project_settings['date'];
				}
				$project_expired = strtotime($parseddate) < time();

				if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){
				$timing = esc_html__('< 24', 'funding');
				}else{
            	$timing = F_Controller::timesince(time(), strtotime($parseddate), 1, '');
				}


             	if(strpos(F_Controller::timesince(time(), strtotime($parseddate), 1, ''), "hour")){
            	 $timingtext =  esc_html__('hours to go', 'funding');
            	}else{

            		if(F_Controller::timesince(time(), strtotime($parseddate), 1, '') == 1){
            			$timingtext =  esc_html__('day to go', 'funding');
            		}else{
            			$timingtext = esc_html__('days to go', 'funding');
            		}
               	}
		/*
		if(round($funded_amount/$project_settings['target']*100) >= 100){
					$body3 = of_get_option('s2a');
                	$body3 = wordwrap(sprintf(
                    $body3,
                    $author->user_nicename, // $author->display_name
                    $project->post_title,
                    $project_currency_sign.$funded_amount,
                    round($funded_amount/$project_settings['target']*100),
                    $project_currency_sign.$project_settings['target'],
                    $timing. ' '. $timingtext,
                     get_permalink($project->ID),
	                get_bloginfo('name'),
	                site_url()
                ), 75);

              funding_send_mail($author->ID, sprintf(esc_html__('Yay! Your project %s has been successfully funded', 'fundingpress'), $project->post_title), $body3);

				foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                'post_parent' => $this_reward->ID,
                'post_type' => 'funder',
                'post_status' => 'publish'
                ));
	                foreach($these_funders as $this_funder){
	                	$funder_info = get_post_meta($this_funder->ID, 'funder', true);
						$body4 = of_get_option('s2a');
	                	$body4 = wordwrap(sprintf(
	                    $body4,
	                    $funder_info['name'], // $author->display_name
	                    $project->post_title,
	                      get_permalink($project->ID),
		                get_bloginfo('name'),
		                site_url()
	                	), 75);
	                   funding_send_mail($funder_info['email'], sprintf(esc_html__('Yay! Project %s has been successfully funded', 'fundingpress'), $project->post_title), $body4);
						update_post_meta($funder->ID, 'notified', 1);
	                }
              	}
				}
				*/

		$body = of_get_option('f2a');
                $body = wordwrap(sprintf(
                    $body,
                    $author->user_nicename, // $author->display_name
                    ucfirst($funder_details['name']),
                    $project->post_title,
                    $project_currency_sign.$funded_amount,
                    round($funded_amount/$project_settings['target']*100),
                    $project_currency_sign.$project_settings['target'],
                    $timing. ' '. $timingtext,
                    $funder->ID,
                    $funder_details['name'],
                    $funder_details['email'],
                    $project_currency_sign.$funding_amount,
                    $preapproval_key,
                    $reward->post_title,
                    $funder->post_content
                ), 75);

      funding_send_mail($author->ID, sprintf(esc_html__('New Funder For %s', 'fundingpress'), $project->post_title), $body);

		 $body1 = of_get_option('f2f');
	          $body1 = wordwrap(sprintf(
	                $body1,
	                $funder_details['name'],
	                $project->post_title,
	                round($funded_amount/$project_settings['target']*100),
	                $project_currency_sign.$project_settings['target'],
	                $timing. ' ' .$timingtext,
	                $funder->ID,
	                $funder_details['name'],
	                $funder_details['email'],
	                $funding_amount,
	                $preapproval_key,
	                $reward->post_title,
	                $funder->post_content,
	                get_permalink($project->ID),
	                get_bloginfo('name'),
	                site_url()
	            ),75);
              //$body1 = esc_html__("Thank you for funding", 'fundingpress').' '.$project->post_title.'!';

              funding_send_mail($funder_details['email'], sprintf(esc_html__('Thanks For Funding %s', 'fundingpress'), $project->post_title.$i), $body1);
		 update_post_meta($funder->ID, 'notified', 1);
  }
	  $url = add_query_arg('thanks', 1, get_post_permalink($project->ID));
	echo "ok.".$url;
	die();

}


function funding_ajax_login(){

    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
		$info['remember'] = false;
		if (!empty($_POST['rememberme'])) {
			if (strlen($_POST['rememberme']) > 3) {
				$info['remember'] = true;
			}
		}
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password.', "fundingpress")));
    } else {
        echo json_encode(array('loggedin'=>true,  'message'=>esc_html__('Login successful, redirecting...', "fundingpress")));
    }

    die();
}

function funding_frontend_charge_funder() {
	if (function_exists('method_charge_funder')) {
		method_charge_funder();
	}
	wp_die();
}


function funding_excerpt_more($more) {
     global $post;
	return '...';
}

function funding_oembed_comments( $comment )
{
    add_filter( 'embed_oembed_discover', '__return_false', 999 );

    $comment = $GLOBALS['wp_embed']->autoembed( $comment );

    remove_filter( 'embed_oembed_discover', '__return_false', 999 );

    return $comment;
}

function funding_be_gravatar_filter($avatar, $id_or_email, $size = 150, $default = true, $alt = false) {

    if (is_int($id_or_email)) {
    	if(function_exists('wsl_get_stored_hybridauth_user_profiles_by_user_id'))
		$user_data = wsl_get_stored_hybridauth_user_profiles_by_user_id($id_or_email);

		if(isset($user_data[0]->photourl) && !empty($user_data[0]->photourl)){
    		$custom_avatar = $user_data[0]->photourl;

		}else{
        	$custom_avatar = get_the_author_meta('profile_pic', $id_or_email);

		}
    } else {
    	$user = get_user_by('email', $id_or_email);
        $custom_avatar = get_the_author_meta('profile_pic',$user->data->ID);
    }

    if ($custom_avatar)
        $return = '<img src="'.esc_url($custom_avatar).'" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'" />';
    elseif ($avatar)
        $return = '<img src="'.get_template_directory_uri().'/img/defaults/default_user.jpg" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'" />';


    return $return;
}


function funding_notify_user_on_publish( $new_status, $old_status, $post ) {
    if ( $new_status !== 'publish' || $old_status === 'publish' )
        return;

    if ( $post->post_type !== "project" )
        return;

    funding_send_mail($post->post_author, esc_html__('New project submit', 'fundingpress'), $mail_test );

}


?>