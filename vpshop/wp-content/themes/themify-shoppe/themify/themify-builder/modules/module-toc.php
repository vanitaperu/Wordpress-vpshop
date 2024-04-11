<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Box
 * Description: Display box content
 */
class TB_Toc_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string {
		return __('Table of Content', 'themify');
	}

	public static function get_module_icon():string {
		return 'layout-width-full';
	}

	public static function get_js_css():array {
		return array(
			'css' => 1,
			'js' => 1
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => ''
        ];
    }
}