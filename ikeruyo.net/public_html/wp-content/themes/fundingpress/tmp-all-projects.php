    <?php
/*
* Template name: All projects page
*/
?>
<?php get_header();?>
<?php
$thumb = get_post_thumbnail_id();
$img_url = wp_get_attachment_url( $thumb,'full');
?>
<?php if(!empty($img_url)){ ?>
<style>
    body.page{
    background-image:url(<?php echo esc_url($img_url); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php }else{ ?>
<?php if (of_get_option('page_header')!=""){ ?>
	<style>
    body.page{
    background-image:url(<?php echo esc_url(of_get_option('page_header')); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php } ?>
<?php } ?>
<div class="all-projects">
	<div class="container ">
	  <div class="row">

<script>
 		jQuery(document).ready(function($) {
			if(document.getElementById('click')){
				var evt = document.createEvent("HTMLEvents");
				evt.initEvent("click", true, true);
				document.getElementById('click').dispatchEvent(evt);
			}
		});
</script>
    <div class="col-md-12 col-sm-12">
     <?php

        $args = array(
              'type' => 'project',
              'taxonomy' => 'project-category',
              'orderby' => 'name',
              'order' => 'ASC',
              );
            $categories = get_categories($args); ?>

            <ul class="category-menu">
                <?php foreach ( $categories as $cat ) { ?>
                <li id="cat-<?php echo esc_attr($cat->term_id); ?>"><a id="click" class="<?php echo esc_attr($cat->slug); ?> ajax get_all_prj" onclick="cat_ajax_get_all('<?php echo esc_attr($cat->term_id); ?>');" ><?php echo esc_attr($cat->name); ?></a></li>

                <?php } ?>
            </ul>
             <div class="loading-animation">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/loading.gif"/>
            </div>
            <div class="category-post-content"></div>
    </div>
    <!-- /.span12 -->
  </div>
  <!-- /.row -->
    </div>
  <!-- /.row -->
</div>
<!-- /.container -->
<?php get_footer(); ?>