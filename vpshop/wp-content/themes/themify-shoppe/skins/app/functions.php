<?php
/**
 * Custom functions specific to the App skin
 *
 * @package Themify Shoppe
 */

/**
 * Load Google web fonts required for the App skin
 *
 * @since 1.4.9
 * @return array
 */
function themify_theme_app_google_fonts( $fonts ){
	/* translators: If there are characters in your language that are not supported by this font, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Raleway font: on or off', 'themify' ) ) {
		$fonts['raleway'] = 'Raleway:300,400,500,600,700,800,900';
	}
	if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'themify' ) ) {
		$fonts['poppins'] = 'Poppins:300,400';
	}
	return $fonts;
}
add_filter( 'themify_google_fonts', 'themify_theme_app_google_fonts' );