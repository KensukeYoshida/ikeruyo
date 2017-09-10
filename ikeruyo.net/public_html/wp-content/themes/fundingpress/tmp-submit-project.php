 <?php
/* Template Name: Launch project */
$workedInsert = false;
$insertSuccess = false;
$thepostid = 0;
$usr = get_userdata(get_current_user_id());
$userpp = $usr->paypal_email;
if(!isset($userpp))$userpp= '';
$userwepay = get_user_meta(get_current_user_id(), 'wepay_account_id', true);
if(!isset($userwepay))$userwepay= '';
$userstripe = get_user_meta(get_current_user_id(), 'stripe_data', true);
if(!isset($userstripe))$userstripe= '';
if(empty($userpp) && empty($userwepay) && empty($userstripe)){
	$er = esc_html__('You need to connect your PayPal, Stripe or WePay account. Please go to My Account / Funding details.', 'fundingpress');
}
if(is_user_logged_in() != 1){wp_redirect( home_url() );}

$current_page_permalink = get_permalink();
$current_user = wp_get_current_user();
$postTitleError = '';

if (isset($_GET['pid']) && $_GET['delete'] == 'true') {

    if(!wp_delete_post( $_GET['pid'])) {
        $message = 'DELETE POST ERROR';
    }
    else {
       wp_redirect(get_permalink( get_page_by_path( 'my-account' ) ));
        exit;
    }

}
else if (isset($_GET['pid']) && $_GET['edit'] == 'true') {

    $editpost = get_post($_GET['pid']);

    if($editpost->post_author != $current_user->ID){wp_redirect( home_url() );}
    $project_title      = $editpost->post_title;
    $project_story    = $editpost->post_content;
    $project_categories   = wp_get_object_terms($_GET['pid'], 'project-category');
    $project_postImage  = get_the_terms( $_GET['pid'], '_thumbnail_id', true );
    $project_postBackground  = get_the_terms( $_GET['pid'], '_thumbnail_id_background', true );
	$campaign_thumbnail = get_the_post_thumbnail($_GET['pid'], "full");
	$project_video = get_post_meta($_GET['pid'], '_smartmeta_video-link-field', true);
	$settings = get_post_meta($_GET['pid'], 'settings', true);

	if (isset($settings['currency']) && $settings['currency']!='') { $project_currency = $settings['currency']; }
	if (isset($settings['date']) && $settings['date']!='') { $project_date = $settings['date']; }
	if (isset($settings['target']) && $settings['target']!='') { $project_amount = $settings['target']; }
	 $rewards = get_children(array(
                'post_parent' => $editpost->ID,
                'post_type' => 'reward',
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'funding_amount',
            ));

}


