<?php

add_filter("manage_edit-project_columns", "fundit_project_columns");
/**
 * Custom columns for the project post type
 * @param array() $columns
 */
function fundit_project_columns($columns){
	return array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => esc_html__("Project Title", 'fundingpress'),
		"status" => esc_html__("Project status", 'fundingpress'),
		"funding-progress" => esc_html__("Progress", 'fundingpress'),
		"funding-time" => esc_html__("Time Remaining", 'fundingpress'),
		"author" => esc_html__("Creator", 'fundingpress'),
		"comments" => '<img src="'.home_url().'/wp-admin/images/comment-grey-bubble.png" alt="Comments" />',
		'date' => esc_html__('Date', 'fundingpress'),
	);
}

add_filter("manage_edit-reward_columns", "fundit_reward_columns");
/**
 * Custom columns for the reward post type
 * @param array() $columns
 */
function fundit_reward_columns($columns){
	return array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => esc_html__("Reward Title", 'fundingpress'),
		'reward-project' => esc_html__('Project', 'fundingpress'),
		'reward-contribution' => esc_html__('Min Contribution', 'fundingpress'),
		'reward-available' => esc_html__('Available', 'fundingpress'),
		"author" => esc_html__("Author", 'fundingpress'),
		'comments' => '<img src="'.home_url().'/wp-admin/images/comment-grey-bubble.png" alt="Comments" />',
		'date' => esc_html__('Date', 'fundingpress'),

	);
}

add_filter("manage_edit-funder_columns", "fundit_funder_columns");
/**
 * Custom columns for the funder post type
 * @param array() $columns
 */
function fundit_funder_columns($columns){
	return array(
		"cb" => "<input type=\"checkbox\" />",
		"funder-name" => esc_html__("Name", 'fundingpress'),
		"funder-amount" => esc_html__("Amount", 'fundingpress'),
		"funder-reward" => esc_html__("Reward", 'fundingpress'),
		"funder-project" => esc_html__("Project", 'fundingpress'),
		"funder-email" => esc_html__("Email", 'fundingpress'),
		'funder-status' => esc_html__('Status', 'fundingpress'),
	);
}

add_action("manage_posts_custom_column", "fundit_custom_columns");
/**
 * Custom column display
 * @param string $column The name of the column
 */
function fundit_custom_columns($column, $post_id){
	global $post;

	switch($column){
		case 'funding-progress':

                global $f_currency_signs;
                $project_settings = (array) get_post_meta($post_id, 'settings', true);

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

		if ( empty( $project_expired ) )
				echo esc_html__( 'Unknown' , 'fundingpress');

			/* If there is a duration, append 'minutes' to the text string. */
			else
				printf( esc_html__( '%s minutes', 'fundingpress' ), $project_expired );

			break;
			break;

		case 'funding-time':

			break;

		// Stuff for the rewards
		case 'reward-project':
			$reward = new Fundit_Model_Reward($post);
			$project = $reward->get_project();
			?><a href="<?php print admin_url('post.php?action=edit&post='.$project->ID) ?>"><?php print $project->post_title ?></a><?php
			break;
		case 'reward-contribution':
			$reward = new Fundit_Model_Reward($post);
			$project = $reward->get_project();

			if(empty($project->contribution)){
				print 'No minimum'; break;
			}
			print $project->get_currency_sign().$project->contribution;
			break;
		case 'reward-available':
			$reward = new Fundit_Model_Reward($post);
			$funders = count($reward->get_funders());
			if($reward->available == 0) {
				print 'Unlimited';
			}
			else{
				print ($reward->available - $funders).' of '.$reward->available;
			}
			print ' <span style="color:#888">('.$funders.' '.($funders == 1 ? 'funder' : 'funders').')</span>';
			break;

		// Stuff for the funders
		case 'funder-name':
			$funder = new Fundit_Model_Funder($post);
			?><strong><a href="mailto:<?php print $funder->email ?>"><?php print $funder->post_title ?></a></strong><?php
			break;
		case 'funder-reward':
			$funder = new Fundit_Model_Funder($post);
			$reward = $funder->get_reward();
			?><a href="<?php print admin_url('post.php?action=edit&post='.$reward->ID) ?>"><?php print $reward->post_title ?></a><?php
			break;
		case 'funder-project':
			$funder = new Fundit_Model_Funder($post);
			$project = $funder->get_project();
			?><a href="<?php print admin_url('post.php?action=edit&post='.$project->ID) ?>"><?php print $project->post_title ?></a><?php
			break;
		case 'funder-amount':
			$funder = new Fundit_Model_Funder($post);
			print $funder->get_currency_sign().$funder->amount;
			break;
		case 'funder-email':
			$funder = new Fundit_Model_Funder($post);
			?><a href="mailto:<?php print $funder->email ?>"><?php print $funder->email ?></a><?php
			break;
		case 'funder-status':
			$funder = new Fundit_Model_Funder($post);
			if($funder->fund_status == 'cancelled'){
				print 'Cancelled';
			}
			else{
				if($funder->post_status == 'draft') print '<strong>'.esc_html__('Awaiting Confirmation', 'fundingpress').'</strong>';
				elseif($funder->post_status == 'publish'){
					if($funder->fund_status == 'funded') print 'Funded';
					else print 'Approved';
				}
			}

			?> &nbsp; <a href="<?php print FUNDIT_PLUGIN_URL_ROOT.'/admin/refresh-funder.php?funder_id='.$funder->ID.'&return='.esc_attr(add_query_arg(null,null)) ?>"><?php esc_html_e("Refresh", 'fundingpress'); ?></a><?php

			if($_GET['funder_updated'] == $funder->ID){
				?><div id="updated" class="updated"><p><?php esc_html_e("Funder", 'fundingpress'); ?> "<?php print $funder->post_title ?>" <?php esc_html_e("status updated.", 'fundingpress'); ?></p></div><?php
			}

			break;
	}
}