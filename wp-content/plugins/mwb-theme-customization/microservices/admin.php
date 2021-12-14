<?php
$notice = false;
if(isset($_POST['mwb_save_setting'])){
	unset($_POST['mwb_save_setting']);
	$postdata = $_POST;
	// print_r($postdata);die;
	
	foreach ($postdata as $key => $value) {
		if($key == 'show_coupon')
		{
			continue;
		}
		else
		{
			$postdata[$key] = stripcslashes($value);
		}
	}
	$savedata = json_encode($postdata);	
	$submitted = update_option('mwb_microservice_notification', $savedata);
	if($submitted == true){
		$notice = true;
	}
}	

$mwb_microservice_notification_json = get_option('mwb_microservice_notification', false);
$mwb_data = json_decode($mwb_microservice_notification_json , true);

$mwb_microservice_hourlymailaddress = isset($mwb_data['mwb_microservice_hourlymailaddress'])?$mwb_data['mwb_microservice_hourlymailaddress']:"";
$mwb_microservice_hourlymailsubject = isset($mwb_data['mwb_microservice_hourlymailsubject'])?$mwb_data['mwb_microservice_hourlymailsubject']:"";
$mwb_microservice_hourlymail_content = isset($mwb_data['mwb_microservice_hourlymail_content'])?$mwb_data['mwb_microservice_hourlymail_content']:"";
$mwb_microservice_userhourlymailsubject = isset($mwb_data['mwb_microservice_userhourlymailsubject'])?$mwb_data['mwb_microservice_userhourlymailsubject']:"";
$mwb_microservice_userhourlymail_content = isset($mwb_data['mwb_microservice_userhourlymail_content'])?$mwb_data['mwb_microservice_userhourlymail_content']:"";
$mwb_microservice_fixedmailaddress = isset($mwb_data['mwb_microservice_fixedmailaddress'])?$mwb_data['mwb_microservice_fixedmailaddress']:"";
$mwb_microservice_fixedmailsubject = isset($mwb_data['mwb_microservice_fixedmailsubject'])?$mwb_data['mwb_microservice_fixedmailsubject']:"";
$mwb_microservice_fixedmail_content = isset($mwb_data['mwb_microservice_fixedmail_content'])?$mwb_data['mwb_microservice_fixedmail_content']:"";
$mwb_microservice_userfixedmailsubject = isset($mwb_data['mwb_microservice_userfixedmailsubject'])?$mwb_data['mwb_microservice_userfixedmailsubject']:"";
$mwb_microservice_userfixedmail_content = isset($mwb_data['mwb_microservice_userfixedmail_content'])?$mwb_data['mwb_microservice_userfixedmail_content']:"";

$mwb_microservice_userdescriptionfixedmailsubject = isset($mwb_data['mwb_microservice_userdescriptionfixedmailsubject'])?$mwb_data['mwb_microservice_userdescriptionfixedmailsubject']:"";

$mwb_microservice_userdescriptionfixedmail_content = isset($mwb_data['mwb_microservice_userdescriptionfixedmail_content'])?$mwb_data['mwb_microservice_userdescriptionfixedmail_content']:"";

$mwb_purchase_time_support = isset($mwb_data['mwb_purchase_time_support'])?$mwb_data['mwb_purchase_time_support']:"";

$mwb_extend_time_support = isset($mwb_data['mwb_extend_time_support'])?$mwb_data['mwb_extend_time_support']:"";

$mwb_renew_time_support = isset($mwb_data['mwb_renew_time_support'])?$mwb_data['mwb_renew_time_support']:"";

$mwb_oneyear_support = isset($mwb_data['mwb_oneyear_support'])?$mwb_data['mwb_oneyear_support']:"";

$mwb_twoyear_support = isset($mwb_data['mwb_twoyear_support'])?$mwb_data['mwb_twoyear_support']:"";

$mwb_twoyear_updates = isset($mwb_data['mwb_twoyear_updates'])?$mwb_data['mwb_twoyear_updates']:"";

$mwb_threeyear_updates = isset($mwb_data['mwb_threeyear_updates'])?$mwb_data['mwb_threeyear_updates']:"";

$mwb_product_installation_charge = isset($mwb_data['mwb_product_installation_charge'])?$mwb_data['mwb_product_installation_charge']:"";

$mwb_show_coupon = isset($mwb_data['show_coupon'])?$mwb_data['show_coupon']:"";

$mwb_enable_cart_coupon_show = isset($mwb_data['mwb_enable_cart_coupon_show'])?$mwb_data['mwb_enable_cart_coupon_show']:"";

