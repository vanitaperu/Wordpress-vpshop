<?php

/***************************************************************************
 *
 * 	----------------------------------------------------------------------
 * 						DO NOT EDIT THIS FILE
 *	----------------------------------------------------------------------
 * 
 *  				     Copyright (C) Themify
 * 
 *	----------------------------------------------------------------------
 *
 ***************************************************************************/

defined( 'ABSPATH' ) || exit;

///////////////////////////////////////////
// Layout Parts Class
///////////////////////////////////////////
class Themify_Layout_Part extends WP_Widget {

	///////////////////////////////////////////
	// Layout Parts
	///////////////////////////////////////////
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'layout-parts', 'description' => __('Layout Parts widget', 'themify') );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'themify-layout-parts' );

		/* Create the widget. */
		parent::__construct( 'themify-layout-parts', __('Themify - Layout Parts', 'themify'), $widget_ops, $control_ops );
	}

	///////////////////////////////////////////
	// Widget
	///////////////////////////////////////////
	function widget( $args, $instance ) {

		/* User-selected settings. */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$layout_part = isset( $instance['layout_part'] ) ? $instance['layout_part'] : false;

		/* Before widget (defined by themes). */
		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] , $title , $args['after_title'];
		}

		echo do_shortcode('[themify_layout_part slug=' . $layout_part . ']'),$args['after_widget'];
	}

	///////////////////////////////////////////
	// Update
	///////////////////////////////////////////
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['layout_part'] = $new_instance['layout_part'];

		return $instance;
	}

	///////////////////////////////////////////
	// Form
	///////////////////////////////////////////
	function form( $instance ) {

		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'layout_part' => '',
		) ); 
		$field = esc_attr($this->get_field_id( 'layout_part' ));
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themify'); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php  echo esc_attr( $instance['title'] ); ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $field; ?>"><?php _e('Layout Part:', 'themify'); ?></label>
			<select id="<?php echo $field; ?>" name="<?php esc_attr_e( $this->get_field_name( 'layout_part' ) ); ?>">
				<?php
				$layouts = get_posts( array(
                                    'post_type'=>Themify_Builder_Layouts::LAYOUT_PART_SLUG,
                                    'post_status' => 'publish',
                                    'orderby'     => 'post_title',
                                    'order'       => 'ASC',
				    'no_found_rows'=>true,
				    'cache_results'=>false,
				    'ignore_sticky_posts'=>true,
				    'update_post_term_cache'=>false,
				    'update_post_meta_cache'=>false,
                                    'nopaging'=>true,
                                ));

				foreach( $layouts as $layout ) {
					echo '<option value="' . $layout->post_name  . '"';

					if ( $layout->post_name === $instance['layout_part'] ) echo ' selected="selected"';

					echo '>' , esc_html( $layout->post_title ),'</option>';
				}
				?>
			</select>
		</p>

		<?php
	}
}
register_widget( 'Themify_Layout_Part' );