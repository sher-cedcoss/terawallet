<?php
if (isset($_POST['mwb_save_setting'])) {
	unset($_POST['mwb_save_setting']);
	$postdata = $_POST;
	// print_r($postdata);die;

	foreach ($postdata as $key => $value) {

		$postdata[$key] = stripcslashes($value);
	}
	$savedata = json_encode($postdata);
	update_option('mwb_festive_decoration_data', $savedata);
}

$mwb_festive_decoration_data = get_option('mwb_festive_decoration_data', false);
$mwb_data = json_decode($mwb_festive_decoration_data, true);

$mwb_enable_festive_decoration = isset($mwb_data['mwb_enable_festive_decoration']) ? $mwb_data['mwb_enable_festive_decoration'] : "";

$mwb_festive_header_image = isset($mwb_data['mwb_festive_header_image']) ? $mwb_data['mwb_festive_header_image'] : "";

$mwb_festive_footer_image = isset($mwb_data['mwb_festive_footer_image']) ? $mwb_data['mwb_festive_footer_image'] : "";

$mwb_festive_falling_image = isset($mwb_data['mwb_festive_falling_image']) ? $mwb_data['mwb_festive_falling_image'] : "";

$mwb_festive_custom_css = isset($mwb_data['mwb_festive_custom_css']) ? $mwb_data['mwb_festive_custom_css'] : "";

// print_r($mwb_enable_cart_coupon_show);die;

?>
<div class="wrap">
	<h2>Festive Decoration Setting</h2>

	<form method="post" enctype="mutipart/form-data">

		<h2>Festive Decoration Setting</h2>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Enable Festive Decoration
					</th>
					<td class="forminp forminp-text">
						<input type="checkbox" name="mwb_enable_festive_decoration" value="1" id="mwb_enable_festive_decoration" <?php if (isset($mwb_enable_festive_decoration) && !empty($mwb_enable_festive_decoration)) {
																																		echo 'checked="checked"';
																																	} ?>>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Festive Header Image
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_festive_header_image" id="mwb_festive_header_image" type="text" value="<?php echo $mwb_festive_header_image; ?>">
						<input type="button" value="Upload Image" class="button-primary upload_festive_image" id="upload_header_image" />
						<div style="display:block;padding-top:30px;">
							<img src="<?php echo $mwb_festive_header_image; ?>" style="height:80px;" alt="Image">
						</div>
					</td>

				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Festive Footer Image
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_festive_footer_image" id="mwb_festive_footer_image" type="text" value="<?php echo $mwb_festive_footer_image; ?>">
						<input type="button" value="Upload Image" class="button-primary upload_festive_image" id="upload_footer_image" />

						<div style="display:block;padding-top:30px;">
							<img src="<?php echo $mwb_festive_footer_image; ?>" style="height:80px;" alt="Image">
						</div>
					</td>

				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Festive Falling Image
					</th>
					<td class="forminp forminp-text">
						<input name="mwb_festive_falling_image" id="mwb_festive_falling_image" type="text" value="<?php echo $mwb_festive_falling_image; ?>">
						<input type="button" value="Upload Image" class="button-primary upload_festive_image" id="upload_falling_image" />

						<div style="display:block;padding-top:30px;">
							<img src="<?php echo $mwb_festive_falling_image; ?>" style="height:150px;" alt="Image">
						</div>
					</td>

				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						Custom CSS
					</th>
					<td class="forminp forminp-text">
						<textarea name="mwb_festive_custom_css" id="mwb_festive_custom_css" rows="8" cols="50"><?php echo $mwb_festive_custom_css; ?></textarea>
					</td>

				</tr>
			</tbody>
		</table>


		<p class="submit">
			<input name="mwb_save_setting" class="button-primary woocommerce-save-button" type="submit" value="Save changes">
		</p>
	</form>
</div>