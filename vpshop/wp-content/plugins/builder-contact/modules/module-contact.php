<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Contact
 */
class TB_Contact_Module extends Themify_Builder_Component_Module {


	public static function get_js_css():array {
		$url=Builder_Contact::$url.'assets/';
		return array(
			'css' =>$url . 'style',
			'js' => $url . 'scripts',
			'ver' => Builder_Contact::get_version()
		);
	}

	public static function get_json_file():array{
		return ['f'=>Builder_Contact::$url . 'json/style.json','v'=>Builder_Contact::get_version()];
	}

	public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
		return __('Contact', 'builder-contact');
	}

	public static function get_module_icon():string{
	    return 'email';
	}

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-contact', Builder_Contact::$url . 'assets/active.js', Builder_Contact::get_version(), [ 'themify-builder-app-js', 'jquery-ui-sortable' ] );
		}
		else{
			wp_enqueue_script('jquery-ui-sortable' );
			$vars['addons'][Builder_Contact::$url . 'assets/active.js']=Builder_Contact::get_version();
		}

		$i18n = include dirname( __DIR__ ) . '/includes/i18n.php';
		$vars['i18n']['label']+= $i18n;

		$vars['contact_vars'] = [
			'url' => Builder_Contact::$url,
			'recaptcha_version' => Builder_Contact::get_option( 'version', 'v2' ),
            'v' => Builder_Contact::get_version(),
			'allowed_ext' => [
				'image'       => array( 'jpg', 'gif', 'png', 'bmp', 'tif', 'ico', 'heic', 'webp' ),
				'cnt_audio'   => array( 'aac', 'flac', 'mka', 'mp3', 'ogg', 'ram', 'wav', 'wma' ),
				'vid'         => array( '3g2', '3gp', '3gpp', 'asf', 'avi', 'divx', 'flv', 'mkv', 'mov', 'mp4', 'mpg', 'mpv', 'ogv', 'wmv' ),
				'cnt_doc'     => array( 'doc', 'docx', 'docm', 'dotm', 'odt', 'pages', 'pdf', 'xps', 'oxps', 'rtf', 'wpd', 'psd', 'xcf' ),
				'cnt_xsl'     => array( 'numbers', 'ods', 'xls', 'xlsx', 'xlsm', 'xlsb' ),
				'cnt_intac'   => array( 'key', 'ppt', 'pptx', 'pptm', 'pps', 'ppsx', 'ppsm', 'sldx', 'sldm', 'odp' ),
				'text'        => array( 'asc', 'csv', 'tsv', 'txt' ),
				'archs'       => array( 'gz', 'rar', 'tar', 'zip', '7z' ),
			],
            'default_template' => Builder_Contact::get_default_template(),
		];

		return $vars;
	}

	/**
	 * Deprecated methods
	 */
	public function __construct() {
		if(method_exists('Themify_Builder_Model', 'add_module')){
			parent::__construct('contact');
		}
		else{//backward
			 parent::__construct(array(
				'name' =>$this->get_name(),
				'slug' => 'contact',
				'category' =>$this->get_group()
			));
		}
	}

    public function get_name(){
		return self::get_module_name();
    }

    public function get_icon(){
		return self::get_module_icon();
    }

    function get_assets() {
		return self::get_js_css();
    }

	public function get_styling() {
		$general = array(
			//bacground
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('', 'background_color', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('', 'bg_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(),
							self::get_color_type(' label'),
							self::get_font_size(),
							self::get_font_style('', 'f_fs_g', 'f_fw_g'),
							self::get_line_height(),
							self::get_text_align(),
							self::get_text_shadow(),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f', 'h'),
							self::get_color_type(' label', 'h'),
							self::get_font_size('', 'f_s', '', 'h'),
							self::get_font_style('', 'f_fs_g', 'f_fw_g', 'h'),
							self::get_text_shadow('', 't_sh', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding()
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('', 'p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin()
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('', 'm', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border()
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('', 'b', 'h')
						)
					)
				))
			)),
			// Width
			self::get_expand('w', array(
				self::get_width('', 'w')
			)),
			// Height & Min Height
			self::get_expand('ht', array(
				self::get_height(),
				self::get_min_height(),
				self::get_max_height()
				)
			),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius()
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('', 'r_c', 'h')
						)
					)
				))
				)
			),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow()
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('', 'sh', 'h')
						)
					)
				))
				)
			),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$labels = array(
			// Font
			self::get_seperator('f'),
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_font_family(array(' .control-label', ' .tb_contact_label'), 'font_family_labels'),
						self::get_color(array(' .control-label', ' .tb_contact_label'), 'font_color_labels'),
						self::get_font_size(array(' .control-label', ' .tb_contact_label'), 'font_size_labels'),
						self::get_font_style(array(' .control-label', ' .tb_contact_label'), 'f_fs_l', 'f_fw_l'),
						self::get_text_shadow(array(' .control-label', ' .tb_contact_label'), 't_sh_l'),
					)
				),
				'h' => array(
					'options' => array(
						self::get_font_family(array(' .control-label', ' .tb_contact_label'), 'f_f_l', 'h'),
						self::get_color(array(' .control-label', ' .tb_contact_label'), 'f_c_l', null, null, 'h'),
						self::get_font_size(array(' .control-label', ' .tb_contact_label'), 'f_s_l', '', 'h'),
						self::get_font_style(array(' .control-label:hover', ' .tb_contact_label:hover'), 'f_fs_l_h', 'f_fw_l_h', null, null, ''),
						self::get_text_shadow(array(' .control-label', ' .tb_contact_label'), 't_sh_l', 'h'),
					)
				)
			))
		);

		$inputs = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'background_color_inputs', 'bg_c', 'background-color'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'b_c_i', 'bg_c', 'background-color', 'h'),
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'font_family_inputs'),
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'font_color_inputs'),
							self::get_font_size(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'font_size_inputs'),
							self::get_font_style(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'f_fs_i', 'f_fw_i'),
							self::get_text_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 't_sh_i'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'f_f_i', 'h'),
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'f_c_i', null, null, 'h'),
							self::get_font_size(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'f_s_i', '', 'h'),
							self::get_font_style(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'f_fs_i', 'f_fw_i', 'h'),
							self::get_text_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 't_sh_i', 'h'),
						)
					)
				))
			)),
			self::get_expand('Placeholder', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="number"]::placeholder', ' textarea::placeholder', ' select::placeholder', ' input[type="tel"]::placeholder'), 'f_f_in_ph'),
							self::get_color(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="number"]::placeholder', ' textarea::placeholder', ' select::placeholder', ' input[type="tel"]::placeholder'), 'f_c_in_ph'),
							self::get_font_size(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="number"]::placeholder', ' textarea::placeholder', ' select::placeholder', ' input[type="tel"]::placeholder'), 'f_s_in_ph'),
							self::get_font_style(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="number"]::placeholder', ' textarea::placeholder', ' select::placeholder', ' input[type="tel"]::placeholder'), 'f_fs_in_ph', 'f_fw_in_ph'),
							self::get_text_shadow(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="number"]::placeholder', ' textarea::placeholder', ' select::placeholder', ' input[type="tel"]::placeholder'), 't_sh_in_ph'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="number"]:hover::placeholder', ' textarea:hover::placeholder', ' select:hover::placeholder', ' input[type="tel"]:hover::placeholder'), 'f_f_in_ph_h', ''),
							self::get_color(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="number"]:hover::placeholder', ' textarea:hover::placeholder', ' select:hover::placeholder', ' input[type="tel"]:hover::placeholder'), 'f_c_in_ph_h', null, null, ''),
							self::get_font_size(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="number"]:hover::placeholder', ' textarea:hover::placeholder', ' select:hover::placeholder', ' input[type="tel"]:hover::placeholder'), 'f_s_in_ph_h', '', ''),
							self::get_font_style(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="number"]:hover::placeholder', ' textarea:hover::placeholder', ' select:hover::placeholder', ' input[type="tel"]:hover::placeholder'), 'f_fs_in_ph', 'f_fw_in_ph', 'h'),
							self::get_text_shadow(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="number"]:hover::placeholder', ' textarea:hover::placeholder', ' select:hover::placeholder', ' input[type="tel"]:hover::placeholder'), 't_sh_in_ph_h', ''),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'border_inputs')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'b_i', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_m', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="number"]', ' textarea', ' select', ' input[type="tel"]', ' input[type="file"]'), 'in_b_sh', 'h')
						)
					)
				))
			))
		);

		$checkbox = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' input[type="checkbox"]', 'b_c_cb', 'bg_c', 'background-color'),
							self::get_color(' input[type="checkbox"]', 'f_c_cb'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' input[type="checkbox"]', 'b_c_cb', 'bg_c', 'background-color', 'h'),
							self::get_color(' input[type="submit"]', 'f_c_cb', null, null, 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' input[type="checkbox"]', 'b_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' input[type="checkbox"]', 'b_cb', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' input[type="checkbox"]', 'p_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' input[type="checkbox"]', 'p_cb', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' input[type="checkbox"]', 'm_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' input[type="checkbox"]', 'm_cb', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' input[type="checkbox"]', 'r_c_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' input[type="checkbox"]', 'r_c_cb', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' input[type="checkbox"]', 's_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' input[type="checkbox"]', 's_cb', 'h')
						)
					)
				))
			))
		);

		$send_button = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .builder-contact-field-send button', 'background_color_send', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .builder-contact-field-send button', 'background_color_send', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .builder-contact-field-send button', 'font_family_send'),
							self::get_color(' .builder-contact-field-send button', 'font_color_send'),
							self::get_font_size(' .builder-contact-field-send button', 'font_size_send'),
							self::get_font_style(' .builder-contact-field-send button', 'f_fs_s', 'f_fw_s'),
							self::get_text_shadow(' .builder-contact-field-send button', 't_sh_b'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .builder-contact-field-send button', 'f_f_s', 'h'),
							self::get_color(' .builder-contact-field-send button', 'f_c_s', null, null, 'h'),
							self::get_font_size(' .builder-contact-field-send button', 'f_s_s', '', 'h'),
							self::get_font_style(' .builder-contact-field-send button', 'f_fs_s', 'f_fw_s', 'h'),
							self::get_text_shadow(' .builder-contact-field-send button', 't_sh_b', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .builder-contact-field-send button', 'border_send')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .builder-contact-field-send button', 'b_s', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .builder-contact-field-send button', 'p_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .builder-contact-field-send button', 'p_sd', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .builder-contact-field-send button', 'r_c_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .builder-contact-field-send button', 'r_c_sd', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .builder-contact-field-send button', 's_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .builder-contact-field-send button', 's_sd', 'h')
						)
					)
				))
			))
		);

		$success_message = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .contact-success', 'background_color_success_message', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .contact-success', 'b_c_s_m', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .contact-success', 'font_family_success_message'),
							self::get_color(' .contact-success', 'font_color_success_message'),
							self::get_font_size(' .contact-success', 'font_size_success_message'),
							self::get_font_style(' .contact-success', 'f_fs_m', 'f_fw_m'),
							self::get_line_height(' .contact-success', 'line_height_success_message'),
							self::get_text_align(' .contact-success', 'text_align_success_message'),
							self::get_text_shadow(' .contact-success', 't_sh_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .contact-success', 'f_f_s_m', 'h'),
							self::get_color(' .contact-success', 'f_c_s_m', null, null, 'h'),
							self::get_font_size(' .contact-success', 'f_s_s_m', '', 'h'),
							self::get_font_style(' .contact-success', 'f_fs_m', 'f_fw_m', 'h'),
							self::get_text_shadow(' .contact-success', 't_sh_m', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .contact-success', 'padding_success_message')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .contact-success', 'p_s_m', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .contact-success', 'margin_success_message')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .contact-success', 'm_s_m', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .contact-success', 'border_success_message')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .contact-success', 'b_s_m', 'h')
						)
					)
				))
			))
		);

		$error_message = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .contact-error', 'background_color_error_message', 'bg_c', 'background-color'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .contact-error', 'b_c_e_m', 'bg_c', 'background-color', 'h'),
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .contact-error', 'font_family_error_message'),
							self::get_color(' .contact-error', 'font_color_error_message'),
							self::get_font_size(' .contact-error', 'font_size_error_message'),
							self::get_font_style(' .contact-error', 'f_fs_e', 'f_fw_e'),
							self::get_line_height(' .contact-error', 'line_height_error_message'),
							self::get_text_align(' .contact-error', 'text_align_error_message'),
							self::get_text_shadow(' .contact-error', 't_sh_e_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .contact-error', 'f_f_e_m'),
							self::get_color(' .contact-error', 'f_c_e_m', null, null, 'h'),
							self::get_font_size(' .contact-error', 'f_s_e_m', '', 'h'),
							self::get_font_style(' .contact-error', 'f_fs_e', 'f_fw_e', 'h'),
							self::get_text_shadow(' .contact-error', 't_sh_e_m', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .contact-error', 'padding_error_message')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .contact-error', 'p_e_m', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .contact-error', 'margin_error_message'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .contact-error', 'm_e_m', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .contact-error', 'border_error_message')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .contact-error', 'b_e_m', 'h')
						)
					)
				))
			))
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'm_t' => array(
					'options' => $this->module_title_custom_style()
				),
				'l' => array(
					'label' => __('Field Labels', 'builder-contact'),
					'options' => $labels
				),
				'i' => array(
					'label' => __('Input Fields', 'builder-contact'),
					'options' => $inputs
				),
				'cb' => array(
					'label' => __('Checkbox', 'builder-contact'),
					'options' => $checkbox
				),
				's_b' => array(
					'label' => __('Send Button', 'builder-contact'),
					'options' => $send_button
				),
				's_m' => array(
					'label' => __('Success Message', 'builder-contact'),
					'options' => $success_message
				),
				'e_m' => array(
					'label' => __('Error Message', 'builder-contact'),
					'options' => $error_message
				)
			)
		);

	}
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if(method_exists('Themify_Builder_Model', 'add_module')){
		new TB_Contact_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Contact_Module');
	}
}