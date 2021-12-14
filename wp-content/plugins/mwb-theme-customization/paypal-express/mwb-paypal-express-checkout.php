<?php
/**
 * PayPal Standard Payment Gateway.
 *
 * Provides a PayPal Standard Payment Gateway.
 *
 * @class 		MWB_Gateway_Paypal_Recurring
 * @extends		WC_Payment_Gateway
 * @version		2.3.0
 * @package		WooCommerce/Classes/Payment
 * @author 		WooThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the gateway to WC Available Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + MWB_Gateway_Paypal_Recurring
 */
function mwb_recurring_paypal_express_checkout( $gateways ) 
{
	$gateways[] = 'MWB_Gateway_Paypal_Recurring';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'mwb_recurring_paypal_express_checkout' );

/**
 * Adds plugin page links
 * 
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */

function mwb_recurring_paypal_express_gateway_plugin_links( $links ) 
{
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=offline_gateway' ) . '">' . __( 'Configure') . '</a>'
	);
	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'mwb_recurring_paypal_express_gateway_plugin_links' );
/**
 * Offline Payment Gateway
 *
 * Provides an Offline Payment Gateway; mainly for testing purposes.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class 		MWB_Gateway_Paypal_Recurring
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author 		SkyVerge
 */
add_action( 'plugins_loaded', 'mwb_recurring_paypal_express_gateway_init', 11 );

