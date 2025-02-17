<?php

defined('ABSPATH') || exit;

/**
 * Module Name: Widget
 * Description: Display any available widgets
 */
class TB_Widget_Module extends Themify_Builder_Component_Module {

	public static function init():void {
		add_action('wp_ajax_tb_get_widget_items', array(__CLASS__, 'get_items'));
		add_action('wp_ajax_module_widget_get_form', array(__CLASS__, 'widget_get_form'), 10);
		add_action('tb_data_before_save', array(__CLASS__, 'before_builder_save'), 10, 2);
	}

	public static function get_module_name():string {
		return __('Widget', 'themify');
	}

	public static function get_items() {
		$result = array();
		global $wp_widget_factory;
		if (!empty($wp_widget_factory->widgets)) {
			foreach ($wp_widget_factory->widgets as $widget) {
				$class = get_class($widget);
				$result[$class] = array('n' => $widget->name, 'b' => $widget->id_base);
				if (!empty($widget->widget_options['description'])) {
					$result[$class]['d'] = $widget->widget_options['description'];
				}
			}
		}

		$columns = array_column($result, 'n');
		array_multisort($columns, SORT_ASC, $result);

		die(json_encode($result));
	}

	/**
	 * Get a widget's registered key in $wp_widget_factory from its classname.
	 * register_widget() works with WP_Widget instances too, in such cases the
	 * widget's key is a hashed string.
	 *
	 * @return string|null
	 */
	public static function get_widget_factory_name($classname) {
		if (empty($classname) || !class_exists($classname,false)) {
			return;
		}
		global $wp_widget_factory;
		if (isset($wp_widget_factory->widgets[$classname])) {
			return $classname;
		}
		foreach ($wp_widget_factory->widgets as $key => $widget) {
			if ($widget instanceof WP_Widget && get_class($widget) === $classname) {
				return $key;
			}
		}
	}

	public static function widget_get_form() {
		check_ajax_referer('tf_nonce', 'nonce');

		global $wp_widget_factory;
		require_once ABSPATH . 'wp-admin/includes/widgets.php';
		$widget_class = $_POST['load_class'];
		if ($widget_class == '') {
			die(-1);
		}
		$widget_class = str_replace('\\\\', '\\', $widget_class);

		$instance = !empty($_POST['widget_instance']) && $_POST['widget_instance'] !== 'false' ? $_POST['widget_instance'] : array();
		$instance = TB_Widget_Module::sanitize_widget_instance($instance);

		$widget_key = self::get_widget_factory_name($widget_class);
		if (!$widget_key) {
			die(-1);
		}
		$widget = $wp_widget_factory->widgets[$widget_key];

		$widget->number = next_widget_id_number($_POST['id_base']);
		ob_start();
		$instance = stripslashes_deep($instance);
		$template = '';
		$src = array();

		if (empty($_POST['tpl_loaded']) && method_exists($widget, 'render_control_template_scripts')) {
			require_once ABSPATH . WPINC . '/media-template.php';
			ob_start();
			$widget->render_control_template_scripts();
			if ($widget->id_base !== 'text') {
				wp_print_media_templates();
			}

			$template = ob_get_contents();
			ob_end_clean();
			$widget->enqueue_admin_scripts();
			$type = str_replace('_', '-', $widget->id_base) . '-widget';
			if ($widget->id_base === 'text') {
				$type .= 's';
			}
			wp_enqueue_script($type);
			global $wp_scripts;
			if (isset($wp_scripts->registered[$type])) {
				$script = $wp_scripts->registered[$type];
				if ($widget->id_base !== 'text' && !empty($wp_scripts->registered[$type]->deps)) {
					foreach ($wp_scripts->registered[$type]->deps as $deps) {
						$src[] = array('src' => self::resolve_script_path($wp_scripts->registered[$deps]->src));
					}
				}

				$src[] = array('src' => self::resolve_script_path($script->src), 'extra' => !empty($script->extra) ? $script->extra : '');
			}
		}
		$widget->form($instance);
		do_action('in_widget_form', $widget, null, $instance);
		$form = ob_get_clean();
		$base_name = 'widget-' . $widget->id_base . '\[' . $widget->number . '\]';
		$form = preg_replace("/{$base_name}/", '', $form); // remove extra names
		$form = str_replace(array(
			'[',
			']'
			), '', $form); // remove extra [ & ] characters
		$widget->form = $form;

		/**
		 * The widget-id is not used to save widget data, it is however needed for compatibility
		 * with how core renders the module forms.
		 */
		$form = '<div class="widget open">
				<div class="widget-inside">
					<div class="form">
						<div class="widget-content">'
			. $form .
			'</div>
						<input type="hidden" class="id_base" name="id_base" value="' . esc_attr($widget->id_base) . '" />
						<input type="hidden" class="widget-id" name="widget-id" value="w_' . time() . '" />
						<input type="hidden" class="widget-class" name="widget-class" value="' . $widget_class . '" />
					</div>
				</div>
				<br/>
			</div>';

		global $wp_version;
		wp_send_json(array(
			'form' => $form,
			'template' => $template,
			'v' => $wp_version,
			'src' => $src
		));
		wp_die();
	}

