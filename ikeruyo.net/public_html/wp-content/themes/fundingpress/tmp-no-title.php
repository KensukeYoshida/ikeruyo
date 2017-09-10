<?php
 /*
 * Template Name: No title
 */
?>
<?php get_header(); ?>
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
<div class="page normal-page container-wrap">
      <div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<?php while ( have_posts() ) : the_post(); ?>

				<?php the_content(); ?>
				<?php endwhile; // end of the loop. ?>
			<div class="clear"></div>
			</div>
		</div>
	 </div>
</div>

<?php get_footer(); ?>