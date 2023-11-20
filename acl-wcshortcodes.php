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


	function acl_add_to_cart( $atts ) {
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
				'style'      => 'border:4px dotted #ccc; padding: 12px;',
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
			echo 'here';
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


	function acl_add_to_cart_text() {
		return apply_filters( 'woocommerce_product_add_to_cart_text', $this->is_purchasable() ? __( 'Select options', 'woocommerce' ) : __( 'Read more', 'woocommerce' ), $this );
	}



	if ( ! function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {

		/**
		 * Get the add to cart template for the loop.
		 *
		 * @param array $args Arguments.
		 */
		function woocommerce_template_loop_add_to_cart( $args = array() ) {
			global $product;
	
			if ( $product ) {
				$defaults = array(
					'quantity'   => 1,
					'class'      => implode(
						' ',
						array_filter(
							array(
								'button',
								wc_wp_theme_get_element_class_name( 'button' ), // escaped in the template.
								'product_type_' . $product->get_type(),
								$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
								$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
							)
						)
					),
					'attributes' => array(
						'data-product_id'  => $product->get_id(),
						'data-product_sku' => $product->get_sku(),
						'aria-label'       => $product->add_to_cart_description(),
						'aria-describedby' => $product->add_to_cart_aria_describedby(),
						'rel'              => 'nofollow',
					),
				);
	
				$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );
	
				if ( ! empty( $args['attributes']['aria-describedby'] ) ) {
					$args['attributes']['aria-describedby'] = wp_strip_all_tags( $args['attributes']['aria-describedby'] );
				}
	
				if ( isset( $args['attributes']['aria-label'] ) ) {
					$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
				}
	
				wc_get_template( 'loop/add-to-cart.php', $args );
			}
		}
	}	

	function acl_test_remove() {
		remove_shortcode ( 'add_to_cart' );
		add_shortcode ( 'add_to_cart', 'acl_add_to_cart' );
	}
	add_action('wp_loaded', 'acl_test_remove');