	private static function resolve_script_path($src) {

		$content_url = defined('WP_CONTENT_URL') ? WP_CONTENT_URL : '';

		if (!preg_match('|^(https?:)?//|', $src) && !( $content_url && 0 === strpos($src, $content_url) )) {
			if (!($guessurl = site_url() )) {
				$guessurl = wp_guess_url();
			}
			$src = $guessurl . $src;
		}

		return $src;
	}

	/*
	 * Sanitize keys for widget fields
	 * This is required to provide backward compatibility with how widget data was saved.
	 *
	 * @return array
	 * @since 3.2.0
	 */

	public static function sanitize_widget_instance($instance) {
		if (is_array($instance)) {
			foreach ($instance as $key => $val) {
				preg_match('/.*\[\d\]\[(.*)\]/', $key, $matches);
				if (isset($matches[1])) {
					unset($instance[$key]);
					$instance[$matches[1]] = $val;
				}
			}
		}

		return $instance;
	}

	/**
	 * Render plain content for static content.
	 * 
	 * @param array $module 
	 * @return string
	 */
	public static function get_static_content(array $module):string {
		if (!isset($module['mod_settings'])) {
			return '';
		}
		$mod_settings = $module['mod_settings']+ array(
			'mod_title_widget' => '',
			'class_widget' => '',
			'instance_widget' => array(),
		);
		$text = '';

		if ('' !== $mod_settings['mod_title_widget'])
			$text = sprintf('<h3>%s</h3>', $mod_settings['mod_title_widget']);

		if ('Themify_Social_Links' === $mod_settings['class_widget'])
			return self::social_links_plain_content();

		return parent::get_static_content($module);
	}

	private static function social_links_plain_content() {
		if (!function_exists('themify_get_data'))
			return;

		$data = themify_get_data();
		$pre = 'setting-link_';
		$out = '';

		$field_ids = isset($data[$pre . 'field_ids']) ? json_decode($data[$pre . 'field_ids']) : false;

		if (is_array($field_ids) || is_object($field_ids)) {
			$out .= '<ul>';
			$is_exist = function_exists('icl_t');
			foreach ($field_ids as $fid) {

				$title_name = $pre . 'title_' . $fid;

				if ($is_exist) {
					$title_val = icl_t('Themify', $title_name, $data[$title_name]);
				} else {
					$title_val = isset($data[$title_name]) ? $data[$title_name] : '';
				}

				$link_name = $pre . 'link_' . $fid;
				$link_val = isset($data[$link_name]) ? trim($data[$link_name]) : '';
				if ('' === $link_val) {
					continue;
				}
				$out .= sprintf('<li><a href="%s">%s</a></li>', esc_url($link_val), $title_val);
			}
			$out .= '</ul>';
		}
		return $out;
	}

	/**
	 * Before Builder saves data, find all Widget modules and call
	 * WP_Widget::update() method on widget instance data.
	 *
	 * @return array
	 */
	public static function before_builder_save(array $builder_data, $post_id) {
		if (!empty($builder_data)) {
			if (strpos(json_encode($builder_data), 'class_widget') !== false) {
				foreach ($builder_data as $row_index => $row) {
					if (!empty($row['cols'])) {
						foreach ($row['cols'] as $col_index => $column) {
							if (!empty($column['modules'])) {
								foreach ($column['modules'] as $module_index => $module) {
									if (!empty($module['cols'])) {
										foreach ($module['cols'] as $sub_column_index => $sub_column) {
											if (!empty($sub_column['modules'])) {
												foreach ($sub_column['modules'] as $sub_module_index => $sub_module) {
													if (isset($sub_module['mod_name']) && $sub_module['mod_name'] === 'widget') {
														$builder_data[$row_index]['cols'][$col_index]['modules'][$module_index]['cols'][$sub_column_index]['modules'][$sub_module_index] = self::call_widget_update($sub_module);
													}
												}
											}
										}
									}
									if (isset($module['mod_name']) && $module['mod_name'] === 'widget') {
										$builder_data[$row_index]['cols'][$col_index]['modules'][$module_index] = self::call_widget_update($module);
									}
								}
							}
						}
					}
				}
			}
		}
		return $builder_data;
	}

	/**
	 * Takes a $module array, for "widget" modules will call WP_Widget::update() method
	 * on the widget instance data
	 *
	 * @return array
	 */
	private static function call_widget_update($module) {
		if (!empty($module['mod_settings']['instance_widget'])) {
			global $wp_widget_factory;
			$widget_class = $module['mod_settings']['class_widget'];
			if (isset($wp_widget_factory->widgets[$widget_class])) {
				$instance = $wp_widget_factory->widgets[$widget_class]->update($module['mod_settings']['instance_widget'], array());
				if (!isset($instance['widget-id']) && isset($module['mod_settings']['instance_widget']['widget-id'])) {
					$instance['widget-id'] = $module['mod_settings']['instance_widget']['widget-id'];
				}
				// Search Widget
				$key = 'tf_search_ajax';
				if (isset($module['mod_settings']['instance_widget'][$key])) {
					$instance[$key] = $module['mod_settings']['instance_widget'][$key];
				}
				$module['mod_settings']['instance_widget'] = $instance;
			}
		}
		return $module;
	}

	public static function widget_gallery_lightbox($markup) {
		return str_replace('<a', '<a class="themify_lightbox"', $markup);
	}

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => '',
            'b_i_w_t' => '.module .widgettitle'
        ];
    }
}

TB_Widget_Module::init();
