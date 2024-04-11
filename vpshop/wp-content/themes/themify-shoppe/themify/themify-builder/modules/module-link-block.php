<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Link
 * Description: Display Link
 */
class TB_Link_Block_Module extends Themify_Builder_Component_Module {


	public static function get_module_name():string {
		return __('Link Block', 'themify');
	}

	public static function get_module_icon():string {
		return 'link';
	}

	public static function get_js_css():array {
		return array(
			'css' => 1
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => ' .tb_link_block_container'
        ];
    }
}