if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
	$counter = 0;
	$holder = Array();


	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 6) == "title_"){
			$holdid = str_replace("title_", "", $key);
			$holder[$counter]['title']= $value;
			$holder[$counter]['description']= $_POST['description_'.$holdid];
			$holder[$counter]['minimum']= $_POST['minimum_'.$holdid];
			$holder[$counter]['available']= $_POST['available_'.$holdid];
			$counter ++;
		}
	}

    $workedInsert = true;
    $project_story = $_POST['postStory'];
    $project_categories = $_POST['postCategory'];

    $old_date = $_POST['postDate'];


	if(get_option('date_format') == 'm/d/Y' && strtotime($old_date) != false){
				$array = explode('/', $old_date);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$old_date = $array[1];
				}else{
				$old_date = implode('/', $array);
				}
			}

			if(get_option('date_format') == 'd/m/Y' && strtotime($old_date) != false){
				$array = explode('/', $old_date);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				if($array[0] == NULL){
					$old_date = $array[1];
				}else{
				$old_date = implode('/', $array);
				}
			}


	if (strpos( $old_date , "/") !== false) {
  		$parseddate = str_replace('/' , '-' , $old_date);
	}else{
		$parseddate = $old_date;
	}

    $old_date_timestamp = strtotime($parseddate);
    $project_date = date(get_option('date_format'), $old_date_timestamp);

	$project_amount = $_POST['amount'];
	$project_currency = $_POST['currency'];
	$project_postImage = $_POST['postImage'];
    $project_postBackground = $_POST['postBackground'];
	$project_video = $_POST['postEmbed'];

    if(trim($_POST['postTitle']) === '') {
        $postTitleError = esc_html__('Please enter a title.', 'fundingpress');
        $hasError = true;
    } else {
        $postTitle = trim($_POST['postTitle']);
    }

    $post_information = array(
        'post_title' => $_POST['postTitle'],
        'post_content' => $_POST['postStory'],
        'post_type' => 'project',
        'post_author' => $current_user->ID,
    );

    if (($_POST['submit'] == 'draft' || $_POST['submit'] == 'pending' || $_POST['submit'] == 'publish') AND (!isset($_GET['edit']))) {



        $post_id = wp_insert_post($post_information);

		$user = get_user_by( 'id', $current_user->ID );
		$mail_test = esc_html__('User', 'fundingpress').' '.$user->user_login.' '.__(' just created new project:','fundingpress').' <a href="'.get_edit_post_link($post_id).'">'.$_POST['postTitle'].'</a>';
		funding_send_mail(1, esc_html__('New project submit', 'fundingpress'), $mail_test );
        if($post_id) {
            $thepostid = $post_id;
            if ($_POST['postCategory']) {
                $test = wp_set_object_terms($post_id, $_POST['postCategory'], 'project-category' );
            }

          //add fields

			 if ($project_currency!='') { $project_settings['currency'] = $project_currency; }
			 if ($project_date!='') { $project_settings['date'] = $project_date; }
			 if ($project_amount!='') { $project_settings['target'] = $project_amount; }
			 if ($project_video!='') {  add_post_meta($post_id, '_smartmeta_video-link-field', $project_video); }

				add_post_meta($post_id, 'settings', $project_settings, true);
                add_post_meta($post_id, 'datum',  $project_settings['date']);

            //here we add photo, if the photo was set
			if ($project_postImage != '') {
				$filename   = basename($project_postImage);
				$wp_filetype = wp_check_filetype( $project_postImage, null );
				$attachment = array(
					 'post_mime_type' => $wp_filetype['type'],
					 'post_title'     => sanitize_file_name( $filename ),
					 'post_content'   => '',
					 'post_status'    => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $project_postImage, $post_id );
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id,$project_postImage );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				set_post_thumbnail( $post_id, $attach_id );
			}

            if ($project_postBackground != '') {
                $filename   = basename($project_postBackground);
                $wp_filetype = wp_check_filetype( $project_postBackground, null );
                $attachment = array(
                     'post_mime_type' => $wp_filetype['type'],
                     'post_title'     => sanitize_file_name( $filename ),
                     'post_content'   => '',
                     'post_status'    => 'inherit'
                );
                $attach_id = wp_insert_attachment( $attachment, $project_postBackground, $post_id );
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata( $attach_id,$project_postBackground );
                wp_update_attachment_metadata( $attach_id, $attach_data );
				//add_post_meta($post_id, 'page-background-campaigns', $imageurl, true);
				MultiPostThumbnails::set_meta($post_id, "projects", "page-background-projects", $attach_id);
				add_post_meta($post_id, '_campaign_link', $campaign_link, true);
                //set_post_thumbnail( $post_id, $attach_id );
            }


			$counter = 0;
			$found = false;
			foreach ($holder as $reward) {
				$id = wp_insert_post(array(
					'post_title' => $reward['title'],
					'post_parent' => $post_id,
					'post_content' => $reward['description'],
					'post_type' => 'reward',
					'post_status' => 'publish',
					'comment_status' => 'open'
				));
				if ($reward['title'] == esc_html__('No reward', 'fundingpress')) {
					$found = true;
				}
				update_post_meta($id, 'available', intval($reward['available']), true);
				update_post_meta($id, 'funding_amount', intval($reward['minimum']), true);
				$counter ++;
			}


				if (($found == false) AND ($counter ==0)) {
					 $id = wp_insert_post(array(
	                    'post_title' => esc_html__('No reward', 'fundingpress'),
	                    'post_parent' => $post_id,
	                    'post_content' => esc_html__('I don\'\t want reward!', 'fundingpress'),
	                    'post_type' => 'reward',
	                    'post_status' => 'publish',
	                    'comment_status' => 'open'
	                ));
	                update_post_meta($id, 'available', 9999999999, true);
	                update_post_meta($id, 'funding_amount', 0, true);

				}




            $insertSuccess = true;
        }
		if ($_SESSION['theuser']['premium'] == 1) {
			add_post_meta($post_id, '_smartmeta_staff-check-field', "true", true);
		}


    }
    else if (($_POST['submit'] == 'draft' && isset($_POST['pid'])) or ($_POST['submit'] == 'publish' && $_GET['edit'] == 'true' )) {
    	$counter = 0;
		$holder = Array();


		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 6) == "title_"){
				$holdid = str_replace("title_", "", $key);
				$holder[$counter]['title']= $value;
				$holder[$counter]['description']= $_POST['description_'.$holdid];
				$holder[$counter]['minimum']= $_POST['minimum_'.$holdid];
				$holder[$counter]['available']= $_POST['available_'.$holdid];
				$counter ++;
			}
		}
        $post = get_post($_POST['pid']);


        if (($post->ID == $_POST['pid']) && ($post->post_author == $current_user->ID)) {

        	$post_information['ID'] = $_POST['pid'];



			$updated_post_id = wp_update_post( $post_information );
			if($updated_post_id) {
				$post_id = $updated_post_id;
				if ($_POST['postCategory']) {
	                $test = wp_set_object_terms($post_id, $_POST['postCategory'], 'project-category' );
	            }

				 if ($project_currency!='') { $project_settings['currency'] = $project_currency; }
				 if ($project_date!='') { $project_settings['date'] = $project_date; }
				 if ($project_amount!='') { $project_settings['target'] = $project_amount; }
				 if ($project_video!='') {  update_post_meta($post_id, '_smartmeta_video-link-field', $project_video); }
				update_post_meta($post_id, 'settings', $project_settings);
                update_post_meta($post_id, 'datum',  $project_settings['date']);

				//here we add photo, if the photo was set
				if ($project_postImage != '') {
					$filename   = basename($project_postImage);
					$wp_filetype = wp_check_filetype( $project_postImage, null );
					$attachment = array(
						 'post_mime_type' => $wp_filetype['type'],
						 'post_title'     => sanitize_file_name( $filename ),
						 'post_content'   => '',
						 'post_status'    => 'inherit'
					);
					$attach_id = wp_insert_attachment( $attachment, $project_postImage, $post_id );
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$attach_data = wp_generate_attachment_metadata( $attach_id,$project_postImage );
					wp_update_attachment_metadata( $attach_id, $attach_data );
					set_post_thumbnail( $post_id, $attach_id );
				}

	            if ($project_postBackground != '') {
	                $filename   = basename($project_postBackground);
	                $wp_filetype = wp_check_filetype( $project_postBackground, null );
	                $attachment = array(
	                     'post_mime_type' => $wp_filetype['type'],
	                     'post_title'     => sanitize_file_name( $filename ),
	                     'post_content'   => '',
	                     'post_status'    => 'inherit'
	                );
	                $attach_id = wp_insert_attachment( $attachment, $project_postBackground, $post_id );
	                require_once(ABSPATH . 'wp-admin/includes/image.php');
	                $attach_data = wp_generate_attachment_metadata( $attach_id,$project_postBackground );
	                wp_update_attachment_metadata( $attach_id, $attach_data );
					//add_post_meta($post_id, 'page-background-campaigns', $imageurl, true);
					MultiPostThumbnails::set_meta($post_id, "projects", "page-background-projects", $attach_id);
					add_post_meta($post_id, '_campaign_link', $campaign_link, true);
	                //set_post_thumbnail( $post_id, $attach_id );
	            }


				$oldrewards = get_children(array(
	                'post_parent' => $updated_post_id,
	                'post_type' => 'reward',
	                'order' => 'ASC',
	                'orderby' => 'meta_value_num',
	                'meta_key' => 'funding_amount',
	            ));
				foreach ($oldrewards as $rewarded) {
					wp_delete_post($rewarded->ID, true);
				}


				foreach ($holder as $reward) {
					$id = wp_insert_post(array(
						'post_title' => $reward['title'],
						'post_parent' => $post_id,
						'post_content' => $reward['description'],
						'post_type' => 'reward',
						'post_status' => 'publish',
						'comment_status' => 'open'
					));
					update_post_meta($id, 'available', intval($reward['available']), true);
					update_post_meta($id, 'funding_amount', intval($reward['minimum']), true);
				}

				$insertSuccess = true;
			}
        }
    }


}

