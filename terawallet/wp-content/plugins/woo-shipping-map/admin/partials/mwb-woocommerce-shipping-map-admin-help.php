<?php
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(isset($_POST['mwb_woocommerce_shipping_map_send_suggestion']))
{
	$mwb_woocommerce_shipping_map_mail_subject = isset($_POST['mwb_woocommerce_shipping_map_mail_subject']) ? $_POST['mwb_woocommerce_shipping_map_mail_subject'] : 'WooCommerce Refund & Exchange Client Needed Help';
	$mwb_woocommerce_shipping_map_mail_content = isset($_POST['mwb_woocommerce_shipping_map_mail_content']) ? $_POST['mwb_woocommerce_shipping_map_mail_subject']  : 'This is default messege please contact me fast so i will feel free from this stress.';

	$mwb_woocommerce_shipping_map_admin_email = get_option('admin_email');

	$status = wp_mail('support@makewebbetter.com',$mwb_woocommerce_shipping_map_mail_subject,$mwb_woocommerce_shipping_map_mail_content);
	if($status)
	{
		$messege = __('Your request is submitted successfully. Our team will respond as soon as possible.','mwb-woocommerce-shipping-map');
		$class = "mwb_woocommerce_shipping_map_mail_success_messege";
	}
	else
	{
		$messege = __('Your request is not submitted. Please try again.','mwb-woocommerce-shipping-map');
		$class = "mwb_woocommerce_shipping_map_mail_unsuccess_messege";
	}
}
?>
<div class="mwb_woocommerce_shipping_map_help_wrapper mwb_woocommerce_shipping_map_help_second_wrapper">
		<div class="mwb_woocommerce_shipping_map_form">
		<form method="POST" action="">
			<div class="mwb-woocommerce-shipping-map-suggestion">
			<?php if(isset($messege)){
					?><div class="<?php echo $class ; ?>"> <?php echo $messege; ?></div><?php 
				} ?>
				<h2><?php _e('Suggestion or Query','mwb-woocommerce-shipping-map'); ?></h2>

				<div class="mwb_woocommerce_shipping_map_help_input-wrap">
					<label><?php _e('Enter Suggestion or Query title here','mwb-woocommerce-shipping-map'); ?></label>
					<div class="mwb_woocommerce_shipping_map_help_input">
						<input class="mwb_woocommerce_shipping_map_help_form-control text-field" type="text" name='mwb_woocommerce_shipping_map_mail_subject'>
					</div>
				</div>
				<div class="mwb_woocommerce_shipping_map_help_input-wrap">
					<label><?php _e('Enter Suggestion or Query detail here','mwb-woocommerce-shipping-map'); ?></label>
					<div class="mwb_woocommerce_shipping_map_help_input">
						<textarea  class="mwb_woocommerce_shipping_map_help_form-control" name="mwb_woocommerce_shipping_map_mail_content"></textarea>
					</div>
				</div>
				<div class="mwb_woocommerce_shipping_map_help-send-suggetion-button-wrap">
					<input type="submit" name="mwb_woocommerce_shipping_map_send_suggestion" value="<?php _e('Send Suggestion','mwb-woocommerce-shipping-map'); ?>" class="button-primary mwb_woocommerce_shipping_map_help-send-suggetion-button">
				</div>
			</div>
		</form>
	</div>
	<?php
	$mwb_woocommerce_shipping_map_help_data = wp_remote_get('https://demo.makewebbetter.com/api/help.json');

	if(array_key_exists('body', $mwb_woocommerce_shipping_map_help_data) && !empty($mwb_woocommerce_shipping_map_help_data['body']))
	{
		$mwb_woocommerce_shipping_map_help_data = json_decode($mwb_woocommerce_shipping_map_help_data['body']);
		if(isset($mwb_woocommerce_shipping_map_help_data->mwb_woocommerce_shipping_map))
		{	
			$mwb_woocommerce_shipping_map_plugin_help_detail = $mwb_woocommerce_shipping_map_help_data->mwb_woocommerce_shipping_map;
		 ?>
			<div class="mwb_woocommerce_shipping_map_doc_video_section">
				<ul class="mwb_woocommerce_shipping_map_help-link-wrap">
					<li>
						<a class="mwb_woocommerce_shipping_map_help-link" href="<?php echo $mwb_woocommerce_shipping_map_plugin_help_detail->documentation;  ?>" target="_blank">
							<img class="mwb_woocommerce_shipping_map_icon" src="<?php echo MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_URL.'admin/images/documentation.png' ?>" alt="">
							<?php _e('Documentation','mwb-woocommerce-shipping-map'); ?>
						</a>
					</li>
					<li>
						<a class="mwb_woocommerce_shipping_map_help-link" href="<?php echo $mwb_woocommerce_shipping_map_plugin_help_detail->faq;  ?>" target="_blank">
							<img class="mwb_woocommerce_shipping_map_icon" src="<?php echo MWB_WOOCOMMERCE_SHIPPING_MAP_DIR_URL.'admin/images/faq.png' ?>" alt="">
							<?php _e('FAQ','mwb-woocommerce-shipping-map'); ?>
						</a>
					</li>
				</ul><?php 
				if(is_array($mwb_woocommerce_shipping_map_plugin_help_detail->video_iframe_src) && !empty($mwb_woocommerce_shipping_map_plugin_help_detail->video_iframe_src)) :
					foreach ($mwb_woocommerce_shipping_map_plugin_help_detail->video_iframe_src as $video_data) : 
						?>
						<div class="mwb_woocommerce_shipping_map_video_content">
							<div class="mwb_woocommerce_shipping_map_video_section">
								<iframe src="<?php echo $video_data->iframe_src; ?>" allowfullscreen="" width="100%" height="315" frameborder="0"></iframe>
								<div class="mwb_woocommerce_shipping_map_vedio_feature_name"><?php _e($video_data->feature_name,'mwb-woocommerce-shipping-map'); ?></div>
							</div>
						</div> <?php
					endforeach;
				endif; ?> 
			</div>
		<?php
		}
	}?>
</div>
