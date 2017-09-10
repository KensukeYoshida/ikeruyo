<?php

/*
 * This is the default template for rendering a funding page.
 *
 * The user chooses the amount they want to fund and the reward they'd like.
 */
global $f_paypal ;
?>

<?php get_header(); the_post(); ?>
<?php if (of_get_option('page_header')!=""){ ?>
	<style>
    html{
    background-image:url(<?php echo esc_url(of_get_option('page_header')); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php } ?>

<div class=" blog">
	<div class="container">
	<div id="primary">
		<div id="content" role="main">

			<?php if(!empty($message)) : ?>
				<div id="form-error-message"><?php print esc_attr($message); ?></div>
			<?php endif; ?>

			<form id="funding-form" method="post" action="<?php print add_query_arg(array('step' => 2), get_post_permalink()) ?>">
				<div class="col-md-7">
								<h3 style="margin-top:0px;"><?php esc_html_e('How much would you like to contribute?', 'fundingpress'); ?></h3>
				<ul id="project-rewards-list">
					<li>
						<span><?php echo esc_attr($project_currency_sign); ?></span>
						<input type="text" name="amount" id="field-amount" value="<?php if(isset($_REQUEST['amount'])) echo esc_attr(@$_REQUEST['amount']); ?>" />
						<div class="clear"></div>
					</li>
				</ul>
            <div class="rewardnasubmitu1">
				<h3><?php esc_html_e('Choose Your Reward', 'fundingpress'); ?></h3>
				<ul id="project-rewards-list" class="perks-wrapper">
					<?php foreach($rewards as $reward) : ?>
						<?php
							$reward_funding_amount = get_post_meta($reward->ID, 'funding_amount', true);
							$reward_available = get_post_meta($reward->ID, 'available', true);
							$funders = get_posts(array(
								'numberposts'     => -1,
								'post_type' => 'funder',
								'post_parent' => $reward->ID,
								'post_status' => 'publish'
							));
						?>

						<?php if(empty($reward_available) || count($funders) < $reward_available) : ?>
							<li class="perk">
								<label for="<?php print 'reward-'.$reward->ID ?>">
									<input class="chosen_reward" type="radio" name="chosen_reward" value="<?php echo esc_attr($reward->ID); ?>" data-minfund='<?php echo $reward_funding_amount; ?>' data-left='<?php echo $reward_available; ?>' id="<?php echo 'reward-'.esc_attr($reward->ID); ?>" <?php checked($reward->ID, @$_REQUEST['chosen_reward']) ?> />
									<input type="hidden" name="chosen_reward_value" value="<?php echo esc_attr($reward_funding_amount); ?>" id="reward_funding_amount" />
									<div class="funding-perk-content">
										<h5><?php print $reward->post_title ?></h5>
										<?php if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { ?>
                                           <div class="min-amount"><strong><?php printf(esc_html__('Pledge %s%s or more', 'fundingpress'), $project_currency_sign, number_format($reward_funding_amount, 2));?></strong></div>
                                        <?php } else { ?>
                                           <div class="min-amount"><strong><?php printf(esc_html__('Pledge %s%s or more', 'fundingpress'), $project_currency_sign, money_format('%.2n', $reward_funding_amount));?></strong></div>
                                        <?php } ?>
										<p><?php echo esc_attr($reward->post_content); ?></p>
									</div>
									<div class="clear"></div>
								</label>
							</li>
						<?php endif; ?>

					<?php endforeach ?>
				</ul>
				</div>
				<div class="who-are-you">
					<?php
					if (is_user_logged_in()) {
						//there is a logged in user
						$user = wp_get_current_user();
						$prefill_name = get_user_meta(get_current_user_id(), 'first_name', true). " ".get_user_meta(get_current_user_id(), 'last_name', true);
						$prefill_mail = $user->data->user_email;
					}

					?>

					<h3><?php esc_html_e('Who Are You?', 'fundingpress'); ?></h3>
					<dl>
						<lh><label for="field-name"><?php esc_html_e('Your Name', 'fundingpress') ?></label></lh>
						<dt><input type="text" name="name" id="field-name" value="<?php
						if (isset($prefill_name)) {
							echo esc_attr($prefill_name);
						} else {
							echo esc_attr(@$_REQUEST['name']);
							}
					 ?>" /></dt>



						<lh><label for="field-email"><?php esc_html_e('Your Email', 'fundingpress') ?></label></lh>
						<dt><input type="text" name="email" id="field-email" value="<?php
							if (isset($prefill_mail)) {
								echo esc_attr($prefill_mail);
							} else {
								echo esc_attr(@$_REQUEST['email']);
								}
						 ?>" /></dt>

					</dl>
				</div>
				<div class="funding-method">
				<h3><?php esc_html_e('Choose a funding method', 'fundingpress'); ?></h3>
					<dl>
						<lh><label for="fundingmethod"><?php esc_html_e('Please select your funding method', 'fundingpress') ?></label></lh>
						<ul id="funding_methods">
						 <?php if(of_get_option('paypal') == '1'){ ?>
						<li class="perk"><input type="radio" name="fundingmethod" value="paypal" id="funding-paypal" <?php if (isset($_REQUEST['fundingmethod'])) {if ($_REQUEST['fundingmethod'] == "paypal") {echo "checked";}} else { echo "checked"; } ?>/>&nbsp;<?php esc_html_e('PayPal', 'fundingpress') ?></input></li>
						<?php } ?>
						 <?php if(of_get_option('wepay') == '1'){
								$wepay_accepts = array('USD', 'CAD', 'GBP' );
								if (in_array($project_settings['currency'], $wepay_accepts)) {
									?>
									<li class="perk"><input type="radio" name="fundingmethod" value="wepay" id="funding-wepay"  <?php if (isset($_REQUEST['fundingmethod'])) {if ($_REQUEST['fundingmethod'] == "wepay") {echo "checked";}} ?>/>&nbsp;<?php esc_html_e('WePay', 'fundingpress') ?></input></li>
									<?php
								}
							 ?>
						<?php } ?>
						<?php if(of_get_option('stripe') == '1'){ ?>
						<li class="perk"><input type="radio" name="fundingmethod" value="stripe" id="funding-stripe"  <?php if (isset($_REQUEST['fundingmethod'])) {if ($_REQUEST['fundingmethod'] == "stripe") {echo "checked";}} ?>/>&nbsp;<?php _e('Stripe', 'fundingpress') ?> <span>Visa, Mastercard, American Express</span></input></li>
						<?php } ?>
						</ul>

					</dl>
				</div>
				<div class="clear"></div>
				</div>
				<div class="col-md-4">

					<h3 style="margin-top:0px; margin-bottom:15px;"><?php esc_html_e('You are helping fund:', 'fundingpress'); ?></h3>

					<div class="project-card col-md-4">
                      <div class="project-thumb-wrapper">
            	<?php
              	$autorpic = get_the_author_meta('profile_pic', get_current_user_id());
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
			<?php if(get_the_author_meta('first_name',get_the_author_meta('ID')) or get_the_author_meta('last_name',get_the_author_meta('ID'))){ ?><span><?php esc_html_e("by", 'fundingpress'); ?> <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_attr(get_the_author_meta('first_name',get_the_author_meta('ID')).' '.get_the_author_meta('last_name',get_the_author_meta('ID'))); ?></a></span> <?php } ?>
            <p> <?php
                $excerpt = get_the_excerpt();
                echo mb_substr($excerpt, 0,80);echo '...';
             ?></p>

            <?php
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
            }?>
             <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?>%" class="bar"></div></div>


            <ul class="project-stats">
                <li class="first funded">
                     <strong><?php printf(esc_html__('%u%%', 'fundingpress'), round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?></strong><?php esc_html_e('funded', 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                        <?php print $project_currency_sign; print number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e('target', 'fundingpress'); ?>
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
                <?php  if (of_get_option('important_text')!=""){ ?>
			       <div class="notice">
					<h6 class="important"><span class="highlight"><?php esc_html_e('Important', 'fundingpress'); ?></span></h6>

                    <?php echo of_get_option('important_text'); ?>

					</div>
                    <?php } ?>

                    <?php if(of_get_option('paypal') == '1'){ ?>
			       <a href="#" style="float:left;" onclick="javascript:window.open('https://www.paypal.com/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/paypal_payment.jpg" border="0" alt="Solution Graphics"></a>
					<?php } ?>

					<?php if(of_get_option('wepay') == '1'){ ?>
					<a href="#" style="float:left;" onclick="javascript:window.open('https://www.wepay.com/about');"><img  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/WePay_Logo.png" border="0" alt="WePay"></a>
					<?php } ?>

					<?php if(of_get_option('stripe') == '1'){ ?>
					<a href="#" style="float:left;" onclick="javascript:window.open('https://stripe.com/about');"><img  src="<?php echo esc_url(get_template_directory_uri()); ?>/img/stripe_logo.png" border="0" alt="Stripe"></a>
					<?php } ?>

				<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<h3 class="funding-comments-title"><?php esc_html_e('Comment', 'fundingpress'); ?></h3>
				<p><?php esc_html_e('If you selected a reward that involve received an item, please write the address in the box bellow. You can also use it if you want to tell something to the creator of the project.', 'fundingpress'); ?></p>
				<ul id="funding-comments">
					<li>
						<textarea name="message" id="input_message"><?php if(isset($_REQUEST['message'])) echo esc_attr(@$_REQUEST['message']); ?></textarea>
					</li>
				</ul>


				 <?php if(of_get_option('paypal') == '1' or of_get_option('wepay') == '1' or of_get_option('stripe') == '1'){ ?>
				<div class="submit">
					<input id="submit_button" type="submit" class="button-green button-medium button-contribute" value="<?php esc_html_e('Commit To Funding', 'fundingpress') ?>" />
				</div>
				<?php } ?>
				<div id="funding-information">
					<?php include(dirname(__FILE__).'/info.php') ?>
				</div>


			</form>

		</div><!-- #content -->
	</div><!-- #primary -->
</div>
</div>
<?php
	if(of_get_option('stripe') == '1'){
		//stripe enabled, write checkers
		require str_replace("tpl/", "", dirname(__FILE__).'/lib/Stripe/init.php');
		if(!isset($f_paypal['stripe-client_secret'])) $f_paypal['stripe-client_secret'] = '';
		if(!isset($f_paypal['stripe-publishable'])) $f_paypal['stripe-publishable'] = '';
			$stripe = array(
			"secret_key"      => $f_paypal['stripe-client_secret'],
			"publishable_key" => $f_paypal['stripe-publishable']
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>
	var handler = StripeCheckout.configure({
		key: '<?php echo esc_attr($f_paypal['stripe-publishable']); ?>',
		image: '<?php echo get_template_directory_uri(); ?>/img/marketplace.png',
		locale: 'auto',
		currency: '<?php echo esc_attr($project_settings ['currency']); ?>',
		bitcoin: true,
		alipay: true,
		alipayReusable: true,
		closed: function() {

		},
		token: function(token) {
			// Use the token to create the charge with a server-side script.
			// You can access the token ID with `token.id`jQuery.ajax({
			jQuery.post( "<?php echo admin_url('admin-ajax.php'); ?>", { 'action': 'funding_capture_card', 'token': token, 'reward' : jQuery('input[name=chosen_reward]:checked', '#funding-form').val(), 'text': jQuery("#input_message").val(), 'name': jQuery("#field-name").val(), 'mail': jQuery("#field-email").val(), 'ammount': jQuery("#field-amount").val()  }, function( data ) {
		 if (data.trim().substr(0,2) == "ok") {

				window.location = data.trim().substr(3);
		 } else {

		 }
		});
		}
	});

	jQuery( document ).ready(function() {
		jQuery('#submit_button').click(function(e) {
			var proceed = true;

			if (!(jQuery('input[name=chosen_reward]:checked', '#funding-form').data('left') > 0)) {
				proceed = false;
			}
			if ((jQuery('input[name=chosen_reward]:checked', '#funding-form').data('minfund') > jQuery("#field-amount").val())) {
				proceed = false;
			}

			if ((jQuery('input[name=fundingmethod]:checked', '#funding-form').val() == "stripe") && (proceed == true)) {
				e.preventDefault();
				handler.open({
					name: 'Support <?php echo esc_attr($post->post_title); ?>',
					description: '<?php echo esc_html__('Commit funding to project ', 'fundingpress').esc_attr($post->post_title); ?>',
					amount: (jQuery("#field-amount").val() * 100)
				});
			}
		});
		// Open Checkout with further options

	});

	// Close Checkout on page navigation
	jQuery(window).on('popstate', function() {
		handler.close();
	});
</script>
<?php
	}
?>
<?php get_footer() ?>
