<?php
/**
 * Generates the options fields that are used in the form.
 */

function import_completed_notice()
{
	echo ' <div class="updated" id="import_comp_noti" style="display:none;"><p>'.esc_html__("Success! The data has been imported successfully.","fundingpress").'</p></div>';
}
function import_incompleted_notice()
{
	echo ' <div class="update-nag" id="import_incomp_noti" style="display:none;"><p>'.esc_html__("To protect your content the demo importer only works when Wordpress is fresh installed. Please re-install Wordpress with the default settings if you want to import the demo data.","fundingpress").'<a href="#" id="close_update_nag"> X</a></p></div>';
}
function optionsframework_fields() {
	global $allowedtags;
	$optionsframework_settings = get_option('optionsframework');
	// Get the theme name so we can display it up top
	$themename = wp_get_theme(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	// Gets the unique option id
	if (isset($optionsframework_settings['id'])) {
		$option_name = $optionsframework_settings['id'];
	}
	else {
		$option_name = 'optionsframework';
	};
	$settings = get_option($option_name);
    $options = optionsframework_options();
    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
		$counter++;
		$val = '';
		$select_value = '';
		$checked = '';
		// Wrap all options
		if ( ($value['type'] != "heading") && ($value['type'] != "info") ) {
			// Keep all ids lowercase with no spaces
			$value['id'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($value['id']) );
			$id = 'section-' . $value['id'];
			$class = 'section ';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}
			$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
			$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
			$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
		 }
		// Set default value to $val
		if ( isset( $value['std']) ) {
			$val = $value['std'];
		}
		// If the option is already saved, ovveride $val
		if ( ($value['type'] != 'heading') && ($value['type'] != 'info')) {
			if ( isset($settings[($value['id'])]) ) {
					$val = $settings[($value['id'])];
					// Striping slashes of non-array options
					if (!is_array($val)) {
						$val = stripslashes($val);
					}
			}
		}
		// If there is a description save it for labels
		$explain_value = '';
		if ( isset( $value['desc'] ) ) {
			$explain_value = $value['desc'];
		}
		switch ( $value['type'] ) {
		// Basic text input
		case 'text':
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break;
		// Textarea
		case 'textarea':
			$cols = '8';
			$ta_value = '';
			if(isset($value['options'])){
				$ta_options = $value['options'];
				if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
				} else { $cols = '8'; }
			}
			$val = stripslashes( $val );
			$output .= '<textarea id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" cols="'. esc_attr( $cols ) . '" rows="8">' . esc_textarea( $val ) . '</textarea>';
		break;
		// Select Box
		case ($value['type'] == 'select'):
			$output .= '<select class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
			foreach ($value['options'] as $key => $option ) {
				$selected = '';
				 if( $val != '' ) {
					 if ( $val == $key) { $selected = ' selected="selected"';}
			     }
				 $output .= '<option'. $selected .' value="' . esc_attr( $key )  . '">' . esc_html( $option ) . '</option>';
			 }
			 $output .= '</select>';
		break;
		// Radio Box
		case "radio":
			$name = $option_name .'['. $value['id'] .']';
			foreach ($value['options'] as $key => $option) {
				$id = $option_name . '-' . $value['id'] .'-'. $key;
				$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
			}
		break;
		// Image Selectors
		case "images":
			$name = $option_name .'['. $value['id'] .']';
			foreach ( $value['options'] as $key => $option ) {
				$selected = '';
				$checked = '';
				if ( $val != '' ) {
					if ( $val == $key ) {
						$selected = ' of-radio-img-selected';
						$checked = ' checked="checked"';
					}
				}
				if(esc_attr( $key ) == 'b1'){
					$rep = 'No repeat';
				}elseif(esc_attr( $key ) == 'b2'){
					$rep = 'Repeat vertically';
				}elseif(esc_attr( $key ) == 'b3'){
					$rep = 'Repeat horizontally ';
				}else{
					$rep = 'Tile';
				}
				$output .= '<input type="radio" id="' . esc_attr( $value['id'] .'_'. $key) . '" class="of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. $checked .' />';
				$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
				$output .= '<a href="#" data-toggle="tooltip" data-placement="top" title="' . $rep . '"><img src="' . esc_url( $option ) . '" alt="' . $option .'" class="of-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($value['id'] .'_'. $key) .'\').checked=true;" /></a>';
			}
		break;
		// Checkbox
		case "checkbox":
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" '. checked( $val, 1, false) .' />';
			$output .= '<label class="explain" for="' . esc_attr( $value['id'] ) . '">' . wp_kses( $explain_value, $allowedtags) . '</label>';
		break;
		//jquery checkbox
		case "jqueryselect":
		?>
<script type="text/javascript">
		jQuery(document).ready(function($) {
		// Start jQuery goodness
			$('#itoggle input#<?php echo esc_js($value['id']); ?>').iToggle({
				easing: 'easeOutExpo',
				onClickOn: function(){
					$('#console').show().css({opacity:0}).animate({opacity:1},400);
					statusUpdate('Console on');
				},
				onClickOff: function(){
					statusUpdate('Console off');
					$('#console').animate({opacity:0},400);
				}
			});
			function statusUpdate(text){
				$('#console').prepend('<p>'+text+'</p>');
			}
		// End jQuery goodness
		});
	</script>
	<?php
		$output .= '<div id="itoggle" class="project"><input type="checkbox" class="checkbox of-input" id="' . esc_attr( $value['id'] ) . '" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" '. checked( $val, 1, false) .'  /></div>';
		break;
		// Multicheck
		case "multicheck":
			foreach ($value['options'] as $key => $option) {
				$checked = '';
				$label = $option;
				$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));
				$id = $option_name . '-' . $value['id'] . '-'. $option;
				$name = $option_name . '[' . $value['id'] . '][' . $option .']';
			    if ( isset($val[$option]) ) {
					$checked = checked($val[$option], 1, false);
				}
				$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
			}
		break;
		// Color picker
		case "color":
			$output .= '<div id="' . esc_attr( $value['id'] . '_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $val ) . '"></div></div>';
			$output .= '<input class="of-color" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break;
		// Uploader
		case "upload":
			$output .= optionsframework_medialibrary_uploader( $value['id'], $val, null ); // New AJAX Uploader using Media Library
		break;
		// Typography
		case 'typography':
			$typography_stored = $val;
	/*		// Font Size
			$output .= '<select class="of-typography of-typography-size" name="' . esc_attr( $option_name . '[' . $value['id'] . '][size]' ) . '" id="' . esc_attr( $value['id'] . '_size' ) . '">';
			for ($i = 9; $i < 71; $i++) {
				$size = $i . 'px';
				$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
			}
			$output .= '</select>';
*/
			// Font Face
			$output .= '<select class="of-typography of-typography-face" name="' . esc_attr( $option_name . '[' . $value['id'] . '][face]' ) . '" id="' . esc_attr( $value['id'] . '_face' ) . '">';
			$faces = of_recognized_font_faces();
			foreach ( $faces as $key => $face ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
			}
			$output .= '</select>';
/*			// Font Weight
			$output .= '<select class="of-typography of-typography-style" name="'.$option_name.'['.$value['id'].'][style]" id="'. $value['id'].'_style">';
*/
			/* Font Style */
/*			$styles = of_recognized_font_styles();
			foreach ( $styles as $key => $style ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
			}
			$output .= '</select>';
			// Font Color
			$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $typography_stored['color'] ) . '"></div></div>';
			$output .= '<input class="of-color of-typography of-typography-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $typography_stored['color'] ) . '" />';
*/
		break;
        case 'impbutton':
			$importer_url =  get_template_directory_uri()."/demo/import.php";
			global $wpdb;
			$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type='post'" );
			?>
            	<script type="text/javascript">
					jQuery(document).ready(function($)
					{
						var whatever = "<?php echo esc_js($importer_url); ?>";
						var demo_stat = "<?php echo esc_js($count); ?>";
						jQuery("#close_update_nag").click(function(event)
						{
							jQuery('#import_incomp_noti').hide();
						});

						jQuery("#import_btn").click(function(event)
						{
							if(demo_stat > 4)
							{
								jQuery("input[id='import_btn']").prop('disabled', true);
								 jQuery('#import_incomp_noti').show();
							} else {
							jQuery.ajax(
							{
								beforeSend: function()
								{
									jQuery("input[id='import_btn']").prop('disabled', true);
									jQuery('#target').show();
								},
								complete: function()
								{
									jQuery('#target').hide();
								},

								url: whatever,
								success: function(data)
								{
								jQuery('#import_comp_noti').show();

								}
							});
							}
						});
					});
            	</script>

        	<?php
		  import_completed_notice();
		  import_incompleted_notice();
		$output .= '<input id="import_btn" class="button button-primary" type="button" value="import" rel="5">';
		$output .= '<div class="loading" id="target" style="display:none;"><div class="loading_text">'.esc_html__("Please wait, the content is importing....","fundingpress").'</div></div>';

		break;
		// Background
		case 'background':
			$background = $val;
			// Background Color
			$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $background['color'] ) . '"></div></div>';
			$output .= '<input class="of-color of-background of-background-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $background['color'] ) . '" />';
			// Background Image - New AJAX Uploader using Media Library
			if (!isset($background['image'])) {
				$background['image'] = '';
			}
			$output .= optionsframework_medialibrary_uploader( $value['id'], $background['image'], null, '',0,'image');
			$class = 'of-background-properties';
			if ( '' == $background['image'] ) {
				$class .= ' hide';
			}
			$output .= '<div class="' . esc_attr( $class ) . '">';
			// Background Repeat
			$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '][repeat]'  ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
			$repeats = of_recognized_background_repeat();
			foreach ($repeats as $key => $repeat) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
			}
			$output .= '</select>';
			// Background Position
			$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '][position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
			$positions = of_recognized_background_position();
			foreach ($positions as $key=>$position) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
			}
			$output .= '</select>';
			// Background Attachment
			$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '][attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
			$attachments = of_recognized_background_attachment();
			foreach ($attachments as $key => $attachment) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
			}
			$output .= '</select>';
			$output .= '</div>';
		break;
		// Info
		case "info":
			$class = 'section';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}
			$output .= '<div class="' . esc_attr( $class ) . '">' . "\n";
			if ( isset($value['name']) ) {
				$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
			}
			if ( isset($value['desc'] )) {
				$output .= apply_filters('of_sanitize_info', $value['desc'] ) . "\n";
			}
			$output .= '<div class="clear"></div></div>' . "\n";
		break;
		// Heading for Navigation
		case "heading":
			if ($counter >= 2) {
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($value['name']) );
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<a id="'.  esc_attr( $jquery_click_hook ) . '-tab" class="nav-tab" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '">' . esc_html( $value['name'] ) . '</a>';
			$output .= '<div class="group" id="' . esc_attr( $jquery_click_hook ) . '">';
			break;
		}
		if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {
			if ( $value['type'] != "checkbox" ) {
				$output .= '<br/>';
			}
			$output .= '</div>';
			if ( $value['type'] != "checkbox" ) {
				$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
			}
			$output .= '<div class="clear"></div></div></div>'."\n";
		}
	}
    $output .= '</div>';
    return array($output,$menu);
}
?>
