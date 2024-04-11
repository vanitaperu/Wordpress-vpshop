<?php

/**
 * Main Themify class
 * @package themify
 * @since 1.0.0
 */
class Themify {

    public $layout;
    public $sticky_sidebar = null;
    public $post_filter = false;
    public $post_layout;
    public $post_layout_type = 'default';
    public $hide_title;
    public $hide_meta;
    public $hide_meta_author;
    public $hide_meta_category;
    public $hide_meta_comment;
    public $hide_meta_tag;
    public $hide_date;
    public $hide_image;
    public $media_position;
    public $unlink_title;
    public $unlink_image;
    public $display_content = '';
    public $auto_featured_image;
    public $width = '';
    public $height = '';
    public $image_size = '';
    public $avatar_size = 96;
    public $page_navigation;
    public $posts_per_page;
    public $is_shortcode = false;
    public $page_id = '';
    public $query_category = '';
    public $query_post_type = '';
    public $query_taxonomy = '';
    public $paged = '';
    public $query_all_post_types;
    ////////////////////////////////////////////
    // Product Variables
    ////////////////////////////////////////////
    public $query = '';
    public $query_products = '';
    public $query_products_field = '';
    public $products_hover_image = 'enable';
    public $is_related_loop = false;
    public $builder_args = array(); //for builder modules
    public $load_from_products_module = false; // Used by WC addon
    
    private const PAGE_IMAGE_WIDTH = 978;
    // Default Single Image Size
    private const SINGLE_IMAGE_WIDTH = 1160;
    private const SINGLE_IMAGE_HEIGHT = 500;
    // List Post
    private const LIST_POST_WIDTH = 1160;
    private const LIST_POST_HEIGHT = 500;
	  // Grid6
    private const GRID6_WIDTH = 180;
    private const GRID6_HEIGHT = 120;
	  // Grid5
    private const GRID5_WIDTH = 210;
    private const GRID5_HEIGHT = 130;
    // Grid4
    private const GRID4_WIDTH = 260;
    private const GRID4_HEIGHT = 160;
    // Grid3
    private const GRID3_WIDTH = 360;
    private const GRID3_HEIGHT = 225;
    // Grid2
    private const GRID2_WIDTH = 560;
    private const GRID2_HEIGHT = 350;
    // List Large
    private const LIST_LARGE_IMAGE_WIDTH = 350;
    private const LIST_LARGE_IMAGE_HEIGHT = 200;
    // List Thumb
    private const LIST_THUMB_IMAGE_WIDTH = 230;
    private const LIST_THUMB_IMAGE_HEIGHT = 200;
    // List Grid2 Thumb
    private const GRID2_THUMB_WIDTH = 120;
    private const GRID2_THUMB_HEIGHT = 100;
    // Sorting Parameters
    public $order = 'DESC';
    public $orderby = 'date';
    public $order_meta_key = false;
    
    public $page_title;
    public $image_page_single_width;
    public $image_page_single_height;
    public $hide_page_image;
    public $excerpt_length;
    public $isPage=false;
    public $google_fonts;

	public $post_module_hook = null;
	public $post_module_tax = null;
	public $more_text='';
	public $more_link='';
	public $themify_post_title_tag='';

    function __construct() {
	add_action('template_redirect', array($this, 'template_redirect'),5);
    }

    private function set_search() {

	$this->layout = themify_get('setting-search-result_layout', 'sidebar1', true);
	$this->post_layout = themify_get('setting-search-result_post_layout', 'list-post', true);
	$this->post_layout_type = themify_get('setting-search-post_content_layout', $this->post_layout_type, true);
	$this->display_content = themify_get('setting-search-result_layout_display', 'excerpt', true);
	$this->hide_title = themify_get('setting-search-result_post_title', 'no', true);
	$this->unlink_title = themify_get('setting-search-result_unlink_post_title', 'no', true);
	$this->hide_date = themify_get('setting-search-result_post_date', 'no', true);
	$this->media_position = 'auto_tiles'===$this->post_layout || in_array($this->post_layout_type,array('polaroid','flip'))?'above':themify_get('setting-search-result_media_position', 'above', true);
	$this->hide_image = themify_get('setting-search-result_post_image', 'no', true);
	$this->unlink_image = themify_get('setting-search-result_unlink_post_image', 'no', true);
	$this->width = themify_get('setting-search-image_post_width', '', true);
	$this->height = themify_get('setting-search-image_post_height', '', true);
	$this->auto_featured_image = themify_check('setting-search-auto_featured_image', true);
	$post_meta_key = 'setting-search-result_';
	$this->hide_meta = themify_get($post_meta_key . 'post_meta', '', true);
	if ($this->hide_meta !== 'yes') {
	    $post_meta_keys = array(
		'_author' => 'post_meta_author',
		'_category' => 'post_meta_category',
		'_comment' => 'post_meta_comment',
		'_tag' => 'post_meta_tag'
	    );

	    foreach ($post_meta_keys as $k => $v) {
		$this->{'hide_meta' . $k} = themify_get($post_meta_key . $v, '', true);
	    }
	}
    }

