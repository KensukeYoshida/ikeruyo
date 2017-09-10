<!DOCTYPE html>
<html <?php language_attributes(); ?>><head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <?php  global $page, $paged, $post, $woocommerce; ?>

    <?php include_once 'css/colours.php'; ?>
      <script type="text/javascript">
        var templateDir = "<?php echo esc_url(get_template_directory_uri()); ?>";
		var ajaxurl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
        var premiumoption = "<?php echo esc_attr(of_get_option('premium_supporters')); ?>";
        var formatdatuma = "<?php echo esc_attr(funding_date_format_php_to_js(get_option('date_format'))); ?>";
    </script>

<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
<div class="main_wrapper">
<?php $i = of_get_option('facebook_btn') ? 1 : 0; ?>
<?php $j = of_get_option('twitter_btn') ? 1 : 0; ?>
<?php $k = of_get_option('google_btn') ? 1 : 0; ?>

<?php if($i + $j + $k  == 1){$btnclass = 'one_btn';} ?>
<?php if($i + $j + $k  == 2){$btnclass = 'two_btn';} ?>
<?php if($i + $j + $k  == 3){$btnclass = 'three_btn';} ?>

<header>
    <div class="navbartop-wrapper" >
        <div class="container">
        <div class="search-wrapper">
            <ul class="social-media">
                <?php if ( of_get_option('facebook') ) { ?><li><a target="_blank" class="facebook"href="<?php echo esc_url(of_get_option('facebook_link')); ?>"><?php esc_html_e("facebook", 'fundingpress'); ?></a></li><?php } ?>
                <?php if ( of_get_option('twitter') ) { ?><li><a target="_blank" class="twitter" href="<?php echo esc_url(of_get_option('twitter_link')); ?>"><?php esc_html_e("twitter", 'fundingpress'); ?></a></li><?php } ?>
                <?php if ( of_get_option('rss') ) { ?><li><a target="_blank" class="rss" href="<?php echo esc_url(of_get_option('rss_link')); ?>"><?php esc_html_e("rss", 'fundingpress'); ?></a></li><?php } ?>
                <?php if ( of_get_option('googleplus') ) { ?> <li><a target="_blank" class="google" href="<?php echo esc_url(of_get_option('google_link')); ?>"><?php esc_html_e("google", 'fundingpress'); ?></a></li><?php } ?>
                <?php if ( of_get_option('instagram') ) { ?> <li><a target="_blank" class="instagram" href="<?php echo esc_url(of_get_option('instagram_link')); ?>"><?php esc_html_e("instagram", 'fundingpress'); ?></a></li><?php } ?>
                <?php if ( of_get_option('skype') ) { ?><li><a target="_blank" class="skype" href="skype:<?php echo esc_url(of_get_option('skype_name')); ?>?add"><?php esc_html_e("skype", 'fundingpress'); ?></a></li><?php } ?>
            </ul>
        </div>
        <div class="top-right">
           <?php if ( is_user_logged_in() ) { ?>
                <a href="<?php echo esc_url(wp_logout_url( home_url())) ?>" class="logout-top "><i class="fa fa-sign-out" aria-hidden="true"></i> <?php esc_html_e("Log out", 'fundingpress'); ?></a>
                <?php  $user = wp_get_current_user();

                if(in_array('administrator', $user->roles)){ ?>
                <a href="<?php echo esc_url(admin_url()).'post-new.php?post_type=project' ?>" class="submit-top"><i class="fa fa-fire" aria-hidden="true"></i> <?php esc_html_e("Submit a project", 'fundingpress'); ?></a>
                <?php }else{ ?>
                <a href="<?php echo funding_get_permalink_for_template('tmp-submit-project'); ?>" class="submit-top"><i class="fa fa-fire" aria-hidden="true"></i> <?php esc_html_e("Submit a project", 'fundingpress'); ?></a>
                <?php } ?>
                <a href="<?php echo funding_get_permalink_for_template( 'tmp-my-account' );  ?>" class="account-top "><i class="fa fa-user" aria-hidden="true"></i> <?php esc_html_e("My account", 'fundingpress'); ?></a>
           <?php }else{ ?>
           		<a href="#myModalR" role="button" class="register-top" data-toggle="modal"><i class="fa fa-lock" aria-hidden="true"></i> <?php esc_html_e("Register", 'fundingpress'); ?></a>

                <a href="#myModalL" role="button" class="login-top" data-toggle="modal"><i class="fa fa-key" aria-hidden="true"></i> <?php esc_html_e("Login", 'fundingpress'); ?></a>

            <?php } ?>
            <?php include_once 'searchformprojects.php'; ?>


        </div>
 </div><!-- top right -->
