<?php
/**
 * Custom functions specific to the skin
 *
 * @package Themify Shoppe
 */

/**
 * Load Google web fonts required for the skin
 *
 * @since 1.4.9
 * @return array
 */
function themify_theme_ecommerce_google_fonts( $fonts ) {
	if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'themify' ) ) {
		$fonts['poppins'] = 'Poppins:400,300,600,700,900';
	}
	return $fonts;
}
add_filter( 'themify_google_fonts', 'themify_theme_ecommerce_google_fonts' );

