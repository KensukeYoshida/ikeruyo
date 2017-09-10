<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */
function optionsframework_option_name() {
    // This gets the theme name from the stylesheet (lowercase and without spaces)
    $themename = wp_get_theme();
    $themename = $themename['Name'];
    $themename = preg_replace("/\W/", "", strtolower($themename) );
    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);
}
function optionsframework_options() {
    // Slider Options
    $slider_choice_array = array("none" => "No Showcase", "accordion" => "Accordion", "wpheader" => "WordPress Header", "image" => "Your Image", "easing" => "Easing Slider", "custom" => "Custom Slider");
    // Pull all the categories into an array
    $options_categories = array();
    $options_categories_obj = get_categories();
    foreach ($options_categories_obj as $category) {
        $options_categories[$category->cat_ID] = $category->cat_name;
    }
    // Pull all the pages into an array
    $options_pages = array();
    $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
    $options_pages[''] = 'Select a page:';
    foreach ($options_pages_obj as $page) {
        $options_pages[$page->ID] = $page->post_title;
    }
    // If using image radio buttons, define a directory path
    $radioimagepath =  get_stylesheet_directory_uri() . '/themeOptions/images/';
    // define sample image directory path
    $imagepath =  get_template_directory_uri() . '/images/demo/';
    $options = array();
    $options[] = array( "name" => esc_html__("General  Settings",'fundingpress'),
                        "type" => "heading");
	$options[] = array( "name" => esc_html__("General  Settings", 'fundingpress'),
                        "type" => "info");

    $options[] = array( "name" => esc_html__("Upload Your Logo",'fundingpress'),
                        "desc" => esc_html__("Upload your logo. I recommend keeping it within reasonable size. Max 150px and minimum height of 90px but not more than 120px.",'fundingpress'),
                        "id" => "logo",
                        "std" => get_template_directory_uri()."/img/logo.png",
                        "type" => "upload");
	$options[] = array( "name" => esc_html__("Upload default page image",'fundingpress'),
                        "desc" => esc_html__("Upload default image that will be shown in page header part. This image will be used only if there is no featured image.",'fundingpress'),
                        "id" => "page_header",
                        "std" => get_template_directory_uri()."/img/fbg.jpg",
                        "type" => "upload");


//social settings
       $options[] = array( "name" => esc_html__("Social Settings", 'fundingpress'), "type" => "heading");
  $options[] = array( "name" => esc_html__("Social Settings - Facebook", 'fundingpress'),
                        "type" => "info");
      $options[] = array( "name" => esc_html__("Turn on Facebook", 'fundingpress'),
                        "desc" => esc_html__("Use Facebook for social login", 'fundingpress'),
                        "id" => "facebook_btn",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Facebook App ID", 'fundingpress'),
                        "desc" => esc_html__("Add your Facebook App ID here", 'fundingpress'),
                        "id" => "facebook_app",
                        "std" => "Facebook app ID",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Facebook App Secret", 'fundingpress'),
                        "desc" => esc_html__("Add your Facebook App Secret here", 'fundingpress'),
                        "id" => "facebook_secret",
                        "std" => "Facebook Secret",
                        "type" => "text");

      $options[] = array( "name" => esc_html__("Social Settings - Twitter", 'fundingpress'),
                        "type" => "info");
     $options[] = array( "name" => esc_html__("Turn on Twitter", 'fundingpress'),
                        "desc" => esc_html__("Use Twitter for social login", 'fundingpress'),
                        "id" => "twitter_btn",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Twitter App ID", 'fundingpress'),
                        "desc" => esc_html__("Add your Twitter API Key here", 'fundingpress'),
                        "id" => "twitter_app",
                        "std" => "Twitter API key",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Twitter API Secret", 'fundingpress'),
                        "desc" => esc_html__("Add your Twitter API Secret here", 'fundingpress'),
                        "id" => "twitter_secret",
                        "std" => "Twitter Secret",
                        "type" => "text");

      $options[] = array( "name" => esc_html__("Social Settings - Google+", 'fundingpress'),
                        "type" => "info");
     $options[] = array( "name" => esc_html__("Turn on Google+", 'fundingpress'),
                        "desc" => esc_html__("Use Google+ for social login", 'fundingpress'),
                        "id" => "google_btn",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Google+ App ID", 'fundingpress'),
                        "desc" => esc_html__("Add your Google+ API Key here", 'fundingpress'),
                        "id" => "google_app",
                        "std" => "Google+ API key",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Google+ API Secret", 'fundingpress'),
                        "desc" => esc_html__("Add your Google+ API Secret here", 'fundingpress'),
                        "id" => "google_secret",
                        "std" => "google+ Secret",
                        "type" => "text");

// Funding section
    $options[] = array( "name" => esc_html__("Funding",'fundingpress'),
                        "type" => "heading");
	$options[] = array( "name" => esc_html__("Funding Settings", 'fundingpress'),
                        "type" => "info");
    $options[] = array( "name" => esc_html__("Add Text",'fundingpress'),
                        "desc" => esc_html__("Enter the important text on the commit to funding page.",'fundingpress'),
                        "id" => "important_text",
                        "type" => "textarea");
/* $options[] = array( "name" => esc_html__("Enable collect fundings",'fundingpress'),
                        "desc" => esc_html__("Enable users to collect fundings before project ends.",'fundingpress'),
                        "id" => "colfun",
                        "std" => "0",
                        "type" => "jqueryselect");
			*/

$options[] = array( "name" => esc_html__("Use reward system",'fundingpress'),
                        "desc" => esc_html__("Enable users to collect rewards for funding projects.",'fundingpress'),
                        "id" => "rewards",
                        "std" => "1",
                        "type" => "jqueryselect");

$options[] = array( "name" => esc_html__("Users can collect funds",'fundingpress'),
                        "desc" => esc_html__("Enable users to collect funds for projects.",'fundingpress'),
                        "id" => "user_collect",
                        "std" => "0",
                        "type" => "jqueryselect");


$options[] = array( "name" => esc_html__("Collect funding",'fundingpress'),
                        "desc" => esc_html__("If this option is off users will be able to collect funds at any time. If this option is on users will be able to collect funds only in successful projects.",'fundingpress'),
                        "id" => "collect_funding",
                        "std" => "0",
                        "type" => "jqueryselect");


$options[] = array( "name" => esc_html__("Use PayPal system",'fundingpress'),
                        "desc" => esc_html__("Enable users to fund projects using PayPal.",'fundingpress'),
                        "id" => "paypal",
                        "std" => "1",
                        "type" => "jqueryselect");


$options[] = array( "name" => esc_html__("Use WePay system",'fundingpress'),
                        "desc" => esc_html__("Enable users to fund projects using WePay.",'fundingpress'),
                        "id" => "wepay",
                        "std" => "1",
                        "type" => "jqueryselect");
$options[] = array( "name" => esc_html__("Use Stripe system",'fundingpress'),
                        "desc" => esc_html__("Enable users to fund projects using Stripe.",'fundingpress'),
                        "id" => "stripe",
                        "std" => "1",
                        "type" => "jqueryselect");

$options[] = array( "name" => esc_html__("Auto publish projects",'fundingpress'),
                        "desc" => esc_html__("Allow automatic publishing of projects",'fundingpress'),
                        "id" => "autopr",
                        "std" => "0",
                        "type" => "jqueryselect");
	$options[] = array( "name" => esc_html__("Email Settings", 'fundingpress'),
                        "type" => "info");
$allowed_tags = array(
	'br' => array(),
);


/*$options[] = array( "name" => esc_html__("Thanks for funding email template",'fundingpress'),
                        "desc" => esc_html__("Thanks for funding email template.",'fundingpress'),
                        "id" => "tff",
                        "std" => wp_kses(esc_html__("Thanks For Funding %s", 'fundingpress'), $allowed_tags ),
                        "type" => "textarea");
*/

$options[] = array( "name" => esc_html__("Funded to project author, email template",'fundingpress'),
                        "desc" => esc_html__("Email template that will be sent to project author.",'fundingpress'),
                        "id" => "f2a",
                        "std" => wp_kses(__("
Hi %s<br/>
Oh yeah! %s has just commited to funding your project - %s.<br/>
<br/>
With this contribution, you've raised %s (%u%%) of your %s target. You still have %s.<br/>
<br/>
Funder ID:				%u<br/>
Name:					%s<br/>
Email:					%s<br/>
Amount:					%s<br/>
Preapproval Key:		%s<br/>
Reward:					%s<br/>
Message:				%s<br/>
<br/>
Don't forget to email them and say thanks. That'll encourage them to share your project with their friends and followers.<br/>
<br/>
Cheers!", 'fundingpress'), $allowed_tags ),
                        "type" => "textarea");


$options[] = array( "name" => esc_html__("Funded to funder, email template",'fundingpress'),
                        "desc" => esc_html__("Email template that will be sent to funder.",'fundingpress'),
                        "id" => "f2f",
                        "std" => wp_kses(__("
Hi %s<br/>
<br/>
Thanks for funding our project - %s. You've helped us reach %u%% of our %s target with %s. For your records, here are your details:<br/>
<br/>
Funder ID:				%u<br/>
Name:					%s<br/>
Email:					%s<br/>
Amount:					%s<br/>
Preapproval Key:		%s<br/>
Reward:					%s<br/>
Message:				%s<br/>
<br/>
If we reach our target, we'll contact you with details about your reward. Please share this project with your friends and followers. We need your help to reach our target.<br/>
<br/>
%s<br/>
Thanks,<br/>
%s<br/>
%s", 'fundingpress'), $allowed_tags ),
                        "type" => "textarea");

/*
$options[] = array( "name" => esc_html__("Project successful to project author, email template",'fundingpress'),
                        "desc" => esc_html__("Email template that will be sent to project author when project is successful.",'fundingpress'),
                        "id" => "s2a",
                        "std" => wp_kses(__("
Yay %s<br/>
<br/>
Your project - %s has been successfully funded. You reached %u%% of your %s target with %s. Don't forget to withdraw funds! Cheers.<br/>
Thanks,<br/><br/>
%s<br/>
%s", 'fundingpress'), $allowed_tags ),
                        "type" => "textarea");

$options[] = array( "name" => esc_html__("Project successful to funders, email template",'fundingpress'),
                        "desc" => esc_html__("Email template that will be sent to funders.",'fundingpress'),
                        "id" => "s2f",
                        "std" => wp_kses(__("
Hi %s<br/>
<br/>
Oh yeah! The project you have supported (%s) has just been funded.<br/><br/>
Please be sure that you have enough funds to be withdrawn!<br/><br/>
Thanks,<br/>
%s<br/>
%s", 'fundingpress'), $allowed_tags ),
                        "type" => "textarea");

*/

// Social Media
    $options[] = array( "name" => esc_html__("Social Media",'fundingpress'),
                        "type" => "heading");
	$options[] = array( "name" => esc_html__("Social Media", 'fundingpress'),
                        "type" => "info");
// Social Network setup
    /*$options[] = array( "name" => "Facebook App ID",
                        "desc" => "Add your Facebook App ID here",
                        "id" => "facebook_app",
                        "std" => "1234567890",
                        "type" => "text");
*/
    $options[] = array( "name" => esc_html__("Enable Twitter",'fundingpress'),
                        "desc" => esc_html__("Show or hide the Twitter icon that shows on the header section.",'fundingpress'),
                        "id" => "twitter",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Twitter Link",'fundingpress'),
                        "desc" => esc_html__("Paste your twitter link here.",'fundingpress'),
                        "id" => "twitter_link",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable Facebook",'fundingpress'),
                        "desc" => esc_html__("Show or hide the Facebook icon that shows on the header section.",'fundingpress'),
                        "id" => "facebook",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Facebook Link",'fundingpress'),
                        "desc" => esc_html__("Paste your facebook link here.",'fundingpress'),
                        "id" => "facebook_link",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable Google+",'fundingpress'),
                        "desc" => esc_html__("Show or hide the Google+ icon that shows on the header section.",'fundingpress'),
                        "id" => "googleplus",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Google+ Link",'fundingpress'),
                        "desc" => esc_html__("Paste your google+ link here.",'fundingpress'),
                        "id" => "google_link",
                        "std" => "#",
                        "type" => "text");
	$options[] = array( "name" => esc_html__("Enable Instagram",'fundingpress'),
                        "desc" => esc_html__("Show or hide the Instagram icon that shows on the header section.",'fundingpress'),
                        "id" => "instagram",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Instagram link",'fundingpress'),
                        "desc" => esc_html__("Paste your Instagram link here.",'fundingpress'),
                        "id" => "instagram_link",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable Skype",'fundingpress'),
                        "desc" => esc_html__("Show or hide the Skype icon that shows on the header section.",'fundingpress'),
                        "id" => "skype",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Skype name",'fundingpress'),
                        "desc" => esc_html__("Paste your Skype name here.",'fundingpress'),
                        "id" => "skype_name",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable RSS",'fundingpress'),
                        "desc" => esc_html__("Show or hide the RSS icon that shows on the header section.",'fundingpress'),
                        "id" => "rss",
                        "std" => "0",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("RSS Link",'fundingpress'),
                        "desc" => esc_html__("Paste your RSS link here.",'fundingpress'),
                        "id" => "rss_link",
                        "std" => "#",
                        "type" => "text");
// color Settings
    $options[] = array( "name" => esc_html__("colors",'fundingpress'),
                        "type" => "heading");
	$options[] = array( "name" => esc_html__("Site colors", 'fundingpress'),
                        "type" => "info");
    $options[] = array( "name" => esc_html__("Primary Color",'fundingpress'),
    "desc" => esc_html__("The primary color for the site.",'fundingpress'),
    "id" => "primary_color",
    "std" => "#63e92a",
    "type" => "color" );
$options[] = array( "name" => esc_html__("Button colors",'fundingpress'),
                        "type" => "info");
    //regular
    $options[] = array(
    "name" => esc_html__("Button color",'fundingpress'),
    "desc" => esc_html__("Primary Button color",'fundingpress'),
    "id" => "button_green",
    "std" => "#63e92a",
    "type" => "color");
     $options[] = array(
    "name" => esc_html__("Button hover color",'fundingpress'),
    "desc" => esc_html__("Button hover color",'fundingpress'),
    "id" => "button_hover",
    "std" => "#689c06",
    "type" => "color");
    //border
    $options[] = array(
    "name" => esc_html__("Button border color",'fundingpress'),
    "desc" => esc_html__("Color for button border.",'fundingpress'),
    "id" => "button_border",
    "std" => "#689c06",
    "type" => "color");

	$options[] = array( "name" => esc_html__("Progress bar colors",'fundingpress'),
                        "type" => "info");
    //regular
    $options[] = array(
    "name" => esc_html__("First color",'fundingpress'),
    "desc" => esc_html__("Left side color of the progress bar",'fundingpress'),
    "id" => "pb-first",
    "std" => "#82f3be",
    "type" => "color");
	//regular
    $options[] = array(
    "name" => esc_html__("Secondary color",'fundingpress'),
    "desc" => esc_html__("Right side color of the progress bar",'fundingpress'),
    "id" => "pb-second",
    "std" => "#63e92a",
    "type" => "color");


// Footer section start
    $options[] = array( "name" => esc_html__("Footer",'fundingpress'), "type" => "heading");
	$options[] = array( "name" => esc_html__("Footer", 'fundingpress'),
                        "type" => "info");
                $options[] = array( "name" => esc_html__("Copyright",'fundingpress'),
                        "desc" => esc_html__("Enter your copyright text.",'fundingpress'),
                        "id" => "copyright",
                        "std" => esc_html__("Made by Skywarrior Themes.",'fundingpress'),
                        "type" => "textarea");

                $options[] = array( "name" => esc_html__("Privacy link",'fundingpress'),
                        "desc" => esc_html__("Enter your privacy link. Please include http://",'fundingpress'),
                        "id" => "privacy",
                        "std" => "http://www.skywarriorthemes.com/",
                        "type" => "text");
                $options[] = array( "name" => esc_html__("Terms link",'fundingpress'),
                        "desc" => esc_html__("Enter your terms link. Please include http://",'fundingpress'),
                        "id" => "terms",
                        "std" => "http://www.skywarriorthemes.com/",
                        "type" => "text");


	$options[] = array( "name" => esc_html__("One click install", 'fundingpress'),
                        "type" => "heading");

	$options[] = array( "name" => esc_html__("demo install", 'fundingpress'),
                        "desc" => esc_html__("Click to install pre-inserted demo contents.  *****Use admin/admin to log in*****", 'fundingpress'),
                        "id" => "demo_install",
                        "std" => "0",
                        "type" => "impbutton");

    return $options;
}
?>