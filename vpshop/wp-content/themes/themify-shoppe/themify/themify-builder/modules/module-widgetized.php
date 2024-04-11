<?php

defined('ABSPATH') || exit;

/**
 * Module Name: Widgetized
 * Description: Display any registered sidebar
 */
class TB_Widgetized_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string {
		return __('Widgetized', 'themify');
	}

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => ''
        ];
    }
}