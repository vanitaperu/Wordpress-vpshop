<?php
/***************************************************************************
 *
 * 	----------------------------------------------------------------------
 * 						DO NOT EDIT THIS FILE
 *	----------------------------------------------------------------------
 * 
 *  				     Copyright (C) Themify
 * 
 *	----------------------------------------------------------------------
 *
 * 
 * Layout Hooks:
 * 
 * 		themify_body_start
 * 
 * 		themify_header_before
 * 		themify_header_start
 * 		themify_header_end
 * 		themify_header_after
 * 
 * 		themify_layout_before
 * 
 * 		themify_content_before 
 * 		themify_content_start
 * 
 * 		themify_post_before
 * 		themify_post_start
 *		themify_post_end
 * 		themify_post_after
 * 
 * 		themify_comment_before
 * 		themify_comment_start
 * 		themify_comment_end
 * 		themify_comment_after
 * 
 *		themify_content_end
 * 		themify_content_after
 * 
 * 		themify_sidebar_before
 * 		themify_sidebar_start
 * 		themify_sidebar_end
 * 		themify_sidebar_after
 * 
 * 		themify_layout_after
 * 
 * 		themify_footer_before
 * 		themify_footer_start
 * 		themify_footer_end
 *		themify_footer_after
 * 
 *		themify_body_end
 * 
 * Theme Feature Hooks:
 * 
 * 		welcome_before
 * 		welcome_start
 * 		welcome_end
 * 		welcome_after
 * 
 * 		slider_before
 * 		slider_start
 * 		slider_end
 *		slider_after
 * 
 * 		footer_slider_before
 * 		footer_slider_start
 * 		footer_slider_end
 * 		footer_slider_after
 * 		
 * 		themify_product_slider_add_to_cart_before
 * 		themify_product_slider_add_to_cart_after
 * 		
 * 		
 * 		
 * 		
 * 
***************************************************************************/

defined( 'ABSPATH' ) || exit;

/**
 * Layout Hooks
 */

function themify_body_start() {
	do_action( 'themify_body_start' );

	/* WP 5.2+ */
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
}

function themify_header_before() { 		do_action( 'themify_header_before' 	);}
function themify_header_start() { 		do_action( 'themify_header_start' 	);}
function themify_header_end() { 		do_action( 'themify_header_end' 	);}
function themify_header_after(){ 		do_action( 'themify_header_after'	);}

function themify_layout_before() { 		do_action( 'themify_layout_before' 	);}

function themify_content_before (){		do_action( 'themify_content_before' );}
function themify_content_start(){ 		do_action( 'themify_content_start' 	);}

function themify_post_before() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_post_before' . $posfix );
}
function themify_post_start() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_post_start' . $posfix );
}
function themify_before_post_image() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_before_post_image' . $posfix );
}
function themify_after_post_image() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_after_post_image' . $posfix );
}
function themify_before_post_title() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_before_post_title' . $posfix );
}
function themify_after_post_title() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_after_post_title' . $posfix );
}
function themify_before_post_content(){
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_before_post_content' . $posfix );
}
function themify_after_post_content() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_after_post_content' . $posfix );
}
function themify_post_end() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_post_end' . $posfix );
}
function themify_post_after() {
	$posfix = ! empty( $GLOBALS['themify']->post_module_hook ) ? '_module' : '';
	do_action( 'themify_post_after' . $posfix	);
}

function themify_comment_before() { 	do_action( 'themify_comment_before' );}
function themify_comment_start() { 		do_action( 'themify_comment_start' 	);}
function themify_comment_end() { 		do_action( 'themify_comment_end' 	);}
function themify_comment_after() { 		do_action( 'themify_comment_after' 	);}

function themify_content_end() { 		do_action( 'themify_content_end' 	);}
function themify_content_after() { 		do_action( 'themify_content_after' 	);}

function themify_sidebar_before(){ 		do_action( 'themify_sidebar_before' );}
function themify_sidebar_start (){		do_action( 'themify_sidebar_start' 	);}
function themify_sidebar_end(){ 		do_action( 'themify_sidebar_end' 	);}
function themify_sidebar_after(){ 		do_action( 'themify_sidebar_after' 	);}

