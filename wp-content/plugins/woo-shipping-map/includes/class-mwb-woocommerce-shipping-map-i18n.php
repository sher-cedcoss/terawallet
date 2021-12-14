<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Woocommerce_Shipping_Map
 * @subpackage Mwb_Woocommerce_Shipping_Map/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mwb_Woocommerce_Shipping_Map
 * @subpackage Mwb_Woocommerce_Shipping_Map/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Woocommerce_Shipping_Map_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mwb-woocommerce-shipping-map',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