?>
<?php get_header(); ?>
<?php
$thumb = get_post_thumbnail_id();
$img_url = wp_get_attachment_url( $thumb,'full');
?>
<?php if(!empty($img_url)){ ?>
<style>
    html{
    background-image:url(<?php echo esc_url($img_url); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php }else{ ?>
<?php if (of_get_option('page_header')!=""){ ?>
	<style>
    html{
    background-image:url(<?php echo esc_url(of_get_option('page_header')); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php } ?>
<?php } ?>
<?php
if (($workedInsert == true) AND ($insertSuccess = true)) {

			if(of_get_option('autopr') == '1' && $_POST['submit'] == 'publish'){
				$post_information['post_status']= 'publish';
			}elseif(of_get_option('autopr') == '0' && $_POST['submit'] == 'publish'){
				$post_information['post_status']= 'pending';
			}else{
				$post_information['post_status']= $_POST['submit'];
			}

	if(isset($_GET["pid"])){ $idpost = $_GET["pid"]; }else{ $idpost = $thepostid; }
	$post_information['ID'] = $idpost;


    wp_update_post( $post_information);
	update_post_meta($idpost, 'settings', $project_settings);

	wp_redirect(get_permalink( get_page_by_path( 'my-account' ) ));
}
?>
<div class=" page normal-page sub-project">
	<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div id="primary">
            <form action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
			<?php if(!empty($er)){ ?>
			<fieldset class="lp-info">
				<i class="fa fa-exclamation-circle"></i>	<?php echo esc_attr($er); ?>
			</fieldset>
			<?php } ?>
             <fieldset>

                <label for="postTitle"><?php esc_html_e('Project Title:', 'fundingpress') ?></label>
                <input type="text" name="postTitle" id="postTitle" value="<?php if(isset($project_title)) echo esc_attr($project_title);?>" class="required" />



                <?php if($postTitleError != '') { ?>
                <span class="error"><?php echo esc_attr($postTitleError); ?></span>
                <div class="clearfix"></div>
            <?php } ?>
            </fieldset>

<div id="HiddenUploader" style="width:0;height:0;overflow:hidden;"></div>
            <fieldset>

                <label for="postImage"><?php esc_html_e('Campaign photo:', 'fundingpress') ?></label>



				<?php esc_html_e('Image needs to be at least 360x240px', 'fundingpress') ?>
				<?php $baseurl = get_template_directory_uri().'/include/'; ?>

				 <div id="flash"></div>
					<div id="ajaxresult">


					</div>
					<div id="files">
<?php if(isset($campaign_thumbnail)) {
					echo esc_url($campaign_thumbnail);
					}  ?>
				 </div>
				<span id="me" class="styleall button-small fileinput-button" style="display: none; cursor:pointer;">

						<?php if(isset($campaign_thumbnail)) {
					esc_html_e('Click Here To Upload New Photo', 'fundingpress');
				} else {
					esc_html_e('Click Here To Upload Photo', 'fundingpress');
				}
				?><input id="meimg" type="file" name="files[]" >

				</span>


				<div id="load"></div><span id="mestatus" ></span>


				<div id="status"></div>
				<div id="cropme" style="display:none;"><a class="button-small crop-me"><?php esc_html_e('CROP IMAGE', 'fundingpress'); ?></a></div>

				<div id="cancelme0" style="display:none;"><a class="button-small"><?php esc_html_e('Cancel', 'fundingpress'); ?></a></div>


				<div id="cancelme" style="display:none;"><a class="button-small"><?php esc_html_e('Cancel', 'fundingpress'); ?></a></div>
                <br>
             <input type="hidden" name="postImage" id="postImage" value=""/>

            </fieldset>

           <fieldset>

                <label for="postEmbed"><?php esc_html_e('Project video', 'fundingpress') ?></label>
                <label for="postEmbed"><?php esc_html_e('Add your project embed video URL here:', 'fundingpress') ?></label>
                <textarea name="postEmbed" id="postEmbed" ><?php if(isset($project_video)) echo $project_video;?></textarea>

            </fieldset>


            <fieldset>
                <label for="postCategory"><?php esc_html_e('Project Category:', 'fundingpress') ?></label>

            <?php
                if(!isset($tax)){$tax='';}
                $args = array(
                    'orderby'           => 'name',
                    'show_count'        => 0,
                    'pad_counts'        => 0,
                    'hierarchical'      => 1,
                    'taxonomy'          => $tax,
                    'title_li'          => '',
                    'hide_empty'        => 0
                );

                $terms = get_terms('project-category', $args);

                if (count($terms) > 0) {
                    echo '<select name="postCategory" class="postCategory" id="postCategory">';
                    foreach ( $terms as $term ) {
                    	if ( isset( $project_categories ) && is_array( $project_categories ) ) {
	                    	foreach ( $project_categories as $category) {
	                            if ($category->slug == $term->slug) {
	                                echo '<option value="'.$term->slug.'" selected="selected">'.$term->name.'</option>';
	                                continue 2;
	                            }
	                        }
                    	}
                        echo '<option value="'.$term->slug.'">'.$term->name.'</option>';

                    }
                    echo '</select>';
                }

            ?>
            </fieldset>

<div class="rewardnasubmitu">
<fieldset>
	<label for="add_reward"><?php esc_html_e('Add a reward', 'fundingpress') ?></label>
		<div id="add_reward" class="button-small button-inverted"><?php esc_html_e('ADD REWARD', 'fundingpress'); ?></div>
		<div id="rewards_holder">
			<?php
			$counter = 10000;
			if ((isset($rewards)) AND (is_array($rewards))) {
				foreach ($rewards as $reward) {
					$hold[$counter]['title'] = $reward->post_title;
					$hold[$counter]['desc'] = $reward->post_content;
					$hold[$counter]['available'] = get_post_meta($reward->ID, 'available', true);
					$hold[$counter]['amount'] = get_post_meta($reward->ID, 'funding_amount', true);

					echo '<div class="reward_holder" id="reward_'. esc_attr($reward->ID).'">
					<label for="title_'.esc_attr($reward->ID).'" >'.esc_html__("Reward title","fundingpress").'</label>
					<input type="text" id="title_'.esc_attr($reward->ID).'" name="title_'.esc_attr($reward->ID).'" value="'.esc_attr($reward->post_title).'" class="required">
					<label for="description_' .esc_attr($reward->ID).'">'.esc_html__("Reward description","fundingpress").'</label>
					<textarea id="description_' . esc_attr($reward->ID) . '" name="description_'. esc_attr($reward->ID) .'" class="required">'.esc_attr($reward->post_content).'</textarea>
					<label for="minimum_'.esc_attr($reward->ID).'">'.esc_html__("Minimum Amount","fundingpress").'</label>
					<input type="text" id="minimum_'.esc_attr($reward->ID).'" name="minimum_'.esc_attr($reward->ID).'" value="'.get_post_meta($reward->ID, 'funding_amount', true).'" class="required">
					<label for="available_'.esc_attr($reward->ID) . '">'.esc_html__("Number Available","fundingpress").'</label>
					<input type="text" id="available_'.esc_attr($reward->ID).'" name="available_' .esc_attr($reward->ID). '" value="'.get_post_meta($reward->ID, 'available', true).'"  class="required">
					<div class="removeme button-small button-inverted" onclick="RemoveMe('.esc_attr($reward->ID).')" id="'.esc_attr($reward->ID).'">'.esc_html__("REMOVE ME","fundingpress").'</div></div>';
				}
			}
			?>


		</div>
</fieldset>
</div>
<fieldset>
	<label for="currency"><?php esc_html_e("Currency", 'fundingpress'); ?></label>
		<select onchange="get_currency_sign(this.value);" name="currency" id="currency" <?php disabled(!empty($funders)) ?>>
			<?php global $f_currencies; foreach($f_currencies as $key => $name) : ?>
				<option value=<?php print $key ?> <?php if(isset($project_currency))selected($project_currency, $key) ?>><?php echo esc_attr($name); ?></option>
			<?php endforeach; ?>
		</select>
</fieldset>



<fieldset>
	<label for="amount"><?php esc_html_e("Target", 'fundingpress'); ?></label>
	<input type="text" name="amount" id="amount" value="<?php if(isset($project_amount)) echo esc_attr($project_amount);?>" class="required" />
</fieldset>

<fieldset>
   <label for="projectdate"><?php esc_html_e("Date", 'fundingpress'); ?></label>
   <input name="postDate" id="postDate" class="date_expiration" value="<?php if(isset($project_date)) echo esc_attr($project_date);?>" />
   <div class="description"><?php esc_html_e('Date that funding ends.', 'fundingpress') ?></div>
</fieldset>
            <fieldset>

                <label for="postStory"><?php esc_html_e('Edit Your Story:', 'fundingpress') ?></label>

                <?php
                    if(isset($project_story)) {
                        if(function_exists('stripslashes')) {
                            $postStory = stripslashes($project_story);
                        }
                        else {
                            $postStory = $project_story;
                        }
                    }

                    $wp_editor_settings = array(
                        'textarea_name' => 'postStory',
                        'media_buttons' => true,
                        'editor_class' => 'widefat',
                        'textarea_rows' => 10,
                        'teeny' => true);
                     if(!isset($postStory)){$postStory='';}
                    if ( current_user_can('contributor') && !current_user_can('upload_files') )

    add_action('admin_init', 'allow_contributor_uploads');



function allow_contributor_uploads() {

    $contributor = get_role('contributor');

    $contributor->add_cap('upload_files');

}
                    wp_editor($postStory, "postStory", $wp_editor_settings);

                ?>

            </fieldset>

        <div class="clear"></div>
        </div>
 </div>
         <div class="col-md-4 col-sm-12 sidebar">


               <div class="pb-container project-card">
            <div class="pb-category"><?php esc_html_e('Category', 'fundingpress'); ?></div>
            <div class="pb-image project-thumb-wrapper">
            	 <?php
              $autorpic = get_the_author_meta('profile_pic', get_current_user_id());
              if(!empty($autorpic)){
               $image = aq_resize( $autorpic,  250, 250, true, true, true ); //resize & crop img
              	if (!isset ($image[0])) {
              		$theimage = $autorpic;
              	} else {
              		$theimage = $image;
              	}
               ?><img class="userimg" src="<?php echo esc_url($theimage); ?>" />
               <?php }else{ ?>
               <img class="userimg" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_user.png" />
               <?php } ?>

                 <?php if (isset($_GET['pid']) && $_GET['edit'] == 'true') {?>
                 <?php if(empty($campaign_thumbnail)){ ?>
                   <img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
                <?php }else{ echo $campaign_thumbnail; } ?>
                <?php }else{ ?>
                <img class="pbimage" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/defaults/default_project.jpg">
                 <?php } ?>
          </div>
            <div class="pb-title bbcard_name">
                <?php if (isset($_GET['pid']) && $_GET['edit'] == 'true') {?>
                <h3><?php if(isset($campaign_title)) echo esc_attr($campaign_title);?></h3>
                <?php }else{ ?>
                <h3><?php esc_html_e('Title goes here', 'fundingpress'); ?><?php if(isset($campaign_title)) echo esc_attr($campaign_title);?></h3>
                <?php } ?>
                <?php if(empty($_SESSION['social_user']['name'])){ $owner = get_the_author_meta('first_name',get_current_user_id()).' '.get_the_author_meta('last_name',get_current_user_id()); }else{ $owner = $_SESSION['social_user']['name']; } ?>
            <p class="pb-content">&ldquo;
                  <?php if (isset($_GET['pid']) && $_GET['edit'] == 'true') {?>
                  <?php if(isset($campaign_content)) echo $campaign_content;?>
                  <?php }else{ ?>
                  <?php esc_html_e('Your message goes here', 'fundingpress'); ?><?php if(isset($campaign_content)) echo $campaign_content;?>
                  <?php } ?>
                  &rdquo;</p>
            <div class="progress progress-striped active bar-green"><div style="width: 100%" class="bar"></div></div>
            <ul class="pb-summary project-stats">
                <li class="pb-funded first funded">
                   <strong> 0% </strong><?php esc_html_e('funded','fundingpress'); ?></h4>
                </li>
                <li class="pb-target pledged">
                   <strong> <span class="cu">$</span> <span class="am">0 </span></br> </strong><?php esc_html_e('target','fundingpress'); ?>
                </li>
                <li class="pb-left">
                 <strong><div id="daysLeft"><script>jQuery( document ).ready(function() {DaysLeft();});</script>999</div> <span></strong><?php esc_html_e('days left','fundingpress'); ?>
                </li>
                <div class="clear"></div>
            </ul>
			<div class="clear"></div>
        </div>
              <fieldset class="sub-bar">

                <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

                <input type="hidden" name="submitted" id="submitted" value="true" />


                <?php

                if (isset($_GET['edit']) == 'true') {
                ?>
                    <input type="hidden" name="pid" value="<?php echo esc_attr($_GET['pid']); ?>">
                    <button name="submit" class="button-small" value="draft" type="submit"><?php esc_html_e('Save', 'fundingpress') ?></button>
                     <button name="submit" class="button-small" value="publish" type="submit"><?php esc_html_e('Publish', 'fundingpress') ?></button>

                    <a href="<?php echo get_permalink( get_page_by_path( 'my-account' ) ); ?>"><?php esc_html_e('Cancel', 'fundingpress') ?></a>
                <?php
                }
                else {
                ?>
                <button name="submit" class="button-small button-inverted" value="draft" type="submit"><?php esc_html_e('Save', 'fundingpress') ?></button>
                <button name="submit" class="button-small" value="publish" type="submit"><?php esc_html_e('Publish', 'fundingpress') ?></button>

                <a href="<?php echo get_permalink( get_page_by_path( 'my-account' ) ); ?>"><?php esc_html_e('Cancel', 'fundingpress') ?></a>
                <?php
                }
                ?>
            </fieldset>
          </div>
    </div>
    </div>
</div>
 </form>

<?php get_footer(); ?>
