<?php
/*
Plugin Name: JMB Social Media Profile Links
Plugin URI: https://github.com/gurjit-git/
Description: This is Social Media profile links plugin.
Author: Gurjit Singh
Version: 1.0
License: GPL-2.0+
Author URI: https://github.com/gurjit-git/
Text domain: jmb-social-media-profile-links
*/
if ( ! defined( 'ABSPATH' ) ) {
    return;
}

// define variable for path to this plugin file.
define( 'JMB_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'JMB_PLUGIN_URL', plugins_url( '', __FILE__ ) );

include(JMB_PLUGIN_DIR. '/inc/widget.php');
//include(JMB_PLUGIN_DIR. '/inc/custom.php');
/**
 * Get the registered social profiles.
 *
 * @return array An array of registered social profiles.
 */
function jmb_get_social_profiles() {

	// return a filterable social profiles.
	return apply_filters(
		'jmb_social_profiles',
		array()
	);

}

/**
 * Registers the default social profiles.
 *
 * @param  array $profiles An array of the current registered social profiles.
 * @return array           The modified array of social profiles.
 */
function jmb_register_default_social_profiles( $profiles ) {

	// add the facebook profile.
	$profiles['facebook'] = array(
		'id'                => 'jmb_facebook_url',
		'label'             => __( 'Facebook URL', 'jmb-social-profiles' ),
		'class'             => 'facebook',
		'description'       => __( 'Enter your Facebook profile URL', 'jmb-social-profiles' ),
		'priority'          => 10,
		'type'              => 'text',
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	);

	// add the linkedin profile.
	$profiles['linkedin'] = array(
		'id'                => 'jmb_linkedin_url',
		'label'             => __( 'LinkedIn URL', 'jmb-social-profiles' ),
		'class'             => 'linkedin',
		'description'       => __( 'Enter your LinkedIn profile URL', 'jmb-social-profiles' ),
		'priority'          => 20,
		'type'              => 'text',
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	);

	// add the twitter profile.
	$profiles['twitter'] = array(
		'id'                => 'jmb_twitter_url',
		'label'             => __( 'Twitter URL', 'jmb-social-profiles' ),
		'class'             => 'twitter',
		'description'       => __( 'Enter your Twitter profile URL', 'jmb-social-profiles' ),
		'priority'          => 40,
		'type'              => 'text',
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	);

	// return the modified profiles.
	return $profiles;

}

add_filter( 'jmb_social_profiles', 'jmb_register_default_social_profiles', 10, 1 );

/**
 * Registers the social profiles with the customizer in WordPress.
 *
 * @param  WP_Customizer $wp_customize The customizer object.
 */
function jmb_register_social_customizer_settings( $wp_customize ) {

	// get the social profiles.
	$social_profiles = jmb_get_social_profiles();

	// if we have any social profiles.
	if ( ! empty( $social_profiles ) ) {

		// register the customizer section for social profiles.
		$wp_customize->add_section(
			'jmb_social',
			array(
				'title'          => __( 'Social Profiles' ),
				'description'    => __( 'Add social media profiles here.' ),
				'priority'       => 160,
				'capability'     => 'edit_theme_options',
			)
		);

		// loop through each progile.
		foreach ( $social_profiles as $social_profile ) {

			// add the customizer setting for this profile.
			$wp_customize->add_setting(
				$social_profile['id'],
				array(
					'default'           => '',
					'sanitize_callback' => $social_profile['sanitize_callback'],
				)
			);

			// add the customizer control for this profile.
			$wp_customize->add_control(
				$social_profile['id'],
				array(
					'type'        => $social_profile['type'],
					'priority'    => $social_profile['priority'],
					'section'     => 'jmb_social',
					'label'       => $social_profile['label'],
					'description' => $social_profile['description'],
				)
			);

		}

	}

}

add_action( 'customize_register', 'jmb_register_social_customizer_settings' );

// Link to settings page from plugins screen
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
function add_action_links ( $links ) {
    $mylinks = array(
        '<a href="' . admin_url( 'customize.php' ) . '" target="_blank">Settings</a>',
    );
    return array_merge( $links, $mylinks );
}