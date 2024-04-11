<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Testimonials
 * Description: Display testimonial custom post type
 */
class TB_Testimonial_Slider_Module extends Themify_Builder_Component_Module {

	
	public static function get_module_name():string {
		return __('Testimonials', 'themify');
	}

	public static function get_js_css():array {
		return array(
			'css' => 1,
			'async' => 1
		);
	}


	public static function get_module_icon():string {
		return 'clipboard';
	}

}


