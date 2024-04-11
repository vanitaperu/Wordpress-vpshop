<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: WooCommerce
 */
class TB_Products_Module extends Themify_Builder_Component_Module {
	
	private static $Actions=array();
	private static $args=array();
	private static $isLoop=false;

	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
		return __('Woo Products', 'builder-wc');
	}

	public static function get_module_icon():string{
	    return 'shopping-cart';
	}

	public static function get_json_file():array{
		return ['f'=>Builder_Woocommerce::$url . 'json/style.json','v'=>Builder_Woocommerce::get_version()];
	}

	public static function get_js_css():array {
		$url=Builder_Woocommerce::$url . 'assets/';
		$arr= array(
			'css' => $url. 'products',
			'ver' => Builder_Woocommerce::get_version()
		);
		if(!Themify_Builder_Model::is_front_builder_activate()){
			$arr['js']=$url. 'scripts';
		}
		if(is_rtl()){
			$css=$arr['css'];
			$arr['css']=array(
				$css,
				$url . 'modules/rtl'
			);
		}
		return $arr;
	}

	public static function set_filters(array $args){
	    self::$args=$args;
	    self::remove_filters();
	    $priority=$args['hide_feat_img_products']==='yes'?1000:10;
	    add_filter( 'woocommerce_product_get_image', array(__CLASS__,'loop_image'),$priority,5);
	    
	    if( $args['hide_rating_products'] !== 'yes' ) {
			add_filter('option_woocommerce_enable_review_rating', array(__CLASS__,'enable'),100);
			// Always show rating even for 0 rating
			if ($args['show_empty_rating']==='show') {
				add_filter('woocommerce_product_get_rating_html', array(__CLASS__, 'product_get_rating_html'), 100, 3);
			}
	    }
	}
	
	public static function loop_image($image, $product, $size, $attr, $placeholder ){
	    if(self::$args['hide_feat_img_products']==='yes'){
			return '';
	    }
	    return self::retrieve_template('partials/image.php', self::$args,dirname(__DIR__).'/templates','',false);
	}
	
	private static function remove_filters(){
	  if(self::$isLoop===false){
		    self::$isLoop=true;
		    $actions=array(
			    'woocommerce_before_shop_loop_item'=>array(
				    'woocommerce_template_loop_product_link_open'=>10
			    ),
			    'woocommerce_before_shop_loop_item_title'=>array(
				    'woocommerce_show_product_loop_sale_flash'=>10,
				    'woocommerce_template_loop_product_thumbnail'=>10
			    ),
			    'woocommerce_shop_loop_item_title'=>array(
				    'woocommerce_template_loop_product_title'=>10
			    ),
			    'woocommerce_after_shop_loop_item_title'=>array(
				    'woocommerce_template_loop_rating'=>5,
				    'woocommerce_template_loop_price'=>10
			    ),
			    'woocommerce_after_shop_loop_item'=>array(
				    'woocommerce_template_loop_product_link_close'=>5,
				    'woocommerce_template_loop_add_to_cart'=>10
			    )
		    );
		    foreach($actions as $ev=>$functions){
			    foreach($functions as $func=>$priority){
				if(has_action($ev,$func)){
					remove_action($ev,$func,$priority);
					if(!isset(self::$Actions[$ev])){
					    self::$Actions[$ev]=array();
					}
					self::$Actions[$ev][$func]=$priority;
				}
			    }
		    }
		}	
	}
	
	public static function revert_filters(){
		if(self::$isLoop===true){
		    foreach(self::$Actions as $ev=>$functions){
				if(!empty($functions)){
					foreach($functions as $func=>$priority){
						add_action($ev,$func,$priority);
					}
				}
		    }
		    $priority=self::$args['hide_feat_img_products']==='yes'?1000:10;
		    remove_filter( 'woocommerce_product_get_image', array(__CLASS__,'loop_image'),$priority);
		    remove_filter('option_woocommerce_enable_review_rating', array(__CLASS__,'enable'),100);
		    remove_filter('woocommerce_product_get_rating_html', array(__CLASS__, 'product_get_rating_html'), 100);
		    self::$args=self::$Actions=array();
		    self::$isLoop=false;
		}
	}
	
	public static function enable(){
	    return 'yes';
	}
	
	public static function disable(){
	    return 'no';
	}
	
	/*
 	* Always display rating in archive product page
 	*/
	public static function product_get_rating_html( $rating_html, $rating, $count ) {
		if('0' === $rating){
			/* translators: %s: rating */
			$label = __( 'Rated 0 out of 5', 'builder-wc' );
			$rating_html  = '<div class="star-rating" role="img" aria-label="' . $label . '">' . wc_get_star_rating_html( $rating, $count ) . '</div>';
		}
		return $rating_html;
	}

	/**
	 * Renders the content added by Hook Content feature. This doesn't use WP's hook system
	 * for faster performance.
	 *
	 * @return null
	 */
	public static function display_hook( $location, $hook_content ) {
		if ( isset( $hook_content[ $location ] ) ) {
			foreach ( $hook_content[ $location ] as $content ) {
				echo do_shortcode( $content );
			}
		}
	}

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-products', Builder_Woocommerce::$url . 'assets/active-products.js', Builder_Woocommerce::get_version(), [ 'themify-builder-app-js' ] );
		}
		else{
			$vars['addons'][Builder_Woocommerce::$url . 'assets/active-products.js']=Builder_Woocommerce::get_version();
		}

		$i18n = include_once dirname( __DIR__ )  . '/includes/i18n.php';
		if ( is_array( $i18n ) ) {
			$vars['i18n']['label']+= $i18n;
		}
		$vars['builderWCVars'] = [
			'admin_css' => Builder_Woocommerce::$url . 'assets/admin.min.css'
		];

		return $vars;
	}


	/**
	 * Deprecated methods
	 */
	public function __construct() {
            if(method_exists('Themify_Builder_Model', 'add_module')){
                parent::__construct('products');
            }
            else{//backward
                 parent::__construct(array(
                    'name' =>$this->get_name(),
                    'slug' => 'products',
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
				    self::get_color('', 'background_color','bg_c','background-color','h')
				)
			    )
			))
		    )),
		    self::get_expand('f', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
					self::get_font_family(),
					self::get_color('','font_color'),
					self::get_font_size(),
					self::get_font_style( '', 'f_fs_g', 'f_fw_g' ),
					self::get_line_height(),
					self::get_text_align(),
					self::get_text_shadow(),
				)
			    ),
			    'h' => array(
				'options' => array(
					self::get_font_family('','f_f','h'),
					self::get_color('','f_c',null,null,'h'),
					self::get_font_size('','f_s','','h'),
					self::get_font_style( '', 'f_fs_g', 'f_fw_g', 'h' ),
					self::get_text_shadow('','t_sh','h'),
				)
			    )
			))
		    )),
		    self::get_expand('l', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
					self::get_color(' a:not(.add_to_cart_button)','link_color'),
					self::get_text_decoration(' a:not(.add_to_cart_button)')
				)
			    ),
			    'h' => array(
				'options' => array(
					self::get_color(' a:not(.add_to_cart_button)','link_color',null,null,'hover'),
					self::get_text_decoration(' a:not(.add_to_cart_button)','t_a','h')
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
		$product_container = array(
			    // Background
			    self::get_expand('bg', array(
				self::get_tab(array(
				    'n' => array(
					'options' => array(
					    self::get_color(' .product', 'b_c_p_ctr','bg_c','background-color')
					)
				    ),
				    'h' => array(
					'options' => array(
					    self::get_color(' .product', 'b_c_p_ctr','bg_c','background-color','h')
					)
				    )
				))
			)),
			// Padding
			self::get_expand('p', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_padding(' .product','p_p_ctr')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_padding(' .product', 'p_p_ctr', 'h')
				    )
				)
			    ))
			)),
			// Margin
			self::get_expand('m', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
				       self::get_margin(' .product','m_p_ctr')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_margin(' .product','m_p_ctr','h')
				    )
				)
			    ))
			)),
			// Border
			self::get_expand('b', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_border(' .product','b_p_ctr')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_border(' .product','b_p_ctr','h')
				    )
				)
			    ))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_border_radius(' .product','r_c_p_ctr')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_border_radius(' .product','r_c_p_ctr','h')
				    )
				)
			    ))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product','b_sh_ctr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product','b_sh_ctr', 'h')
						)
					)
				))
			))
		);
		$product_content = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
				    'n' => array(
					'options' => array(
					    self::get_color(' .post-content', 'b_c_p_ct','bg_c','background-color')
					)
				    ),
				    'h' => array(
					'options' => array(
					     self::get_color(' .post-content', 'b_c_p_ct','bg_c','background-color','h')
					)
				    )
				))
			)),
			self::get_expand('f', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					    self::get_font_family( ' .post-content', 'f_f_p_ct'),
					    self::get_color( ' .post-content','f_c_p_ct'),
					    self::get_font_size(' .post-content','f_s_p_ct'),
					    self::get_line_height(' .post-content','l_h_p_ct'),
					    self::get_text_align(' .post-content','t_a_p_ct'),
					    self::get_text_transform(' .post-content','t_t_p_ct'),
					    self::get_font_style(' .post-content', 'f_sy_p_ct', 'f_w_p_ct'),
						self::get_text_shadow(' .post-content', 't_sh_p_c'),
				    )
				),
				'h' => array(
				    'options' => array(
					    self::get_font_family(' .post-content', 'f_f_p_ct','h'),
					    self::get_color(' .post-content','f_c_p_ct',null,null,'h'),
					    self::get_font_size(' .post-content','f_s_p_ct','','h'),
					    self::get_font_style(' .post-content', 'f_sy_p_ct', 'f_w_p_ct','h'),
						self::get_text_shadow(' .post-content', 't_sh_p_c','h'),
				    )
				)
			    ))
			)),
			// Padding
			self::get_expand('p', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_padding(' .post-content','p_p_ct')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_padding(' .post-content','p_p_ct','h')
				    )
				)
			    ))
			)),
			// Margin
			self::get_expand('m', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
				       self::get_margin(' .post-content','m_p_ct')
				    )
				),
				'h' => array(
				    'options' => array(
					 self::get_margin(' .post-content','m_p_ct','h')
				    )
				)
			    ))
			)),
			// Border
			self::get_expand('b', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_border(' .post-content','b_p_ct')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_border(' .post-content','b_p_ct','h')
				    )
				)
			    ))
			))
                        
		);
		$product_title = array(
			// font
			self::get_expand('f', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					    self::get_font_family(array( '.module .product h3', '.module .product h3 a' ), 'f_f_p_t'),
					    self::get_color(array( '.module .product h3', '.module .product h3 a'),'f_c_p_t'),
					    self::get_font_size('.module .product h3','f_s_p_t'),
					    self::get_line_height('.module .product h3','l_h_p_t'),
					    self::get_text_align('.module .product h3','t_a_p_t'),
					    self::get_text_transform('.module .product h3','t_t_p_t'),
					    self::get_font_style(array( '.module .product h3', '.module .product h3 a' ), 'f_sy_p_t', 'f_w_p_t'),
						self::get_text_shadow(array( '.module .product h3', '.module .product h3 a'), 't_sh_p_t'),
				    )
				),
				'h' => array(
				    'options' => array(
					    self::get_font_family(array( '.module .product h3', '.module .product h3 a'), 'f_f_p_t','h'),
					    self::get_color(array( '.module .product h3', '.module .product h3 a'),'f_c_p_t',null,null,'h'),
					    self::get_font_size('.module .product h3','f_s_p_t','','h'),
					    self::get_font_style(array( '.module .product h3', '.module .product h3 a'), 'f_sy_p_t', 'f_w_p_t','h'),
						self::get_text_shadow(array( '.module .product h3', '.module .product h3 a'), 't_sh_p_t'.'h'),
				    )
				)
			    ))
			)),
			// Padding
			self::get_expand('p', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_padding('.module .product .woocommerce-loop-product__title','p_p_t')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_padding('.module .product .woocommerce-loop-product__title','p_p_t','h')
				    )
				)
			    ))
			)),
			// Margin
			self::get_expand('m', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
				       self::get_margin('.module .product .woocommerce-loop-product__title','m_p_t')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_margin('.module .product .woocommerce-loop-product__title','m_p_t','h')
				    )
				)
			    ))
			)),
			// Border
			self::get_expand('b', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_border('.module .product .woocommerce-loop-product__title','b_p_t')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_border('.module .product .woocommerce-loop-product__title','b_p_t','h')
				    )
				)
			    ))
			))
                        
		);
		$image = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .post-image img', 'p_i_bg_c', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .post-image img', 'p_i_bg_c', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .post-image img', 'p_i_p')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .post-image img', 'p_i_p', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .post-image img', 'p_i_m')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .post-image img', 'p_i_m', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .post-image img', 'p_i_b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .post-image img', 'p_i_b', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post-image img', 'p_i_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post-image img', 'p_i_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post-image img', 'p_i_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post-image img', 'p_i_b_sh', 'h')
						)
					)
				))
			))
		
		);
		$price = array(
			// Font
			self::get_expand('f', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_font_family(' .product .price', 'f_f_p_p'),
					self::get_color(' .product .price','f_c_p_p'),
					self::get_font_size(' .product .price','f_s_p_p'),
					self::get_line_height(' .product .price','l_h_p_p'),
					self::get_text_align(' .product .price','t_a_p_p'),
					self::get_font_style(' .product .price', 'f_sy_p_p', 'f_w_p_p'),
					self::get_text_shadow(' .product .price', 't_sh_p'),
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_font_family(' .product .price', 'f_f_p_p','h'),
					self::get_color(' .product .price','f_c_p_p',null,null,'h'),
					self::get_font_size(' .product .price','f_s_p_p','','h'),
					self::get_font_style(' .product .price', 'f_sy_p_p', 'f_w_p_p','h'),
					self::get_text_shadow(' .product .price', 't_sh_p','h'),
				    )
				)
			    ))
			)),
			self::get_expand('p', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_padding(' .product .price','p_p_p')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_padding(' .product .price','p_p_p','h')
				    )
				)
			    ))
			)),
			// Margin
			self::get_expand('m', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
				       self::get_margin(' .product .price','m_p_p')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_margin(' .product .price','m_p_p','h')
				    )
				)
			    ))
			)),
			// Border
			self::get_expand('b', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_border(' .product .price','b_p_p')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_border(' .product .price','b_p_p','h')
				    )
				)
			    ))
			))
		);
		$button = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
				    'n' => array(
					'options' => array(
					    self::get_color(' .product .add-to-cart-button .button', 'b_c_p_b','bg_c','background-color'),
					)
				    ),
				    'h' => array(
					'options' => array(
					     self::get_color(' .product .add-to-cart-button .button:hover', 'b_c_h_p_b','bg_c','background-color')
					)
				    )
				))
			)),
			// Font
			self::get_expand('f', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_font_family(' .product .add-to-cart-button .button', 'f_f_p_b'),
					self::get_color(' .product .add-to-cart-button .button','f_c_p_b'),
					self::get_font_size(' .product .add-to-cart-button .button','f_s_p_b'),
					self::get_font_style(' .product .add-to-cart-button .button','f_fs_p_b', 'f_fw_p_b'),
					self::get_line_height(' .product .add-to-cart-button .button','l_h_p_b'),
					self::get_text_align(' .product .add-to-cart-button','t_a_p_b'),
					self::get_text_shadow(' .product .add-to-cart-button .button', 't_sh_b'),
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_font_family(' .product .add-to-cart-button .button:hover', 'f_f_h_p_b'),
					self::get_color(' .product .add-to-cart-button .button:hover','f_c_h_p_b'),
					self::get_font_size(' .product .add-to-cart-button .button','f_s_p_b','','h'),
					self::get_font_style(' .product .add-to-cart-button .button','f_fs_p_b', 'f_fw_p_b', 'h'),
					self::get_text_shadow(' .product .add-to-cart-button .button', 't_sh_b','h'),
				    )
				)
			    ))
			)),
			self::get_expand('p', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_padding(' .product .add-to-cart-button .button','p_p_b')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_padding(' .product .add-to-cart-button .button','p_p_b','h')
				    )
				)
			    ))
			)),
			// Margin
			self::get_expand('m', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
				       self::get_margin(' .product .add-to-cart-button .button','m_p_b')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_margin(' .product .add-to-cart-button .button','m_p_b','h')
				    )
				)
			    ))
			)),
			// Border
			self::get_expand('b', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_border(' .product .add-to-cart-button .button','b_p_b')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_border(' .product .add-to-cart-button .button','b_p_b','h')
				    )
				)
			    ))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .product .add-to-cart-button .button', 'r_c_p_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .product .add-to-cart-button .button', 'r_c_p_b', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product .add-to-cart-button .button', 'sh_p_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product .add-to-cart-button .button', 'sh_p_b', 'h')
						)
					)
				))
			))
		);
		
		$controls = array(
			// Arrows
			self::get_expand(__('Arrows', 'builder-wc'), array(
			   self::get_width(array(' .tf_carousel_nav_wrap .carousel-prev',' .tf_carousel_nav_wrap .carousel-next'), 'w_ctrl'),
			   self::get_height(array(' .tf_carousel_nav_wrap .carousel-prev',' .tf_carousel_nav_wrap .carousel-next'), 'h_ctrl')
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
					'c' => array(
						'label' => __('Container', 'builder-wc'),
						'options' => $product_container
					),
					'co' => array(
						'label' => __('Description', 'builder-wc'),
						'options' => $product_content
					),
					'i' => array(
						'label' => __('Image', 'builder-wc'),
						'options' => $image
					),
					't' => array(
						'label' => __('Title', 'builder-wc'),
						'options' => $product_title
					),
					'p' => array(
						'label' => __('Price', 'builder-wc'),
						'options' => $price
					),
					'b' => array(
						'label' => __('Button', 'builder-wc'),
						'options' => $button
					),
					'ctrl' => array(
						'label' => __('Controls', 'builder-wc'),
						'options' => $controls
					)
					)
			
		    );
	}
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
		new TB_Products_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Products_Module');
	}
}