</div><!-- Container -->
    <!-- NAVBAR
    ================================================== -->
 <div class="navbar-wrapper navtransparent">
      <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
      <div class="container">
        <div class="logo-wrapper">
             <?php if (of_get_option('logo')!=""){ ?>
                 <a href="<?php  echo esc_url(home_url()); ?>"> <img src="<?php echo esc_url(of_get_option('logo')); ?>" alt="logo"  /> </a>
             <?php } ?>
        </div>
        <div class="navbar navbar-inverse navbar-static-top " role="navigation">

            <div class="navbar-header">

            	<?php if (!is_plugin_active( 'ubermenu/ubermenu.php' )){ ?>
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only"><?php esc_html_e('Toggle navigation', 'fundingpress'); ?></span>
                <span class="fa fa-bars"></span>
              </button>
				<?php } ?>
              <form method="get" id="sformm" action="<?php echo esc_url( site_url( '/' ) ); ?>">
	                <input type="search" autocomplete="off" name="s">
	                <input type="hidden" name="post_type[]" value="post" />
	                <input type="hidden" name="post_type[]" value="page" />
	                <i class="fa fa-search"></i>
	          </form>

            </div>
            <?php if (!is_plugin_active( 'ubermenu/ubermenu.php' )){ ?>
            <div class="navbar-collapse collapse">
			<?php } ?>
                <?php if(has_nav_menu('header-menu')) { ?>
                	<?php if (is_plugin_active( 'ubermenu/ubermenu.php' )){ ?>
              <?php wp_nav_menu( array( 'theme_location'  => 'header-menu', 'depth' => 0,'sort_column' => 'menu_order', 'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>') ); ?>
                	<?php }else{  ?>
                <?php wp_nav_menu( array( 'theme_location'  => 'header-menu', 'depth' => 0,'sort_column' => 'menu_order', 'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>' ) ); ?>
                	<?php } ?>
                <?php }else { ?>

                   <div class="no-menu"><?php esc_html_e('No menu assigned!', 'fundingpress'); ?></div>

                <?php } ?>
                <div class="clear"></div>
             <?php if (!is_plugin_active( 'ubermenu/ubermenu.php' )){ ?>
            </div><!--/.nav-collapse -->
            <?php } ?>


                  <?php if ($woocommerce) { if(is_woocommerce()){ ?>
	                    <div class="cart-outer">
	                        <div class="cart-menu-wrap">
	                            <div class="cart-menu">
	                                <a class="cart-contents" href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>"><div class="cart-icon-wrap"><i class="fa fa-shopping-cart"></i> <?php  esc_html_e('Your cart.', 'crystalskull'); ?><div class="cart-wrap"><span><?php echo esc_attr($woocommerce->cart->cart_contents_count); ?> </span></div> </div></a>
	                            </div>
	                        </div>

	                        <div class="cart-notification">
	                            <span class="item-name"></span> <?php  esc_html_e('was successfully added to your cart.', 'crystalskull'); ?>
	                        </div>

	                         <!-- If woocommerce -->
		                <?php if ($woocommerce) { if(is_woocommerce()){ ?>
		                        <?php
		                            // Check for WooCommerce 2.0 and display the cart widget
		                            if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
		                                the_widget( 'WC_Widget_Cart', 'title= ' );
		                            } else {
		                                the_widget( 'WooCommerce_Widget_Cart', 'title= ' );
		                            }
		                        ?>

		                 <?php }} ?>
                 			<!-- Endif woocommerce -->

                       </div>
						<?php }} ?>
            <div class="clear"></div>

          <div class="clear"></div>
   </div>

      </div> <!-- /.container -->
    </div><!-- /.navbar-wrapper -->

