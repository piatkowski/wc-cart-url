<?php

/**
 * Plugin Name: WC Cart URL
 * Description: Share cart with URL
 * Version: 1.0.0
 * Author: Krzysztof Piątkowski
 * Text Domain: wc-cart-share
 * Domain Path: /languages
 * License: GPLv2
 * Requires PHP: 5.6
 * Network: true
 */

namespace WC_Cart_Url;

if (!defined('ABSPATH')) {
    die("No direct access!");
}

if (!class_exists('WC_Cart_Url\Plugin')) {

	class Plugin
	{
		private static $session_cart_keys = array(
			'cart', 'cart_totals', 'applied_coupons', 'coupon_discount_totals', 'coupon_discount_tax_totals', 'removed_cart_contents'
		);

		public static function init()
		{
			add_action('woocommerce_before_cart', array(__CLASS__, 'before_cart'));
			add_action('woocommerce_load_cart_from_session', array(__CLASS__, 'set_session_cart'), 1);
		}

		public static function get_session_cart()
		{
			$cart_session = array();

			foreach(self::$session_cart_keys as $key) {
				$cart_session[$key] = WC()->session->get( $key );
			}

			return serialize($cart_session);
		}

		public static function set_session_cart()
		{
			if( isset($_REQUEST['share'])) {

				$hash = sanitize_file_name($_REQUEST['share']);
				$file = get_temp_dir() . $hash;

				if (file_exists($file)) {

					$serialized = unserialize( file_get_contents($file) );
					
					foreach(self::$session_cart_keys as $key) {
						WC()->session->set( $key, $serialized[$key] );
					}
				}
			}
		}

		public static function before_cart()
		{
			if (current_user_can('manage_woocommerce')) {

				if (isset($_POST['wc_cart_share'])) {

					$session_cart = self::get_session_cart();
					$hash = wp_hash( $session_cart );

					file_put_contents( get_temp_dir() . $hash, $session_cart);

					echo '<pre>Share URL:' . "\n" . wc_get_cart_url() . '?share=' . $hash . '</pre>';

				} else {
				?>
				<form method="post">
					<button type="submit" name="wc_cart_share"><?php _e('Share this cart', 'wc-cart-url'); ?></button>
				</form>
				<?php
				}
			}
		}
	}

	Plugin::init();

}