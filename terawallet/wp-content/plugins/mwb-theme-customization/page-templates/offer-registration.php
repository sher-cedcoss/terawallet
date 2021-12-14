<?php
get_header();

// if(isset($_POST['mwb-offer-user-register']))
// {
// 	$mwb_already_registered = false;
// 	$mwb_user_name = $_POST['mwb-offer-user-name'];
// 	$mwb_user_email = $_POST['mwb-offer-user-email'];

// 	$mwb_user_data = array(
// 		'mwb_user_name' => $mwb_user_name,
// 		'mwb_user_email' => $mwb_user_email
// 		);

// 	$mwb_offer_user_data = get_option('mwb_offer_user_data', false);
// 	$mwb_offer_user_decoded_data = json_decode($mwb_offer_user_data , true);

// 	if(!is_array($mwb_offer_user_decoded_data))
// 	{
// 		$mwb_offer_user_decoded_data = array();
// 	}

// 	if(in_array($mwb_user_email, array_column($mwb_offer_user_decoded_data, 'mwb_user_email')))
// 	{
// 		$mwb_already_registered = true;
// 	}
// 	else
// 	{
// 		$mwb_already_registered = false;
// 		$mwb_offer_user_decoded_data[] = $mwb_user_data;

// 		$savedata = json_encode($mwb_offer_user_decoded_data);
// 		update_option('mwb_offer_user_data', $savedata);

// 		$to = $mwb_user_email;
// 		$subject = "Hurray!!! You have successfully registered to gain 50% OFF";
// 		$message = '<html lang="en"> <head> <meta charset="utf-8"> <meta content="width=device-width, initial-scale=1.0" name="viewport"> <meta content="noindex" name="robots"> </head> <body style="margin: 0; padding: 0;"> 
// 		<div style="width:700px;padding:5px;margin:auto;font-size:14px;line-height:18px"></p><table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="header" width="100%"><tr><td align="center" style="border-bottom: 1px solid #f7f7f7; padding: 12px 0;"> <a href="https://makewebbetter.com"> <img alt="" border="0" src="https://makewebbetter.com/wp-content/uploads/2018/12/mwb-xmas-logo.png" width="100"> </a> </td></tr></table><style>div{margin: 0 auto !important;max-width: 700px !important;width: 100% !important;}table{border-collapse: collapse; border-spacing: 0;}td.text, .has-btn{mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none;}a[x-apple-data-detectors]{color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;}@media screen and (max-width: 600px){.text{font-size: 14px !important; line-height: 21px !important;}.h1{font-size: 20px !important;}.h2{font-size: 18px !important;}.h3{font-size: 16px !important;}.h4{font-size: 14px !important;}.h5{font-size: 14px !important;}table{line-height: 1.5 !important;}.h1, .h2, .h3, .h4, .h5{line-height: 1.2 !important;}.cs-logo{width: 120px !important;}.flex-size{max-width: 100% !important; width: 100% !important;}.flex-size img{max-width: 100% !important;}.s-height{height: 10px !important;}.s-db{display: block !important;}.s-dib{display: inline-block !important;}.s-hf{height: 0 !important;}.s-paf{padding: 0 !important;}.s-pbf{padding-bottom: 0 !important;}.s-pbm{padding-bottom: 16px !important;}.s-plf{padding-left: 0 !important;}.s-prf{padding-right: 0 !important;}.s-pts{padding-top: 8px !important;}.s-ptm{padding-top: 16px !important;}.s-ptl{padding-top: 32px !important;}.s-tac{text-align: center !important;}.s-tal{text-align: left !important;}}@media screen and (min-device-width: 375px) and (max-device-width: 667px){table{font-size: 16px !important;}table.footer-content{font-size: 12px !important;}}.footer a{color: #aebdc1;}.text a{transition: color 0.25s linear;}.text a:hover{color: #58bb69 !important;}.btn-a{transition: background 0.25s linear;}.btn-a:hover{background: #58bb69 !important;}</style><table bgcolor="#408dbd" border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="padding: 40px 20px 48px 20px;"><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size"><tr><td style="font-size: 0;">&nbsp;</td><td bgcolor="#ffffff" class="f-outlook" style="border-radius: 6px; padding: 0 40px 40px 40px;" width="480"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>
// 			<td align="left" class="text h2" style="color: #575a5b; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 300; line-height: 42px; padding-top: 16px; text-align: center; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none;"> Hi, '.$mwb_user_name.' </td>
// 		</tr>
// 		<tr>
// 			<td align="left" class="text" style="color: #393d40; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none;">This is a kind note to tell you that ‘You have Successfully Registered with MakeWebBetter to gain 50% OFF on 12.12’. Grab the best of plugins/extensions this festival and make your web happy!</td>
// 		</tr>
// 		<tr>
// 			<td align="left" class="text" style="color: #393d40; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none;">If you have any query related to your purchase, simply revert us. We will get back to you soon.</td>
// 		</tr>
// 		<tr>
// 			<td align="left" class="text" style="color: #393d40; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none;">[<strong>Note:</strong> You will receive the coupon code at 11:59 pm midnight of Dec 11, 2018] </td>
// 		</tr>
// 		<tr>
// 			<td align="left" class="text" style="color: #393d40; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none;">So, stay awake and do better shopping!</td>
// 		</tr>
// 		<tr>
// 			<td align="left" class="text" style="color: #393d40; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; padding-top: 8px; mso-line-height-rule: exactly; -webkit-font-smoothing: antialiased; -ms-text-size-adjust: none; -webkit-text-size-adjust: none;">Thanks!</td>
// 		</tr></table></td><td style="font-size: 0;">&nbsp;</td></tr></table></td></tr></table><table bgcolor="#2d3033" border="0" cellpadding="0" cellspacing="0" class="wrapper" style="border-collapse:collapse;border-spacing:0;color: #aebdc1; font-size: 12px;" width="100%"><tr><td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0"><tr><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://twitter.com/makewebbetter" style="text-decoration: none;"> <img alt="Twitter Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/social-twitter.png" width="30"> </a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://www.facebook.com/makewebbetter" style="text-decoration: none;"> <img alt="Facebook Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/social-facebook.png" width="30"></a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://www.youtube.com/channel/UC7nYNf0JETOwW3GOD_EW2Ag" style="text-decoration: none;"> <img alt="Youtube Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/email_social_youtube.png" width="30"></a> </td><td align="center" style="padding: 32px 12px 0 12px;"> <a href="https://plus.google.com/111610242430101820802" style="text-decoration: none;"> <img alt="Google+ Icon" border="0" height="30" src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/email_social_gplus.png" width="30"></a> </td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse:collapse;border-spacing:0"><tr><td align="left" class="text--footer s-tac" style="color: #aebdc1; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 24px;"> Have questions or need assistance? </td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse:collapse;border-spacing:0"><tr><td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse:collapse;border-spacing:0;max-width: 384px;" width="100%"><tr><td style="font-size: 0; text-align: center;"><table align="left" border="0" cellpadding="0" cellspacing="0" class="content" width="182" style="border-collapse:collapse;border-spacing:0"><tr><td align="center" style="padding: 16px 16px 0 16px;"> <a href="https://makewebbetter.freshdesk.com/support/tickets/new" style="background-color:#2d3033;border:1px solid #aebdc1;border-radius:20px;color:#aebdc1;display:inline-block;font-family:Open Sans, Verdana, Helvetica, Arial, sans-serif;font-size:12px;font-weight:300;line-height:40px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;text-transform: uppercase;mso-hide:all;">Support</a> </td></tr></table><table align="left" border="0" cellpadding="0" cellspacing="0" class="content" width="182" style="border-collapse:collapse;border-spacing:0"><tr><td align="center" style="padding: 16px 16px 0 16px;"> <a href="http://docs.makewebbetter.com/" style="background-color:#2d3033;border:1px solid #aebdc1;border-radius:20px;color:#aebdc1;display:inline-block;font-family:Open Sans, Verdana, Helvetica, Arial, sans-serif;font-size:12px;font-weight:300;line-height:40px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;text-transform: uppercase;mso-hide:all;">FAQ</a> </td></tr></table></td></tr></table></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="flex-size" style="border-collapse:collapse;border-spacing:0"><tr><td align="center" class="text--footer" style="color: #aebdc1; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 18px; padding: 32px 20px 0 20px;"> &copy; 2018 MakeWebBetter. All right Reserved </td></tr><tr><td align="center" class="text--footers" style="color: #aebdc1; font-family: Open Sans, Verdana, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 18px; padding: 16px 0 32px 0;"> </td></tr></table></td></tr></table>
// 		</div>
// 		</body>
// 		</html>';
// 		wc_mail($to, $subject, $message);

