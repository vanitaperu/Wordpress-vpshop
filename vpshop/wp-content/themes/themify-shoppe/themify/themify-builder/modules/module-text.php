<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Text
 * Description: Display text content
 */
class TB_Text_Module extends Themify_Builder_Component_Module {

	
	public static function get_module_name():string {
		return __('Text', 'themify');
	}

	public static function get_js_css():array {
		return array(
			'css' => 1
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => ''
        ];
    }
}