<?php
/**
 * Widget Name: Project Categry widget
 * Description: Populate Project Category.
 * Version: 1.0
 */

class projectcat_widget extends WP_Widget
{
	function __construct()
	{
		parent::__construct( 'projectcat_widget', esc_html__('Project Categories', 'fundingpress'),

// Widget description
array( 'description' => esc_html__( 'Display project category', 'fundingpress' ), )
);
}

// Creating widget front-end
// This is where the action happens
function widget( $args, $instance ) {
		extract( $args );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Categories', 'fundingpress' ) : $instance['title'], $instance, $this->id_base );




		$funding_allowed = wp_kses_allowed_html( 'post' ); echo wp_kses($before_widget,$funding_allowed);
		if ( $title ){ ?><h3> <?php echo esc_attr($title); ?></h3> <?php }


?>
		<ul>
<?php

$tax_terms = get_terms('project-category', array('number'=>$instance['count']));
?>

<?php
foreach ($tax_terms as $tax_term) {
echo '<li>' . '<a href="' . esc_attr(get_term_link($tax_term)) . '" title="' . sprintf( esc_html__( "View all posts in %s", 'fundingpress' ), $tax_term->name ) . '" ' . '>' . $tax_term->name.'</a></li>';
}
?>

		</ul>
<?php


		echo  wp_kses($after_widget,$funding_allowed);
	}

// Widget Backend
public function form( $instance ) {
$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = esc_attr( $instance['count'] );

// Widget admin form
?>
<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title:', 'fundingpress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<p><label for="<?php echo esc_attr($this->get_field_id('count')); ?>"><?php esc_html_e( 'Number of categories to show:', 'fundingpress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>" type="text" value="<?php echo esc_attr($count); ?>" /></p>
<?php
}

function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);


		return $instance;
	}


} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'projectcat_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
?>
