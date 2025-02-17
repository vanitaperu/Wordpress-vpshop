<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder
 * @subpackage Themify_Builder/classes
 */

class Themify_Builder_Plugin_Compat_facetwp {

	static function init() {
		add_action( 'themify_builder_active_enqueue', [ __CLASS__, 'admin_enqueue' ] );
		add_action( 'themify_builder_module_classes', [ __CLASS__, 'themify_builder_module_classes' ], 1, 4 );
	}

	public static function admin_enqueue() {
		wp_enqueue_script( 'tb-facetwp-admin', THEMIFY_BUILDER_URI .'/includes/plugin-compat/js/facetwp-admin.js', [ 'themify-builder-app-js' ], THEMIFY_VERSION, true );
		wp_localize_script( 'tb-facetwp-admin', 'tbFacet', [
			'label' => __( 'FacetWP', 'themify' ),
			'desc' => __( 'Enable integration with FacetWP plugin, the posts display in this module can be filtered.', 'themify' ),
		] );
	}

	public static function themify_builder_module_classes(array $classes,?string $mod_name,?string $element_id,array $options ):array {
		if (!empty( $options['facetwp'] ) ) {
			$classes[] = 'facetwp-template';
			add_action( 'pre_get_posts', [ __CLASS__, 'pre_get_posts' ] );
			add_action( 'facetwp_assets', [ __CLASS__, 'facetwp_assets' ] );
		}
		return $classes;
	}

	/**
	 * Enable filtering the wp_query by facet
	 */
	static function pre_get_posts( $query ) {
		remove_action( 'pre_get_posts', [ __CLASS__, 'pre_get_posts' ] );
		$query->set( 'facetwp', true );
	}

	/**
	 * Load frontend scripts for fixing display of posts
	 */
	static function facetwp_assets(array $assets ):array {
		$assets['tb-facetwp-front'] = THEMIFY_BUILDER_URI .'/includes/plugin-compat/js/facetwp-front.js';
		return $assets;
	}
}