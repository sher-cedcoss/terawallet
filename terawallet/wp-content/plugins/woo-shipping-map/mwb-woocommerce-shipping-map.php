<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Mwb_Woocommerce_Shipping_Map
 *
 * @wordpress-plugin
 * Plugin Name:       MWB WooCommerce Shipping Map
 * Plugin URI:        https://makewebbetter.com/product/mwb-woocommerce-shipping-map/
 * Description:       Plugin creates a map between store and customer shipping address.
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       mwb-woocommerce-shipping-map
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to: 	  4.9.5
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
$active_plugins = get_option('active_plugins',false);
if(in_array('woocommerce/woocommerce.php',$active_plugins))
{
// Define plugin constants.
function define_mwb_woocommerce_shipping_map_constants() {

	mwb_woocommerce_shipping_map_constants( 'MWB_WOOCOMMERCE_SHIPPING_MAP_VERSION', '1.0.0' );
	mwb_woocommerce_shipping_map_constants( 'MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_PATH', plugin_dir_path( __FILE__ ) );
	mwb_woocommerce_shipping_map_constants( 'MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_URL', plugin_dir_url( __FILE__ ) );

	// For License Validation.
	mwb_woocommerce_shipping_map_constants( 'MWB_WOOCOMMERCE_SHIPPING_MAP_SPECIAL_SECRET_KEY', '59f32ad2f20102.74284991' );
	mwb_woocommerce_shipping_map_constants( 'MWB_WOOCOMMERCE_SHIPPING_MAP_SERVER_URL', 'https://makewebbetter.com' );
	mwb_woocommerce_shipping_map_constants( 'MWB_WOOCOMMERCE_SHIPPING_MAP_ITEM_REFERENCE', 'MWB WooCommerce Shipping Map' );
}

// Callable function for defining plugin constants.
function mwb_woocommerce_shipping_map_constants( $key, $value ) {

	if( ! defined( $key ) ) {
		
		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mwb-woocommerce-shipping-map-activator.php
 */
function activate_mwb_woocommerce_shipping_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-shipping-map-activator.php';
	Mwb_Woocommerce_Shipping_Map_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mwb-woocommerce-shipping-map-deactivator.php
 */
function deactivate_mwb_woocommerce_shipping_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-shipping-map-deactivator.php';
	Mwb_Woocommerce_Shipping_Map_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mwb_woocommerce_shipping_map' );
register_deactivation_hook( __FILE__, 'deactivate_mwb_woocommerce_shipping_map' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-shipping-map.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mwb_woocommerce_shipping_map() {

	define_mwb_woocommerce_shipping_map_constants();
	mwb_woocommerce_shipping_map_auto_update();

	$plugin = new Mwb_Woocommerce_Shipping_Map();
	$plugin->run();

}
run_mwb_woocommerce_shipping_map();

// Add settings link on plugin page.
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'mwb_woocommerce_shipping_map_settings_link' );

// Settings link.
function mwb_woocommerce_shipping_map_settings_link( $links ) {

    $my_link = array(
    		'<a href="' . admin_url( 'admin.php?page=mwb_woocommerce_shipping_map_menu' ) . '">' . __( 'Settings', 'mwb-woocommerce-shipping-map' ) . '</a>',
    	);
    return array_merge( $my_link, $links );
}
}
else{
	add_action('admin_init','mwb_wsm_plugin_deactivate');
	function mwb_wsm_plugin_deactivate()
 	{
 		deactivate_plugins( plugin_basename( __FILE__ ) );
 		add_action( 'admin_notices', 'mwb_wsm_plugin_error_notices' );
 	}

 	function mwb_wsm_plugin_error_notices(){
 		?>
 		<div class="notice notice-error is-dismissible">
 		<p><?php _e('WooCommerce is not activated, Please activate Woocommerce first to install MWB WooCommerce Shipping Map','mwb-woocommerce-shipping-map'); ?></p>
 		</div>
 		<style>
 			#message{display:none;}
 		</style>
 		<?php
 	}
}

// Plugin Auto Update.
function mwb_woocommerce_shipping_map_auto_update() {

	$license_key = get_option( 'mwb_woocommerce_shipping_map_lcns_key', '' );
	define( 'MWB_WOOCOMMERCE_SHIPPING_MAP_LICENSE_KEY', $license_key );
	define( 'MWB_WOOCOMMERCE_SHIPPING_MAP_BASE_FILE', __FILE__ );
	$update_check = "https://makewebbetter.com/pluginupdates/mwb-woocommerce-shipping-map/update.php";
	require_once( 'mwb-woocommerce-shipping-map-update.php' );
}




