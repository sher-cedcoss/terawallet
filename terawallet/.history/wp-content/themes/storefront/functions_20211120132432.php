<?php

/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme('storefront');
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if (class_exists('Jetpack')) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if (storefront_is_woocommerce_activated()) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if (is_admin()) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if (version_compare(get_bloginfo('version'), '4.7.3', '>=') && (is_admin() || is_customize_preview())) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */




// add_action('woocommerce_thankyou', 'sher_cash_back_fb', 10, 1);
function sher_cash_back_fb($order_id)
{
	if (isset($order_id))
		$order = wc_get_order($order_id);

	$orderstat = $order->get_status();

	$user_id = get_current_user_id();
	$customer_orders = get_posts(array(
		'meta_key'    => '_customer_user',
		'meta_value'  => $user_id,
		'post_type'   => 'shop_order',
		'post_status' => 'wc-processing',
		'numberposts' => -1
	));

	$count = 0;
	if (!empty($customer_orders))
		foreach ($customer_orders as $customer_order) {
			$count = $count + 1;
		}



	$no_refresh_effect = get_post_meta($order_id, "order_key", true);
	if ($count == 1) {
		$sub_ttl = $order->get_subtotal();
		$sub_ttl = ($sub_ttl * 10) / 100;
		if ($no_refresh_effect != "true") {
			woo_wallet()->wallet->credit($user_id, $sub_ttl, sanitize_textarea_field("Sher cash back"));
			if ($orderstat == "processing") {
				update_post_meta($order_id, "order_key", "true");
			}
		}
	}
}
