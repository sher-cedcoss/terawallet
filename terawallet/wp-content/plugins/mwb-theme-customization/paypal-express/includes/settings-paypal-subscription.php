<?php
/**
 * Settings for PayPal Gateway.
 *
 * @package WooCommerce/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

return array(
	'enabled'               => array(
		'title'   => __( 'Enable/Disable', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable PayPal Express', 'woocommerce' ),
		'default' => 'no',
	),
	'title'                 => array(
		'title'       => __( 'Title', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
		'default'     => __( 'PayPal Express', 'woocommerce' ),
		'desc_tip'    => true,
	),
	'description'           => array(
		'title'       => __( 'Description', 'woocommerce' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
		'default'     => __( "Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.", 'woocommerce' ),
	),
	'email'                 => array(
		'title'       => __( 'PayPal email', 'woocommerce' ),
		'type'        => 'email',
		'description' => __( 'Please enter your PayPal email address; this is needed in order to take payment.', 'woocommerce' ),
		'default'     => get_option( 'admin_email' ),
		'desc_tip'    => true,
		'placeholder' => 'you@youremail.com',
	),
	'advanced'              => array(
		'title'       => __( 'Advanced options', 'woocommerce' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'              => array(
		'title'       => __( 'PayPal sandbox', 'woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable PayPal sandbox', 'woocommerce' ),
		'default'     => 'no',
	),
	
	'api_details'           => array(
		'title'       => __( 'API credentials', 'woocommerce' ),
		'type'        => 'title',
		/* translators: %s: URL */
		'description' => sprintf( __( 'Enter your PayPal API credentials to process refunds via PayPal. Learn how to access your <a href="%s">PayPal API Credentials</a>.', 'woocommerce' ), 'https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#create-an-api-signature' ),
	),
	'api_username'          => array(
		'title'       => __( 'Live API username', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from PayPal.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
	'api_password'          => array(
		'title'       => __( 'Live API password', 'woocommerce' ),
		'type'        => 'password',
		'description' => __( 'Get your API credentials from PayPal.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
	'api_signature'         => array(
		'title'       => __( 'Live API signature', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from PayPal.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
	'sandbox_api_username'  => array(
		'title'       => __( 'Sandbox API username', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from PayPal.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
	'sandbox_api_password'  => array(
		'title'       => __( 'Sandbox API password', 'woocommerce' ),
		'type'        => 'password',
		'description' => __( 'Get your API credentials from PayPal.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
	'sandbox_api_signature' => array(
		'title'       => __( 'Sandbox API signature', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from PayPal.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
);
