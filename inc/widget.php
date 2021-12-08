<?php
/**
 * Register the social icons widget with WordPress.
 */
function jmb_register_social_icons_widget() {
    register_widget( 'jmb_Social_Icons_Widget' );
}

add_action( 'widgets_init', 'jmb_register_social_icons_widget' );

/**
 * Extend the widgets class for our new social icons widget.
 */
class jmb_Social_Icons_Widget extends WP_Widget {

	/**
	 * Setup the widget.
	 */
	public function __construct() {

		/* Widget settings. */
		$widget_ops = array(
			'classname'   => 'jmb-social-icons',
			'description' => __( 'Output your sites social icons, based on the social profiles added to the cutomizer.', 'jmb-social-profiles-widget' ),
		);

		/* Widget control settings. */
		$control_ops = array(
			'id_base' => 'jmb_social_icons',
		);

		/* Create the widget. */
		parent::__construct( 'jmb_social_icons', 'JMB Social Icons', $widget_ops, $control_ops );
	
	}

	/**
	 * Output the widget front-end.
	 */
	public function widget( $args, $instance ) {

		// output the before widget content.
		echo wp_kses_post( $args['before_widget'] );

		/**
		 * Call an action which outputs the widget.
		 *
		 * @param $args is an array of the widget arguments e.g. before_widget.
		 * @param $instance is an array of the widget instances.
		 *
		 * @hooked jmb_social_icons_output_widget_title.- 10
		 * @hooked jmb_output_social_icons_widget_content - 20
		 */
		do_action( 'jmb_social_icons_widget_output', $args, $instance );

		// output the after widget content.
		echo wp_kses_post( $args['after_widget'] );

	}

	/**
	 * Output the backend widget form.
	 */
	public function form( $instance ) {

		// get the saved title.
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'jmb-extensible-social-profiles-widget' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<?php
			printf(
				__( 'To add social profiles, please use the social profile section in the %1$scustomizer%2$s.', 'jmb-social-profiles-widget' ),
				'<a href="' . admin_url( 'customize.php' ) . '">',
				'</a>'
			);
			?>

		</p>

		<?php

	}

	/**
	 * Controls the save function when the widget updates.
	 *
	 * @param  array $new_instance The newly saved widget instance.
	 * @param  array $old_instance The old widget instance.
	 * @return array               The new instance to update.
	 */
	public function update( $new_instance, $old_instance ) {

		// create an empty array to store new values in.
		$instance = array();

		// add the title to the array, stripping empty tags along the way.
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		// return the instance array to be saved.
		return $instance;

	}

}

/**
 * Outputs the widget title for the social icons widget.
 *
 * @param  array $args     An array of widget args.
 * @param  array $instance The current instance of widget data.
 */
function jmb_social_icons_output_widget_title( $args, $instance ) {

	// if we have before widget content.
	if ( ! empty( $instance['title'] ) ) {

		// if we have before title content.
		if ( ! empty( $args['before_title'] ) ) {

			// output the before title content.
			echo wp_kses_post( $args['before_title'] );

		}

		// output the before widget content.
		echo esc_html( $instance['title'] );

		// if we have after title content.
		if ( ! empty( $args['after_title'] ) ) {

			// output the after title content.
			echo wp_kses_post( $args['after_title'] );

		}
	}

}

add_action( 'jmb_social_icons_widget_output', 'jmb_social_icons_output_widget_title', 10, 2 );

/**
 * Outputs the widget content for the social icons widget - the actual icons and links.
 *
 * @param  array $args     An array of widget args.
 * @param  array $instance The current instance of widget data.
 */
function jmb_output_social_icons_widget_content( $args, $instance ) {

	// get the array of social profiles.
	$social_profiles = jmb_get_social_profiles();

	// if we have any social profiles.
	if ( ! empty( $social_profiles ) ) {

		// start the output markup.
		?>
		<ul class="jmb-social-icons">
		<?php

		// loop through each profile.
		foreach ( $social_profiles as $social_profile ) {

			// get the value for this social profile - the profile url.
			$profile_url = get_theme_mod( $social_profile['id'] );

			// if we have a no value - url.
			if ( empty( $profile_url ) ) {
				continue; // continue to the next social profile.
			}

			// if we don't have a specified class.
			if ( empty ( $social_profile['class'] ) ) {

				// use the label for form a class.
				$social_profile['class'] = strtolower( sanitize_title_with_dashes( $social_profile['label'] ) );

			}

			// build the markup for this social profile.
			?>

			<li class="jmb-social-icons__item jmb-social-icons__item--<?php echo esc_attr( $social_profile['class'] ); ?>">
				<a target="_blank" class="jmb-social-icons__item-link" href="<?php echo esc_url( $profile_url ); ?>">
					<i class="icon-<?php echo esc_attr( $social_profile['class'] ); ?>"></i> <span><?php echo esc_html( $social_profile['label'] ); ?></span>
				</a>
			</li>

			<?php

		}

		// end the output markup.
		?>
		</ul>
		<?php

	}

}

add_action( 'jmb_social_icons_widget_output', 'jmb_output_social_icons_widget_content', 20, 2 );