            <div class="comment-form">
<?php /* Run some checks for bots and password protected posts */ ?>
<?php
	$childpost = get_post_meta($post->ID, '_comment_holder_id', true);

    $req = get_option('require_name_email'); // Checks if fields are required.
    if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
        die ( 'Please do not load this page directly. Thanks!' );
    if ( ! empty($post->post_password) ) :
        if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
?>
                <div class="nopassword"><?php esc_html_e('This post is password protected. Enter the password to view any comments.', 'fundingpress') ?></div>
            </div><!-- .comments -->
<?php
        return;
    endif;
endif;
?>
<?php /* See IF there are comments and do the comments stuff! */ ?>
<?php /* An ordered list of our custom comments callback, custom_comments(), in functions.php   */ ?>
   <ul  class="comment-list">
        <?php

        $comments = get_comments(array(
			'post_id' => $childpost
		));

        wp_list_comments(array(
			'callback' => 'funding_comments_child', //Allow comment pagination
			'reverse_top_level' => false //Show the latest comments at the top of the list
		), $comments);

       // wp_list_comments('callback=funding_comments&post_id='.$childpost.'&reverse_top_level=true');
       ?>
   </ul>
<?php  ?>
<?php if (get_current_user_id() != 0) { ?>
<div id="respond">
  <h2><?php comment_form_title( esc_html__('Post a comment', 'fundingpress'), esc_html__('Post a Reply to %s', 'fundingpress') ); ?></h2>
        <div class="formcontainer">
              <form id="commentform" action="<?php echo esc_url(get_option('siteurl')); ?>/wp-comments-post.php" method="post" class="contact comment-form">
              <input id="author" name="author" placeholder="<?php esc_html_e('Name*', 'fundingpress'); ?>" type="hidden" value="<?php echo esc_attr(get_current_user_id()); ?>" size="30" maxlength="20" tabindex="3" />
              <input placeholder="<?php esc_html_e('Email*', 'fundingpress'); ?>" id="email" name="email" type="hidden" value="me@me.com" size="30" maxlength="50" tabindex="4" />
              <input id="url" placeholder="<?php esc_html_e('Website', 'fundingpress'); ?>" name="url" type="hidden" value="http://me.com" size="30" maxlength="50" tabindex="5" />
              <div id="form-section-comment" class="form-section input-prepend">
                  <textarea placeholder="<?php esc_html_e('Your comment', 'fundingpress'); ?>" id="comment" name="comment" cols="45" rows="8" tabindex="6"></textarea>
              </div><!-- #form-section-comment .form-section -->
<?php do_action('comment_form', $childpost); ?>
      <div class="form-submit"><input id="submit" name="submit"  class="button-small button-green" type="submit" value="<?php esc_html_e('Submit comment', 'fundingpress') ?>" tabindex="7" /><input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($id); ?>" /></div>
<?php comment_id_fields($childpost); $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<input type="hidden" name="redirect_to" value="<?php echo esc_url($actual_link); ?>">
              </form><!-- #commentform -->
         </div><!-- .formcontainer -->
</div><!-- #respond -->
<?php } ?>
</div>