<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Login
 * Description: Displays login form
 */
class TB_Login_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string {
		return __('Login', 'themify');
	}

	public static function get_module_icon():string {
		return 'unlock';
	}

	public static function get_js_css():array {
		$_arr = array(
			'css' => 1
		);
		if (!Themify_Builder_Model::is_front_builder_activate()) {
			$_arr['js'] = 1;
		}
		return $_arr;
	}

	/**
	 * Render plain content for static content.
	 * 
	 * @param array $module 
	 * @return string
	 */
	public static function get_static_content(array $module):string {
		return '';
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => ''
        ];
    }
}