$mwb_enable_notification_bar = isset($mwb_data['mwb_enable_notification_bar'])?$mwb_data['mwb_enable_notification_bar']:"";

$mwb_notification_bar_custom_css = isset($mwb_data['mwb_notification_bar_custom_css'])?$mwb_data['mwb_notification_bar_custom_css']:"";

$mwb_notification_bar_content = isset($mwb_data['mwb_notification_bar_content'])?$mwb_data['mwb_notification_bar_content']:"";



$mwb_welcome_mail_subject = isset($mwb_data['mwb_welcome_mail_subject'])?$mwb_data['mwb_welcome_mail_subject']:"";
$mwb_welcome_mail_content = isset($mwb_data['mwb_welcome_mail_content'])?$mwb_data['mwb_welcome_mail_content']:"";
$mwb_reminder_mail_subject = isset($mwb_data['mwb_reminder_mail_subject'])?$mwb_data['mwb_reminder_mail_subject']:"";
$mwb_reminder_mail_content = isset($mwb_data['mwb_reminder_mail_content'])?$mwb_data['mwb_reminder_mail_content']:"";
$mwb_license_activation_mail_subject = isset($mwb_data['mwb_license_activation_mail_subject'])?$mwb_data['mwb_license_activation_mail_subject']:"";
$mwb_license_activation_mail_content = isset($mwb_data['mwb_license_activation_mail_content'])?$mwb_data['mwb_license_activation_mail_content']:"";


// print_r($mwb_enable_cart_coupon_show);die;

?>

