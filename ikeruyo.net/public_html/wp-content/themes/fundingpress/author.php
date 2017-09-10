<?php get_header(); ?>
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
global $wp_query;

$author_id = $wp_query->query_vars['author'];
?>

<div class="row profile">
	<div class="container">
		<div class="profile-info row">

			<div class="col-lg-3">
				<div class="shadow">

					<?php
					$autorpic = get_the_author_meta( 'profile_pic', $author_id );

					if ( ! empty( $autorpic ) ) {
					?>

						<?php
						$image = aq_resize( $autorpic,  250, 250, true, true, true ); //resize & crop img
						if ( ! isset ( $image[0] ) ) {
							$theimage = $autorpic;
						} else {
							$theimage = $image;
						}
						?>

						<img src="<?php echo esc_url($theimage); ?>" />

					<?php } else { ?>

						<?php echo get_avatar( $author_id, 250 ); ?>

					<?php } ?>

				</div>
			</div>


			<div class="tabbable col-lg-9"> <!-- Only required for left/right tabs -->
				<div class="tab-content">
					<div id="profile" class="tab-pane active">

						<div class="col-lg-10">

							<h1>
								<?php
								if ( $display_name = get_the_author_meta( 'display_name', $author_id ) ) {
									echo esc_attr($display_name);
								}
								?>

								<?php if ( usercountry_name_display( $author_id ) != "" ) { ?>
									<small><i class="fa fa-map-marker"></i>
										<?php echo get_the_author_meta( 'city', $author_id ); ?>, <?php echo usercountry_name_display( esc_attr($author_id) ); ?></small>
								<?php } ?>
							</h1>

							<?php  if ( $description = get_the_author_meta('description', $author_id ) ) { ?>
								<div class="biography"><p><?php echo esc_attr($description); ?></p></div>
							<?php } ?>


							<table>

								<?php
								if ( get_the_author_meta( 'first_name', $author_id ) ) { ?>
									<tr>
										<td>
											<i class="fa fa-user"></i> &nbsp;<?php esc_html_e( 'Name', 'fundingpress' ); ?>
										</td>
										<td>
											<?php
											echo get_the_author_meta( 'first_name', $author_id );

											if ( $last_name = get_the_author_meta( 'last_name', $author_id ) ) {
												echo ' ';
												echo esc_attr($last_name);
											}
											?>
										</td>
									</tr>
								<?php }else{ ?>
									<tr>
										<td>
											<i class="fa fa-user"></i> &nbsp;<?php esc_html_e( 'Name', 'fundingpress' ); ?>
										</td>
										<td>
											<?php
											echo get_the_author_meta( 'display_name', $author_id );
											?>
										</td>
									</tr>

								<?php } ?>
								<?php
								if ( get_the_author_meta( 'user_registered', $author_id ) ) { ?>
									<tr>
										<td>
											<i class="fa fa-calendar"></i> &nbsp;<?php esc_html_e( 'Member Since', 'fundingpress' ); ?>
										</td>
										<td>
											<?php echo date( 'F Y', strtotime( get_userdata( $author_id )->user_registered ) ); ?>
										</td>
									</tr>
								<?php } ?>

								<?php
								if ( $user_url = get_the_author_meta( 'user_url', $author_id ) ) { ?>
									<tr>
										<td>
											<i class="fa fa-globe"></i> &nbsp;<?php esc_html_e("Website", 'fundingpress'); ?>
										</td>
										<td>
											<a target="_blank" href="<?php echo esc_url($user_url); ?>">
												<?php echo esc_url($user_url); ?>
											</a>
										</td>
									</tr>
								<?php } ?>

							</table>

						</div>
						<!-- end .cl-lg-10 -->

					</div>
					<!-- end #profile -->

				</div>
				<!-- end .tab-content -->

			</div>
			<!-- end .tabbable -->

		</div>
		<!-- end .profile-info -->

	</div>
	<!-- end .container -->

</div>
<!-- end .profile -->


