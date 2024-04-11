<?php
/**
 * Template for cart
 * @package themify
 * @since 1.0.0
 */
?>

<div class="shopdock">

	<?php if ( themify_is_ajax() ) : ?>

		<div id="cart-wrap" class="tf_box">
			<?php // check whether cart is not empty
			if ( ! empty( WC()->cart->get_cart() ) ) {
				if(current_user_can( 'manage_woocommerce' ) && 'yes' !== get_option( 'woocommerce_enable_ajax_add_to_cart' )){
					echo sprintf('<div class="tf_admin_msg">%s <a href="%s">%s</a>.</div>',
                        /* translators: This string is cached in the browser, make sure to clear browser cache. Also in multilingual websites, WooCommerce Multilingual plugin is required for translated text to show. */
						__('WooCommerce Ajax add to cart option needs to be enabled to use this Ajax cart.','themify'),
						admin_url('admin.php?page=wc-settings&tab=products'),
                        /* translators: This string is cached in the browser, make sure to clear browser cache. Also in multilingual websites, WooCommerce Multilingual plugin is required for translated text to show. */
						__('Enable it on WooCommerce settings','themify')
					);
				}
				?>
				<div id="cart-list" class="tf_box tf_scrollbar">
					<?php get_template_part('includes/loop-product', 'cart'); ?>
				</div><!-- #cart-list -->

				<div class="cart-total-checkout-wrap">
					<p class="cart-total">
						<span class="amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
						<a id="view-cart" href="<?php echo esc_url( wc_get_cart_url() ) ?>">
							<?php
                                /* translators: This string is cached in the browser, make sure to clear browser cache. Also in multilingual websites, WooCommerce Multilingual plugin is required for translated text to show. */
                                _e('View Cart', 'themify')
                            ?>
						</a>
					</p>

					<?php themify_checkout_start(); //hook ?>

					<p class="checkout-button">
						<button type="submit" class="button checkout white flat" onClick="document.location.href = '<?php echo esc_url( wc_get_checkout_url() ); ?>'; return false;"><?php
                            /* translators: This string is cached in the browser, make sure to clear browser cache. Also in multilingual websites, WooCommerce Multilingual plugin is required for translated text to show. */
                            _e('Checkout', 'themify')
                        ?></button>
					</p><!-- .checkout-botton -->

					<?php themify_checkout_end(); //hook ?>
				</div>
			<?php
			} elseif ( themify_get_cart_style() !== 'link_to_cart' ) {
				echo '<div class="tf_textc empty-shopdock">';
				printf(
                    /* translators: This string is cached in the browser, make sure to clear browser cache. Also in multilingual websites, WooCommerce Multilingual plugin is required for translated text to show. */
                    __( 'Your cart is empty. Go to <a href="%s">Shop</a>.', 'themify' ),
                    themify_get_shop_permalink()
                );
				echo '</div>';
			} // cart whether is not empty ?>

		</div><!-- /#cart-wrap -->
	<?php endif;?>

	<?php themify_shopdock_end(); //hook ?>
</div><!-- .shopdock -->