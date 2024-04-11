<?php
/**
 * Custom functions specific to the Spa skin
 *
 * @package Themify Shoppe
 */

/**
 * Load Google web fonts required for the Spa skin
 *
 * @since 1.4.9
 * @return array
 */
function themify_theme_spa_google_fonts( $fonts ) {
	if ( 'off' !== _x( 'on', 'Josefin Sans font: on or off', 'themify' ) ) {
		$fonts['josefinsans'] = 'Josefin+Sans:400,700';
	}
	if ( 'off' !== _x( 'on', 'Comfortaa font: on or off', 'themify' ) ) {
		$fonts['comfortaa'] = 'Comfortaa:400,700';
	}
	if ( 'off' !== _x( 'on', 'Varela Round: on or off', 'themify' ) ) {
		$fonts['varelaround'] = 'Varela+Round';
	}	
	if ( 'off' !== _x( 'on', 'Lora : on or off', 'themify' ) ) {
		$fonts['lora'] = 'Lora:400i';
	}	
	return $fonts;
}
add_filter( 'themify_google_fonts', 'themify_theme_spa_google_fonts' );