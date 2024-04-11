<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Image
 * Description: Display Image content
 */
class TB_Image_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string {
		return __('Image', 'themify');
	}

	public static function get_js_css():array {
		return array(
			'css' => 1
		);
	}

	/**
	 * Backward Compatibility methods
	 */
	public function __construct() {
		parent::__construct('image');
	}

	public function get_name() {
		return self::get_module_name();
	}

    function get_assets() {
		return self::get_js_css();
    }

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => ''
        ];
    }
}

new TB_Image_Module();//backward for builder-pro
