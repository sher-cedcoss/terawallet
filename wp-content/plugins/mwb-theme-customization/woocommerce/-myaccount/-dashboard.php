<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<?php if ( $has_downloads ) :


	foreach ($downloads as $key => $download) {
		$order_id = $download['order_id'];
		$licenses = get_post_meta($order_id, '_wc_slm_payment_licenses', true);
		foreach ($licenses as $license) {
			if(in_array($license['item'], $download)){
				$download['license_key'] = $license['key'];
			}
		}
		$downloads[$key] = $download;
	}
	$current_user = wp_get_current_user();
	$name= $current_user->user_firstname;
	$coupon_code = 'thankyou-'.$name; // Code
	$amount = '10'; // Amount
	$discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product
	$user_coupon = get_post_meta($user_id,'mwb_user_coupon',true);
	if(!$user_coupon)
	{
		?>
		<h2><?php echo apply_filters( 'woocommerce_my_account_my_downloads_title', __( 'Your Offers', 'woocommerce' ) ); ?></h2>
		<p class='woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received mwbservicenotice mwbusercoupon'>You have unlocked a gift Coupon. You can use this coupon for your next purchase. This coupon is valid for 1 week. Your Coupon Code is <span class='mwbcoupon'><?php echo $coupon_code;?></span></p>
		<?php
	}
	?>	
	<h2><?php echo apply_filters( 'woocommerce_my_account_my_downloads_title', __( 'Available downloads', 'woocommerce' ) ); ?></h2>



	<?php do_action( 'woocommerce_before_available_downloads' ); ?>

	<?php do_action( 'woocommerce_available_downloads', $downloads ); ?>

	<?php do_action( 'woocommerce_after_available_downloads' ); ?>

<?php else : ?>
	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Go shop', 'woocommerce' ) ?>
		</a>
		<?php esc_html_e( 'No downloads available yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>

<h2><?php echo apply_filters( 'woocommerce_my_account_my_downloads_title', __( 'Your Subscription', 'woocommerce' ) ); ?></h2>

<?php
$args = array(
	'post_type'=>'shop_order',
	'post_status'    => 'all',
	'meta_query' => array(
		array(
			'key'     => 'mwb_order_subscription',
			'value'   => null,
			'compare' => "!=",
		)
	)
);

// The Query
$the_query = new WP_Query( $args );

// The Loop
if ( $the_query->have_posts() ) {
	?>
	<section class="woocommerce-order-downloads">
	<table class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details">
		<thead>
			<tr>
				<th><span class="nobr">Order Id</span></th>
				
				<th><span class="nobr">PROFILEID</span></th>
	
				<th><span class="nobr">STATUS</span></th>
	
				<th><span class="nobr">DESC</span></th>
	
				<th><span class="nobr">PROFILESTARTDATE</span></th>
	
				<th><span class="nobr">NEXTBILLINGDATE</span></th>
	
				<th><span class="nobr">NUMCYCLESCOMPLETED</span></th>
	
				<th><span class="nobr">BILLINGPERIOD</span></th>

				<th><span class="nobr">BILLINGFREQUENCY</span></th>

				<th><span class="nobr">CURRENCYCODE</span></th>

				<th><span class="nobr">AMT</span></th>
	
			</tr>
		</thead>
		<?php
	while ( $the_query->have_posts() ) 
	{
		$the_query->the_post();
		$current_order_id = get_the_id();
		$profileresponse = get_post_meta($current_order_id, "mwb_order_subscription", true);
		$profileid = $profileresponse['PROFILEID'];
		$mwb_paypal_recurring = new MWB_Gateway_Paypal_Recurring();
		$response = $mwb_paypal_recurring->get_recurring_profile($profileid);
		?>
		<tr>
			<tr>
			<th><span class="nobr"><?php echo $current_order_id;?></span></th>
			
			<th><span class="nobr"><?php echo urldecode($response['PROFILEID']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['STATUS']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['DESC']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['PROFILESTARTDATE']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['NEXTBILLINGDATE']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['NUMCYCLESCOMPLETED']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['BILLINGPERIOD']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['BILLINGFREQUENCY']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['CURRENCYCODE']);?></span></th>

			<th><span class="nobr"><?php echo urldecode($response['AMT']);?></span></th>

		</tr>
		<?php
		
	}	
	wp_reset_postdata();
	?>
	</table>
</section>
<?php
}