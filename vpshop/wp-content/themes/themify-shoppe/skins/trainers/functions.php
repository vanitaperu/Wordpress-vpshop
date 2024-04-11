<?php
/**
 * Custom functions specific to the Trainers skin
 *
 * @package Themify Shoppe
 */

/**
 * Load Google web fonts required for the Trainers skin
 *
 * @since 1.4.9
 * @return array
 */
function themify_theme_trainers_google_fonts( $fonts ){
	if ( 'off' !== _x( 'on', 'Public Sans font: on or off', 'themify' ) ) {
		$fonts['open-sans'] = 'Public+Sans:300,400,400i,600,600i,700,700i,800,800i';
	}
	return $fonts;
}
add_filter( 'themify_google_fonts', 'themify_theme_trainers_google_fonts' );