function themify_layout_after() { 		do_action( 'themify_layout_after' 	);}

function themify_footer_before() { 		do_action( 'themify_footer_before' 	);}
function themify_footer_start() { 		do_action( 'themify_footer_start' 	);}
function themify_footer_end() { 		do_action( 'themify_footer_end' 	);}
function themify_footer_after() { 		do_action( 'themify_footer_after' 	);}

function themify_body_end() { 			do_action( 'themify_body_end' 		);}


/**
 * Theme Features Hooks
 */

function themify_welcome_before(){		do_action( 'themify_welcome_before' );}
function themify_welcome_start(){		do_action( 'themify_welcome_start' 	);}
function themify_welcome_end(){			do_action( 'themify_welcome_end' 	);}
function themify_welcome_after(){		do_action( 'themify_welcome_after' 	);}

function themify_slider_before(){		do_action( 'themify_slider_before' 	);}
function themify_slider_start(){		do_action( 'themify_slider_start' 	);}
function themify_slider_end(){			do_action( 'themify_slider_end'		);}
function themify_slider_after(){		do_action( 'themify_slider_after' 	);}

function themify_footer_slider_before(){do_action( 'themify_footer_slider_before' );}
function themify_footer_slider_start(){ do_action( 'themify_footer_slider_start'  );}
function themify_footer_slider_end(){ 	do_action( 'themify_footer_slider_end' 	  );}
function themify_footer_slider_after(){ do_action( 'themify_footer_slider_after'  );}

function themify_sidebar_alt_before(){ 	do_action( 'themify_sidebar_alt_before'	);}
function themify_sidebar_alt_start(){ 	do_action( 'themify_sidebar_alt_start'	);}
function themify_sidebar_alt_end(){ 	do_action( 'themify_sidebar_alt_end'	);}
function themify_sidebar_alt_after(){ 	do_action( 'themify_sidebar_alt_after'	);}

function themify_product_slider_add_to_cart_before(){ do_action('themify_product_slider_add_to_cart_before'); }
function themify_product_slider_add_to_cart_after(){  do_action('themify_product_slider_add_to_cart_after');  }
function themify_product_slider_image_start(){ 	do_action('themify_product_slider_image_start'); }
function themify_product_slider_image_end(){ 	do_action('themify_product_slider_image_end'); }
function themify_product_slider_title_start(){ 	do_action('themify_product_slider_title_start'); }
function themify_product_slider_title_end(){ 	do_action('themify_product_slider_title_end'); }
function themify_product_slider_price_start(){ 	do_action('themify_product_slider_price_start'); }
function themify_product_slider_price_end(){ 	do_action('themify_product_slider_price_end'); }

function themify_product_cart_image_start(){	do_action('themify_product_cart_image_start'); }
function themify_product_cart_image_end(){ 		do_action('themify_product_cart_image_end'); }

function themify_shopdock_before(){ do_action('themify_shopdock_before'); }
function themify_shopdock_start(){ 	do_action('themify_shopdock_start'); }
function themify_shopdock_end(){ 	do_action('themify_shopdock_end'); }
function themify_shopdock_after(){ 	do_action('themify_shopdock_after'); }

function themify_sorting_before(){ 	do_action('themify_sorting_before'); }
function themify_sorting_after(){ 	do_action('themify_sorting_after'); }
function themify_related_products_start(){ 	do_action('themify_related_products_start'); }
function themify_related_products_end(){ 	do_action('themify_related_products_end'); }

function themify_breadcrumb_before(){ 	do_action('themify_breadcrumb_before'); }
function themify_breadcrumb_after(){ 	do_action('themify_breadcrumb_after'); }

function themify_mobile_menu_start() { do_action( 'themify_mobile_menu_start' ); }
function themify_mobile_menu_end() { do_action( 'themify_mobile_menu_end' ); }
function themify_search_fields() { do_action( 'themify_search_fields' ); }


/**
 * Substitute hooks for WooCommerce
 *
 * Add support for various WC-related hooks added by the framework
 */
