<?php
$el_project_highlight_title = $el_project_highlight_categories =  $el_display_type = '';
extract( shortcode_atts( array(
    'el_project_highlight_title' => '',
    'el_project_highlight_categories' => '',
    'el_display_type' => ''
), $atts ) );
$ra = rand();
?>
<script>
 		jQuery(document).ready(function($) {
			if(document.getElementById('click<?php echo esc_attr($ra); ?>')){
				var evt = document.createEvent("HTMLEvents");
				evt.initEvent("click", true, true);
				document.getElementById('click<?php echo esc_attr($ra); ?>').dispatchEvent(evt);
			}
		});

	 function cat_ajax_get<?php echo esc_attr($ra); ?>(catID, display) {

        jQuery('#category-menu<?php echo esc_attr($ra); ?> li.l<?php echo esc_attr($ra); ?>').click(function(li) {
        jQuery('li.l<?php echo esc_attr($ra); ?>').removeClass('current');
        jQuery(this).addClass('current');
        });

     jQuery("#category-post-content<?php echo esc_attr($ra); ?>").hide();
    jQuery("#loading-animation<?php echo esc_attr($ra); ?>").show();


       jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "load-filter", cat: catID, display:  display},
        success: function(response) {
            jQuery("#category-post-content<?php echo esc_attr($ra); ?>").html(response);
            jQuery("#loading-animation<?php echo esc_attr($ra); ?>").hide();
               jQuery("#category-post-content<?php echo esc_attr($ra); ?>").show();
            return false;
        }
    });
}

