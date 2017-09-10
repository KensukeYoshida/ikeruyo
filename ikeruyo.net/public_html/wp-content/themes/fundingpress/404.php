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
<div class=" page">
	<div class="container">
    <div class="row">
        <div class="cl-lg-12">
           <div class="four0four">
    <p class="huge"><?php esc_html_e(' OOPS! 404 ', 'fundingpress');?></p>
    <?php esc_html_e('Page not found, sorry', 'fundingpress');?> :(

            </div>
        </div>
    </div>
    </div>
</div>


<?php get_footer(); ?>