<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder
 * @subpackage Themify_Builder/classes
 */

class Themify_Builder_Plugin_Compat_WPML {

	static function init() {
		add_action( 'wp_ajax_themify_builder_icl_copy_from_original', array( __CLASS__, 'icl_copy_from_original' ) );
		add_filter( 'get_translatable_documents', array( __CLASS__, 'get_translatable_documents' ) );
	}

	/**
	 * Load Builder content from original page when "Copy content" feature in WPML is used
	 *
	 * @access public
	 * @since 1.4.3
	 */
	public static function icl_copy_from_original() {

		if ( isset( $_POST['source_page_id'],$_POST['source_page_lang'] ) && current_user_can( 'edit_post', $_POST['source_page_id'] ) ) {
			global $wpdb;
			$post_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid='%d' AND language_code='%s' LIMIT 1",
					$_POST[ 'source_page_id' ],
					$_POST[ 'source_page_lang' ]
				)
			);
			$post = ! empty( $post_id ) ? get_post( $post_id ) : null;
			if ( ! empty( $post ) ) {
				$builder_data = ThemifyBuilder_Data_Manager::get_data( $post->ID );
                wp_send_json_success($builder_data);
			}
            wp_send_json_error('');
		}
		die;
	}

	/**
	 * Disable translation on some post types
	 *
	 * @return array
	 */
	public static function get_translatable_documents(array $translatable_post_types=array() ):array {
		unset( $translatable_post_types[Themify_Global_Styles::SLUG],$translatable_post_types['tb_cf'], $translatable_post_types['tbp_theme']  );

		return $translatable_post_types;
	}
}