if ( themify_is_woocommerce_active() ) {
	add_filter( 'woocommerce_product_get_image', 'themify_product_image_hooks' );
    add_action( 'woocommerce_before_single_product_summary', 'themify_product_single_image_start_hook', 1 );
    add_action( 'woocommerce_before_single_product_summary', 'themify_product_single_image_end_hook', 99 );
    add_filter( 'woocommerce_get_price_html', 'themify_product_price_hooks' );
	add_action( 'woocommerce_checkout_billing', 'themify_checkout_start_hook', 1 );
	add_action( 'woocommerce_checkout_billing', 'themify_checkout_end_hook', 100 );
	add_action( 'themify_sidebar_before', 'themify_ecommerce_sidebar_before_hook' );
	add_action( 'themify_sidebar_after', 'themify_ecommerce_sidebar_after_hook' );
	add_action( 'woocommerce_shop_loop_item_title', 'themify_before_product_title_hook', 1 );
	add_action( 'woocommerce_shop_loop_item_title', 'themify_after_product_title_hook', 100 );
	add_action( 'woocommerce_before_template_part', 'themify_product_title_start_hook' );
	add_action( 'woocommerce_after_template_part', 'themify_product_title_end_hook' );
}

function themify_product_title_start_hook( $template_name ) {
	if ( $template_name === 'single-product/title.php' ) {
		do_action( 'themify_product_title_start' );
	} else if ( $template_name === 'single-product/tabs/tabs.php' ) {
		do_action( 'themify_before_product_tabs' );
	}
}
function themify_product_title_end_hook( $template_name ) {
	if ( $template_name === 'single-product/title.php' ) {
		do_action( 'themify_product_title_end' );
	} else if ( $template_name === 'single-product/tabs/tabs.php' ) {
		do_action( 'themify_after_product_tabs' );
	}
}
function themify_before_product_title_hook() {
	if ( ! is_singular( 'product' ) ) {
		do_action( 'themify_product_title_start' );
	}
}
function themify_after_product_title_hook() {
	if ( ! is_singular( 'product' ) ) {
		do_action( 'themify_product_title_end' );
	}
}

function themify_product_image_hooks( $image ) {
	ob_start();
	do_action( 'themify_product_image_start' );
	echo $image;
	do_action( 'themify_product_image_end' );
	return ob_get_clean();
}

function themify_product_price_hooks( $price ) {
	ob_start();
	do_action( 'themify_product_price_start' );
	echo $price;
	do_action( 'themify_product_price_end' );
	return ob_get_clean();
}

function themify_product_single_image_start_hook() {
    do_action( 'themify_product_image_start' );
}

function themify_product_single_image_end_hook() {
    do_action( 'themify_product_image_end' );
}

function themify_checkout_start_hook() {
	do_action( 'themify_checkout_start' );
}

function themify_checkout_end_hook() {
	do_action( 'themify_checkout_end' );
}

function themify_ecommerce_sidebar_before_hook() {
	if ( is_woocommerce() ) {
		do_action( 'themify_ecommerce_sidebar_before' );
	}
}

function themify_ecommerce_sidebar_after_hook() {
	if ( is_woocommerce() ) {
		do_action( 'themify_ecommerce_sidebar_after' );
	}
}

/**
 * Deprecated hook functions
 *
 * These are managed by the Substitute hooks defined above, so the
 * function hooks are "silenced" (nullified) to prevent double call
 * of the same hook, should the theme include them.
 *
 * @deprecated since 3.5.9
 */
function themify_product_image_start() {}
function themify_product_image_end() {}
function themify_product_title_start() {}
function themify_product_title_end() {}
function themify_product_price_start() {}
function themify_product_price_end() {}
function themify_product_single_price_before() {}
function themify_product_single_price_end() {}
function themify_product_single_image_before() {}
function themify_product_single_image_end() {}
function themify_product_single_title_before() {}
function themify_product_single_title_end() {}
function themify_checkout_start() {}
function themify_checkout_end() {}
function themify_ecommerce_sidebar_before() {}
function themify_ecommerce_sidebar_after() {}
