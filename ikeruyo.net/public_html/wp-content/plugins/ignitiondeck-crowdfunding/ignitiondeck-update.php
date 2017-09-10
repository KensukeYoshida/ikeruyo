<?php

/*
// TEMP: Enable update check on every request. Normally you don't need this! This is for testing only!
// NOTE: The 
//	if (empty($checked_data->checked))
//		return $checked_data; 
// lines will need to be commented in the check_for_plugin_update function as well.
*/
//set_site_transient('update_plugins', null);

// TEMP: Show which variables are being requested when query plugin API
/*add_filter('plugins_api_result', 'aaa_result', 10, 3);
function aaa_result($res, $action, $args) {
	print_r($res);
	return $res;
}
// NOTE: All variables and functions will need to be prefixed properly to allow multiple plugins to be updated
*/
global $id_api_url, $idcf_license_type, $id_plugin_slug, $id_api_key;
$idcf_license_type = idcf_license_type();
$id_api_url = 'https://ignitiondeck.com/id/pluginserv/';
$id_plugin_slug = basename(dirname(__FILE__));
$id_api_key = get_option('id_license_key');
// Take over the update check
add_filter('pre_set_site_transient_update_plugins', 'check_for_id_update');

function check_for_id_update($checked_data) {
	global $id_api_url, $id_plugin_slug, $wp_version, $id_api_key, $idcf_license_type;

	//Comment out these two lines during testing.
	if (empty($checked_data->checked))
		return $checked_data;
	$args = array(
		'slug' => $id_plugin_slug,
		'version' => $checked_data->checked[$id_plugin_slug .'/' .'ignitiondeck.php'],
	);

	$request_string = array(
			'body' => array(
				'action' => 'basic_check',
				'request' => serialize($args),
				'api-key' => $id_api_key,
				'license_type' => $idcf_license_type
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

	// Start checking for an update
	$raw_response = wp_remote_post($id_api_url, $request_string);

	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);

	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$id_plugin_slug .'/'. 'ignitiondeck.php'] = $response;

	return $checked_data;
}


// Take over the Plugin info screen
add_filter('plugins_api', 'id_api_call', 10, 3);

function id_api_call($def, $action, $args) {
	global $id_plugin_slug, $id_api_url, $wp_version, $id_api_key;

	if (!isset($args->slug) || ($args->slug != $id_plugin_slug))
		return false;

	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$id_plugin_slug .'/'. 'ignitiondeck.php'];
	$args->version = $current_version;

	$request_string = array(
			'body' => array(
				'action' => $action, 
				'request' => serialize($args),
				'api-key' => $id_api_key
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

	$request = wp_remote_post($id_api_url, $request_string);

	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);

		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
	}

	return $res;
}

function idcf_license_type() {
	$is_pro = is_id_pro();
	if ($is_pro) {
		return 'active';
	}
	$was_pro = was_id_pro();
	if ($was_pro) {
		return 'expired';
	}
	return 'free';
}
?>