    private function themify_set_global_options() {
	///////////////////////////////////////////
	//Global options setup
	///////////////////////////////////////////
	$this->layout = themify_get('setting-default_layout', 'sidebar1', true);
	$this->post_layout = themify_get('setting-default_post_layout', 'list-post', true);
	$this->post_layout_type = themify_get('setting-post_content_layout', $this->post_layout_type, true);
	$this->hide_title = themify_get('setting-default_post_title', '', true);
	$this->unlink_title = themify_get('setting-default_unlink_post_title', '', true);
	$this->media_position = 'auto_tiles'===$this->post_layout || in_array($this->post_layout_type,array('polaroid','flip'))?'above':themify_get('setting-default_media_position', 'above', true);
	$this->hide_image = themify_get('setting-default_post_image', '', true);
	$this->unlink_image = themify_get('setting-default_unlink_post_image', '', true);
	$this->auto_featured_image = themify_check('setting-auto_featured_image', true);


	$this->hide_meta = themify_get('setting-default_post_meta', '', true);
	$this->hide_meta_author = themify_get('setting-default_post_meta_author', '', true);
	$this->hide_meta_category = themify_get('setting-default_post_meta_category', '', true);
	$this->hide_meta_comment = themify_get('setting-default_post_meta_comment', '', true);
	$this->hide_meta_tag = themify_get('setting-default_post_meta_tag', '', true);

	$this->hide_date = themify_get('setting-default_post_date', '', true);

	// Set Order & Order By parameters for post sorting
	$this->order = themify_get('setting-index_order', $this->order, true);
	$this->orderby = themify_get('setting-index_orderby', $this->orderby, true);

	if ($this->orderby === 'meta_value' || $this->orderby === 'meta_value_num') {
	    $this->order_meta_key = themify_get('setting-index_meta_key', '', true);
	}

	$this->display_content = themify_get('setting-default_layout_display', 'excerpt', true);
	$this->excerpt_length = themify_get('setting-default_excerpt_length', '', true);
	$this->avatar_size = apply_filters('themify_author_box_avatar_size', $this->avatar_size);

	$this->posts_per_page = get_option('posts_per_page');
	$this->width = themify_get('setting-image_post_width', '', true);
	$this->height = themify_get('setting-image_post_height', '', true);
    }

