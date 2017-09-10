<?php
// Include the origin controller
require_once (dirname(__FILE__).'/lib/Controller.php');
require_once (dirname(__FILE__).'/paypal.php');
require_once (dirname(__FILE__).'/globals.php');
require_once (dirname(__FILE__).'/admin.php');
function siteorigin_funding_activate(){
    F_Controller::action_init();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'siteorigin_funding_activate');
/**
 * Front end controller
 */
class F_Controller extends Origin_Controller{
      public function __construct(){
        return parent::__construct(false, 'f');
    }
    static function single($class){
    	 if(empty($class)) $class = __CLASS__;
        return parent::single(__CLASS__);
    }
    ///////////////////////////////////////////////////////////////////
    // Action Functions
    ///////////////////////////////////////////////////////////////////
    function action_init(){
        global $f_paypal;
        if(empty($_REQUEST['mode']) or empty($_REQUEST['app_id']) or empty($_REQUEST['api_username']) or empty($_REQUEST['api_password']) or empty($_REQUEST['api_signature']) or empty($_REQUEST['email'])){
            $f_paypal = get_option('funding_paypal');
            $mode = $f_paypal["mode"];
            $appid = $f_paypal["app_id"];
            $appusername = $f_paypal["api_username"];
            $apppassword = $f_paypal["api_password"];
            $appsingature = $f_paypal["api_signature"];
            $email = $f_paypal["email"];
            //sneak in wepay data
            $wepay_account_id = $f_paypal['wepay-account_id'];
        }else{

            $mode = $_REQUEST['mode'];
            $appid = $_REQUEST['app_id'];
            $appusername = $_REQUEST['api_username'];
            $apppassword = $_REQUEST['api_password'];
            $appsingature = $_REQUEST['api_signature'];
            $email = $_REQUEST['email'];
            //sneak in wepay data
            $wepay_account_id = $_REQUEST['wepay-account_id'];
        }

        if(empty($f_paypal)){
        	if(!isset($mode_wepay))$mode_wepay='';
			if(!isset($wepay_client_id))$wepay_client_id='';
			if(!isset($wepay_client_secret))$wepay_client_secret='';
			if(!isset($wepay_access_token))$wepay_access_token='';
            $f_paypal = array(
                'mode' => $mode,
                'app_id' => $appid,
                'api_username' => $appusername,
                'api_password' => $apppassword,
                'api_signature' => $appsingature,
                'email' => $email,
                'mode_wepay' => $mode_wepay,
                'wepay-client_id' =>$wepay_client_id,
                'wepay-client_secret' =>$wepay_client_secret,
                'wepay-access_token' =>$wepay_access_token,
                'wepay-account_id' => $wepay_account_id
            );
            update_option('funding_paypal', $f_paypal);
        }
        $f_paypal = get_option('funding_paypal');
        define(
            'X_PAYPAL_API_BASE_ENDPOINT',
            $f_paypal['mode'] == 'sandbox' ? 'https://svcs.sandbox.paypal.com/' : 'https://svcs.paypal.com/'
        );
        // This is dirty, but the Paypal API likes constants
        define('SOCF_API_USERNAME', $f_paypal['api_username']);
        define('SOCF_API_PASSWORD', $f_paypal['api_password']);
        define('SOCF_API_SIGNATURE', $f_paypal['api_signature']);
        define('SOCF_APPLICATION_ID', $f_paypal['app_id']);
        // Some more PayPal settings
        define('X_PAYPAL_ADAPTIVE_SDK_VERSION','PHP_SOAP_SDK_V1.4_MODIFIED');
        define('X_PAYPAL_REQUEST_DATA_FORMAT','SOAP11');
        define('X_PAYPAL_RESPONSE_DATA_FORMAT','SOAP11');
        // Create project custom post type


		global $f_paypal;
    if ((isset($_GET['scope'])) AND (isset($_GET['code']))) {
    	//stripe connected, handle and redirect
    	if (isset($_GET['code'])) { // Redirect w/ code
    		define('TOKEN_URI', 'https://connect.stripe.com/oauth/token');
  			define('AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize');
		    $code = $_GET['code'];
		    $token_request_body = array(
		      'client_secret' => rtrim($f_paypal['stripe-client_secret']),
		      'grant_type' => 'authorization_code',
		      'client_id' => rtrim($f_paypal['stripe-client_id']),
		      'code' => $code,
		    );
		    $req = curl_init(TOKEN_URI);
			curl_setopt($req, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 0);
		    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($req, CURLOPT_POST, true );
		    curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
		    // TODO: Additional error handling
		    $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
		    $resp = json_decode(curl_exec($req), true);
        $url = funding_get_permalink_for_template( 'tmp-my-account' );
			if (isset($resp['access_token'])) {
				update_user_meta(get_current_user_id(), 'stripe_data', $resp);
				?>
				<script>
				window.location = "<?php echo $url; ?>";
				</script>
				<?php
			} else {
				?>
				<script>
				window.location = "<?php echo $url; ?>";
				</script>
				<?php
			}
	  	} else if (isset($_GET['error'])) { // Error
        ?>
        <script>
        window.location = "<?php echo $url; ?>";
        </script>
        <?php
	  	}
	}



    }
    /**
     * Render the project page.
     */
    function action_template_redirect(){
        global $post;
        if(is_single() && $post->post_type == 'project'){
            $step = isset($_GET['step']) ? intval($_GET['step']) : 0;
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
            global $f_currency_signs;
            $project_currency_sign = $f_currency_signs[$project_settings['currency']];
            $rewards = get_children(array(
                'post_parent' => $post->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));
            if(!empty($rewards)){
                $keys = array_keys($rewards);
                $lowest_reward = $keys[0];
                $funding_minimum = get_post_meta($lowest_reward, 'funding_amount', true);
            }
            // Get all funders
            $funders = array();
            $funded_amount = 0;
            $chosen_reward = null;
            foreach($rewards as $reward){
                $these_funders = get_children(array(
                    'post_parent' => $reward->ID,
                    'post_type' => 'funder',
                    'post_status' => 'publish'
                ));
                foreach($these_funders as $this_funder){
                    $funding_amount = get_post_meta($this_funder->ID, 'funding_amount', true);
                    $funders[] = $this_funder;
                    $funded_amount += $funding_amount;
                }
            }


            if (isset($_GET['preapproval_id'])) {
                if (is_numeric($_GET['preapproval_id'])) {

                    global $wpdb;
                    $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = '".$_GET['preapproval_id']."'", ARRAY_A );
                    if (isset($results[0])) {
                        //well apparently he pledged
                        $theid = $results[0]['post_id'];//the founder ID with the wepay pledge
                        $funder = get_post($theid); //funder post
                        $reward = get_post($funder->post_parent); //the reward post
                        $project = get_post($reward->post_parent); //the project post

                        $project_settings = (array) get_post_meta($project->ID, 'settings', true);
                        $notified = get_post_meta($funder->ID, 'notified', true);

                        global $f_currency_signs;
                        $project_currency_sign = $f_currency_signs[$project_settings['currency']];
                        if (empty($notified)){	// Email the funder and the author
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
						$body4 = of_get_option('s2f');
	                	$body4 = wordwrap(sprintf(
	                    $body4,
	                    $funder_info['name'], // $author->display_name
	                    $project->post_title,
	                      get_permalink($project->ID),
		                get_bloginfo('name'),
		                site_url()
	                	), 75);
	                   funding_send_mail($funder_info['email'], sprintf(esc_html__('Yay! Project %s has been successfully funded', 'fundingpress'), $project->post_title), $body4);

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
                        		//$body = esc_html__("User", 'fundingpress').' '.$funder_details['name'].' '.esc_html__('has funded your project', 'fundingpress').' '.$project->post_title.' '.esc_html__('with', 'fundingpress').' '.$project_currency_sign.$funding_amount;
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
                        		funding_send_mail($funder_details['email'], sprintf(esc_html__('Thanks For Funding %s', 'fundingpress'), $project->post_title), $body1);
                        		update_post_meta($funder->ID, 'notified', 1);
                        }
                        wp_publish_post( $theid );
                        $url = add_query_arg('thanks', 1, get_post_permalink($project->ID));
                        header("Location: ".$url, true, 303);

                    }
                }
            }
            // The chosen reward
            $reward = null;
            $reward_available = 0;
            if(isset($_REQUEST['chosen_reward'])){
                $reward = get_post(intval($_REQUEST['chosen_reward']));
                $reward_funding_amount = get_post_meta($reward->ID, 'funding_amount', true);
                $reward_available = get_post_meta($reward->ID, 'available', true);
            }
            if($project_expired && $step > 0) {
                header('Location: '.get_permalink($post->ID), true, 301);
                exit();
            }

            if($step == 2){
              if (!empty($_REQUEST['name'])) {
                $name = $_REQUEST['name'];
              }
              if (!empty($_REQUEST['email'])) {

                $mail = $_REQUEST['email'];
              }
                $funders = get_posts(array(
                    'numberposts'     => -1,
                    'post_type' => 'funder',
                    'post_parent' => $reward->ID,
                    'post_status' => 'publish'
                ));
                $valid = false;
                $step = 1;
				global $f_paypal;
				$limit = $f_paypal['paypal_limit'];
				if(empty($limit))$limit = 99999999999999999999999;

				$project_currency_sign = $f_currency_signs[$project_settings['currency']];
                if(empty($name)){
                    $message = esc_html__('Please insert your name.', 'fundingpress');
                }
                elseif(empty($mail)){
                    $message = esc_html__('Please insert your e-mail.', 'fundingpress');
                }
				elseif(!is_email($mail)){
                    $message = esc_html__('Please insert valid e-mail.', 'fundingpress');
                }
                elseif(empty($_REQUEST['amount'])){
                    $message = esc_html__('Please choose an amount.', 'fundingpress');
                }
                elseif(floatval($_REQUEST['amount']) < $reward_funding_amount){
                    $message = esc_html__('You need to fund more for this reward.', 'fundingpress');
                    $_REQUEST['amount'] = $reward_funding_amount;
                } elseif(floatval($_REQUEST['amount']) > $limit){
                    $message = esc_html__('Maximum funding amount is: ', 'fundingpress').$project_currency_sign.$limit;
                }
                elseif( count($funders) >= $reward_available){
                    $message = esc_html__('The reward you chose is no longer available.', 'fundingpress');
                } elseif(empty($_REQUEST['fundingmethod'])) {
                  $message = esc_html__('Please choose a funding method.', 'fundingpress');
                } else{
                $temp_post = $post;

		get_header(); ?>
		<?php if (of_get_option('page_header')!=""){ ?>
			<style>
		    html{
		    background-image:url(<?php echo esc_url(of_get_option('page_header')); ?>) !important;
		    background-position:center top !important;
		    background-repeat:  no-repeat !important;
		}
		</style>
		<?php } ?>
			<div class=" page tran_proc">
				<div class="container">
					<span><?php esc_html_e('Please wait while we process your transaction..', 'fundingpress'); ?></span>
				</div>
			</div><!-- container -->

			<?php

				get_footer();
        $post = $temp_post;

                    $valid = true;
                    $step = 2;

                    // Create funder post
                    $funding_id = wp_insert_post(array(
                        'post_parent' => $reward->ID,
                        'post_type' => 'funder',
                        'post_status' => 'draft',
                        'post_content' => $_REQUEST['message'],
                    ));

                    add_post_meta($funding_id, 'funder', array(
                        'name' => $_REQUEST['name'],
                        'email' => $_REQUEST['email']
                    ), true);
                    add_post_meta($funding_id, 'funding_amount', floatval($_REQUEST['amount']), true);

                    if ($_REQUEST['fundingmethod'] == "paypal") {
                        //if funding by paypal
                        // Redirect to PayPal
                        $paypal = new F_PayPal();

                        $funding = get_post($funding_id);

                        // Redirect
                        $url = $paypal->get_auth_url($post, $reward, $funding); ?>

    <script type="text/javascript">
    window.location.href='<?php echo $url; ?>';
    </script>
    <?php
                        add_post_meta($funding_id, 'funding_method', 'paypal' , true);
                        exit;
                    } else {
                        global $f_paypal;

						if ($_REQUEST['fundingmethod'] == "stripe") {


						} else {
                        //not funding by paypal, do the wepay

                        require str_replace("tpl/", "", dirname(__FILE__).'/lib/WePay/wepay.php');
                        // application settings


                        $client_id = $f_paypal['wepay-client_id']; //api key for wepay
                        $client_secret = $f_paypal['wepay-client_secret']; //client secret for wepay
                        $access_token = $f_paypal['wepay-access_token'];
                        $amount = floatval($_REQUEST['amount']);


                        if ($f_paypal['wepay-staging'] != 'Yes')  {
                            // change to useProduction for live environments
                            Wepay::useProduction($client_id, $client_secret);
                        } else {
                            Wepay::useStaging($client_id, $client_secret);
                        }
            						$user_ID1 = $post->post_author; //tusi
            						$token = get_user_meta($user_ID1 ,"wepay_token", true);

                        $wepay = new WePay($token); // Don't pass an access_token for this call
                        $percent = $f_paypal['admin-commission']; //how much do we take
                        // create the pre-approval
                        $returnurl =  $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
                        if (strpos($returnurl, "http://") === false) {
                            $returnurl = "http://".$returnurl;
                        }
                        $totadminamount = $percent /100;
                        $app_fee = $totadminamount*$amount;

                         //if($app_fee < 1){
                          //  $app_fee = 1;
                          //  $amount = $amount - 1;
                        //} tusi

						$account_id = get_user_meta($user_ID1 ,"wepay_account_id", true);
                        $response = $wepay->request('preapproval/create', array(
                            'account_id'         => $account_id,
   							'amount'            => $amount,
   							"currency" =>  	$project_settings['currency'],
   							'app_fee'          => $app_fee,
   							'fee_payer' => "payer",
                            'mode'              => 'regular',
                            'short_description' => esc_html__('Commit funding to project ', 'fundingpress').$post->post_title,
                            'redirect_uri'      => $returnurl,
                            'period'            => 'once'
                        ));
                        add_post_meta($funding_id, 'funding_method', 'wepay' , true);
                        add_post_meta($funding_id, 'wepay_preapproval_id', $response->preapproval_id , true);


?>
    <script type="text/javascript">
        window.location.href='<?php echo esc_url($response->preapproval_uri); ?>';
    </script>
    <?php
                    exit;
                    }
                    }
                }
            }
            $templates = array(
                0 => 'f-project.php',
                1 => 'f-fund-project.php',
                2 => 'f-user-details.php',
            );
            $template = $templates[$step];
            $file = locate_template($template);
            if(empty($file)) $file = dirname(__FILE__).'/tpl/'.$template;
            // Include the CSS and Javascript
            if(file_exists(STYLESHEETPATH.'/f/f.css')) wp_enqueue_style('fundingpress', get_stylesheet_directory_uri().'/f/f.css');
            elseif(file_exists(TEMPLATEPATH.'/f/f.css')) wp_enqueue_style('fundingpress', get_template_directory_uri().'/f/f.css');
            else wp_enqueue_style('fundingpress',get_template_directory_uri().'/funding/tpl/f.css');
            if(file_exists(STYLESHEETPATH.'/f/f.js')) wp_enqueue_script('fundingpress', get_stylesheet_directory_uri().'/f/f.js', array('jquery'));
            elseif(file_exists(TEMPLATEPATH.'/f/f.js')) wp_enqueue_script('fundingpress', get_template_directory_uri().'/f/f.js', array('jquery'));
            else wp_enqueue_script('fundingpress', get_template_directory_uri().'/funding/tpl/f.js', array('jquery'));
            if($template == ""){include(dirname(__FILE__) .'/404.php');}else{
            include($file);}
            do_action('wp_shutdown');
            exit();
        }
    }
    /**
     * Handle IPN from PayPal
     */
    function method_paypal_ipn(){
        $this->method_funded();
    }
    /**
     * Handle a user returning from PayPal
     */

