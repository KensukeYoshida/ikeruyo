<?php get_header();?>
<?php if (of_get_option('page_header')!=""){ ?>
	<style>
    html{
    background-image:url(<?php echo esc_url(of_get_option('page_header')); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php } ?>
<div class="container blog">
  <div class="row">

    <div class="cl-lg-8">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>



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
                            $image = aq_resize( $img_url, 817, 310, true, '', true   ); //resize & crop img
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




            <h2><a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a></h2>

            <p> <?php the_excerpt(); ?></p>

            <div class="clear"></div>
            <div class="blog-pinfo-wrapper">
                <div class="post-pinfo"><?php esc_html_e("By ", 'fundingpress');?><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))); ?>" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e("View all posts by ", 'fundingpress');?><?php echo esc_attr(get_the_author()); ?>"><?php echo esc_attr(get_the_author()); ?></a> |

                    <?php if ( is_plugin_active( 'disqus-comment-system/disqus.php' )){ ?>
                        <a  title="<?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'fundingpress' ), number_format_i18n( get_comments_number() ) ); ?> <?php esc_html_e('in this post', 'fundingpress'); ?>" href="<?php echo esc_url(the_permalink()); ?>#comments" >
                        <?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'fundingpress' ), number_format_i18n( get_comments_number() ) ); ?>
                        </a> &nbsp;
                        <?php }else{ ?>
                        <a title="<?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'fundingpress' ), number_format_i18n( get_comments_number() ) ); ?> <?php esc_html_e('in this post', 'fundingpress'); ?>" href="<?php echo esc_url(the_permalink()); ?>#comments" data-toggle="tooltip" data-placement="top">
                        <?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'fundingpress' ), number_format_i18n( get_comments_number() ) ); ?>
                        </a> &nbsp;

                       <?php } ?>


                    </div>
                <a class="button-green button-small" href="<?php the_permalink(); ?>"><?php esc_html_e("Read more", 'fundingpress');?></a>
                <div class="clear"></div>
            </div>
        </div>
        <!-- /.blog-post -->


        <?php endwhile; endif; ?>
        <div class="clear"></div>
    </div>
    <!-- /.cl-lg-8 -->


    <div class="cl-lg-4 ">
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer widgets') ) : ?>
                <?php dynamic_sidebar('two'); ?>
           <?php endif; ?>
    </div>
    <!-- /.cl-lg-4 -->

  </div>
  <!-- /.row -->
</div>
<!-- /.container -->


<?php get_footer(); ?>