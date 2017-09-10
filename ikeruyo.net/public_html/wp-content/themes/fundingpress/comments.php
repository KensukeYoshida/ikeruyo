<?php  posts_nav_link(); ?>
<?php wp_link_pages( $args ); ?>
 <?php comment_form(); ?>
 <?php the_tags('Tags: ', ', ', '<br />');
 add_editor_style();
 if ( ! isset( $content_width ) ) $content_width = 900;
  ?>