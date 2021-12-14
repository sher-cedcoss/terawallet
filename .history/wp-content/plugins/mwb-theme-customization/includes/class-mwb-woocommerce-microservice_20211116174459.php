<?php

/**
 * Exit if accessed directly
 */

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('MWB_WooCommerce_Microservice')) {
	/**
	 * This is class for managing order status and other functionalities .
	 *
	 * @name    MWB_WooCommerce_Microservice
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */

	class MWB_WooCommerce_Microservice
	{

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * The array of templates that this plugin tracks.
		 */
		protected $templates;

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		public function __construct()
		{

			$this->templates = array();

			// Add a filter to the attributes metabox to inject template into the cache.
			if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {

				// 4.6 and older
				add_filter('page_attributes_dropdown_pages_args', array($this, 'register_project_templates'));
			} else {

				// Add a filter to the wp 4.7 version attributes metabox
				add_filter('theme_page_templates', array($this, 'add_new_template'));
			}

			// Add a filter to the save post to inject out template into the page cache
			add_filter('wp_insert_post_data', array($this, 'register_project_templates'));

			// Add a filter to the template include to determine if the page has our
			// template assigned and return it's path
			add_filter('template_include', array($this, 'view_project_template'));

			// Add your templates to this array.
			$this->templates = array(
				'../microservices/microservice-list.php' => 'Microservices',
				'../themes/theme-list.php' => 'Themes',
				'../themes/template-list.php' => 'Template',
				'../themes/snippet-list.php' => 'Snippet',
				'../themes/icon-list.php' => 'Icon',
				'../themes/mautic-list.php' => 'Mautic Template',
				'../themes/free-mautic-list.php' => 'Free Mautic Template',
				'../hubspot/hubspot-template.php' => 'Hubspot',
				'../page-templates/hire_us.php' => 'Hire-Us',
				'../page-templates/seo.php' => 'SEO',
				'../page-templates/rma-page-template.php' => 'RMA',
				'../app-landing/index.php' => 'Magenative',
				'../page-templates/offer-registration.php' => 'Offers Registration',
				'../page-templates/hubspot-listing-page.php' => 'Hubspot listing',
				'../page-templates/mautic-ecommerce.php' => 'Mautic Ecommerce',
				'../page-templates/mwb-congratulations.php' => 'Congratulations Page',
				'../page-templates/affiliate-page-template.php' => 'Affiliate Page',
				'../page-templates/mwb-about.php' => 'About-Us'
			);

			add_shortcode(
				'mwb_microservice_related',
				array($this, 'mwb_microservice_related_function')
			);
			add_action('woocommerce_account_services_endpoint', array($this, 'mwb_services_endpoint_content'));
			add_action('woocommerce_account_free-themes_endpoint', array($this, 'mwb_free_themes_endpoint_content'));

			add_filter('woocommerce_product_query_tax_query', array($this, 'mwb_services_woocommerce_product_query_tax_query'), 10, 2);

			add_action('woocommerce_thankyou', array($this, 'mwb_services_woocommerce_order'), 5);

			add_action('woocommerce_after_single_product', array($this, 'mwb_woocommerce_after_single_product'), 5);

			add_action('wp_ajax_mwb_submit_service', array($this, 'mwb_submit_service'));
			add_action('wp_ajax_nopriv_mwb_submit_service', array($this, 'mwb_submit_service'));

			add_action('woocommerce_order_item_meta_end', array($this, 'mwb_woocommerce_order_item_meta_end'), 20, 3);

			add_action('init', array($this, 'mwb_submti_service_description'));

			add_action('admin_menu',  array($this, 'mwb_register_microservice_page'));

			add_action('add_meta_boxes', array($this, 'mwb_microservice_meta_boxes'));

			add_action('admin_enqueue_scripts', array($this, 'mwb_admin_enqueue_scripts'));

			add_action('save_post', array($this, 'mwb_save_microservice_extra'));

			add_shortcode('mwb_microservice_extra', array($this, 'mwb_microservice_extra_shortcode'));

			add_shortcode('mwb_product_support', array($this, 'mwb_product_support_shortcode'));
			add_shortcode('mwb_product_multiple_license', array($this, 'mwb_product_multiple_license'));

			add_filter('woocommerce_add_cart_item_data', array($this, 'mwb_add_cart_item_data'), 10, 2);

			add_filter('woocommerce_get_item_data', array($this, 'mwb_add_item_meta'), 10, 2);

			add_action('woocommerce_before_calculate_totals', array($this, 'mwb_woocommerce_before_calculate_totals'), 1);

			add_filter('woocommerce_get_cart_item_from_session', array($this, 'mwb_get_cart_session_data'), 10, 2);
			add_filter('woocommerce_account_menu_items', array($this, 'mwb_subscription_menu_items'), 10, 1);

			add_action('woocommerce_add_order_item_meta', array($this, 'mwb_order_item_meta'), 10, 2);
			add_action('init', array($this, 'mwb_rewrite_endpoint'));

			add_action('woocommerce_before_single_variation', array($this, 'mwb_support_in_variation'));
			add_action('woocommerce_after_add_to_cart_button', array($this, 'mwb_woocommerce_after_add_to_cart_button'));

			add_filter('woocommerce_structured_data_review', array($this, 'mwb_review_schema_fix'), 10, 2);

			// 			add_action( 'woocommerce_review_order_after_cart_contents', array( $this, 'mwb_wc_discount_total_30' ),10 );

			add_action('woocommerce_cart_totals_after_order_total', array($this, 'mwb_wc_discount_total_30'), 10);
			add_action('woocommerce_review_order_after_order_total', array($this, 'mwb_wc_discount_total_30'), 10);

			add_shortcode('mwb_review_schema_sc', array($this, 'mwb_review_schema_sc'));
		}

		function mwb_review_schema_sc()
		{

			global $post, $wpdb;

			$shop_name = get_bloginfo('name');
			$shop_url  = home_url();
			$permalink = get_the_permalink($post->ID);

			$markup = array(
				'@context'    => 'http://schema.org/',
				'@type'       => 'Organization',
				'@id'         => $permalink . '#Organization',
				'url'         => $shop_url,
				'name'        => $shop_name,
				'telephone'   => '+18885752397',
			);

			$address = array(
				'@type'              => 'PostalAddress',
				'streetAddress'      => 'Northeast Sumner Street 11923',
				'addressLocality'    => 'Portland',
				'postalCode'         => '97220',
				'addressCountry'     => array(
					'@type'        => 'Country',
					'name'         => 'US',
				),
			);

			$markup['address'] = $address;

			if (wc_review_ratings_enabled()) {

				$review_count = get_comments(array(
					'status'   => 'approve',
					'post_status' => 'publish',
					'post_type'   => 'product',
					'count' => true
				));

				$product_rating = $wpdb->get_results("
                    SELECT t.slug, tt.count
                    FROM {$wpdb->prefix}terms as t
                    JOIN {$wpdb->prefix}term_taxonomy as tt ON tt.term_id = t.term_id
                    WHERE t.slug LIKE 'rated-%' AND tt.taxonomy LIKE 'product_visibility'
                    ORDER BY t.slug
                ");

				$stars = 1;
				$average = 0;
				$total_count = 0;
				if (sizeof($product_rating) > 0) {
					foreach ($product_rating as $values) {
						$average += $stars * $values->count;
						$total_count += $values->count;
						$stars++;
					}

					$avg_rating = round($average / $total_count, 1);
				} else {
					$avg_rating = 5;
				}

				$markup['aggregateRating'] = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => $avg_rating,
					'reviewCount' => $review_count,
				);

				// Markup 5 most recent rating/review.
				$comments = get_comments(
					array(
						'number'      => 15,
						'status'      => 'approve',
						'post_status' => 'publish',
						'post_type'   => 'product',
						'parent'      => 0,
						'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
							array(
								'key'     => 'rating',
								'type'    => 'NUMERIC',
								'compare' => '>',
								'value'   => 0,
							),
						),
					)
				);

				if ($comments) {
					$markup['review'] = array();
					foreach ($comments as $comment) {
						$markup['review'][] = array(
							'@type'         => 'Review',
							'reviewRating'  => array(
								'@type'       => 'Rating',
								'bestRating'  => '5',
								'ratingValue' => get_comment_meta($comment->comment_ID, 'rating', true),
								'worstRating' => '1',
							),
							'author'        => array(
								'@type' => 'Person',
								'name'  => get_comment_author($comment),
							),
							'reviewBody'    => get_comment_text($comment),
							'datePublished' => get_comment_date('c', $comment),
						);
					}
				}
			}

			$mark_json = json_encode($markup);

			ob_start();
			echo '<script type="application/ld+json">';
			echo $mark_json;
			echo '</script>';
			return ob_get_clean();
		}


		function mwb_wc_discount_total_30()
		{

			global $woocommerce;

			$discount_total = 0;
			$subscription_product = false;

			foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {

				$_product = $values['data'];

				$product_type = $_product->get_type();

				if ($product_type == "variation") {
					$prod_id = $_product->get_parent_id();
				} else {
					$prod_id = $_product->get_id();
				}

				$subscription_enable = get_post_meta($prod_id, 'mwb_subscription_enable', true);

				if ($subscription_enable == "yes") {
					$subscription_product = true;
				}

				if ($_product->is_on_sale()) {
					$regular_price = $_product->get_regular_price();
					$sale_price = $_product->get_sale_price();
					$discount = ($regular_price - $sale_price) * $values['quantity'];
					$discount_total += $discount;
				}
			}

			if ($subscription_product == false) {
				if ($discount_total > 0) {
					echo '<tr class="mwb-discount-save-msg">
				    <td colspan="2"><div class="mwb-discount-save-msg-content">You have saved '
						. wc_price($discount_total + $woocommerce->cart->discount_cart) . ' on the order</td>
				    </tr>';
				}
			}
		}


		/**
		 * To fix schema for review section over product pages
		 *
		 */
		public function mwb_review_schema_fix($markup, $comment)
		{

			$product = wc_get_product($comment->comment_post_ID);

			$shop_name = get_bloginfo('name');
			$shop_url  = home_url();
			$currency  = get_woocommerce_currency();
			$permalink = get_permalink($product->get_id());

			// Declare SKU or fallback to ID.
			if ($product->get_sku()) {
				$sku = $product->get_sku();
			} else {
				$sku = $product->get_id();
			}

			if ('' !== $product->get_price()) {
				// Assume prices will be valid until the end of next year, unless on sale and there is an end date.
				$price_valid_until = date('Y-12-31', current_time('timestamp', true) + YEAR_IN_SECONDS);

				if ($product->is_type('variable')) {
					$lowest  = $product->get_variation_price('min', false);
					$highest = $product->get_variation_price('max', false);

					if ($lowest === $highest) {
						$markup_offer = array(
							'@type'              => 'Offer',
							'price'              => wc_format_decimal($lowest, wc_get_price_decimals()),
							'priceValidUntil'    => $price_valid_until,
							'priceSpecification' => array(
								'price'                 => wc_format_decimal($lowest, wc_get_price_decimals()),
								'priceCurrency'         => $currency,
								'valueAddedTaxIncluded' => wc_prices_include_tax() ? 'true' : 'false',
							),
						);
					} else {
						$markup_offer = array(
							'@type'      => 'AggregateOffer',
							'lowPrice'   => wc_format_decimal($lowest, wc_get_price_decimals()),
							'highPrice'  => wc_format_decimal($highest, wc_get_price_decimals()),
							'offerCount' => count($product->get_children()),
						);
					}
				} else {
					if ($product->is_on_sale() && $product->get_date_on_sale_to()) {
						$price_valid_until = date('Y-m-d', $product->get_date_on_sale_to()->getTimestamp());
					}
					$markup_offer = array(
						'@type'              => 'Offer',
						'price'              => wc_format_decimal($product->get_price(), wc_get_price_decimals()),
						'priceValidUntil'    => $price_valid_until,
						'priceSpecification' => array(
							'price'                 => wc_format_decimal($product->get_price(), wc_get_price_decimals()),
							'priceCurrency'         => $currency,
							'valueAddedTaxIncluded' => wc_prices_include_tax() ? 'true' : 'false',
						),
					);
				}

				$markup_offer += array(
					'priceCurrency' => $currency,
					'availability'  => 'http://schema.org/' . ($product->is_in_stock() ? 'InStock' : 'OutOfStock'),
					'url'           => $permalink,
					'seller'        => array(
						'@type' => 'Organization',
						'name'  => $shop_name,
						'url'   => $shop_url,
					),
				);

				$offers =  $markup_offer;
			}

			if ($product->get_rating_count() && wc_review_ratings_enabled()) {
				$aggregateRating = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => $product->get_average_rating(),
					'reviewCount' => $product->get_review_count(),
				);

				// Markup most recent rating/review.
				$comments = get_comments(
					array(
						'number'      => 1,
						'post_id'     => $product->get_id(),
						'status'      => 'approve',
						'post_status' => 'publish',
						'post_type'   => 'product',
						'parent'      => 0,
						'meta_key'    => 'rating',
						'orderby'     => 'meta_value_num',
					)
				);

				if ($comments) {
					foreach ($comments as $comm) {
						$rating = get_comment_meta($comm->comment_ID, 'rating', true);

						if (!$rating) {
							continue;
						}

						$review = array(
							'@type'        => 'Review',
							'reviewRating' => array(
								'@type'       => 'Rating',
								'ratingValue' => $rating,
							),
							'author'       => array(
								'@type' => 'Person',
								'name'  => get_comment_author($comm->comment_ID),
							),
						);
					}
				}
			}

			$brand = array(
				'@type'  => 'Thing',
				'name'  =>  'MakeWebBetter',
			);

			$markup['itemReviewed']  = array(
				'@type' => 'Product',
				'name'  => get_the_title($comment->comment_post_ID),
				'brand' => $brand,
				'mpn' => 'MWB00' . $comment->comment_post_ID,
				'image'       => wp_get_attachment_url($product->get_image_id()),
				'description' => wp_strip_all_tags(do_shortcode($product->get_short_description() ? $product->get_short_description() : $product->get_description())),
				'sku' => $sku,
				'aggregateRating' => $aggregateRating,
				'review' => $review,
				'offers' => $offers,

			);

			return $markup;
		}

		/**
		 * Adds our template to the pages cache in order to trick WordPress
		 * into thinking the template file exists where it doens't really exist.
		 */
		public function register_project_templates($atts)
		{

			// Create the key used for the themes cache
			$cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

			// Retrieve the cache list.
			// If it doesn't exist, or it's empty prepare an array
			$templates = wp_get_theme()->get_page_templates();
			if (empty($templates)) {
				$templates = array();
			}

			// New cache, therefore remove the old one
			wp_cache_delete($cache_key, 'themes');

			// Now add our template to the list of templates by merging our templates
			// with the existing templates array from the cache.
			$templates = array_merge($templates, $this->templates);

			// Add the modified cache to allow WordPress to pick it up for listing
			// available templates
			wp_cache_add($cache_key, $templates, 'themes', 1800);

			return $atts;
		}

		/**
		 * Adds our template to the page dropdown for v4.7+
		 *
		 */
		public function add_new_template($posts_templates)
		{
			$posts_templates = array_merge($posts_templates, $this->templates);
			return $posts_templates;
		}

		/**
		 * Checks if the template is assigned to the page
		 */
		public function view_project_template($template)
		{

			// Get global post
			global $post;

			// Return template if post is empty
			if (!$post) {
				return $template;
			}

			// Return default template if we don't have a custom one defined
			if (!isset($this->templates[get_post_meta(
				$post->ID,
				'_wp_page_template',
				true
			)])) {
				return $template;
			}

			$file = plugin_dir_path(__FILE__) . get_post_meta($post->ID, '_wp_page_template', true);

			// Just to be safe, we check if the file exist first
			if (file_exists($file)) {
				return $file;
			} else {
				echo $file;
			}

			// Return template
			return $template;
		}

		//Shortcode for related products on microservices description page
		function mwb_microservice_related_function()
		{
			global $product;

			if (isset($product) && !empty($product)) {
				$serviceterms = array();
				$product_id = $product->get_id();
				$turn_around_time = get_post_meta($product_id, "mwb_turnaround_time", true);
				if (isset($turn_around_time) && !empty($turn_around_time)) {
					$ew_is_hourly = get_post_meta($product_id, "ew_is_hourly", true);

					if (isset($ew_is_hourly) && !empty($ew_is_hourly)) {
						if ($ew_is_hourly == "no") {
							echo "<p class='mwb_turnaround_time'><strong>Turn Around Time:</strong> $turn_around_time Days or less</p>";
						}
					}
				}
				$args = array(
					'post_type' => 'product',
					'post__not_in' => array($product_id),
					'posts_per_page' => 4,
					'orderby' => 'rand',
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => 'services',
						)
					),
				);

				$terms = wp_get_post_terms($product_id, 'microservice');
				if (isset($terms) && !empty($terms)) {
					foreach ($terms as $key => $term) {
						$serviceterms[] = $term->term_id;
					}
				}
				if (isset($serviceterms) && !empty($serviceterms)) {
					foreach ($serviceterms as $key => $serviceterm) {
						$servicetermquery[] = array(
							'taxonomy' => 'microservice',
							'field'    => 'term_id',
							'terms'    => $serviceterm,
						);
						$servicetermquery['relation'] = "OR";
					}

					$args['tax_query'][] = $servicetermquery;
					$args['relation'] = "AND";
				}
				$query = new WP_Query($args);
				if ($query->have_posts()) {
?>
					<h4 class='mwb_related_service_heading'>You may like this</h4>
					<ul>
						<?php
						while ($query->have_posts()) {
							$query->the_post();
							$relatedserviceid = get_the_ID();
							$serviceicon = get_post_meta($relatedserviceid, "mwb_service_icon", true);
						?>
							<li class="mwb_related_service_list">
								<div class="mwb_related_service_wrapper">
									<div class="mwb_related_service_center">
										<span>
											<a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title(); ?>
											</a>
										</span>
									</div>
									<!-- <strong> -->
									<?php
									// $ishourly = get_post_meta($relatedserviceid, 'ew_is_hourly', true);
									// $hourly_rate = get_post_meta($relatedserviceid, 'ew_hourly_rate', true);
									// $turnaround_time = get_post_meta($relatedserviceid, 'mwb_turnaround_time', true);
									// $ishourly = isset($ishourly)?$ishourly:"no";

									// $product = new WC_Product($relatedserviceid);
									// $product_slug = $product->get_slug();

									// if($ishourly == "no")
									// {
									// 	if($product_slug == 'hubspot-cos-development')
									// 	{
									// 		echo '&nbsp;';
									// 	}
									// 	else
									// 	{
									// 		echo $product->get_price_html();
									// 	}
									// }
									// else
									// {
									// 	echo "<span class='woocommerce-Price-amount amount'><span class='woocommerce-Price-currencySymbol'>$</span>".number_format($hourly_rate, 2)." / Hour</span>";
									// }
									?>
									<!-- </strong> -->
									<div class="mwb-button">
										<?php
										// if($ishourly == "no")
										// {
										// if($product_slug == 'hubspot-cos-development')
										// {
										?>
										<!-- <a class="mwbaddtocartbutton button product_type_simple add_to_cart_button ajax_add_to_cart" href="<?php //echo get_permalink($relatedserviceid);
																																				?>">View Detail</a> -->
										<?php
										// }
										// else
										// {
										?>
										<!-- <a class="mwbaddtocartbutton button product_type_simple add_to_cart_button ajax_add_to_cart" href="<?php //echo home_url('/store')
																																				?>?add-to-cart=<?php //echo $relatedserviceid;
																																																?>" data-quantity="1" data-product_id="<?php //echo $relatedserviceid;
																																																																	?>">Add to Cart</a> -->
										<?php
										// 	}
										// }
										// else
										// {
										?>
										<a class="mwbaddtocartbutton button product_type_simple add_to_cart_button ajax_add_to_cart" href="<?php echo get_permalink($relatedserviceid); ?>">View Detail</a>
										<?php
										// }
										?>
									</div>
								</div>
							</li>
						<?php
						}
						?>
					</ul>
				<?php
				}
			}
		}

		//Shortcode for Microservices extra
		public function mwb_microservice_extra_shortcode($atts)
		{
			global $product;
			if (isset($product) && !empty($product)) {
				$product_id = $product->get_ID();
				$product_price = $product->get_price();
				$microservicejson = get_post_meta($product_id, 'mwb_microservice_details', true);
				$microservices = json_decode($microservicejson, true);

				if (isset($microservices) && !empty($microservices)) {
					$html = "<form class='cart' method='post' enctype='multipart/form-data'><ul class='mwb_microservice_extra_wrapper'>";
					$html .= "<input type='hidden' value='" . $product_price . "' id='mwb_extra_service_price'>";
					foreach ($microservices as $key => $microservice) {
						$html .= "<li class='mwb_microservice_extra_list'><input type='checkbox' value='" . $microservice['price'] . "' name='microservice_extra_" . $key . "' class='microservice_extra_check'><span class='mwb-service-detail'>" . $microservice['service'] . "</span>" . wc_price($microservice['price']) . "</li>";
					}
					$html .= "<li class='mwb_microservice_extra_list mwb_microservice_total_wrapper'><span class='mwb_microservice_total'><strong>Total:</strong></span>" . wc_price($product_price) . "</li>";
					$html .= "</ul>";
					$html .= "<input type='hidden' name='quantity' value='1'>
						<button value='" . $product->get_id() . "' name='add-to-cart' class='single_add_to_cart_button button alt'>" . $product->single_add_to_cart_text() . "</button>
					</form>";
					return $html;
				}
			}
		}

		//Shortcode for Support license
		public function mwb_product_support_shortcode($atts)
		{
			global $product;
			if (isset($product) && !empty($product)) {
				$product_id = $product->get_ID();
				$product_price = $product->get_price();

				if ($product->is_type('variable')) {
					$fname = 'mwb_extend_support_variation';
				} else {
					$fname = 'mwb_extend_support';
				}
				$enable_support = get_post_meta($product_id, 'mwb_enable_support_option', true);
				$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
				$mwb_data = json_decode($mwb_microservice_notification_json, true);
				$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
				$mwb_support_price = intval($product_price * ($mwb_purchase_time_support / 100));
				if (isset($enable_support) && !empty($enable_support) && $enable_support[0] == 'true') {
					$html = "<table><tr><td>";
					$html .= "<input type='hidden' id='mwb_product_price' name='mwb_product_price' value='" . $product_price . "' /><input type='hidden' id='mwb_product_id' name='mwb_product_id' value='" . $product_id . "' /><input type='checkbox' id='" . $fname . "' name='" . $fname . "' value='" . $mwb_support_price . "' /></td><td> <p class='mwb_extend_support'><b>Extend Support to 12 Months</b></p><p class='mwb_extend_support_saving'>Get it now and save up to 65%.</p></td>";

					$html .= "<td id='mwb_ajax_price'>" . wc_price($mwb_support_price) . "</td>";
					$html .= "</tr></table>";
					return $html;
				}
			}
		}

		//Support license shortcode apply on variable product
		public function mwb_support_in_variation()
		{
			global $product;
			if ($product->is_type('variable')) {
				echo do_shortcode('[mwb_product_support]');
			}
		}

		//Adding form field after add to cart button for support and multiple license
		public function mwb_woocommerce_after_add_to_cart_button()
		{
			global $product;
			if ($product->is_type('variable')) {
				$product_id = $product->get_ID();
				// $support_price = get_post_meta($product_id, 'mwb_12_month_support_price', true);
				$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
				$mwb_data = json_decode($mwb_microservice_notification_json, true);
				$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
				$price = $product->get_price();
				$support_price = intval($price * ($mwb_purchase_time_support / 100));
				$support_meta = $support_price;

				echo '<input type="hidden" name="support_cart" value="' . $support_meta . '">';
			}
		}

		//getting variations of variable product for multiple license
		public function mwb_product_multiple_license()
		{

			global $product;
			if ($product->is_type('variable')) {

				// Enqueue variation scripts.
				wp_enqueue_script('wc-add-to-cart-variation');

				// Get Available variations?
				$get_variations = count($product->get_children()) <= apply_filters('woocommerce_ajax_variation_threshold', 30, $product);

				// Load the template.
				wc_get_template('single-product/add-to-cart/variable.php', array(
					'available_variations' => $get_variations ? $product->get_available_variations() : false,
					'attributes'           => $product->get_variation_attributes(),
					'selected_attributes'  => $product->get_default_attributes(),
				));
			}
		}

		//microservices job description form
		function mwb_services_endpoint_content()
		{
			global $wp_query;
			$subscriptiontab = $wp_query->query['services'];

			if (isset($subscriptiontab) && !empty($subscriptiontab)) {
				if (is_numeric($subscriptiontab)) {
					$order_id = $subscriptiontab;
					$order = new WC_Order($order_id);

					if (!$order = wc_get_order($order_id)) {
						return;
					}

					$order_items           = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
					$show_purchase_note    = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
					$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
					$downloads             = $order->get_downloadable_items();
					$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

					if ($show_downloads) {
						wc_get_template('order/order-downloads.php', array('downloads' => $downloads, 'show_title' => true));
					}
				?>
					<section class="woocommerce-order-details">
						<div class="mwb_order_detail_wrapper">
							<h2 class="woocommerce-order-details__title"><?php _e('Service details', 'woocommerce'); ?></h2>

							<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
								<thead>
									<tr>
										<th class="woocommerce-table__product-name product-name"><?php _e('Product', 'woocommerce'); ?></th>
										<th class="woocommerce-table__product-table product-total"><?php _e('Total', 'woocommerce'); ?></th>
									</tr>
								</thead>

								<tbody>
									<?php
									foreach ($order_items as $item_id => $item) {
										$product = apply_filters('woocommerce_order_item_product', $item->get_product(), $item);
										$product_id = $product->get_ID();
										if (has_term('services', 'product_cat', $product_id)) {
											wc_get_template('order/order-details-item.php', array(
												'order'			     => $order,
												'item_id'		     => $item_id,
												'item'			     => $item,
												'show_purchase_note' => $show_purchase_note,
												'purchase_note'	     => $product ? $product->get_purchase_note() : '',
												'product'	         => $product,
											));
										}
									}
									?>
									<?php do_action('woocommerce_order_items_table', $order); ?>
								</tbody>
							</table>
					</section>
					<?php
				} else {
					if (strpos($subscriptiontab, "/submit/") !== false) {
						$urldata = explode("/", $subscriptiontab);
						if (isset($urldata[0]) && !empty($urldata[0]) && isset($urldata[2]) && !empty($urldata[2])) {
							$order_id = $urldata[0];
							$item_id = $urldata[2];
							$itemmetakey = $order_id . "-" . $item_id;
							$microserviceid = get_post_meta($order_id, $itemmetakey, true);
							$hourlyrate = get_post_meta($microserviceid, 'ew_hourly_rate', true);
							$email = get_post_meta($microserviceid, 'ew_email_address', true);
							$productid = get_post_meta($microserviceid, 'ew_microservice_id', true);
							$post_title = get_post_meta($microserviceid, 'ew_microservice_name', true);

							$order = wc_get_order($order_id);

							$order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));

							foreach ($order_items as $items_id => $item) {
								if ($items_id == $item_id) {
									$mwb_price = $order->get_formatted_line_subtotal($item);
									break;
								}
							}

							$job_description_submitted = get_post_meta($microserviceid, "mwb_job_description_submitted", true);

							$jobdescription = get_post($microserviceid)->post_content;

							$servicedetails = new WC_Product($productid);
							$servicename = $servicedetails->get_title();
					?>
							<div class="mwb_extras">
								<h4>Enter your Job description:</h4>
								<table class="shop_table">
									<tr>
										<th>Order Id</th>
										<td>#<?php echo $order_id; ?></td>
									</tr>
									<tr>
										<th>Service Name</th>
										<td><?php echo $servicename . " - " . wc_price($hourlyrate); ?>
											<?php wc_display_item_meta($item); ?></td>
									</tr>
									<tr>
										<th>Email</th>
										<td><?php echo $email; ?></td>
									</tr>
									<tr>
										<th>Amount</th>
										<td><?php echo $mwb_price; ?></td>
									</tr>
								</table>
								<form method="post">
									<input type="hidden" value="<?php echo $microserviceid; ?>" name="microservicequeryid">
									<p class="mwb_microservice_field"><label>Job Description</label></p>

									<?php
									if ($job_description_submitted) {
										echo $jobdescription;
									} else {
									?>
										<textarea name="mwb_service_description" id="mwb_service_description"><?php echo $jobdescription; ?></textarea>
										<p><input type="submit" class="button" value="Submit" name="ew_submit_service_description"></p>
									<?php
									}
									?>
								</form>
							</div>
					<?php
							/*$args = array(
										'post_id' => $microserviceid, // use user_id

									);
									$comments = get_comments($args);
									if(isset($comments) && !empty($comments)){
										$comments = array_reverse($comments);
										echo "<h4>Comments are:</h4>";
										foreach($comments as $comment) :
											?>
											<p><strong><?php echo $comment->comment_author;?>: </strong><?php echo $comment->comment_content;?></p>
											<?php
										endforeach;
									}

									comment_form( array() , $microserviceid );

									*/

							if (isset($_SESSION['microservicequerysubmit']) && !empty($_SESSION['microservicequerysubmit'])) {
								$description = $_POST['mwb_service_description'];

								$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
								$mwb_data = json_decode($mwb_microservice_notification_json, true);

								$adminmail = $mwb_data['mwb_microservice_fixedmailaddress'];
								$subject = $mwb_data['mwb_microservice_fixedmailsubject'];
								$message = $mwb_data['mwb_microservice_fixedmail_content'];

								$subject = str_replace("{servicename}", $servicename, $subject);
								$subject = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $subject);
								$subject = str_replace("{serviceusermail}", $email, $subject);
								$subject = str_replace("{servicedescription}", $description, $subject);

								$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
								$subject = str_replace("{servicerecordlink}", $servicerecordlink, $subject);

								$message = str_replace("{servicename}", $servicename, $message);
								$message = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $message);
								$message = str_replace("{serviceusermail}", $email, $message);
								$message = str_replace("{servicedescription}", $description, $message);

								$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
								$message = str_replace("{servicerecordlink}", $servicerecordlink, $message);

								wc_mail($adminmail, $subject, $message);

								// Send Service Contact Success mail to Customer

								$to = $email;
								$subject = $mwb_data['mwb_microservice_userdescriptionfixedmailsubject'];
								$message = $mwb_data['mwb_microservice_userdescriptionfixedmail_content'];

								$subject = str_replace("{servicename}", $servicename, $subject);
								$subject = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $subject);
								$subject = str_replace("{serviceusermail}", $email, $subject);
								$subject = str_replace("{servicedescription}", $description, $subject);

								$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
								$subject = str_replace("{servicerecordlink}", $servicerecordlink, $subject);

								$message = str_replace("{servicename}", $servicename, $message);
								$message = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $message);
								$message = str_replace("{serviceusermail}", $email, $message);
								$message = str_replace("{servicedescription}", $description, $message);

								$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
								$message = str_replace("{servicerecordlink}", $servicerecordlink, $message);
								wc_mail($to, $subject, $message);
								unset($_SESSION['microservicequerysubmit']);
							}
						}
					}
				}
			} else {

				$customer_orders = get_posts(array(
					'post_type'   => wc_get_order_types(),
					'numberposts' => -1,
					'post_status' => array_keys(wc_get_order_statuses()),
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => '_customer_user',
							'value'   =>  get_current_user_id(),
						),
						array(
							'key'     => 'mwb_service_order',
							'value'   =>  true,
						),
					)
				));


				if (isset($customer_orders) && !empty($customer_orders)) :
					?>
					<h4>Your Ordered Services</h4>
					<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
						<thead>
							<tr>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Order</span></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">Date</span></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr">Status</span></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><span class="nobr">Actions</span>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($customer_orders as $customer_orderpost) :
								$customer_order = $customer_orderpost->ID;
								$order      = wc_get_order($customer_order);
								$item_count = $order->get_item_count();
							?>
								<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr($order->get_status()); ?> order">
									<?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) :
										if ('order-total' != $column_id) :
									?>
											<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>">
												<?php if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) : ?>
													<?php do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order); ?>

												<?php elseif ('order-number' === $column_id) : ?>
													<a href="<?php echo esc_url($order->get_view_order_url()); ?>">
														<?php echo _x('#', 'hash before order number', 'woocommerce') . $order->get_order_number(); ?>
													</a>

												<?php elseif ('order-date' === $column_id) : ?>
													<time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time>

												<?php elseif ('order-status' === $column_id) : ?>
													<?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>

												<?php elseif ('order-actions' === $column_id) :
													$microservicesdetail = home_url('/my-account/services/') . $customer_order;
												?>
													<a href="<?php echo $microservicesdetail ?>" class="woocommerce-button button view">View</a>
												<?php endif; ?>
											</td>
									<?php
										endif;
									endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php
				else :
				?>
					<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
						<a class="woocommerce-Button button" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
							<?php _e('Go shop', 'woocommerce') ?>
						</a>
						<?php _e('No order has been made yet.', 'woocommerce'); ?>
					</div>
				<?php endif; ?>

			<?php do_action('woocommerce_after_account_orders', $has_orders);
			}
		}

		//Adding product tax query
		function mwb_services_woocommerce_product_query_tax_query($tax_query, $query)
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

		public function mwb_free_themes_endpoint_content()
		{
			?>
			<h4>Free Themes Offered For You</h4>
			<?php
			$tax_query = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => array('theme'),
				'operator' => 'IN'
			);

			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 12,
				'tax_query' => array($tax_query),
				'order'   => 'asc'
			);
			$the_query = new WP_Query($args);

			// print_r($the_query);die;
			if ($the_query->have_posts()) {
			?>
				<div id="pg-<?php echo $postid; ?>-0" class="panel-grid panel-no-style mwb-wp-layout-wrapper">
					<?php
					while ($the_query->have_posts()) {
						$the_query->the_post();

						global $post;
						$product_id = get_the_ID();
						$post_thumbnail_id = get_post_thumbnail_id($post);
						$image = wp_get_attachment_image_src($post_thumbnail_id, "full", false);
						if ($image) {
							list($src, $width, $height) = $image;
							$product_image = $src;
						} else {
							$product_image = wc_placeholder_img_src();
						}
						$product = new WC_Product($product_id);
						$product_price = $product->get_price();
						if ($product_price == 0) {
					?>
							<div id="pgc-<?php echo $postid; ?>-0-0" class="panel-grid-cell mwb-wp-layout-wrapper-div">
								<div id="panel-<?php echo $postid; ?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
									<div class="so-widget-sow-editor so-widget-sow-editor-base">
										<div class="siteorigin-widget-tinymce textwidget clearfix mwb-wp-layout-wrapper-col">
											<div class="mwb-wp-layout__img">
												<a href="<?php echo get_the_permalink() ?>">
													<img src="<?php echo $product_image; ?>" width="" height="" alt="" />
												</a>
											</div>
											<div class="mwb-wp-layout__content">
												<h2 class="mwb-wp-layout__title"><a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title(); ?></a></h2>
												<p class="mwb-wp-layout__description"><?php echo get_the_excerpt(); ?></p>
												<div class="mwb-wp-layout__category-price clearfix">
													<div class="mwb-wp-layout__category">
														<?php
														$terms = get_the_terms($product_id, 'theme_category');
														$separator = ' , ';
														if (!empty($terms)) {
														?>
															<?php
															$output = "";
															foreach ($terms as $term) {

																$output .= $term->name . $separator;
															}
															$output = trim($output, $separator);
															?>
															<span>Category: <?php echo $output; ?></span>
														<?php
														}
														?>

													</div>
													<div class="mwb-wp-layout__price">
														<?php
														if ($product_price == 0) {
															echo '<span class="mwb_price_free"><strong>FREE</strong></span>';
														} else {
															echo '<span class="woocommerce-Price-amount amount">' . wc_price($product_price) . '</span>';
														}
														?>
													</div>
												</div>
												<div class="mwb-wp-layout__details">
													<?php
													$mwb_theme_feature_content = get_post_meta($product_id, 'mwb_theme_feature_content', true);
													echo $mwb_theme_feature_content;
													?>

												</div>
												<div class="mwb-wp-layout__button">
													<a href="<?php echo get_the_permalink() ?>" class="button mwb-wp-layout__button-details">View Details</a>
													<?php $mwb_demo_preview = get_post_meta($product_id, 'mwb_plugin_front_end_demo', true); ?>
													<a href="<?php echo $mwb_demo_preview; ?>" class="button mwb-wp-layout__button-preview" target="_blank">Preview</a>
													<a href="<?php echo home_url(); ?>/cart/?add-to-cart=<?php echo $product_id; ?>" class="button mwb-wp-layout__button-cart">
														<?php
														if ($product_price == 0) {
															echo '<i class="fa fa-download"></i>';
														} else {
															echo '<i class="fa fa-shopping-cart"></i>';
														}
														?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
					<?php
						}
					}
					wp_reset_postdata();
					?>
				</div>
			<?php
			} else {
			?>
				<div class="mwb-empty">
					<h2>No Products Found</h2>
				</div>
				<?php
			}
		}

		//Function for adding functionality at Thankyou page
		function mwb_services_woocommerce_order($order_id)
		{
			if (!$order_id) {
				return;
			}

			$mwb_support_renewal_status = get_post_meta($order_id, "mwb_support_renewal_status", true);
			if (empty($mwb_support_renewal_status)) {
				$order = wc_get_order($order_id);
				$order_date = $order->get_date_completed();
				$items = $order->get_items();
				$support_extended = true;
				foreach ($items as $item_id => $item) {
					$item_meta = $item->get_meta_data();
					foreach ($item_meta as $item_key => $item_value) {
						$item_data = $item_value->get_data();
						if ($item_data['key'] == 'Extended Support to 12 Month') {
							$support_purchase = true;
						}

						if ($item_data['key'] == 'Support & Updates') {
							$support_purchase = true;
						}

						if ($item_data['key'] == 'Support Type') {
							$support_type = $item_data['value'];
						}
						if ($item_data['key'] == 'Item Id') {
							$items_id = $item_data['value'];
							$items_key = $item_data['key'];
							$support_extended = false;
						}
						if ($support_extended) {
							$items_id = $item_id;
						}
					}

					$meta_key = 'mwb_support_updates_timestamp';
					$current_timestamp = time();
					$support_set_time = get_post_meta($items_id, $meta_key, true);
					if (empty($items_key)) {
						if ($support_purchase == true) {

							$mwb_updated_timestamp = strtotime('+2 year', strtotime($order_date));
						} else {
							$mwb_updated_timestamp = strtotime('+1 year', strtotime($order_date));
						}
					} else {
						if ($support_type == 'Renew') {
							$mwb_updated_timestamp = strtotime('+6 months', $current_timestamp);
						}

						if ($support_type == 'Extend') {
							$mwb_updated_timestamp = strtotime('+6 months', $support_set_time);
						}
					}
					update_post_meta($items_id, $meta_key, $mwb_updated_timestamp);
				}
				update_post_meta($order_id, 'mwb_support_renewal_status', true);
			}

			$mwb_service_order = get_post_meta($order_id, "mwb_service_order", true);

			if (empty($mwb_service_order)) {
				$order = wc_get_order($order_id);
				$cat_in_order = false;
				$items = $order->get_items();
				foreach ($items as $item_id => $item) {
					$product_id = $item['product_id'];
					if (has_term('services', 'product_cat', $product_id)) {
						$cat_in_order = true;

						$product = new WC_Product($product_id);

						$post_title = $product->get_title();
						$post_description = "";
						$hourlyrate = $product->get_price();
						$email = $order->get_billing_email();
						$productid = $product_id;

						$microservicequery = array(
							'post_title' => $post_title,
							'post_type' => 'microservicesquery',
							'post_content' => $post_description,
							'post_status' => 'publish'
						);

						$microserviceid = wp_insert_post($microservicequery);

						$itemmetakey = $order_id . "-" . $item_id;

						update_post_meta($order_id, $itemmetakey, $microserviceid);

						$ishourly = get_post_meta($serviceid, 'ew_is_hourly', true);
						$ishourly = isset($ishourly) ? $ishourly : "no";
						if ($ishourly == "no") {
							update_post_meta($microserviceid, 'ew_service_type', 'fixed');
						} else {
							update_post_meta($microserviceid, 'ew_service_type', 'hourly');
						}

						update_post_meta($microserviceid, 'ew_hourly_rate', $hourlyrate);
						update_post_meta($microserviceid, 'ew_email_address', $email);
						update_post_meta($microserviceid, 'ew_microservice_name', $post_title);
						update_post_meta($microserviceid, 'ew_microservice_id', $productid);

						//Send Service Contact mail to ADMIN

						$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
						$mwb_data = json_decode($mwb_microservice_notification_json, true);

						$adminmail = $mwb_data['mwb_microservice_fixedmailaddress'];
						$subject = $mwb_data['mwb_microservice_fixedmailsubject'];
						$message = $mwb_data['mwb_microservice_fixedmail_content'];

						$subject = str_replace("{servicename}", $post_title, $subject);
						$subject = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $subject);
						$subject = str_replace("{serviceusermail}", $email, $subject);
						$subject = str_replace("{servicedescription}", $post_description, $subject);

						$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
						$subject = str_replace("{servicerecordlink}", $servicerecordlink, $subject);

						$servicedescriptionlink = home_url() . "/my-account/services/$order_id/submit/$item_id/";
						$subject = str_replace("{servicedescriptionlink}", $servicedescriptionlink, $subject);

						$message = str_replace("{servicename}", $post_title, $message);
						$message = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $message);
						$message = str_replace("{serviceusermail}", $email, $message);
						$message = str_replace("{servicedescription}", $post_description, $message);

						$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
						$message = str_replace("{servicerecordlink}", $servicerecordlink, $message);

						$servicedescriptionlink = home_url() . "/my-account/services/$order_id/submit/$item_id/";
						$message = str_replace("{servicedescriptionlink}", $servicedescriptionlink, $message);

						wc_mail($adminmail, $subject, $message);

						// Send Service Contact Success mail to Customer

						$to = $email;
						$subject = $mwb_data['mwb_microservice_userfixedmailsubject'];
						$message = $mwb_data['mwb_microservice_userfixedmail_content'];

						$subject = str_replace("{servicename}", $post_title, $subject);
						$subject = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $subject);
						$subject = str_replace("{serviceusermail}", $email, $subject);
						$subject = str_replace("{servicedescription}", $post_description, $subject);

						$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
						$subject = str_replace("{servicerecordlink}", $servicerecordlink, $subject);

						$servicedescriptionlink = home_url() . "/my-account/services/$order_id/submit/$item_id/";
						$subject = str_replace("{servicedescriptionlink}", $servicedescriptionlink, $subject);

						$message = str_replace("{servicename}", $post_title, $message);
						$message = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $message);
						$message = str_replace("{serviceusermail}", $email, $message);
						$message = str_replace("{servicedescription}", $post_description, $message);

						$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
						$message = str_replace("{servicerecordlink}", $servicerecordlink, $message);

						$servicedescriptionlink = home_url() . "/my-account/services/$order_id/submit/$item_id/";
						$message = str_replace("{servicedescriptionlink}", $servicedescriptionlink, $message);
						wc_mail($to, $subject, $message);
					}
				}

				update_post_meta($order_id, "mwb_service_order", false);

				if ($cat_in_order) {
					update_post_meta($order_id, "mwb_service_order", true);
					// echo "<p class='woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received mwbservicenotice'>You have purchased services. Kindly submit the prerequisite details of your requested service, our team will catch you soon. Thanks!</p>";
				}

				//Generate Coupon Code
				// $user_id = get_current_user_id();

				// if($user_id > 0 )
				// {
				// 	$current_user = wp_get_current_user();
				// 	$name = $current_user->user_firstname;

				// 	if(isset($name) && !empty($name))
				// 	{
				// 		$coupon_code = 'thankyou-'.$name; // Code
				// 		$amount = '10'; // Amount
				// 		$discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product
				// 		$user_coupon = get_post_meta($user_id,'mwb_user_coupon',true);
				// 		if(!$user_coupon)
				// 		{
				// 			$coupon = array(
				// 				'post_title' => $coupon_code,
				// 				'post_content' => '',
				// 				'post_status' => 'publish',
				// 				'post_author' => 1,
				// 				'post_type'   => 'shop_coupon'
				// 				);

				// 			$todaydate = date_i18n("Y-m-d");
				// 			$expirydate = date_i18n( "Y-m-d", strtotime( "$todaydate +7 day" ) );

				// 			$new_coupon_id = wp_insert_post( $coupon );

				// 			// Add meta
				// 			update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				// 			update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				// 			update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
				// 			update_post_meta( $new_coupon_id, 'product_ids', '' );
				// 			update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
				// 			update_post_meta( $new_coupon_id, 'usage_limit', 1 );
				// 			update_post_meta( $new_coupon_id, 'expiry_date', $expirydate ); // YYYY-MM-DD
				// 			update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				// 			update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
				// 			update_post_meta( $user_id,'mwb_user_coupon', $coupon_code );

				// 			echo "<p class='woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received mwbservicenotice mwbusercoupon'><span>Hi ".$name.",</span> You have unlocked a gift Coupon. You can use this coupon for your next purchase. This coupon is valid for 1 week. Your Coupon Code is <span class='mwbcoupon'>".$coupon_code."</span></p>";
				// 		}
				// 	}
				// }
			}
		}

		//popup form for hourly rate microservices
		function mwb_woocommerce_after_single_product()
		{
			global $product;
			if (isset($product) && !empty($product)) {
				$product_id = $product->get_ID();
				if (has_term('services', 'product_cat', $product_id)) {
				?>
					<div class="mwb_microservice_contact_wrapper">
						<div class="mwb_microservice_contact_fields">
							<span class="mwb_serviceclose">&times;</span>
							<div id="mwbmicroserviceresponse"></div>
							<h3>Please enter your Job Description</h3>
							<p class="mwb_microservice_field">
								<label>Service</label>
								<input type="hidden" name="mwb_service_id" id="mwb_service_id" value="">
								<input type="text" name="mwb_service" id="mwb_service" readonly>
							</p>
							<p class="mwb_microservice_field">
								<label>Hourly Rate</label>
								<input type="text" name="mwb_hourly_rate" id="mwb_hourly_rate" readonly>
							</p>
							<p class="mwb_microservice_field">
								<label>Email Address</label>
								<input type="email" name="mwb_email" id="mwb_email">
							</p>
							<p class="mwb_microservice_field">
								<label>Job Description</label>
								<textarea name="mwb_service_description" id="mwb_service_description"></textarea>
							</p>
							<p class="mwb_microservice_field">
								<input type="checkbox" name="mwb_checkbox_microservices" id="mwb_checkbox_microservices" value="1" required>
								<label>I have read and agree to the <a target="_blank" href="https://makewebbetter.com/terms-and-conditions/">Terms and Conditions</a></label>
							</p>
							<p>
								<a class="button mwb_microservicecpntactsubmit" href="javascript:void(0);">Submit</a>
								<img src="<?php echo home_url() ?>/wp-content/plugins/mwb-theme-customization/assets/images/preloader.gif" class="mwb_job_submit_load">
							</p>
						</div>
					</div>
			<?php
				}
			}
		}

		//Submit form for hourly rate microservices
		public function mwb_submit_service()
		{
			$validation = true;
			if (empty($_POST['email'])) {
				$validation = false;
				$message = __('Please enter the email address.');
				wc_add_notice($message, 'error');
			} else {
				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					$validation = false;
					$message = __('Please enter a valid email address');
					wc_add_notice($message, 'error');
				}
			}

			if (empty($_POST['description'])) {
				$validation = false;
				$message = __('Please enter the job description.');
				wc_add_notice($message, 'error');
			}

			if (!isset($_POST['mwb_checkbox']) || empty($_POST['mwb_checkbox'])) {
				$validation = false;
				$message = __('Please accept our terms and conditions.');
				wc_add_notice($message, 'error');
			}

			if ($validation) {
				$post_title = $_POST['product_title'];
				$post_description = $_POST['description'];
				$hourlyrate = $_POST['hourlyrate'];
				$email = $_POST['email'];
				$productid = $_POST['productid'];

				$microservicequery = array(
					'post_title' => $post_title,
					'post_type' => 'microservicesquery',
					'post_content' => $post_description,
					'post_status' => 'publish'
				);

				$microserviceid = wp_insert_post($microservicequery);

				$ishourly = get_post_meta($serviceid, 'ew_is_hourly', true);
				$ishourly = isset($ishourly) ? $ishourly : "no";
				if ($ishourly == "no") {
					update_post_meta($microserviceid, 'ew_service_type', 'fixed');
				} else {
					update_post_meta($microserviceid, 'ew_service_type', 'hourly');
				}

				update_post_meta($microserviceid, 'ew_hourly_rate', $hourlyrate);
				update_post_meta($microserviceid, 'ew_email_address', $email);
				update_post_meta($microserviceid, 'ew_microservice_name', $post_title);
				update_post_meta($microserviceid, 'ew_microservice_id', $productid);
				$message = __('Your query is submitted successfully. We will get back to you soon.');
				wc_add_notice($message, 'info');

				//Send Service Contact mail to ADMIN

				$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
				$mwb_data = json_decode($mwb_microservice_notification_json, true);

				$adminmail = $mwb_data['mwb_microservice_hourlymailaddress'];
				$subject = $mwb_data['mwb_microservice_hourlymailsubject'];
				$message = $mwb_data['mwb_microservice_hourlymail_content'];

				$subject = str_replace("{servicename}", $post_title, $subject);
				$subject = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $subject);
				$subject = str_replace("{serviceusermail}", $email, $subject);
				$subject = str_replace("{servicedescription}", $post_description, $subject);

				$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
				$subject = str_replace("{servicerecordlink}", $servicerecordlink, $subject);

				$message = str_replace("{servicename}", $post_title, $message);
				$message = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $message);
				$message = str_replace("{serviceusermail}", $email, $message);
				$message = str_replace("{servicedescription}", $post_description, $message);

				$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
				$message = str_replace("{servicerecordlink}", $servicerecordlink, $message);

				wc_mail($adminmail, $subject, $message);

				// Send Service Contact Success mail to Customer

				$to = $email;
				$subject = $mwb_data['mwb_microservice_userhourlymailsubject'];
				$message = $mwb_data['mwb_microservice_userhourlymail_content'];

				$subject = str_replace("{servicename}", $post_title, $subject);
				$subject = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $subject);
				$subject = str_replace("{serviceusermail}", $email, $subject);
				$subject = str_replace("{servicedescription}", $post_description, $subject);

				$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
				$subject = str_replace("{servicerecordlink}", $servicerecordlink, $subject);

				$message = str_replace("{servicename}", $post_title, $message);
				$message = str_replace("{servicehourlyrate}", wc_price($hourlyrate), $message);
				$message = str_replace("{serviceusermail}", $email, $message);
				$message = str_replace("{servicedescription}", $post_description, $message);

				$servicerecordlink = home_url() . "/wp-admin/post.php?post=$microserviceid&action=edit";
				$message = str_replace("{servicerecordlink}", $servicerecordlink, $message);

				wc_mail($to, $subject, $message);
			}

			$all_notices  = WC()->session->get('wc_notices', array());
			$notice_types = apply_filters('woocommerce_notice_types', array('error', 'info', 'message', 'success'));
			$responsemsg = "";

			foreach ($notice_types as $notice_type) {
				if (wc_notice_count($notice_type) > 0) {
					$messages = $all_notices[$notice_type];
					$responsemsg .= '<ul class="woocommerce-' . $notice_type . '">';
					foreach ($messages as $message) :
						$responsemsg .= '<li>' . wp_kses_post($message) . '</li>';
					endforeach;
					$responsemsg .= '</ul>';
				}
			}

			wc_clear_notices();
			$finalresponse = array();
			$finalresponse['valid'] = $validation;
			$finalresponse['message'] = $responsemsg;
			echo json_encode($finalresponse);
			die;
		}

		//Adding order item meta for microservices
		public function mwb_woocommerce_order_item_meta_end($item_id, $item, $order)
		{
			$product = apply_filters('woocommerce_order_item_product', $item->get_product(), $item);
			$product_id = $product->get_id();
			if (has_term('services', 'product_cat', $product_id)) {
				$order_id = $order->get_ID();
				$itemmetakey = $order_id . "-" . $item_id;
				$microserviceid = get_post_meta($order_id, $itemmetakey, true);

				if (isset($microserviceid) && !empty($microserviceid)) {
					$microserviceurl = home_url() . "/my-account/services/$order_id/submit/$item_id";
					echo "<p id='mwb_service_details'><a href='" . $microserviceurl . "' href='_blank'>Submit Prerequisite Details</a></p>";
				}
			}
		}

		//Submit Service decription for microservices
		public function mwb_submti_service_description()
		{
			if (!session_id())
				session_start();

			if (isset($_POST['ew_submit_service_description'])) {
				if (isset($_POST['mwb_service_description']) && !empty($_POST['mwb_service_description'])) {
					$microservicequeryid = $_POST['microservicequeryid'];
					$description = $_POST['mwb_service_description'];
					$microservice = array(
						'ID'           => $microservicequeryid,
						'post_content' => $description,
					);

					update_post_meta($microservicequeryid, "mwb_job_description_submitted", true);

					wp_update_post($microservice);
					$message = __('You Job Description is submitted successfully.');
					wc_add_notice($message, 'success');

					$_SESSION['microservicequerysubmit'] = true;
				} else {
					$_SESSION['microservicequerysubmit'] = false;
					$message = __('Please enter the Job Description.');
					wc_add_notice($message, 'error');
				}
			}
		}

		//Register setting page for microservices mails
		public function mwb_register_microservice_page()
		{
			add_menu_page('MWB Setting', 'MWB Setting', 'manage_options', 'mwb-customization-setting', array($this, 'mwb_customization_setting'));

			add_submenu_page('mwb-customization-setting', 'Festive Decoration Setting', 'Festive Decoration Setting', 'manage_options', 'mwb-festive-decoration-setting', array($this, 'mwb_setting_submenu'));
		}


		//adding template for showing setting page
		public function mwb_customization_setting()
		{
			include_once MWB_DIRPATH . "microservices/admin.php";
		}

		//adding template for showing setting page
		public function mwb_setting_submenu()
		{
			include_once MWB_DIRPATH . "microservices/mwb-festive-decoration.php";
		}

		//Adding meta boxes for microservices
		public function mwb_microservice_meta_boxes()
		{
			add_meta_box('mwb-microservice-extra', __('Microservice Extra'),  array($this, 'mwb_microservice_extra_display_callback'), 'product');
		}

		//Enqueue scripts for Mwb setting page
		public function mwb_admin_enqueue_scripts()
		{

			$mwb_current_screen = get_current_screen();
			$festive_page = 'mwb-setting_page_mwb-festive-decoration-setting';
			$mwb_setting_page = 'toplevel_page_mwb-customization-setting';

			wp_enqueue_media();

			if ($mwb_current_screen->id == $mwb_setting_page) {
				wp_enqueue_style('mwb-select2-css', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css");
				wp_enqueue_script('mwb-select2-js', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js", array('jquery'));
			}

			wp_register_script('mwb-admin-js', MWB_URL . "assets/js/mwb-admin.js?" . time(), array('jquery'));

			$translation_array = array(
				'ajaxurl' => admin_url('admin-ajax.php')
			);

			wp_localize_script('mwb-admin-js', 'mwb_admin_subscription', $translation_array);
			wp_enqueue_script('mwb-admin-js');


			if ($mwb_current_screen->id == $festive_page) {

				wp_enqueue_script('mwb-decoration-js', MWB_URL . "assets/js/mwb-decoration.js?" . time(), array('jquery'));
			}
		}

		//Save microservices extras fields
		public function mwb_save_microservice_extra($post_id)
		{
			if (isset($_POST['mwb_microservice_extra']) && !empty($_POST['mwb_microservice_extra'])) {
				$microservice_extra = $_POST['mwb_microservice_extra'];
				$microservice_price = $_POST['mwb_microservice_price'];

				$microservices = array();
				if (isset($microservice_extra) && !empty($microservice_extra)) {

					foreach ($microservice_extra as $key => $value) {
						$microservice['service'] = $microservice_extra[$key];
						$microservice['price'] = $microservice_price[$key];
						$microservices[] = $microservice;
					}
				}

				$microservicejson = json_encode($microservices);
				update_post_meta($post_id, 'mwb_microservice_details', $microservicejson);
			}
		}

		//Adding Microservices extras data with add to cart for microservices
		public function mwb_add_cart_item_data($the_cart_data, $product_id)
		{
			$microservicejson = get_post_meta($product_id, 'mwb_microservice_details', true);
			$microservices = json_decode($microservicejson, true);
			$postdata = $_POST;
			if (isset($postdata) && !empty($postdata)) {
				foreach ($postdata as $key => $value) {
					if (strpos($key, 'microservice_extra_') !== false) {
						unset($postdata[$key]);
						$microservicekey = str_replace("microservice_extra_", "", $key);

						$selectedvalue = $microservices[$microservicekey]['service'];
						$selectedprice = $microservices[$microservicekey]['price'];
						$finalvalue = $selectedvalue . "- " . wc_price($selectedprice);
						$postdata[$key] = $finalvalue;
					}
					if (strpos($key, 'support') !== false) {
						if (isset($_POST['mwb_extend_support_variation'])) {
							$postdata['checked'] = true;
						}
					}
				}
			}

			$the_cart_data['product_meta'] = array('meta_data' => $postdata);
			return $the_cart_data;
		}

		//Adding Item Meta for microservices
		public function mwb_add_item_meta($item_meta, $existing_item_meta)
		{
			$i = 0;
			if ($existing_item_meta['product_meta']['meta_data']) {
				$product = $existing_item_meta['data'];
				$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
				$mwb_data = json_decode($mwb_microservice_notification_json, true);
				$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";

				$product_data = $product->get_data();
				$price = $product_data['price'];
				$support_price = intval($price * ($mwb_purchase_time_support / 100));

				foreach ($existing_item_meta['product_meta']['meta_data'] as $key => $val) {
					if (strpos($key, 'microservice_extra_') !== false) {
						$i++;
						$item_meta[] = array(
							'name' => "Extra$i",
							'value' => $val,
						);
					}
					if (strpos($key, 'checked') !== false) {
						$item_meta[] = array(
							'name' => "Extended Support to 12 Month",
							'value' => wc_price($support_price),
						);
					}
					if (strpos($key, 'support_update') !== false) {
						$item_meta[] = array(
							'name' => "Support & Updates",
							'value' => "Extended Upto 1 Year - " . wc_price($support_price),
						);
					}
					if (strpos($key, 'support_license') !== false) {
						$item_meta[] = array(
							'name' => "Order No",
							'value' => $existing_item_meta['product_meta']['meta_data']['order_id'],
						);
						$item_meta[] = array(
							'name' => "Product",
							'value' => $existing_item_meta['product_meta']['meta_data']['product_name'],
						);
						$item_meta[] = array(
							'name' => "Item Id",
							'value' => $existing_item_meta['product_meta']['meta_data']['items_id'],
						);
						$item_meta[] = array(
							'name' => "Support Type",
							'value' => $existing_item_meta['product_meta']['meta_data']['support_license'],
						);
					}
				}
			}
			return $item_meta;
		}

		//Price Upadate
		public function mwb_woocommerce_before_calculate_totals($cart_object)
		{

			// Microservice Price Update Code

			if (isset($_POST['add-to-cart'])) {
				$postdata = $_POST;
				$product_id = $_POST['add-to-cart'];
				$extracharges = 0;
				if (isset($postdata) && !empty($postdata)) {
					foreach ($postdata as $key => $value) {
						if (strpos($key, 'microservice_extra_') !== false) {
							unset($postdata[$key]);
							$extracharges += $value;
						}
					}
				}

				foreach ($cart_object->cart_contents as $key => $value) {
					if ($value['product_id'] == $product_id) {
						$price = $value['data']->get_price();
						$total_price = $extracharges + $price;

						$value['data']->set_price($total_price);
					}
				}
			}

			// Support Price Update Code

			if (isset($_POST['support_cart'])) {
				$postdata = $_POST;
				$product_id = $postdata['add-to-cart'];
				if (isset($postdata) && !empty($postdata)) {
					if (isset($postdata['mwb_extend_support_variation'])) {
						foreach ($cart_object->cart_contents as $key => $value) {
							$product   = apply_filters('woocommerce_cart_item_product', $value['data'], $value, $key);
							$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
							$mwb_data = json_decode($mwb_microservice_notification_json, true);
							$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
							if ($value['product_id'] == $product_id) {
								$price = $product->get_price();
								$support_price = intval($price * ($mwb_purchase_time_support / 100));
								$total_price = $support_price + $price;
								$value['data']->set_price($total_price);
							}
						}
					}
				}
			}

			//Support License Price Update Code
			if (isset($_POST['support_license'])) {
				$postdata = $_POST;
				$product_id = $postdata['product_id'];

				if (isset($postdata) && !empty($postdata)) {

					//set price for extend time support
					if (isset($postdata['support_license']) && $postdata['support_license'] == 'Extend') {
						foreach ($cart_object->cart_contents as $key => $value) {
							$p_id = $value['product_meta']['meta_data']['product_id'];
							$product = wc_get_product($product_id);
							$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
							$mwb_data = json_decode($mwb_microservice_notification_json, true);
							$mwb_extend_time_support = isset($mwb_data['mwb_extend_time_support']) ? $mwb_data['mwb_extend_time_support'] : "";

							if ($p_id == $product_id) {

								$price = $product->get_price();
								$support_price = intval($price * ($mwb_extend_time_support / 100));
								$value['data']->set_price($support_price);
							}
						}
					}

					//set price for renew time support
					if (isset($postdata['support_license']) && $postdata['support_license'] == 'Renew') {
						foreach ($cart_object->cart_contents as $key => $value) {
							$p_id = $value['product_meta']['meta_data']['product_id'];
							$product = wc_get_product($product_id);
							$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
							$mwb_data = json_decode($mwb_microservice_notification_json, true);
							$mwb_renew_time_support = isset($mwb_data['mwb_renew_time_support']) ? $mwb_data['mwb_renew_time_support'] : "";

							if ($p_id == $product_id) {
								$price = $product->get_price();
								$support_price = intval($price * ($mwb_renew_time_support / 100));
								$value['data']->set_price($support_price);
							}
						}
					}
				}
			}
		}

		//Price update code session
		public function mwb_get_cart_session_data($cart_items, $values)
		{
			if ($cart_items == '')
				return;

			if (isset($values['product_meta'])) {
				$cart_items['product_meta'] = $values['product_meta'];
				if (isset($values['product_meta']['meta_data'])) {
					$product_id = $values['product_id'];
					$microservicejson = get_post_meta($product_id, 'mwb_microservice_details', true);
					$microservices = json_decode($microservicejson, true);
					$extracharges = 0;

					foreach ($values['product_meta']['meta_data'] as $key => $mwb_value) {

						// Microservice Price Update Code SESSION

						if (strpos($key, 'microservice_extra_') !== false) {
							$microservicekey = str_replace("microservice_extra_", "", $key);
							$selectedvalue = $microservices[$microservicekey]['service'];
							$selectedprice = $microservices[$microservicekey]['price'];
							if (is_numeric($selectedprice)) {

								$extracharges += $selectedprice;
							}
						}

						// License Support Price Update Code SESSION

						if (has_term('product', 'product_cat', $product_id) && strpos($key, 'checked') !== false) {
							if ($key == true) {
								$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
								$mwb_data = json_decode($mwb_microservice_notification_json, true);
								$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
								$product = $cart_items['data'];
								$price = $product->get_price();
								$support_price = intval($price * ($mwb_purchase_time_support / 100));
								$extracharges = $support_price;
							}
						}

						if (has_term('product', 'product_cat', $product_id) && strpos($key, 'support_update') !== false) {
							$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
							$mwb_data = json_decode($mwb_microservice_notification_json, true);
							$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
							$product = $cart_items['data'];
							$price = $product->get_price();
							$support_price = intval($price * ($mwb_purchase_time_support / 100));
							$extracharges = $support_price;
						}

						if (has_term('product', 'product_cat', $product_id) && strpos($key, 'support_license') !== false) {

							//set price for extend time support session
							if ($mwb_value == 'Extend') {
								$product_id = $values['product_meta']['meta_data']['product_id'];
								$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
								$mwb_data = json_decode($mwb_microservice_notification_json, true);
								$mwb_extend_time_support = isset($mwb_data['mwb_extend_time_support']) ? $mwb_data['mwb_extend_time_support'] : "";
								$product = new WC_Product_Variation($product_id);
								$price = $product->get_price();
								$support_price = intval($price * ($mwb_extend_time_support / 100));
							}

							//set price for renew time support session
							if ($mwb_value == 'Renew') {
								$product_id = $values['product_meta']['meta_data']['product_id'];
								$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
								$mwb_data = json_decode($mwb_microservice_notification_json, true);
								$mwb_renew_time_support = isset($mwb_data['mwb_renew_time_support']) ? $mwb_data['mwb_renew_time_support'] : "";
								$product = new WC_Product_Variation($product_id);
								$price = $product->get_price();
								$support_price = intval($price * ($mwb_renew_time_support / 100));
							}
						}
					}

					if (isset($values['product_meta']['meta_data']['support_license'])) {
						$cart_item_price = $cart_items['data']->get_price();
						$cart_items['data']->set_price($support_price);
					} else {
						$cart_item_price = $cart_items['data']->get_price();
						$cart_items['data']->set_price($cart_item_price + $extracharges);
					}
				}
			}
			return $cart_items;
		}

		//Adding tabs on my account page
		public function mwb_subscription_menu_items($items)
		{
			unset($items['customer-logout']);
			// $items['subscription'] = __( 'My Subscriptions', 'mwb' );
			$items['services'] = __('Services', 'mwb');
			$items['free-themes'] = __('Free Themes', 'mwb');
			$items['customer-logout'] = __('Logout', 'mwb');
			return $items;
		}

		//Adding order item meta
		public function mwb_order_item_meta($item_id, $cart_item)
		{
			if (isset($cart_item['product_meta'])) {
				$product = $cart_item['data'];
				$product_data = $product->get_data();
				$price = $product_data['price'];
				$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
				$mwb_data = json_decode($mwb_microservice_notification_json, true);
				$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support']) ? $mwb_data['mwb_purchase_time_support'] : "";
				$support_price = intval($price * ($mwb_purchase_time_support / 100));

				$i = 0;
				foreach ($cart_item['product_meta']['meta_data'] as $key => $val) {
					$order_val = stripslashes($val);

					$product_name = isset($cart_item['product_meta']['meta_data']['product_name']) ? stripslashes($cart_item['product_meta']['meta_data']['product_name']) : "";
					$order_id = isset($cart_item['product_meta']['meta_data']['order_id']) ? stripslashes($cart_item['product_meta']['meta_data']['order_id']) : "";
					$items_id = isset($cart_item['product_meta']['meta_data']['items_id']) ? stripslashes($cart_item['product_meta']['meta_data']['items_id']) : "";
					$support_type = isset($cart_item['product_meta']['meta_data']['support_license']) ? stripslashes($cart_item['product_meta']['meta_data']['support_license']) : "";

					if ($val) {
						if (strpos($key, 'microservice_extra_') !== false) {
							$i++;
							wc_add_order_item_meta($item_id, "Extra", $order_val);
						}
						if (strpos($key, 'checked') !== false) {
							$i++;
							wc_add_order_item_meta($item_id, "Extended Support to 12 Month", wc_price($support_price));
						}
						if (strpos($key, 'support_update') !== false) {
							wc_add_order_item_meta($item_id, "Support & Updates", "Extended Upto 1 Year - " . wc_price($support_price));
						}
						if (strpos($key, 'support_license') !== false) {
							$i++;
							wc_add_order_item_meta($item_id, "Order No", $order_id);
							wc_add_order_item_meta($item_id, "Product", $product_name);
							wc_add_order_item_meta($item_id, "Item Id", $items_id);
							wc_add_order_item_meta($item_id, "Support Type", $support_type);
						}
					}
				}
			}
		}

		public function mwb_rewrite_endpoint()
		{
			// add_rewrite_endpoint( 'subscription', EP_PAGES );
			add_rewrite_endpoint('services', EP_PAGES);
			add_rewrite_endpoint('free-themes', EP_PAGES);
		}

		//Display Microservices extra callback
		public function mwb_microservice_extra_display_callback($post)
		{
			$post_id = $post->ID;
			$microservicejson = get_post_meta($post_id, 'mwb_microservice_details', true);

			$microservices = json_decode($microservicejson, true);
			?>
			<table id="mwb_miroservice_extra_wrapper">
				<tr>
					<th>Service Name</th>
					<th>Service Cost</th>
				</tr>
				<style type="text/css">
					.mwb_remove_microservice {
						background: #111;
						color: #fff;
						padding: 4px;
						border-radius: 22px;
					}
				</style>
				<?php
				if (isset($microservices) && !empty($microservices)) {
					foreach ($microservices as $key => $microservice) {
				?>
						<tr>
							<td><input type="text" name="mwb_microservice_extra[]" value="<?php echo $microservice['service']; ?>"></td>
							<td><input type="text" name="mwb_microservice_price[]" value="<?php echo $microservice['price']; ?>"></td>
							<td><a herf="javascript:void(0);" class="mwb_remove_microservice">X</a></td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td><input type="text" name="mwb_microservice_extra[]"></td>
						<td><input type="text" name="mwb_microservice_price[]"></td>
					</tr>
				<?php
				}
				?>
			</table>
			<input type="button" value="Add" id="mwb_microservice_addextra" class="button">
<?php
		}
	}
	new MWB_WooCommerce_Microservice();
} ?>