<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

global $woocommerce;

$usercountry = WC()->customer->get_billing_country();

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-thumbnail">&nbsp;</th>
				<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<?php
				if($usercountry == 'IN')
				{
					?>
					<th class="product-sac"><?php _e( 'SAC', 'woocommerce' ); ?></th>
					<th class="product-igst"><?php _e( 'IGST', 'woocommerce' ); ?></th>
					<?php
				}
				?>
				<th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key );
							?>
						</td>

						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail;
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
								}
							?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<?php
								if ( ! $product_permalink ) {
									echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
								} else {
									echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
								}

								// Meta data
								echo wc_get_formatted_cart_item_data( $cart_item );

								// Backorder notification
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
								}
							?>
							<?php
							if($usercountry == 'IN')
							{
								?>
								<dl class="variation">
									<dt class="variation-igst">IGST:</dt>
									<dd class="variation-igst">18%</dd>
								</dl>
								<?php
							}
							?>
							<dl class="variation" style="padding-top: 15px;">
								<?php
								
								$selected = false;
								if( isset($cart_item['product_meta']['meta_data']['support_update']) && $cart_item['product_meta']['meta_data']['support_update'] == 'extended' )
								{
									$selected = true;
								}
								?>
								<dt class="variation-extendoption"><input type="checkbox" name="mwb_support_and_extend_checkbox" class="mwb_support_and_extend_checkbox" id="mwb_support_and_extend_checkbox" data-prod_id="<?php esc_attr_e( $product_id ) ?>" data-cart_key="<?php echo $cart_item_key ?>" <?php if($selected){ echo 'checked'; } ?> ></dt>
								<dd class="variation-extendoption">Extend Support and Updates Upto 1 Year</dd>
    								
							</dl>
						</td>
						<?php
						if($usercountry == 'IN')
						{
							?>
							<td class="product-sac" data-title="<?php esc_attr_e( 'SAC', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_sac', "998313");
								?>
							</td>

							<td class="product-igst" data-title="<?php esc_attr_e( 'IGST', 'woocommerce' ); ?>">
								<?php
									
								if ( $_product->is_sold_individually() ) {
										$product_quantity = 1;
									} else {
										$product_quantity = $cart_item['quantity'];
									}

								$tax_display_cart = get_option( 'woocommerce_tax_display_cart' );
									if ( 'excl' === $tax_display_cart ) {
										$product_price = wc_get_price_excluding_tax( $_product );
									} else {
										$product_price = wc_get_price_including_tax( $_product );
									}

								echo wc_price(($product_quantity*$product_price*18)/100);

								?>
							</td>
							<?php
						}
						?>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input( array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->get_max_purchase_quantity(),
										'min_value'   => '0',
									), $_product, false );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
							?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="8" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon" style="text-align:left;" >
							<label for="coupon_code"><?php _e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
							
							<!--<div style="font-size: 16px;font-family: 'Nunito Sans';padding-left: 10px;border-left: 3px solid #00688b;margin-top: 22px;text-align: left;line-height: 2;margin-bottom: 15px;">Spend Less Save More, Get Flat <span style="background: #e6e655;font-family: 'Nunito Sans-SemiBold';">10% Off</span> on Entire Site | Use Coupon: <span style="background: #e6e655;font-family: 'Nunito Sans-SemiBold';"><strong>FREE04JULY</strong></span></div>-->
						</div>
					<?php } ?>

					<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" />

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<div class="cart-collaterals">
	<?php
		/**
		 * woocommerce_cart_collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
	 	do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
