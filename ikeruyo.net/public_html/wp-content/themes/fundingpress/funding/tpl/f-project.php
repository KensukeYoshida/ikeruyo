<?php

/**
 * This is the default template for rendering a single project page
 */

?>
<?php get_header(); the_post(); global $post, $new_childpost_id; ?>
<?php if (of_get_option('page_header')!=""){ ?>
	<style>
    html{
    background-image:url(<?php echo esc_url(of_get_option('page_header')); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php } ?>

<?php

	$new_childpost_id = get_post_meta($post->ID, '_comment_holder_id', true);

	if (strlen($new_childpost_id) == 0) {

		$new_post = array(
		  'post_content'   => 'Comment holder for post: '.$post->post_title,
		  'post_title'     => 'Comment holder for post: '.$post->post_title,
		  'post_status'    => 'publish',
		  'post_type'      => 'comments_holder',
		  'post_parent'    => $post->ID,
		  'comment_status' => 'open'
		);
		 $new_post_id =  wp_insert_post( $new_post, false );
		 update_post_meta($post->ID, '_comment_holder_id', $new_post_id);
		 $new_childpost_id = $new_post_id;
	} ?>
<div class="page project-page">


    <div class="tabbable"> <!-- Only required for left/right tabs -->

      <div class="fund-tabs-cont">
	      <ul class="nav nav-tabs container">
	        <li class="active"><a class="button-small button-green" data-toggle="tab" href="#tab1"><?php esc_html_e('Home', 'fundingpress');?></a></li>
	        <li><a data-toggle="tab" href="#backers"><?php esc_html_e('Backers', 'fundingpress');?></a></li>
	        <li><a href="#updates" data-toggle="tab"><?php esc_html_e('Updates', 'fundingpress'); ?> <strong class='ccounter' id="updates_counter"><?php $comments_count = wp_count_comments( $post->ID ); echo esc_attr($comments_count->approved); ?></strong></a></li>
            <?php
            function comnum(){
			  	global $new_childpost_id;
				$blah = wp_count_comments($new_childpost_id);
				echo esc_attr($blah->approved);
			}   ?>

            <li><a href="#comments" data-toggle="tab"><?php esc_html_e('Comments', 'fundingpress'); ?> <strong class='ccounter' id="comments_counter"><?php comnum(); ?></strong></a></li>
	      </ul>
      </div>
      <div class="container">
		<?php  global $f_currency_signs;
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
            $target = $project_settings['target']; ?>

                 <?php if(!empty($_GET['thanks'])) : ?>
            <div class="cf-thanks">
               <div class="alert alert-success">
                    <?php esc_html_e('Thanks for committing to fund our project. We appreciate your support.', 'fundingpress') ?>
                    <?php printf(esc_html__("We'll contact you when we reach our target of %s%s.", 'fundingpress'), $project_currency_sign, round($project_settings['target'])) ?>
                </div>
            </div>
        <?php endif; ?>


        <?php if($funded_amount == $target or $funded_amount > $target){ ?>
                  <div class="alert alert-success yay">
                    <strong><?php esc_html_e('Yay! This project has been successfully funded!', 'fundingpress') ?></strong>
                 </div>
                <?php }elseif(isset($project_expired) && $project_expired == 1){ ?>

                  <div class="alert alert-error yay">
                      <strong><?php esc_html_e("Unfortunately this project hasn't been funded on time!", 'fundingpress') ?></strong>
                  </div>

                <?php }?>

      <div class="tab-content">
        <div id="tab1" class="tab-pane active">

            <div class="col-md-8">

               <?php if(get_post_meta($post->ID, '_smartmeta_video-link-field', true) == ""){ ?>
            <?php if(!has_post_thumbnail()){ ?>
               <div class="project-thumb-wrapper-big"><img src="<?php echo esc_url(get_template_directory_uri()).'/img/defaults/default_project_big.jpg'?>" /></div>
            <?php }else{
            	   $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 850, 530, true, '', true ); //resize & crop img

            	?>
                <div class="project-thumb-wrapper-big"><img src="<?php echo esc_url($image[0]); ?>" /></div>
            <?php } ?>
               <?php }else{ echo get_post_meta($post->ID, '_smartmeta_video-link-field', true);} ?>


              <div class="w_container">
              <div id="story" class="project-content ">
                  <?php the_content(); ?>
              </div>

                    </div>
              <!-- project-content -->
            </div>
            <!-- /.col-md-8 -->
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
                if(!empty($rewards)){
                $keys = array_keys($rewards);
                $lowest_reward = $keys[0];
                $funding_minimum = get_post_meta($lowest_reward, 'funding_amount', true);}else{
                $lowest_reward = 0;
                $funding_minimum = get_post_meta($lowest_reward, 'funding_amount', true);}

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


        </div> <!-- /.tab1 -->


          <div id="backers" class="tab-pane">
			<script>
			jQuery(document).ready(function($) {

			var site_url = "<?php echo site_url(); ?>",
			charge_nonce = "<?php echo wp_create_nonce('charge_nonce'); ?>",
			currency = "<?php isset($project_settings['currency']) ? $project_settings['currency'] : 'USD'; ?>";

			jQuery('#collect-funding').on( "click", function() {
			if(confirm('Are you sure you want to collect funding?')){
			var total = jQuery('#project-funders .project-backer').length;
			var done = 0;
			var totalCollected = 0;


			jQuery('#project-funders .project-backer').each(function(){
				var $$ = jQuery(this);
				$$.find('.loader').css('opacity', 0.2).show().fadeIn();

				jQuery.post(
					'<?php echo  admin_url( 'admin-ajax.php' ); ?>',{
						'action' : 'charge_funder',
						'funder_id' : $$.attr('data-funder-id'),
						'project_id' : $$.attr('data-project-id'),
						'_wpnonce' : charge_nonce
				},
					function(data){

						$$.find('.loader').fadeOut();

						if(data.status == 'success'){
							totalCollected += Number(data.amount);
							$$.append(jQuery('<div class="icon charged">').fadeIn());
						}
						else if(data.status == 'fail'){
							alert (JSON.stringify(data));

							var icon = jQuery('<a class="icon charged_error" href="#" />')
								.fadeIn()
								.click(function(){
									alert(data.message);
									return false;
								});
							$$.append(icon);
						}

						done++;
						if(done == total){

						}

					}

				); window.setTimeout('location.reload()', 3000);
			});

		}
		return false;

	});

	});
			</script>
            <div class="col-md-8">
            	<?php if(get_the_author_meta( 'ID' ) == get_current_user_id()){ ?>
	            	<?php if(of_get_option('user_collect') == '1'){ ?>
	            		<?php if(of_get_option('collect_funding') == '1'){ ?>
	            			<?php if($funded_amount == $target or $funded_amount > $target){ ?>
				            	<div class="cf-notice">
				            		<p><?php esc_html_e("Please use the button bellow to collect funds", "fundingpress"); ?></p>
				            		<a id="collect-funding" class="button-medium"><i class="fa fa-money" aria-hidden="true"></i> <?php esc_html_e("Collect funds", "fundingpress"); ?></a>
				            	</div>
		            		<?php } ?>
		            	<?php }else{ ?>
		            	<div class="cf-notice">
		            		<p><?php esc_html_e("Please use the button bellow to collect funds", "fundingpress"); ?></p>
		            		<a id="collect-funding" class="button-medium"><i class="fa fa-money" aria-hidden="true"></i> <?php esc_html_e("Collect funds", "fundingpress"); ?></a>
		            	</div>
		            	<?php } ?>
					<?php } ?>
				<?php } ?>
                <div id="project-funders">
                	<?php if(!empty($funders)){ ?>
                    <?php foreach($funders as $funder) : ?>
                        <?php
                            $funder_info = get_post_meta($funder->ID, 'funder', true);
                            $amount = get_post_meta($funder->ID, 'funding_amount', true);
                            $reward = get_post($funder->post_parent);
                            $charged = get_post_meta($funder->ID, 'charged', true);

                        ?>

                        <div class="project-backer col-lg-3" data-funder-id="<?php echo esc_attr($funder->ID); ?>" data-project-id="<?php echo get_the_ID(); ?>">
                            <div class="pb-img">
                            	<?php if(get_the_author_meta( 'ID' ) == get_current_user_id()){ ?>
                                <a href="mailto:<?php echo esc_attr($funder_info['email']); ?>" title="<?php printf(esc_html__('Email %s', 'fundingpress'), $funder_info['name']) ?>">
                                    <?php print get_avatar($funder_info['email'], 85) ?>
                                </a>

                                <div class="loader"></div>
                            </div>
                            <div class="pb-info">
                            	<div class="name"><?php echo esc_attr($funder_info['name']); ?></a></div>
                            	<span class="amount"><?php echo esc_attr($project_currency_sign.$amount); ?></span>
								<?php if(of_get_option("rewards") == 1){ ?> -
	                                <span class="reward"><?php echo esc_attr($reward->post_title); ?></span>
	                                <?php } ?>
									<?php }else{ ?>
									<?php print get_avatar($funder_info['email'], 85) ?>
                                    <div class="name"><?php echo esc_attr($funder_info['name']); ?></div>
                                    <span class="amount"><?php echo esc_attr($project_currency_sign.$amount); ?></span>
                                    <?php if(of_get_option("rewards") == 1){ ?> -
	                                <span class="reward"><?php echo esc_attr($reward->post_title); ?></span>
	                                <?php } ?>

									<?php } ?>
                            </div>



                            <?php if(!empty($charged)) : ?>
                                <div class="icon charged"></div>
                            <?php endif; ?>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach; }else{?> <div class="no-backers"><?php esc_html_e('No backers yet!', 'fundingpress'); ?> </div> <?php } ?>
                    <div class="clear"></div>
                </div>
             </div>
            <!-- /.col-md-8 -->
        	</div><!-- backer tab end -->
            <div id="updates" class="tab-pane col-md-8">

                           <?php comments_template('/short-comments-update.php'); ?>

                </div>
              <div id="comments" class="tab-pane col-md-8">
                     <?php comments_template('/short-comments-update_child.php'); ?>
                </div>



        <div class="col-md-4">
                <?php include_once('side.php'); ?>
         </div>
            <!-- /.col-md-4 -->

         </div><!-- tab content end -->

		</div><!-- container -->
    </div> <!-- tabbable -->

</div><!-- container -->
<?php get_footer() ?>
