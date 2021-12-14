<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Woocommerce_Shipping_Map
 * @subpackage Mwb_Woocommerce_Shipping_Map/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_Woocommerce_Shipping_Map
 * @subpackage Mwb_Woocommerce_Shipping_Map/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Woocommerce_Shipping_Map {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mwb_Woocommerce_Shipping_Map_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		if ( defined( 'MWB_WOOCOMMERCE_SHIPPING_MAP_VERSION' ) ) {

			$this->version = MWB_WOOCOMMERCE_SHIPPING_MAP_VERSION;
		} 
		
		else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'mwb-woocommerce-shipping-map';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_Woocommerce_Shipping_Map_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_Woocommerce_Shipping_Map_i18n. Defines internationalization functionality.
	 * - Mwb_Woocommerce_Shipping_Map_Admin. Defines all hooks for the admin area.
	 * - Mwb_Woocommerce_Shipping_Map_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-woocommerce-shipping-map-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-woocommerce-shipping-map-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-woocommerce-shipping-map-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-woocommerce-shipping-map-public.php';

		$this->loader = new Mwb_Woocommerce_Shipping_Map_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_Woocommerce_Shipping_Map_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mwb_Woocommerce_Shipping_Map_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mwb_Woocommerce_Shipping_Map_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'settings_api' );
	
		// Add settings menu for MWB WooCommerce Shipping Map.

		// Running action for ajax license.

		$callname_lic = Mwb_Woocommerce_Shipping_Map::$lic_callback_function;
		$callname_lic_initial = Mwb_Woocommerce_Shipping_Map::$lic_ini_callback_function;
		$day_count = Mwb_Woocommerce_Shipping_Map::$callname_lic_initial();
		$enable = get_option( 'mwb_woocommerce_shipping_map_enable_plug', 'yes' );

		if( 'yes' === $enable ) {
		// Condition for validating.
			if( Mwb_Woocommerce_Shipping_Map::$callname_lic() || 0 <= $day_count ) {
			// All admin actions and filters after License Validation goes here.
				$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );	
				// Using Settings API for settings menu.
				$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'mwb_wsm_metabox');
				// Daily ajax license action.
				$this->loader->add_action( 'mwb_woocommerce_shipping_map_license_daily', $plugin_admin, 'validate_license_daily' );
				$this->loader->add_action( 'wp_ajax_mwb_woocommerce_shipping_map_license', $plugin_admin, 'validate_license_handle' );
				$this->loader->add_action( 'admin_init', $plugin_admin, 'mwb_wsm_store_latlong' );
			}
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mwb_Woocommerce_Shipping_Map_Public( $this->get_plugin_name(), $this->get_version() );

		$callname_lic = Mwb_Woocommerce_Shipping_Map::$lic_callback_function;

		$callname_lic_initial = Mwb_Woocommerce_Shipping_Map::$lic_ini_callback_function;

		$day_count = Mwb_Woocommerce_Shipping_Map::$callname_lic_initial();

		// Condition for validating.
		if( Mwb_Woocommerce_Shipping_Map::$callname_lic() || 0 <= $day_count ) {

			// All public actions and filters after License Validation goes here.

			// Check if plugin is enabled.
			$enable = get_option( 'mwb_woocommerce_shipping_map_enable_plug', 'yes' );

			if( 'yes' === $enable ) {

				$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
				
				$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			}
		}

	}

	// public static variable to be accessed in this plugin.
	public static $lic_callback_function = 'check_lcns_validity';

	// public static variable to be accessed in this plugin.
	public static $lic_ini_callback_function = 'check_lcns_initial_days';

	/**
	 * Validate the use of features of this plugin.
	 *
	 * @since    1.0.0
	 */
	public static function check_lcns_validity() {

		$mwb_woocommerce_shipping_map_lcns_key = get_option( 'mwb_woocommerce_shipping_map_lcns_key', '' );

		$mwb_woocommerce_shipping_map_lcns_status = get_option( 'mwb_woocommerce_shipping_map_lcns_status', '' );

		if( $mwb_woocommerce_shipping_map_lcns_key && 'true' === $mwb_woocommerce_shipping_map_lcns_status ) {
			
			return true;
		}

		else {

			return false;
		}
	}

	/**
	 * Validate the use of features of this plugin for initial days.
	 *
	 * @since    1.0.0
	 */
	public static function check_lcns_initial_days() {

		$thirty_days = get_option( 'mwb_woocommerce_shipping_map_lcns_thirty_days', 0 );

		$current_time = current_time( 'timestamp' );

		$day_count = ( $thirty_days - $current_time ) / (24 * 60 * 60);

		return $day_count;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mwb_Woocommerce_Shipping_Map_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