    function template_redirect() {

	$this->themify_set_global_options();

	if (is_singular()) {
	    $this->display_content = 'content';
	}
	if (is_page() || themify_is_shop()) {
	    if (post_password_required()) {
		return;
	    }
	    $this->page_id = get_the_ID();

	    global $paged;

	    // Set Page Number for Pagination
	    $this->paged = get_query_var('paged');
	    if (empty($this->paged)) {
		$this->paged = get_query_var('page', 1);
	    }

	    $paged = $this->paged;
	    $this->layout = themify_get_both('page_layout', 'setting-default_page_layout', 'sidebar1');
	    $this->page_title = themify_get_both('hide_page_title', 'setting-hide_page_title', 'no');
	    $this->hide_page_image = themify_get('setting-hide_page_image', false, true) === 'yes' ? 'yes' : 'no';
	    $this->image_page_single_width = themify_get( 'setting-page_featured_image_width',self::PAGE_IMAGE_WIDTH,true );
	    $this->image_page_single_height = themify_get( 'setting-page_featured_image_height',0,true );
	    if(!themify_is_shop() && themify_get('product_query_category', '') === ''){
		$this->query_category = themify_get('query_category', '');

		if ($this->query_category !== '') {
		    $this->query_taxonomy = 'category';
		    $this->query_post_type = 'post';
		    $this->post_layout = themify_get('layout', 'list-post');
		    $this->post_layout_type = themify_get('post_content_layout', $this->post_layout_type);
            $this->media_position = 'auto_tiles'===$this->post_layout || in_array($this->post_layout_type,array('polaroid','flip'))?'above':$this->media_position;
		    $this->hide_title = themify_get('hide_title', $this->hide_title);
		    $this->unlink_title = themify_get('unlink_title', $this->unlink_title);
		    $this->hide_image = themify_get('hide_image', $this->hide_image);
		    $this->unlink_image = themify_get('unlink_image', $this->unlink_image);
		    $this->display_content = themify_get('display_content', 'excerpt');
		    $this->page_navigation = themify_get('hide_navigation', $this->page_navigation);
		    $this->posts_per_page = themify_get('posts_per_page', $this->posts_per_page);
		    $this->hide_date = themify_get('hide_date', $this->hide_date);
		    $this->order = themify_get('order', 'desc');
		    $this->orderby = themify_get('orderby', 'date');
		    $this->width = themify_get('image_width', $this->width);
		    $this->height = themify_get('image_height', $this->height);
		    // Post Meta Values ///////////////////////
		    $post_meta_keys = array(
			'_author' => 'post_meta_author',
			'_category' => 'post_meta_category',
			'_comment' => 'post_meta_comment',
			'_tag' => 'post_meta_tag'
		    );

		    $post_meta_key = 'setting-default_';
		    $this->hide_meta = themify_get('hide_meta_all',$this->hide_meta);

		    foreach ($post_meta_keys as $k => $v) {
			$this->{'hide_meta' . $k} = themify_get_both('hide_meta' . $k, $post_meta_key . $v, false);
		    }

		    if ($this->orderby === 'meta_value' || $this->orderby === 'meta_value_num') {
			$this->order_meta_key = themify_get('meta_key', $this->order_meta_key);
		    }
		} 
	    }
	} 
	elseif (is_search()) {
	    $this->set_search();
	} 
	elseif (is_single() && !is_singular('product')) {

	    $this->display_content = '';
	    $this->layout = themify_get_both('layout', 'setting-default_page_post_layout', 'sidebar1');
	    $this->hide_title = themify_get_both('hide_post_title', 'setting-default_page_post_title', '');
	    $this->unlink_title = themify_get_both('unlink_post_title', 'setting-default_page_unlink_post_title', '');
	    $this->hide_date = themify_get_both('hide_post_date', 'setting-default_page_post_date', '');
	    $this->hide_image = themify_get_both('hide_post_image', 'setting-default_page_post_image', '');
	    $this->unlink_image = themify_get_both('unlink_post_image', 'setting-default_page_unlink_post_image', '');
	    $this->media_position = 'auto_tiles'===$this->post_layout || in_array($this->post_layout_type,array('polaroid','flip'))?'above':themify_get('setting-default_page_single_media_position', 'above', true);
	    $this->width = themify_get_both('image_width', 'setting-image_post_single_width', '');
	    $this->height = themify_get_both('image_height', 'setting-image_post_single_height', '');

	    // Post Meta Values ///////////////////////
	    $post_meta_keys = array(
		'_author' => 'post_meta_author',
		'_category' => 'post_meta_category',
		'_comment' => 'post_meta_comment',
		'_tag' => 'post_meta_tag'
	    );

	    $post_meta_key = 'setting-default_page_';
	    $this->hide_meta = themify_get_both('hide_meta_all', $post_meta_key . 'post_meta', false);
	    foreach ($post_meta_keys as $k => $v) {
		$this->{'hide_meta' . $k} = themify_get_both('hide_meta' . $k, $post_meta_key . $v, false);
	    }

	    // for custom post tpye
	    $excluded_types = apply_filters('themify_exclude_CPT_for_sidebar', array('post', 'page', 'attachment', 'tbuilder_layout', 'tbuilder_layout_part', 'section'));
	    $postType = get_post_type();

	    if (!in_array($postType, $excluded_types, true)) {
		$layout = 'custom_post_' . $postType . '_single';
		$this->layout = themify_get($layout, $this->layout);
	    }

		if ( is_attachment() ) {
			$this->hide_image = 'yes';
		}

	} elseif (is_archive()) {
	    $excluded_types = apply_filters('themify_exclude_CPT_for_sidebar', array('post', 'page', 'attachment', 'tbuilder_layout', 'tbuilder_layout_part', 'section'));
	    $postType = get_post_type();
	    if (!in_array($postType, $excluded_types, true)) {
		$this->layout = themify_get('setting-custom_post_' . $postType . '_archive', $this->layout, true);
	    }
	}

	// Set Sticky Sidebar Value
	$this->sticky_sidebar = $this->themify_get_sticky_slider_value();
	if (in_array( $this->post_layout, array('list-large-image', 'list-thumb-image','grid2-thumb','auto_tiles'),true)) {
	    $this->post_layout_type = '';
	}

	if ($this->width === '' && $this->height === '') {
	    if (is_single()) {
		$this->width = self::SINGLE_IMAGE_WIDTH;
		$this->height = self::SINGLE_IMAGE_HEIGHT;
	    } else {
		switch ($this->post_layout) { 
			case 'grid6':
			$this->width = self::GRID6_WIDTH;
			$this->height = self::GRID6_HEIGHT;
			break;  case 'grid5':
			$this->width = self::GRID5_WIDTH;
			$this->height = self::GRID5_HEIGHT;
			break;
		    case 'grid4':
			$this->width = self::GRID4_WIDTH;
			$this->height = self::GRID4_HEIGHT;
			break;
		    case 'grid3':
			$this->width = self::GRID3_WIDTH;
			$this->height = self::GRID3_HEIGHT;
			break;
		    case 'grid2':
			$this->width = self::GRID2_WIDTH;
			$this->height = self::GRID2_HEIGHT;
			break;
		    case 'list-large-image':
			$this->width = self::LIST_LARGE_IMAGE_WIDTH;
			$this->height = self::LIST_LARGE_IMAGE_HEIGHT;
			break;
		    case 'list-thumb-image':
			$this->width = self::LIST_THUMB_IMAGE_WIDTH;
			$this->height = self::LIST_THUMB_IMAGE_HEIGHT;
			break;
		    case 'grid2-thumb':
			$this->width = self::GRID2_THUMB_WIDTH;
			$this->height = self::GRID2_THUMB_HEIGHT;
			break;
		    default :
			$this->width = self::LIST_POST_WIDTH;
			$this->height = self::LIST_POST_HEIGHT;
			break;
		}
	    }
	}
    }