</script>
<div class="highlight-block">
<?php

		if(empty($el_project_highlight_categories)) $el_project_highlight_categories = "";


       switch($el_display_type) {
            case 'latest_highlight_projects': //////////////////////////////////////////latest
            /*  list_categories();*/
        if($el_project_highlight_title == ""){}else{?><div class="title"><h4><?php echo esc_attr($el_project_highlight_title); ?></h4></div><?php }
            $args = array(
              'type' => 'project',
              'taxonomy' => 'project-category',
              'orderby' => 'name',
              'order' => 'ASC',
              'include' => $el_project_highlight_categories
              );

            $categories = get_categories($args); ?>
            <ul class="category-menu" id="category-menu<?php echo esc_attr($ra); ?>">
                <?php foreach ( $categories as $cat ) {?>
                <li class="l<?php echo esc_attr($ra); ?>" id="cat-<?php echo esc_attr($cat->term_id); ?>"><a id="click<?php echo esc_attr($ra); ?>"  class="<?php echo esc_attr($cat->slug); ?> ajax" onclick="cat_ajax_get<?php echo esc_attr($ra); ?>('<?php echo esc_attr($cat->term_id); ?>',1);" ><?php echo esc_attr($cat->name); ?></a></li>
                <?php } ?>
            </ul>
            <div class="category-post-content" id="category-post-content<?php echo esc_attr($ra); ?>"></div>
            <div class="loading-animation" id="loading-animation<?php echo esc_attr($ra); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/loading.gif"/>
            </div>



                <?php
                break;
            case 'staff_projects': //////////////////////////////////staff
             /* list_categories();*/
if($el_project_highlight_title == ""){}else{?><div class="title"><h4><?php echo esc_attr($el_project_highlight_title); ?></h4></div><?php }
            $args=array(
              'type' => 'project',
              'taxonomy' => 'project-category',
              'orderby' => 'name',
              'order' => 'ASC',
              'include' => $el_project_highlight_categories
              );
            $categories = get_categories($args); ?>
             <div class="category-post-content" id="category-post-content<?php echo esc_attr($ra); ?>"></div>
             <div class="loading-animation" id="loading-animation<?php echo esc_attr($ra); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/loading.gif"/>
             </div>
             <ul  class="category-menu" id="category-menu<?php echo esc_attr($ra); ?>">
                <?php foreach ( $categories as $cat ) {
                       global $post;
                       $term = get_term( $cat->term_id, 'project-category' );

                       $args = array (
                           'showposts' => -1,
                           'post_type' => 'project',
                           'orderby' => 'post_date',
                           'project-category' => $term->slug);
                            $posts = get_posts( $args );

                           foreach ( $posts as $post ) {
                                   global $post;
                               setup_postdata($post);

          if(get_post_meta($post->ID, '_smartmeta_staff-check-field', true) == 'true'){ ?>
          <li class="l<?php echo esc_attr($ra); ?>" id="cat-<?php echo esc_attr($cat->term_id); ?>"><a id="click<?php echo esc_attr($ra); ?>" class="<?php echo esc_attr($cat->slug); ?> ajax" onclick="cat_ajax_get<?php echo esc_attr($ra); ?>('<?php echo esc_attr($cat->term_id); ?>',2);" ><?php echo esc_attr($cat->name); ?></a></li>
          <?php   }}}?>
            </ul>
            <?php
                break;
     case 'featured_projects': //////////////////////////////////featured
             /* list_categories();*/
if($el_project_highlight_title == ""){}else{?><div class="title"><h4><?php echo esc_attr($el_project_highlight_title); ?></h4></div><?php }
            $args=array(
              'type' => 'project',
              'taxonomy' => 'project-category',
              'orderby' => 'name',
              'order' => 'ASC',
              'include' => $el_project_highlight_categories
              );
            $categories = get_categories($args); ?>
             <div class="category-post-content" class="category-post-content" id="category-post-content<?php echo esc_attr($ra); ?>"></div>
             <div class="loading-animation" id="loading-animation<?php echo esc_attr($ra); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/loading.gif"/>
             </div>
             <ul class="category-menu" id="category-menu<?php echo esc_attr($ra); ?>">
                <?php foreach ( $categories as $cat ) {
                       global $post;
                       $term = get_term( $cat->term_id, 'project-category' );

                       $args = array (
                           'showposts' => -1,
                           'post_type' => 'project',
                           'orderby' => 'post_date',
                           'project-category' => $term->slug);
                            $posts = get_posts( $args );
                           foreach ( $posts as $post ) {
                                   global $post;
                               setup_postdata($post);
          if(get_post_meta($post->ID, '_smartmeta_featured', true) == 'true'){ ?>
          <li class="l<?php echo esc_attr($ra); ?>" id="cat-<?php echo esc_attr($cat->term_id); ?>"><a id="click<?php echo esc_attr($ra); ?>" class="<?php echo esc_attr($cat->slug); ?> ajax" onclick="cat_ajax_get<?php echo esc_attr($ra); ?>('<?php echo esc_attr($cat->term_id); ?>',3);" ><?php echo esc_attr($cat->name); ?></a></li>
          <?php   }}}?>
            </ul>
            <?php
                break;
              case 'latest_suc_projects': ////////////////////////////////////////successful
            /*  list_categories();*/
if($el_project_highlight_title == ""){}else{?><div class="title"><h4><?php echo esc_attr($el_project_highlight_title); ?></h4></div><?php }
             $args = array(
              'type' => 'project',
              'taxonomy' => 'project-category',
              'orderby' => 'name',
              'order' => 'ASC',
              'include' => $el_project_highlight_categories
              );
            $categories = get_categories($args);

            ?>
            <div class="category-post-content" id="category-post-content<?php echo esc_attr($ra); ?>"></div>
            <div class="loading-animation" id="loading-animation<?php echo esc_attr($ra); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/loading.gif"/>
            </div>
            <ul class="category-menu" id="category-menu<?php echo esc_attr($ra); ?>">
                <?php foreach ( $categories as $cat ) {
                       global $post;
                       $term = get_term( $cat->term_id, 'project-category' );

                       $args = array (
                           'showposts' => -1,
                           'post_type' => 'project',
                           'orderby' => 'post_date',
                           'project-category' => $term->name);
                            $posts = get_posts( $args );

                           foreach ( $posts as $post ) {
                                 global $post;
                                 setup_postdata($post);
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
                        $project_expired = strtotime($project_settings['date']) < time();
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
                  if($funded_amount == $target or $funded_amount > $target){ ?>

                     <li class="l<?php echo esc_attr($ra); ?>" id="cat-<?php echo esc_attr($cat->term_id); ?>"><a id="click<?php echo esc_attr($ra); ?>" class="<?php echo esc_attr($cat->slug); ?> ajax" onclick="cat_ajax_get<?php echo esc_attr($ra); ?>('<?php echo esc_attr($cat->term_id); ?>',4);" ><?php echo esc_attr($cat->name); ?></a></li>

          <?php break;  }else{}}}?>
            </ul>

    <?php   break;
            case 'first_ending_projects'://///////////////////////////////////ending
         /*  list_categories();*/
if($el_project_highlight_title == ""){}else{?><div class="title"><h4><?php echo esc_attr($el_project_highlight_title); ?></h4></div><?php }
              $args = array(
              'type' => 'project',
              'taxonomy' => 'project-category',
              'orderby' => 'name',
              'order' => 'ASC',
              'include' => $el_project_highlight_categories
              );
            $categories = get_categories($args); ?>
            <div class="category-post-content" id="category-post-content<?php echo esc_attr($ra); ?>"></div>
            <div class="loading-animation" id="loading-animation<?php echo esc_attr($ra); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/loading.gif"/>
            </div>
            <ul class="category-menu" id="category-menu<?php echo esc_attr($ra); ?>">
                <?php foreach ( $categories as $cat ) { ?>
                <li class="l<?php echo esc_attr($ra); ?>" id="cat-<?php echo esc_attr($cat->term_id); ?>"><a id="click<?php echo esc_attr($ra); ?>" class="<?php echo esc_attr($cat->slug); ?> ajax" onclick="cat_ajax_get<?php echo esc_attr($ra); ?>('<?php echo esc_attr($cat->term_id); ?>',5);" ><?php echo esc_attr($cat->name); ?></a></li>
                <?php } ?>
            </ul>
            <?php
                break;
        }
?>
<div class="clear"></div>
</div>