<div class="wrap">
	<h2>Microservice Mail Notifications</h2>
	<?php
	if(isset($notice) && $notice == true)
	{
		?>
		<div class="notice notice-success" >
			<p>Form is successfully submitted! </p>
		</div>
		<?php
	}
	?>
	<h2>Microservice Hourly Rate Mail Notifications</h2>
	<p><strong>Note: Use the following code in mail</strong></p>
	<p>{servicename}: Service name which user ordered.</p>
	<p>{servicehourlyrate}: Service hourly rate.</p>
	<p>{serviceusermail}: Service User Mail</p>
	<p>{servicedescription}: Service Description</p>
	<p>{servicerecordlink}: Service Record Admin Link</p>
	<p>{servicedescriptionlink}: Service Descrition Submit Myaccount Link</p>

	<form method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Admin Mail Address
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_microservice_hourlymailaddress" id="mwb_microservice_hourlymailaddress" type="text" value="<?php echo $mwb_microservice_hourlymailaddress;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Admin Mail Subject
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_microservice_hourlymailsubject" id="mwb_microservice_hourlymailsubject" type="text" value="<?php echo $mwb_microservice_hourlymailsubject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Admin Mail Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_microservice_hourlymail_content";
						$content = $mwb_microservice_hourlymail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						User Mail Subject
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_microservice_userhourlymailsubject" id="mwb_microservice_userhourlymailsubject" type="text" value="<?php echo $mwb_microservice_userhourlymailsubject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						User Mail Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_microservice_userhourlymail_content";
						$content = $mwb_microservice_userhourlymail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
				
			</tbody>
		</table>	

		<h2>Microservice Fixed Rate Mail Notifications</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Admin Mail Address
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_microservice_fixedmailaddress" id="mwb_microservice_fixedmailaddress" type="text" value="<?php echo $mwb_microservice_fixedmailaddress;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Admin Mail Subject
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_microservice_fixedmailsubject" id="mwb_microservice_fixedmailsubject" type="text" value="<?php echo $mwb_microservice_fixedmailsubject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Admin Mail Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_microservice_fixedmail_content";
						$content = $mwb_microservice_fixedmail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						User Mail Subject
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_microservice_userfixedmailsubject" id="mwb_microservice_userfixedmailsubject" type="text" value="<?php echo $mwb_microservice_userfixedmailsubject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						User Mail Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_microservice_userfixedmail_content";
						$content = $mwb_microservice_userfixedmail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" class="titledesc">
						User Mail Subject For Description Submitted
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_microservice_userdescriptionfixedmailsubject" id="mwb_microservice_userdescriptionfixedmailsubject" type="text" value="<?php echo $mwb_microservice_userdescriptionfixedmailsubject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						User Mail Content For Description Submitted
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_microservice_userdescriptionfixedmail_content";
						$content = $mwb_microservice_userdescriptionfixedmail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
				
			</tbody>
		</table>

		<h2>Support License Price</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						On Purchase Time (%)
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_purchase_time_support" id="mwb_purchase_time_support" type="text" value="<?php echo $mwb_purchase_time_support;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						On Extend Time (%)
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_extend_time_support" id="mwb_extend_time_support" type="text" value="<?php echo $mwb_extend_time_support;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						On Renew Time (%)
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_renew_time_support" id="mwb_renew_time_support" type="text" value="<?php echo $mwb_renew_time_support;?>"> 						
					</td>
				</tr>
			</tbody>
		</table>

		<h2>Support Time Period Price</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						1 Year Support (%)
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_oneyear_support" id="mwb_oneyear_support" type="text" value="<?php echo $mwb_oneyear_support;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						2 Year Support (%)
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_twoyear_support" id="mwb_twoyear_support" type="text" value="<?php echo $mwb_twoyear_support;?>"> 						
					</td>
				</tr>
			</tbody>
		</table>

		<h2>Updates Time Period Price</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						2 Year Updates (%)
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_twoyear_updates" id="mwb_twoyear_updates" type="text" value="<?php echo $mwb_twoyear_updates;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						3 Year Updates (%)
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_threeyear_updates" id="mwb_threeyear_updates" type="text" value="<?php echo $mwb_threeyear_updates;?>"> 						
					</td>
				</tr>
			</tbody>
		</table>

		<h2>Installation Price</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Installation
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_product_installation_charge" id="mwb_product_installation_charge" type="text" value="<?php echo $mwb_product_installation_charge;?>"> 						
					</td>
				</tr>
			</tbody>
		</table>

		<h2>Coupon Show Setting</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Enable Coupon Show On Cart
					</th>
					<td class="forminp forminp-text">
						<input type="checkbox" name="mwb_enable_cart_coupon_show" value="1" id="mwb_enable_cart_coupon_show" <?php if(isset($mwb_enable_cart_coupon_show) && !empty($mwb_enable_cart_coupon_show)){echo 'checked="checked"';} ?> >					
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Select Coupon From List To Show On Cart
					</th>
					<td class="forminp forminp-text">
						<select class="js-example-basic-multiple" name="show_coupon[]" multiple="multiple" >
					    <?php 
					    $args = array(
					        'posts_per_page'   => -1,
					        'orderby'          => 'title',
					        'order'            => 'asc',
					        'post_type'        => 'shop_coupon',
					        'post_status'      => 'publish',
					    );
					    
					    $coupons = get_posts( $args );
					    foreach($coupons as $coupon)
					    {
					    ?>
							<option <?php if(in_array($coupon->ID , $mwb_show_coupon)){ echo "selected='selected'"; } ?> value="<?php echo $coupon->ID; ?>"><?php echo $coupon->post_title; ?></option>
						    <?php
					    }
					    ?> 
						</select>						
					</td>
				</tr>
			</tbody>
		</table>

		<h2>Top Header Notification Bar</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Enable Notification Bar
					</th>
					<td class="forminp forminp-text">
						<input type="checkbox" name="mwb_enable_notification_bar" value="1" id="mwb_enable_notification_bar" <?php if(isset($mwb_enable_notification_bar) && !empty($mwb_enable_notification_bar)){echo 'checked="checked"';} ?> >					
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Custom CSS
					</th>
					<td class="forminp forminp-text">
						<textarea name="mwb_notification_bar_custom_css" id="mwb_notification_bar_custom_css" rows="8" cols="50"><?php echo $mwb_notification_bar_custom_css;?></textarea>						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Notification Bar Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_notification_bar_content";
						$content = $mwb_notification_bar_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
				
				
			</tbody>
		</table>



		<h2>Welcome Mail Notification</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Mail Subject
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_welcome_mail_subject" id="mwb_welcome_mail_subject" type="text" value="<?php echo $mwb_welcome_mail_subject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Mail Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_welcome_mail_content";
						$content = $mwb_welcome_mail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
			</tbody>
		</table>


		<h2>Subscription Reminder Mail Notification</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Mail Subject
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_reminder_mail_subject" id="mwb_reminder_mail_subject" type="text" value="<?php echo $mwb_reminder_mail_subject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Mail Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_reminder_mail_content";
						$content = $mwb_reminder_mail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
			</tbody>
		</table>


		<h2>License Activation Mail Notification</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Mail Subject
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_license_activation_mail_subject" id="mwb_license_activation_mail_subject" type="text" value="<?php echo $mwb_license_activation_mail_subject;?>"> 						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Mail Content
					</th>
					<td class="forminp forminp-text">
						<?php 
						$editor_id = "mwb_license_activation_mail_content";
						$content = $mwb_license_activation_mail_content;
						wp_editor( $content, $editor_id );?>						
					</td>
				</tr>
			</tbody>
		</table>


		<p class="submit">
			<input name="mwb_save_setting" class="button-primary woocommerce-save-button" type="submit" value="Save changes">
		</p>	
	</form>
</div>