<?php if(is_page_template('tmp-home.php') or is_page_template('tmp-home-left.php') or is_page_template('tmp-home-right.php') or is_page_template('tmp-home-news.php') or is_page_template('tmp-no-title.php')){}elseif(is_search()){ ?>

<div class="container">
        <div class="col-md-12">

        </div>
</div>
</div>
<?php }else{ ?>
<!--<div class="page-title">
    <div class="container">
            <div class="col-md-12">
                <div class="title-page">
                    <h1><?php
                 if ( is_plugin_active( 'woocommerce/woocommerce.php' )){
                    if (is_shop()){ echo get_the_title(funding_get_ID_by_slug ('shop'));}
                    else{ if(is_tag()){esc_html_e("Tag: ",'fundingpress');echo get_query_var('tag' ); }elseif(is_author()){esc_html_e("Author: ",'fundingpress');echo get_the_author_meta('user_login', get_query_var('author' ));}elseif(is_archive()){ ?>
				  	<?php if ( is_day() ) : ?>
				        <?php printf( esc_html__( 'Daily Archives: %s', 'fundingpress' ), get_the_date() ); ?>
				    <?php elseif ( is_month() ) : ?>
				        <?php printf( esc_html__( 'Monthly Archives: %s', 'fundingpress' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'fundingpress' ) ) ); ?>
				    <?php elseif ( is_year() ) : ?>
				        <?php printf( esc_html__( 'Yearly Archives: %s', 'fundingpress' ), get_the_date( _x( 'Y', 'yearly archives date format', 'fundingpress' ) ) ); ?>
				    <?php elseif ( is_tax() ) :?>
				        <?php esc_html_e('Category: ', 'fundingpress'); echo get_query_var( 'term' ); ?>
				    <?php else : ?>
				        <?php esc_html_e( 'Blog Archives', 'fundingpress' ); ?>
				    <?php endif; }else{the_title();} }
                 }else{  if(is_tag()){esc_html_e("Tag: ",'fundingpress');echo get_query_var('tag' );}elseif(is_author()){esc_html_e("Author: ",'fundingpress');echo get_the_author_meta('user_login', get_query_var('author' ));}elseif(is_archive()){ ?>
				  	<?php if ( is_day() ) : ?>
				        <?php printf( esc_html__( 'Daily Archives: %s', 'fundingpress' ), get_the_date() ); ?>
				    <?php elseif ( is_month() ) : ?>
				        <?php printf( esc_html__( 'Monthly Archives: %s', 'fundingpress' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'fundingpress' ) ) ); ?>
				    <?php elseif ( is_year() ) : ?>
				        <?php printf( esc_html__( 'Yearly Archives: %s', 'fundingpress' ), get_the_date( _x( 'Y', 'yearly archives date format', 'fundingpress' ) ) ); ?>
				     <?php elseif ( is_tax() ) :?>
				        <?php esc_html_e('Category: ', 'fundingpress'); echo get_query_var( 'term' ); ?>
				    <?php else : ?>
				        <?php esc_html_e( 'Blog Archives', 'fundingpress' ); ?>
				    <?php endif; }else{the_title();} } ?>
            </h1>
              </div>
                <?php if(get_post_type($post->ID) == 'project' && !is_tax()){  ?>
                	<?php
                	 $first = get_the_author_meta('first_name', get_post_field( 'post_author',$post->ID ));
					 $last = 	get_the_author_meta('last_name', get_post_field( 'post_author',$post->ID ));
					 $display = get_the_author_meta('display_name',get_post_field( 'post_author',$post->ID )); ?>
                	<div class="breadcrumbs"><?php esc_html_e('By', 'fundingpress');  ?>
                		<a href="<?php echo esc_url(get_author_posts_url(get_post_field( 'post_author',$post->ID ))); ?>">
                			<?php  if ($first) {
                				echo esc_attr($first); }?>
                      <?php  if ( $last ) {
                      	echo esc_attr($last);
								}?>
						<?php if(empty($last) && empty($first)){
						 echo esc_attr($display);
						}	?>
						</a>
                	</div>
                <?php }elseif(!is_tax()){ ?>
                	<div class="breadcrumbs"><?php funding_breadcrumbs(); ?></div>
                <?php } ?>
            </div>
      </div>
