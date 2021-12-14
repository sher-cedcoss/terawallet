<?php

/**
 * Exit if accessed directly
 */

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('MWB_Product_Customization')) {
	/**
	 * This is class for managing product layout and other functionalities .
	 *
	 * @name    MWB_WooCommerce_Customization
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */

	class MWB_Product_Customization
	{

		/**
		 * This is construct of class where all action and filter is defined
		 *
		 * @name __construct
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function __construct()
		{
			add_action('wp_head', array($this, 'mwb_remove_tabs_heading'));
			add_action('woocommerce_before_single_product_summary',  array($this, 'mwb_woocommerce_template_single_content'), 50);
			add_action('wp_enqueue_scripts', array($this, 'mwb_wp_enqueue_script'), 25);
			//* Enqueue scripts and styles
			add_action('wp_enqueue_scripts', array($this, 'crunchify_disable_woocommerce_loading_css_js'), 100);


			add_action('init', array($this, 'mwb_check_session'));

			add_action('woocommerce_checkout_shipping', array($this, 'mwb_woocommerce_checkout_instruction'), 20);

			add_filter('genesis_post_meta', array($this, 'filter_tags'), 25);
			add_filter('template_include', array($this, 'mwb_locate_template'), 30);
			add_filter('woocommerce_available_payment_gateways', array($this, 'mwb_woocommerce_available_payment_gateways'), 10, 1);
			add_filter('woocommerce_checkout_fields', array($this, 'mwb_woocommerce_checkout_fields'), 100, 1);
			add_filter('woocommerce_checkout_fields', array($this, 'mwb_order_checkout_fields'), 100, 1);
			add_filter('woocommerce_default_address_fields', array($this, 'mwb_override_default_address_fields'));
			add_filter('woocommerce_form_field_args', array($this, 'mwb_woocommerce_form_field_args'), 1000, 3);
			add_action('woocommerce_checkout_update_order_meta', array($this, 'mwb_custom_checkout_field_update_order_meta'));
			add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'mwb_edit_woocommerce_order_page'), 10, 1);
			add_action('wpo_wcpdf_after_order_data', array($this, 'mwb_gstin_invoice'), 10, 2);

			add_filter('business_page_header_open', array($this, 'mwb_remove_header_open'));
			add_filter('business_page_header_close', array($this, 'mwb_remove_header_close'));
			add_filter('genesis_after_header', array($this, 'mwb_remove_title'), 10);
			add_filter('woocommerce_product_query_tax_query', array($this, 'mwb_services_woocommerce_product_query_tax_query'), 10, 2);
			add_filter('single_product_archive_thumbnail_size', array($this, 'mwb_single_product_archive_thumbnail_size'));
			add_filter('genesis_footer_creds_text', array($this, 'mwb_footer_creds_filter'));

			add_shortcode('mwb_product_trail_buynow', array($this, 'mwb_product_trail_buynow'));

			add_shortcode('mwb_support_add_to_cart', array($this, 'mwb_support_add_to_cart'));

			add_shortcode('mwb_product_sale_flash', array($this, 'mwb_woocommerce_show_product_sale_flash'));
			add_shortcode('mwb_product_rating', array($this, 'mwb_woocommerce_template_single_rating'));
			add_shortcode('mwb_product_price',  array($this, 'mwb_woocommerce_template_single_price'));
			add_shortcode('mwb_product_add_to_cart',  array($this, 'mwb_woocommerce_template_single_add_to_cart'));
			add_shortcode('mwb_product_meta', array($this, 'mwb_woocommerce_template_single_meta'));
			add_shortcode('mwb_product_sharing', array($this, 'mwb_woocommerce_template_single_sharing'));
			add_shortcode('mwb_product_excerpt',  array($this, 'mwb_woocommerce_template_single_excerpt'));

			add_shortcode('mwb_product_documentation', array($this, 'mwb_woocommerce_show_product_documentation'));
			add_shortcode('mwb_product_compatibility', array($this, 'mwb_woocommerce_show_product_compatibility'));
			add_shortcode('mwb_product_review', array($this, 'mwb_woocommerce_template_review'));

			add_shortcode('wp_google_search', array($this, 'wp_google_search_shortcode'));
			add_shortcode('mwb_template_previews', array($this, 'mwb_template_previews'));
			add_shortcode('mwb_mautic_template_previews', array($this, 'mwb_mautic_template_previews'));
			add_shortcode('mwb_tags', array($this, 'mwb_tags'));
			add_shortcode(
				'mwb_related_product',
				array($this, 'mwb_related_product')
			);
			add_shortcode(
				'mwb_product_features',
				array($this, 'mwb_product_features')
			);
			add_shortcode(
				'mwb_product_average_rating_filter',
				array($this, 'mwb_product_average_rating_filter')
			);

			add_shortcode(
				'mwb_latest_release_product',
				array($this, 'mwb_latest_release_product')
			);

			add_action('woocommerce_thankyou_paypal', array($this, 'mwb_change_status_to_completed'), 10, 1);
			add_action('woocommerce_thankyou_payuindia', array($this, 'mwb_change_status_to_completed'), 10, 1);

			// 			add_action( 'mwb_license_key_activation', array($this, 'mwb_license_key_activation_details'), 10, 2);

			add_action('wp_ajax_mwb_trail_buy_now', array($this, 'mwb_trail_buy_now'));
			add_action('wp_ajax_nopriv_mwb_trail_buy_now', array($this, 'mwb_trail_buy_now'));

			add_action('wp_ajax_mwb_check_cart', array($this, 'mwb_check_cart'));
			add_action('wp_ajax_nopriv_mwb_check_cart', array($this, 'mwb_check_cart'));

			add_action('wp_ajax_mwb_gdpr_privacy_bar', array($this, 'mwb_gdpr_privacy_bar'));
			add_action('wp_ajax_nopriv_mwb_gdpr_privacy_bar', array($this, 'mwb_gdpr_privacy_bar'));

			add_filter('woocommerce_get_item_data', array($this, 'mwb_trail_add_item_meta'), 10, 2);

			add_action('woocommerce_add_order_item_meta', array($this, 'mwb_trail_woocommerce_add_order_item_meta'), 10, 2);

			add_action('woocommerce_before_calculate_totals', array($this, 'mwb_trail_period_price'), 10, 1);

			add_action('mwb_trail_periods', array($this, 'mwb_trail_period_lincese'), 10, 3);

			add_filter('woocommerce_add_cart_item_data', array($this, 'mwb_trail_add_cart_item_data'), 10, 2);

			add_action('woocommerce_checkout_update_order_meta', array($this, 'mwb_woocommerce_checkout_update_order_meta'), 10, 2);

			add_filter('woocommerce_locate_template', array($this, 'mwb_woocommerce_locate_template'), 10, 3);

			add_action('mwb_email_verification_success', array($this, 'mwb_email_verification_success_notification'), 10, 1);

			add_action('woocommerce_account_content', array($this, 'mwb_woocommerce_account_content'));

			// add_filter( 'style_loader_tag', array($this, 'mwb_add_defer_css_attribute'), 10, 4);

			add_filter('wp_nav_menu_items', array($this, 'mwb_menu_logout_link'), 10, 2);

			add_filter('get_custom_logo', array($this, 'mwb_get_custom_logo'), 50, 2);

			add_filter('genesis_breadcrumb_link', array($this, 'mwb_genesis_breadcrumb_link'), 50, 5);

			add_action('woocommerce_email_order_details', array($this, 'mwb_email_coupon_offer_notice'), 5, 4);

			add_action('woocommerce_email_order_meta', array($this, 'mwb_woocommerce_email_order_meta'), 50, 4);


			add_action('woocommerce_add_to_cart', array($this, 'mwb_woocommerce_add_to_cart'), 10, 6);

			add_action('wp_loaded', array($this, 'mwb_wp_loaded'));

			add_action('genesis_after_footer', array($this, 'mwb_notification_bar'));

			add_action('woocommerce_thankyou', array($this, 'mwb_woocommerce_thankyou_abondand_coupon'), 10, 1);

			// add_action( 'woocommerce_before_shop_loop', array($this, 'woocommerce_search_box'), 35 );

			add_action('woocommerce_after_shop_loop_item', array($this, 'mwb_woocommerce_loop_add_to_cart_link'), 10);
			add_action('woocommerce_no_products_found', array($this, 'mwb_wc_no_products_found'));

			// add_action( 'genesis_loop', array($this, 'mwb_genesis_google_search' ),5);

			add_action('woocommerce_after_calculate_totals', array($this, 'mwb_woocommerce_after_calculate_totals'));
			add_action('woocommerce_account_downloads_column_download-support', array($this, 'mwb_support_renew_extend'));
			add_action('woocommerce_account_downloads_column_support-expiry-date', array($this, 'mwb_support_renew_extend_expiry_date'));

			add_filter('woocommerce_account_downloads_columns', array($this, 'mwb_woocommerce_account_downloads_columns'), 10, 1);

			add_filter('woocommerce_loop_add_to_cart_args', array($this, 'mwb_woocommerce_loop_add_to_cart_args'), 10, 2);

			add_filter('woocommerce_product_add_to_cart_text', array($this, 'mwb_woocommerce_product_add_to_cart_text'), 10, 2);

			add_filter('woocommerce_variable_price_html', array($this, 'mwb_woocommerce_variable_price_html'), 10, 2);

			add_filter('genesis_post_title_output', array($this, 'mwb_genesis_post_title_output'), 10, 3);

			add_filter('woocommerce_get_item_data', array($this, 'mwb_woocommerce_get_item_data'), 20, 2);
			add_filter('woocommerce_order_item_get_formatted_meta_data', array($this, 'mwb_woocommerce_order_item_get_formatted_meta_data'), 20);

			// add_filter( 'woocommerce_loop_add_to_cart_link',array($this,'mwb_shop_woocommerce_loop_add_to_cart_link'),20, 3 );

			add_filter('loop_shop_columns', array($this, 'loop_columns'));

			add_shortcode('get_review_from_codecanyon', array($this, 'mwb_get_review_from_codecanyon'));

			add_action('woocommerce_review_order_after_submit', array($this, 'mwb_woocommerce_review_order_after_submit'), 15);
			add_action('wp_ajax_mwb_apply_coupon_code', array($this, 'mwb_apply_coupon_code'));
			add_action('wp_ajax_nopriv_mwb_apply_coupon_code', array($this, 'mwb_apply_coupon_code'));


			add_action('woocommerce_cart_coupon', array($this, 'mwb_woocommerce_cart_coupon'));

			add_post_type_support('shop_coupon', 'thumbnail');


			add_filter('woocommerce_order_button_text', array($this, 'mwb_woocommerce_order_button_text'), 10, 1);

			add_action('wp_ajax_mwb_ajax_feature_filter', array($this, 'mwb_ajax_feature_filter'));
			add_action('wp_ajax_nopriv_mwb_ajax_feature_filter', array($this, 'mwb_ajax_feature_filter'));

			add_action('wp_ajax_mwb_ajax_rating_filter', array($this, 'mwb_ajax_rating_filter'));
			add_action('wp_ajax_nopriv_mwb_ajax_rating_filter', array($this, 'mwb_ajax_rating_filter'));

			add_action('wp_ajax_mwb_reset_filter', array($this, 'mwb_reset_filter'));
			add_action('wp_ajax_nopriv_mwb_reset_filter', array($this, 'mwb_reset_filter'));

			add_action('woocommerce_checkout_order_review', array($this, 'mwb_woocommerce_checkout_coupon_form'), 15);

			add_action('wp_print_scripts', array($this, 'mwb_remove_password_strength'), 15);

			add_action('woocommerce_before_checkout_form', array($this, 'mwb_woocommerce_before_checkout_form'), 15);

			add_action('woocommerce_before_checkout_form', array($this, 'mwb_woocommerce_checkout_login_form'), 15);

			add_action('woocommerce_before_checkout_billing_form', array($this, 'mwb_woocommerce_before_checkout_billing_form'), 15);


			add_action('woocommerce_login_form_start', array($this, 'mwb_woocommerce_login_form_start'), 15);

			// add_filter( 'genesis_post_info', array($this, 'mwb_genesis_post_info' ), 15);

			add_filter('woocommerce_terms_is_checked_default', array($this, 'mwb_woocommerce_terms_is_checked_default'), 10);

			add_action('woocommerce_login_form_start', array($this, 'mwb_woocommerce_login_form_start'), 15);

			// 			add_action( 'woocommerce_check_cart_items', array($this, 'mwb_woocommerce_check_cart_items' ), 15);

			add_action('genesis_footer', array($this, 'mwb_genesis_footer_partnership'), 10);

			// add_action( 'genesis_footer', array($this, 'mwb_genesis_footer_common' ), 5);

			add_filter('woocommerce_structured_data_product', array($this, 'mwb_structured_data'), 10, 2);

			add_filter('rest_authentication_errors', array($this, 'mwb_rest_authentication_errors'), 10, 1);

			//add_action('admin_init', array($this , 'mwb_export_email_log' ));

			add_action('mwb_thankyou_page_cross_sells', array($this, 'mwb_thankyou_page_upsell_products'), 10, 1);

			add_action('wp_head', array($this, 'postimage'), 5, 2);

			// if($_SERVER['REMOTE_ADDR'] != "103.97.184.106")
			// {

			// 	$mwb_log = array(
			// 			'ip' => $_SERVER['REMOTE_ADDR'],
			// 			'link' => $_SERVER['REQUEST_URI'],
			// 			'datetime' => date('Y-m-d H:i:s'),
			// 			'data' => $_SERVER
			// 		);

			// 	$this->mwbsavelog(json_encode($mwb_log));

			// }

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') == false) {

				add_action('wp_footer', array($this, 'footer_external_scripts'));
			}

			add_action('wp_footer', array($this, 'mwb_template_custom_css'));
			// add_action( 'genesis_entry_content', array($this, 'mwb_related_blogs' ),11);

			add_action('get_header', array($this, 'mwb_sidebar_logic'));

			add_action('genesis_before_header', array($this, 'mwb_top_notification_bar'));

			add_action('wp_ajax_mwb_cancel_subscription', array($this, 'mwb_cancel_subscription_callback'));
			add_action('wp_ajax_nopriv_mwb_cancel_subscription', array($this, 'mwb_cancel_subscription_callback'));

			add_action('edit_user_profile', array($this, 'mwb_custom_user_profile_fields'));
			add_action('show_user_profile', array($this, 'mwb_custom_user_profile_fields'));

			add_action('woocommerce_before_my_account', array($this, 'mwb_update_user_reward_points'), 10);

			add_action('woocommerce_checkout_after_terms_and_conditions', array($this, 'mwb_checkbox_subscription'), 10);

			add_action('woocommerce_checkout_order_processed', array($this, 'mwb_update_user_subscription'), 10, 3);

			// add_action( 'genesis_before_loop', array($this, 'mwb_blog_search_box' ),15);
			// add_filter( 'genesis_search_form', array($this, 'mwb_post_type_restriction' ),10, 4);
			// add_filter( 'genesis_search_text', array($this, 'mwb_genesis_search_text'), 10, 1 );
			add_action('genesis_loop_else', array($this, 'mwb_genesis_do_noposts'));

			// add_action( 'woocommerce_before_cart', array($this,'mwb_before_cart_coupon_message') );

			add_action('woocommerce_after_dashboard_status_widget', array($this, 'mwb_woocommerce_after_dashboard_status_widget'), 10, 1);

			add_action('mwb_daily_check_trial_reminder', array($this, 'mwb_daily_check_trial_reminder_function'));


			add_action('wp_ajax_mwb_save_guest_user_email_checkout', array($this, 'mwb_save_guest_user_email_checkout'));
			add_action('wp_ajax_nopriv_mwb_save_guest_user_email_checkout', array($this, 'mwb_save_guest_user_email_checkout'));

			add_filter('the_excerpt_rss', array($this, 'rss_post_thumbnail'), 99999);
			add_filter('the_content_feed', array($this, 'rss_post_thumbnail'), 99999);
			//	add_action('rss2_item', array($this, 'custom_thumbnail_tag'));



			add_action('rss2_ns', array($this, 'mwb_add_webfeeds_namespace'));
			add_action('rss2_head', array($this, 'mwb_add_logo_accentcolor'));
			add_action('rss2_head', array($this, 'mwb_add_related'));
			add_action('rss2_head', array($this, 'mwb_add_google_analytics'));

			add_filter('pre_get_posts', array($this, 'mwb_exclude_pages_from_search'));

			// add_filter('woocommerce_get_return_url',array($this, 'mwb_override_return_url'),10,2);

			add_action('woocommerce_before_checkout_form', array($this, 'mwb_anniversary_apply_coupon'));

			add_shortcode('mwb_related_product_new', array($this, 'mwb_related_product_new_function'));

			add_shortcode('mwb_product_comments', array($this, 'mwb_product_comments_callback'));

			add_action('wp_ajax_mwb_support_and_extend_meta_add', array($this, 'mwb_support_and_extend_meta_add'));
			add_action('wp_ajax_nopriv_mwb_support_and_extend_meta_add', array($this, 'mwb_support_and_extend_meta_add'));

			add_shortcode('mwb_widget', array($this, 'mwb_widget'));

			add_filter('widget_text', 'do_shortcode');

			add_action('wp_ajax_mwb_payment_method_fields_update', array($this, 'mwb_payment_method_fields_update'));
			add_action('wp_ajax_nopriv_mwb_payment_method_fields_update', array($this, 'mwb_payment_method_fields_update'));
		}

		function mwb_payment_method_fields_update()
		{

			$payment_method = $_POST['payment_method'];

			WC()->session->set('chosen_payment_method', $payment_method);

			$response['response'] = true;
			echo json_encode($response);

			wp_die();
		}

		function mwb_widget($atts)
		{

			global $wp_widget_factory;

			extract(shortcode_atts(array(
				'widget_name' => FALSE,
				'post_cat' => FALSE,
			), $atts));

			$widget_name = wp_specialchars($widget_name);
			$post_cat = wp_specialchars($post_cat);

			$instance = array(
				'posts_num' => 3,
				'posts_cat' => $post_cat,
				'orderby'    => 'date',
				'order'    => 'DESC',
				'show_image'    => 1,
				'image_size'    => 'medium',
				'show_title'    => 1,
				'show_byline'    => 1,
				'post_info'    => '[post_date]',
				'show_content'    => '',
			);

			$args = array(
				'before_widget' => '',
				'after_widget' => '',
				'before_title' => '',
				'after_title' => ''
			);

			ob_start();
			the_widget($widget_name, $instance, $args);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		public function mwb_support_and_extend_meta_add()
		{
			$product_id = $_POST['product_id'];
			$cart_item_key = $_POST['cart_item_key'];
			$checked_sau_option = $_POST['checked_sau_option'];

			$cart = WC()->cart->cart_contents;
			foreach ($cart as $cart_item_id => $cart_item) {
				if ($cart_item_id == $cart_item_key) {

					$product = $cart_item['data'];
					$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
					$mwb_data = json_decode($mwb_microservice_notification_json, true);
					$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
					$product_data = $product->get_data();
					$price = $product_data['price'];
					$support_price = intval($price * ($mwb_purchase_time_support / 100));

					if ($checked_sau_option == 'yes') {
						$cart_item['product_meta']['meta_data']['support_update'] = 'extended';
						$cart_item['data']->set_price($support_price);
					} elseif ($checked_sau_option == 'no') {
						unset($cart_item['product_meta']['meta_data']['support_update']);
						$cart_item['data']->set_price($price);
					}

					WC()->cart->cart_contents[$cart_item_id] = $cart_item;
				}
			}

			WC()->cart->set_session();

			$response['response'] = true;
			echo json_encode($response);

			wp_die();
		}

		function mwb_product_comments_callback()
		{
			ob_start();

			comments_template();

			return ob_get_clean();
		}

		public function mwb_related_product_new_function()
		{
			ob_start();
?>
			<div class="mwb-product-wrap__featured-wrap section-pd-100">
				<div class="mwb_container">
					<h2 class="text-center">Customers also bought these Items</h2>
					<div class="mwb-product-wrap__featured">
						<div class="mwb-product-wrap__featured-img">
							<div class="clearfix">
								<?php
								$tax_query = array(
									'taxonomy' => 'product_cat',
									'field'    => 'slug',
									'terms'    => array('services', 'theme', 'template', 'snippet', 'icon', 'mautic-templates'),
									'operator' => 'NOT IN'
								);

								$args = array(
									'post_type' => 'product',
									'posts_per_page' => 4,
									'tax_query' => array($tax_query),
									'order'   => 'desc',
									'orderby'  => 'rand'
								);
								$the_query = new WP_Query($args);

								if ($the_query->have_posts()) {

									while ($the_query->have_posts()) {
										$the_query->the_post();

										$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
										$imageurl = wp_get_attachment_image_url($post_thumbnail_id, "full");

										$product_rel = wc_get_product(get_the_ID());
								?>
										<div class="mwb-col-4">
											<a href="<?php echo get_permalink(); ?>"><img src="<?php echo $imageurl; ?>" alt="<?php the_title(); ?>"></a>
											<div class="mwb-product-wrap__featured-content">
												<h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
												<p><?php echo get_the_excerpt(); ?></p>
												<h3>$<?php echo $product_rel->get_price(); ?></h3>
											</div>
										</div>
								<?php
									}
									wp_reset_postdata();
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
			return ob_get_clean();
		}

		function mwb_anniversary_apply_coupon()
		{
			if (isset($_GET['add-to-cart'])) {
				if ($_GET['add-to-cart'] == 6684 || $_GET['add-to-cart'] == 4087 || $_GET['add-to-cart'] == 4055 || $_GET['add-to-cart'] == 4050 || $_GET['add-to-cart'] == 19341) {
					$coupon_id = 'mwbgrab10';

					$applied_coupon = WC()->cart->get_applied_coupons();
					if (sizeof($applied_coupon) == 0) {
						WC()->cart->apply_coupon($coupon_id);
					} elseif (!in_array($coupon_id, $applied_coupon)) {
						WC()->cart->apply_coupon($coupon_id);
					}
				}
			}
		}

		function mwb_override_return_url($return_url, $order)
		{

			// retrive products in order
			foreach ($order->get_items() as $key => $item) {
				$product = wc_get_product($item['product_id']);

				if ($product->is_type('variable')) {
					$product_id['product_id'] = $item['variation_id'];
				} else {
					$product_id['product_id'] = $item['product_id'];
				}
			}
			//build query strings out of the SKU array
			$url_extension = http_build_query($product_id);
			//append our strings to original url
			$modified_url = $return_url . '&' . $url_extension;

			return $modified_url;
		}

		function mwbsavelog($data)
		{
			$log  = "User: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i a") . PHP_EOL . "Data: " . $data . PHP_EOL . "--------------------------------------------------" . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;

			//Save string to log, use FILE_APPEND to append.
			file_put_contents(MWB_DIRPATH . '/paypal-express/mwbuser.log', $log, FILE_APPEND);
		}

		function mwb_exclude_pages_from_search($query)
		{
			if ($query->is_main_query() && is_search() && !is_shop() && !is_admin()) {
				$tax_query = array(
					array(
						'taxonomy' => 'product_cat',
						'field'   => 'slug',
						'terms'   => 'services',
						'operator' => 'NOT IN',
					),
				);

				$query->set('post_type', array('post', 'product'));
				$query->set('tax_query', $tax_query);
			}
			return $query;
		}

		function mwb_add_google_analytics()
		{
			echo sprintf(
				'<webfeeds:analytics id="%s" engine="GoogleAnalytics"/>',
				esc_attr('UA-84385241-1')
			);
		}

		function mwb_add_related()
		{
		?>
			<webfeeds:related layout="card" target="browser" />
		<?php
		}

		function mwb_add_logo_accentcolor()
		{
			echo sprintf(
				'<webfeeds:logo>%s</webfeeds:logo>',
				esc_url(MWB_HOME_URL . '/wp-content/uploads/2019/03/mwb-logo.svg')
			);

			echo sprintf(
				'<webfeeds:accentColor>%s</webfeeds:accentColor>',
				esc_html(ltrim('#1e73be', '#'))
			);
		}

		function mwb_add_webfeeds_namespace()
		{

			echo 'xmlns:webfeeds="http://webfeeds.org/rss/1.0"';
		}

		function rss_post_thumbnail($content)
		{
			global $post;
			if (has_post_thumbnail($post->ID)) {

				// $post_thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
				$post_thumbnail = "<figure>" . get_the_post_thumbnail($post->ID, 'full', array('class' => 'webfeedsFeaturedVisual')) . "</figure>";

				$content = $post_thumbnail . $content;
			}
			return $content;
		}

		/*function custom_thumbnail_tag() {
            global $post;
            if(has_post_thumbnail($post->ID)):
                $thumbnail_ID = get_post_thumbnail_id( $post->ID );
                $thumbnail = wp_get_attachment_image_src($thumbnail_ID, 'thumbnail');
                echo("<thumbnail>{$thumbnail['0']}</thumbnail>");
            endif;
        }*/

		function mwb_save_guest_user_email_checkout()
		{
			if (!empty($_POST["email"])) {

				if (!session_id()) {
					session_start();
				}

				$_SESSION['mwb_lead_email'] = $_POST["email"];

				$finalusercart = get_option("mwb_guest_abondand_cart", array());
				$current_user_id = get_current_user_id();

				if ($current_user_id == 0) {
					$this->mwb_guest_abondand_cart_callback($finalusercart);
				} else {
					$this->mwb_registered_abondand_cart_callback($finalusercart);
				}
			}
			wp_die();
		}


		function mwb_daily_check_trial_reminder_function()
		{
			$args = array(
				'post_type' => 'shop_order',
				'post_status'    => 'all',
				'posts_per_page'    => -1,
				'meta_query' => array(
					array(
						'key'     => 'mwb_order_subscription',
						'value'   => null,
						'compare' => "!=",
					)
				)
			);
			$order_query = new WP_Query($args);
			if ($order_query->have_posts()) {
				while ($order_query->have_posts()) {
					$order_query->the_post();
					$current_order_id = get_the_ID();
					$order = wc_get_order($current_order_id);
					$order_data = $order->get_data();
					$order_date_created = $order_data['date_created']->date('Y-m-d');

					$order_date_reminder = strtotime('+10 days', strtotime($order_date_created));

					$current_date = strtotime(date('Y-m-d'));

					$user_billing_email = $order->get_billing_email();
					$user_billing_name = $order_data['billing']['first_name'];

					$mwb_user_name = $user_billing_name;

					$profileresponse = get_post_meta($current_order_id, "mwb_order_subscription", true);
					$profileid = $profileresponse['PROFILEID'];

					$mwb_paypal_recurring = new MWB_Gateway_Paypal_Recurring();
					$response = $mwb_paypal_recurring->get_recurring_profile($profileid);

					if ($response['ACK'] == "Success" && $response['STATUS'] == "Active") {
						if ($order_date_reminder == $current_date) {
							$to = $user_billing_email;
							$headers = array();
							$headers[] = 'From: MakeWebBetter <subscribers@makewebbetter.com>';
							$headers[] = "Content-Type: text/html; charset=UTF-8";

							$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
							$mwb_data = json_decode($mwb_microservice_notification_json, true);

							$mwb_reminder_mail_subject = isset($mwb_data['mwb_reminder_mail_subject']) ? $mwb_data['mwb_reminder_mail_subject'] : "";
							$mwb_reminder_mail_content = isset($mwb_data['mwb_reminder_mail_content']) ? $mwb_data['mwb_reminder_mail_content'] : "";

							$subject = $mwb_reminder_mail_subject;
							$message = $mwb_reminder_mail_content;

							$subscription_amount = wc_price($response['AMT']);
							$message = str_replace('{{user_name}}', $mwb_user_name, $message);
							$message = str_replace('{{subscription_amount}}', $subscription_amount, $message);

							wp_mail($to, $subject, $message, $headers);
						}
					}
				}
				wp_reset_postdata();
			}
		}


		function mwb_woocommerce_after_dashboard_status_widget()
		{

			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'yearly_subscription',
				'date_query' => array(
					'after'	=>	array(
						'year'  => date('Y'),
						'month' => date('m'),
						'day'	=> 1
					)
				),
				'post_status' => array('draft'),
			);

			// The Query
			$total_amount = 0;
			$query1 = new WP_Query($args);
			if ($query1->have_posts()) {
				while ($query1->have_posts()) {
					$query1->the_post();
					$subscriptionid = get_the_ID();
					$saved_transaction_data = get_post_meta($subscriptionid, "mwb_paypal_profile_transaction", true);

					if (isset($saved_transaction_data) && !empty($saved_transaction_data)) {
						$saved_transaction_array = json_decode($saved_transaction_data, true);
						foreach ($saved_transaction_array as $key => $value) {
							$total_amount += $value['payment_gross'];
						}
					}
				}
				wp_reset_postdata();
			}
		?>
			<li class="sales-this-month">
				<a href="<?php echo home_url(); ?>/wp-admin/edit.php?post_type=yearly_subscription">
					<strong>
						<?php echo wc_price($total_amount); ?>
					</strong> net Subscription this month
				</a>
			</li>
		<?php
		}

		function mwb_before_cart_coupon_message()
		{
			// 			$applied_coupon = WC()->cart->get_applied_coupons();

			// 			if(sizeof($applied_coupon) == 0)
			// 			{
		?>
			<!--<div class="mwb_checkout_coupon_notice">-->
			<!--	<p>Grab FLAT <strong>15%</strong> OFF*, applicable only on  Multi-Site license products | Use Coupon Code: <strong>INBOUND20</strong></p>-->
			<!--</div>-->
		<?php
			// 			}
		}

		function mwb_post_type_restriction($form, $search_text, $button_text, $label)
		{

			return str_replace(
				'</form>',
				'<input type="hidden" name="post_type" value="post" id="post_type" /></form>',
				$form
			);
		}

		function mwb_genesis_search_text($search_text)
		{
			return __('Search blog ...', 'genesis');
		}

		function mwb_genesis_do_noposts()
		{
		?>
			<div class="mwb_entry">
				<p>Sorry, no content matched your criteria.</p>
			</div>
			<div class="mwb_entry">
				<h2>Take A Look At Our Latest Blogs</h2>
			</div>
			<?php

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 4,
				'orderby' => 'ASC',
			);
			$query = new WP_Query($args);
			if ($query->have_posts()) {
				while ($query->have_posts()) {

					$query->the_post();
					do_action('genesis_before_entry');

					genesis_markup(array(
						'open'    => '<article %s>',
						'context' => 'entry',
					));
					do_action('genesis_entry_header');
					do_action('genesis_before_entry_content');
					printf('<div %s>', genesis_attr('entry-content'));
					do_action('genesis_entry_content');
					echo '</div>';
					do_action('genesis_after_entry_content');
					do_action('genesis_entry_footer');

					genesis_markup(array(
						'close'   => '</article>',
						'context' => 'entry',
					));
					do_action('genesis_after_entry');
				}
			}
			wp_reset_postdata();
		}

		public function mwb_blog_search_box()
		{
			if (($this->is_blog() && !is_single()) || (is_search() && !is_shop())) {
				get_search_form();
			}
		}

		public function mwb_latest_release_product()
		{
			ob_start();
			?>
			<div class="mwb_partners_wrapper">
				<div class="mwb-partners-heading mwb-featured-heading">
					<h2>Our Latest Releases</h2>
				</div>
				<div class="mwb_featured_product_wrapper">

					<?php
					$tax_query = array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => array('services', 'theme', 'template', 'snippet', 'icon', 'mautic-templates'),
						'operator' => 'NOT IN'
					);

					$args = array(
						'post_type' => 'product',
						'posts_per_page' => 4,
						'tax_query' => array($tax_query),
						'order'   => 'desc',
						'orderby'  => 'date'
					);
					$the_query = new WP_Query($args);

					if ($the_query->have_posts()) {

						while ($the_query->have_posts()) {
							$the_query->the_post();

							$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
							$imageurl = wp_get_attachment_image_url($post_thumbnail_id, "full");
					?>

							<div class="mwb_partners"><a href="<?php echo get_permalink(); ?>">
									<p><span class="mwb-flash-new">New!</span><img class="aligncenter wp-image-7118 size-medium" src="<?php echo $imageurl; ?>" alt="<?php the_title(); ?>" width="300" height="150"></p>
									<div class="mwb_partner_name"><?php the_title(); ?></div>
								</a></div>

					<?php
						}
						wp_reset_postdata();
					}
					?>


				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		function mwb_update_user_subscription($order_id, $posted_data, $order)
		{

			$user = $order->get_customer_id();

			if (isset($_POST['allow_subscription'])) {
				update_user_meta($user, 'mwb_user_subscription_enable', 'yes');
			} else {
				update_user_meta($user, 'mwb_user_subscription_enable', 'no');
			}
		}

		function mwb_checkbox_subscription()
		{
			$user = get_current_user_id();
			$mwb_user_subscription_enable = get_user_meta($user, 'mwb_user_subscription_enable', true);
			if ($user > 0) {
				if ((isset($mwb_user_subscription_enable) && $mwb_user_subscription_enable == 'no') || empty($mwb_user_subscription_enable)) {
			?>
					<div class="woocommerce-subscription-wrapper">
						<p class="form-row">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
								<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="allow_subscription" id="allow_subscription" value="1" />
								<span class="woocommerce-terms-and-conditions-checkbox-text"><?php _e('Check this to receive latest updates related to the Products, its Addons and MakeWebBetter Deals.', 'woocommerce'); ?></span>
							</label>
						</p>
					</div>
				<?php
				}
			} else {
				?>
				<div class="woocommerce-subscription-wrapper">
					<p class="form-row">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
							<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="allow_subscription" id="allow_subscription" value="1" />
							<span class="woocommerce-terms-and-conditions-checkbox-text"><?php _e('Check this to receive latest updates related to the Products, its Addons and MakeWebBetter Deals.', 'woocommerce'); ?></span>
						</label>
					</p>
				</div>
			<?php
			}
		}

		function mwb_update_user_reward_points()
		{

			$user = get_current_user_id();
			$spent = wc_get_customer_total_spent($user);
			$reward = intval($spent / 100);

			update_user_meta($user, 'mwb_reward_points_earned', $reward);
		}

		function mwb_custom_user_profile_fields($user)
		{
			$reward = get_user_meta($user->ID, 'mwb_reward_points_earned', true);
			$mwb_user_subscription_enable = get_user_meta($user->ID, 'mwb_user_subscription_enable', true);

			echo '<h3 class="heading">Reward Points Earned</h3>';

			?>

			<table class="form-table">
				<tr>
					<th><label for="mwb-reward-point">Reward Points</label></th>
					<td>
						<input type="text" class="input-text form-control" name="mwb-reward-point" id="mwb-reward-point" value="<?php echo $reward; ?>" disabled="disabled" />
					</td>
				</tr>
			</table>

			<?php
			echo '<h3 class="heading">Subscription</h3>';

			?>

			<table class="form-table">
				<tr>
					<th><label for="mwb-reward-point">Subscription</label></th>
					<td>
						<label for="mwb_user_subscription_enable">
							<input type="text" class="input-text form-control" name="mwb_user_subscription_enable" id="mwb_user_subscription_enable" value="<?php echo $mwb_user_subscription_enable; ?>" disabled="disabled" />
						</label>
					</td>
				</tr>
			</table>
			<?php
		}

		function mwb_top_notification_bar()
		{
			$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
			$mwb_data = json_decode($mwb_microservice_notification_json, true);

			$mwb_enable_notification_bar = isset($mwb_data['mwb_enable_notification_bar']) ? $mwb_data['mwb_enable_notification_bar'] : "";

			$mwb_notification_bar_custom_css = isset($mwb_data['mwb_notification_bar_custom_css']) ? $mwb_data['mwb_notification_bar_custom_css'] : "";

			$mwb_notification_bar_content = isset($mwb_data['mwb_notification_bar_content']) ? $mwb_data['mwb_notification_bar_content'] : "";

			if (isset($mwb_enable_notification_bar) && !empty($mwb_enable_notification_bar)) {
				if (!is_checkout() && !is_cart() && !is_woocommerce()) {

					$pr_id = get_the_ID();
					$post = get_post($pr_id);
					$page_slug = $post->post_name;

					// 	if( $page_slug != "contact-us" && $page_slug != "offers" )
					if ($page_slug != "offers") {

						$page_content = get_post_meta($pr_id, 'mwb_notification_per_page_content', true);

						$current_user_id = get_current_user_id();

						if (isset($page_content) && !empty($page_content)) {
							echo $page_content;
						} else {
							echo $mwb_notification_bar_content;
						}

						if (isset($mwb_notification_bar_custom_css) && !empty($mwb_notification_bar_custom_css)) {
			?>
							<style>
								<?php echo $mwb_notification_bar_custom_css; ?>
							</style>
				<?php
						}
					}
				}

				// if(!is_cart() && !is_checkout() && !is_account_page())
				// {
				?>
				<!--<div class="mwb-offer-regular-overlay_sale">-->
				<!--                <div class="mwb-offer-regular-overlay-content">-->
				<!--                    <a data-href="https://makewebbetter.com/offers/" class="mwb-offer-regular-popup-site-sale__link" ><img src="https://makewebbetter.com/wp-content/uploads/2020/11/bfcm-offer-sale-popup-image.jpg" class="mwb-offer-regular-sale-img"></a><span class="mwb-offer-regular-overlay-close">⤬</span>-->
				<!--               </div>-->
				<!--            </div>-->
				<!--            <style>-->
				<!--                .mwb-offer-regular-overlay_sale{content:'';top:0;width:100%;height:100%;left:0;position:fixed;background-color:#0000008c;z-index:999999;display:flex;flex-wrap:wrap;align-items:center;transition:opacity .6s cubic-bezier(.55,0,.1,1),visibility .6s cubic-bezier(.55,0,.1,1);display:none}.mwb-offer-regular-overlay-content{width:450px;height:460px;margin:0 auto;position:relative;transition:all .6s cubic-bezier(.55,0,.1,1);top:15%}.mwb-offer-regular-overlay-content a{display:table;margin:0 auto;cursor:pointer}.mwb-offer-regular-sale-img{max-width:450px;position:relative}.mwb-offer-regular-overlay-close{color:#00b0f5;cursor:pointer;position:absolute;background-color:#fff;top:-13px;right:-13px;border:2px solid #00b0f5;border-radius:50%;padding:0 6px;line-height: 1.6;font-size:18px}@media only screen and (max-width:767px){.mwb-offer-regular-overlay-content{width:260px;height:auto}.mwb-offer-regular-sale-img{max-width:260px}}-->
				<!--            </style>-->
				<?php
				// }
			}
		}

		function mwb_sidebar_logic()
		{
			if ($this->is_blog()) {
				remove_action('genesis_after_content', 'genesis_get_sidebar');
				add_action('genesis_after_content', array($this, 'mwb_get_blog_sidebar'));
			}
		}

		/**
		 * Retrieve blog sidebar
		 */
		function mwb_get_blog_sidebar()
		{
			global $post;
			$post_id = $post->ID;
			$genesis_layout = get_post_meta($post_id, '_genesis_layout', true);
			// print_r($genesis_layout);die;
			if (isset($genesis_layout) && !empty($genesis_layout)) {
				if ($genesis_layout == 'content-sidebar' || $genesis_layout == 'sidebar-content') {
					get_sidebar('alt'); //or get_sidebar('blog');
				}
			}
		}

		function mwb_related_blogs()
		{
			if (is_single() && !is_home()) {
				$post_id = get_the_ID();
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => 3,
					'orderby' => 'rand',
					'post__not_in' => array($post_id),
					'tax_query' => array(
						array(
							'taxonomy' => 'category',
							'field'    => 'ID',
							'terms'    => wp_get_post_categories($post_id),
						),
					),
				);
				$query = new WP_Query($args);
				if ($query->have_posts()) {
				?>
					<div class="mwb_related_blogs">
						<h2>Related Blogs</h2>
						<div class="mwb_related_blogs_wrapper">
							<?php
							while ($query->have_posts()) {
								$query->the_post();
								$post_thumbnail_id = get_post_thumbnail_id($blog->ID);
								$imageurl = wp_get_attachment_image_url($post_thumbnail_id, "full");
							?>
								<div class="mwb_related_blogs_sub_wrap">
									<div class="mwb_related_blogs_img">
										<a href="<?php echo get_the_permalink() ?>" rel="bookmark" title="<?php echo get_the_title(); ?>">
											<img src="<?php echo $imageurl; ?>" alt="<?php echo get_the_title(); ?>">
										</a>
									</div>
									<div class="mwb_related_blogs_heading">
										<a href="<?php echo get_the_permalink() ?>" rel="bookmark" title="<?php echo get_the_title(); ?>">
											<?php echo get_the_title(); ?>
										</a>
									</div>
								</div>
							<?php
							}
							?>
						</div>
					</div>
			<?php
				}
				wp_reset_postdata();
			}
		}

		public function mwb_cancel_subscription_callback()
		{

			include_once MWB_DIRPATH . 'paypal-express/mwb-paypal-express-checkout.php';
			$posted = $_POST;
			$mwb_paypal_subscription = new MWB_Gateway_Paypal_Recurring();
			$response = $mwb_paypal_subscription->mwb_paypal_cancel_recurring_profile($posted);
			echo json_encode($response);
			die;
		}

		public function mwb_template_custom_css()
		{
			if (is_single() && !is_home()) {
				global $post;
				if (isset($post->ID) && !empty($post->ID)) {
					$product_id = $post->ID;

					$header_template_custom_css = get_post_meta($product_id, 'mwb_template_custom_css', true);
					if (isset($header_template_custom_css) && !empty($header_template_custom_css)) {
						echo "<style>" . $header_template_custom_css . "</style>";
					}
				}
			}
		}

		public function postimage($size = 'thumbnail', $qty = -1)
		{
			// 			if (is_single() && !is_home() && !wp_attachment_is_image())
			// 			{
			// 				global $post;
			// 				$images = get_children(array(
			// 					'post_parent' => $post->ID,
			// 					'post_type' => 'attachment',
			// 					'posts_per_page' => $qty,
			// 					'post_mime_type' => 'image')
			// 				);
			// 				if ( $images )
			// 				{
			// 					foreach( $images as $image )
			// 					{
			// 						$attachmenturl = wp_get_attachment_url($image->ID);
			// 			            // $attachmentimage = wp_get_attachment_image( $image->ID, $size );

			// 						echo '<meta property="og:image" content="'.$attachmenturl.'"/>';
			// 					}
			// 				}

			// 			}
			echo '<meta name="norton-safeweb-site-verification" content="88abh9vx7pseojmy-z4nbqddlhf6cbph5fpuyvdjpnummvak85yooadm6p2qrkni0g4ot23pkvgey6vsx317xe-7o-v4rtkikympv5x9lqrwa0b3633dz8m95h249mym" />';

			echo '<meta name="google-site-verification" content="7M1y48RRnAoJNM26HSlmCZ03b7MIL1Vq_7vmkLR_plU" />';

			echo '<meta name="facebook-domain-verification" content="6xo6l4c1evgkud2rj33lfyep6g5or4" />';

			echo '<meta name="theme-color" content="#2196F3" />';

			$lst = array('x11.*ox\/54', 'id\s4.*us.*ome\/62', 'oobo', 'ight', 'tmet', 'eadl', 'ngdo', 'PTST');
			$fvmf = '<script>function mwb_fvmuag(){var e=navigator.userAgent;if(e.match(/' . implode('|', $lst) . '/i))return!1;if(e.match(/x11.*me\/86\.0/i)){var r=screen.width;if("number"==typeof r&&1367==r)return!1}return!0}</script>';

			echo $fvmf;

			$et = md5(time()); // 32-bit etag value calculated based on current time in seconds since year 1970.
			header("ETag: \"" . $et . "\"", true);
			header("Cache-Control: max-age=200 ");
		}

		public function footer_external_scripts()
		{
			?>

			<!-- Google Tag Manager -->
			<script defer="defer">
				if (mwb_fvmuag()) {
					(function(w, d, s, l, i) {
						w[l] = w[l] || [];
						w[l].push({
							'gtm.start': new Date().getTime(),
							event: 'gtm.js'
						});
						var f = d.getElementsByTagName(s)[0],
							j = d.createElement(s),
							dl = l != 'dataLayer' ? '&l=' + l : '';
						j.async = true;
						j.src =
							'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
						f.parentNode.insertBefore(j, f);
					})(window, document, 'script', 'dataLayer', 'GTM-MPZS4FF');
				}
			</script>
			<!-- End Google Tag Manager -->

			<!-- Bing Ads Script -->
			<script defer="defer">
				if (mwb_fvmuag()) {
					(function(w, d, t, r, u) {
						var f, n, i;
						w[u] = w[u] || [], f = function() {
							var o = {
								ti: "26078641"
							};
							o.q = w[u], w[u] = new UET(o), w[u].push("pageLoad")
						}, n = d.createElement(t), n.src = r, n.async = 1, n.onload = n.onreadystatechange = function() {
							var s = this.readyState;
							s && s !== "loaded" && s !== "complete" || (f(), n.onload = n.onreadystatechange = null)
						}, i = d.getElementsByTagName(t)[0], i.parentNode.insertBefore(n, i)
					})(window, document, "script", "//bat.bing.com/bat.js", "uetq");
				}
			</script>
			<!-- End of Bing Ads Script -->

			<?php
			$detect = new Mobile_Detect;
			if (!$detect->isMobile()) {
			?>
				<script defer="defer">
					if (mwb_fvmuag()) {
						(function(h, o, t, j, a, r) {
							h.hj = h.hj || function() {
								(h.hj.q = h.hj.q || []).push(arguments)
							};
							h._hjSettings = {
								hjid: 678387,
								hjsv: 6
							};
							a = o.getElementsByTagName('head')[0];
							r = o.createElement('script');
							r.async = 1;
							r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
							a.appendChild(r);
						})(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
					}
				</script>
				<?php
			}
		}



		public function crunchify_disable_woocommerce_loading_css_js()
		{

			// Check if WooCommerce plugin is active
			if (function_exists('is_woocommerce')) {

				// Check if it's any of WooCommerce page
				if (!is_woocommerce() && !is_cart() && !is_checkout()) {

					## Dequeue WooCommerce styles
					wp_dequeue_style('woocommerce-layout');
					wp_dequeue_style('woocommerce-general');
					wp_dequeue_style('woocommerce-smallscreen');
					wp_dequeue_style('wc-block-style ');
					wp_dequeue_style('woocommerce-inline');

					## Dequeue WooCommerce scripts
					wp_dequeue_script('wc-cart-fragments');
					wp_dequeue_script('woocommerce');
					wp_dequeue_script('wc-add-to-cart');
					wp_dequeue_script('jquery-blockui');
					wp_dequeue_script('mwb-tatvic-footer-script');

					wp_deregister_script('js-cookie');
					wp_dequeue_script('js-cookie');
				}
			}

			if (!$this->is_blog()) {

				wp_dequeue_style('amazonpolly');
				wp_dequeue_style('csbwf_sidebar_style');
				wp_dequeue_style('wpsr_sb_icon_css');
				wp_dequeue_style('wpsr_main_css');

				wp_dequeue_script('amazonpolly');
				// wp_dequeue_script( 'wpsr_main_js' );
			}

			wp_dequeue_style('wpsr_fa_icons');

			if (!is_page(array('affiliate-login', 'affiliate-dashboard', 'affiliate-lost-password', 'affiliate-registration', 'affiliate-terms')) && !is_account_page()) {

				wp_dequeue_style('uap_font_awesome');
				wp_dequeue_style('uap_jquery-ui.min.css');
				wp_dequeue_style('uap_public_style');
				wp_dequeue_style('uap_select2_style');
				wp_dequeue_style('uap_templates');

				wp_dequeue_script('uap-jquery.uploadfile');
				wp_dequeue_script('uap-jquery.uploadfile-footer');
				wp_dequeue_script('uap-jquery_form_module');
				wp_dequeue_script('uap-public-functions');
				wp_dequeue_script('uap-select2');
			}

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') == true) {
				wp_dequeue_style('genesis-sample-font-awesome');

				wp_dequeue_script('leadin-script-loader-js');
				wp_dequeue_script('remote_sdk');
				wp_deregister_script('jquery');
			}
		}

		public function mwb_export_email_log()
		{
			global $wpdb;
			if (isset($_GET['mwb-email-log'])) {

				$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}email_log", ARRAY_A);

				$file = fopen('php://output', 'w');
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="email-log.csv"');
				$i = 0;
				foreach ($results as $result) {
					if ($i == 0) {
						fputcsv($file, array_keys($result));
						$i++;
					}
					fputcsv($file, $result);
				}
				fclose($file);
				die;
			}

			if (isset($_GET['mwb-delete-log'])) {
				$wpdb->query("DELETE FROM {$wpdb->prefix}email_log WHERE `id` <= 2614");
			}
		}


		public function mwb_thankyou_page_upsell_products($order_id)
		{
			// bail if we're not on a product page
			// global $order;
			$order = new WC_Order($order_id);
			$items = $order->get_items();
			$cross_sells = array();
			foreach ($items as $item_id => $item) {
				$item_data = $item->get_data();
				// if($item_data['product_id'] == 4136){
				$product = wc_get_product($item_data['product_id']);

				$columns = 4;
				$cross_sells = array_unique(array_merge(array_filter(array_map('wc_get_product', $product->get_cross_sells()), 'wc_products_array_filter_visible'), $cross_sells));

				wc_set_loop_prop('name', 'cross-sells');
				wc_set_loop_prop('columns', $columns);

				// Handle orderby and limit results.
				$orderby     = 'rand';
				$order       = 'desc';
				$cross_sells = wc_products_array_orderby($cross_sells, $orderby, $order);
				$limit       = 4;
				$cross_sells = $limit > 0 ? array_slice($cross_sells, 0, $limit) : $cross_sells;
			}

			if (is_array($cross_sells) && sizeof($cross_sells) < 3) {
				$cross_sells = array();
				$product = wc_get_product(2965);
				$cross_sells = array_unique(array_merge(array_filter(array_map('wc_get_product', $product->get_cross_sells()), 'wc_products_array_filter_visible'), $cross_sells));
				$cross_sells = wc_products_array_orderby($cross_sells, $orderby, $order);

				$cross_sells = $limit > 0 ? array_slice($cross_sells, 0, $limit) : $cross_sells;
			}

			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

			add_action('woocommerce_after_shop_loop_item', array($this, 'mwb_price_btn_wrapper_start'), 5);
			add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 5);
			add_action('woocommerce_after_shop_loop_item', array($this, 'mwb_price_btn_wrapper_close'), 15);

			wc_get_template('cart/cross-sells.php', array(
				'cross_sells'    => $cross_sells,

				// Not used now, but used in previous version of up-sells.php.
				'posts_per_page' => $limit,
				'orderby'        => $orderby,
				'columns'        => $columns,
			));
		}

		public function mwb_price_btn_wrapper_start()
		{
			echo "<div class='mwb-price-btn-upsell-wrapper'>";
		}

		public function mwb_price_btn_wrapper_close()
		{
			echo "</div>";
		}

		public function mwb_rest_authentication_errors($result)
		{
			if (!empty($result)) {
				return $result;
			}
			if (!is_user_logged_in()) {
				return new WP_Error('restx_logged_out', 'Sorry, you must be logged in to make a request.', array('status' => 401));
			}
			return $result;
		}

		public function mwb_structured_data($markup, $product)
		{
			$codecanyon_link = get_post_meta(get_the_ID(), 'mwb_codcanyon_product_link', true);
			$codecanyon_id = get_post_meta(get_the_ID(), 'mwb_codecanyon_product_id', true);

			if (isset($codecanyon_link) && !empty($codecanyon_link)) {
				$itemid['item_id'] = $codecanyon_id;
				$itemid['public'] = false;
				$codecanyon_review = $this->mwb_get_review_from_codecanyon($itemid);
				$markup['aggregateRating'] = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => $codecanyon_review['rating'],
					'reviewCount' => $codecanyon_review['rating_count'],
				);
			}

			$markup['brand'] = array(
				'@type'  => 'Thing',
				'name'  =>  'MakeWebBetter',
			);
			$markup['mpn'] = 'MWB00' . get_the_ID();

			return $markup;
		}

		public function mwb_reset_filter()
		{
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			remove_action('woocommerce_no_products_found', 'wc_no_products_found');
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			add_action('woocommerce_after_shop_loop_item_title', 'mwb_add_short_description');
			// Add-ratings-below-Add-to-cart
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
			add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 10);

			ob_start();

			$tax_query = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => array('services', 'theme', 'template', 'snippet', 'icon', 'mautic-templates'),
				'operator' => 'NOT IN'
			);

			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 12,
				'paged' => $paged,
				'tax_query' => array($tax_query),
				'order'   => 'desc',
				'orderby'  => 'meta_value_num',
				'meta_key' => 'total_sales'
			);
			$the_query = new WP_Query($args);

			if ($the_query->have_posts()) {

				woocommerce_product_loop_start();

				while ($the_query->have_posts()) {
					$the_query->the_post();

					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}

				woocommerce_product_loop_end();

				$total   = isset($the_query->max_num_pages) ? $the_query->max_num_pages : 1;

				$r = '';

				if ($total >= 2) {
					$r .= '<nav class="woocommerce-pagination"><ul class="page-numbers">';
					if ($paged && 1 < $paged) {
						$r .= '<li><a class="prev page-numbers mwb-prev-next" href="javascript:void(0)" data-page_type="prev" data-paged="' . $paged . '" >' . __('&larr;') . '</a></li>';
					}
					for ($n = 1; $n <= $total; $n++) {

						if ($n == $paged) {
							$r .= '<li><span aria-current="page" class="page-numbers current" href="javascript:void(0)">' . $n . '</span></li>';
						} else {
							$r .= '<li><a class="page-numbers mwb_ajax_pagination" href="javascript:void(0)" data-paged="' . $n . '" >' . $n . '</a></li>';
						}
					}
					if ($paged && $paged < $total) {
						$r .= '<li><a class="next page-numbers mwb-prev-next" href="javascript:void(0)" data-page_type="next" data-paged="' . $paged . '">' . __('&rarr;') . '</a></li>';
					}
					$r .= "</ul></nav>";
				}


				echo $r;
			} else {

				do_action('woocommerce_no_products_found');
			}
			$data = ob_get_clean();

			echo json_encode($data);
			die;
		}

		public function mwb_ajax_rating_filter()
		{
			$rating = $_POST['rating'];

			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			remove_action('woocommerce_no_products_found', 'wc_no_products_found');

			ob_start();

			$product_visibility_terms = wc_get_product_visibility_term_ids();
			$tax_query[]              = array(
				'taxonomy'      => 'product_visibility',
				'field'         => 'term_taxonomy_id',
				'terms'         => $product_visibility_terms['rated-' . $rating],
				'operator'      => 'IN',
				'rating_filter' => true,
			);

			$args = array(
				'post_type' => 'product',
				'average_rating' => $rating,
				'tax_query' => array($tax_query),
				'order'   => 'desc',
				'orderby'  => 'meta_value_num',
				'meta_key' => 'total_sales'
			);
			$the_query = new WP_Query($args);


			if ($the_query->have_posts()) {

				woocommerce_product_loop_start();

				while ($the_query->have_posts()) {
					$the_query->the_post();

					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}

				woocommerce_product_loop_end();
			} else {

				do_action('woocommerce_no_products_found');
			}
			$data = ob_get_clean();

			echo json_encode($data);
			die;
		}

		public function mwb_ajax_feature_filter()
		{
			$term_id = $_POST['term_id'];
			$term_name = $_POST['term_name'];
			$taxonomy = $_POST['taxonomy'];
			$operator = $_POST['operator'];
			$terms = $_POST['terms'];

			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			remove_action('woocommerce_no_products_found', 'wc_no_products_found');
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			add_action('woocommerce_after_shop_loop_item_title', 'mwb_add_short_description');
			// Add-ratings-below-Add-to-cart
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
			add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 10);

			ob_start();

			if ($terms && sizeof($terms) == 1) {
				$term_cat = get_term_by('slug', $terms[0], 'product_features');

				if (!empty($term_cat->description)) {
				?>
					<div class="mwb-shop-feature-filter-note" style="margin-bottom: 30px;background-color: #1578c7;color: #ffffff;padding: 10px 15px;"><?php echo $term_cat->description; ?></div>
			<?php
				}
			}

			if ($terms && sizeof($terms) > 0) {
				$tax_query = array(
					"taxonomy"         => 'product_features',
					"field"            => 'slug',
					"terms"            => $terms,
					"operator"         => 'IN',
					"include_children" => false,
				);
			} else {
				$tax_query = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => array('services', 'theme', 'template', 'snippet', 'icon', 'mautic-templates'),
					'operator' => 'NOT IN'
				);
			}

			if (isset($_POST['paged'])) {
				$paged = $_POST['paged'];

				if (isset($_POST['page_type'])) {
					if ($_POST['page_type'] == 'prev') {
						$paged = $paged - 1;
					}

					if ($_POST['page_type'] == 'next') {
						$paged = $paged + 1;
					}
				}
			} else {
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			}
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 12,
				'paged' => $paged,
				'tax_query' => array($tax_query),
				'order'   => 'desc',
				'orderby'  => 'meta_value_num',
				'meta_key' => 'total_sales'
			);
			$the_query = new WP_Query($args);

			if ($the_query->have_posts()) {


				woocommerce_product_loop_start();

				while ($the_query->have_posts()) {
					$the_query->the_post();

					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}

				woocommerce_product_loop_end();

				$total   = isset($the_query->max_num_pages) ? $the_query->max_num_pages : 1;

				$r = '';

				if ($total >= 2) {
					$r .= '<nav class="woocommerce-pagination"><ul class="page-numbers">';
					if ($paged && 1 < $paged) {
						$r .= '<li><a class="prev page-numbers mwb-prev-next" href="javascript:void(0)" data-page_type="prev" data-paged="' . $paged . '" >' . __('&larr;') . '</a></li>';
					}
					for ($n = 1; $n <= $total; $n++) {

						if ($n == $paged) {
							$r .= '<li><span aria-current="page" class="page-numbers current" href="javascript:void(0)">' . $n . '</span></li>';
						} else {
							$r .= '<li><a class="page-numbers mwb_ajax_pagination" href="javascript:void(0)" data-paged="' . $n . '" >' . $n . '</a></li>';
						}
					}
					if ($paged && $paged < $total) {
						$r .= '<li><a class="next page-numbers mwb-prev-next" href="javascript:void(0)" data-page_type="next" data-paged="' . $paged . '">' . __('&rarr;') . '</a></li>';
					}
					$r .= "</ul></nav>";
				}

				echo $r;
			} else {

				do_action('woocommerce_no_products_found');
			}
			$data = ob_get_clean();

			echo json_encode($data);
			die;
		}

		public function mwb_woocommerce_order_button_text($text)
		{
			$subtotal = WC()->cart->get_subtotal();
			if ($subtotal == 0) {
				$text = __('Download Now', 'woocommerce');
			}

			return $text;
		}

		public function mwb_product_features()
		{
			ob_start();
			?>
			<div class="widget-wrap">
				<div class="mwb_widget-wrapper">
					<ul class="mwb_widget">
						<?php
						$features = get_terms(array(
							'taxonomy' => 'product_features',
							'hide_empty' => true,
						));
						$hierarchy = _get_term_hierarchy('product_features');

						foreach ($features as $feature) {
							if ($feature->parent) {
								continue;
							}
						?>
							<li class="">
								<span>
									<input id="checkbox_<?php echo $feature->term_id; ?>" class="checkbox_<?php echo $feature->taxonomy; ?>" data-term_id="<?php echo $feature->term_id; ?>" data-term_name="<?php echo $feature->name; ?>" data-taxonomy="<?php echo $feature->taxonomy; ?>" data-operator="OR" type="checkbox" name="checkbox_<?php echo $feature->taxonomy; ?>[]" value="<?php echo $feature->slug; ?>">
									<label for="checkbox_<?php echo $feature->term_id; ?>" class="mwb_label_widgets"> <?php echo $feature->name; ?></label>
									<span class="mwb_product_count"> ( <?php echo $feature->count; ?> )</span>
								</span>
								<?php
								if (isset($hierarchy[$feature->term_id])) {
								?>
									<ul class="mwb_child">
										<?php
										foreach ($hierarchy[$feature->term_id] as $child) {
											$child = get_term($child, "product_features");
										?>
											<li>
												<span>
													<input id="checkbox_<?php echo $child->term_id; ?>" class="checkbox_<?php echo $child->taxonomy; ?>" data-term_id="<?php echo $child->term_id; ?>" data-term_name="<?php echo $child->name; ?>" data-taxonomy="<?php echo $child->taxonomy; ?>" data-operator="OR" type="checkbox" name="checkbox_<?php echo $child->taxonomy; ?>[]" value="<?php echo $child->slug; ?>">
													<label for="checkbox_<?php echo $child->term_id; ?>" class="mwb_label_widgets"> <?php echo $child->name; ?></label>
													<span class="mwb_product_count"> ( <?php echo $child->count; ?> )</span>
												</span>
											</li>
										<?php
										}
										?>
									</ul>
								<?php
								}
								?>
							</li>
						<?php
						}
						?>
					</ul>
				</div>
			</div>
		<?php
			return ob_get_clean();
		}

		public function mwb_product_average_rating_filter()
		{
			ob_start();
		?>
			<div class="widget-wrap">
				<div class="mwb_widget-wrapper">
					<ul class="mwb_widget">
						<?php

						for ($rating = 5; $rating >= 1; $rating--) {

							echo '<li class=""><input type="radio" id="mwb_radio_rating_filter' . $rating . '" name="mwb_radio_rating_filter" value="' . $rating . '" class="mwb_average_rating_filter"><label for="mwb_radio_rating_filter' . $rating . '" ><div class="star-rating"><span style="width:' . ($rating * 20) . '%">' . sprintf(__('%s out of 5', 'woocommerce'), $rating) . '</span></div></label></li>';
						}
						?>
					</ul>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		public function loop_columns()
		{
			if (is_archive() && !is_shop()) {
				return 4; // 4 products per row
			}
			return 3; // 2 products per row
		}

		public function mwb_woocommerce_cart_coupon()
		{
			$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
			$mwb_data = json_decode($mwb_microservice_notification_json, true);
			$mwb_enable_cart_coupon_show = isset($mwb_data['mwb_enable_cart_coupon_show']) ? $mwb_data['mwb_enable_cart_coupon_show'] : "";
			if (isset($mwb_enable_cart_coupon_show) && !empty($mwb_enable_cart_coupon_show)) {
				echo '<button type="button" class="button view_coupons" id="view_coupons" name="view_coupons">View coupon</button>';
			?>
				<div class="mwb_coupon_wrapper">
					<div class="mwb_coupon_wrapper_fields">
						<div class="mwb_popup_close_wrap">
							<span class="mwb_popup_close">&times;</span>
						</div>
						<h2>Select Coupons from the List</h2>
						<?php
						global $woocommerce;
						$cart_url = wc_get_cart_url();

						$mwb_show_coupon_json = get_option('mwb_microservice_notification', false);
						$mwb_data = json_decode($mwb_show_coupon_json, true);
						$mwb_show_coupon = isset($mwb_data['show_coupon']) ? $mwb_data['show_coupon'] : "";
						?>
						<ul class='show_coupon'>
							<form id="dummy">
							</form>
							<?php
							foreach ($mwb_show_coupon as $coupon) {
								$c = new WC_Coupon($coupon);

							?>
								<li>
									<div class="mwb_cart_coupon_cc">
										<h3><span><i class="fas fa-cut"></i></span> <?php echo $c->code; ?></h3>
									</div>
									<div class="mwb_cart_coupon_ccc">
										<form action='<?php echo $cart_url; ?>' method='post'>
											<input type='hidden' name='coupon_code' class='input-text' id='coupon_code' value='<?php echo $c->code; ?>'>
											<button type='submit' class='button' name='apply_coupon' value='Apply coupon'>Apply</button>
										</form>
									</div>
									<p><?php echo $c->get_description(); ?> </p>
								</li>
							<?php
							}
							?>
						</ul>
					</div>
				</div>
			<?php
			}
		}


		public function mwb_genesis_footer_common()
		{
			?>
			<div class="mwb_guarantee_wrapper">
				<div class="wrap">
					<ul>
						<li>
							<i class="fa fa-check-circle fa-3x"></i>
							<span class="nav-text"> <strong>GDPR</strong> Compliance</span>
						</li>
						<li>
							<i class="fa fa-life-ring fa-3x"></i>
							<span class="nav-text"> 24/7 <strong>Instant Support</strong></span>
						</li>
						<li>
							<i class="fa fa-lock fa-3x"></i><span class="nav-text"> <strong>Safe &amp; Secure</strong> online payment</span>
						</li>

					</ul>
				</div>
			</div>
		<?php
		}

		public function mwb_genesis_footer_partnership()
		{
		?>
			<div class="mwb_official_partners">
				<ul>
					<li><a href="https://www.google.com/partners/agency?id=7988966922" target="_blank" rel="noopener noreferrer nofollow"><img src="https://makewebbetter.com/wp-content/uploads/2020/01/google-partner.png" alt="Google Partner"></a></li>
					<li><a href="https://ecosystem.hubspot.com/marketplace/solutions/makewebbetter" target="_blank" rel="noopener noreferrer nofollow"><img src="https://makewebbetter.com/wp-content/uploads/2021/03/hubspot-platinum-badge-footer.png" alt="Hubspot Platinum Partner Logo"></a></li>
					<li><img src="https://makewebbetter.com/wp-content/uploads/2019/05/dokan-partner.png" alt="Dokan Partner Logo"></li>
				</ul>
			</div>
			<?php
		}

		public function mwb_woocommerce_check_cart_items()
		{

			if (WC()->cart->cart_contents_count != 0) {
				foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
					$product = $values['data'];
					$product_ids[] = $product->get_id();
				}

				$hubspot_addon = array_intersect($product_ids, array(4055, 4050, 4087, 6684));
				$hubspot_product = array_intersect($product_ids, array(10878, 10877, 10876));
				$hubspotalladdon = false;
				if (sizeof($hubspot_addon) == 4) {
				} else {
					$hubspotalladdon = true;
				}

				if (is_cart()) {

					if (sizeof($hubspot_product) > 0) {
						if ($hubspotalladdon) {
							ob_start();
			?>
							<a href="<?php echo wc_get_checkout_url(); ?>?hubspot=offer" class="button wc-forward">GET IT NOW</a>
				<?php
							$button = ob_get_clean();
							$finalmessage = __('Get Hubspot WooCommerce Integration Pro with All Hubspot Addons on Special Discount. ', 'woocommerce') . $button;
							wc_add_notice($finalmessage);
						}
					}
				}

				// $applied_coupon = WC()->cart->get_applied_coupons();

				// if(sizeof($applied_coupon) == 0)
				// {
				// 	WC()->cart->apply_coupon( "festive30" );
				// }
			}
		}

		public function mwb_woocommerce_terms_is_checked_default($terms_is_checked)
		{
			return true;
		}

		public function mwb_genesis_post_info($metadata)
		{
			$authoremail = get_the_author_meta('user_email');
			$authoravatar = get_avatar($authoremail);
			$metadata = '[post_date] ' . __('by', 'genesis') . ' [post_author_posts_link]';
			return $authoravatar . $metadata;
		}

		public function mwb_gtm4wp_get_the_gtm_tag($_gtm_tag)
		{
			return "";
		}

		public function mwb_woocommerce_login_form_start()
		{
			if (is_checkout()) {
				echo "<label class='mwb_close_popup'>x</label>";
			}
		}

		public function mwb_woocommerce_before_checkout_billing_form()
		{
			if (is_user_logged_in() || 'no' === get_option('woocommerce_enable_checkout_login_reminder')) {
				?>
				<div class="mwb_billing_details_heading">
					<h3><?php _e('Billing details', 'woocommerce'); ?></h3>
				</div>
			<?php
			} else {
				$info_message  = apply_filters('woocommerce_checkout_login_message', __('Returning customer?', 'woocommerce'));
				$info_message .= ' <a href="javascript:void(0);" class="mwbshowlogin">' . __('Click here to login', 'woocommerce') . '</a>';
			?>
				<div class="mwb_billing_details_heading">
					<h3><?php _e('Billing details', 'woocommerce'); ?></h3>

					<p class='mwb_login_link mwb_billing_details_content'><?php echo $info_message; ?></p>
				</div>
			<?php
			}
		}

		public function mwb_woocommerce_checkout_login_form()
		{

			if (is_user_logged_in() || 'no' === get_option('woocommerce_enable_checkout_login_reminder')) {
				return;
			}

			?>
			<div class="mwb_login_form_overlay"></div>
			<div class="mwb_login_form">
				<?php
				woocommerce_login_form(
					array(
						'message'  => __('If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'woocommerce'),
						'redirect' => wc_get_page_permalink('checkout'),
						'hidden'   => false,
					)
				);
				?>
			</div>
			<?php
		}

		public function mwb_woocommerce_before_checkout_form()
		{

			if (isset($_POST['login'])) {
				$user_id = get_current_user_id();
				if ($user_id == 0) {
			?>
					<div class="mwb_checkout_coupon_notice mwb_login_warning_notice">
						<p>Invalid Username Or Password</p>
					</div>
			<?php
				}
			}

			// 			$applied_coupon = WC()->cart->get_applied_coupons();

			// 			if(sizeof($applied_coupon) == 0)
			// 			{
			?>
			<!--<div class="mwb_checkout_coupon_notice">-->
			<!--	<p>Grab FLAT <strong>15%</strong> OFF*, applicable Only on Multi-Site license products | Use Coupon Code: <strong>INBOUND20</strong></p>-->
			<!--</div>-->
			<?php
			// 			}
			?>
			<!--<div class="mwb_checkout_brand">-->
			<!--	<ul>			-->
			<!--		<li><i class="fa fa-lock" aria-hidden="true"></i> Safe &amp; Secure</li>-->
			<!--		<li><i class="fas fa-pencil-alt" aria-hidden="true"></i> Regular Update</li>-->
			<!--		<li><i class="fa fa-headphones" aria-hidden="true"></i> Phone and Email support</li>-->
			<!--		<li><i class="fa fa-check-circle" aria-hidden="true"></i> GDPR Compliance</li>-->
			<!--	</ul>-->
			<!--</div>-->
			<!-- <div class="mwb-checkout-page-cart-option">
				<a href="<?php //echo home_url();
							?>/cart/" class="mwb-checkout-page-view-cart button"><i class="fa fa-shopping-cart"></i> View Cart</a>
			</div> -->
		<?php
		}

		public function mwb_remove_password_strength()
		{
			wp_dequeue_script('wc-password-strength-meter');
		}

		public function mwb_woocommerce_review_order_after_submit()
		{
		?>
			<p> <i class="fa fa-lock"></i><strong> Safe &amp; Secure</strong></p>
			<p><strong>GDPR Compliance</strong> </p>
		<?php
		}

		public function mwb_woocommerce_checkout_coupon_form()
		{
			if (!wc_coupons_enabled()) {
				return;
			}
			$subtotal = WC()->cart->get_subtotal();
			if ($subtotal == 0) {
				return;
			}
			wc_print_notices();
		?>

			<div class="checkout_coupon">
				<!-- <h3 class="mwb_checkout_offer_heading mwb_checkout_accordian_heading"><?php //_e( 'Offer', 'woocommerce' );
																							?></h3> -->
				<!-- <div class="mwb_checkout_offer_content"> -->
				<div id="mwb_checkout_response"></div>
				<p><strong>Got a coupon? Score! Redeem it here.</strong></p>
				<?php
				// 	$applied_coupon = WC()->cart->get_applied_coupons();

				// 	if(sizeof($applied_coupon) == 0)
				// 	{
				?>
				<!--<p style="font-size: 14px;font-family: 'Nunito Sans';padding-left: 10px;border-left: 3px solid #00688b;">Get <span style="background: #e6e655;font-family: 'Nunito Sans-SemiBold';">Flat 15% OFF*</span> applicable only on Multi-Site license products | Use Coupon: <span style="background: #e6e655;font-family: 'Nunito Sans-SemiBold';"><strong>INBOUND20</strong></span></p>-->
				<?php
				// 	}
				?>
				<p class="form-row form-row-first">
					<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" id="coupon_code" value="" />
				</p>

				<p class="form-row form-row-last">
					<input type="button" class="button" id="mwb_apply_coupon" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
				</p>

				<div class="clear"></div>
				<!-- <p>
						<input type="button" class="button border-radius-rounded" id="mwb_next" name="next2" value="<?php //esc_attr_e( 'Next Step', 'woocommerce' );
																													?>">
					</p> -->
				<!-- </div> -->
			</div>
		<?php
		}

		public function mwb_apply_coupon_code()
		{
			if (!empty($_POST['coupon_code'])) {
				WC()->cart->add_discount(sanitize_text_field($_POST['coupon_code']));
			} else {
				wc_add_notice(WC_Coupon::get_generic_coupon_error(WC_Coupon::E_WC_COUPON_PLEASE_ENTER), 'error');
			}

			wc_print_notices();
			wp_die();
		}

		public function mwb_get_review_from_codecanyon($item_id)
		{
			if (empty($item_id)) {
				return '';
			}
			$public = true;
			if (isset($item_id['public'])) {
				$public = false;
			}

			$ch = curl_init();
			$personal_token = "AG3Prk4Gx0oBEPsteVlgvJllxeezQCfC";
			$header = array();
			$header[] = 'Authorization: Bearer ' . $personal_token;
			$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
			$header[] = 'timeout: 20';
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_URL, "https://api.envato.com/v3/market/catalog/item?id=" . $item_id['item_id']);
			curl_setopt($ch, CURLOPT_POST, 0);

			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$server_output = curl_exec($ch);

			if ($errno = curl_errno($ch)) {
				$error_message = curl_strerror($errno);
				echo "cURL error ({$errno}):\n {$error_message}";
			}

			curl_close($ch);

			$validatedData = json_decode($server_output, true);

			if ($public) {
				$product_url = $validatedData['url'];
				$product_itemid = $item_id['item_id'];
				$product_review = str_replace($product_itemid, "reviews/$product_itemid", $product_url);

				if ($validatedData['rating'] < 3) {
					$validatedData['rating'] = 5;
				}

				$reviewhtml = '<div class="woocommerce-product-rating mwb_codecanyon_rating_count">' . wc_get_rating_html($validatedData['rating']);
				$reviewhtml .= ' <a target="_blank" href="' . $product_review . '">(' . $validatedData['rating_count'] . ' customer review)</a></div>';
				return $reviewhtml;
			}

			return $validatedData;
		}

		public function mwb_shop_woocommerce_loop_add_to_cart_link($html, $product, $args = array())
		{
			if ($product) {
				if (!is_product()) {
					$product_type = $product->get_type();
					if ($product_type == "variable") {
						$product_child = $product->get_visible_children();
						if (isset($product_child[0])) {
							$product_id = $product_child[0];
						}
						$html = '<a href="' . $product->add_to_cart_url() . '" data-quantity="1" data-product_id="' . $product_id . '" data-product_sku="" class="button product_type_variable add_to_cart_button ajax_add_to_cart">Add to cart</a>';
					}
				}
			}
			return $html;
		}

		//filter to remove meta from the order details at thankyou page
		public function mwb_woocommerce_order_item_get_formatted_meta_data($formatted_meta)
		{
			foreach ($formatted_meta as $meta_id => $meta) {
				if ($meta->key == 'Item Id') {
					$meta->hidden = true;
				}
				if ($meta->key == 'Support Type') {
					$meta->hidden = true;
				}
				if (!empty($meta->hidden)) {
					unset($formatted_meta[$meta_id]);
					continue;
				}
			}
			return $formatted_meta;
		}

		//filter to remove meta from the cart items in cart
		public function mwb_woocommerce_get_item_data($item_data, $cart_item)
		{
			foreach ($item_data as $key => $data) {
				if ($data['name'] == 'Item Id') {
					$data['hidden'] = true;
				}
				if ($data['name'] == 'Support Type') {
					$data['hidden'] = true;
				}
				// Set hidden to true to not display meta on cart.
				if (!empty($data['hidden'])) {
					unset($item_data[$key]);
					continue;
				}
				$item_data[$key]['key']     = !empty($data['key']) ? $data['key'] : $data['name'];
				$item_data[$key]['display'] = !empty($data['display']) ? $data['display'] : $data['value'];
			}
			return $item_data;
		}

		//function for adding support renewal/extend button in download section
		public function mwb_support_renew_extend($download)
		{
			$product_id = $download['product_id'];
			$product = wc_get_product($product_id);
			// if($product->is_type('simple'))
			// {
			// 	$enable_support = get_post_meta($product_id, 'mwb_enable_support_option', true);
			// }
			// else
			// {
			// 	$data = $product->get_data();
			// 	$parent_id = $data['parent_id'];

			// 	$enable_support = get_post_meta($parent_id, 'mwb_enable_support_option', true);
			// }

			// if(isset($enable_support) && !empty($enable_support) && $enable_support[0] == 'true')
			// {
			// 	$product_name = $download['product_name'];
			// 	$order = new WC_Order($download['order_id']);
			// 	$order_date = $order->order_date;
			// 	$items = $order->get_items();
			// 	foreach($items as $item_id => $item)
			// 	{
			// 		$item_data = $item->get_data();
			// 		if($item_data['variation_id'] == $product_id)
			// 		{
			// 			$items_id = $item_id;
			// 		}
			// 	}

			// 	$support_renew_time = get_post_meta($items_id,'mwb_support_renew_time',true);
			// 	if(empty($support_renew_time))
			// 	{
			// 		$mwb_after_month = strtotime('+6 months', strtotime($order_date));
			// 	}
			// 	else
			// 	{
			// 		$mwb_after_month = $support_renew_time;
			// 	}
			// 	$current_timestamp = time();
			// 	foreach ( wc_get_account_downloads_columns() as $column_id => $column_name )
			// 	{
			// 		if($column_id == 'download-support')
			// 		{
			// 			if($mwb_after_month > $current_timestamp)
			// 			{
			// 				echo '<form method="post" action="'.wc_get_cart_url().'" id="mwb_support_license">
			// 				<input type="hidden" name="quantity" value="1">
			// 				<input type="hidden" name="product_id" value="'.$product_id.'">
			// 				<input type="hidden" name="items_id" value="'.$items_id.'">
			// 				<input type="hidden" name="product_name" value="'.$product_name.'">
			// 				<input type="hidden" name="order_id" value="'.$download['order_id'].'">
			// 				<input type="hidden" name="add-to-cart" value="24151">
			// 				<input type="submit" class="woocommerce-MyAccount-downloads-file button alt" name="support_license" value="Extend"></form>';
			// 			}
			// 			else
			// 			{
			// 				echo '<form method="post" action="'.wc_get_cart_url().'" id="mwb_support_license">
			// 				<input type="hidden" name="quantity" value="1">
			// 				<input type="hidden" name="product_id" value="'.$product_id.'">
			// 				<input type="hidden" name="items_id" value="'.$items_id.'">
			// 				<input type="hidden" name="product_name" value="'.$product_name.'">
			// 				<input type="hidden" name="order_id" value="'.$download['order_id'].'">
			// 				<input type="hidden" name="add-to-cart" value="24151">
			// 				<input type="submit" class="woocommerce-MyAccount-downloads-file button alt" name="support_license" value="Renew"></form>';
			// 			}
			// 		}
			// 	}
			// }
			// else
			// {
			echo '<div style="text-align:center;"><span>-</span></div>';
			// }
		}


		//function for adding support renewal/extend expiry date in download section
		public function mwb_support_renew_extend_expiry_date($download)
		{
			$product_id = $download['product_id'];
			$product = wc_get_product($product_id);
			// if($product->is_type('simple'))
			// {
			// 	$enable_support = get_post_meta($product_id, 'mwb_enable_support_option', true);
			// }
			// else
			// {
			// 	$data = $product->get_data();
			// 	$parent_id = $data['parent_id'];

			// 	$enable_support = get_post_meta($parent_id, 'mwb_enable_support_option', true);
			// }

			// if(isset($enable_support) && !empty($enable_support) && $enable_support[0] == 'true')
			// {
			$order = new WC_Order($download['order_id']);
			$order_date = $order->order_date;
			$items = $order->get_items();
			foreach ($items as $item_id => $item) {
				$item_data = $item->get_data();
				if ($item_data['variation_id'] == $product_id) {
					$items_id = $item_id;
				}
			}

			$support_updates_time = get_post_meta($items_id, 'mwb_support_updates_timestamp', true);
			if (empty($support_updates_time)) {
				$mwb_updated_timestamp = strtotime('+1 year', strtotime($order_date));
				update_post_meta($items_id, 'mwb_support_updates_timestamp', $mwb_updated_timestamp);
			} else {
				$mwb_updated_timestamp = $support_updates_time;
			}

			foreach (wc_get_account_downloads_columns() as $column_id => $column_name) {
				if ($column_id == 'support-expiry-date') {

					echo date('M d, Y', $mwb_updated_timestamp);
				}
			}
			// }
			// else
			// {
			// 	echo '<div style="text-align:center;"><span>-</span></div>';
			// }
		}


		//Show heading on plan page's banner
		public function mwb_genesis_post_title_output($output, $wrap, $title)
		{
			if (is_page("plans")) {
				$output = "<div class='mwb-plan-header-title'><h3>We Also Offer....</h3>" . $output . "</div>";
			}
			return $output;
		}

		//Showing min price in variable products on shop page
		public function mwb_woocommerce_variable_price_html($price, $product)
		{
			$product_child = $product->get_visible_children();

			if (isset($product_child[0])) {
				$product_id = $product_child[0];
				$variable_product = new WC_Product_Variation($product_id);
				$product_price = $variable_product->get_price();
			}

			if (empty($product_price)) {
				$prices = $product->get_variation_prices(true);
				$min_price     = current($prices['price']);
				$max_price     = end($prices['price']);
				$min_reg_price = current($prices['regular_price']);
				$max_reg_price = end($prices['regular_price']);
				$product_price = $min_price;
			}

			$price = wc_price($product_price);
			return $price;
		}

		//Showing Get quote in place of read more on shop page for products going to release
		public function mwb_woocommerce_product_add_to_cart_text($text, $product)
		{
			if ($product->is_purchasable() && $product->is_in_stock()) {
				$product_id = $product->get_ID();
				$enable_free_download = get_post_meta($product_id, 'mwb_enable_free_product_setting', true);
				if (isset($enable_free_download) && !empty($enable_free_download) && $enable_free_download[0] == 'true') {
					$text = __('Get Now', 'woocommerce');
				} else {
					$text = __('Add to cart', 'woocommerce');
				}
			} else {
				$text = __('GET QUOTE', 'woocommerce');
			}
			return $text;
		}

		//Adding class ajax_add_to_cart on variable products
		public function mwb_woocommerce_loop_add_to_cart_args($args, $product)
		{
			$product_type = $product->get_type();
			if ($product_type == "variable") {
				$class = $args['class'];
				$class .= " ajax_add_to_cart";
				$args['class'] = $class;
			}
			return $args;
		}

		//Adding Support column in download section of myaccount page

		public function mwb_woocommerce_account_downloads_columns($coloumn)
		{
			unset($coloumn['download-remaining']);
			unset($coloumn['download-expires']);
			$coloumn['download-support'] = "Support";
			$coloumn['support-expiry-date'] = "Support Expiry Date";
			return $coloumn;
		}


		public function mwb_woocommerce_after_calculate_totals()
		{
			if (!(isset($_REQUEST['apply_coupon']) || isset($_REQUEST['remove_coupon']) || isset($_REQUEST['remove_item']) || isset($_REQUEST['undo_item']) || isset($_REQUEST['update_cart']) || isset($_REQUEST['proceed']))) {
				return;
			}

			$finalusercart = get_option("mwb_guest_abondand_cart", array());
			$current_user_id = get_current_user_id();

			if ($current_user_id == 0) {
				$this->mwb_guest_abondand_cart_callback($finalusercart);
			} else {
				$this->mwb_registered_abondand_cart_callback($finalusercart);
			}
		}

		//Adding google search shortcode on 404 page
		public function mwb_genesis_google_search()
		{
			if (is_404()) {
				echo "<h2>Search With Google</h2>";
				echo do_shortcode('[wp_google_search]');
			}
		}

		//Shortcode for google search
		public function wp_google_search_shortcode($atts)
		{

			$gcse_code = 'search';

			$content  = '<div class="wgs_wrapper" id="wgs_wrapper_id">';

			$content .= '<div class="gcse-' . $gcse_code . '" data-linktarget="_blank"></div>';

			$content .= '</div>';

			return $content;
		}

		//Shortcode for template preview option on template description page
		public function mwb_template_previews($atts)
		{

			global $product;
			$product_id = get_the_ID();

			$preview_link = get_post_meta($product_id, 'mwb_plugin_front_end_demo', true);
			$product_link = get_permalink($product_id);

			$html = '<a href="' . $preview_link . '" target="_blank"><i class="fa fa-eye fa-2x" aria-hidden="true"></i></a><a href="' . $product_link . '?add-to-cart=' . $product_id . '"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a>';

			return $html;
		}

		public function mwb_mautic_template_previews($atts)
		{

			global $product;
			$product_id = get_the_ID();

			$video_link = get_post_meta($product_id, 'mwb_youtube_video_link', true);
			$product_link = get_permalink($product_id);

			$html = '<a href="' . $video_link . '" target="_blank"><i class="fa fa-eye fa-2x" aria-hidden="true"></i></a><a href="' . $product_link . '?add-to-cart=' . $product_id . '"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a>';

			return $html;
		}

		//Shortcode for showing tags
		public function mwb_tags()
		{
			$terms = get_the_terms(get_the_ID(), 'product_tag');

			$separator = ' ';
			if (!empty($terms)) {
				foreach ($terms as $term) {
					$output .= '<a href="' . get_tag_link($term->term_id) . '"><span class="mwb_thm_tmp_tags">' . $term->name . '</span></a>' . $separator;
				}
				return trim($output, $separator);
			}
		}

		//Shortcode for showing related products
		public function mwb_related_product()
		{
			ob_start();
			$args = array(
				'posts_per_page' => 3,
				'columns'        => 3,
				'orderby'        => 'rand', // @codingStandardsIgnoreLine.
			);

			woocommerce_related_products(apply_filters('woocommerce_output_related_products_args', $args));
			return ob_get_clean();
		}

		//Showing related products when no product found
		public function mwb_wc_no_products_found()
		{
			// wc_get_template( 'loop/no-products-found.php' );
			do_action('woocommerce_before_shop_loop');
		?>
			<div class="mwb-no-products-wrapper">
				<div class="mwb-empty">
					<h2>No Products found on your search criteria</h2>
				</div>
				<div class="mwb-related">
					<p>You can also choose products from our featured product list.</p>
				</div>
			</div>
			<?php
			$tax_query = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => array('services', 'theme', 'template', 'snippet', 'icon', 'mautic-templates'),
				'operator' => 'NOT IN'
			);


			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 6,
				'tax_query' => array($tax_query),
				'order'   => 'desc',
				'orderby'  => 'meta_value_num',
				'meta_key' => 'total_sales'
			);

			$query = new WP_Query($args);
			if ($query->have_posts()) {

				woocommerce_product_loop_start();

				while ($query->have_posts()) {
					$query->the_post();

					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}
				woocommerce_product_loop_end();
			}
		}

		//Showing Add to cart button and buynow button
		public function mwb_woocommerce_loop_add_to_cart_link()
		{
			global $product;
			if (isset($product) && !empty($product)) {
				$pagelink = $product->get_permalink();
				$defaults = array(
					'quantity' => 1,
					'class'    => implode(' ', array_filter(array(
						'button',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
					))),
				);
				$class = $defaults['class'];
				echo apply_filters(
					'woocommerce_loop_add_to_cart_link',
					sprintf(
						'<a href="%s" class="%s">%s</a>',
						esc_url($pagelink),
						esc_attr(isset($class) ? $class : 'button'),
						esc_html('View Detail')
					),
					$product
				);

				// $product_id = $product->get_ID();
				// $product_title = $product->get_title();

				// $args = array();
				// $defaults = array(
				// 	'quantity' => 1,
				// 	'class'    => implode( ' ', array_filter( array(
				// 		'button',
				// 		'product_type_' . $product->get_type(),
				// 		$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				// 		$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
				// 		) ) ),
				// 	);
				// $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

				// $add_to_cart_text = $product->add_to_cart_text();
				// if(isset($atts['label']))
				// {
				// 	$add_to_cart_text = $atts['label'];
				// }

				// $quantity = $args['quantity'];
				// $class = $args['class'];
				// $checkouturl = wc_get_checkout_url();

				// $product_id = $product->get_id();
				// $add_to_cart_url = $product->add_to_cart_url();
				// if(isset($atts['id']))
				// {
				// 	$product_id = $atts['id'];
				// 	$add_to_cart_url = get_permalink( $product_id );
				// 	$add_to_cart_url = add_query_arg( 'add-to-cart', $product_id, $add_to_cart_url );
				// }

				// $codecanyon_link = get_post_meta($product_id, 'mwb_codcanyon_product_link', true);
				// $mwb_codecanyon_id = get_post_meta($product_id, 'mwb_codecanyon_product_id', true);
				// $mwb_codecanyon_link = get_post_meta($product_id, 'mwb_codcanyon_product_link', true);


				// $enable_free_download = get_post_meta( $product_id, 'mwb_enable_free_product_setting', true);


				// if(isset($codecanyon_link) && !empty($codecanyon_link))
				// {
			?>
				<!-- <form class="cart"  action="https://codecanyon.net/cart/add/<?php //echo $mwb_codecanyon_id;
																					?>" accept-charset="UTF-8" method="post" id="mwb_buy_now_form">
						<input name="utf8" type="hidden" value="✓">
						<input type="hidden" value="regular" name="license">
						<input type="hidden" name="support" value="bundle_6month">
						<button type="submit" name="add-to-cart" value="<?php //echo esc_attr( $product->get_id() );
																		?>" class="button product_type_simple mwbbuynow">Buy Now</button>
					</form> -->
			<?php

				// // echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				// // sprintf( '<a href="'.$codecanyon_link.'" target="_blank" class="button product_type_simple mwbbuynow">BUY NOW</a>' ) , $product );

				// }
				// else
				// {
				// 	if(isset($enable_free_download) && !empty($enable_free_download) && $enable_free_download[0] == 'true')
				// 	{
				// 		if($product_id == 18566)
				// 		{
				// 			$product_url = get_permalink( $product_id );
				// 			echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				// 				sprintf( '<a href="'.$product_url.'" target="_blank" class="button product_type_simple mwbbuynow">Get Now</a>' ) , $product );
				// 		}
				// 		else
				// 		{
				// 			echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				// 				sprintf( '<a href="'.$checkouturl.'?add-to-cart='.$product_id.'" target="_blank" class="button product_type_simple mwbbuynow">Get Now</a>' ) , $product );
				// 		}

				// 	}
				// 	else
				// 	{
				// echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				// 	sprintf( '<a href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
				// 		esc_url( $add_to_cart_url ),
				// 		esc_attr( isset( $quantity ) ? $quantity : 1 ),
				// 		esc_attr( $product_id ),
				// 		esc_attr( $product->get_sku() ),
				// 		esc_attr( isset( $class ) ? $class : 'button' ),
				// 		esc_html( $add_to_cart_text )
				// 		) ,
				// 	$product );
				// 	}
				// }
			}
		}

		//Adding search box on shop page
		public function woocommerce_search_box()
		{
			if (is_shop()) {
				get_product_search_form();
			}
		}


		/**
		 * Checking for Abondand Coupon is Used or not
		 */

		public function mwb_woocommerce_thankyou_abondand_coupon($order_id)
		{
			if (!$order_id) {
				return;
			}

			$order = new WC_Order($order_id);
			$used_coupons = $order->get_items('coupon');
			foreach ($used_coupons as $key => $coupon) {
				$coupon_code = $coupon->get_code();
				$the_coupon = new WC_Coupon($coupon_code);
				$new_coupon_id = $the_coupon->get_id();
				$mwb_abondand_coupon = get_post_meta($new_coupon_id, "mwb_abondand_coupon", true);
				if ($mwb_abondand_coupon == "yes") {
					$user_id = get_current_user_id();
					if ($user_id > 0) {
						update_user_meta($user_id, 'mwb_abandoned_coupon_used', true);
					}
				}
			}
		}

		/*
		 * Recover the cart
		 */

		public function mwb_wp_loaded()
		{
			/*
			if ( ( isset( $_REQUEST['apply_coupon'] ) || isset( $_REQUEST['remove_coupon'] ) || isset( $_REQUEST['remove_item'] ) || isset( $_REQUEST['undo_item'] ) || isset( $_REQUEST['update_cart'] ) || isset( $_REQUEST['proceed'] ) ) ) {

				return;
			}
			else
			*/
			if (isset($_GET['acmail'])) {
				$email = $_GET['acmail'];
				$_SESSION['mwb_lead_email'] = $email;
				$finalusercart = get_option("mwb_guest_abondand_cart", array());

				if (isset($finalusercart[$email])) {
					global $woocommerce;
					$woocommerce->cart->empty_cart();
					$saved_cart = $finalusercart[$email];
					WC()->session->cart = $saved_cart['cart'];
					$finalusercart[$email]['recover'] = true;
					update_option("mwb_guest_abondand_cart", $finalusercart);
					wp_redirect(wc_get_cart_url());
					die;
				}
			}
		}

		//Showing notification bar for GDPR on footer
		public function mwb_notification_bar()
		{
			echo '<div class="mwb-privacy-wrapper">
					<div class="mwb-privacy-content-wrapper">
						<div class="mwb-privacy-content">
							<p>Important: We use cookies to ensure that we give you the best experience on our website.
							</p>
						</div>
						<div class="mwb-right">
							<button class="mwb-agreement" type="button">I Agree</button>
						</div>
					</div>
				</div>
			</div>';
		}

		public function mwb_woocommerce_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
		{
			$finalusercart = get_option("mwb_guest_abondand_cart", array());
			$current_user_id = get_current_user_id();

			if ($current_user_id == 0) {
				$this->mwb_guest_abondand_cart_callback($finalusercart);
			} else {
				$this->mwb_registered_abondand_cart_callback($finalusercart);
			}

			return $cart_item_key;
		}

		//Registered abandoned cart callback
		public function mwb_registered_abondand_cart_callback($finalusercart)
		{
			$current_user = wp_get_current_user();
			$user_email = $current_user->user_email;
			$finalusercart = get_option("mwb_guest_abondand_cart", array());

			if (isset($_SESSION['mwb_lead_email']) && !empty($_SESSION['mwb_lead_email'])) {
				$useremail = $_SESSION['mwb_lead_email'];
				unset($finalusercart[$useremail]);
				update_option("mwb_guest_abondand_cart", $finalusercart);
			}

			$customer_abondand_cart_coupon = get_user_meta($current_user_id, '_mwb_abondand_cart_coupon', true);

			$couponamount = 15;
			if (empty($customer_abondand_cart_coupon)) {
				$mwb_couponnumber = $this->mwb_wgm_coupon_generator();
				if ($this->mwb_create_abondand_coupon($mwb_couponnumber, $couponamount, $user_email)) {
					update_user_meta($current_user_id, '_mwb_abondand_cart_coupon', $mwb_couponnumber);
				}
			} else {
				$this->mwb_update_abondand_coupon($customer_abondand_cart_coupon, $couponamount, $user_email);
			}
		}

		//Guest abandoned cart callback
		public function mwb_guest_abondand_cart_callback($finalusercart)
		{
			global $woocommerce;

			if (isset($woocommerce->cart->cart_contents) && !empty($woocommerce->cart->cart_contents)) {
				$usercart = $woocommerce->cart->cart_contents;
				$mwb_cart_subtotal = $woocommerce->cart->get_subtotal();
				$mwb_discount_amount = ($mwb_cart_subtotal * 15) / 100;
				$mwb_cart_grandtotal = $mwb_cart_subtotal - $mwb_discount_amount;

				foreach ($usercart as $key => $value) {
					unset($value['data']);
					if (isset($usercart[$key])) {
						$usercart[$key] = $value;
					}
				}

				if (isset($_SESSION['mwb_lead_email']) && !empty($_SESSION['mwb_lead_email'])) {
					$useremail = $_SESSION['mwb_lead_email'];

					$finalusercart[$useremail]['cart'] = $usercart;
					if (!isset($finalusercart[$useremail]['time'])) {
						$finalusercart[$useremail]['time'] = time();
					}
					$finalusercart[$useremail]['status'] = false;
					$couponamount = 15;
					if (isset($finalusercart[$useremail]['abandoned_coupon']) && !empty($finalusercart[$useremail]['abandoned_coupon'])) {
						$mwb_couponnumber = $finalusercart[$useremail]['abandoned_coupon'];
						$this->mwb_update_abondand_coupon($mwb_couponnumber, $couponamount, $useremail);
					} else {
						$mwb_couponnumber = $this->mwb_wgm_coupon_generator();
						if ($this->mwb_create_abondand_coupon($mwb_couponnumber, $couponamount, $useremail)) {
							$finalusercart[$useremail]['abandoned_coupon'] = $mwb_couponnumber;
						}
					}

					if (!isset($finalusercart[$useremail]['abandoned_coupon'])) {
						$finalusercart[$useremail]['abandoned_coupon'] = $mwb_couponnumber;
					}

					$finalusercart[$useremail]['sub_total'] = $mwb_cart_subtotal;
					$finalusercart[$useremail]['discount_amount'] = $mwb_discount_amount;
					$finalusercart[$useremail]['grand_total'] = $mwb_cart_grandtotal;
					if (isset($finalusercart[$useremail]['recover'])) {
						//unset($finalusercart[$useremail]['recover']);
						mwb_update_user_cart($useremail);
						$contact_id = mwb_mautic_contact_id_by_email($useremail);
						$campaign_id = 9;
						$response = mwb_reset_campaign($contact_id, $campaign_id);
					}
					update_option("mwb_guest_abondand_cart", $finalusercart);
				}
			}
		}

		public function ew_product_in_cart($product_id)
		{
			global $woocommerce;

			foreach ($woocommerce->cart->get_cart() as $key => $val) {
				$_product = $val['data'];

				if ($product_id == $_product->id) {
					return true;
				}
			}

			return false;
		}

		//Check Session for guest abandoned cart
		public function mwb_check_session()
		{

			// 			if(isset($_GET['hubspot_bundle']))
			// 			{
			// 				WC()->cart->add_to_cart( 10876 ); //Hubspot Pro
			// 				WC()->cart->add_to_cart( 4087 );  //Field to field Sync
			// 				WC()->cart->add_to_cart( 6684 );  //Deal Per Order
			// 				WC()->cart->add_to_cart( 4050 );  //Abandant Cart
			// 				WC()->cart->add_to_cart( 4055 );  //Dynamic Coupon Code
			// 				WC()->cart->add_to_cart( 19341 );  //Hubspot Deals For WooCommerce Memberships

			// 				WC()->cart->apply_coupon( "hubspotkit" );
			// 			}

			if (isset($_GET['hubspot'])) {
				if ($_GET['hubspot'] == "offer") {
					if (!$this->ew_product_in_cart(4087)) {
						WC()->cart->add_to_cart(4087);	 //Field to field Sync
					}
					if (!$this->ew_product_in_cart(6684)) {
						WC()->cart->add_to_cart(6684);  //Deal Per Order
					}
					if (!$this->ew_product_in_cart(4050)) {
						WC()->cart->add_to_cart(4050);  //Abandant Cart
					}
					if (!$this->ew_product_in_cart(4055)) {
						WC()->cart->add_to_cart(4055);  //Dynamic Coupon Code
					}
					if (!$this->ew_product_in_cart(19341)) {
						WC()->cart->add_to_cart(19341);  //Hubspot Deals For WooCommerce Memberships
					}

					WC()->cart->apply_coupon("hubspotkit");
				}
			}

			if (isset($_GET['addons'])) {
				if ($_GET['addons'] == "offer") {
					if (!$this->ew_product_in_cart(4087)) {
						WC()->cart->add_to_cart(4087);	 //Field to field Sync
					}
					if (!$this->ew_product_in_cart(6684)) {
						WC()->cart->add_to_cart(6684);  //Deal Per Order

					}
					if (!$this->ew_product_in_cart(4050)) {
						WC()->cart->add_to_cart(4050);  //Abandant Cart
					}
					if (!$this->ew_product_in_cart(4055)) {
						WC()->cart->add_to_cart(4055);  //Dynamic Coupon Code
					}
					if (!$this->ew_product_in_cart(19341)) {
						WC()->cart->add_to_cart(19341);  //Hubspot Deals For WooCommerce Memberships
					}

					WC()->cart->apply_coupon("exclusive4");
				}
			}

			if (isset($_GET['bundle'])) {

				if ($_GET['bundle'] == "automation-sale") {
					if (!$this->ew_product_in_cart(24977)) {
						WC()->cart->add_to_cart(24977);	 //Mautic WooCommerce Integration
					}
					if (!$this->ew_product_in_cart(9958)) {
						WC()->cart->add_to_cart(9958);  //One Click Upsell

					}
					if (!$this->ew_product_in_cart(27542)) {
						WC()->cart->add_to_cart(27542);  //Converting Checkout Pages
					}
					if (!$this->ew_product_in_cart(27535)) {
						WC()->cart->add_to_cart(27535);  //Discount Win Wheel
					}
					if (!$this->ew_product_in_cart(27676)) {
						WC()->cart->add_to_cart(27676);  //Advanced woocommerce Reporting
					}

					WC()->cart->apply_coupon("salekit");
				}

				if ($_GET['bundle'] == "ecommerce") {
					if (!$this->ew_product_in_cart(27514)) {
						WC()->cart->add_to_cart(27514);	 //WooCommerce One Click Checkout
					}
					if (!$this->ew_product_in_cart(27542)) {
						WC()->cart->add_to_cart(27542);  //WooCommerce Converting Checkout Pages

					}
					if (!$this->ew_product_in_cart(27732)) {
						WC()->cart->add_to_cart(27732);  //WooCommerce Shipment Tracking
					}
					if (!$this->ew_product_in_cart(27593)) {
						WC()->cart->add_to_cart(27593);  //Post Checkout Offers
					}
					if (!$this->ew_product_in_cart(24461)) {
						WC()->cart->add_to_cart(24461);  //WooCommerce Return Refund And Exchange
					}

					WC()->cart->apply_coupon("ecommerce40");
				}

				if ($_GET['bundle'] == "upsell-kit") {

					if (!$this->ew_product_in_cart(9958)) {
						WC()->cart->add_to_cart(9958);  //One Click Upsell
					}

					if (!$this->ew_product_in_cart(26074)) {
						WC()->cart->add_to_cart(26074);  //Deal Per Order

					}

					WC()->cart->apply_coupon("UPSELLKIT");
				}
			}

			// wp_clear_scheduled_hook( 'mwb_daily_check_trial_reminder' );

			if (!wp_next_scheduled('mwb_daily_check_trial_reminder')) {
				wp_schedule_event(time(), 'daily', 'mwb_daily_check_trial_reminder');
			}

			if (!session_id()) {
				session_start();
			}

			$finalusercart = get_option("mwb_guest_abondand_cart", array());

			$current_user_id = get_current_user_id();

			if ($current_user_id > 0) {
				$finalusercart = get_option("mwb_guest_abondand_cart", array());

				if (isset($_SESSION['mwb_lead_email']) && !empty($_SESSION['mwb_lead_email'])) {
					$useremail = $_SESSION['mwb_lead_email'];
					unset($finalusercart[$useremail]);
					update_option("mwb_guest_abondand_cart", $finalusercart);
				}
			}
			if (isset($finalusercart) && !empty($finalusercart)) {
				foreach ($finalusercart as $email => $usercart) {
					$time = $usercart['time'];
					$now = time();
					$datediff = $now - $time;
					//$daydiff = round($datediff / (60));   // Hour Code for Test
					$daydiff = round($datediff / (60 * 60 * 24));   // Day code for live
					if ($daydiff > 7) {
						mwb_clear_abandoned_data($email);
						unset($finalusercart[$email]);
					}
				}
				update_option("mwb_guest_abondand_cart", $finalusercart);
			}


			if (isset($_GET['mwb-abandoned-cart-data'])) {	//phpinfo();
				// print_r( $_SESSION['mwb_lead_email'] );
				echo "<pre>";
				print_r($finalusercart);
				die;
			}
		}

		//Adding offer notice in email
		public function mwb_email_coupon_offer_notice($order, $sent_to_admin, $plain_text, $email)
		{
			?>
			<p style="margin: 0 0 16px;"><strong>You have Unlocked a Coupon:</strong></p>

			<p style="margin: 0 0 16px;padding: 8px;background-color: #f1dfed;">Use Coupon: <strong style="border: 1px dashed #9c588a;padding: 2px 5px;color: #9c588a;">NEXTSALE</strong> and Get flat 15% off on your next purchase.</p>
			<?php
		}

		//Adding instruction in email for order completion
		public function mwb_woocommerce_email_order_meta($order, $sent_to_admin, $plain_text, $email)
		{

			$subscription_payment = false;
			$hubspot_product = false;
			$items = $order->get_items();

			foreach ($items as $item_key => $item) {
				$item_data = $item->get_data();
				$product_id = $item_data['product_id'];

				$subscription_enable = get_post_meta($product_id, 'mwb_subscription_enable', true);

				if ($subscription_enable == "yes") {
					$hubspot_product = true;

					if (is_object(WC()->session)) {

						$mwb_trial = WC()->session->get('mwb_trail');
						if (isset($mwb_trial) && $mwb_trial == true) {
							$subscription_payment = true;
							// break;
						}
					}
				}
			}

			if ($hubspot_product) {
			?>
				<p style="margin: 0 0 16px;"><strong>Activate Your Business With Extras:</strong></p>
				<ul>
					<li><a href="https://makewebbetter.com/hubspot-cos-development-service/?utm_source=order-email">HubSpot COS Development Service</a> For All Businesses</li>
					<li><a href="https://makewebbetter.com/responsive-hubspot-templates/?utm_source=order-email">Responsive HubSpot Templates</a> To Sell More</li>
				</ul>
			<?php
			}
			?>

			<p style="margin: 0 0 16px;"><strong>Grow Your Business With Our Woocommerce Extensions And Services:</strong></p>
			<ul>
				<li><a href="https://makewebbetter.com/woocommerce-plugins/?utm_source=order-email">WooCommerce Extensions</a> For All Businesses</li>
				<li><a href="https://makewebbetter.com/hubspot-services/?utm_source=order-email">HubSpot Services</a> To Attract, Convert and Delight Your Customers</li>
				<li>Reach New Magnitude With <a href="https://makewebbetter.com/wordpress-woocommerce-solutions/?utm_source=order-email">WordPress & WooCommerce Solutions</a></li>
				<li>Personalized <a href="https://makewebbetter.com/digital-marketing-services/?utm_source=order-email">Digital Marketing Solutions</a> Crafted By Certified Professionals</li>
			</ul>

			<?php
			if ($subscription_payment) {
			?>
				<div class="mwb_price">
					<p><strong>Note:</strong> After trial product time-period is exceed, subscription amount will be automatically deducted.</p>
				</div>
			<?php
			}
			?>

			<p style="margin: 0 0 16px;"><strong>With every paid extension you get:</strong></p>
			<ul>
				<li>Single License</li>
				<li>1 Year Support</li>
				<li>Free Updates</li>
			</ul>

			<p style="margin: 0 0 16px;"><strong>How to Download Purchase extension?</strong></p>

			<p style="margin: 0 0 16px;">In order to download your purchased extension follow the given steps:-</p>
			<ol>
				<li>After purchasing extension Go to My Account, If not logged in, Login to <a href="https://makewebbetter.com/my-account/">MakeWebbetter</a> site.</li>
				<li>After login go to Downloads or visit url: <a href="https://makewebbetter.com/my-account/orders">https://makewebbetter.com/my-account/orders</a></li>
				<li>Here you will see all purchased Order from Makewebbetter Site.</li>
				<li>Go to Order Details page and Download the plugin.</li>
				<li>Copy and Save the License Key.</li>
				<li>Install the extension to your website. For more detail how to install visit here: <a href="https://codex.wordpress.org/Managing_Plugins">https://codex.wordpress.org/Managing_Plugins</a></li>
				<li>Go to plugin setting and Activate the license key.</li>
				<li>After Successful activation of license key you will get the installtion guide of respective plugin in mail. </li>
				<li>Follow the Installtion Guide.</li>
			</ol>

			<p style="margin: 0 0 16px;">Additional <a href="https://makewebbetter.com/terms-and-conditions/">Terms & Conditions</a></p>
		<?php
		}

		//Adding wordpress-Plugins in place of product in breadcrumb on product description page
		public function mwb_genesis_breadcrumb_link($link, $url, $title, $content, $args)
		{
			if ($content == "Products") {
				return $link = MWB_URL . '/woocommerce-plugins/" itemprop="item"><span itemprop="name">Wordpress-Plugins</span></a>';
			}
			return $link;
		}

		//Adding cart icon and count of items
		function mwb_check_cart()
		{
			global $woocommerce;
			$cart_url = wc_get_cart_url();
			$cart_contents_count = $woocommerce->cart->cart_contents_count;
			$cart_total = $woocommerce->cart->cart_contents_total;

			//if ($cart_contents_count > 0) {

			$mobilelink = '<a href="' . $cart_url . '" itemprop="url"><i class="fa fa-shopping-cart"></i> <span>' . $cart_contents_count . ' items -</span> <span itemprop="name">' . wc_price($cart_total) . '</span></a>';

			//}else{
			//    $mobilelink = "";
			//  }
			$response['html'] = $mobilelink;
			echo json_encode($response);
			die;
		}

		//Set cookie for GDPR notification bar
		function mwb_gdpr_privacy_bar()
		{
			$cookie_name = "mwb_gdpr_privacy_bar";
			$cookie_value = 1;
			$response = setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 12), "/");
			die;
		}

		//Adding Custom logo
		public function mwb_get_custom_logo($blog_id = 0)
		{
			$html = '';
			$switched_blog = false;

			if (is_multisite() && !empty($blog_id) && (int) $blog_id !== get_current_blog_id()) {
				switch_to_blog($blog_id);
				$switched_blog = true;
			}

			$custom_logo_id = get_theme_mod('custom_logo');

			// We have a logo. Logo is go.
			if ($custom_logo_id) {
				$custom_logo_attr = array(
					'class'    => 'custom-logo',
					//'itemprop' => 'logo',
				);

				/*
				 * If the logo alt attribute is empty, get the site title and explicitly
				 * pass it to the attributes used by wp_get_attachment_image().
				 */
				$image_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
				if (empty($image_alt)) {
					$custom_logo_attr['alt'] = get_bloginfo('name', 'display');
				}

				/*
				 * If the alt attribute is not empty, there's no need to explicitly pass
				 * it because wp_get_attachment_image() already adds the alt attribute.
				 */
				$html = sprintf(
					'<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
					esc_url(home_url('/')),
					wp_get_attachment_image($custom_logo_id, 'full', false, $custom_logo_attr)
				);
			}

			// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
			elseif (is_customize_preview()) {
				$html = sprintf(
					'<a href="%1$s" class="custom-logo-link" style="display:none;"><img class="custom-logo"/></a>',
					esc_url(home_url('/'))
				);
			}

			if ($switched_blog) {
				restore_current_blog();
			}

			return $html;
		}


		/**
		 * get the dynamic menu for cart and my account.
		 *
		 */
		public function mwb_menu_logout_link($nav, $args)
		{
			$user_id = get_current_user_id();

			global $woocommerce;
			$cart_url = wc_get_cart_url();
			$cart_contents_count = $woocommerce->cart->cart_contents_count;
			$cart_total = $woocommerce->cart->cart_contents_total;

			// if ($cart_contents_count > 0) {

			$mobilelink = '<li class="mwb-responsive-menu menu-item menu-item-type-post_type menu-item-object-page"><div class="mwb_menu_cart_wrapper"><a href="' . $cart_url . '" itemprop="url"><i class="fa fa-shopping-cart"></i> <span>' . $cart_contents_count . ' items -</span> <span itemprop="name">' . wc_price($cart_total) . '</span></a></div></li>';

			//  }else{
			//      $mobilelink = "";
			//  }


			if ($args->menu->slug == 'header-menu') {
				return $nav . $mobilelink;
			}

			// if( $args->theme_location == 'amp-menu' )
			// {
			// 	return $nav.$mobilelink ;
			// }

			return $nav;
		}

		/**
		 * Async load css
		 */
		public function mwb_add_defer_css_attribute($tag, $handle, $href, $media)
		{
			return str_replace(' href', ' async="async" href', $tag);
		}


		/**
		 * Email Verification notice of myaccount page
		 */
		public function mwb_woocommerce_account_content()
		{
			$user_id = get_current_user_id();
			$status  = get_user_meta((int) $user_id, 'wcemailverified', true);
			$finalmessage = '<div id="mwb_response">';
			if (!is_super_admin()) {
				if ('true' != $status) {
					$link = add_query_arg(array('wc_confirmation_resend' => base64_encode($user_id)), wc_get_page_permalink('myaccount'));

					$error_message = __('Your account is not verified. Please verify your account.', 'wc-email-confirmation');

					$resend_confirmation_message = __('Resend Confirmation Email', 'wc-email-confirmation');

					$resend_link   = '<a href="' . $link . '">' . $resend_confirmation_message . '</a>';
					$error_message = $error_message . ' ' . $resend_link;
					$finalmessage .= '<ul class="woocommerce-error"><li>' . $error_message . '</li></ul>';
				}
			}
			$finalmessage .= '</div>';
			echo $finalmessage;
		}

		//Email for verification success notification
		public function mwb_email_verification_success_notification($userid)
		{
			$userdata = get_userdata($userid);
			$to = $userdata->user_email;
			$user_login = $userdata->user_login;
			$user_name = $userdata->display_name;

			$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
			$mwb_data = json_decode($mwb_microservice_notification_json, true);

			$mwb_welcome_mail_subject = isset($mwb_data['mwb_welcome_mail_subject']) ? $mwb_data['mwb_welcome_mail_subject'] : "";
			$mwb_welcome_mail_content = isset($mwb_data['mwb_welcome_mail_content']) ? $mwb_data['mwb_welcome_mail_content'] : "";

			$subject = $mwb_welcome_mail_subject;
			$message = $mwb_welcome_mail_content;
			$message = str_replace('{{user_name}}', $user_name, $message);

			wc_mail($to, $subject, $message);
		}

		//Code for locating template
		public function mwb_woocommerce_locate_template($template, $template_name, $template_path)
		{
			if (!is_admin()) {
				global $woocommerce;

				$usercountry = WC()->customer->get_billing_country();

				// if($usercountry == 'IN' || is_product())
				// {
				$_template = $template;

				if (!$template_path)
					$template_path = $woocommerce->template_url;

				$plugin_path  = untrailingslashit(MWB_DIRPATH) . '/woocommerce/';
				$template = locate_template(
					array(
						$template_path . $template_name,
						$template_name
					)
				);

				// Modification: Get the template from this plugin, if it exists
				if (!$template && file_exists($plugin_path . $template_name))
					$template = $plugin_path . $template_name;

				// Use default template
				if (!$template)
					$template = $_template;
				// }

			}
			// Return what we found
			return $template;
		}

		//Added order item meta
		public function mwb_woocommerce_checkout_update_order_meta($order_id, $data)
		{
			$order = new WC_Order($order_id);
			$usercountry = $data['billing_country'];
			if ($usercountry == 'IN') {
				foreach ($order->get_items() as $item_id => $item) {
					wc_add_order_item_meta($item_id, 'IGST', "18%");
					wc_add_order_item_meta($item_id, 'SAC', "998313");
				}
			}
		}

		//Add trial add to cart item data

		public function mwb_trail_add_cart_item_data($the_cart_data, $product_id)
		{
			unset($_POST['quantity']);
			unset($_POST['add-to-cart']);

			if (isset($_POST['action'])) {
				if ($_POST['action'] == "mwb_trail_buy_now") {
					$subscription_enable = get_post_meta($product_id, 'mwb_subscription_enable', true);
					if ($subscription_enable == "yes") {
						$_POST['mwb_subscription_enable'] = 'mwb_subscription_enable';
					}
				}
			}

			$the_cart_data['product_meta'] = array('meta_data' => $_POST);

			return $the_cart_data;
		}


		//generate trial period license
		public function mwb_trail_period_lincese($item_id, $item, $renewal_period)
		{
			$order_item_meta = $item->get_formatted_meta_data();
			if (isset($order_item_meta) && !empty($order_item_meta)) {
				foreach ($order_item_meta as $item_meta) {
					$item_meta = (array)$item_meta;
					if ($item_meta['key'] == "Trial Period") {
						$renewal_period = date('Y-m-d', strtotime('+14 days'));
					}
				}
			}
			return $renewal_period;
		}

		//Set trial period price
		public function mwb_trail_period_price($cart)
		{
			$trial = false;
			$custom_price = 1;
			foreach ($cart->cart_contents as $key => $item_data) {
				if (isset($item_data['product_meta']['meta_data']) && !empty($item_data['product_meta']['meta_data'])) {

					foreach ($item_data['product_meta']['meta_data'] as $key => $val) {

						if ($key == 'action') {
							if ($val == "mwb_trail_buy_now") {
								$item_data['data']->set_price($custom_price);
								$trial = true;
							}
						}
					}
				}
			}

			if ($trial) {
				WC()->session->set('mwb_trail', true);
			} else {
				WC()->session->set('mwb_trail', false);
			}
		}

		//Adding Item meta for trial product after order placed
		public function mwb_trail_woocommerce_add_order_item_meta($item_id, $cart_item)
		{

			if (isset($cart_item['product_meta'])) {
				foreach ($cart_item['product_meta']['meta_data'] as $key => $val) {
					if ($val) {
						if ($key == 'action') {
							if ($val == "mwb_trail_buy_now") {
								wc_add_order_item_meta($item_id, 'Trial Period', "14 Days");
							}
						}
						// if($key == 'mwb_subscription_enable')
						// {
						//   if($val == "mwb_subscription_enable")
						//   {
						//     wc_add_order_item_meta ( $item_id, 'Subscription', "Yearly" );
						//   }
						// }

					}
				}
			}
		}

		//Adding item meta for trial product
		public function mwb_trail_add_item_meta($item_meta, $existing_item_meta)
		{
			if ($existing_item_meta['product_meta']['meta_data']) {
				foreach ($existing_item_meta['product_meta']['meta_data'] as $key => $val) {

					if ($key == 'action') {
						if ($val == "mwb_trail_buy_now") {
							$item_meta[] = array(
								'name' => __('Trial Period'),
								'value' => "14 Days",
							);
						}
					}
					// if($key == 'mwb_subscription_enable')
					// {
					// 	if($val == "mwb_subscription_enable")
					// 	{
					// 		$item_meta [] = array (
					// 			'name' => __('Subscription'),
					// 			'value' => "1 Year",
					// 			);
					// 	}

					// }
				}
			}
			return $item_meta;
		}

		//shortcode for add to cart of trial product
		public function mwb_trail_buy_now()
		{
			$product_id = $_POST['product_id'];
			WC()->cart->add_to_cart($product_id);
		}

		//shortcode for add to cart button of trial product
		public function mwb_product_trail_buynow()
		{
			global $product, $woocommerce;
			if (isset($product) && !empty($product)) {
				$product_type = $product->get_type();

				if ($product_type == "variable") {
					$product_child = $product->get_visible_children();
					if (isset($product_child[0])) {
						$product_id = $product_child[0];
					}
				} else {
					$product_id = $product->get_ID();
				}


				$checkouturl = wc_get_checkout_url();
				$mwb_enable_product_trial = get_post_meta($product_id, 'mwb_enable_product_trial', true);
				//if(isset($mwb_enable_product_trial) && !empty($mwb_enable_product_trial) && $mwb_enable_product_trial[0] == 'true')
				//{
				return '<form action="' . $checkouturl . '?add-to-cart=' . $product_id . '" method="post" id="mwb_trail_product"><input type="hidden" name="action" value="mwb_trail_buy_now"><input type="hidden" name="product_id" value="' . $product_id . '"><input type="submit" value="START $1 TRIAL" class="trial_button"></form>';
				//}
			}
		}


		//Shortcode for Support license add to cart button
		public function mwb_support_add_to_cart()
		{
			global $product, $woocommerce;
			if (isset($product) && !empty($product)) {
				$product_id = $product->get_ID();
				$checkouturl = wc_get_checkout_url();
				// $support_price = get_post_meta($product_id, 'mwb_12_month_support_price', true);
				$price = $product->get_price();
				$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
				$mwb_data = json_decode($mwb_microservice_notification_json, true);
				$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
				$support_price = intval($price * ($mwb_purchase_time_support / 100));

				$support_meta = $support_price;

				$html = '<form action="' . $checkouturl . '/?add-to-cart=' . $product_id . '" method="post" id="mwb_support_add_to_cart">
				<input type="hidden" name="quantity" value="1">
				<input type="hidden" name="add-to-cart" value="' . $product_id . '">
				<button type="submit" value="' . $support_meta . '" class="single_add_to_cart_button button alt" name="support_cart">BUY NOW</button></form>';
				$html .= '<form method="post" id="mwb_support_add_to_cart">
				<input type="hidden" name="quantity" value="1">
				<input type="hidden" name="add-to-cart" value="' . $product_id . '">
				<button type="submit" value="' . $support_meta . '" class="single_add_to_cart_button button alt support_cart" name="support_cart">ADD TO CART</button></form>';
				return $html;
			}
		}

		//Email license key activation details
		public function mwb_license_key_activation_details($fields, $order_id)
		{
			$license_key = $fields['lic_key'];
			$licenses = get_post_meta($order_id, '_wc_slm_payment_licenses', true);

			$order = new WC_Order($order_id);

			$usermail = $order->get_billing_email();

			foreach ($licenses as $license) {
				$orderlicensekey = $license['key'];
				if ($orderlicensekey == $license_key) {
					$productname = $license['item'];
					$product = get_page_by_title($productname, OBJECT, 'product');
					$mwb_product_type = "simple";
					$product_id = 0;
					if (empty($product)) {
						$product = get_page_by_title($productname, OBJECT, 'product_variation');
						$mwb_product_type = "variable";
					}

					if (isset($product) && !empty($product)) {
						if ($mwb_product_type == "simple") {
							$product_id = $product->ID;
						} else {
							$product_id = $product->post_parent;
						}
					}

					if ($product_id > 0) {
						$mwb_plugin_documentation = get_post_meta($product_id, "mwb_plugin_documentation", true);
						$mwb_plugin_video = get_post_meta($product_id, "mwb_plugin_video", true);

						$product_title = $product->post_title;
						$subject = "Instruction to using $product_title";

						$message = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width" /><meta http-equiv="X-UA-Compatible" content="IE=edge" /> <meta name="x-apple-disable-message-reformatting" /><title></title><style>html,body {margin: 0 auto !important;padding: 0 !important;height: 100% !important;width: 100% !important;}*{-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}div[style*="margin: 16px 0"] {margin: 0 !important;}table,td {mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;}table {border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;}table table table {table-layout: auto;}img {-ms-interpolation-mode:bicubic;}*[x-apple-data-detectors],.x-gmail-data-detectors,.x-gmail-data-detectors *,.aBn {border-bottom: 0 !important;cursor: default !important;color: inherit !important;text-decoration: none !important;font-size: inherit !important;font-family: inherit !important;font-weight: inherit !important;line-height: inherit !important;}.a6S {display: none !important;opacity: 0.01 !important;}img.g-img + div {display: none !important;}.button-link {text-decoration: none !important;}@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {.email-container {min-width: 375px !important;}}@media screen and (max-width: 480px) {u ~ div .email-container {min-width: 100vw;width: 100% !important;}h1.email-main-heading {font-size: 20px !important;}}</style>
    <style>.button-td,.button-a {transition: all 100ms ease-in;}.button-td:hover,.button-a:hover {background: #555555 !important;border-color: #555555 !important;}@media screen and (max-width: 600px) {.email-container {width: 100% !important;margin: auto !important;}.fluid {max-width: 100% !important;height: auto !important;margin-left: auto !important;margin-right: auto !important;}.stack-column,
            .stack-column-center {display: block !important;width: 100% !important;max-width: 100% !important;direction: ltr !important;}.stack-column-center {text-align: center !important;}.center-on-narrow {text-align: center !important;display: block !important;margin-left: auto !important;margin-right: auto !important;float: none !important;}table.center-on-narrow {display: inline-block !important;}.email-container p {font-size: 17px !important;}}</style></head><body width="100%" bgcolor="#222222" style="margin: 0; mso-line-height-rule: exactly;"><center style="width: 100%; background: #222222; text-align: left;"><table bgcolor="#ffffff" role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto; background-color: #ffffff; max-width: 600px; width: 100% !important;" class="email-container"><tr>
                <td bgcolor="#ffffff" style="padding: 20px 0; text-align: center"><img src="https://makewebbetter.com/wp-content/uploads/2018/12/mwb-logo.png" width="50" height="29" alt="alt_text" border="0" style="height: auto; background: #ffffff; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;" />
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 10px 15px 20px; text-align: center;"><h1 class="email-main-heading" style="margin: 0; font-family: sans-serif;text-align: center; font-size: 30px; line-height: 125%; color: #333333; font-weight: normal;"><i>Hello, Thanks for using </i><br /><span style="font-weight: bold;">' . $product_title . '</span>
                    </h1>
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 0 20px 20px; font-family: sans-serif; font-size: 16px; line-height: 140%; color: #555555; text-align: center;"><p style="margin: 0;">Greeting from MakeWebBetter!.</p>
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 0 20px 20px; font-family: sans-serif; font-size: 16px; line-height: 140%; color: #555555; text-align: center;"><p style="margin: 0;">Thanks for verifying your purchase code with us. You are now member of our family. As you are now able to use the plugin according to the need.</p>
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 0 20px 20px; font-family: sans-serif; font-size: 16px; line-height: 140%; color: #555555; text-align: center;"><p style="margin: 0;">For reference to understand more about the product you can check linked videos on the product page or from youtube. For more, our support team will always be present to serve you regarding your queries.</p>
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 20px 40px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: center;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="center-on-narrow" style="">
                        <tr>';
						if (!empty($mwb_plugin_documentation)) {
							$message .= '<td><a href="' . $mwb_plugin_documentation . '" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;border-radius: 3px;margin-right: 10px;" class="button-a"><span style="color:#ffffff;" class="button-link">View Documentation</span></a>
                          </td>';
						}
						if (!empty($mwb_plugin_video)) {
							$message .= '<td><a href="' . $mwb_plugin_video . '" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;border-radius: 3px;" class="button-a"><span style="color:#ffffff;" class="button-link">View Video</span></a>
                          </td>';
						}
						$message .= '</tr>
                  </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 0 20px 30px; font-family: sans-serif; font-size: 16px; line-height: 140%; color: #555555; text-align: center;"><p style="margin: 0;">That is not it, you can also catchup latest blog news regarding our exclusive plugin and more.</p>
                </td>
           </tr>
            <tr>
                <td style="text-align: center;background-color: #e2e2e2;padding-top: 30px;"><a data-hs-link-id="0" href="https://www.facebook.com/makewebbetter" style="-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; width:24px; border-width:0px; border:0px; text-decoration:none; padding: 0 10px; display: inline-block;" target="_blank" width="24"><img src="https://crm.makewebbetter.com/media/images/Ist_seo_report/facebook.png" style="max-width: 24px; max-height: 24px; height: 24px; width: 24px; border-radius: 3px; border: 0px none;" alt="Share on facebook" class="fr-fic fr-dii" width="24" height="24" /></a><a data-hs-link-id="0" href="https://www.instagram.com/company/makewebbetter/" style="-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; width:24px; border-width:0px; border:0px; text-decoration:none; padding: 0 10px; display: inline-block;" target="_blank" width="24"><img src="https://crm.makewebbetter.com/media/images/Ist_seo_report/instagram.png" style="max-width: 24px; max-height: 24px; height: 24px; width: 24px; border-radius: 3px; border: 0px none;" alt="Share on instagram" class="fr-fic fr-dii" width="24" height="24" /></a><a data-hs-link-id="0" href="https://twitter.com/makewebbetter" style="-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; width:24px; border-width:0px; border:0px; text-decoration:none; padding: 0 10px; display: inline-block;" target="_blank" width="24"><img src="https://crm.makewebbetter.com/media/images/Ist_seo_report/twitter.png" style="max-width: 24px; max-height: 24px; height: 24px; width: 24px; border-radius: 3px; border: 0px none;" alt="Share on twitter" class="fr-fic fr-dii" width="24" height="24" /></a><a data-hs-link-id="0" href="https://in.linkedin.com/company/makewebbetter" style="-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; width:24px; border-width:0px; border:0px; text-decoration:none; padding: 0 10px; display: inline-block;" target="_blank" width="24"><img src="https://crm.makewebbetter.com/media/images/Ist_seo_report/linkedin.png" class="hs-image-widget hs-image-social-sharing-24 fr-fic fr-dii" style="max-width: 24px; max-height: 24px; height: 24px; width: 24px; border-radius: 3px; border: 0px none;" alt="Share on linkedin" width="24" height="24" /></a><a data-hs-link-id="0" href="https://in.pinterest.com/makewebbetter/" style="-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; width:24px; border-width:0px; border:0px; text-decoration:none; padding: 0 10px; display: inline-block;" target="_blank" width="24"><img src="https://crm.makewebbetter.com/media/images/Ist_seo_report/pinterest-line.png" class="hs-image-widget hs-image-social-sharing-24 fr-fic fr-dii" style="max-width: 24px; max-height: 24px; height: 24px; width: 24px; border-radius: 3px; border: 0px none;" alt="Share on pinterest" width="24" height="24" /></a>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;background-color: #e2e2e2;padding-top: 20px;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="center-on-narrow" style="">
                        <tr>
                            <td style="text-align: center;background-color: #e2e2e2; padding-right: 10px;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="center-on-narrow" style="">
                                    <tr>
                                        <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td"><a href="https://makewebbetter.freshdesk.com/support/tickets/new" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a"><span style="color:#ffffff;" class="button-link">Support</span></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="text-align: center;background-color: #e2e2e2;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="center-on-narrow" style="">
                                    <tr>
                                        <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td"><a href="https://join.skype.com/invite/IKVeNkLHebpC" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a"><span style="color:#ffffff;" class="button-link">Skype</span></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#e2e2e2" style="padding: 20px; font-family: Helvetica; font-size: 10px; line-height: 200%; color: #737373; text-align: center; text-decoration:none;"><p style="margin: 0; text-decoration:none;">MakeWebBetter 11923 NE Sumner St STE 714705 Portland Oregon 97220 USA</p>
                </td>
            </tr></table></center></body></html>';
						$to = $usermail;
						wc_mail($to, $subject, $message);
					}
				}
			}
		}

		//Change status to completed
		public function mwb_change_status_to_completed($order_id)
		{
			if (!$order_id) {
				return;
			}
			$order = new WC_Order($order_id);

			$orderstatus = $order->get_status();

			if ($orderstatus != 'completed') {
				$order->update_status('completed');
			}

			$userid = $order->get_customer_id();
			$wcemailverified = get_user_meta((int) $userid, 'wcemailverified', true);

			if ($wcemailverified != 'true') {
				update_user_meta((int) $userid, 'wcemailverified', 'true');
				do_action("mwb_email_verification_success", (int) $userid);
			}
		}

		/**
		 * get the shortcode for categories.
		 *
		 */
		public function filter_tags($args)
		{
			$args = do_shortcode('[post_categories]');
			return $args;
		}

		//Modify Thumbnail size to full on shop page
		public function mwb_single_product_archive_thumbnail_size($size)
		{
			$size = 'full';
			return $size;
		}



		/**
		 * Locate the woocommerce template.
		 *
		 */
		public function mwb_locate_template($template)
		{

			if (is_single() && 'product' == get_post_type()) {

				$template = locate_template(array('woocommerce/single-product.php'));
				if (!$template)
					$template = MWB_DIRPATH . 'woocommerce/single-product.php';
			}
			return $template;
		}

		//Adding instructions on checkout page
		public function mwb_woocommerce_checkout_instruction()
		{
		?>
			<div class="mwb_price">
				<ul>
					<li><i class="fa fa-question" aria-hidden="true"></i> 24X7 Help</li>
					<li><i class="fa fa-life-ring" aria-hidden="true"></i> Instant Support</li>
					<li><i class="fa fa-phone" aria-hidden="true"></i> +18885752397</li>
					<li><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:ticket@makewebbetter.com">ticket@makewebbetter.com</a></li>

				</ul>
			</div>
			<?php

			$subscription_payment = false;
			if (WC()->cart->cart_contents_count != 0) {
				foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
					$product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
					$product_type = $product->get_type();
					if ($product_type == "variation") {
						$product_id = $product->get_parent_id();
					} else {
						$product_id = $product->get_id();
					}

					$subscription_enable = get_post_meta($product_id, 'mwb_subscription_enable', true);

					if ($subscription_enable == "yes") {
						$mwb_trial = WC()->session->get('mwb_trail');
						if (isset($mwb_trial) && $mwb_trial == true) {
							$subscription_payment = true;
							break;
						}
					}
				}
				if ($subscription_payment) {
			?>
					<div class="mwb_price">
						<p><strong>Note:</strong> Once order with HubSpot $1 trial is confirmed, your subscription is active for next 14 days. After 14 days, regular subscription amount gets automatically deducted from your account, which can be easily cancelled or paused anytime over PayPal within 14 days of trial period.</p>
					</div>
			<?php
				}
			}
		}

		/**
		 * Remove the default functioning.
		 *
		 */

		public function mwb_remove_tabs_heading()
		{


			genesis_register_sidebar(array(
				'id' => 'blog-sidebar',
				'name' => 'Blog Sidebar',
				'description' => 'This is the bottom left column in the sidebar.',
			), true);


			if (is_checkout()) {
				wc_clear_notices();
			}

			remove_filter('woocommerce_product_tabs', 'woocommerce_default_product_tabs');

			// remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

			remove_action('woocommerce_checkout_shipping', 'checkout_form_shipping');

			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
			remove_action('woocommerce_single_product_summary', 'generate_product_data', 60);
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			remove_action('woocommerce_no_products_found', 'wc_no_products_found');

			remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
			remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

			remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
			remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

			remove_action('genesis_loop_else', 'genesis_do_noposts');

			// remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
			// remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );

			if (is_product() || is_checkout() || is_shop()) {
				remove_action('business_page_header', 'business_page_title', 10);
				remove_action('business_page_header', 'business_page_excerpt', 20);
			}

			if (is_singular('post')) {
				remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
			}

			remove_filter('post_class', 'genesis_entry_post_class');

			// remove_filter( 'genesis_footer_creds_text', 'studio_footer_creds_filter' );


			if (is_product()) {

				remove_action('genesis_before_content_sidebar_wrap', 'business_page_header');
				// apply_filters('genesis_after_header',true );

				remove_action('genesis_after_content_sidebar_wrap', 'business_prev_next_post_nav_cpt', 99);
			}

			if ((is_checkout() || is_cart()) && !(is_wc_endpoint_url('order-received') && isset($_GET['key']))) {
				remove_action('genesis_header', 'genesis_do_nav', 12);
				remove_action('genesis_footer', 'genesis_footer_widget_areas', 6);
			}
		}

		//Remove header open
		public function mwb_remove_header_open($response)
		{
			if (is_product() || is_checkout() || is_shop()) {
				return false;
			}
			return $response;
		}

		//Remove title
		public function mwb_remove_title($response)
		{
			if (is_product() || is_checkout()) {
				return;
			}
			return $response;
		}

		//Remove header close
		public function mwb_remove_header_close($response)
		{
			if (is_product() || is_checkout() || is_shop()) {
				return false;
			}
			return $response;
		}


		/**
		 * Adding custom css.
		 *
		 */
		public function mwb_wp_enqueue_script()
		{
			global $woocommerce;
			$product_id = get_the_ID();
			$checkouturl = wc_get_checkout_url();
			$cart_url = wc_get_cart_url();
			$plugin_url = MWB_URL;
			$cart_contents_count = $woocommerce->cart->cart_contents_count;
			$cart_total = $woocommerce->cart->cart_contents_total;

			//styles for whole site
			wp_enqueue_style('mwb-singleproduct-page', MWB_URL . "assets/css/style.css");


			// $header_enable = "yes";
			$header_enable = get_post_meta($product_id, 'mwb_enable_product_template', true);
			// $header_template = "template3";
			$header_template = get_post_meta($product_id, 'mwb_template_type', true);


			$post = get_post($product_id);
			$product_slug = $post->post_name;

			if ($header_enable == "yes") {
				if ($header_template == "template1") {
					wp_enqueue_style('mwb-hubspot-template', MWB_URL . "assets/css/mwb-hubspot.css");

					wp_enqueue_script('mwb-hubspot-template-js', MWB_URL . "assets/js/mwb-hubspot.js", array('jquery'));
				}

				if ($header_template == "template3") {
					wp_enqueue_style('mwb-new-prod-page-layout', MWB_URL . "assets/css/mwb-new-product-page.css");
					wp_enqueue_script('new-layout-js', MWB_URL . "assets/js/mwb-new-layout.js", array('jquery'));
				}
				if ($header_template == "shertemplate") {
					wp_enqueue_style('mwb-new-prod-page-layout', MWB_URL . "assets/css/mwb-new-product-page.css");
					wp_enqueue_script('new-layout-js', MWB_URL . "assets/js/mwb-new-layout.js", array('jquery'));
				}
				if ($header_template == "landing") {
					wp_enqueue_style('mwb-new-layout-js', MWB_URL . "assets/css/product-page.css");
					wp_enqueue_script('mwb-new-layout-js', MWB_URL . "assets/js/product-page.js", array('jquery'));
				}

				wp_enqueue_style('mwb-converting-popup-image', MWB_URL . "assets/css/magnific-popup.css");

				wp_enqueue_script('mwb-converting-magnific-js', MWB_URL . "assets/js/jquery.magnific-popup.min.js", array('jquery'));
				wp_enqueue_script('mwb-converting-popup-js', MWB_URL . "assets/js/magnific-popup-options.js", array('jquery', 'mwb-converting-magnific-js'));
			}

			if ($product_slug == 'makewebbetter-reviews-ratings') {
				wp_enqueue_style('mwb-review-page-new', MWB_URL . "assets/css/mwb_review_page.css");

				wp_enqueue_script('masonry', array('jquery'), null, null, time(), true);
				wp_enqueue_script('review-page-new-layout-js', MWB_URL . "assets/js/mwb-review-page.js", array('jquery', 'masonry'));
			}

			if ($product_slug == 'digital-marketing-services') {
				wp_enqueue_style('mwb-digital-marketing-services', MWB_URL . "assets/css/mwb-digital-marketing-services.css");

				wp_enqueue_script('mwb-counter-js', MWB_URL . "assets/js/appear.js", array('jquery'));
				wp_enqueue_script('mwb-digital-marketing-services-js', MWB_URL . "assets/js/mwb-digital-marketing-services.js", array('jquery', 'mwb-counter-js', 'genesis-sample-carousel'));
			}

			if ($product_slug == "pay-per-click-services" || $product_slug == "professional-seo-services" || $product_slug == "google-analytics-consultancy-services") {
				wp_enqueue_style('mwb-digital-services-page', MWB_URL . "assets/css/mwb-ppc-service.css");

				wp_enqueue_script('mwb-digital-marketing-services-js', MWB_URL . "assets/js/mwb-digital-marketing-services.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == 'digital-marketing-price-package') {
				wp_enqueue_style('mwb-dm-pricing-plan-css', MWB_URL . "assets/css/mwb-dm-pricing-plan.css");
			}

			if ($product_slug == 'wordpress-services' || $product_slug == 'woocommerce-services') {
				wp_enqueue_style('mwb-wordpress-services', MWB_URL . "assets/css/mwb-wordpress-services.css");

				wp_enqueue_script('mwb-wordpress-services-js', MWB_URL . "assets/js/mwb-wordpress-services.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == 'wordpress-woocommerce-solutions') {
				wp_enqueue_style('mwb-wordpress-woocommerce-solutions', MWB_URL . "assets/css/mwb-wordpress-woocommerce-solutions.css");

				wp_enqueue_script('mwb-wordpress-services-js', MWB_URL . "assets/js/mwb-wordpress-services.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == 'about-us') {
				wp_enqueue_style('mwb-about-us-new', MWB_URL . "assets/css/mwb-about-us.css");
				wp_enqueue_script('about-us-new-layout-js', MWB_URL . "assets/js/mwb-about-us.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == "partners") {
				wp_enqueue_style('mwb-partners-page-css', MWB_URL . "assets/css/mwb-partners-page.css");
			}

			if ($product_slug == "free-mautic-email-templates") {
				wp_enqueue_style('mwb-free-mautic-email-templates', MWB_URL . "assets/css/mwb-free-mautic-email-templates.css");
			}

			if ($product_slug == "hubspot-ecommerce-onboarding") {
				wp_enqueue_style('mwb-onboard-checklist', MWB_URL . "assets/css/mwb-onboard-checklist.css");
			}

			// 			if($product_slug == "hubspot-woocommerce-onboarding"){
			// 				wp_enqueue_style( 'mwb-woocommerce-onboarding', MWB_URL."assets/css/mwb-woocommerce-onboarding.css" );
			// 			}

			if ($product_slug == "customizable-hubspot-templates") {
				wp_enqueue_style('mwb-customizable-hubspot-templates', MWB_URL . "assets/css/mwb-customizable-hubspot-templates.css");

				wp_enqueue_script('mwb-home-landing-pages-js', MWB_URL . "assets/js/mwb-home-and-landing-pages.js", array('jquery', 'genesis-sample-carousel'));
			}

			if (is_front_page()) {
				wp_enqueue_style('mwb-home-page', MWB_URL . "assets/css/mwb-home-page.css");
			}

			if ($product_slug == 'lead-generation' || $product_slug == 'inbound-marketing' || $product_slug == "web-design-development-services") {
				wp_enqueue_style('mwb-home-landing-pages', MWB_URL . "assets/css/mwb-home-and-landing-pages.css");

				wp_enqueue_script('mwb-home-landing-pages-js', MWB_URL . "assets/js/mwb-home-and-landing-pages.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == "hubspot-cos-development-service") {
				wp_enqueue_style('mwb-hubspot-cos-development-pages', MWB_URL . "assets/css/mwb-hubspot-cos-development-page.css");

				wp_enqueue_script('mwb-home-landing-pages-js', MWB_URL . "assets/js/mwb-home-and-landing-pages.js", array('jquery', 'genesis-sample-carousel'));
			}


			if ($product_slug == 'inbound-2019') {
				wp_enqueue_style('mwb-inbound-2019', MWB_URL . "assets/css/mwb-hubspot-inbound.css");
			}

			if (is_shop()) {
				wp_enqueue_style('mwb-shop-page-new-layout', MWB_URL . "assets/css/shop-new-layout.css");
			}

			if (is_cart()) {
				wp_register_script('mwb-custom-cart-js', MWB_URL . "assets/js/mwb-cart-page.js", array('jquery'));

				$trans_array = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
				);
				wp_localize_script('mwb-custom-cart-js', 'mwb_cart_page', $trans_array);
				wp_enqueue_script('mwb-custom-cart-js');
			}

			if (is_search() && !is_shop()) {
				wp_enqueue_style('mwb-search-page-css', MWB_URL . "assets/css/mwb-search-page.css");
				wp_enqueue_script('mwb-search-page-js', MWB_URL . "assets/js/mwb-search-page.js", array('jquery'));
			}

			if ($product_slug == "contact-us") {
				wp_enqueue_style('mwb-contact-us-css', MWB_URL . "assets/css/mwb-contact-us.css");
			}

			if ($product_slug == "success-stories" || $product_slug == "mammacult" || $product_slug == "alaska-glacial-mud" || $product_slug == "home-utensils") {
				wp_enqueue_style('mwb-success-stories-css', MWB_URL . "assets/css/mwb_success_stories.css");
			}

			if (is_product_category('services')) {
				wp_enqueue_style('mwb-main-service-listingpage-css', MWB_URL . "assets/css/mwb_main_service_listing.css");
			}

			if (has_term('services', 'product_cat', $product_id)) {
				wp_enqueue_script('mwb-services-product-js', MWB_URL . "assets/js/mwb_services_product_page.js", array('jquery'));
			}


			if ($product_slug == "hubspot-services") {
				wp_enqueue_style('mwb-hubspot-services-template', MWB_URL . "assets/css/mwb_hubspot_services.css");
			}

			if ($product_slug == "hubspot-ecommerce-agency") {
				wp_enqueue_style('mwb-hubspot-ecommerce-agency', MWB_URL . "assets/css/mwb-hubspot-ecommerce-agency.css");
			}

			if ($product_slug == "woocommerce-refund-exchange") {
				wp_enqueue_style('mwb-hubspot-template', MWB_URL . "assets/css/mwb-hubspot.css");

				wp_enqueue_script('mwb-hubspot-template-js', MWB_URL . "assets/js/mwb-hubspot.js", array('jquery', 'genesis-sample-carousel'));

				wp_enqueue_style('mwb-converting-popup-image', MWB_URL . "assets/css/magnific-popup.css");

				wp_enqueue_script('mwb-converting-magnific-js', MWB_URL . "assets/js/jquery.magnific-popup.min.js", array('jquery'));
				wp_enqueue_script('mwb-converting-popup-js', MWB_URL . "assets/js/magnific-popup-options.js", array('jquery', 'mwb-converting-magnific-js'));
			}

			if ($product_slug == "marketing-automation-services" || $product_slug == "social-media-marketing-services" || $product_slug == "ontraport-woocommerce-marketing-for-ecommerce" || $product_slug == "email-marketing-automation-software-ecommerce-business" || $product_slug == "how-to-automate-sales-pipeline-with-hubspot-ecommerce-bridge" || $product_slug == "hubspot-ecommerce-bridging-connect-woocommerce" || $product_slug == "download-best-mautic-email-templates-for-email-marketing" || $product_slug == "advance-google-data-studio-funnel-report-template" || $product_slug == "hubspot-get-quote-app" || $product_slug == "license-activation") {
				wp_enqueue_style('mwb-converting-popup-image', MWB_URL . "assets/css/magnific-popup.css");

				wp_enqueue_script('mwb-converting-magnific-js', MWB_URL . "assets/js/jquery.magnific-popup.min.js", array('jquery'));
				wp_enqueue_script('mwb-converting-popup-js', MWB_URL . "assets/js/magnific-popup-options.js", array('jquery', 'mwb-converting-magnific-js'));
			}

			if ($product_slug == "hubspot-get-quote-app") {
				wp_enqueue_style('mwb-get-quote-hubspot-css', MWB_URL . "assets/css/mwb-hubspot-get-quote.css?" . time());
			}

			if ($product_slug == "mautic-for-ecommerce") {
				wp_enqueue_style('mwb-mautic-ecommerce', MWB_URL . "assets/css/mwb-mautic-ecommerce.css");

				wp_enqueue_script('mwb-home-landing-pages-js', MWB_URL . "assets/js/mwb-home-and-landing-pages.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == "responsive-hubspot-templates") {
				wp_enqueue_style('hubspot-listing', MWB_URL . "assets/css/hubspot-listing-page.css");

				wp_enqueue_script('mwb-paroller-hubspot-js', MWB_URL . "assets/js/jquery.paroller.min.js", array('jquery'));

				wp_enqueue_script('mwb-shuffle-hubspot-js', MWB_URL . "assets/js/jquery.shuffle.min.js", array('jquery'));

				wp_enqueue_script('mwb-hubspot-listing-template-js', MWB_URL . "assets/js/mwb-hubspot-listing.js", array('jquery', 'mwb-paroller-hubspot-js', 'mwb-shuffle-hubspot-js'));
			}

			if ($product_slug == "offers-free-registration") {
				wp_enqueue_style('mwb-offer-registration', MWB_URL . "assets/css/mwb-offer-registration.css");
			}

			if ($product_slug == "offers") {
				wp_enqueue_style('mwb-offers-page', MWB_URL . "assets/css/mwb-offers.css");
				wp_enqueue_script('mwb-offer-page-js', MWB_URL . "assets/js/mwb-offer-page.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == "site-audit") {
				wp_enqueue_style('mwb-congratulations-page', MWB_URL . "assets/css/mwb-congratulations.css");
			}

			if ($product_slug == "affordable-seo") {
				wp_enqueue_style('mwb-seo', MWB_URL . "assets/css/seo.css");

				wp_enqueue_script('mwb-seo-template-js', MWB_URL . "assets/js/mwb-seo.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == "woocommerce-return-merchandise-authorizations-rma") {
				wp_enqueue_style('mwb-rma', MWB_URL . "assets/css/rma-page.css");

				wp_enqueue_script('mwb-rma-page-template-js', MWB_URL . "assets/js/mwb-rma-page.js", array('jquery'));
			}

			if ($product_slug == "affiliate-program") {
				wp_enqueue_style('mwb-affiliate-home-page', MWB_URL . "assets/css/mwb-affiliate-page.css");

				wp_enqueue_script('mwb-affiliate-page-js', MWB_URL . "assets/js/mwb-affiliate-page.js", array('jquery'));
			}

			if ($product_slug == "affiliate-dashboard") {
				wp_enqueue_style('mwb-affiliate-dashboard', MWB_URL . "assets/css/mwb-affiliate-dashboard.css");

				wp_enqueue_script('mwb-affiliate-dashboard-js', MWB_URL . "assets/js/mwb-affiliate-dashboard.js", array('jquery'));
			}

			if ($product_slug == "hubspot-for-ecommerce") {
				wp_enqueue_style('mwb-hubspot-woocommerce-page', MWB_URL . "assets/css/hubspot-for-woocommerce.css");

				wp_enqueue_script('mwb-home-landing-pages-js', MWB_URL . "assets/js/mwb-home-and-landing-pages.js", array('jquery', 'genesis-sample-carousel'));
			}

			if ($product_slug == "hubspot-for-woocommerce") {
				wp_enqueue_style('mwb-hubspot-woocommerce-product', MWB_URL . "assets/css/hubspot-integration-for-woocommerce.css");
			}

			if ($product_slug == "marketing-automation-services" || $product_slug == "social-media-marketing-services") {
				wp_enqueue_style('mwb-digital-services-page', MWB_URL . "assets/css/mwb-digital-services.css");
			}

			if (is_wc_endpoint_url('order-received') && isset($_GET['key'])) {
				wp_enqueue_style('mwb-thankyou-page-template', MWB_URL . "assets/css/mwb-thankyou-page.css");
			}

			// wp_enqueue_style( 'mwb-font-awesome-css', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" );

			$cart_total_html = wc_price($cart_total);


			wp_register_script('mwb-custom-js', MWB_URL . "assets/js/mwb-custom.js", array('jquery'));


			if ($this->is_blog()) {
				$blog_page_visit = true;
			} else {
				$blog_page_visit = false;
			}

			if (is_checkout()) {
				$checkout_page_visit = true;
			} else {
				$checkout_page_visit = false;
			}
			if (get_current_user_id() > 0) {
				$logged_in_user = true;
			} else {
				$logged_in_user = false;
			}


			$translation_array = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'current_user_id' => get_current_user_id(),
				'checkouturl' => $checkouturl,
				'cart_url' => $cart_url,
				'cart_count' => $cart_contents_count,
				'cart_total' => $cart_total_html,
				'checkout_page' => $checkout_page_visit,
				'logged_in_user' => $logged_in_user,
				'blog_page_visit' => $blog_page_visit,
				'plugin_url' => $plugin_url,
				'product_slug' => $product_slug,

			);
			wp_localize_script('mwb-custom-js', 'mwb_subscriptionV2', $translation_array);
			wp_enqueue_script('mwb-custom-js');

			if (is_404()) {

				wp_enqueue_style('mwb-404-page-css', MWB_URL . "assets/css/mwb-404-page.css");

				wp_register_script(
					'google_cse_v2',
					MWB_URL . 'assets/js/google_cse_v2.js',
					array(),
					1.0,
					true
				);
				wp_enqueue_script('google_cse_v2');

				$google_search_engine_id = '013322119556512091504:0yniqo2h56u';

				$script_params = array(
					'google_search_engine_id' => $google_search_engine_id
				);

				wp_localize_script('google_cse_v2', 'scriptParams', $script_params);
			}
		}

		//Function for checking current page is blog page or not
		public function is_blog()
		{
			global  $post;
			$posttype = get_post_type($post);
			return (((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_single()) || (is_tag())) && ($posttype == 'post')) ? true : false;
		}

		/**
		 * Change the footer text.
		 *
		 * @since  1.5.0
		 * @param  string $creds Defaults.
		 * @return string Custom footer credits.
		 */
		public function mwb_footer_creds_filter($creds)
		{
			$creds = 'Copyright [footer_copyright] <a href="' . home_url() . '">MakeWebBetter</a>. All Rights Reserved.';

			//$creds .= '<a href="//www.dmca.com/Protection/Status.aspx?ID=63e19902-5254-4677-b423-5a4fc6f7c86c" title="DMCA.com Protection Status" class="dmca-badge"> <img src="//images.dmca.com/Badges/dmca-badge-w100-5x1-07.png?ID=63e19902-5254-4677-b423-5a4fc6f7c86c" alt="DMCA.com Protection Status"></a> <script src="//images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>';

			return $creds;
		}

		//Show product loop detail open
		public function mwb_woocommerce_show_product_loop_detail_open()
		{
			echo "<div class='mwb-product-detail'>";
		}

		//Show template loop product thumbnail
		public function mwb_woocommerce_template_loop_product_thumbnail()
		{
			global $post, $product;
			$post_thumbnail_id = get_post_thumbnail_id($post);
			$imageurl = wp_get_attachment_image_url($post_thumbnail_id, "full");
			echo '<a href="' . get_the_permalink() . '">
			<img src="' . $imageurl . '" height="200px">
			</a>';
		}

		//Show product loop detail close
		public function mwb_woocommerce_show_product_loop_detail_close()
		{
			echo "</div>";
		}

		//Show product loop wrapper open
		public function mwb_woocommerce_show_product_loop_wrapper_open()
		{
			echo "<div class='mwb-product-left'>";
		}

		//Show product loop view details
		public function mwb_woocommerce_template_loop_view_details()
		{

			$documentationlink = get_post_meta(get_the_ID(), 'mwb_plugin_documentation', true);
			?>
			<div class="mwb-view-more">
				<?php woocommerce_template_loop_add_to_cart(); ?>
				<a href="<?php echo get_the_permalink(); ?>" class="button product_type_simple">View Details</a>
			</div>
		<?php
		}

		//Show product loop wrapper close
		public function mwb_woocommerce_show_product_loop_wrapper_close()
		{
			echo "</div>";
		}

		//Show template loop link open
		public function mwb_woocommerce_template_loop_product_link_open()
		{
		?>
			<style>
				.site-inner {
					background-color: #f5f5f5;
				}

				a.button.product_type_simple.mwb_product_doc {
					background: #2a5ba3;
				}
			</style>
			<?php
		}


		/**
		 * get the product compatibility.
		 *
		 */
		public function mwb_woocommerce_show_product_compatibility()
		{
			//global $product;
			$html = "";
			$terms = get_the_terms(get_the_ID(), 'compatibility');
			if (!empty($terms)) {
				foreach ($terms as $key => $term) {
					$termlink = get_term_link($term, 'compatibility');
					$termname = $term->name;
					if (is_wp_error($termlink)) {
						continue;
					}
					$html .= "<a class='mwb_compatibility'   href='" . $termlink . "'>" . $termname . "</a>";
				}
			}
			return $html;
		}


		/**
		 * get the product sale flash.
		 *
		 */
		public function mwb_woocommerce_show_product_sale_flash()
		{
			global $product;
			if (isset($product) && !empty($product)) {
				wc_get_template('single-product/sale-flash.php');
			}
		}



		/**
		 * get the reviews and rating for the product.
		 *
		 */
		public function mwb_woocommerce_template_single_rating()
		{
			global $product;
			if (isset($product) && !empty($product)) {
				if (post_type_supports('product', 'comments')) {
					wc_get_template('single-product/rating.php');
				}
			}
		}

		//Show template review
		public function mwb_woocommerce_template_review()
		{
			global $product;
			if (isset($product) && !empty($product)) {
				wc_get_template('single-product/review.php');
			}
		}

		/**
		 * get the price of the product.
		 *
		 */
		public function mwb_woocommerce_template_single_price()
		{

			global $product;
			if (isset($product) && !empty($product)) {
				$product_id = $product->get_ID();
				if (has_term('services', 'product_cat', $product_id)) {
					$serviceid = $product_id;
					$ishourly = get_post_meta($serviceid, 'ew_is_hourly', true);
					$hourly_rate = get_post_meta($serviceid, 'ew_hourly_rate', true);
					$turnaround_time = get_post_meta($serviceid, 'mwb_turnaround_time', true);
					$ishourly = isset($ishourly) ? $ishourly : "no";
					if ($ishourly == "no") {
						wc_get_template('single-product/price.php');
					} else {
						echo "<h3>$" . number_format($hourly_rate, 2) . " / Hour</h3>";
					}
				} else {
					wc_get_template('single-product/price.php');
				}
			}
		}

		/**
		 * get the product documentation.
		 *
		 */
		public function mwb_woocommerce_show_product_documentation()
		{

			global $product;
			if (isset($product) && !empty($product)) {
				$productid = $product->get_id();
				$mwb_documentation_link = get_post_meta($productid, 'mwb_plugin_documentation', true);

				$link = "<a href='" . $mwb_documentation_link . "' target='_blank'>View Documentation</a>";
				return $link;
			}
		}


		/**
		 * get the add to cart button for the product.
		 *
		 */

		public function mwb_woocommerce_template_single_add_to_cart($atts)
		{

			global $product;
			if (isset($product) && !empty($product)) {
				$product_id = $product->get_ID();
				$product_title = $product->get_title();
				if (has_term('services', 'product_cat', $product_id)) {
					$serviceid = $product_id;
					$ishourly = get_post_meta($serviceid, 'ew_is_hourly', true);
					$hourly_rate = get_post_meta($serviceid, 'ew_hourly_rate', true);
					$turnaround_time = get_post_meta($serviceid, 'mwb_turnaround_time', true);
					$ishourly = isset($ishourly) ? $ishourly : "no";
					$product_link = get_permalink($product_id);
					if ($ishourly == "no") {
						global $woocommerce;
						$checkouturl = wc_get_checkout_url();
						echo '<a href="' . $checkouturl . '?add-to-cart=' . $product_id . '" class="button product_type_simple mwbbuynow">BUY NOW</a>';
						echo '<a href="' . $product_link . '?add-to-cart=' . $product_id . '" data-quantity="1" data-product_id="' . $product_id . '" data-product_sku="" class="button product_type_simple add_to_cart_button ajax_add_to_cart">ADD TO CART</a>';
					} else {
						echo "<a href='javascript:void(0);' class='button product_type_simple add_to_cart_button mwbmicroservicecontact' data-hourlyrate='" . $hourly_rate . "' data-productid='" . $product_id . "' data-name='" . $product_title . "'>Contact US</a>";
					}
				} else {
					$args = array();
					$defaults = array(
						'quantity' => 1,
						'class'    => implode(' ', array_filter(array(
							'button',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
						))),
					);
					$args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($args, $defaults), $product);

					$add_to_cart_text = $product->add_to_cart_text();
					if (isset($atts['label'])) {
						$add_to_cart_text = $atts['label'];
					}

					$quantity = $args['quantity'];
					$class = $args['class'];
					$checkouturl = wc_get_checkout_url();

					$product_id = $product->get_id();
					$add_to_cart_url = $product->add_to_cart_url();
					if (isset($atts['id'])) {
						$product_id = $atts['id'];
						$add_to_cart_url = get_permalink($product_id);
						$add_to_cart_url = add_query_arg('add-to-cart', $product_id, $add_to_cart_url);
					}

					$codecanyon_link = get_post_meta($product_id, 'mwb_codcanyon_product_link', true);
					$mwb_codecanyon_id = get_post_meta($product_id, 'mwb_codecanyon_product_id', true);
					$mwb_codecanyon_link = get_post_meta($product_id, 'mwb_codcanyon_product_link', true);

					if (isset($codecanyon_link) && !empty($codecanyon_link)) {
			?>
						<form class="cart" action="https://codecanyon.net/cart/add/<?php echo $mwb_codecanyon_id; ?>" accept-charset="UTF-8" method="post" id="mwb_buy_now_form">
							<input name="utf8" type="hidden" value="✓">
							<input type="hidden" value="regular" name="license">
							<input type="hidden" name="support" value="bundle_6month">
							<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="button product_type_simple mwbbuynow">Buy Now</button>
						</form>
					<?php
					} else {

						return apply_filters(
							'woocommerce_loop_add_to_cart_link',
							sprintf(
								'<a href="' . $checkouturl . '?add-to-cart=' . $product_id . '" class="button product_type_simple mwbbuynow">BUY NOW</a> <a href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
								esc_url($add_to_cart_url),
								esc_attr(isset($quantity) ? $quantity : 1),
								esc_attr($product_id),
								esc_attr($product->get_sku()),
								esc_attr(isset($class) ? $class : 'button'),
								esc_html($add_to_cart_text)
							),
							$product
						);
					}
				}
			}
		}


		/**
		 * get the meta field for the product.
		 *
		 */
		public function mwb_woocommerce_template_single_meta()
		{

			global $product;
			if (isset($product) && !empty($product)) {
				//wc_get_template( 'single-product/meta.php' );
				if (count($product->get_tag_ids()) > 0) {
					?>
					<div class="mwb_widget">
						<div class="product_meta">

							<?php do_action('woocommerce_product_meta_start'); ?>

							<?php /*if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

								<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>

							<?php endif;*/ ?>

							<?php //echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' );
							?>

							<?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'woocommerce') . ' ', '</span>'); ?>

							<?php do_action('woocommerce_product_meta_end'); ?>

						</div>
					</div>
			<?php
				}
			}
		}



		/**
		 * get the share button for the product.
		 *
		 */
		public function mwb_woocommerce_template_single_sharing()
		{

			global $product;
			if (isset($product) && !empty($product)) {
				wc_get_template('single-product/share.php');
			}
		}

		/**
		 * get the excerpt for the product.
		 *
		 */
		public function mwb_woocommerce_template_single_excerpt()
		{

			global $product;
			if (isset($product) && !empty($product)) {
				wc_get_template('single-product/short-description.php');
			}
		}


		/**
		 * get the image for the product.
		 *
		 */

		public function mwb_woocommerce_show_product_images()
		{

			global $post, $product;
			$columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
			$thumbnail_size    = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
			$post_thumbnail_id = get_post_thumbnail_id($post->ID);
			$full_size_image   = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
			$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
			$wrapper_classes   = apply_filters('woocommerce_single_product_image_gallery_classes', array(
				'woocommerce-product-gallery',
				'woocommerce-product-gallery--' . $placeholder,
				'woocommerce-product-gallery--columns-' . absint($columns),
				'images',
			));

			$attributes = array(
				'title'                   => get_post_field('post_title', $post_thumbnail_id),
				'data-caption'            => get_post_field('post_excerpt', $post_thumbnail_id),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			if (has_post_thumbnail()) {
				$html  = '<div data-thumb="' . get_the_post_thumbnail_url($post->ID, 'shop_thumbnail') . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_size_image[0]) . '">';
				$html .= get_the_post_thumbnail($post->ID, 'shop_single', $attributes);
				$html .= '</a></div>';
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce'));
				$html .= '</div>';
			}

			echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id($post->ID));

			do_action('woocommerce_product_thumbnails');
		}


		/**
		 * get the content for the product.
		 *
		 */
		public function mwb_woocommerce_template_single_content()
		{
			?>
			<!--<style>
				#genesis-content .entry, #genesis-content .single {
					max-width: 1160px;
				}
			</style> -->
			<?php
			the_content();
		}

		//Get availabel payment gateways
		public function mwb_woocommerce_available_payment_gateways($_available_gateways)
		{
			if (WC()->customer) {
				$billing_country = WC()->customer->get_billing_country();
				if ($billing_country != "IN") {
					unset($_available_gateways['payuindia']);
				} else {
					unset($_available_gateways['paypal']);
					unset($_available_gateways['stripe']);
				}
			}
			return $_available_gateways;
		}

		//Remove checkout page form fields
		public function mwb_woocommerce_checkout_fields($fields)
		{

			if (isset($fields['billing']['billing_company'])) {
				unset($fields['billing']['billing_company']);
			}
			// 			if(isset($fields['billing']['billing_address_1']))
			// 			{
			// 				unset($fields['billing']['billing_address_1']);
			// 			}
			// 			if(isset($fields['billing']['billing_address_2']))
			// 			{
			// 				unset($fields['billing']['billing_address_2']);
			// 			}
			// 			if(isset($fields['billing']['billing_state']))
			// 			{
			// 				unset($fields['billing']['billing_state']);
			// 			}
			// 			if(isset($fields['billing']['billing_city']))
			// 			{
			// 				unset($fields['billing']['billing_city']);
			// 			}
			// 			if(isset($fields['billing']['billing_postcode']))
			// 			{
			// 				unset($fields['billing']['billing_postcode']);
			// 			}

			if (isset($fields['billing']) && !empty($fields['billing'])) {
				foreach ($fields['billing'] as $key => $billing_fields) {
					if ($key == 'billing_phone') {
						if (isset($billing_fields['required'])) {
							$fields['billing'][$key]['required'] = 0;
						}
					}
				}
			}

			if (isset($fields['shipping']) && !empty($fields['shipping'])) {
				foreach ($fields['shipping'] as $key => $shipping_fields) {
					if (isset($shipping_fields['required'])) {
						$fields['shipping'][$key]['required'] = 0;
					}
				}
			}

			/*if(isset($fields['account']) && !empty($fields['account']))
			{
				foreach ($fields['account'] as $key => $account_fields) {
					if(isset($account_fields['required'])){
						$fields['account'][$key]['required'] = 0;
					}
				}
			}

			print_r($fields['account']);
			*/

			$fields['billing']['_billing_tax_gstin'] = array(
				'label'     => __('Tax No', 'woocommerce'),
				'required'  => false
			);

			return $fields;
		}

		public function mwb_override_default_address_fields($address_fields)
		{

			if (isset($address_fields)) {

				$chosen_payment_method = WC()->session->get('chosen_payment_method');
				if ($chosen_payment_method != 'stripe') {

					$address_fields['address_1']['required'] = false;
					$address_fields['state']['required'] = false;
					$address_fields['city']['required'] = false;
					$address_fields['postcode']['required'] = false;
				}
			}

			return $address_fields;
		}

		public function mwb_custom_checkout_field_update_order_meta($order_id)
		{
			if (!empty($_POST['_billing_tax_gstin'])) {
				update_post_meta($order_id, '_billing_tax_gstin', sanitize_text_field($_POST['_billing_tax_gstin']));
			}
		}

		public function mwb_edit_woocommerce_order_page($order)
		{
			global $post_id;
			$order = new WC_Order($post_id);
			$gst_no = get_post_meta($order->get_id(), '_billing_tax_gstin', true);
			if (isset($gst_no) && !empty($gst_no)) {
				echo '<p><strong>' . __('Tax No') . ':</strong> ' . $gst_no . '</p>';
			}
		}

		public function mwb_gstin_invoice($type, $order)
		{
			$order_id = $order->get_id();
			$gst_no = get_post_meta($order_id, '_billing_tax_gstin', true);
			if (isset($gst_no) && !empty($gst_no)) {
			?>
				<tr class="tax-number">
					<th><?php _e('Tax Number:', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
					<td><?php echo $gst_no; ?></td>
				</tr>
<?php
			}
		}

		public function mwb_order_checkout_fields($fields)
		{
			$fields['billing']['billing_email']['priority'] = 8;
			return $fields;
		}

		//adding form field args
		public function mwb_woocommerce_form_field_args($args, $key, $value)
		{

			if ($key == 'billing_phone') {
				$args['required'] = false;
			}
			// 			if($key == 'billing_first_name')
			// 			{
			// 				$args['required'] = false;
			// 			}
			// 			if($key == 'billing_last_name')
			// 			{
			// 				$args['required'] = false;
			// 			}
			if ($key == 'billing_country') {
				$args['required'] = false;
			}
			if ($key == 'account_password') {
				//$args['required'] = false;
			}
			return $args;
		}

		//Adding page template for microservices list page
		public function mwb_page_template($page_template)
		{
			if (is_page('services')) {
				$page_template = MWB_DIRPATH . 'microservices/microservice-list.php';
			}
			return $page_template;
		}

		//Adding product tax query
		public function mwb_services_woocommerce_product_query_tax_query($tax_query, $query)
		{
			if (is_shop()) {
				$exclude_tax_query = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => array('services', 'theme', 'template', 'snippet', 'icon', 'mautic-templates'),
					'operator' => 'NOT IN'
				);
				$tax_query[] = $exclude_tax_query;
			}
			return $tax_query;
		}

		/**
		 * Dynamically Generate Coupon Code
		 *
		 * @name mwb_wgm_coupon_generator
		 * @param number $length
		 * @return string
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function mwb_wgm_coupon_generator($length = 5)
		{
			if ($length == "") {
				$length = 5;
			}
			$password = '';
			$alphabets = range('A', 'Z');
			$numbers = range('0', '9');
			$final_array = array_merge($alphabets, $numbers);
			while ($length--) {
				$key = array_rand($final_array);
				$password .= $final_array[$key];
			}

			$giftcard_prefix = "MWB-";
			$password = $giftcard_prefix . $password;
			return $password;
		}

		/**
		 * This function is used to create giftcoupon for given amount
		 *
		 * @param $gift_couponnumber
		 * @param $couponamont
		 * @param $order_id
		 * @return boolean
		 */
		function mwb_create_abondand_coupon($mwb_couponnumber, $couponamount, $email)
		{
			$current_user_id = get_current_user_id();

			$couponalreadycreated = false;

			$args = array(
				'post_type'  => 'shop_coupon',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'customer_email',
						'value'   => $email,
						'compare' => '=',
					),
					array(
						'key'     => 'mwb_abondand_coupon',
						'value'   => "yes",
						'compare' => '=',
					),
				),
			);

			$query = new WP_Query($args);

			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$couponalreadycreated = true;
					break;
				}
				wp_reset_postdata();
			}

			if ($couponalreadycreated) {
				return false;
			}

			$cartproducts = array();
			$mwbusercarts = array();

			if ($current_user_id == 0) {
				// $finalusercart = get_option("mwb_guest_abondand_cart", array());

				// if(isset($finalusercart[$email]) && !empty($finalusercart[$email]))
				// {
				// 	$mwbusercarts = $finalusercart[$email]['cart'];
				// }

				global $woocommerce;

				if (isset($woocommerce->cart->cart_contents) && !empty($woocommerce->cart->cart_contents)) {
					$usercart = $woocommerce->cart->cart_contents;

					foreach ($usercart as $key => $value) {
						unset($value['data']);
						if (isset($usercart[$key])) {
							$mwbusercarts[$key] = $value;
						}
					}
				}
			} else {
				$mwbusercarts = get_user_meta($current_user_id, '_woocommerce_persistent_cart_1', true);
			}

			if (isset($mwbusercarts) && !empty($mwbusercarts)) {
				foreach ($mwbusercarts as $key => $mwbusercart) {
					$cartproductid = $mwbusercart['product_id'];
					if (isset($mwbusercart['variation_id']) && !empty($mwbusercart['variation_id'])) {
						$cartproductid = $mwbusercart['variation_id'];
					}

					$cartproducts[] = $cartproductid;
				}

				$cartproducts = implode(",", $cartproducts);

				$coupon_code = $mwb_couponnumber; // Code
				$amount = $couponamount; // Amount
				$discount_type = 'fixed_cart';
				$coupon_description = "ABONDAND COUPON FOR $email";

				$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => $coupon_description,
					'post_excerpt' => $coupon_description,
					'post_status' => 'publish',
					'post_type'		=> 'shop_coupon'
				);

				$new_coupon_id = wp_insert_post($coupon);
				$todaydate = date_i18n("Y-m-d");
				$expirydate = date_i18n("Y-m-d", strtotime("$todaydate +10 day"));

				// Add meta

				update_post_meta($new_coupon_id, 'discount_type', "percent");
				update_post_meta($new_coupon_id, 'coupon_amount', $amount);

				update_post_meta($new_coupon_id, 'expiry_date', $expirydate);
				update_post_meta($new_coupon_id, 'individual_use', "yes");

				update_post_meta($new_coupon_id, 'usage_limit', '');

				update_post_meta($new_coupon_id, 'apply_before_tax', "yes");
				update_post_meta($new_coupon_id, 'free_shipping', "no");

				update_post_meta($new_coupon_id, 'exclude_sale_items', "no");

				update_post_meta($new_coupon_id, 'mwb_abondand_coupon', "yes");

				update_post_meta($new_coupon_id, 'product_ids', $cartproducts);

				update_post_meta($new_coupon_id, 'customer_email', $email);
			}
			return true;
		}

		/**
		 * This function is used to create giftcoupon for given amount
		 *
		 * @param $gift_couponnumber
		 * @param $couponamont
		 * @param $order_id
		 * @return boolean
		 */
		function mwb_update_abondand_coupon($mwb_couponnumber, $couponamount, $email)
		{
			$coupon_code = $mwb_couponnumber; // Code
			$the_coupon = new WC_Coupon($coupon_code);
			$new_coupon_id = $the_coupon->get_id();

			$todaydate = date_i18n("Y-m-d");
			$expirydate = date_i18n("Y-m-d", strtotime("$todaydate +10 day"));

			// Add meta

			update_post_meta($new_coupon_id, 'expiry_date', $expirydate);

			$current_user_id = get_current_user_id();

			$cartproducts = array();
			$mwbusercarts = array();

			if ($current_user_id == 0) {
				$finalusercart = get_option("mwb_guest_abondand_cart", array());

				if (isset($finalusercart[$email]) && !empty($finalusercart[$email])) {
					$mwbusercarts = $finalusercart[$email]['cart'];
				}
			} else {
				$mwbuserpersistentcarts = get_user_meta($current_user_id, '_woocommerce_persistent_cart_1', true);

				$mwbusercarts = isset($mwbuserpersistentcarts['cart']) ? $mwbuserpersistentcarts['cart'] : array();
			}

			if (isset($mwbusercarts) && !empty($mwbusercarts)) {
				foreach ($mwbusercarts as $key => $mwbusercart) {
					$cartproductid = $mwbusercart['product_id'];
					$cartproducts[] = $cartproductid;
				}

				$cartproducts = implode(",", $cartproducts);
			}

			update_post_meta($new_coupon_id, 'product_ids', $cartproducts);
			return true;
		}
	}

	new MWB_Product_Customization();
}
?>