    function method_funded($funder_id = null){
		if(empty($funder_id)) $funder_id = intval($_REQUEST['funder_id']);
        $paypal = new F_PayPal();
        $funder = get_post($funder_id);
        // Check authentication and update the funder status
        $auth = $paypal->check_auth($funder);
        $reward = get_post($funder->post_parent);
        $project = get_post($reward->post_parent);
        $project_settings = (array) get_post_meta($project->ID, 'settings', true);
        $notified = get_post_meta($funder->ID, 'notified', true);
        global $f_currency_signs;
        $project_currency_sign = $f_currency_signs[$project_settings['currency']];
        if($auth && empty($notified)){
            // Email the  and the author
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

              funding_send_mail($author->ID, sprintf(esc_html__('Yay! Your project %s has been successfully funded', 'fundingpress'), $project->post_title.$i), $body3);

				foreach($rewards as $this_reward){
                $these_funders = get_children(array(
                'post_parent' => $this_reward->ID,
                'post_type' => 'funder',
                'post_status' => 'publish'
                ));
	                foreach($these_funders as $this_funder){
	                	$funder_info = get_post_meta($this_funder->ID, 'funder', true);
						$body4 = of_get_option('s2f');
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
        header("Location: ".$url, true, 303);
    }
    ///////////////////////////////////////////////////////////////////
    // Support functions
    ///////////////////////////////////////////////////////////////////
    /**
    * Returns a string representation of the time between $time and $time2
    *
    * @param int $time A unix timestamp of the start time.
    * @param int $time2 A unix timestamp of the end time.
    * @param int $precision How many parts to include
    */
    static function timesince($time, $time2 = null, $precision = 2, $separator = ' '){
	    if(empty($time2)) $time2 = time();

	    $time_diff = $time2 - $time;
	    return ceil(abs($time2 - $time) / 86400);
	}
    static function get_funders($project_id){
        $rewards = get_children(array(
            'post_parent' => $project_id,
            'post_type' => 'reward',
            'order' => 'ASC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'funding_amount',
        ));
        $funders = array();
        foreach($rewards as $this_reward){
            $these_funders = get_children(array(
                'post_parent' => $this_reward->ID,
                'post_type' => 'funder',
                'post_status' => 'publish'
            ));
            $funders = array_merge($funders, (array) $these_funders);
        }
        return $funders;
    }
}
if(!isset($class))$class='';
F_Controller::single($class);




function method_charge_funder(){

  if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'charge_nonce')) return false;

  $project = get_post($_REQUEST['project_id']);
  if((!current_user_can('edit_post', $_REQUEST['project_id'])) AND (get_current_user_id() != $project->post_author)) {
    print json_encode(array(
      'status' => 'fail',
      'message' => esc_html__("Cannot withdraw!", "funding"),
    ));
  }

  if($project->post_type != 'project') return false;
  $project_settings = get_post_meta($project->ID, 'settings', true);

  $funder = get_post($_REQUEST['funder_id']);

  header('Content-Type: application/json', true);

  if (get_post_meta($funder->ID, "funding_method", true) == "paypal") {
    try{
      $paypal = new F_PayPal();

      $test = $paypal->charge_funder($funder);

      print json_encode(array(
        'status' => 'success',
        'amount' => get_post_meta($funder->ID, 'funding_amount', true),
      ));
    }
    catch(Exception $e){
      print json_encode(array(
        'status' => 'fail',
        'message' => $e->getMessage(),
      ));
    }
  } else {

    if (get_post_meta($funder->ID, "funding_method", true) == "wepay") {
    //it's wepay time to process stuff!
    try {
        global $f_paypal;
      $preapproval = get_post_meta($funder->ID, "wepay_preapproval_id", true); // <-------------- preapproval id

      $reward = get_post($funder->post_parent);

      if ($reward->post_type == "reward") {
        $mainpost = get_post($reward->post_parent);
      } else {
        $mainpost = $reward;
      }
      $userid = $mainpost->post_author;

      $account_id = get_user_meta($userid,"wepay_account_id", true);          // <----------- account id          // <----------- account id
      $othertoken = get_user_meta($userid,"wepay_token", true);
      if (!$account_id) {
        print json_encode(array(
        'status' => 'fail',
        'message' => esc_html__("Invalid account id", 'fundingpress'),
        ));
        die();
      }

      $amount = get_post_meta($funder->ID, 'funding_amount', true);

      require str_replace("tpl/", "", dirname(__FILE__).'/lib/WePay/wepay.php');
      $client_id = $f_paypal['wepay-client_id']; //api key for wepay
      $client_secret = $f_paypal['wepay-client_secret']; //client secret for wepay
      $access_token = $f_paypal['wepay-access_token'];
      $percent = $f_paypal['admin-commission']; //how much do we take
      if ($f_paypal['wepay-staging'] != 'Yes')  {
        // change to useProduction for live environments
        Wepay::useProduction($client_id, $client_secret);
      } else {
        Wepay::useStaging($client_id, $client_secret);
      }

              $totadminamount = $percent /100;
              $app_fee = $totadminamount*$amount;
              //if($app_fee < 1){
              //    $app_fee = 1;
              //    $amount = $amount - 1;
              //} tusi3

              $wepay = new WePay($othertoken); // Don't pass an access_token for this call

              $response = $wepay->request('checkout/create', array(
                  'account_id'        => $account_id,
                  'amount'            => $amount,
                  'short_description' => esc_html__('Funding to project ', 'fundingpress').$mainpost->post_title,
                  'fee'	=> array('app_fee' => $app_fee , 'fee_payer' => 'payer'),
                  'currency'  =>   $project_settings['currency'],
                  'type'              => 'donation',
                  'payment_method' => array('type' => 'preapproval',  'preapproval' => array('id'  => $preapproval) )
              ));


      if ($response == "You have already captured 1 checkouts with this preapproval") {
        update_post_meta($_REQUEST['funder_id'], 'charged', $amount);
        print json_encode(array(
          'status' => 'success',
          'amount' => get_post_meta($funder->ID, 'funding_amount', true),
        ));

      } elseif ($response->state == "authorized") {
        update_post_meta($_REQUEST['funder_id'], 'charged',$amount);
        print json_encode(array(
          'status' => 'success',
          'amount' => get_post_meta($funder->ID, 'funding_amount', true),
        ));
      } else {
        print json_encode(array(
          'status' => 'fail',
          'response' => $response
          ));
      }


    }catch(Exception $e){
      if ($e->getMessage() == "You have already captured 1 checkouts with this preapproval") {
        update_post_meta($_REQUEST['funder_id'], 'charged', $amount);
        print json_encode(array(
          'status' => 'success'
        ));
      } else {
        print json_encode(array(
          'status' => 'fail',
          'message' => $e->getMessage(),
          'error_code' => $e->getCode(),
          'account' => $account_id,
          'preapprove' => $preapproval,

        ));
      }

    }

    }else{

    //it's stripe

      try {
        global $f_paypal;
        require dirname(__FILE__).'/lib/Stripe/init.php';
        $stripe = array(
          "secret_key"      => $f_paypal['stripe-client_secret'],
          "publishable_key" => $f_paypal['stripe-publishable']
        );
        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        //calculate app fee
        $amount = get_post_meta($funder->ID, 'funding_amount', true); // in dollahs
        $percent = $f_paypal['admin-commission']; //how much do we take
        $totadminamount = $percent /100;
        $app_fee = $totadminamount*$amount;

        $reward = get_post($funder->post_parent);

        if ($reward->post_type == "reward") {
          $mainpost = get_post($reward->post_parent);
        } else {
          $mainpost = $reward;
        }
        $userid = $mainpost->post_author;

        $stripe_data_owner = get_user_meta($userid, 'stripe_data', true);
        if (empty($stripe_data_owner)) {
          throw new Exception('No strip data on project owner');
        }
        $targetaccount = $stripe_data_owner['stripe_user_id'];


        $card = get_post_meta($funder->ID, 'stripe_card_data', true);

         $charge = \Stripe\Charge::create(array(
          "amount" => ($amount * 100),
                      "currency" => $project_settings['currency'],
                      "application_fee" => ($app_fee * 100),
          "destination" => $targetaccount,
          "source" => $card['id']
          ));

          update_post_meta($funder->ID, 'charged', $amount);
          delete_post_meta($funder->ID, 'tried_on');
          delete_post_meta($funder->ID, 'tries');
          //NotifyOnWithdrawl($_REQUEST['funder_id'], $_REQUEST['project_id'], true);
          print json_encode(array(
            'status' => 'success'
          ));
      }catch (Exception $e) {
        update_post_meta($_REQUEST['funder_id'], 'tried_on', time());
        print json_encode(array(
            'status' => 'fail',
            'message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'account' => $account_id,
            'preapprove' => $preapproval,

          ));
      }


    }




  }


  return true;
}
