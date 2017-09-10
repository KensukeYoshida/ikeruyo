<?php
/*
 * Template name: My account
 */
?>
<?php get_header(); ?>
<?php
$thumb = get_post_thumbnail_id();
$img_url = wp_get_attachment_url( $thumb,'full');
?>
<?php if(!empty($img_url)){ ?>
<style>
    html{
    background-image:url(<?php echo esc_url($img_url); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php }else{ ?>
<?php if (of_get_option('page_header')!=""){ ?>
	<style>
    html{
    background-image:url(<?php echo esc_url(of_get_option('page_header')); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php } ?>
<?php } ?>
<?php if(is_user_logged_in() != 1){wp_redirect( home_url() );} ?>
<div class="profile">
  <div class="container">
    <div class="profile-info row">

        <div class="col-md-3 col-sm-12"><div class="shadow">

              <?php
              $autorpic = get_the_author_meta('profile_pic', get_current_user_id());
              if(!empty($autorpic)){
               $image = aq_resize( $autorpic,  250, 250, true, true, true ); //resize & crop img
              	if (!isset ($image[0])) {
              		$theimage = $autorpic;
              	} else {
              		$theimage = $image;
              	}
               ?><img src="<?php echo esc_url($theimage); ?>" />
               <?php }else{ ?>
               <?php echo get_avatar( get_current_user_id(), 250 );?>
               <?php } ?>

        </div></div>
          <div class="tabbable col-md-9 col-sm-12"> <!-- Only required for left/right tabs -->
                <ul class="nav nav-tabs">
                     <li class="active"><a class="button-small button-green" data-toggle="tab" href="#profile"><?php esc_html_e("My profile", 'fundingpress'); ?></a></li>
                     <li><a class="button-small button-green" data-toggle="tab" href="#profile-edit"><?php esc_html_e("Edit profile", 'fundingpress'); ?></a></li>
                     <li><a class="button-small button-green" data-toggle="tab" href="#funding-edit"><?php esc_html_e("Funding details", 'fundingpress'); ?></a></li>
                </ul>
                 <div class="tab-content">
          <div id="profile" class="tab-pane active">

            <div class="col-md-10 col-sm-12">
            <h1>
           <?php
        if (get_the_author_meta('display_name', get_current_user_id())) {

        	echo esc_attr(get_the_author_meta('display_name', get_current_user_id()));
        }?>

        <?php if (usercountry_name_display(get_current_user_id()) != ""){?>
        	<small><i class="fa fa-map-marker"></i> <?php echo esc_attr(get_the_author_meta('city', get_current_user_id())); ?>, <?php echo esc_attr(usercountry_name_display(get_current_user_id())); ?></small><?php } ?></h1>

			<?php  if (get_the_author_meta('description', get_current_user_id())){ ?>
                <div class="biography"><p><?php echo esc_attr(get_the_author_meta('description', get_current_user_id()));?></p></div>
            <?php } ?>

              <table>
                  <?php
                    if (get_the_author_meta('first_name', get_current_user_id())){ ?>
                  <tr>
					  <td><i class="fa fa-user"></i> &nbsp;<?php esc_html_e("Name", 'fundingpress'); ?></td>
					  <td> <?php echo esc_attr(get_the_author_meta('first_name', get_current_user_id())); if (get_the_author_meta('last_name', get_current_user_id())){
						  echo ' ';echo esc_attr(get_the_author_meta('last_name', get_current_user_id())); }?>
					  </td>
				  </tr>
                    <?php } ?>

                  <?php if (get_the_author_meta('user_registered', get_current_user_id())) { ?>
				  <tr>
					  <td><i class="fa fa-calendar"></i> &nbsp;<?php esc_html_e("Member Since", 'fundingpress'); ?></td>
					  <td><?php echo esc_attr(date_i18n("F Y", strtotime(get_userdata(get_current_user_id()) -> user_registered)));?></td>
				  </tr>
                <?php } ?>
                 <?php   if (get_the_author_meta('user_url', get_current_user_id())) { ?>
				 <tr>
					  <td><i class="fa fa-globe"></i> &nbsp;<?php esc_html_e("Website", 'fundingpress'); ?></td>
					  <td><a target="_blank" href="<?php
						if (get_the_author_meta('user_url', get_current_user_id())) {echo esc_attr(get_the_author_meta('user_url', get_current_user_id()));}?>">
						<?php echo esc_url(get_the_author_meta('user_url', get_current_user_id()));?></a></td>
				 </tr>
                <?php } ?>
              </table>

			</div>   <!-- /.span9 -->

        </div>
        <!-- profile tab end -->
         <div id="profile-edit" class="tab-pane">
          <div class="row">
            <div class="col-md-11 col-sm-12">
            <?php

                global $current_user, $wp_roles;
                /* Load the registration file. */
                require_once (ABSPATH . WPINC . '/registration.php');
                $error = array();
                /* If profile was saved, update profile. */
                if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user') {
                    /* Update user password. */
                    if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
                        if ($_POST['pass1'] == $_POST['pass2'])
                            wp_update_user(array('ID' => $current_user -> ID, 'user_pass' => esc_attr($_POST['pass1'])));
                        else
                            $error[] = esc_html__('The passwords you entered do not match.  Your password was not updated.', 'fundingpress');
                    }
                    /* Update user information. */
                    //website
                    wp_update_user( array ('ID' => $current_user -> ID, 'user_url' => esc_url($_POST['user_url'])) ) ;
                    if (!empty($_POST['email'])) {

                        if (!is_email(esc_attr($_POST['email'])))
                            $error[] = esc_html__('The Email you entered is not valid.  please try again.', 'fundingpress');
                        elseif (trim (email_exists(esc_attr($_POST['email']))) != "" && email_exists(esc_attr($_POST['email'])) != $current_user -> ID)
                            $error[] = esc_html__('This email is already used by another user.  try a different one.', 'fundingpress');
                        else {
                            wp_update_user(array('ID' => $current_user -> ID, 'user_email' => esc_attr($_POST['email'])));
                        }
                    }
					if (!empty($_POST['postImage']))
						update_user_meta($current_user -> ID, 'profile_pic', esc_attr($_POST['postImage']));
                    if(!empty($_POST['usercountry_id']))
                         update_user_meta($current_user -> ID, 'usercountry_id', esc_attr($_POST['usercountry_id']));
                    if (!empty($_POST['first-name']))
                        update_user_meta($current_user -> ID, 'first_name', esc_attr($_POST['first-name']));
                    if (!empty($_POST['last-name']))
                        update_user_meta($current_user -> ID, 'last_name', esc_attr($_POST['last-name']));
                    if (!empty($_POST['description']))
                        update_user_meta($current_user -> ID, 'description', esc_attr($_POST['description']));
                     if (!empty($_POST['city']))
                        update_user_meta($current_user -> ID, 'city', esc_attr($_POST['city']));

                    /* Redirect so the page will show updated info.*/
                    /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
                    if (count($error) == 0) {
                        //action hook for plugins and extra fields saving
                        do_action('edit_user_profile_update', $current_user -> ID);
                        wp_redirect(get_permalink());

                        exit ;
                    }
                }
            ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>">
        <div class="entry-content entry">
            <?php the_content(); ?>
            <?php if ( !is_user_logged_in() ) : ?>
                    <p class="warning">
                        <?php esc_html_e('You must be logged in to edit your profile.', 'fundingpress'); ?>
                    </p><!-- .warning -->
            <?php else : ?>
                <?php
                if (count($error) > 0)
                    echo '<p class="error">' . implode("<br />", esc_attr($error)) . '</p>';
 ?>
                <form method="post" id="adduser" action="<?php the_permalink(); ?>">
                    <fieldset class="form-username">
                        <label for="first-name"><?php esc_html_e('First Name', 'fundingpress'); ?></label>
                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta('first_name', $current_user -> ID); ?>" />
                    </fieldset><!-- .form-username -->
                    <fieldset class="form-username">
                        <label for="last-name"><?php esc_html_e('Last Name', 'fundingpress'); ?></label>
                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta('last_name', $current_user -> ID); ?>" />
                    </fieldset><!-- .form-username -->


                        <fieldset>

                <label for="postImage"><?php esc_html_e('Profile photo:', 'fundingpress') ?></label>



				<?php esc_html_e('Image needs to be at least 250x250px', 'fundingpress') ?>
				<?php $baseurl = get_template_directory_uri().'/include/'; ?>

				 <div id="flash"></div>
					<div id="ajaxresult">


					</div>
					<div id="files">
					<?php
					$photo_user = get_user_meta(get_current_user_id(), 'profile_pic', true);
					if(empty($photo_user))$photo_user = "http://0.gravatar.com/avatar/?s=500&d=mm&r=g 2x";
					if (isset($photo_user)) {
						 echo "<img src='".esc_url($photo_user)."' id='uploadedimage' style='width:100%'>";
					}  ?>
				 </div>
				<span id="me" class="styleall button-small fileinput-button" style=" cursor:pointer;">

						<?php if(isset($photo_user)) {
					esc_html_e('Click Here To Upload New Photo', 'fundingpress');
				} else {
					esc_html_e('Click Here To Upload Photo', 'fundingpress');
				}
				?><input id="meimg" type="file" name="files[]">

				</span>


				<div id="load"></div><span id="mestatus" ></span>


				<div id="status"></div>
				<div id="cropme" style="display:none;"><a class="button-small crop-me"><?php esc_html_e('CROP IMAGE', 'fundingpress'); ?></a></div>

				<div id="cancelme0" style="display:none;"><a class="button-small"><?php esc_html_e('cancel', 'fundingpress'); ?></a></div>


				<div id="cancelme" style="display:none;"><a class="button-small"><?php esc_html_e('cancel', 'fundingpress'); ?></a></div>
                <br>
             <input type="hidden" name="postImage" id="postImage" value=""/>

            </fieldset>
                    <!-- .form-username -->


                    <fieldset class="form-email">
                        <label for="email"><?php esc_html_e('E-mail *', 'fundingpress'); ?></label>
                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta('user_email', $current_user -> ID); ?>" />
                    </fieldset><!-- .form-email -->
                    <fieldset class="form-url">
                        <label for="user_url"><?php esc_html_e('Website', 'fundingpress'); ?></label>
                        <input class="text-input" name="user_url" type="text" id="user_url" value="<?php the_author_meta('user_url', $current_user -> ID); ?>" />
                    </fieldset><!-- .form-url -->
                    <fieldset class="form-password">
                        <label for="pass1"><?php esc_html_e('Password *', 'fundingpress'); ?> </label>
                        <input class="text-input" name="pass1" type="password" id="pass1" />
                    </fieldset><!-- .form-password -->
                    <fieldset class="form-password">
                        <label for="pass2"><?php esc_html_e('Repeat Password *', 'fundingpress'); ?></label>
                        <input class="text-input" name="pass2" type="password" id="pass2" />
                    </fieldset><!-- .form-password -->
                    <fieldset class="form-textarea">
                        <label for="description"><?php esc_html_e('Biographical Information', 'fundingpress') ?></label>
                        <textarea name="description" id="description" rows="3" cols="250"><?php the_author_meta('description', $current_user -> ID); ?></textarea>
                    </fieldset><!-- .form-textarea -->
					<fieldset>
               <?php    $id = $current_user -> ID;
                        $usercountry_id = get_user_meta($id, 'usercountry_id');?>
                        <label for="usercountry_id"><?php esc_html_e('Country', 'fundingpress'); ?></label>
                         <?php   global $wpdb;
                            $table = $wpdb->prefix."user_countries";
                            $countries = $wpdb->get_results("SELECT * FROM $table ORDER BY `name`");
                        ?><select name="usercountry_id">
                        <option value="0"><?php esc_html_e('- Select -','fundingpress') ?></option>
                        <?php
                            foreach ($countries as $country) {
                                $selected="";
                                if ($usercountry_id[0]==$country->id_country) { $selected="selected";}
                                echo '<option '.esc_attr($selected).' value="'.esc_attr($country->id_country).'">'.esc_attr($country->name).'</option>';
                            }?>
                        </select>
						</fieldset>
                         <fieldset class="form-city">
                        <label for="city"><?php esc_html_e('City', 'fundingpress'); ?></label>
                        <input class="text-input" name="city" type="text" id="city" value="<?php the_author_meta('city', $current_user -> ID); ?>" />
						</fieldset>
                    </fieldset><!-- .form-email -->
                    <p class="form-submit">
                        <?php echo esc_attr($referer); ?>
                        <input name="updateuser" type="submit" id="updateuser" class="submit button button-green button-small" value="<?php esc_html_e('Update', 'fundingpress'); ?>" />
                        <?php wp_nonce_field( 'update-user' ) ?>
                        <input name="action" type="hidden" id="action" value="update-user" />
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
            <?php endif; ?>
        </div><!-- .entry-content -->
    </div><!-- .hentry .post -->
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-data">
        <?php esc_html_e('Sorry, no page matched your criteria.', 'fundingpress'); ?>
    </p><!-- .no-data -->
<?php endif; ?>
             </div>
            <!-- /.span9 -->
          </div>
          <!-- /.row -->
        </div>
        <!-- profile tab end -->


	  <div id="funding-edit" class="tab-pane">

		  <div class="row">
			<div class="col-md-11 col-sm-12">

				<form action="" method="POST">
					<fieldset>
					<h4><?php esc_html_e("Funding Settings", 'fundingpress'); ?></h4>
					</fieldset>
					 <?php if(of_get_option('paypal') == '1'){ ?>
					<fieldset>


						<label for="paypal_email"><?php esc_html_e('PayPal Email Address', 'fundingpress'); ?></label>
						 <?php $current_user = wp_get_current_user();

						 	if(isset($_POST['paypal_email']))
						funding_save_user_pp_address($current_user->ID, $_POST['paypal_email']); ?>
					   <input type="text" name="paypal_email" id="paypal_email" class="regular-text" value="<?php echo esc_attr(funding_return_user_pp_address($current_user->ID));?>" />
						   <div class="description">
									<?php print esc_html__('The PayPal email address you want to be paid into.', 'fundingpress') ?>
						   </div>

					</fieldset>
					<?php } ?>
					 <?php if(of_get_option('wepay') == '1'){ ?>
					<fieldset>
					<h4><?php esc_html_e('WePay Credentials', 'fundingpress'); ?></h4>
					<?php
						global $f_paypal;

						$user = get_current_user_id();
						//delete_user_meta($user, 'linked_wepay');
						if (get_the_author_meta('linked_wepay', get_current_user_id())) {

							?>
							<label for="wepay-account_id"><?php esc_html_e('WePay account ID', 'fundingpress') ?></label>
							<input type="text" name="wepayaccount_id" id="wepayaccount_id" class="regular-text" disabled value="<?php echo esc_attr(get_user_meta($user, "wepay_account_id", true)); ?>" />
							<label for="wepay-account_id"><?php esc_html_e('WePay access token', 'fundingpress') ?></label>
							<input type="text" name="wepay-token" id="wepay-token" class="regular-text" disabled value="<?php echo esc_attr(get_user_meta($user, "wepay_token", true)); ?>" />
                           <br />
                           <div class="button-primary button-small button-green" id="WePayUnlink"><?php esc_html_e('Unlink WePay!', 'fundingpress'); ?> </div>
							<?php
						} else {

							?>
							<a id="start_oauth2"><?php esc_html_e('Click here to link your WePay account', 'fundingpress'); ?> </a>

							<script src="https://static.wepay.com/min/js/wepay.v2.js" type="text/javascript"></script>
							<script type="text/javascript">
							<?php

								if ($f_paypal['wepay-staging'] == 'Yes')  {
									echo 'WePay.set_endpoint("stage");';

								} else {
									echo 'WePay.set_endpoint("production");';
								}
								$redirurl = get_site_url();

							?>
							 // stage or production

							WePay.OAuth2.button_init(document.getElementById('start_oauth2'), {
							    "client_id":"<?php echo esc_attr($f_paypal['wepay-client_id']); ?>",
							     "scope":["manage_accounts","collect_payments","view_user","send_money","preapprove_payments"],
							    "user_name":"<?php echo esc_attr(get_the_author_meta('user_login', get_current_user_id())); ?>",
							    "user_email":"<?php echo esc_attr(get_the_author_meta('user_email', get_current_user_id())); ?>",
							    "redirect_uri":"<?php echo esc_url($redirurl); ?>",
							    "top":100, // control the positioning of the popup with the top and left params
							    "left":100,
							    "state":"robot", // this is an optional parameter that lets you persist some state value through the flow
							    "callback":function(data) {
							    	//console.log(data);
									/** This callback gets fired after the user clicks "grant access" in the popup and the popup closes. The data object will include the code which you can pass to your server to make the /oauth2/token call **/
									if (data.code.length !== 0) {
										// send the data to the server
										if (data.wepay_message_type == "oauth2_complete") {
											if (data.code.length > 0) {
												 var ajaxurl ='<?php echo esc_url(home_url()); ?>/wp-admin/admin-ajax.php';
											       jQuery.ajax({
											        type: 'POST',
											        url: ajaxurl,
											        data: {"action": "funding_get_wepay_token", 'code': data.code, 'redirURL': '<?php echo esc_url($redirurl); ?>' },
											        success: function(response) {
											           if (response.substr(0, 2) == "ok") {
											           		location.reload();
											           } else {
											           		//console.log (response);
											           }
											        }
											    });

											}
										}
									} else {
										// an error has occurred and will be in data.error
										//console.log (data);
									}
								}
							});

							</script>

							<?php
						}

					?>


					</fieldset>
					<?php } ?>
					 <?php if(of_get_option('stripe') == '1'){ ?>
						<?php global $f_paypal;
						if(!empty($f_paypal['stripe-client_id'])){ ?>
					 	<fieldset>
					<h4><?php esc_html_e('Stripe Credentials', 'fundingpress'); ?></h4>
					<?php


						$user = get_current_user_id();
						//delete_user_meta($user, 'linked_wepay');
						if (get_the_author_meta('stripe_data', get_current_user_id())) {
							$hold = (get_the_author_meta('stripe_data', get_current_user_id()));

							?>
							<label for="stripe-account_id"><?php esc_html_e('Stripe user ID', 'fundingpress') ?></label>
							<input type="text" name="stripe-account_id" id="stripe-account_id" class="regular-text" disabled value="<?php echo esc_attr($hold['stripe_user_id']); ?>" />

                           <br />
                           <div class="button-primary button-small button-green" id="StripeUnlink"><?php esc_html_e('Unlink Stripe!', 'fundingpress'); ?> </div>
							<?php
						} else {
							  global $f_paypal;
							  define('AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize');
							    $authorize_request_body = array(
							      'response_type' => 'code',
							      'stripe_landing' => 'login',
							      'scope' => 'read_write',
							      'client_id' => $f_paypal['stripe-client_id']
							    );
							    $url = AUTHORIZE_URI . '?' . http_build_query($authorize_request_body);
							    echo "<a href='".esc_url($url)."'>".esc_html__('Connect with Stripe.','fundingpress')."</a>";

						}

					?>


					</fieldset>

					<?php } ?>

					<?php } ?>
					<p class="form-submit">

						<input class="button-primary button-small button-green" type="submit" value="<?php esc_html_e('Save Changes', 'fundingpress'); ?>" name="submit" />
					</p>
				</form>

			</div>
		</div>

        </div>
         <!-- funding setting tab end -->

     </div>
    </div>
  </div>
</div>
<div class="profile-projects">
<div class="container blog">
  <div class="row">
<h2><?php esc_html_e("Projects", 'fundingpress'); ?></h2>
<?php
            global $current_user;
 ?>
    <div class="col-md-12 col-sm-12 isoprblck">
        <?php
          global $post;
          $args = array(
            'post_type'=> 'project',
            'order'    => 'DESC',
            'author' => $current_user->ID,
            'posts_per_page' => -1,
            'post_status' => array( 'pending', 'draft', 'future', 'publish', 'private' )
         );
        $wp_query = new WP_Query( $args);

         if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();

            global $f_currency_signs;
            $project_settings = (array) get_post_meta($post -> ID, 'settings', true);

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
            $target = $project_settings['target'];
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
            }       if(empty($target) or $target == 0){$target = 1;}?>

         <div class="project-card">

              <?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
                ?>
              <div class="project-thumb-wrapper"><a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($image[0]); ?>" /></a></div>
                <?php
                }else{ ?>
                <div class="project-thumb-wrapper"><a href="<?php the_permalink(); ?>"><img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg"></a></div>
                <?php } ?>
             <h5 class="bbcard_name"><a href="<?php the_permalink(); ?>"><?php $title = get_the_title(); echo esc_attr(mb_substr($title, 0,20)); if(strlen($title) > 23){echo '...';}?></a></h5>
            <p> <?php $excerpt = get_the_excerpt();
            echo mb_substr($excerpt, 0, 80);
            echo '...';
             ?></p>

            <div class="progress progress-striped active bar-green"><div style="width: <?php printf(esc_html__('%u%', 'fundingpress'), round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

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
            </ul><div class="clear"></div>

            <?php if(get_post_status($post->ID) == 'publish' && !$project_expired){}else{?>
            <a class="edit-button button-small button-green" href="<?php echo  esc_url(get_site_url()).'/?page_id='.funding_get_ID_by_slug('submit-project').'&pid='.$post->ID.'&edit=true'; ?>"><?php esc_html_e('Edit project', 'fundingpress'); ?></a>
            <a class="dproject delete-button button-small button-red <?php echo esc_attr($post->ID); ?>" href="#myModalD" data-toggle="modal"><?php esc_html_e('Delete project', 'fundingpress'); ?></a>


            <div class="clear"></div>
            <?php } ?>
            <?php
            if(get_post_status($post->ID) == 'pending'){
            	$status = esc_html__('pending', 'fundingpress');
            }elseif(get_post_status($post->ID) == 'publish'){
            	$status = esc_html__('publish', 'fundingpress');
            }elseif(get_post_status($post->ID) == 'draft'){
            	$status = esc_html__('draft', 'fundingpress');
            }

            ?>
            <div id="prostatus"><?php esc_html_e("Project status: ", 'fundingpress'); echo esc_attr($status);?></div>
           <div class="clear"></div>
        </div>
        <!-- /.blog-post -->
   <?php endwhile; else: ?><div class="no-pr"> <?php esc_html_e('You don\'t have any projects!', 'fundingpress');?><div class="clear"></div></div><?php endif; ?>
        <div class="clear"></div>
    </div>
    <!-- /.span12 -->

 </div>
  <!-- /.row -->
</div>
<!-- /.container -->
</div> <!-- /.profile -->
<?php get_footer(); ?>