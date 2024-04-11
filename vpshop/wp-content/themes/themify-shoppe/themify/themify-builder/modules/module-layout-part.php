<?php

defined('ABSPATH') || exit;

/**
 * Module Name: Layout Part
 * Description: Layout Part Module
 */
class TB_Layout_Part_Module extends Themify_Builder_Component_Module {


	public static function get_module_name():string {
		return __('Layout Part', 'themify');
	}

	public static function get_module_icon():string {
		return 'layout';
	}

	public static function get_js_css():array {
		return array(
			'css' => 1
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => ''
        ];
    }
}