</div>
<?php } ?>-->

</header><!-- /.header -->

<div id="myModalL" class="modal fade <?php if(isset($btnclass)){echo esc_attr($btnclass);} ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	  <div class="modal-content">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3><?php esc_html_e("Login", 'fundingpress'); ?></h3>
		  </div>
		  <div class="modal-body">
		<?php
		if ( is_user_logged_in() ) {
		    global $current_user;
		?>
		<div id="LoginWithAjax">
		    <?php
		        global $current_user;
		        global $user_level;
		        global $wpmu_version;
		    ?>
		    <table cellpadding="0" cellspacing="0" width="100%">
		        <tr>
		            <td class="avatar" id="LoginWithAjax_Avatar">
		                <?php echo get_avatar( $current_user->ID, $size = '50' );  ?>
		            </td>
		            <td>
		                  <a id="wp-logout" href="<?php echo esc_url(wp_logout_url( home_url())) ?>"><?php echo strtolower(esc_html__( 'Log Out', 'fundingpress' )) ?></a><br />
		            </td>
		        </tr>
		    </table>
		</div>
		<?php
		    }else{
		?>
		    <div id="LoginWithAjax" class="default"><?php //ID must be here, and if this is a template, class name should be that of template directory ?>
		        <span id="LoginWithAjax_Status"></span>
		        <?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>
		        <form name="LoginWithAjax_Form" id="LoginWithAjax_Form" method="post" action="login" >
		            <table width='100%' cellspacing="0" cellpadding="0">
									<tr style="display:none" id="LoginMessageContainer">
										<td id="LoginMessage">
										</td>
									</tr>
		                <tr id="LoginWithAjax_Username">
		                    <td class="username_input">
		                        <input type="text" name="username" placeholder="Username" id="login_username" class="input" value="" />
														<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		                    </td>
		                </tr>
										<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		                <tr id="LoginWithAjax_Password">
		                    <td class="password_input">
		                        <input type="password" placeholder="Password" name="password" id="login_password" class="input" value="" />
		                    </td>
		                </tr>
		                <tr><td colspan="2"><?php do_action('login_form'); ?></td></tr>
		                <tr id="LoginWithAjax_Submit">
		                    <td id="LoginWithAjax_SubmitButton">
		                         <input name="rememberme" type="checkbox" id="login_remember" value="forever" /> <label ><?php esc_html_e( 'Remember Me', 'fundingpress' ) ?></label>
		                        <a id="LoginWithAjax_Links_Remember"href="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login')); ?>" title="<?php esc_html_e('Password Lost and Found', 'fundingpress') ?>"><?php esc_html_e('Lost your password?', 'fundingpress') ?></a>
		                        <br /><br />

		                        <input type="submit"  class="button-green button-small"  name="wp-submit" id="lwa_wp-submit" value="<?php esc_html_e('Log In', 'fundingpress'); ?>" tabindex="100" />
								<br />



		                        <input type="hidden" name="redirect_to" value="http://<?php echo esc_attr($_SERVER['SERVER_NAME']) . esc_attr($_SERVER['REQUEST_URI']) ?>" />
		                        <input type="hidden" name="testcookie" value="1" />
		                        <input type="hidden" name="lwa_profile_link" value="<?php echo esc_url($lwa_data['profile_link']); ?>" />
		                    </td>
		                </tr>
		            </table>
		        </form>
		        <form name="LoginWithAjax_Remember" id="LoginWithAjax_Remember" action="<?php echo esc_url(home_url())?><?php echo (!is_plugin_active('better-wp-security/better-wp-security.php')) ? '/wp-login.php?' : '/?'; ?>callback=?&template=" method="post">
		            <table width='100%' cellspacing="0" cellpadding="0">
		                <tr>
		                    <td>
		                        <strong><?php echo esc_html__("Forgotten Password", 'fundingpress'); ?></strong>
		                    </td>
		                </tr>
		                <tr>
		                    <td class="forgot-pass-email">
		                        <?php $msg = esc_html__("Enter username or email", 'fundingpress'); ?>
		                        <input type="text" name="user_login" id="lwa_user_remember" value="<?php echo esc_attr($msg); ?>" onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}" />
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <input type="submit" class="button-green button-small"  value="<?php echo esc_html__("Get New Password", 'fundingpress'); ?>" />
		                          <a href="#" id="LoginWithAjax_Links_Remember_Cancel"><?php esc_html_e("Cancel", 'fundingpress'); ?></a>
		                        <input type="hidden" name="login-with-ajax" value="remember" />

		                    </td>
		                </tr>
		            </table>
		        </form>
		    </div>
		<?php } ?>
		  </div>
	  </div>
	</div>
