<?php
/**
 * Custom functions specific to the Furniture skin
 *
 * @package Themify Shoppe
 */

/**
 * Load Google web fonts required for the Agency4 skin
 *
 * @since 1.4.9
 * @return array
 */
function themify_theme_agency4_google_fonts( $fonts ) {
	if ( 'off' !== _x( 'on', 'DMSans font: on or off', 'themify' ) ) {
		$fonts['DMSans'] = 'DM+Sans:400,700';
	}
	return $fonts;
}
add_filter( 'themify_google_fonts', 'themify_theme_agency4_google_fonts' );