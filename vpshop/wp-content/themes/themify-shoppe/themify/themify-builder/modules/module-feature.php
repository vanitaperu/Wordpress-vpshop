<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Feature
 * Description: Display Feature content
 */
class TB_Feature_Module extends Themify_Builder_Component_Module {

	
	public static function get_module_name():string {
		return __('Feature', 'themify');
	}

	public static function get_module_icon():string {
		return 'layout-media-left';
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