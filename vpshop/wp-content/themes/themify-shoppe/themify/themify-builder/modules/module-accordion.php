<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Accordion
 * Description: Display Accordion content
 */
class TB_Accordion_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string {
		return __('Accordion', 'themify');
	}

	public static function get_module_icon():string {
		return 'layout-accordion-merged';
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
		$mod_settings = $module['mod_settings']+array(
			'mod_title_accordion' => '',
			'content_accordion' => array()
		);
		$text = '' !== $mod_settings['mod_title_accordion']?sprintf('<h3>%s</h3>', $mod_settings['mod_title_accordion']):'';

		if (!empty($mod_settings['content_accordion'])) {
			$text .= '<ul>';
			foreach ($mod_settings['content_accordion'] as $accordion) {
				if(isset($accordion['text_accordion'])){
					$text .= sprintf('<li><h4>%s</h4>%s</li>', $accordion['title_accordion']??'', $accordion['text_accordion']);
				}
				
			}
			$text .= '</ul>';
		}
		return $text;
	}

    public static function subrow_attributes( $attr ) {
        remove_filter( 'themify_builder_subrow_attributes', [ __CLASS__, 'subrow_attributes' ] );
        $attr['itemprop'] = 'text';
        return $attr;
    }

    public static function get_styling_image_fields() : array {
        return [
            'bg_i' => [ ' .ui.module-accordion .accordion-title', ' .ui.module-accordion>li' ]
        ];
    }
}
