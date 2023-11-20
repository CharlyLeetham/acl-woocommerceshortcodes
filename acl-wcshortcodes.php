<?php
/*
Plugin Name: ACL New Woocommerce Shortcodes
Plugin URI: http://askcharlyleetham.com
Description: New Shortcodes for WooCommerce
Version: 1
Author: Charly Dwyer
Author URI: http://askcharlyleetham.com
License: GPL

Changelog
Version 1.0 - Original Version
*/

/*
	function product_add_to_cart( $atts ) {
		global $post;

		if ( empty( $atts ) ) {
			return '';
		}

		$atts = shortcode_atts(
			array(
				'id'         => '',
				'class'      => '',
				'quantity'   => '1',
				'sku'        => '',
				'style'      => 'border:4px solid #ccc; padding: 12px;',
				'show_price' => 'true',
			),
			$atts,
			'product_add_to_cart'
		);

		if ( ! empty( $atts['id'] ) ) {
			$product_data = get_post( $atts['id'] );
		} elseif ( ! empty( $atts['sku'] ) ) {
			$product_id   = wc_get_product_id_by_sku( $atts['sku'] );
			$product_data = get_post( $product_id );
		} else {
			return '';
		}

		$product = is_object( $product_data ) && in_array( $product_data->post_type, array( 'product', 'product_variation' ), true ) ? wc_setup_product_data( $product_data ) : false;

		if ( ! $product ) {
			return '';
		}

		ob_start();

		echo '<p class="product woocommerce add_to_cart_inline ' . esc_attr( $atts['class'] ) . '" style="' . ( empty( $atts['style'] ) ? '' : esc_attr( $atts['style'] ) ) . '">';

		if ( wc_string_to_bool( $atts['show_price'] ) ) {
			// @codingStandardsIgnoreStart
			echo $product->get_price_html();
			// @codingStandardsIgnoreEnd
		}

		woocommerce_template_loop_add_to_cart(
			array(
				'quantity' => $atts['quantity'],
			)
		);

		echo '</p>';

		// Restore Product global in case this is shown inside a product post.
		wc_setup_product_data( $post );

		return ob_get_clean();
	}
	*/

	remove_shortcode('product_add_to_cart');