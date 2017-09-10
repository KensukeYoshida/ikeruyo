<?php
/**
 * Widget Name: Popular Posts with a Thumbnail
 * Description: A Popular Posts widget that displays a thumbnail from your blog.
 * Version: 1.0
 */

class PopularWidget extends WP_Widget {

    function __construct() {
        parent::__construct(false, $name = esc_html__('Projects Widget', 'fundingpress'));
    }

    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $nopost=$instance['nopost'];
        ?>

	<?php $funding_allowed = wp_kses_allowed_html( 'post' ); echo wp_kses($before_widget,$funding_allowed); ?>
	<h3> <?php echo  $instance['title'] ; ?></h3>

    <ul class="review p-widget">
<?php

if(isset($instance['cat'])){
			if($instance['cat'] == -1){
 			$cat_id = $instance['cat'];
             $term = get_term( $cat_id, 'project-category' );
			$args = array(
			'post_type' => 'project',
			'orderby' => 'name',
			'posts_per_page' => $nopost,
			 'order' => 'ASC',
		    'post_status' => 'publish',
);
			}else{
				$cat_id = $instance['cat'];
             $term = get_term( $cat_id, 'project-category' );
			$args = array(
			'post_type' => 'project',
			'orderby' => 'name',
			'posts_per_page' => $nopost,
			'project-category' => $term->slug,
			 'order' => 'ASC',
		    'post_status' => 'publish',
);
			}

 }
 ?>
<?php $pc = new WP_Query($args);
if ( $pc->have_posts() ) : ?>
<?php while ($pc->have_posts()) : $pc->the_post(); ?>


      <li>	<?php                global $post;
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
            <div class="img">

             <?php if(has_post_thumbnail()){
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                    $image = aq_resize( $img_url, 57, 57, true, '', true ); //resize & crop img
                ?>
             <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($image[0]); ?>" /></a><span class="overlay-link"></span>
                <?php
                }else{ ?>
              <a href="<?php the_permalink(); ?>"><img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg"></a><span class="overlay-link"></span>
                <?php } ?>
               </div>

                <div class="info">


            <h4 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div class="post-author">
            <?php if(get_the_author_meta('first_name',get_the_author_meta('ID'))){ ?>
            	<?php esc_html_e('by ','fundingpress');?>
            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
            		<?php echo get_the_author_meta('first_name',get_the_author_meta('ID')); ?>
            	</a>
            	<?php }else{ ?>
				<?php esc_html_e('by ','fundingpress');?>
            	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
            		<?php echo get_the_author_meta('display_name',get_the_author_meta('ID')); ?>
            	</a>
				<?php } ?>
           </div>
            <div id="post-content">


            <div class="progress progress-striped active bar-green"><div style="width: <?php printf('%u%', round($funded_amount/$target*100), $project_currency_sign, number_format(round((int)$target), 0, '.', ',')) ?>%" class="bar"></div></div>

            <ul class="project-stats">
                 <li class="first funded">
                     <strong><?php printf('%u%%', round($funded_amount/$target*100), $project_currency_sign, round($target)) ?></strong><?php esc_html_e('funded', 'fundingpress'); ?>
                </li>
                <li class="pledged">
                    <strong>
                         <?php echo esc_attr($project_currency_sign); print number_format(round((int)$target), 0, '.', ',');?></strong><?php esc_html_e('target', 'fundingpress'); ?>
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
          </div> <!--post-content -->
           </div> <!-- info -->
		   <div class="clear"></div>
      </li>
      <?php endwhile;  ?>
      <?php else : ?>
      <div><?php esc_html_e("No projects", 'fundingpress'); ?></div>
      <?php endif; ?>

    </ul>


              <?php echo wp_kses($after_widget,$funding_allowed);; ?>
        <?php
    }

/** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;

	$instance['title'] = strip_tags($new_instance['title']);
	$instance['cat'] = strip_tags($_POST['cat']);
	$instance['nopost'] = strip_tags($new_instance['nopost']);

        return $instance;
    }

/** @see WP_Widget::form */
    function form($instance) {
        $title = esc_attr($instance['title']);
        $category = esc_attr($instance['cat']);
        $nopost = esc_attr($instance['nopost']);
        ?>
         <p>
          <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'fundingpress'); ?></label>
          <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
         <p>
          <label for="<?php echo esc_attr($this->get_field_id('cat')); ?>"><?php esc_html_e('Category:', 'fundingpress'); ?></label>
         <?php

         $args = array(
	'show_option_none'   => esc_html__('None', 'fundingpress'),
	'orderby'            => 'NAME',
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => 1,

	'echo'               => 1,
	'selected'           => $category,
	'hierarchical'       => 0,
	'name'               => 'cat',
'id'                 => '',
	'class'              => 'postform',
	'depth'              => 0,
	'tab_index'          => 0,
	'taxonomy'           => 'project-category',
	'hide_if_empty'      => true,
);

wp_dropdown_categories($args); ?>
        </p>
         <p>
          <label for="<?php echo esc_attr($this->get_field_id('nopost')); ?>"><?php esc_html_e('No. of Posts:', 'fundingpress'); ?></label>
          <input class="widefat" id="<?php echo esc_attr($this->get_field_id('nopost')); ?>" name="<?php echo esc_attr($this->get_field_name('nopost')); ?>" type="text" value="<?php echo esc_attr($nopost); ?>" />
        </p>
        <?php
    }

}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("PopularWidget");'));
?>