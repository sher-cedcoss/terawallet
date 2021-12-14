<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
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
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();
?>
<h2><?php
/* translators: 1: user display name 2: logout url */
printf(
	__( 'Hello %1$s <a href="%2$s" class="mwb-my-account-logout">Log out</a>', 'woocommerce' ),
	'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
	esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
);
?></h2>

<p><?php
	printf(
		__( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ),
		esc_url( wc_get_endpoint_url( 'orders' ) ),
		esc_url( wc_get_endpoint_url( 'edit-address' ) ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) )
	);
?></p>

<hr/>

<?php

/**
 * My Account navigation.
 * @since 2.6.0
 */
do_action( 'woocommerce_account_navigation' ); ?>



<div class="woocommerce-MyAccount-content">


	<?php
		/**
		 * My Account content.
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );
	?>
</div>