</div>
<div id="myModalR" class="modal fade <?php if(isset($btnclass)){echo esc_attr($btnclass);} ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3><?php esc_html_e('Register For This Site', 'fundingpress') ?></h3>
		  </div>
		  <div class="modal-body">
		    <div id="LoginWithAjax_Footer">
		        <div id="LoginWithAjax_Register"  class="default">
		                <span id="LoginWithAjax_Register_Status"></span>

		            <form name="LoginWithAjax_Register" id="LoginWithAjax_Register_Form" action="<?php echo esc_url(home_url()); ?>/wp-login.php?action=register&callback=?&template=" method="post">
		                <p>
		                    <label><input type="text" placeholder="Username" name="user_login" id="user_login" class="input" size="20" tabindex="10" /></label>
		                </p>
		                <p>
		                    <label><input type="text" placeholder="E-mail" name="user_email" id="user_email" class="input" size="25" tabindex="20" /></label>
		                </p>
		                <?php do_action('register_form'); ?>
		                <p id="reg_passmail"><?php esc_html_e('A password will be e-mailed to you.', 'fundingpress') ?></p>
		                <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button-green button-small" value="<?php esc_html_e('Register', 'fundingpress'); ?>" tabindex="100" /></p>
		                <input type="hidden" name="lwa" value="1" />
		            </form>
					<form name="LoginWithAjax_Form" id="LoginWithAjax_Form" action="<?php echo esc_url(home_url())?><?php echo (!is_plugin_active('better-wp-security/better-wp-security.php')) ? '/wp-login.php?' : '/?'; ?>callback=?&template=" method="post">
<!--
					<div id="social_login" class="reg" >
					 <p><?php esc_html_e('Or login with:', 'fundingpress'); ?></p>
					  <?php if(of_get_option('facebook_btn')){ ?>
						<a id='facebooklogin' class='button-medium facebookloginb'><i class='fa fa-facebook-square'></i><?php esc_html_e(' connect', 'fundingpress');?></a>
					 <?php } ?>
					   <?php if(of_get_option('twitter_btn')){ ?>
						<a id='twitterlogin' class='button-medium twitterloginb'><i class='fa fa-twitter-square'></i><?php esc_html_e(' connect', 'fundingpress');?></a>
					<?php } ?>
					  <?php if(of_get_option('google_btn')){ ?>
						<a id='googlelogin' class='button-medium googleloginb'><i class='fa fa fa-google-plus-square'></i><?php esc_html_e(' connect', 'fundingpress');?></a>
					<?php } ?>
				</div> -->
					</form>
		        </div>
		    </div>
		  </div>
	  </div>
  </div>
</div>



	<div id="myModalD" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-content">
				  <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				    <h3><?php esc_html_e("Delete", 'fundingpress'); ?></h3>
				  </div>
				  <div class="modal-body">
					<?php esc_html_e('Are you sure you want to delete this project?', 'fundingpress'); ?><br/><br/>
					<script>

						jQuery( document ).ready(function() {
								var id = 0;
								var prid = jQuery('.dproject');
								prid.on( "click", function() {
								   var myClass = jQuery(this).attr("class");
								   myClass = myClass.replace(/\D/g,'');
								   id = parseInt(myClass, 10);

								});

								var conf = jQuery('#conf');
								conf.on( "click", function() {
									delete_project(id);
								});

						});

					</script>

					 <a id="conf" class="delete-button button-small button-red"><?php esc_html_e('Yes', 'fundingpress'); ?></a>

					 <button type="button" class="button-small button-grey" data-dismiss="modal" aria-hidden="true"><?php esc_html_e('No','fundingpress'); ?></button>
				  </div>
				</div>
			</div>
		</div>
	</div>
