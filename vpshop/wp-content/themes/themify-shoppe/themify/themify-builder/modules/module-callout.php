<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Callout
 * Description: Display Callout content
 */
class TB_Callout_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string {
		return __('Callout', 'themify');
	}

	public static function get_module_icon():string {
		return 'announcement';
	}

	public static function get_js_css():array {
		return array(
			'css' => 1
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => '.module'
        ];
    }
}