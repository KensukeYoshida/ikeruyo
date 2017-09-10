<?php

add_action( 'vc_before_init', 'funding_integrateWithVC' );

function funding_integrateWithVC() {


$categories = get_categories(

array(
        'type'          => 'post',
        'child_of'      => 0,
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => 1,
        'hierarchical'  => 1,
        'taxonomy'      => 'category',
        'pad_counts'    => false

) );

foreach ($categories as $cat) {
    $cats[$cat->cat_name] = $cat->cat_ID;
}
if(!isset($cats))$cats='';


$cat_args = array(
    'orderby'       => 'term_id',
    'order'         => 'ASC',
    'hide_empty'    => true,
);
$categories_project = get_terms('project-category', $cat_args);

foreach ($categories_project as $catpr) {
	if(isset($catpr))
    $catspr[$catpr->name] = $catpr->term_id;
}
if(!isset($catspr))$catspr='';


/* News Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'News Block', 'fundingpress' ),
    'base' => 'vc_column_news',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'fundingpress' ),
    'description' => esc_html__( 'A block for news', 'fundingpress' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'fundingpress' ),
            'param_name' => 'el_news_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your news block.', 'fundingpress' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'fundingpress' ),
            'param_name' => 'el_news_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'fundingpress' ),
            'value' => $cats,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of posts to show', 'fundingpress' ),
            'param_name' => 'el_news_number_posts',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Enter number of posts you wolud like to show in this block.', 'fundingpress' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Extra class name', 'fundingpress' ),
            'param_name' => 'el_class',
            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'fundingpress' )
        ),


    )
) );


/* News Block - Horizontal vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'News Block - Horizontal', 'fundingpress' ),
    'base' => 'vc_column_news_horizontal',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'fundingpress' ),
    'description' => esc_html__( 'A block for horizontal news', 'fundingpress' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'fundingpress' ),
            'param_name' => 'el_news_horizontal_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your news block.', 'fundingpress' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'fundingpress' ),
            'param_name' => 'el_news_horizontal_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'fundingpress' ),
            'value' => $cats,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of posts to show', 'fundingpress' ),
            'param_name' => 'el_news_horizontal_number_posts',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Enter number of posts you wolud like to show in this block.', 'fundingpress' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Extra class name', 'fundingpress' ),
            'param_name' => 'el_class',
            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'fundingpress' )
        ),


    )
) );


/* Contact Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Contact Block', 'fundingpress' ),
    'base' => 'vc_contact',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'fundingpress' ),
    'description' => esc_html__( 'A block with contact form.', 'fundingpress' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'fundingpress' ),
            'param_name' => 'el_contact_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your contact block.', 'fundingpress' )
        )
    )
) );


/* Social Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Social media Block', 'fundingpress' ),
    'base' => 'vc_social',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'fundingpress' ),
    'description' => esc_html__( 'Add social media links', 'fundingpress' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'fundingpress' ),
            'param_name' => 'el_social_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your social media block.', 'fundingpress' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Rss link', 'fundingpress' ),
            'param_name' => 'el_social_rss',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Rss feed.', 'fundingpress' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Dribbble link', 'fundingpress' ),
            'param_name' => 'el_social_dribbble',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Dribbble profile.', 'fundingpress' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Vimeo link', 'fundingpress' ),
            'param_name' => 'el_social_vimeo',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Vimeo profile.', 'fundingpress' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Youtube link', 'fundingpress' ),
            'param_name' => 'el_social_youtube',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Youtube profile.', 'fundingpress' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Twitch link', 'fundingpress' ),
            'param_name' => 'el_social_twitch',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Twitch profile.', 'fundingpress' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Steam link', 'fundingpress' ),
            'param_name' => 'el_social_steam',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Steam profile.', 'fundingpress' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Pinterest link', 'fundingpress' ),
            'param_name' => 'el_social_pinterest',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Pinterest profile.', 'fundingpress' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Google+ link', 'fundingpress' ),
            'param_name' => 'el_social_google',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Google+ profile.', 'fundingpress' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Twitter link', 'fundingpress' ),
            'param_name' => 'el_social_twitter',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Twitter profile.', 'fundingpress' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Facebook link', 'fundingpress' ),
            'param_name' => 'el_social_facebook',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Facebook profile.', 'fundingpress' )
        ),

    )
) );


/* Contact Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Projects Block', 'fundingpress' ),
    'base' => 'vc_projects',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'fundingpress' ),
    'description' => esc_html__( 'A block with projects.', 'fundingpress' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'fundingpress' ),
            'param_name' => 'el_projects_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your projects block.', 'fundingpress' )
        ),
         array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'fundingpress' ),
            'param_name' => 'el_projects_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'fundingpress' ),
            'value' => $catspr,
        ),
         array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Display options', 'fundingpress' ),
            'param_name' => 'el_display_type',
            'value' => array(
            esc_html__('Show both','fundingpress') => 'all_projects',
			esc_html__('Static projects','fundingpress') => 'static_projects',
			esc_html__('Slider projects','fundingpress') => 'slider_projects'
    		),
    		'std' => 'all',
            'description' => esc_html__( 'Choose your display option.', 'fundingpress' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of slides', 'fundingpress' ),
            'param_name' => 'el_projects_slide',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Insert number of slide posts', 'fundingpress' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of static posts', 'fundingpress' ),
            'param_name' => 'el_projects_static',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Insert number of static posts', 'fundingpress' )
        )
    )
) );

/* Contact Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Project Highlight Block', 'fundingpress' ),
    'base' => 'vc_project_highlight',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'fundingpress' ),
    'description' => esc_html__( 'A block with projects.', 'fundingpress' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'fundingpress' ),
            'param_name' => 'el_project_highlight_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your projects block.', 'fundingpress' )
        ),
         array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'fundingpress' ),
            'param_name' => 'el_project_highlight_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'fundingpress' ),
            'value' => $catspr,
        ),
         array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Display options', 'fundingpress' ),
            'param_name' => 'el_display_type',
            'value' => array(
			esc_html__('Latest projects','fundingpress') => 'latest_highlight_projects',
			esc_html__('Staff picks','fundingpress') => 'staff_projects',
			esc_html__('Featured projects','fundingpress') => 'featured_projects',
			esc_html__('Latest successful projects','fundingpress') => 'latest_suc_projects',
			esc_html__('First ending projects','fundingpress') => 'first_ending_projects',

    		),
    		'std' => '',
            'description' => esc_html__( 'Choose your display option.', 'fundingpress' )
        ),
    )
) );
}