    /**
     * Check whether sticky sidebar is enabled.
     *
     * @return bool
     */
    private function themify_get_sticky_slider_value() {
	$postType = get_post_type();
	$excluded_types = apply_filters('themify_exclude_CPT_for_sidebar', array('post', 'page', 'attachment', 'tbuilder_layout', 'tbuilder_layout_part', 'section'));
	$option = null;
	if (themify_is_shop()) {
	    $option = 'setting-shop_sticky_sidebar';
	} 
	elseif (is_page()) {
	    $option = 'setting-default_page_sticky_sidebar';
	} 
	elseif (is_singular('post')) {
	    $option = 'setting-default_page_post_sticky_sidebar';
	} 
	elseif (themify_is_woocommerce_active() && ( is_product_category() || is_product_tag() || is_singular('product'))) {
	    $option = is_singular('product')?'setting-single_product_sticky_sidebar':'setting-shop_archive_sticky_sidebar';
	} 
	elseif (!in_array($postType, $excluded_types)) {
	    if (is_archive($postType)) {
		$option = 'setting-custom_post_' . $postType . '_archive_post_sticky_sidebar';
	    } elseif (is_singular($postType)) {
		$option = 'setting-custom_post_' . $postType . '_single_post_sticky_sidebar';
	    }
	}
	elseif (is_archive() || is_home()) {
	    $option = 'setting-default_sticky_sidebar';
	} 
	elseif (is_search()) {
	    $option = 'setting-search-result_sticky_sidebar';
	} 
	if ($option !== null) {
	    $value = is_singular() || themify_is_shop()? themify_check('post_sticky_sidebar') : false;
	    if ($value === false) {
		$value = themify_check($option, true);
	    }
	} else {
	    $value = false;
	}
	return $value;
    }

}

global $themify;
$themify = new Themify();