// 	}
// }
?>

<section class="offer-registration-section">
	<div class="offer-registration-container">

		<div class="offer-registration-main-wrapper">
			<div class="offer-registration-content-wrapper">
				<div class="offer-registration-header">
				<span id="mwb-offer-response" class="mwb-offer-response" >Registration Closed</span>
					<?php 
					if($mwb_already_registered)
					{
						?>
					<span id="mwb-offer-response" class="mwb-offer-response" >You have already registered with this email.</span>
					<?php
					}
					 
					if(isset($_POST['mwb-offer-user-register']) && $mwb_already_registered == false)
					{
						?>
					<span id="mwb-offer-response" class="mwb-offer-response" >Thanks for Registration! Check your inbox for Amazing Offer.</span>
					<?php
					}
					?>
					<p>Be an active part of Biggest Shopping Bonanza </p>
					<h1>12.12</h1>
					<p>And avail the best of sale clad with <b>FLAT 50% OFF</b> </p>
				</div>
				<div class="offer-registration-main-content">
					<div class="offer-registration-content">
						<h2>After a quick Registration, the <b>OFFER</b> will land on your inbox! </h2>
						<form action="" class="offer-registration-form" method="post">
							<div class="offer-registration-form-wrap">
								<input type="text" placeholder="Name:John" name="mwb-offer-user-name" id="mwb-offer-user-name">
							</div>
							<div class="offer-registration-form-wrap">
								<input type="text" placeholder="Email:example@domain.com" name="mwb-offer-user-email" id="mwb-offer-user-email">
							</div>
							<input type="submit" class="offer-registration-submit-button" value="Grab 1212" name="mwb-offer-user-register" id="mwb-offer-user-register" disabled="disabled" >
						</form>
					</div>
					<div class="offer-registration-footer">
						<b>Note:</b> Be the early bird to grab the offer within 1212 lucky customers. 
					</div>
				</div>
			</div>
			<div class="offer-registration-image-wrapper">
				<img src="https://makewebbetter.com/wp-content/uploads/2018/12/banner-image.png" alt="Offer user registration">
			</div>
		</div>
	</div>
	
</section>

<?php
get_footer();
?>