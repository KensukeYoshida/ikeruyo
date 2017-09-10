<?php

class FAdmin_Controller extends Origin_Controller{
	public function __construct(){
		return parent::__construct(false, 'fa');
	}

	static function single($class = ''){
		return parent::single(__CLASS__);
	}

	////////////////////////////////////////////////////////////
	// Action Functions
	/////////////////////w///////////////////////////////////////

	function action_admin_menu(){
		add_submenu_page(
			'edit.php?post_type=project',
			esc_html__('Funding Settings', 'fundingpress'),
			'Funding',
			'edit_posts',
			'funding-settings',
			array(__CLASS__, 'page_settings')
		);
	}

	/**
	 * Enqueue admin scripts
	 */
	function action_admin_enqueue_scripts(){
		global $pagenow, $post;
		if(isset($post->post_type) && $post->post_type == 'project'){
			wp_enqueue_style('f-admin', get_template_directory_uri().'/funding/admin/css/admin.css');
		}

		if(($pagenow == 'post.php' || $pagenow == 'post-new.php') && @ $post->post_type == 'project'){


            wp_enqueue_script('jquery-ui', '//code.jquery.com/ui/1.10.1/jquery-ui.js', array('jquery'));


            wp_enqueue_script('jquery.json', get_template_directory_uri().'/funding/admin/js/jquery.json.min.js', array('jquery'));
            wp_enqueue_script('f-project', get_template_directory_uri().'/funding/admin/js/project.js', array('jquery'));

			$project_settings = get_post_meta($post->ID, 'settings', true);

			wp_localize_script('f-project', 'fundingpress', array(
				'site_url' => site_url(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'charge_nonce' => wp_create_nonce('charge_nonce'),
				'currency' => isset($project_settings['currency']) ? $project_settings['currency'] : 'USD',
			));
		}
	}

	/**
	 * Donate modal in the footer, for when the user successfully funds a project
	 */
	function action_admin_footer(){
		global $pagenow, $post;
		if(($pagenow == 'post.php' || $pagenow == 'post-new.php') && @ $post->post_type == 'project'){
			include(dirname(__FILE__).'/admin/modal-donate.php');
		}
	}



	/**
	 * Delete the children when we delete a project or rewards
	 *
	 * @param int $post_id The post ID.
	 */
	function action_delete_post($post_id){
		$post = get_post($post_id);
		if($post->post_type == 'project'){
			$rewards = get_children(array(
				'post_parent' => $post->ID,
				'post_type' => 'reward',
			));
			if(empty($rewards)) return;
			foreach($rewards as $reward){
				wp_delete_post($reward->ID);
			}
		}
		elseif($post->post_type == 'reward'){
			$funders = get_children(array(
				'post_parent' => $post->ID,
				'post_type' => 'funder',
			));
			if(empty($funders)) return;
			foreach($funders as $funder){
				wp_delete_post($funder->ID);
			}
		}
	}

	/**
	 * Save the post
	 */
	function action_save_post($post_id){
		global $post;
		if(!isset($post))return;
		if(!isset($post->ID))return;
		if(defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  return;
		if(empty($post) || $post->ID != $post_id) return;
		if($post->post_type != 'project') return;
		if(!current_user_can('edit_post', $post_id)) return;
		if(@$_REQUEST['action'] == 'trash') return;

		$rewards = json_decode(stripslashes($_POST['rewards']), true);
		$deleted = json_decode(stripslashes($_POST['rewards_deleted']), true);

		if(!empty($deleted)) { foreach($deleted as $to_delete){
			wp_delete_post($to_delete, false);
		}}

		if(!empty($rewards)) { foreach($rewards as $id => $reward){
			if(substr($id, 0, 4) == 'new-'){
				// Create a new reward
				$id = wp_insert_post(array(
					'post_title' => $reward['title'],
					'post_parent' => $post_id,
					'post_content' => $reward['description'],
					'post_type' => 'reward',
					'post_status' => 'publish',
					'comment_status' => 'open'
				));

				update_post_meta($id, 'available', intval($reward['available']), true);
				update_post_meta($id, 'funding_amount', intval($reward['amount']), true);
			}
			else{

				// Update an existing reward
				$post = get_post(intval($id));

				if($post->post_type != 'reward') continue;
				wp_update_post(array(
					'ID' => $post->ID,
					'post_title' => $reward['title'],
					'post_content' => $reward['description'],
				));
				update_post_meta($id, 'reward', array(
					'available' => $reward['available'],
					'amount' => $reward['amount']
				));
				update_post_meta($id, 'available', $reward['available']);
				update_post_meta($id, 'funding_amount', $reward['amount']);
			}
		}

	}
	/*
	else{
			 $id = wp_insert_post(array(
	                    'post_title' => esc_html__('No reward', 'fundingpress'),
	                    'post_parent' => $post_id,
	                    'post_content' => esc_html__('I don\'\t want reward!', 'fundingpress'),
	                    'post_type' => 'reward',
	                    'post_status' => 'publish',
	                    'comment_status' => 'open'
	                ));
	                update_post_meta($id, 'available', 9999999999, true);
	                update_post_meta($id, 'funding_amount', 0, true);
		}
		*/
		// Update the settings
		global $f_currencies;
		$funders = F_Controller::get_funders($post_id);
		$project_settings = get_post_meta($post_id, 'settings', true);
        add_post_meta($post_id, 'datum',  $_POST['f_target_date'], true );

		$new = array(
			'date' =>	$_POST['f_target_date'],
			'target' => floatval($_REQUEST['f_target_amount']),
		);
		if(empty($funders)) $new['currency'] = isset($f_currencies[$_REQUEST['f_target_currency']]) ? $_REQUEST['f_target_currency'] : 'USD';
		else $new['currency'] = $project_settings['currency'];

		update_post_meta($post_id, 'settings', $new);
		update_post_meta($post_id, 'datum', $_POST['f_target_date']);

	}

	/**
	 *
	 */
	function action_admin_init(){
		add_meta_box(
			'project_rewards',
			esc_html__( 'Rewards', 'fundingpress' ),
			array(__CLASS__, 'metabox_rewards'),
			'project'
		);

		add_meta_box(
			'project_settings',
			esc_html__( 'Project Settings', 'fundingpress' ),
			array(__CLASS__, 'metabox_settings'),
			'project',
			'side'
		);

            add_meta_box(
            'project_funders',
            esc_html__( 'Funders', 'fundingpress' ),
            array(__CLASS__, 'metabox_funders'),
            'project'
        );

	}




	////////////////////////////////////////////////////////////
	// Meta Boxes and Their Handlers
	////////////////////////////////////////////////////////////

	function metabox_rewards(){
		global $post;
		$project = $post;

		$rewards = get_children(array(
			'post_parent' => $project->ID,
			'post_type' => 'reward',

			'order' => 'ASC',
			'orderby' => 'meta_value_num',
			'meta_key' => 'funding_amount',
		));

		//
		$rewards_keyed = array();
		foreach($rewards as $reward){
			$funding_amount = get_post_meta($reward->ID, 'funding_amount', true);
			$available = get_post_meta($reward->ID, 'available', true);


			$rewards_keyed[$reward->ID] = array(
				'title' => $reward->post_title,
				'description' => $reward->post_content,
				'amount' => !empty($funding_amount) ? floatval($funding_amount) : 0,
				'available' => intval($available) == 0 ? esc_html__('Unlimited', 'fundingpress') : intval($available)
			);
		}

		?><script><?php print 'var rewards = ' . (empty($rewards_keyed) ? '{}' : json_encode($rewards_keyed)).';'; ?></script><?php

		$project_settings = (array) get_post_meta($post->ID, 'settings', true);
		if(empty($project_settings['currency'])) $project_settings['currency'] = 'USD';
		global $f_currency_signs;
		$project_currency_sign = $f_currency_signs[$project_settings['currency']];

		include(dirname(__FILE__).'/admin/metabox-reward.php');
	}

	/**
	 * Targets for the project
	 */
	function metabox_settings(){
		global $post;

		$funders = F_Controller::get_funders($post->ID);

		$settings = get_post_meta($post->ID, 'settings', true);
		$settings = array_merge(array(
			'currency' => 'USD',
			'date' => date('m/d/Y', time() + 86400*14),
			'target' => 1000,
		), (array) $settings);

		include(dirname(__FILE__).'/admin/metabox-settings.php');
	}

	function metabox_funders(){
		global $post;
		$project = $post;
		$funders = F_Controller::get_funders($project->ID);

		$project_settings = (array) get_post_meta($post->ID, 'settings', true);
		global $f_currency_signs;
		$project_currency_sign = !empty($project_settings['currency']) ? $f_currency_signs[$project_settings['currency']] : '$';

		// Check if this project is ready for funding
		$ready = true;

		include(dirname(__FILE__).'/admin/metabox-funders.php');
	}

	/**
	 * render and process the settings page
	 */
	public static function page_settings(){
		global $f_paypal;
		if(isset($_REQUEST['submit']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'funding_settings')){
			// The signature
			if(is_email($_REQUEST['email'])) $f_paypal['email'] = $_REQUEST['email'];
			$f_paypal['mode'] = $_REQUEST['mode'];
			$f_paypal['app_id'] = $_REQUEST['app_id'];
			$f_paypal['api_username'] = $_REQUEST['api_username'];
			$f_paypal['api_password'] = $_REQUEST['api_password'];
			$f_paypal['api_signature'] = $_REQUEST['api_signature'];
			$f_paypal['paypal_limit'] = $_REQUEST['paypal_limit'];


			$f_paypal['mode_wepay'] = $_REQUEST['mode_wepay'];
			$f_paypal['wepay-client_id'] = $_REQUEST['wepay-client_id'];
			$f_paypal['wepay-client_secret'] = $_REQUEST['wepay-client_secret'];
			$f_paypal['wepay-access_token'] = $_REQUEST['wepay-access_token'];
			$f_paypal['wepay-account_id'] = $_REQUEST['wepay-account_id'];
            $f_paypal['wepay-staging'] = $_REQUEST['wepay-staging'];
            $f_paypal['admin-commission'] = $_REQUEST['admin-commission'];

			$f_paypal['stripe-client_id'] = $_REQUEST['stripe-client_id'];
            $f_paypal['stripe-client_secret'] = $_REQUEST['stripe-client_secret'];
            $f_paypal['stripe-publishable'] = $_REQUEST['stripe-publishable'];

			update_option('funding_paypal', $f_paypal);
			?><div id="updated" class="updated"><p><strong><?php esc_html_e('Settings saved.', 'fundingpress') ?></strong></p></div><?php
		}

		include(dirname(__FILE__).'/admin/page-settings.php');
	}

	////////////////////////////////////////////////////////////
	// Method handlers
	////////////////////////////////////////////////////////////

	/**
	 * Charge the user with the amount they comitted to fund.
	 */

	/**
	 * Export funders to a CSV
	 */
	function method_export_funders(){
		if(!current_user_can('edit_post', $_REQUEST['project'])) return false;
		if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'export_funders')) return false;

		$project = get_post($_REQUEST['project']);
		if($project->post_type != 'project') return false;
		$project_settings = get_post_meta($project->ID, 'settings', true);

		header("HTTP/1.0 200 OK", true, 200);
		header('Content-Type: text/csv', true);
		header('Content-Disposition: attachment; filename="funders.csv"', true);

		$csv = fopen('php://output', 'w');
		fputcsv($csv, array(
			'name',
			'email',
			'project',
			'project_id',
			'reward',
			'reward_id',
			'currency',
			'funding_amount',
			'funding_method',
			'paypal_email',
			'stripe method',
			'wepay_preapproval_id',
			'preapproval_key',
			'charged',
		));

		$funders = F_Controller::get_funders($project->ID);

		foreach($funders as $funder){
			$reward = get_post($funder->post_parent);
			$funder_info = get_post_meta($funder->ID, 'funder', true);

			$charged = get_post_meta($funder->ID, 'charged', true);
			$stripe_data = get_post_meta($funder->ID, 'stripe_card_data', true);
			if(!empty($stripe_data)){
				$stripe_meta = get_post_meta($funder->ID, 'stripe_card_data', true);
				$stripe = $stripe_meta['card']['brand'];
			}else{
				$stripe = '';
			}
			fputcsv($csv, array(
				$funder_info['name'],
				$funder_info['email'],
				$project->post_title,
				$project->ID,
				$reward->post_title,
				$reward->ID,
				$project_settings['currency'],
				get_post_meta($funder->ID, 'funding_amount', true),
				get_post_meta($funder->ID, 'funding_method', true),
				get_post_meta($funder->ID, 'paypal_email', true),
				$stripe,
				get_post_meta($funder->ID, 'wepay_preapproval_id', true),
				get_post_meta($funder->ID, 'preapproval_key', true),
				!empty($charged) ? 'true' : 'false',
			));
		}

		return true;
	}
}

FAdmin_Controller::single();
