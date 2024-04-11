<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Tab
 * Description: Display Tab content
 */
class TB_Tab_Module extends Themify_Builder_Component_Module {


	public static function get_module_name():string {
		return __('Tab', 'themify');
	}

	public static function get_module_icon():string {
		return 'layout-tab';
	}

	public static function get_js_css():array {
		return array(
			'css' => 1,
			'js' => 1
		);
	}


	/**
	 * Render plain content for static content.
	 * 
	 * @param array $module 
	 * @return string
	 */
	public static function get_static_content(array $module):string {
		$mod_settings = $module['mod_settings']+ array(
			'mod_title_tab' => '',
			'tab_content_tab' => array()
		);
		$text ='' !== $mod_settings['mod_title_tab']?sprintf('<h3>%s</h3>', $mod_settings['mod_title_tab']): '';
		if (!empty($mod_settings['tab_content_tab'])) {
			$text .= '<ul>';
			foreach ($mod_settings['tab_content_tab'] as $content) {
				if(isset($content['text_tab'])){
					$text .= sprintf('<li><h4>%s</h4>%s</li>', $content['title_tab']??'', $content['text_tab']);
				}
			}
			$text .= '</ul>';
		}
		return $text;
	}

    public static function get_styling_image_fields() : array {
        return [
            'bg_i' => '.ui .tab-nav li.current'
        ];
    }
}