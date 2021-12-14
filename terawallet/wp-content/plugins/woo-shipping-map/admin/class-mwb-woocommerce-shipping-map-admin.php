<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_Woocommerce_Shipping_Map
 * @subpackage Mwb_Woocommerce_Shipping_Map/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Woocommerce_Shipping_Map
 * @subpackage Mwb_Woocommerce_Shipping_Map/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Woocommerce_Shipping_Map_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles( $hook ) {
		// Enqueue styles only on this plugin's menu page.
		if ( $hook != 'toplevel_page_mwb_woocommerce_shipping_map_menu' && $hook != 'post.php' ) {

			return;
		}

		wp_enqueue_style( $this->plugin_name, MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_URL . 'admin/css/mwb-woocommerce-shipping-map-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		if ( $hook != 'toplevel_page_mwb_woocommerce_shipping_map_menu' && $hook != 'post.php' ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name . 'admin-js', MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_URL . 'admin/js/mwb-woocommerce-shipping-map-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name . 'admin-js',
			'license_ajax_object',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'reloadurl' => admin_url( 'admin.php?page=mwb_woocommerce_shipping_map_menu' ),
				'license_nonce' => wp_create_nonce( 'mwb-woocommerce-shipping-map-license-nonce-action' ),
			)
		);

		wp_enqueue_script( 'plugin_map', 'https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyA_yQlkvr8aHYdYhh5EHPLUB-nF9bqHass', '', '', false );
	}

	/**
	 * Adding settings menu for MWB WooCommerce Shipping Map.
	 *
	 * @since 1.0.0
	 */
	public function add_options_page() {
		add_menu_page(
			__( 'MWB WooCommerce Shipping Map', 'mwb-woocommerce-shipping-map' ),
			__( 'MWB WooCommerce Shipping Map', 'mwb-woocommerce-shipping-map' ),
			'manage_options',
			'mwb_woocommerce_shipping_map_menu',
			array( $this, 'options_menu_html' ),
			'',
			85
		);
	}

	/**
	 * MWB WooCommerce Shipping Map admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function options_menu_html() {
		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {

			return;
		}

		$callname_lic = Mwb_Woocommerce_Shipping_Map::$lic_callback_function;

		$callname_lic_initial = Mwb_Woocommerce_Shipping_Map::$lic_ini_callback_function;

		$day_count = Mwb_Woocommerce_Shipping_Map::$callname_lic_initial();

		?>

<div class="mwb-woocommerce-shipping-map-wrap">

		<?php

		// Condition for Warning notification.
		if ( ! Mwb_Woocommerce_Shipping_Map::$callname_lic() && 0 <= $day_count ) :

			$day_count_warning = floor( $day_count );

			$day_string = sprintf( _n( '%s day', '%s days', $day_count_warning, 'mwb-woocommerce-shipping-map' ), number_format_i18n( $day_count_warning ) );

			$day_string = '<span id="mwb-woocommerce-shipping-map-day-count" >' . $day_string . '</span>';

			?>

	<div id="mwb-woocommerce-shipping-map-thirty-days-notify" class="notice notice-warning">
		<p>
			<strong><a
					href="?page=mwb_woocommerce_shipping_map_menu&tab=license"><?php _e( 'Activate', 'mwb-woocommerce-shipping-map' ); ?></a><?php printf( __( ' the license key before %s or you may risk losing data and the plugin will also become dysfunctional.', 'mwb-woocommerce-shipping-map' ), $day_string ); ?></strong>
		</p>
	</div>

			<?php

		endif;

		?>

	<h2><?php _e( 'MWB WooCommerce Shipping Map', 'mwb-woocommerce-shipping-map' ); ?></h2>

		<?php

		// Condition for validating.
		if ( Mwb_Woocommerce_Shipping_Map::$callname_lic() || 0 <= $day_count ) {

			$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

			// Redirect to default when tab value is not one of the valid ones.
			if ( $active_tab != 'general' && $active_tab != 'license' && $active_tab != 'about_us' && $active_tab != 'help' ) {

				   wp_redirect( admin_url( 'admin.php?page=mwb_woocommerce_shipping_map_menu' ) );
				   exit;
			}

			?>

	<h2 class="nav-tab-wrapper">

		<a href="?page=mwb_woocommerce_shipping_map_menu&tab=general"
			class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'mwb-woocommerce-shipping-map' ); ?></a>


		<a href="?page=mwb_woocommerce_shipping_map_menu&tab=help"
			class="nav-tab <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Help', 'mwb-woocommerce-shipping-map' ); ?></a>

		<a href="?page=mwb_woocommerce_shipping_map_menu&tab=about_us"
			class="nav-tab <?php echo $active_tab == 'about_us' ? 'nav-tab-active' : ''; ?>"><?php _e( 'About Us', 'mwb-woocommerce-shipping-map' ); ?></a>
			<?php if ( ! Mwb_Woocommerce_Shipping_Map::$callname_lic() ) : ?>

		<a href="?page=mwb_woocommerce_shipping_map_menu&tab=license"
			class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>"><?php _e( 'License Activation', 'mwb-woocommerce-shipping-map' ); ?></a>

			<?php endif; ?>

	</h2>

			<?php

			if ( $active_tab == 'general' ) {

				   // Menu HTML and PHP code for General Options goes here.

				   echo '<form action="options.php" method="post">';

				   settings_errors();

				   settings_fields( 'mwb_woocommerce_shipping_map_gen_menu' );

				   do_settings_sections( 'mwb_woocommerce_shipping_map_gen_menu' );

				   submit_button( __( 'Save Options', 'mwb-woocommerce-shipping-map' ) );

				   echo '</form>';

			}// endif General Options tab.

			elseif ( $active_tab == 'help' ) {

				// Menu HTML and PHP code for Help Section goes here.

				include_once MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_PATH . 'admin/partials/mwb-woocommerce-shipping-map-admin-help.php';

			}// endif Help Section tab.

			elseif ( $active_tab == 'about_us' ) {

				// Menu HTML and PHP code for COntact Us Section goes here.

				include_once MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_PATH . 'admin/partials/mwb-woocommerce-shipping-map-admin-about_us.php';

			}// endif Contact Us Section tab.

			elseif ( $active_tab == 'license' && ! Mwb_Woocommerce_Shipping_Map::$callname_lic() ) {

				// Menu HTML and PHP code for License Activation goes here.

				include_once MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_PATH . 'admin/partials/mwb-woocommerce-shipping-map-admin-license.php';

			}// endif License Activation tab.
		} else {

			include_once MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_PATH . 'admin/partials/mwb-woocommerce-shipping-map-admin-license.php';
		}

		?>

</div> <!-- mwb-woocommerce-shipping-map-wrap -->

		<?php
	}


	/**
	 *  Order Map .
	 */
	public function add_menu() {
		$this->page_id = add_submenu_page( 'woocommerce', __( 'Order Map', 'woocommerce-bebuzzd-digisparks' ), __( 'Order Map', 'woocommerce-bebuzzd-digisparks' ), 'manage_woocommerce', 'bebuzzd', array( $this, 'my_custom_submenu_page_callback' ) );
	}
	/**
	 * Callback functon .
	 */
	public function my_custom_submenu_page_callback() {
		$mwb_wsm_view_map_file_processing_order = plugin_dir_path( __FILE__ ) . 'partials/mwb-woocommerce-shipping-map-admin-show-all-process-order-map.php';
		if ( file_exists( $mwb_wsm_view_map_file_processing_order ) ) {
			global $woocommerce, $post ,$product_id;
			wp_enqueue_script( 'plugin_map', 'https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyA_yQlkvr8aHYdYhh5EHPLUB-nF9bqHass', '', '', false );
			wp_register_script( 'processing_order', plugin_dir_url( __FILE__ ) . 'js/mwb_wsm_all_processing_orders.js' );

			wp_enqueue_script( 'processing_order' );
			include_once $mwb_wsm_view_map_file_processing_order;
			$this->woocommerce_check_order_count( $product_id );
		}
	}
	/**
	 * Getting all processing orders ids .
	 */

	public function woocommerce_check_order_count( $product_id ) {
	$statuses = array( 'wc-processing' );

		// get all order.

		$allorders = wc_get_orders(
			array(
				'status' => array( 'wc-refunded', 'wc-ready-for-dispatc', 'wc-dispatched', 'wc-ready-for-collect', 'wc-picking', 'wc-processing', 'pending', 'on-hold', 'cancelled', 'failed' ),
				'type '  => 'shop_order',
				'limit' => -1,
				'return' => 'ids',
			)
		);
		$html = array();
		foreach ( $allorders as $key => $value ) {
			$order = new WC_Order( $value );
			$shipping_add1     = $order->get_address( 'shipping' )['address_1'];
			$shipping_add1_url = urldecode( $shipping_add1 );
			$shipping_add2     = $order->get_address( 'shipping' )['city'];
			$shipping_add2_url = urldecode( $shipping_add2 );
		
			$shipping_add41    = $order->get_address( 'shipping' )['country'];
			$shipping_add4     = WC()->countries->countries[ $shipping_add41 ];
			$shipping_add4_url = urldecode( $shipping_add4 );

			$shipping_add31    = $order->get_address( 'shipping' )['state'];

			$shipping_add3     = WC()->countries->get_states( $shipping_add41 )[ $shipping_add31 ];
			$shipping_add3_url = urldecode( $shipping_add3 );
			
			$order_status  = $order->get_status();

			$latitude = 'mwb_wsm_orders_latitude_' . $value;
			$longitude = 'mwb_wsm_orders_longitude_' . $value;
			$order_url = get_edit_post_link( $value );
			$lat = get_option( $latitude );
			$long = get_option( $longitude );

			if ( get_option( $latitude ) == '' && get_option( $longitude ) == '' ) {

				$mwb_wsm_store_process_order_map_api = "https://maps.googleapis.com/maps/api/geocode/json?address=$shipping_add1_url+$shipping_add2_url+$shipping_add3_url&key=AIzaSyA_yQlkvr8aHYdYhh5EHPLUB-nF9bqHass";
				$mwb_wsm_store_process_order_map_api_response = wp_remote_get( $mwb_wsm_store_process_order_map_api );

				$mwb_wsm_store_results = json_decode( $mwb_wsm_store_process_order_map_api_response['body'] );

				if ( ! empty( $mwb_wsm_store_results->results ) ) {
					$mwb_wsm_store_status = $mwb_wsm_store_results->status;
					$mwb_wsm_store_location_all_fields = $mwb_wsm_store_results->results;
					$mwb_wsm_store_location_all_fields = $mwb_wsm_store_location_all_fields[0];
					$mwb_wsm_store_location_geometry = $mwb_wsm_store_location_all_fields->geometry->location;
					if ( $mwb_wsm_store_status == 'OK' ) {
						$mwb_wsm_store_latitude  = $mwb_wsm_store_location_geometry->lat;
						$mwb_wsm_store_longitude = $mwb_wsm_store_location_geometry->lng;
						$html [ $value ]         = array( $shipping_add1, $shipping_add2, $mwb_wsm_store_latitude, $mwb_wsm_store_longitude, $shipping_add3, $shipping_add4, $value, $order_url, $order_status );
						 update_option( 'mwb_wsm_orders_latitude_' . $value, $mwb_wsm_store_latitude );
						 update_option( 'mwb_wsm_orders_longitude_' . $value, $mwb_wsm_store_longitude );
					} else {
						$mwb_wsm_store_latitude = '';
						$mwb_wsm_store_longitude = '';
						$html [ $value ] = array( $shipping_add1, $shipping_add2, $mwb_wsm_store_latitude, $mwb_wsm_store_longitude, $shipping_add3, $shipping_add4, $value, $order_url, $order_status );
					}
				}
			} else {
				$html [ $value ] = array( $shipping_add1, $shipping_add2, $lat, $long, $shipping_add3, $shipping_add4, $value, $order_url, $order_status );
			}
		}
		wp_localize_script( 'processing_order', 'object_name', $html );


	}

	/**
	 * Using Settings API for settings menu.
	 *
	 * @since 1.0.0
	 */
	public function settings_api() {
		register_setting( 'mwb_woocommerce_shipping_map_gen_menu', 'mwb_woocommerce_shipping_map_enable_plug' );

		add_settings_section(
			'mwb_woocommerce_shipping_map_gen_menu_sec',
			null,
			null,
			'mwb_woocommerce_shipping_map_gen_menu'
		);

		add_settings_field(
			'mwb_woocommerce_shipping_map_enable',
			__( 'Enable', 'mwb-woocommerce-shipping-map' ),
			array( $this, 'enable_plugin_cb' ),
			'mwb_woocommerce_shipping_map_gen_menu',
			'mwb_woocommerce_shipping_map_gen_menu_sec'
		);

	}

	/**
	 * Callback for Enable Plugin option.
	 *
	 * @since 1.0.0
	 */
	public function enable_plugin_cb() {
		?>

<div class="mwb-woocommerce-shipping-map-option-sec">

	<label for="mwb_woocommerce_shipping_map_enable_plug">
		<input type="checkbox" name="mwb_woocommerce_shipping_map_enable_plug" value="yes"
		<?php checked( 'yes', get_option( 'mwb_woocommerce_shipping_map_enable_plug', 'yes' ) ); ?>>
		<?php _e( 'Enable the plugin.', 'mwb-woocommerce-shipping-map' ); ?>
	</label>
	<p class="description">
		<?php _e( 'Enable the checkbox if you want this extension to work. ', 'mwb-woocommerce-shipping-map' ); ?></p>

</div>

		<?php
	}

	/**
	 * Validate license.
	 *
	 * @since 1.0.0
	 */
	public function validate_license_handle() {
		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'mwb-woocommerce-shipping-map-license-nonce-action', 'mwb-woocommerce-shipping-map-license-nonce' );

		$mwb_license_key = ! empty( $_POST['mwb_woocommerce_shipping_map_purchase_code'] ) ? sanitize_text_field( $_POST['mwb_woocommerce_shipping_map_purchase_code'] ) : '';

		// API query parameters
		$api_params = array(
			'slm_action' => 'slm_activate',
			'secret_key' => MWB_WOOCOMMERCE_SHIPPING_MAP_SPECIAL_SECRET_KEY,
			'license_key' => $mwb_license_key,
			'registered_domain' => $_SERVER['SERVER_NAME'],
			'item_reference' => urlencode( MWB_WOOCOMMERCE_SHIPPING_MAP_ITEM_REFERENCE ),
		);

		// Send query to the license manager server
		$query = esc_url_raw( add_query_arg( $api_params, MWB_WOOCOMMERCE_SHIPPING_MAP_SERVER_URL ) );

		$response = wp_remote_get(
			$query,
			array(
				'timeout' => 20,
				'sslverify' => false,
			)
		);

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( isset( $license_data->result ) && 'success' === $license_data->result ) {

			update_option( 'mwb_woocommerce_shipping_map_lcns_key', $mwb_license_key );
			update_option( 'mwb_woocommerce_shipping_map_lcns_status', 'true' );

			echo json_encode(
				array(
					'status' => true,
					'msg' => __(
						'Successfully Verified...',
						'mwb-woocommerce-shipping-map'
					),
				)
			);
		} else {

			$error_message = ! empty( $license_data->message ) ? $license_data->message : __( 'License Verification Failed.', 'mwb-woocommerce-shipping-map' );

			echo json_encode(
				array(
					'status' => false,
					'msg' => $error_message,
				)
			);
		}

		wp_die();
	}

	/**
	 * Validate License daily.
	 *
	 * @since 1.0.0
	 */
	public function validate_license_daily() {
		$mwb_license_key = get_option( 'mwb_woocommerce_shipping_map_lcns_key', '' );

		// API query parameters
		$api_params = array(
			'slm_action' => 'slm_check',
			'secret_key' => MWB_WOOCOMMERCE_SHIPPING_MAP_SPECIAL_SECRET_KEY,
			'license_key' => $mwb_license_key,
			'registered_domain' => $_SERVER['SERVER_NAME'],
			'item_reference' => urlencode( MWB_WOOCOMMERCE_SHIPPING_MAP_ITEM_REFERENCE ),
		);

		$query = esc_url_raw( add_query_arg( $api_params, MWB_WOOCOMMERCE_SHIPPING_MAP_SERVER_URL ) );

		$mwb_response = wp_remote_get(
			$query,
			array(
				'timeout' => 20,
				'sslverify' => false,
			)
		);

		$license_data = json_decode( wp_remote_retrieve_body( $mwb_response ) );

		if ( isset( $license_data->result ) && 'success' === $license_data->result && isset( $license_data->status ) && 'active' === $license_data->status ) {
			update_option( 'mwb_woocommerce_shipping_map_lcns_key', $mwb_license_key );
			update_option( 'mwb_woocommerce_shipping_map_lcns_status', 'true' );
		} else {
			delete_option( 'mwb_woocommerce_shipping_map_lcns_key' );
			update_option( 'mwb_woocommerce_shipping_map_lcns_status', 'false' );
		}

	}
	/**
	 * MWB WooCommerce Shipping Map admin metabox in Woocommerce order page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_wsm_metabox() {
		 $mwb_wsm_metabox_id = 'mwb_wsm_shipping_metabox';
		$mwb_wsm_metabox_title = 'Map';
		$mwb_wsm_metabox_function = 'mwb_wsm_map_content';
		$mwb_wsm_screen = 'shop_order';
		add_meta_box( $mwb_wsm_metabox_id, $mwb_wsm_metabox_title, array( $this, $mwb_wsm_metabox_function ), $mwb_wsm_screen );
	}

	public function mwb_wsm_store_latlong() {
		$mwb_wsm_store_address = get_option( 'woocommerce_store_address' );

		$mwb_wsm_store_address_url = urlencode( $mwb_wsm_store_address );

		$mwb_wsm_store_city = get_option( 'woocommerce_store_city' );

		$mwb_wsm_store_city_url = urlencode( $mwb_wsm_store_city );

		$mwb_wsm_store_array = wc_get_base_location();

		$mwb_wsm_store_country = $mwb_wsm_store_array['country'];

		$mwb_wsm_store_state = urlencode( $mwb_wsm_store_array['state'] );

		$mwb_wsm_store_map_api = "https://maps.googleapis.com/maps/api/geocode/json?address=$mwb_wsm_store_address_url+$mwb_wsm_store_city_url+$mwb_wsm_store_state&key=AIzaSyA_yQlkvr8aHYdYhh5EHPLUB-nF9bqHass";
		$mwb_wsm_google_api_store_response = wp_remote_get( $mwb_wsm_store_map_api );

		$mwb_wsm_store_results = json_decode( $mwb_wsm_google_api_store_response['body'] );

		if ( ! empty( $mwb_wsm_store_results->results ) ) {
			$mwb_wsm_store_status = $mwb_wsm_store_results->status;
			$mwb_wsm_store_location_all_fields = $mwb_wsm_store_results->results;
			$mwb_wsm_store_location_all_fields = $mwb_wsm_store_location_all_fields[0];
			$mwb_wsm_store_location_geometry = $mwb_wsm_store_location_all_fields->geometry->location;
			if ( $mwb_wsm_store_status == 'OK' ) {
				$mwb_wsm_store_latitude = $mwb_wsm_store_location_geometry->lat;
				$mwb_wsm_store_longitude = $mwb_wsm_store_location_geometry->lng;
				update_option( 'mwb_wsm_store_latitude', $mwb_wsm_store_latitude );
				update_option( 'mwb_wsm_store_longitude', $mwb_wsm_store_longitude );
			} else {
				$mwb_wsm_store_latitude = '';
				$mwb_wsm_store_longitude = '';
			}
		}
	}
	/**
	 * functionality of function mwb_wsm_map_content.
	 *
	 * @since 1.0.0
	 */
	public function mwb_wsm_map_content() {
		 $mwb_wsm_view_map_file = plugin_dir_path( __FILE__ ) . 'partials/mwb-woocommerce-shipping-map-admin-view-map.php';

		if ( file_exists( $mwb_wsm_view_map_file ) ) {
			global $woocommerce, $post;

			wp_register_script( 'some_handle', plugin_dir_url( __FILE__ ) . 'js/mwb_wsm_custom.js' );

			wp_enqueue_script( 'some_handle' );

			include_once $mwb_wsm_view_map_file;

			$mwb_wsm_order = new WC_Order( $post->ID );

			$mwb_wsm_order_id = $mwb_wsm_order->get_ID();

			$mwb_wsm_shipping_latitude = get_post_meta( $post->ID, 'mwb_wsm_shipping_latitude', true );

			$mwb_wsm_shipping_longitude = get_post_meta( $post->ID, 'mwb_wsm_shipping_longitude', true );

			$mwb_wsm_store_latitude = get_option( 'mwb_wsm_store_latitude' );

			$mwb_wsm_store_longitude = get_option( 'mwb_wsm_store_longitude' );

			$mwb_wsm_store_address = get_option( 'woocommerce_store_address' );

			$mwb_wsm_store_address_url = urlencode( $mwb_wsm_store_address );

			$mwb_wsm_store_city = get_option( 'woocommerce_store_city' );

			$mwb_wsm_store_city_url = urlencode( $mwb_wsm_store_city );

			$mwb_wsm_store_array = wc_get_base_location();

			$mwb_wsm_store_country = $mwb_wsm_store_array['country'];

			$mwb_wsm_store_state = urlencode( $mwb_wsm_store_array['state'] );

			$mwb_wsm_shipping_address = $mwb_wsm_order->get_address( 'shipping' );

			$mwb_wsm_shippin_address = urlencode( $mwb_wsm_shipping_address['address_1'] );

			$mwb_wsm_shipping_city = urlencode( $mwb_wsm_shipping_address['city'] );

			$mwb_wsm_shipping_state = urlencode( $mwb_wsm_shipping_address['state'] );

			$mwb_wsm_shipping_country = $mwb_wsm_shipping_address['country'];

			if ( $mwb_wsm_store_country == $mwb_wsm_shipping_country ) {
				$mwb_wsm_country_status = 'same';
			} else {
				$mwb_wsm_country_status = 'different';
			}

			if ( empty( $mwb_wsm_store_latitude ) && empty( $mwb_wsm_store_longitude ) ) {
				$mwb_wsm_store_map_api = "https://maps.googleapis.com/maps/api/geocode/json?address=$mwb_wsm_store_address_url+$mwb_wsm_store_city_url+$mwb_wsm_store_state&key=AIzaSyA_yQlkvr8aHYdYhh5EHPLUB-nF9bqHass";

				$mwb_wsm_google_api_store_response = wp_remote_get( $mwb_wsm_store_map_api );

				$mwb_wsm_store_results = json_decode( $mwb_wsm_google_api_store_response['body'] );

				if ( ! empty( $mwb_wsm_store_results->results ) ) {
					$mwb_wsm_store_status = $mwb_wsm_store_results->status;

					$mwb_wsm_store_location_all_fields = $mwb_wsm_store_results->results;

					$mwb_wsm_store_location_all_fields = $mwb_wsm_store_location_all_fields[0];

					$mwb_wsm_store_location_geometry = $mwb_wsm_store_location_all_fields->geometry->location;

					if ( $mwb_wsm_store_status == 'OK' ) {
						$mwb_wsm_store_latitude = $mwb_wsm_store_location_geometry->lat;

						$mwb_wsm_store_longitude = $mwb_wsm_store_location_geometry->lng;

						update_option( 'mwb_wsm_store_latitude', $mwb_wsm_store_latitude );

						update_option( 'mwb_wsm_store_longitude', $mwb_wsm_store_longitude );
					} else {
						$mwb_wsm_store_latitude = '';

						$mwb_wsm_store_longitude = '';
					}

					if ( empty( $mwb_wsm_shipping_latitude ) || empty( $mwb_wsm_shipping_longitude ) ) {
						$mwb_wsm_shipping_url = "https://maps.googleapis.com/maps/api/geocode/json?address=$mwb_wsm_shippin_address+$mwb_wsm_shipping_city+$mwb_wsm_shipping_state&key=AIzaSyA_yQlkvr8aHYdYhh5EHPLUB-nF9bqHass";

						$mwb_wsm_google_api_shipping_response = wp_remote_get( $mwb_wsm_shipping_url );

						$mwb_wsm_shipping_results = json_decode( $mwb_wsm_google_api_shipping_response['body'] );

						if ( ! empty( $mwb_wsm_shipping_results->results ) ) {
							$mwb_wsm_shipping_status = $mwb_wsm_shipping_results->status; // easily use our status

							$mwb_wsm_shipping_location_all_fields = $mwb_wsm_shipping_results->results;

							$mwb_wsm_shipping_location_all_fields = $mwb_wsm_shipping_location_all_fields[0];

							$mwb_wsm_shipping_location_geometry = $mwb_wsm_shipping_location_all_fields->geometry->location;

							if ( $mwb_wsm_shipping_status == 'OK' ) {
								$mwb_wsm_shipping_latitude = $mwb_wsm_shipping_location_geometry->lat;

								$mwb_wsm_shipping_longitude = $mwb_wsm_shipping_location_geometry->lng;

								update_post_meta( $mwb_wsm_order_id, 'mwb_wsm_shipping_latitude', $mwb_wsm_shipping_latitude );

								update_post_meta( $mwb_wsm_order_id, 'mwb_wsm_shipping_longitude', $mwb_wsm_shipping_longitude );
							} else {
								$mwb_wsm_shipping_latitude = '';

								$mwb_wsm_shipping_longitude = '';
							}

							$mwb_wsm_latitude_translation = array(

								'store_latitude' => __( $mwb_wsm_store_latitude, 'mwb-woocommerce-shipping-map' ),

								'store_longitude' => __( $mwb_wsm_store_longitude, 'mwb-woocommerce-shipping-map' ),

								'shipping_latitude' => __( $mwb_wsm_shipping_latitude, 'mwb-woocommerce-shipping-map' ),

								'shipping_longitude' => __( $mwb_wsm_shipping_longitude, 'mwb-woocommerce-shipping-map' ),

								'country_status' => __( $mwb_wsm_country_status, 'mwb-woocommerce-shipping-map' ),

								'store_address' => __( $mwb_wsm_store_address, 'mwb-woocommerce-shipping-map' ),

								'store_city' => __( $mwb_wsm_store_city, 'mwb-woocommerce-shipping-map' ),

								'store_country' => __( $mwb_wsm_store_country, 'mwb-woocommerce-shipping-map' ),

								'shipping_address' => __( $mwb_wsm_shipping_address['address_1'], 'mwb-woocommerce-shipping-map' ),

								'shipping_city' => __( $mwb_wsm_shipping_address['city'], 'mwb-woocommerce-shipping-map' ),

								'shipping_country' => __( $mwb_wsm_shipping_address['country'], 'mwb-woocommerce-shipping-map' ),
							);

							wp_localize_script( 'some_handle', 'object_name', $mwb_wsm_latitude_translation );
						}
					}
				}
			} elseif ( empty( $mwb_wsm_shipping_latitude ) || empty( $mwb_wsm_shipping_longitude ) ) {
				$mwb_wsm_shipping_url = "https://maps.googleapis.com/maps/api/geocode/json?address=$mwb_wsm_shippin_address+$mwb_wsm_shipping_city+$mwb_wsm_shipping_state&key=AIzaSyA_yQlkvr8aHYdYhh5EHPLUB-nF9bqHass";

				$mwb_wsm_google_api_shipping_response = wp_remote_get( $mwb_wsm_shipping_url );

				$mwb_wsm_shipping_results = json_decode( $mwb_wsm_google_api_shipping_response['body'] );

				if ( ! empty( $mwb_wsm_shipping_results->results ) ) {
					$mwb_wsm_shipping_status = $mwb_wsm_shipping_results->status; // easily use our status

					$mwb_wsm_shipping_location_all_fields = $mwb_wsm_shipping_results->results;

					$mwb_wsm_shipping_location_all_fields = $mwb_wsm_shipping_location_all_fields[0];

					$mwb_wsm_shipping_location_geometry = $mwb_wsm_shipping_location_all_fields->geometry->location;

					if ( $mwb_wsm_shipping_status == 'OK' ) {
						$mwb_wsm_shipping_latitude = $mwb_wsm_shipping_location_geometry->lat;

						$mwb_wsm_shipping_longitude = $mwb_wsm_shipping_location_geometry->lng;

						update_post_meta( $mwb_wsm_order_id, 'mwb_wsm_shipping_latitude', $mwb_wsm_shipping_latitude );

						update_post_meta( $mwb_wsm_order_id, 'mwb_wsm_shipping_longitude', $mwb_wsm_shipping_longitude );
					} else {
						$mwb_wsm_shipping_latitude = '';

						$mwb_wsm_shipping_longitude = '';
					}

					$mwb_wsm_latitude_translation = array(

						'store_latitude' => __( $mwb_wsm_store_latitude, 'mwb-woocommerce-shipping-map' ),

						'store_longitude' => __( $mwb_wsm_store_longitude, 'mwb-woocommerce-shipping-map' ),

						'shipping_latitude' => __( $mwb_wsm_shipping_latitude, 'mwb-woocommerce-shipping-map' ),

						'shipping_longitude' => __( $mwb_wsm_shipping_longitude, 'mwb-woocommerce-shipping-map' ),

						'country_status' => __( $mwb_wsm_country_status, 'mwb-woocommerce-shipping-map' ),

						'store_address' => __( $mwb_wsm_store_address, 'mwb-woocommerce-shipping-map' ),

						'store_city' => __( $mwb_wsm_store_city, 'mwb-woocommerce-shipping-map' ),

						'store_country' => __( $mwb_wsm_store_country, 'mwb-woocommerce-shipping-map' ),

						'shipping_address' => __( $mwb_wsm_shipping_address['address_1'], 'mwb-woocommerce-shipping-map' ),

						'shipping_city' => __( $mwb_wsm_shipping_address['city'], 'mwb-woocommerce-shipping-map' ),

						'shipping_country' => __( $mwb_wsm_shipping_address['country'], 'mwb-woocommerce-shipping-map' ),
					);

					wp_localize_script( 'some_handle', 'object_name', $mwb_wsm_latitude_translation );
				}
			} else {
				$mwb_wsm_latitude_translation = array(

					'store_latitude' => __( $mwb_wsm_store_latitude, 'mwb-woocommerce-shipping-map' ),

					'store_longitude' => __( $mwb_wsm_store_longitude, 'mwb-woocommerce-shipping-map' ),

					'shipping_latitude' => __( $mwb_wsm_shipping_latitude, 'mwb-woocommerce-shipping-map' ),

					'shipping_longitude' => __( $mwb_wsm_shipping_longitude, 'mwb-woocommerce-shipping-map' ),

					'country_status' => __( $mwb_wsm_country_status, 'mwb-woocommerce-shipping-map' ),

					'store_address' => __( $mwb_wsm_store_address, 'mwb-woocommerce-shipping-map' ),

					'store_city' => __( $mwb_wsm_store_city, 'mwb-woocommerce-shipping-map' ),

					'store_country' => __( $mwb_wsm_store_country, 'mwb-woocommerce-shipping-map' ),

					'shipping_address' => __( $mwb_wsm_shipping_address['address_1'], 'mwb-woocommerce-shipping-map' ),

					'shipping_city' => __( $mwb_wsm_shipping_address['city'], 'mwb-woocommerce-shipping-map' ),

					'shipping_country' => __( $mwb_wsm_shipping_address['country'], 'mwb-woocommerce-shipping-map' ),
				);

				wp_localize_script( 'some_handle', 'object_name', $mwb_wsm_latitude_translation );
			}
		}
	}
}