<div class="profile-projects">

	<div class="container blog">
		<div class="row">

			<h2><?php esc_html_e( 'Projects', 'fundingpress'); ?></h2>

			<div class="col-lg-12 isoprblck">
		        <?php

				$args = array(
					'post_type'      => 'project',
					'order'          => 'ASC',
					'author'         => $author_id,
					'posts_per_page' => -1
				);

				$wp_query = new WP_Query( $args );

				if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

					<?php
					global $post;
					global $f_currency_signs;

					$author_id             = get_the_author_meta( 'ID' );
					$project_settings      = (array) get_post_meta( $post->ID, 'settings', true );

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
					$target                = $project_settings['target'];
					$rewards               = get_children( array(
						'post_parent' => $post->ID,
						'post_type' => 'reward',
						'order' => 'ASC',
						'orderby' => 'meta_value_num',
						'meta_key' => 'funding_amount',
					));
					$funders       = array();
					$funded_amount = 0;
					$chosen_reward = null;

					foreach( $rewards as $this_reward ) {
						$these_funders = get_children( array(
							'post_parent' => $this_reward->ID,
							'post_type'   => 'funder',
							'post_status' => 'publish'
						));
						foreach( $these_funders as $this_funder ) {
							$funding_amount = get_post_meta( $this_funder->ID, 'funding_amount', true );
							$funders[]      = $this_funder;
							$funded_amount  += $funding_amount;
						}
					}
					?>

					<?php if ( empty( $target ) or $target == 0 ) { $target = 1; } ?>

					<div class="project-card col-lg-3">
						<?php if ( has_post_thumbnail() ) { ?>

							<?php
							$thumb   = get_post_thumbnail_id();
							$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
							$image   = aq_resize( $img_url, 320, 200, true, '', true ); //resize & crop img
							?>

							<div class="project-thumb-wrapper">
								<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($image[0]); ?>" /></a>
							</div>

						<?php } else { ?>
							<div class="project-thumb-wrapper">
								<a href="<?php the_permalink(); ?>"><img class="pbimage" src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/defaults/default_project.jpg"></a>
							</div>
						<?php } ?>

						<h5 class="bbcard_name">
							<a href="<?php the_permalink(); ?>"><?php $title = get_the_title(); echo mb_substr( $title, 0, 23 ); if ( strlen( $title ) > 23 ) { echo '...'; } ?></a>
						</h5>

						<?php
						if(get_the_author_meta('first_name',$author_id) or get_the_author_meta('last_name',$author_id)){ ?>
							<span><?php esc_html_e("by", 'fundingpress'); ?>
								<a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
									<?php echo esc_attr(get_the_author_meta('first_name',$author_id).' '.get_the_author_meta('last_name',$author_id)); ?>
								</a>
							</span>
						<?php }else{ ?>
							<span><?php esc_html_e("by", 'fundingpress'); ?>
								<a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
									<?php echo esc_attr(get_the_author_meta('display_name',$author_id)); ?>
								</a>
							</span>

						<?php } ?>

						<p>
							<?php
							$excerpt = get_the_excerpt();
							echo mb_substr( $excerpt, 0, 110 );
							echo '...';
							?>
						</p>

						<p>
							<?php if ( usercountry_name_display( $author_id ) != '' || get_the_author_meta( 'city', $author_id ) != '' ) { ?>
								<span class="icon-map-marker" ></span><b><?php echo esc_attr( usercountry_name_display( $author_id ) ); ?></b>
								<?php if ( get_the_author_meta( 'city', $author_id ) != '' ) { echo ', '; } ?>
								<?php if ( get_the_author_meta( 'city', $author_id ) ) { echo get_the_author_meta( 'city', $author_id ); } ?>
							<?php } ?>
						</p>

						 <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?>%" class="bar"></div></div>


						<ul class="project-stats">
							<li class="first funded">
								<strong><?php printf( esc_html__( '%u%%', 'fundingpress' ), round( $funded_amount/$target*100 ), $project_currency_sign, number_format( round( (int) $target ), 0, '.', ',') ); ?></strong><?php esc_html_e( 'funded', 'fundingpress' ); ?>
							</li>
							<li class="pledged">
								<strong>
								<?php print $project_currency_sign; print number_format( round( (int) $target ), 0, '.', ',' ); ?>
								</strong>
								<?php esc_html_e('target', 'fundingpress'); ?>
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

				<?php endwhile; endif; ?>

				<div class="clear"></div>

			</div>
			<!-- end .cl-lg-12 -->

		</div>
		<!-- end .row -->

	</div>
	<!-- end .container -->

</div>
<!-- end .profile -->


<?php get_footer(); ?>