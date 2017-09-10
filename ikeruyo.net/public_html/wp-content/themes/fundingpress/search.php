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
<div class=" page normal-page">
	<div class="container">
    <div class="row">
        <div class="col-lg-12">
<?php if(get_post_type( $post->ID ) == 'post') { ?>
        	<div class="container blog">
  				<div class="row">
				<div class="col-lg-8 isoblog isotope">

<?php } ?>
 <?php if(get_post_type( $post->ID ) == 'project') { ?>
<div class="isoprblck">
 <?php } ?>
			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				    <?php if(get_post_type( $post->ID ) == 'project') { ?>


                <?php
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
     ?>
                <div class="project-card col-lg-3">
	             <div class="project-thumb-wrapper">

		              <?php if(has_post_thumbnail()){
		                    $thumb = get_post_thumbnail_id();
		                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
		                    $image = aq_resize( $img_url, 320,200, true, '', true ); //resize & crop img
		                ?>
		              <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($image[0]); ?>" /></a>
		                <?php
		                }else{ ?>
		                <a href="<?php the_permalink(); ?>"><img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg"></a>
		                <?php } ?>

                	</div>



            <h5 class="bbcard_name">
            	<a href="<?php the_permalink(); ?>">
            		<?php $title = get_the_title(); echo esc_attr(mb_substr($title, 0,23)); if(strlen($title) > 23){echo '...';}?>
            	</a>
            </h5>
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
			 <p class="plocation">
                <?php if(usercountry_name_display(get_the_author_meta( 'ID' )) != '' || get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ ?>
                <span class="fa fa-map-marker" ></span> <b><?php echo esc_attr(usercountry_name_display(get_the_author_meta( 'ID' )));?></b><?php if(get_the_author_meta('city', get_the_author_meta( 'ID' )) != ''){ echo ', '; } ?>
                <?php if ( get_the_author_meta('city', get_the_author_meta( 'ID' )) ) {echo esc_attr(get_the_author_meta('city',get_the_author_meta( 'ID' ))); } ?>
                <?php } ?>
            </p>

            <div class="progress progress-striped active bar-green"><div style="width: <?php printf(esc_html__('%u%', 'fundingpress'), round($funded_amount/$target*100), $project_currency_sign, round($target)) ?>%" class="bar"></div></div>

            <ul class="project-stats">
                <li class="first funded">
                     <strong><?php printf(esc_html__('%u%%', 'fundingpress'), round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?></strong><?php esc_html_e( 'funded', 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                        <?php echo esc_attr($project_currency_sign); print number_format(round((int)$target), 0, '.', ',')?></strong><?php esc_html_e('target', 'fundingpress'); ?>
                </li>
                <li data-end_time="2013-02-24T08:41:18Z" class="last ksr_page_timer">
                 <?php   if(!$project_expired){ ?>
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


				<?php }else {?>


    <div class="blog-list">

             <div class="blog-image img_thumb entry-thumb">
             <?php
                $key_1_value = get_post_meta(get_the_ID(), '_smartmeta_video', true);

                if($key_1_value != '') {
                $funding_allowed['iframe'] = array(
                            'src'             => array(),
                            'height'          => array(),
                            'width'           => array(),
                            'frameborder'     => array(),
                            'allowfullscreen' => array(),
                        );
                 echo wp_kses($key_1_value, $funding_allowed,array('http', 'https'));
                }elseif ( has_post_thumbnail() ) { ?>
                  <a href="<?php the_permalink(); ?>">  <?php
                            $thumb = get_post_thumbnail_id();
                            $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                            $image = aq_resize( $img_url, 320, 200, true, '', true  ); //resize & crop img
                            ?>
                            <img class="attachment-small wp-post-image" src="<?php echo esc_url($image[0]); ?>" /></a>
             <?php } ?>
             <?php if ( has_post_thumbnail() or  $key_1_value != '') { ?>
             <div class="blog-pdate green-bg">
             <?php }else{?>
             <div class="blog-pdate-noimg green-bg">
             <?php } ?>
                <span class="date"><?php the_time('M'); ?><br /><?php the_time('d'); ?></span>
             </div>

        </div><!-- blog-image -->
		<div class="clear"></div>

            <h2><a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a></h2>

            <p> <?php the_excerpt(5); ?></p>

            <div class="clear"></div>
            <div class="blog-pinfo-wrapper">
                <div class="post-pinfo">
                	<?php esc_html_e("By",'fundingpress');?>
                	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))); ?>" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e("View all posts by ",'fundingpress');?>
                		<?php echo esc_attr(get_the_author()); ?>"><?php echo esc_attr(get_the_author()); ?>
                	</a> |
                	<a data-toggle="tooltip" data-placement="top" title="<?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'fundingpress' ), number_format_i18n( get_comments_number() ) ); ?> <?php esc_html_e('in this post', 'fundingpress'); ?>" href="<?php echo esc_url(the_permalink()); ?>#comments">
                		<?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'fundingpress' ), number_format_i18n( get_comments_number() ) ); ?>
                	</a>
                </div>
                <a class="button-green button-small" href="<?php the_permalink(); ?>"><?php esc_html_e("Read more",'fundingpress');?></a>
                <div class="clear"></div>
            </div>
        </div>
        <!-- /.blog-post -->

			<?php } ?>

			<?php endwhile; ?>
			 <?php if(get_post_type( $post->ID ) == 'project') { ?>
				</div>
			<?php } ?>
			    <?php if(get_post_type( $post->ID ) == 'post') { ?>

			    		</div>
			 <div class="col-lg-4 ">
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer widgets') ) : ?>
                <?php dynamic_sidebar('blog'); ?>
           <?php endif; ?>
    		</div>

    <!-- /.col-lg-4 -->
    </div>
 <!-- /.row -->

        <div class="clear"></div>
		<?php } ?>

		 <ul id="pager">
              <li>
                <?php
            $showposts1 = get_option('posts_per_page ');
            $additional_loop = new WP_Query('showposts='.$showposts1.'&paged='.$paged.'&post_type='.get_post_type( $post->ID ));
            $page=$additional_loop->max_num_pages;
            echo funding_kriesi_pagination($additional_loop->max_num_pages);
            ?>
            <?php wp_reset_query(); ?>
              </li>
            </ul>


			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<div class="entry-header">
						<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'fundingpress' ); ?></h1>
					</div><!-- .entry-header -->

					<div class="entry-content">
						<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'fundingpress' ); ?></p>

					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>
        </div>
    </div>
    </div>
</div>
<?php get_footer(); ?>