function mwb_recurring_paypal_express_gateway_init() 
{

	/**
	 * MWB_Gateway_Paypal_Recurring Class.
	 */
	class MWB_Gateway_Paypal_Recurring extends WC_Payment_Gateway {

		/** @var bool Whether or not logging is enabled */
		public static $log_enabled = false;

		/** @var WC_Logger Logger instance */
		public static $log = false;

		public $api_username;
		public $api_password;
		public $api_signature;
		public $debug;
		public $paypal_api;
		public $paypal_url;
		public $version;

		
		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
			// Setup general properties.

			$this->setup_properties();
			$this->init_settings();
			$this->init_form_fields();

			// Get settings.
			$this->title              = $this->get_option( 'title' );
			$this->description        = $this->get_option( 'description' );
			$this->testmode       = 'yes' === $this->get_option( 'testmode', 'no' );
			$this->debug          = 'yes' === $this->get_option( 'debug', 'no' );
			$this->instructions       = "Enable this payment gateway for subscription based products.";
			$this->email          = $this->get_option( 'email' );
			$this->api_username = $this->get_option( 'api_username' );
			$this->api_password = $this->get_option( 'api_password' );
			$this->api_signature = $this->get_option( 'api_signature' );
			$this->version = urlencode('98');

			if ( $this->testmode == 'yes' ) {
				/* translators: %s: Link to PayPal sandbox testing guide page */
				$this->description .= ' ' . sprintf( __( 'SANDBOX ENABLED. You can use sandbox testing accounts only. See the <a href="%s">PayPal Sandbox Testing Guide</a> for more details.', 'woocommerce' ), 'https://developer.paypal.com/docs/classic/lifecycle/ug_sandbox/' );
				$this->description  = trim( $this->description );

				$this->api_username = $this->get_option( 'sandbox_api_username' );
				$this->api_password = $this->get_option( 'sandbox_api_password' );
				$this->api_signature = $this->get_option( 'sandbox_api_signature' );
			}

			if($this->testmode == 'yes' ){
				$this->paypal_api = 'https://api-3t.sandbox.paypal.com/nvp';
				$this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
			}else{
				$this->paypal_api = 'https://api-3t.paypal.com/nvp';
				$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr?';
			}
			// Load the settings.
			
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ), 30 );
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'mwb_woocommerce_available_payment_gateways' ),100, 1);

			add_action( 'woocommerce_before_my_account', array( $this, 'mwb_woocommerce_before_my_account'), 10);
			add_action( 'woocommerce_api_mwb_paypal_recurring', array( $this, 'woocommerce_api_mwb_paypal_recurring_callback'), 10);
			add_action( 'valid-paypal-standard-ipn-request', array( $this, 'woocommerce_api_mwb_paypal_recurring_profile' ) );
		}

		public function mwb_paypal_cancel_recurring_profile($posted)
		{
			$profileid = $posted['profileid'];
			$orderid = $posted['orderid'];
			$userid = get_current_user_id();

			$requestdata = array();
			$requestdata['profileid'] = $profileid;
			$requestdata['orderid'] = $orderid;
			$requestdata['userid'] = $userid;

			$order_user_id = get_post_meta($orderid, '_customer_user', true);
   			//if($userid == $order_user_id)
			//{
				$this->savelog("Profile Suspended Request", json_encode($requestdata));

				$method = "ManageRecurringPaymentsProfileStatus";

				$args = array();
				$args['PROFILEID'] = $profileid;
				$args['ACTION'] = "Suspend";
				$args['NOTE'] = "Profile Suspended by User Having ID: $userid";

				$profilecancelresponse = $this->request($method, $args);

				$response = array();
				if(isset($profilecancelresponse['PROFILEID']))
				{
					$this->savelog("Profile Suspended Successfully", json_encode($profilecancelresponse));
					$response['success'] = true;
					$profileresponse = $this->get_recurring_profile($profileid);
					$this->send_admin_notice($requestdata, $profileresponse);
					$this->send_user_notice($requestdata, $profileresponse);
				}
				else
				{
					$response['success'] = false;
				}
			//}	
			return $response;

		}

		protected function send_user_notice($requestdata, $response)
		{
			if($response['ACK'] == "Success")
			{
				$profileid = $requestdata['profileid'];
				$orderid = $requestdata['orderid'];
				$userid = $requestdata['userid'];
				$userdata = get_userdata( $userid );
				$profiledesc = urldecode($response['DESC']);
				$profilestatus = urldecode($response['STATUS']);
				$profileamt = urldecode($response['AMT']);


				$to = $userdata->user_email;
				$subject = "Your Subscription is cancelled";
				$message = '<!DOCTYPE html><html lang="en"> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"> <meta charset="utf-8"> <meta content="width=device-width, initial-scale=1.0" name="viewport"> <meta content="noindex" name="robots"> <style type="text/css">@media screen and (max-width: 600px){.text{font-size: 14px !important; line-height: 21px !important;}.h1{font-size: 20px !important;}.h2{font-size: 18px !important;}.h3{font-size: 16px !important;}.h4{font-size: 14px !important;}.h5{font-size: 14px !important;}table{line-height: 1.5 !important;}.h1, .h2, .h3, .h4, .h5{line-height: 1.2 !important;}.cs-logo{width: 120px !important;}.flex-size{max-width: 100% !important; width: 100% !important;}.flex-size img{max-width: 100% !important;}.s-height{height: 10px !important;}.s-db{display: block !important;}.s-dib{display: inline-block !important;}.s-hf{height: 0 !important;}.s-paf{padding: 0 !important;}.s-pbf{padding-bottom: 0 !important;}.s-pbm{padding-bottom: 16px !important;}.s-plf{padding-left: 0 !important;}.s-prf{padding-right: 0 !important;}.s-pts{padding-top: 8px !important;}.s-ptm{padding-top: 16px !important;}.s-ptl{padding-top: 32px !important;}.s-tac{text-align: center !important;}.s-tal{text-align: left !important;}}@media screen and (min-device-width: 375px) and (max-device-width:667px){table{font-size: 16px !important;}table.footer-content{font-size: 12px !important;}}</style></head> <body style="margin: 0; padding: 0;"> <div style="width: 100% !important; padding: 5px; margin: 0 auto !important; font-size: 14px; line-height: 18px; max-width: 700px !important;"> <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="header" width="100%" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td align="center" style="border-bottom: 1px solid #f7f7f7; padding: 12px 0;"> <a href="https://makewebbetter.com/v2" style="color: #96588a; font-weight: normal; text-decoration: underline;"> <img alt="" border="0" src="https://makewebbetter.com/wp-content/uploads/2017/10/cropped-mwb-128.png" width="100" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"> </a> </td></tr></table> <table bgcolor="#408dbd" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td align="center" style="padding: 40px 20px 48px 20px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td style="font-size: 0;"> </td><td bgcolor="#ffffff" class="f-outlook" style="border-radius: 6px; padding: 0 40px 40px 40px;" width="340"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td align="left" class="text h2" style="color: #575a5b; font-size: 28px; font-weight: 300; line-height: 42px; padding-top: 16px; text-align: center; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;"> Your Subscription is Cancelled </td></tr><tr> <td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">As per your request, Your Subscription is cancelled. </td></tr><tr> <td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">Description: '.$profiledesc.' </td></tr><tr> <td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">Subscripition Amount: '.$profileamt.' </td></tr><tr><td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">Subscripition Status: '.$profilestatus.' </td></tr></table></td><td style="font-size: 0;"> </td></tr></table></td></tr></table><table bgcolor="#2d3033" border="0" cellpadding="0" cellspacing="0" class="wrapper" style="border-collapse: collapse; border-spacing: 0; color: #aebdc1; font-size: 12px;" width="100%"><tr><td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://twitter.com/makewebbetter" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Twitter Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/social-twitter.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"> </a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://www.facebook.com/makewebbetter" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Facebook Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/social-facebook.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://www.youtube.com/channel/UC7nYNf0JETOwW3GOD_EW2Ag" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Youtube Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/email_social_youtube.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://plus.google.com/111610242430101820802" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Google+ Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/email_social_gplus.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></a> </td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="left" class="text--footer s-tac" style="color: #aebdc1; font-size: 14px; line-height: 21px; padding-top: 24px;">Have questions or need assistance? </td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0; max-width: 384px;" width="100%"><tr><td style="font-size: 0; text-align: center;"><table align="left" border="0" cellpadding="0" cellspacing="0" class="content" width="182" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" style="padding: 16px 16px 0 16px;"> <a href="https://makewebbetter.freshdesk.com/support/tickets/new" style="background-color: #2d3033; border-radius: 20px; color: #aebdc1; display: inline-block; font-size: 12px; font-weight: 300; line-height: 40px; text-align: center; text-decoration: none; width: 150px; -webkit-text-size-adjust: none; text-transform: uppercase; mso-hide: all;">Support</a> </td></tr></table><table align="left" border="0" cellpadding="0" cellspacing="0" class="content" width="182" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" style="padding: 16px 16px 0 16px;"> <a href="http://docs.makewebbetter.com/" style="background-color: #2d3033; border-radius: 20px; color: #aebdc1; display: inline-block; font-size: 12px; font-weight: 300; line-height: 40px; text-align: center; text-decoration: none; width: 150px; -webkit-text-size-adjust: none; text-transform: uppercase; mso-hide: all;">FAQ</a></td></tr></table></td></tr></table></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" class="text--footer" style="color: #aebdc1; font-family: Open Sanss, Verdana, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 18px; padding: 32px 20px 0 20px;">© 2017 MakeWebBetter. All right Reserved </td></tr><tr><td align="center" class="text--footers" style="color: #aebdc1; font-size: 12px; line-height: 18px;"> </td></tr></table></td></tr></table> </div></body></html>';

				wc_mail($to, $subject, $message);
			}	
		}

		protected function send_admin_notice($requestdata, $response)
		{
			if($response['ACK'] == "Success")
			{
				$profileid = $requestdata['profileid'];
				$orderid = $requestdata['orderid'];
				$userid = $requestdata['userid'];
				$userdata = get_userdata( $userid );
				$profiledesc = urldecode($response['DESC']);
				$profilestatus = urldecode($response['STATUS']);
				$profileamt = urldecode($response['AMT']);

				$to = get_option('admin_email', false);
				$subject = "User #$orderid Subscription is cancelled";
				$message = '<!DOCTYPE html><html lang="en"> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"> <meta charset="utf-8"> <meta content="width=device-width, initial-scale=1.0" name="viewport"> <meta content="noindex" name="robots"> <style type="text/css">@media screen and (max-width: 600px){.text{font-size: 14px !important; line-height: 21px !important;}.h1{font-size: 20px !important;}.h2{font-size: 18px !important;}.h3{font-size: 16px !important;}.h4{font-size: 14px !important;}.h5{font-size: 14px !important;}table{line-height: 1.5 !important;}.h1, .h2, .h3, .h4, .h5{line-height: 1.2 !important;}.cs-logo{width: 120px !important;}.flex-size{max-width: 100% !important; width: 100% !important;}.flex-size img{max-width: 100% !important;}.s-height{height: 10px !important;}.s-db{display: block !important;}.s-dib{display: inline-block !important;}.s-hf{height: 0 !important;}.s-paf{padding: 0 !important;}.s-pbf{padding-bottom: 0 !important;}.s-pbm{padding-bottom: 16px !important;}.s-plf{padding-left: 0 !important;}.s-prf{padding-right: 0 !important;}.s-pts{padding-top: 8px !important;}.s-ptm{padding-top: 16px !important;}.s-ptl{padding-top: 32px !important;}.s-tac{text-align: center !important;}.s-tal{text-align: left !important;}}@media screen and (min-device-width: 375px) and (max-device-width:667px){table{font-size: 16px !important;}table.footer-content{font-size: 12px !important;}}</style></head> <body style="margin: 0; padding: 0;"> <div style="width: 100% !important; padding: 5px; margin: 0 auto !important; font-size: 14px; line-height: 18px; max-width: 700px !important;"> <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="header" width="100%" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td align="center" style="border-bottom: 1px solid #f7f7f7; padding: 12px 0;"> <a href="https://makewebbetter.com/v2" style="color: #96588a; font-weight: normal; text-decoration: underline;"> <img alt="" border="0" src="https://makewebbetter.com/wp-content/uploads/2017/10/cropped-mwb-128.png" width="100" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"> </a> </td></tr></table> <table bgcolor="#408dbd" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td align="center" style="padding: 40px 20px 48px 20px;"> <table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td style="font-size: 0;"> </td><td bgcolor="#ffffff" class="f-outlook" style="border-radius: 6px; padding: 0 40px 40px 40px;" width="340"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border-spacing: 0;"> <tr> <td align="left" class="text h2" style="color: #575a5b; font-size: 28px; font-weight: 300; line-height: 42px; padding-top: 16px; text-align: center; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">Subscription is Cancelled </td></tr><tr> <td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">A User cancelled their Subscription.</td></tr><tr> <td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">Description: '.$profiledesc.' </td></tr><tr> <td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">Subscripition Amount: '.$profileamt.' </td></tr><tr><td align="left" class="text" style="color: #393d40; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;">Subscripition Status: '.$profilestatus.' </td></tr></table></td><td style="font-size: 0;"> </td></tr></table></td></tr></table><table bgcolor="#2d3033" border="0" cellpadding="0" cellspacing="0" class="wrapper" style="border-collapse: collapse; border-spacing: 0; color: #aebdc1; font-size: 12px;" width="100%"><tr><td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://twitter.com/makewebbetter" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Twitter Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/social-twitter.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"> </a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://www.facebook.com/makewebbetter" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Facebook Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/social-facebook.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://www.youtube.com/channel/UC7nYNf0JETOwW3GOD_EW2Ag" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Youtube Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/email_social_youtube.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://plus.google.com/111610242430101820802" style="text-decoration: none; color: #96588a; font-weight: normal;"> <img alt="Google+ Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/email_social_gplus.png" width="30" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></a> </td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="left" class="text--footer s-tac" style="color: #aebdc1; font-size: 14px; line-height: 21px; padding-top: 24px;">Have questions or need assistance? </td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0; max-width: 384px;" width="100%"><tr><td style="font-size: 0; text-align: center;"><table align="left" border="0" cellpadding="0" cellspacing="0" class="content" width="182" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" style="padding: 16px 16px 0 16px;"> <a href="https://makewebbetter.freshdesk.com/support/tickets/new" style="background-color: #2d3033; border-radius: 20px; color: #aebdc1; display: inline-block; font-size: 12px; font-weight: 300; line-height: 40px; text-align: center; text-decoration: none; width: 150px; -webkit-text-size-adjust: none; text-transform: uppercase; mso-hide: all;">Support</a> </td></tr></table><table align="left" border="0" cellpadding="0" cellspacing="0" class="content" width="182" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" style="padding: 16px 16px 0 16px;"> <a href="http://docs.makewebbetter.com/" style="background-color: #2d3033; border-radius: 20px; color: #aebdc1; display: inline-block; font-size: 12px; font-weight: 300; line-height: 40px; text-align: center; text-decoration: none; width: 150px; -webkit-text-size-adjust: none; text-transform: uppercase; mso-hide: all;">FAQ</a></td></tr></table></td></tr></table></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse: collapse; border-spacing: 0;"><tr><td align="center" class="text--footer" style="color: #aebdc1; font-family: Open Sanss, Verdana, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 18px; padding: 32px 20px 0 20px;">© 2017 MakeWebBetter. All right Reserved </td></tr><tr><td align="center" class="text--footers" style="color: #aebdc1; font-size: 12px; line-height: 18px;"> </td></tr></table></td></tr></table> </div></body></html>';

				wc_mail($to, $subject, $message);
			}	
		}


		public function woocommerce_api_mwb_paypal_recurring_profile($posted)
		{
			if(isset($posted['txn_type']))
			{
				if($posted['txn_type'] == 'recurring_payment_profile_created')
				{
					$this->savelog('Notice', 'Profile Creation');

 				  	$this->savelog('IPN Profile Creation', json_encode($posted));
					
          			//Fetch Profile ID from Paypal IPN Request
					$profile_id = $posted['recurring_payment_id'];

					$this->savelog('Profile ID', $profile_id);

					// Find the Order Of that Profile
					$args = array(
						'post_type'		=>	'shop_order',
						'post_status'    => 'all',
						'meta_key'	=>	'mwb_order_paypal_profile',
						'meta_value' => $profile_id,
						'meta_compare' => 'LIKE'
					);

					$order_query = new WP_Query( $args );
					$orderid = 0;
					if ( $order_query->have_posts() ) {
						while ( $order_query->have_posts() ) {
							$order_query->the_post();
							$orderid = get_the_ID();
							break;
						}
						wp_reset_postdata();
					}

					$this->savelog('Order ID', $orderid);

					//Set the subscription Title

					$posttitle = $profile_id;

					if($orderid > 0){
						$posttitle = "Order#".$orderid." - ".$profile_id;
					}

					$this->savelog('Subscripition Title', $posttitle);

					//Save the subscription

					$subscription_data = array(
					    'post_title' => $posttitle,
					    'post_content' => json_encode($posted),
					    'post_type' => 'yearly_subscription',
					);

					//Insert the Paypal Subscription Data

					$subscription_profile_id = wp_insert_post( $subscription_data );

					$this->savelog('Subscripition ID', $subscription_profile_id);

					//Saving Profile ID in meta

					update_post_meta($subscription_profile_id, 'mwb_paypal_profile_id', $profile_id);

					$this->savelog('End', 'Profile Creation');
				}
				
				// Handle Recurring Payment

				if($posted['txn_type'] == 'recurring_payment')
				{
					//start curl
					$product_lowered = strtolower( $posted['product_name'] );
					if($product_lowered=='taskonbot' || $product_lowered=='leavesonbot' || $product_lowered=='eventonbot' || $product_lowered=='busyon' || $product_lowered=='busyon chat' || $product_lowered=='botmywork chatbot builder')
					{

						$this->savelog('Notice', 'BotMyWork Payment Received From Profile');
						
						$bot_posted = array('details'=>$posted);

						$this->savelog('Posted Data', json_encode($bot_posted));

						$ch = curl_init();

						curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-KEY:123456'));
				        // set url
				        curl_setopt($ch, CURLOPT_URL, "https://botmywork.com/mcp/api/payments/paymentsDetails");

				        curl_setopt($ch, CURLOPT_POST, 1);

				        // send data 
				        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bot_posted));

				        curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE); 

				        // $output contains the output string
				        $output = curl_exec($ch);

				        if($errno = curl_errno($ch)) 
				        {
				        	$error_message = curl_strerror($errno);
				        	$this->savelog('Errorno', $errno);
				        	$this->savelog('Error Message', $error_message);
				        }
				        else
				        {
				        	$this->savelog('Notice', 'BotMyWork Payment Data Sent');
				        }
				        
				        // close curl resource to free up system resources
				        curl_close($ch);
					}
					//end curl

					$this->savelog('Notice', 'Payment Received From Profile');

					$profile_id = $posted['recurring_payment_id'];

					$this->savelog('Profile ID', $profile_id);

					// Find the Order Of that Profile

					$args = array(
						'post_type'		=>	'shop_order',
						'post_status'    => 'all',
						'meta_key'	=>	'mwb_order_paypal_profile',
						'meta_value' => $profile_id,
						'meta_compare' => 'LIKE'
					);

					$order_query = new WP_Query( $args );
					$orderid = 0;
					if ( $order_query->have_posts() ) {
						while ( $order_query->have_posts() ) {
							$order_query->the_post();
							$orderid = get_the_ID();
							break;
						}
						wp_reset_postdata();
					}

					$this->savelog('Order ID', $orderid);

					// Find the Subscription Of that Profile

					$args = array(
						'post_type'		=>	'yearly_subscription',
						'post_status'	=> 'any',
						'meta_key'	=>	'mwb_paypal_profile_id',
						'meta_value' => $profile_id,
						'meta_compare' => 'LIKE'
					);

					$subscription_query = new WP_Query( $args );
					$subscriptionid = 0;
					if ( $subscription_query->have_posts() ) {
						while ( $subscription_query->have_posts() ) {
							$subscription_query->the_post();
							$subscriptionid = get_the_id();
							break;
						}
						wp_reset_postdata();
					}

					$this->savelog('Subscription ID', $subscriptionid);

					//Both Order and Subscription Fetched

					if($orderid > 0 && $subscriptionid > 0)
					{
						$posttitle = "Order#".$orderid." - ".$profile_id;

						$this->savelog('Subscripition Title', $posttitle);

						//Save the subscription

						$subscription_data = array(
							'ID' => $subscriptionid,
						    'post_title' => $posttitle,
						    'post_type' => 'yearly_subscription',
						);

						$this->savelog('Updated Subscrition', json_encode($subscription_data));

						wp_update_post($subscription_data);

						$saved_transaction_data = get_post_meta($subscriptionid, "mwb_paypal_profile_transaction", true);

						$this->savelog('Saved Transaction Data', $saved_transaction_data);

						if(empty($saved_transaction_data)){
							$transaction_data[] = $posted; 	
						}else{
							$transaction_data = json_decode($saved_transaction_data, true);
							$transaction_data[] = $posted; 	
						}

						$transaction_data = json_encode($transaction_data);

						$this->savelog('Updated Transaction Data', $transaction_data);

						update_post_meta($subscriptionid, "mwb_paypal_profile_transaction", $transaction_data);

						$this->savelog('End', 'Payment Received From Profile');
					}
					else
					{
						$posttitle = $profile_id;

						if($orderid > 0){
							$posttitle = "Order#".$orderid." - ".$profile_id;
						}

						$this->savelog('Subscripition Title', $posttitle);

						//Save the subscription

						$subscription_data = array(
						    'post_title' => $posttitle,
						    'post_content' => json_encode($posted),
						    'post_type' => 'yearly_subscription',
						);

						$this->savelog('Updated Subscrition', json_encode($subscription_data));

						//Insert the Paypal Data
						$subscriptionid = wp_insert_post( $subscription_data );

						//Saving Profile ID in meta

						update_post_meta($subscriptionid, 'mwb_paypal_profile_id', $profile_id);

						$this->savelog('Subscripition ID', $subscriptionid);

						$saved_transaction_data = get_post_meta($subscriptionid, "mwb_paypal_profile_transaction", true);

						$this->savelog('Saved Transaction Data', $saved_transaction_data);

						if(empty($saved_transaction_data))
						{
							$transaction_data[] = $posted; 	
						}
						else
						{
							$transaction_data = json_decode($saved_transaction_data, true);
							$transaction_data[] = $posted; 	
						}

						$transaction_data = json_encode($transaction_data);

						$this->savelog('Updated Transaction Data', $transaction_data);

						update_post_meta($subscriptionid, "mwb_paypal_profile_transaction", $transaction_data);

						$this->savelog('End', 'Payment Received From Profile');
					}	
				}	
			}	
		}

		public function mwb_woocommerce_before_my_account()
		{
			if($this->enabled == "yes")
			{
				$customer_user_id = get_current_user_id();
				$args = array(
					'post_type'=>'shop_order',
					'post_status'    => 'all',
					'posts_per_page'    => -1,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'mwb_order_subscription',
							'value'   => null,
							'compare' => "!=",
						),
						array(
							'key'     => '_customer_user',
							'value'   => $customer_user_id,
							'compare' => "=",
						)
					)
				);

				// The Query
				$the_query = new WP_Query( $args );

				// The Loop
				if ( $the_query->have_posts() ) {
					?>
					<h2><?php echo apply_filters( 'woocommerce_my_account_my_downloads_title', __( 'Your Subscription', 'woocommerce' ) ); ?></h2>
					<section class="woocommerce-order-downloads">
						<table class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive mwb_order_profile_details">
							<thead>
								<tr>
									<th><span class="nobr">Order Id</span></th>
									<?php /*<th><span class="nobr">Profile Id</span></th>*/ ?>
									<th><span class="nobr">Status</span></th>
									<th><span class="nobr">Start Date</span></th>
									<th><span class="nobr">Next Billing Date</span></th>
									<th><span class="nobr">Number of Billing</span></th>
									<th><span class="nobr">Billing Details</span></th>
									<?php /*<th><span class="nobr">Cancel Subscription</span></th>*/ ?>
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

								if($response['ACK'] == "Success")
								{	
								?>
								<tr>
									<td data-title="Order Id"><span class="nobr"><?php echo $current_order_id;?></span></td>
									
									<?php /*<td><span class="nobr"><?php //echo urldecode($response['PROFILEID']);?></span></td>*/ ?>

									<td data-title="Status"><span class="nobr"><?php echo urldecode($response['STATUS']);?></span></td>

									<td data-title="Start Date"><span class="nobr">
									<?php 
									$date=date_create(urldecode($response['PROFILESTARTDATE']));
									echo date_format($date,"M d, Y");
									?></span></td>

									<td data-title="Next Billing Date"><span class="nobr"><?php 
									$date=date_create(urldecode($response['NEXTBILLINGDATE']));
									echo date_format($date,"M d, Y");
									?></span></td>

									<td data-title="Number of Billing"><span class="nobr"><?php echo urldecode($response['NUMCYCLESCOMPLETED']);?></span></td>
									
									<td data-title="Billing Details"><span class="nobr"><?php echo wc_price(urldecode($response['AMT'])+urldecode($response['TAXAMT']));?><!--/--><?php //echo urldecode($response['BILLINGPERIOD']);?></span></td>
									<?php /*
									<!--<td data-title="Cancel Subscription"><?php
									/if(urldecode($response['STATUS']) == "Active")
									{
										?>
										<input type="button" class="button mwb_cancel_profile" value="CANCEL" data-profileid="<?php echo urldecode($response['PROFILEID']);?>" data-orderid="<?php echo $current_order_id;?>">
										<?php
									}
									else
									{
										?>
										<strong><?php echo urldecode($response['STATUS']);?></strong>
										<?php
									}	
									?>
									</td>*/ ?>
								</tr>
								<?php
								}
								
							}	
							wp_reset_postdata();
						?>
						</table>
					</section>
					<?php
				}
			}
		}


		public function mwb_woocommerce_available_payment_gateways($available_gateways)
		{
			if($this->enabled == "yes")
			{
				$subscription_payment = true;
				if ( ! is_admin() ) 
				{
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_type = $product->get_type();
						if($product_type == "variation")
						{
							$product_id = $product->get_parent_id();
						}
						else
						{
							$product_id = $product->get_id();
						}	
						
						$subscription_enable = get_post_meta($product_id, 'mwb_subscription_enable', true);

						if($subscription_enable == "yes")
						{
							$mwb_trial = WC()->session->get('mwb_trail');
							if($mwb_trial){
								$subscription_payment = false;
								break;
							}
						}	
					}	
				}	

				if($subscription_payment){
					unset($available_gateways['mwbsub']);
				}else{
					unset($available_gateways['paypal']);
					unset($available_gateways['payuindia']);
					unset($available_gateways['stripe']);
					unset($available_gateways['wallet']);
				}
			}	
			return $available_gateways;
		}


		public function woocommerce_api_mwb_paypal_recurring_callback()
		{
			if ( ! empty( $_POST ) && $this->validate_ipn() ) {
				$posted = wp_unslash( $_POST );	
				$this->savelog('Hurry', 'Now Action Begin');
				$this->mwb_valid_response($posted);
				$this->woocommerce_api_mwb_paypal_recurring_profile($posted);
			}
		}

		public function validate_ipn() 
		{	

			$this->savelog("IPN RECEIVED", json_encode($_POST));

			// Get received values from post data.
			$validate_ipn        = wp_unslash( $_POST ); // WPCS: CSRF ok, input var ok.
			$validate_ipn['cmd'] = '_notify-validate';


			// Send back post vars to paypal.
			$params = array(
				'body'        => $validate_ipn,
				'timeout'     => 60,
				'httpversion' => '1.1',
				'compress'    => false,
				'decompress'  => false,
				'user-agent'  => 'WooCommerce/3.3.0',
			);


			// Post back to get a response.
			$response = wp_safe_remote_post( $this->paypal_url, $params );

			$this->savelog("IPN PROCESSED", json_encode($response));

			if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( $response['body'], 'VERIFIED' ) ) {
				$this->savelog("VALID", "Received valid response from PayPal IPN");
				return true;
			}

			$this->savelog("INVALID", "Received invalid response from PayPal IPN");
			return false;
		}

		public function mwb_valid_response($posted)
		{

			$this->savelog('Hurry', 'VALIDATING IPN START');
			$this->savelog('Hurry', json_encode($posted));

			$order = ! empty( $posted['custom'] ) ? $this->get_paypal_order( $posted['custom'] ) : false;

			$this->savelog('ORDER COLLECTED', json_encode($order));
			
			if ( $order ) 
			{
				if(isset($posted['payment_type']))
				{
					$posted['payment_status'] = strtolower( $posted['payment_status'] );
					$this->savelog('Found order #', $order->get_id());
					$this->savelog('Payment status: ', $posted['payment_status']);
					$order_id = $order->get_id();
					if ( method_exists( $this, 'payment_status_' . $posted['payment_status'] ) ) {

						$this->savelog('method_exists  ', 'payment_status_' . $posted['payment_status']);

						call_user_func( array( $this, 'payment_status_' . $posted['payment_status'] ), $order, $posted );
					}
				}	
			}
		}

		/**
		 * Get the order from the PayPal 'Custom' variable.
		 *
		 * @param  string $raw_custom JSON Data passed back by PayPal.
		 * @return bool|WC_Order object
		 */
		public function get_paypal_order( $raw_custom ) 
		{
			$this->savelog('ORDER CUSTOM',$raw_custom);
			// We have the data in the correct format, so get the order.
			$custom = json_decode( $raw_custom );
			if ( $custom && is_object( $custom ) ) {
				$order_id  = $custom->order_id;
				$order_key = $custom->order_key;
			} else {
				// Nothing was found.
				$this->savelog('Notice','Order ID and key were not found in "custom".');
				return false;
			}

			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				// We have an invalid $order_id, probably because invoice_prefix has changed.
				$order_id = wc_get_order_id_by_order_key( $order_key );
				$order    = wc_get_order( $order_id );
			}

			$this->savelog('Notice',json_encode($order));
			$this->savelog('Notice',json_encode($order_key));

			if ( ! $order || $order->get_order_key() !== $order_key ) {
				$this->savelog('Notice','Order Keys do not match.');
				return false;
			}

			return $order;
		}

		public function payment_status_completed( $order, $posted ) 
		{

			if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
        $this->savelog('Notice','Aborting, Order #' . $order->get_id() . ' is already complete.');
  			//exit;
			}else{
				$this->savelog('Notice','UnComplete, Order #' . $order->get_id() . ' payment is not completed.');
			}

			$this->validate_transaction_type( $posted['txn_type'] );
			$this->validate_currency( $order, $posted['mc_currency'] );
			$this->validate_amount( $order, $posted['mc_gross'] );
			$this->validate_receiver_email( $order, $posted['receiver_email'] );
			$this->save_paypal_meta_data( $order, $posted );

			if ( 'completed' === $posted['payment_status'] ) {
				if ( $order->has_status( 'cancelled' ) ) {
					$this->payment_status_paid_cancelled_order( $order, $posted );
				}

				$this->payment_complete( $order, ( ! empty( $posted['txn_id'] ) ? wc_clean( $posted['txn_id'] ) : '' ), __( 'IPN payment completed- MWB Yearly Subscription', 'woocommerce' ) );

				if ( ! empty( $posted['mc_fee'] ) ) {
					// Log paypal transaction fee.
					update_post_meta( $order->get_id(), 'PayPal Transaction Fee', wc_clean( $posted['mc_fee'] ) );
				}
			} else {
				if ( 'authorization' === $posted['pending_reason'] ) {
					$this->payment_on_hold( $order, __( 'Payment authorized. Change payment status to processing or complete to capture funds.', 'woocommerce' ) );
				} else {
					/* translators: %s: pending reason. */
					$this->payment_on_hold( $order, sprintf( __( 'Payment pending (%s).', 'woocommerce' ), $posted['pending_reason'] ) );
				}
			}
		}

		/**
		 * Handle a pending payment.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_pending( $order, $posted ) {
			$this->payment_status_completed( $order, $posted );
		}

		/**
		 * Handle a failed payment.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_failed( $order, $posted ) {
			/* translators: %s: payment status. */
			$order->update_status( 'failed', sprintf( __( 'Payment %s via IPN.', 'woocommerce' ), wc_clean( $posted['payment_status'] ) ) );
		}

		/**
		 * Handle a denied payment.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_denied( $order, $posted ) {
			$this->payment_status_failed( $order, $posted );
		}

		/**
		 * Handle an expired payment.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_expired( $order, $posted ) {
			$this->payment_status_failed( $order, $posted );
		}

		/**
		 * Handle a voided payment.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_voided( $order, $posted ) {
			$this->payment_status_failed( $order, $posted );
		}

		/**
		 * Handle a refunded order.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_refunded( $order, $posted ) 
		{
			// Only handle full refunds, not partial.
			if ( $order->get_total() === wc_format_decimal( $posted['mc_gross'] * -1, wc_get_price_decimals() ) ) {

				/* translators: %s: payment status. */
				$order->update_status( 'refunded', sprintf( __( 'Payment %s via IPN.', 'woocommerce' ), strtolower( $posted['payment_status'] ) ) );

				$this->send_ipn_email_notification(
					/* translators: %s: order link. */
					sprintf( __( 'Payment for order %s refunded', 'woocommerce' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
					/* translators: %1$s: order ID, %2$s: reason code. */
					sprintf( __( 'Order #%1$s has been marked as refunded - PayPal reason code: %2$s', 'woocommerce' ), $order->get_order_number(), $posted['reason_code'] )
				);
			}
		}

		/**
		 * Handle a reversal.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_reversed( $order, $posted ) 
		{
			/* translators: %s: payment status. */
			$order->update_status( 'on-hold', sprintf( __( 'Payment %s via IPN.', 'woocommerce' ), wc_clean( $posted['payment_status'] ) ) );

			$this->send_ipn_email_notification(
				/* translators: %s: order link. */
				sprintf( __( 'Payment for order %s reversed', 'woocommerce' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
				/* translators: %1$s: order ID, %2$s: reason code. */
				sprintf( __( 'Order #%1$s has been marked on-hold due to a reversal - PayPal reason code: %2$s', 'woocommerce' ), $order->get_order_number(), wc_clean( $posted['reason_code'] ) )
			);
		}

		/**
		 * Handle a cancelled reversal.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		protected function payment_status_canceled_reversal( $order, $posted ) 
		{
			$this->send_ipn_email_notification(
				/* translators: %s: order link. */
				sprintf( __( 'Reversal cancelled for order #%s', 'woocommerce' ), $order->get_order_number() ),
				/* translators: %1$s: order ID, %2$s: order link. */
				sprintf( __( 'Order #%1$s has had a reversal cancelled. Please check the status of payment and update the order status accordingly here: %2$s', 'woocommerce' ), $order->get_order_number(), esc_url( $order->get_edit_order_url() ) )
			);
		}


		/* Complete order, add transaction ID and note.
		 * @param  WC_Order $order
		 * @param  string   $txn_id
		 * @param  string   $note
		 */
		function payment_complete( $order, $txn_id = '', $note = '' ) {
			$order->add_order_note( $note );
			$order->payment_complete( $txn_id );
		}

		/**
		 * Hold order and add note.
		 * @param  WC_Order $order
		 * @param  string   $reason
		 */
		function payment_on_hold( $order, $reason = '' ) {
			$order->update_status( 'on-hold', $reason );
			wc_reduce_stock_levels( $order->get_id() );
			WC()->cart->empty_cart();
		}

		/**
		 * When a user cancelled order is marked paid.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		function payment_status_paid_cancelled_order( $order, $posted ) {
			$this->send_ipn_email_notification(
				/* translators: %s: order link. */
				sprintf( __( 'Payment for cancelled order %s received', 'woocommerce' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
				/* translators: %s: order ID. */
				sprintf( __( 'Order #%s has been marked paid by PayPal IPN, but was previously cancelled. Admin handling required.', 'woocommerce' ), $order->get_order_number() )
			);
		}

		/**
		 * Send a notification to the user handling orders.
		 *
		 * @param string $subject Email subject.
		 * @param string $message Email message.
		 */
		function send_ipn_email_notification( $subject, $message ) {
			$new_order_settings = get_option( 'woocommerce_new_order_settings', array() );
			$mailer             = WC()->mailer();
			$message            = $mailer->wrap_message( $subject, $message );

			$woocommerce_paypal_settings = get_option( 'woocommerce_paypal_settings' );
			if ( ! empty( $woocommerce_paypal_settings['ipn_notification'] ) && 'no' === $woocommerce_paypal_settings['ipn_notification'] ) {
				return;
			}

			$mailer->send( ! empty( $new_order_settings['recipient'] ) ? $new_order_settings['recipient'] : get_option( 'admin_email' ), strip_tags( $subject ), $message );
		}


		/**
		 * Save important data from the IPN to the order.
		 *
		 * @param WC_Order $order  Order object.
		 * @param array    $posted Posted data.
		 */
		function save_paypal_meta_data( $order, $posted ) {
			if ( ! empty( $posted['payment_type'] ) ) {
				update_post_meta( $order->get_id(), 'Payment type', wc_clean( $posted['payment_type'] ) );
			}
			if ( ! empty( $posted['txn_id'] ) ) {
				update_post_meta( $order->get_id(), '_transaction_id', wc_clean( $posted['txn_id'] ) );
			}
			if ( ! empty( $posted['payment_status'] ) ) {
				update_post_meta( $order->get_id(), '_paypal_status', wc_clean( $posted['payment_status'] ) );
			}
		}

		/**
		 * Check receiver email from PayPal. If the receiver email in the IPN is different than what is stored in.
		 * WooCommerce -> Settings -> Checkout -> PayPal, it will log an error about it.
		 *
		 * @param WC_Order $order          Order object.
		 * @param string   $receiver_email Email to validate.
		 */
		function validate_receiver_email( $order, $receiver_email ) {
			//if ( strcasecmp( trim( $receiver_email ), trim( $this->receiver_email ) ) !== 0 ) {
			//	savelog('Notice',"IPN Response is for another account: {$receiver_email}. Your email is");	
				
				/* translators: %s: email address . */
			//	$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal IPN response from a different email address (%s).', 'woocommerce' ), $receiver_email ) );
			//	exit;
			//}
		}


		/**
		 * Check payment amount from IPN matches the order.
		 *
		 * @param WC_Order $order  Order object.
		 * @param int      $amount Amount to validate.
		 */
		function validate_amount( $order, $amount ) {
			if ( number_format( $order->get_total(), 2, '.', '' ) !== number_format( $amount, 2, '.', '' ) ) {
				$this->savelog('Notice','Payment error: Amounts do not match (gross ' . $amount . ')');
				
				/* translators: %s: Amount. */
				$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal amounts do not match (gross %s).', 'woocommerce' ), $amount ) );
				exit;
			}
		}

		/**
		 * Check for a valid transaction type.
		 *
		 * @param string $txn_type Transaction type.
		 */
		function validate_transaction_type( $txn_type ) {
			$accepted_types = array( 'cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money', 'paypal_here' );

			if ( ! in_array( strtolower( $txn_type ), $accepted_types, true ) ) {
				$this->savelog('Notice','Aborting, Invalid type:' . $txn_type );
				exit;
			}
		}

		/**
		 * Check currency from IPN matches the order.
		 *
		 * @param WC_Order $order    Order object.
		 * @param string   $currency Currency code.
		 */
		function validate_currency( $order, $currency ) {
			if ( $order->get_currency() !== $currency ) {
				$this->savelog('Notice','Payment error: Currencies do not match (sent "' . $order->get_currency() . '" | returned "' . $currency . '")' );
				/* translators: %s: currency code. */
				$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal currencies do not match (code %s).', 'woocommerce' ), $currency ) );
				exit;
			}
		}

		/**
		 * Initialise Gateway Settings Form Fields.
		 */
		public function init_form_fields() {
			$this->form_fields = include 'includes/settings-paypal-subscription.php';
		}

		/**
		 * Setup general properties for the gateway.
		 */
		protected function setup_properties() {
			$this->id                 = 'mwbsub';
			//$this->icon               = apply_filters( 'woocommerce_cod_icon', '' );
			$this->method_title       = __( "MWB Paypal Subscription", 'woocommerce' );
			$this->method_description = __( "Enable this payment gateway for subscription based products.", 'woocommerce' );
			$this->has_fields         = false;
		}

		public function thankyou_page($order_id)
		{
			if($this->enabled == "yes")
			{
				$this->savelog("Thankyou Page Start", $order_id);

				$response = get_post_meta($order_id, "mwb_order_subscription", true);

				$alreadyprocessed = true;
				$validate = true;


				if(isset($response['ACK']) && isset($response['PROFILESTATUS']))
				{
					if($response['ACK'] == "Success" && $response['PROFILESTATUS'] == "ActiveProfile")
					{
						$alreadyprocessed = false;
						$this->savelog("Thankyou Page Already Processed", $alreadyprocessed);
					}
				}
				
				if(isset($_GET['token']) && $alreadyprocessed)
				{
					$order = wc_get_order( $order_id );
					$token = $_GET['token'];
					$GetExpressCheckoutDetailsdata = "&TOKEN=$token";
					$method = "GetExpressCheckoutDetails";

					$this->savelog("GetExpressCheckoutDetails Request", $GetExpressCheckoutDetailsdata);

					$GetExpressCheckoutDetails = $this->request($method, $GetExpressCheckoutDetailsdata, true);

					$this->savelog("GetExpressCheckoutDetails Response", $GetExpressCheckoutDetails);

					$discountquery = "";
					if ( $order->get_total_discount() > 0 ) {
						$args = array();
						$args['PAYMENTREQUEST_0REDEEMEDOFFERAMOUNT'] = $order->get_total_discount();
						$discountquery .= "&".http_build_query($args);

						$this->savelog("Discount Data", $discountquery);

					}	


					$DoExpressCheckoutPaymentdata = "&".$GetExpressCheckoutDetails.$discountquery;
					$method = "DoExpressCheckoutPayment";

					$this->savelog("DoExpressCheckoutPayment Request", $DoExpressCheckoutPaymentdata);

					$DoExpressCheckoutPaymentresponse = $this->request($method, $DoExpressCheckoutPaymentdata, true);

					$this->savelog("DoExpressCheckoutPayment Response", $DoExpressCheckoutPaymentresponse);

					$GetExpressCheckoutDetails = explode("&", $GetExpressCheckoutDetails);
					$GetExpressCheckoutDetailsresponse = array();
					foreach ($GetExpressCheckoutDetails as $i => $value) 
					{
						$tmpAr = explode("=", $value);
						if(sizeof($tmpAr) > 1) 
						{
						  $GetExpressCheckoutDetailsresponse[$tmpAr[0]] = $tmpAr[1];
						}
					}
					
					
					$i=0;
					$calculated_total = 0;
					$tax_calculated_total = 0;
					$trail = false;
					foreach ( $order->get_items( array( 'line_item' ) ) as $item ) 
					{
						// Check for Trail Product

						$ordermetadatas = $item->get_formatted_meta_data();
						if(isset($ordermetadatas) && !empty($ordermetadatas))
						{
							foreach ($ordermetadatas as $key => $ordermetadata) 
							{
								if($ordermetadata->key == "Trial Period")
								{
									$trail = true;  // Trial Product Exist
								}	
							}
						}	
						
						// Get Product ID

						$product          = $item->get_product();
						$product_type = $product->get_type();
						if($product_type == "variation")
						{
							$product_id = $product->get_parent_id();
						}
						else
						{
							$product_id = $product->get_id();
						}	
						
						// Check for product subscription

						$subscription_enable = get_post_meta($product_id, 'mwb_subscription_enable', true);

						if($subscription_enable == "yes")
						{
							$sku = $product ? $product->get_sku() : '';

							// Get Product Line Amount

							if($trail)
							{
								$item_line_total  = $product->get_price();
							}
							else
							{
								$item_line_total  = $this->number_format( $order->get_item_subtotal( $item, false ), $order );
							}

							// Get Product Line Tax

							$item_line_tax  = $this->number_format( $order->get_item_tax( $item, false ), $order );

							$line_item_name        = $this->get_order_item_name( $order, $item );

							// Yearly Subscription Total

							$calculated_total += $item_line_total * $item->get_quantity();

							// Yearly Tax Total

							$tax_calculated_total += $item_line_tax;

							$args["L_PAYMENTREQUEST_0_NAME".$i] = $line_item_name;
							$args["L_PAYMENTREQUEST_0_DESC".$i] = $line_item_name;
							$args["L_PAYMENTREQUEST_0_AMT".$i] = $item_line_total;
							$args["L_PAYMENTREQUEST_0_TAXAMT".$i] = $item_line_tax;
							$args["L_PAYMENTREQUEST_0_QTY".$i] = $item->get_quantity();
							$args["L_PAYMENTREQUEST_0_NUMBER".$i] = $i+1;
							$args["L_PAYMENTREQUEST_0_ITEMCATEGORY".$i] = "Digital";
							$i++;
						}	
					}

					$methodName = "CreateRecurringPaymentsProfile";
					$dt = new DateTime();

					if($trail)
					{
						//Subscription Start After 14 Days

						$dt->add(new DateInterval('P14D'));
					}	
					else
					{
						//Subscription Start After 1 Year

						$dt->add(new DateInterval('P1Y'));
					}	

					$dt->setTimeZone(new DateTimeZone('UTC'));
					$datetime = $dt->format('Y-m-d\TH:i:s\Z');
					$email = $GetExpressCheckoutDetailsresponse['EMAIL']; 
					$currency = $GetExpressCheckoutDetailsresponse['CURRENCYCODE'];

					$args = array(); 
					$args['TOKEN'] = $token;
					$args['PROFILESTARTDATE'] = $datetime;
					$args['EMAIL'] = $email;
					$args['CURRENCYCODE'] = get_woocommerce_currency();
					$args['BILLINGPERIOD'] = "Year";
					$args['BILLINGFREQUENCY'] = 1;
					$args['TOTALBILLINGCYCLES'] = 1;
					$args['DESC'] =  "Subscription of ".($tax_calculated_total+$calculated_total)." ".get_woocommerce_currency();
					$args['TAXAMT'] = $tax_calculated_total;
					$args['AMT'] = $calculated_total;
					
					$this->savelog("CreateRecurringPaymentsProfile Argument", json_encode($args));

					if($calculated_total > 0)
					{
						$method = "CreateRecurringPaymentsProfile";


						$this->savelog("CreateRecurringPaymentsProfile Request", json_encode($args));

						$response = $this->request($method, $args);

						$this->savelog("CreateRecurringPaymentsProfile Response", json_encode($response));

						update_post_meta($order_id, "mwb_order_subscription", $response);
						if(isset($response['ACK']) && isset($response['PROFILESTATUS']))
						{
							if($response['ACK'] == "Success" && $response['PROFILESTATUS'] == "ActiveProfile")
							{
								$validate = false;
								$alreadyprocessed = false;
								$order->update_status( 'completed', __( 'Payment is completed using MWB WooCommerce Subscription', 'woocommerce' ));
								$profileid = urldecode($response['PROFILEID']);
								update_post_meta($order_id, "mwb_order_paypal_profile", $profileid);
							}	
						}
						else
						{

							$order->update_status( 'pending', __( 'Profile Creation is failed  using MWB WooCommerce Subscription', 'woocommerce'));
						}	
					}
					if($validate){
						$order->update_status( 'pending', __( 'Subscription Amount is 0 in MWB WooCommerce Subscription', 'woocommerce' ));
					}

					$this->savelog("Process End", "END OF PAYMENT PROCESS");	
				}
				if(!$validate || !$alreadyprocessed)
				{
					?>
					<section class="woocommerce-order-downloads">
						<h2>Subscription Details</h2>
						<table class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details">
							<thead>
								<tr>
									<th><span class="nobr">Order Id</span></th>
									<!--<th><span class="nobr">Profile Id</span></th>-->
									<th><span class="nobr">Status</span></th>
									<th><span class="nobr">Start Date</span></th>
									<th><span class="nobr">Next Billing Date</span></th>
									<th><span class="nobr">Number of Billing</span></th>
									<th><span class="nobr">Billing Details</span></th>
								</tr>
							</thead>
							<?php
							$profileresponse = get_post_meta($order_id, "mwb_order_subscription", true);
							$profileid = $profileresponse['PROFILEID'];
							$response = $this->get_recurring_profile($profileid);
							
							if($response['ACK'] == "Success")
							{	
							?>
							<tr>
								<tr>
								<td><span class="nobr"><?php echo $order_id;?></span></td>
								
								<!--<td><span class="nobr"><?php //echo urldecode($response['PROFILEID']);?></span></td>-->

								<td><span class="nobr"><?php echo urldecode($response['STATUS']);?></span></td>

								<td><span class="nobr">
								<?php 
								$date=date_create(urldecode($response['PROFILESTARTDATE']));
								echo date_format($date,"M d, Y");
								?></span></td>

								<td><span class="nobr"><?php 
								$date=date_create(urldecode($response['NEXTBILLINGDATE']));
								echo date_format($date,"M d, Y");
								?></span></td>

								<td><span class="nobr"><?php echo urldecode($response['NUMCYCLESCOMPLETED']);?></span></td>
								
								<td><span class="nobr"><?php echo wc_price(urldecode($response['AMT'])+urldecode($response['TAXAMT']));?><!--/--><?php //echo urldecode($response['BILLINGPERIOD']);?></span></td>

							</tr>
							<?php
							}
								
						?>
						</table>
					</section>
					<?php
				}
			}	
		}

		

		/**
		 * Process the payment and return the result.
		 *
		 * @param int $order_id Order ID.
		 * @return array
		 */
		public function process_payment( $order_id ) {

			$this->savelog("Process Start", "START OF PAYMENT PROCESS");

			$order = wc_get_order( $order_id );

			if ( $order->get_total() > 0 ) {
				$args = $this->prepare_subscription_args( $order );

				$this->savelog("SetExpressCheckout Arguments", json_encode($args));

				$method = "SetExpressCheckout";
				$response = $this->request($method, $args);

				$this->savelog("SetExpressCheckout Response", json_encode($response));
				if(isset($response['TOKEN'])){
					$token = $response['TOKEN'];
					return array(
						'result'   => 'success',
						'redirect' => $this->paypal_url . "cmd=_express-checkout&token=$token",
					);
				}
			} 
		}


		private function request($method, $data, $string = false){
			if(is_array($data)){
				$data = http_build_query($data);
			}	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->paypal_api);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			$nvpreq = "METHOD=".$method."&VERSION=".$this->version."&PWD=".$this->api_password."&USER=".$this->api_username."&SIGNATURE=".$this->api_signature."&$data";
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
			$httpResponse = curl_exec($ch);

			if(!$httpResponse) {
				exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}

			if($string){
				return $httpResponse;
			}

			$httpResponseAr = explode("&", $httpResponse);
			$httpParsedResponseAr = array();
			foreach ($httpResponseAr as $i => $value) 
			{
				$tmpAr = explode("=", $value);
				if(sizeof($tmpAr) > 1) 
				{
				  $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
				}
			}
			return $httpParsedResponseAr;
		}

		private function prepare_subscription_args( $order )
		{
			$args['RETURNURL'] = esc_url_raw( add_query_arg( 'utm_nooverride', '1', $this->get_return_url( $order ) ) );
			$args['CANCELURL'] = esc_url_raw( $order->get_cancel_order_url_raw() );
			$args['PAYMENTREQUEST_0_NOTIFYURL'] = home_url()."?wc-api=mwb_paypal_recurring";
			$args['PAYMENTREQUEST_0_CUSTOM'] = wp_json_encode(array('order_id'  => $order->get_id(),'order_key' => $order->get_order_key()));
			$args['NOSHIPPING'] = "1";
			$args['LOGOIMG'] = home_url()."/wp-content/uploads/2018/08/logo.png";
			$args['EMAIL'] = $order->get_billing_email();
			$args['BRANDNAME'] = "MakeWebBetter";
			$args['PAYMENTREQUEST_0_PAYMENTACTION'] = "Sale";
			$args['PAYMENTREQUEST_0_CURRENCYCODE'] = get_woocommerce_currency();
			$args['PAYMENTREQUEST_0_AMT'] = $order->get_total();
			$args['PAYMENTREQUEST_0_ITEMAMT'] = $order->get_total()-$order->get_total_tax();
			$args['PAYMENTREQUEST_0_TAXAMT'] = $order->get_total_tax();
			$args['PAYMENTREQUEST_0_DESC'] = "Order Details";
			$i=0;
			$calculated_total = 0;
			$tax_calculated_total = 0;


			$recur_calculated_total = 0;
			$recur_tax_calculated_total = 0;

			$trail = false;

			foreach ( $order->get_items( array( 'line_item' ) ) as $item ) {

				// Check for trial Product

				$ordermetadatas = $item->get_formatted_meta_data();
				if(isset($ordermetadatas) && !empty($ordermetadatas))
				{
					foreach ($ordermetadatas as $key => $ordermetadata) 
					{
						if($ordermetadata->key == "Trial Period")
						{
							$trail = true;
						}	
					}
				}

				$product          = $item->get_product();
				$sku              = $product ? $product->get_sku() : '';
				$item_line_total  = $this->number_format( $order->get_item_subtotal( $item, false ), $order );
				$item_line_tax  = $this->number_format( $order->get_item_tax( $item, false ), $order );
				$line_item_name        = $this->get_order_item_name( $order, $item );
				$calculated_total += $item_line_total * $item->get_quantity();
				$tax_calculated_total += $item_line_tax;
				$args["L_PAYMENTREQUEST_0_NAME".$i] = $line_item_name;
				$args["L_PAYMENTREQUEST_0_DESC".$i] = $line_item_name;
				$args["L_PAYMENTREQUEST_0_AMT".$i] = $item_line_total;
				$args["L_PAYMENTREQUEST_0_TAXAMT".$i] = $item_line_tax;
				$args["L_PAYMENTREQUEST_0_QTY".$i] = $item->get_quantity();
				$args["L_PAYMENTREQUEST_0_NUMBER".$i] = $i+1;
				$i++;
				$product_type = $product->get_type();

				//Get Product ID

				if($product_type == "variation")
				{
					$product_id = $product->get_parent_id();
				}
				else
				{
					$product_id = $product->get_id();
				}	


				$subscription_enable = get_post_meta($product_id, 'mwb_subscription_enable', true);

				//Check for Trial

				if($trail)
				{
					$item_line_total  = $product->get_price();
				}
				else
				{
					$item_line_total  = $this->number_format( $order->get_item_subtotal( $item, false ), $order );
				}

				if($subscription_enable == "yes")
				{
					$recur_calculated_total += $item_line_total * $item->get_quantity();
					$recur_tax_calculated_total += $item_line_tax;
				}	

			}


			//Manage Discount

			if ( $order->get_total_discount() > 0 ) 
			{
				$total_discount = $order->get_total_discount();
				$args["L_PAYMENTREQUEST_0_NAME".$i] = "Coupon Discount";
				$args["L_PAYMENTREQUEST_0_AMT".$i] = "-$total_discount";
				$args["L_PAYMENTREQUEST_0_NUMBER".$i] = $i+1;
			}	

			$args["L_BILLINGTYPE0"] = "RecurringPayments";
			$args['L_BILLINGAGREEMENTDESCRIPTION0'] =  "Subscription of ".($recur_calculated_total+$recur_tax_calculated_total)." ".get_woocommerce_currency();
			return $args;
		}

		/**
		 * Get order item names as a string.
		 * @param  WC_Order $order
		 * @param  array $item
		 * @return string
		 */
		protected function get_order_item_name( $order, $item ) {
			$item_name = $item->get_name();
			$item_meta = strip_tags( wc_display_item_meta( $item, array(
				'before'    => "",
				'separator' => ", ",
				'after'     => "",
				'echo'      => false,
				'autop'     => false,
			) ) );

			if ( $item_meta ) {
				$item_name .= ' (' . $item_meta . ')';
			}

			return apply_filters( 'woocommerce_paypal_get_order_item_name', $item_name, $order, $item );
		}

		/**
		 * Format prices.
		 * @param  float|int $price
		 * @param  WC_Order $order
		 * @return string
		 */
		protected function number_format( $price, $order ) {
			$decimals = 2;

			if ( ! $this->currency_has_decimals( $order->get_currency() ) ) {
				$decimals = 0;
			}


			return number_format( $price, $decimals, '.', '' );
		}

		/**
		 * Check if currency has decimals.
		 * @param  string $currency
		 * @return bool
		 */
		protected function currency_has_decimals( $currency ) {
			if ( in_array( $currency, array( 'HUF', 'JPY', 'TWD' ) ) ) {
				return false;
			}

			return true;
		}

		protected function savelog($action, $data){
			$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL."Action: ".$action.PHP_EOL."Data: ".$data.PHP_EOL."--------------------------------------------------".PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;
			//Save string to log, use FILE_APPEND to append.
			file_put_contents(MWB_DIRPATH.'/paypal-express/mwbsubscription.log', $log, FILE_APPEND);
		}

		/**
		 * Get gateway icon.
		 *
		 * @return string
		 */
		public function get_icon() {
			$icon_html = '';
			$icon      = (array) $this->get_icon_image( WC()->countries->get_base_country() );

			foreach ( $icon as $i ) {
				$icon_html .= '<img src="' . esc_attr( $i ) . '" alt="' . esc_attr__( 'PayPal acceptance mark', 'woocommerce' ) . '" />';
			}

			$icon_html .= sprintf( '<a href="%1$s" class="about_paypal" onclick="javascript:window.open(\'%1$s\',\'WIPaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' . esc_attr__( 'What is PayPal?', 'woocommerce' ) . '</a>', esc_url( $this->get_icon_url( WC()->countries->get_base_country() ) ) );

			return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
		}

		/**
		 * Get PayPal images for a country.
		 *
		 * @param string $country Country code.
		 * @return array of image URLs
		 */
		protected function get_icon_image( $country ) {
			switch ( $country ) {
				case 'US':
				case 'NZ':
				case 'CZ':
				case 'HU':
				case 'MY':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
					break;
				case 'TR':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_odeme_secenekleri.jpg';
					break;
				case 'GB':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png';
					break;
				case 'MX':
					$icon = array(
						'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_visa_mastercard_amex.png',
						'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_debit_card_275x60.gif',
					);
					break;
				case 'FR':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_moyens_paiement_fr.jpg';
					break;
				case 'AU':
					$icon = 'https://www.paypalobjects.com/webstatic/en_AU/mktg/logo/Solutions-graphics-1-184x80.jpg';
					break;
				case 'DK':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_PayPal_betalingsmuligheder_dk.jpg';
					break;
				case 'RU':
					$icon = 'https://www.paypalobjects.com/webstatic/ru_RU/mktg/business/pages/logo-center/AM_mc_vs_dc_ae.jpg';
					break;
				case 'NO':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/banner_pl_just_pp_319x110.jpg';
					break;
				case 'CA':
					$icon = 'https://www.paypalobjects.com/webstatic/en_CA/mktg/logo-image/AM_mc_vs_dc_ae.jpg';
					break;
				case 'HK':
					$icon = 'https://www.paypalobjects.com/webstatic/en_HK/mktg/logo/AM_mc_vs_dc_ae.jpg';
					break;
				case 'SG':
					$icon = 'https://www.paypalobjects.com/webstatic/en_SG/mktg/Logos/AM_mc_vs_dc_ae.jpg';
					break;
				case 'TW':
					$icon = 'https://www.paypalobjects.com/webstatic/en_TW/mktg/logos/AM_mc_vs_dc_ae.jpg';
					break;
				case 'TH':
					$icon = 'https://www.paypalobjects.com/webstatic/en_TH/mktg/Logos/AM_mc_vs_dc_ae.jpg';
					break;
				case 'JP':
					$icon = 'https://www.paypal.com/ja_JP/JP/i/bnr/horizontal_solution_4_jcb.gif';
					break;
				case 'IN':
					$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
					break;
				default:
					$icon = WC_HTTPS::force_https_url( WC()->plugin_url() . '/includes/gateways/paypal/assets/images/paypal.png' );
					break;
			}
			return apply_filters( 'woocommerce_paypal_icon', $icon );
		}
		/**
		 * Get the link for an icon based on country.
		 *
		 * @param  string $country Country two letter code.
		 * @return string
		 */
		protected function get_icon_url( $country ) {
			$url           = 'https://www.paypal.com/' . strtolower( $country );
			$home_counties = array( 'BE', 'CZ', 'DK', 'HU', 'IT', 'JP', 'NL', 'NO', 'ES', 'SE', 'TR', 'IN' );
			$countries     = array( 'DZ', 'AU', 'BH', 'BQ', 'BW', 'CA', 'CN', 'CW', 'FI', 'FR', 'DE', 'GR', 'HK', 'ID', 'JO', 'KE', 'KW', 'LU', 'MY', 'MA', 'OM', 'PH', 'PL', 'PT', 'QA', 'IE', 'RU', 'BL', 'SX', 'MF', 'SA', 'SG', 'SK', 'KR', 'SS', 'TW', 'TH', 'AE', 'GB', 'US', 'VN' );

			if ( in_array( $country, $home_counties, true ) ) {
				return $url . '/webapps/mpp/home';
			} elseif ( in_array( $country, $countries, true ) ) {
				return $url . '/webapps/mpp/paypal-popup';
			} else {
				return $url . '/cgi-bin/webscr?cmd=xpt/Marketing/general/WIPaypal-outside';
			}
		}

		public function get_recurring_profile($profileid)
		{
			$data = "&PROFILEID=$profileid";
			$method = "GetRecurringPaymentsProfileDetails";
			$response = $this->request($method, $data);
			return $response;
		}	
	}
}