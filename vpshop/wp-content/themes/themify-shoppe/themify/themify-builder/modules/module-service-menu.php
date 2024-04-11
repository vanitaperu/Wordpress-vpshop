<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Service Menu
 * Description: Display a Service item
 */
class TB_Service_Menu_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string {
		return __('Service Menu', 'themify');
	}

	public static function get_module_icon():string {
		return 'menu-alt';
	}

	public static function get_js_css():array {
		return array(
			'css' => 1
		);
	}
}
