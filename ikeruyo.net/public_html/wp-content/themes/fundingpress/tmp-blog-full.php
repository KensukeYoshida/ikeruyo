<?php
/*
 * Template name: Blog - Full Width
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
<div class="blog">
	<div class="container">
	  <div class="row">

    <div class="col-md-12 col-sm-12">
        <?php
        $category_id = get_query_var('cat' );
        $showposts = get_option('posts_per_page');
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $new_query = new WP_Query();
        $new_query->query( 'showposts='.$showposts.'&cat='.$category_id.'&paged='.$paged );
       ?>

        <?php if ( $new_query->have_posts() ) : while ( $new_query->have_posts() ) : $new_query->the_post(); ?>



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
                            $image = aq_resize( $img_url, 817, 310, true, '', true  ); //resize & crop img
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

            <p> <?php the_excerpt(); ?></p>

            <div class="clear"></div>
            <div class="blog-pinfo-wrapper">
                <div class="post-pinfo"><?php esc_html_e("By ", 'fundingpress'); ?><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))); ?>" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e("View all posts by ", 'fundingpress'); ?><?php echo esc_attr(get_the_author()); ?>"><?php echo esc_attr(get_the_author()); ?></a> |

                    <?php if ( is_plugin_active( 'disqus-comment-system/disqus.php' )){ ?>
                        <a  href="<?php echo esc_url(the_permalink()); ?>#comments" >
                        <?php comments_number( esc_html__('No comments','fundingpress'), esc_html__('One comment','fundingpress'), esc_html__('% comments','fundingpress')); ?></a> &nbsp;
                       <?php }else{ ?>
                        <a title="<?php comments_number( esc_html__('No comments in this post','fundingpress'), esc_html__('One comment in this post','fundingpress'), esc_html__('% comments in this post','fundingpress')); ?>" href="<?php echo esc_url(the_permalink()); ?>#comments" data-toggle="tooltip" data-placement="top">
                        <?php comments_number( esc_html__('No comments','fundingpress'), esc_html__('One comment','fundingpress'), esc_html__('% comments','fundingpress')); ?></a> &nbsp;

                       <?php } ?>


                    </div>
                <a class="button-green button-small" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'fundingpress'); ?></a>
                <div class="clear"></div>
            </div>
        </div>
        <!-- /.blog-post -->


        <?php endwhile; endif; ?>
            <ul id="pager">
              <li>
                <?php
            $showposts1 = get_option('posts_per_page');
            $additional_loop = new WP_Query('showposts='.$showposts1.'&cat='.$category_id.'&paged='.$paged);
            $page=$additional_loop->max_num_pages;
            echo funding_kriesi_pagination($additional_loop->max_num_pages);
            ?>
            <?php wp_reset_query(); ?>
              </li>
            </ul>
        <div class="clear"></div>
    </div>
    <!-- /.span12 -->

  </div>
  <!-- /.row -->
  </div>
  <!-- /.row -->
</div>
<!-- /.container -->


<?php get_footer(); ?>