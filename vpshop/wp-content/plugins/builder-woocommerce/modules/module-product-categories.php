<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: WooCommerce Product Categories
 */
class TB_Product_Categories_Module extends Themify_Builder_Component_Module {

	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}
	
	public static function get_json_file():array{
		return Themify_Builder_Model::check_module_active('products')?parent::get_json_file():['f'=>Builder_Woocommerce::$url . 'json/style.json','v'=>Builder_Woocommerce::get_version()];
	}

	public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
		return __('Product Categories', 'builder-wc');
	}

	public static function get_module_icon():string{
	    return 'shopping-cart';
	}

	public static function get_js_css():array {
		return array(
			'css' => Builder_Woocommerce::$url . 'assets/product-categories',
			'ver' => Builder_Woocommerce::get_version()
		);
	}

    public static function set_category_image_size($size){
        $sizes = wp_get_additional_image_sizes();
        if(isset($sizes['themify_wc_category_thumbnail'])){
            $size = array_values($sizes['themify_wc_category_thumbnail']);
        }
        return $size;
    }

    public static function change_category_image_size($image, $attachment_id, $size, $icon){
        // Make sure it's called from WC category thumbnail
        if($size===apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' )){
            $sizes = wp_get_additional_image_sizes();
            if(isset($sizes['themify_wc_category_thumbnail'])){
                $image[0]=themify_get_image(array('urlonly'=>true,'src'=>$image[0],'w'=>$sizes['themify_wc_category_thumbnail']['width'],'h'=>$sizes['themify_wc_category_thumbnail']['height']));
            }
        }
        return $image;
    }

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-product-categories', Builder_Woocommerce::$url . 'assets/active-product_categories.js', Builder_Woocommerce::get_version(), [ 'themify-builder-app-js' ] );
		}
		else{
			$vars['addons'][Builder_Woocommerce::$url . 'assets/active-product_categories.js']=Builder_Woocommerce::get_version();
		}

		$i18n = include_once( dirname( __DIR__ ) . '/includes/i18n.php' );
		if ( is_array( $i18n ) ) {
			$vars['i18n']['label']+= $i18n;
		}

		return $vars;
	}


	/**
	 * Deprecated methods
	 */
	public function __construct() {
            if(method_exists('Themify_Builder_Model', 'add_module')){
                parent::__construct('product-categories');
            }
            else{//backward
                 parent::__construct(array(
                    'name' =>$this->get_name(),
                    'slug' => 'product-categories',
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
				    self::get_color('', 'background_color','bg_c','background-color')
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
					self::get_color(' .products .product a','font_color'),
					self::get_font_size(),
					self::get_line_height(),
					self::get_text_align(' .products .product'),
					self::get_text_transform(' .products .product h3', 'text_transform_title'),
					self::get_font_style('', 'font_style_title'),
					self::get_text_shadow(),
				)
			    ),
			    'h' => array(
				'options' => array(
					self::get_font_family('','f_f','h'),
					self::get_color(' .products .product a','f_c',null,null,'h'),
					self::get_font_size('','f_s','','h'),
					self::get_font_style('', 'f_st','f_w','h'),
					self::get_text_shadow('','t_sh','h'),
				)
			    )
			))
		    )),
		    self::get_expand('l', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
					self::get_color(' a h3','link_color'),
					self::get_text_decoration(' a h3')
				)
			    ),
			    'h' => array(
				'options' => array(
					self::get_color(' a h3','link_color',null,null,'hover'),
					self::get_text_decoration(' a h3','t_a','h')
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
				    self::get_margin('','m','h')
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
				    self::get_border('','b','h')
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
		$category_container = array(
			//bacground
		    self::get_expand('bg', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_color('.module .product-category', 'b_c_c_cn','bg_c','background-color')
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_color('.module .product-category', 'b_c_c_cn','bg_c','background-color','h')
				)
			    )
			))
		    )),
			// Font
			self::get_expand('f', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_font_family('.module .product-category', 'f_f_c_cn'),
					self::get_color('.module .product-category', 'f_c_c_cn'),
					self::get_font_size('.module .product-category', 'f_s_c_cn'),
					self::get_line_height('.module .product-category', 'l_h_c_cn'),
					self::get_letter_spacing('.module .product-category', 'l_s_c_cn'),
					self::get_text_align('.module .product-category', 't_a_c_cn'),
					self::get_text_transform('.module .product-category', 't_t_c_cn'),
					self::get_font_style('.module .product-category', 'f_sy_c_cn'),
					self::get_text_decoration('.module .product-category', 't_d_r_c_cn'),
					self::get_text_shadow('.module .product-category','t_sh_c_cn'),
				)
				),
				'h' => array(
				'options' => array(
					self::get_font_family('.module .product-category', 'f_f_c_cn', 'h'),
					self::get_color('.module .product-category','f_c_c_cn', null,null, 'h'),
					self::get_font_size('.module .product-category', 'f_s_c_cn', '', 'h'),
					self::get_font_style('.module .product-category', 'f_sy_c_cn', 'h'),
					self::get_text_decoration('.module .product-category', 't_d_r_c_cn', 'h'),
					self::get_text_shadow('.module .product-category','t_sh_c_cn', 'h'),
				)
				)
			))
			)),
			// Link
			self::get_expand('l', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color('.module .product-category a', 'l_c_cn'),
					self::get_text_decoration('.module .product-category a', 't_d_cn')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color('.module .product-category a', 'l_c_cn', 'h'),
					self::get_text_decoration('.module .product-category a', 't_d_cn', 'h')
				)
				)
			))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding('.module .product-category', 'p_cn')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding('.module .product-category', 'p_cn', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin('.module .product-category', 'm_cn')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin('.module .product-category', 'm_cn', 'h')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border('.module .product-category', 'b_cn')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border('.module .product-category', 'b_cn', 'h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .product-category', 'c_cn_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .product-category', 'c_cn_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .product-category', 'c_cn_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .product-category', 'c_cn_b_sh', 'h')
						)
					)
				))
			))
		);
		$category_image = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color('.module .product-category img', 'b_c_c_i', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color('.module .product-category img', 'b_c_c_i', 'bg_c', 'background-color', 'h')
				)
				)
			))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding('.module .product-category img', 'p_c_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding('.module .product-category img', 'p_c_i', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin('.module .product-category img', 'm_c_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin('.module .product-category img', 'm_c_i', 'h')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border('.module .product-category img', 'b_c_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border('.module .product-category img', 'b_c_i', 'h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .product-category img', 'c_i_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .product-category img', 'c_i_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .product-category img', 'c_i_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .product-category img', 'c_i_b_sh', 'h')
						)
					)
				))
			))
		);
		$category_title = array(
			// Font
			self::get_expand('f', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_font_family('.module .product-category h3', 'f_f_c_t'),
					self::get_color('.module .product-category h3', 'f_c_c_t'),
					self::get_font_size('.module .product-category h3', 'f_s_c_t'),
					self::get_line_height('.module .product-category h3', 'l_h_c_t'),
					self::get_letter_spacing('.module .product-category h3', 'l_s_c_t'),
					self::get_text_transform('.module .product-category h3', 't_t_c_t'),
					self::get_font_style('.module .product-category h3', 'f_sy_c_t', 'f_w_c_t'),
					self::get_text_decoration('.module .product-category h3', 't_d_r_c_t'),
					self::get_text_shadow('.module .product-category h3', 't_sh_c_t'),
				)
				),
				'h' => array(
				'options' => array(
					self::get_font_family('.module .product-category h3', 'f_f_c_t', 'h'),
					self::get_color('.module .product-category h3', 'f_c_c_t', null, null, 'h'),
					self::get_font_size('.module .product-category h3', 'f_s_c_t', '', 'h'),
					self::get_font_style('.module .product-category h3', 'f_sy_c_t', 'f_w_c_t', 'h'),
					self::get_text_decoration('.module .product-category h3', 't_d_r_c_t', 'h'),
					self::get_text_shadow('.module .product-category h3', 't_sh_c_t','h'),
				)
				)
			))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding('.module .product-category h3', 'p_c_t')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding('.module .product-category h3', 'p_c_t', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin('.module .product-category h3', 'm_c_t'),
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin('.module .product-category h3', 'm_c_t', 'h'),
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border('.module .product-category h3', 'b_c_t')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border('.module .product-category h3', 'b_c_t', 'h')
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
				'c' => array(
					'label' => __('Category Container', 'themify'),
					'options' => $category_container
				),
				'ci' => array(
					'label' => __('Category Image', 'themify'),
					'options' => $category_image
				),
				't' => array(
					'label' => __('Category Title', 'themify'),
					'options' => $category_title
				)
			)
		);

	}

    /**
     * copy of woocommerce_subcategory_thumbnail() with added fallback
     */
    public static function get_category_image( $category ) {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );

		if ( $thumbnail_id ) {
			$image        = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
			$image        = $image[0];
			$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, $small_thumbnail_size ) : false;
			$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, $small_thumbnail_size ) : false;
		} else if ( get_term_meta( $category->term_id, 'tbp_cover', true ) ) {
            $image = get_term_meta( $category->term_id, 'tbp_cover', true );
			$image_srcset = false;
			$image_sizes  = false;
		} else {
			$image        = wc_placeholder_img_src();
			$image_srcset = false;
			$image_sizes  = false;
		}

		if ( $image ) {
			// Prevent esc_url from breaking spaces in urls for image embeds.
			// Ref: https://core.trac.wordpress.org/ticket/23605.
			$image = str_replace( ' ', '%20', $image );

			// Add responsive image markup if available.
			if ( $image_srcset && $image_sizes ) {
				echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" />';
			} else {
				echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
			}
		}
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
		new TB_Product_Categories_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Product_Categories_Module');
	}
}