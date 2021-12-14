<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
do_action( 'woocommerce_shop_loop' );
?>
<?php
if ( have_posts() )
{ 
	while (have_posts())
	{
		the_post(); 

		$product_id = get_the_ID();
		$product = wc_get_product($product_id);
//print_r($product);
		$checkouturl = wc_get_checkout_url();
		$post_thumbnail_id = get_post_thumbnail_id( $product_id );
		$imageurl = wp_get_attachment_image_url( $post_thumbnail_id, "full" );
		$codecanyon_link = get_post_meta($product_id, 'mwb_codcanyon_product_link', true);
		$mwb_codecanyon_id = get_post_meta($product_id, 'mwb_codecanyon_product_id', true);
		$mwb_plugin_front_end_demo=get_post_meta($product_id,'mwb_plugin_front_end_demo',true);
		?>
		<section id="page-header" class="page-header hubspot-woo-integration <?php echo $product->get_data()['slug']; ?>" role="banner">
			<div class="wrap">
				<div class="mwb_hub_start_content hubspot-woo-integration__wrapper">
					<!-- <h2>Connect</h2> -->
					<h1 class="mwb_products_add_title"><?php the_title(); ?></h1>
					<div class="hubspot-woo-integration__content">
						<?php the_excerpt(); ?>
					</div>
					<div class="mwb_pricing_wrapper">
						<p><?php echo do_shortcode('[mwb_product_price]'); ?></p>
					</div>
					<div class="hubspot-woo-integration__button">
						<?php
						if(isset($codecanyon_link) && !empty($codecanyon_link))
						{
							?>
							<form class="cart"  action="https://codecanyon.net/cart/add/<?php echo $mwb_codecanyon_id;?>" accept-charset="UTF-8" method="post" id="mwb_codecanyon_product_template_form">
								<input name="utf8" type="hidden" value="✓">
								<input type="hidden" value="regular" name="license">  
								<input type="hidden" name="support" value="bundle_6month">
								<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="button mwb_product_codecanyon_button">Purchase Now</button>
							</form>
							<?php
						}
						else
						{
							if($product->is_type('variable'))
							{
								$variation_id = $product->get_children()[0];
								?>
								<a class="button" href="#mwb_features_pdf">Features</a>
								<?php
							}
							else
							{
								?>
								<a class="button" href="<?php echo $checkouturl ; ?>?add-to-cart=<?php echo $product_id ; ?>">Purchase Now</a>
								<?php
							}

							if(isset($mwb_plugin_front_end_demo) && !empty($mwb_plugin_front_end_demo))
							{
								?>
								<a href="<?php echo $mwb_plugin_front_end_demo; ?>" class="button">Live Demo</a>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</section>
		<div id="genesis-content" class="mwb_woocommerce_template">



			<?php
// if ( have_posts() ) {
// 	while ( have_posts() ) {
// 		the_post(); 
// the_content();
// 		//
// 		// Post Content here
// 		//
// comments_template();
// 	} // end while
// } // end if
			?>


			<div class="hubspot-woocommerce-integration">
				<div class="template-wrapper">
					<section id="hubspot-features" class="section section-pd-80 feature-section mwb_abandoned_features_section">
						<div class="template-row">
							<div class="template-col">
								<h2 class="section-heading text-center">Featured Packed and Full of Benefits</h2>
							</div>
							<div class="flex-box">
								<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
									<h3 class="section-heading-md">Map and Sync contact properties</h3>
									<p>Map the HubSpot Contact Properties with WordPress user's or customer's fields. The fields will get mapped and synced once the customers’ data will get updated over HubSpot. Easily map and sync new contact properties over HubSpot</p>
								</div>
								<div class="template-col feature-section__col feature-section__col--image feature-section__col--right"><a href="javascript:void(0);"><br />
									<img class="alignnone wp-image-10731 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/10/HubSpot-Field-to-Field-Sync.jpg" alt="hubspot field to field sync Map and Sync contact properties" width="1166" height="983" /><br />
								</a></div>
							</div>
						</div>
					</section>

<section class="mwb_hub_review_section mwb_hubspot_common_section">
	<div class="mwb_hub_review_wrap">
		<div class="mwb_hub_review">
			<h2 class="section-heading text-center">What People say</h2>
			<img src="https://makewebbetter.com/wp-content/uploads/2018/10/02.jpg" class="mwb_hub_review_image" alt="hubspot field to field sync user review">
			<h6>Mohammed Fahad</h6>
			<div class="mwb_hub_star_review">
				<span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt="hubspot field to field sync star review"></span><span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt="hubspot field to field sync star review"></span><span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt="hubspot field to field sync star review"></span><span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt="hubspot field to field sync star review"></span><span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt="hubspot field to field sync star review"></span>
			</div>
			<h3>Their support has been great!</h3>
			<p>Their support has been great! They are very responsive via Skype, and helped us implement their plugin and ensure it's working dispite our unusual website setup. They stand behind their products!</p>

			<div class="mwb_review_redirect_section">

				<a target="_blank" href='https://www.capterra.com/reviews/179620/HubSpot-WooCommerce-Integration-PRO?utm_source=vendor&utm_medium=badge&utm_campaign=capterra_reviews_badge'><img border='0' src='https://assets.capterra.com/badge/12488b742415d32da70c34ac3ab82012.png?v=2125271&p=179620' /></a><script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script><div class="mwb_trustpilot_wrap"><h2>We are rated Excellent</h2><div class="trustpilot-widget" data-locale="en-US" data-template-id="5419b6a8b0d04a076446a9ad" data-businessunit-id="5b9fbc0023057e0001acfbdd" data-style-height="60px" data-style-width="100%" data-theme="light"><a href="https://www.trustpilot.com/review/makewebbetter.com" target="_blank">Trustpilot</a></div></div>
			</div>
		</div>
	</div>
</section>

<section id="hubspot-pricing" class="section section-pd-80 hubspot-pricing">
	<div class="template-container">
		<div class="template-row">
			<div class="template-col text-center">
				<h2 class="section-heading text-center text-white">Pricing</h2>
				<h3 class="section-heading__title text-center text-white">( For all HubSpot WooCommerce Integration Plans)</h3>
				<div class="hubspot-pricing__container clearfix">
					<div class="hubspot-pricing__wrapper hubspot_abondoned_wrapper">
						<div class="hubspot-pricing__price">
							<h2>For All</h2>
							<h2 class="price">$79</h2>
							<p class="title">Lifetime</p>
						</div>
						<ul class="check-list">
							<li class="check-list-item">Map and synchronize existing HubSpot contact properties with the WordPress users’ fields </li>
							<li class="check-list-item">Customize or change the contact property values </li>


							<li class="check-list-item"><b>24*7</b> Phone, Email & Skype support</li>
							<li class="check-list-item">Regular updates</li>

						</ul>
						<p><a class="button" href="https://makewebbetter.com/checkout/?add-to-cart=4087">Get Add-on</a></p>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<section class="mwb_hub_faq_section mwb_hubspot_common_section">
	<div class="mwb_hub_faq_content_wrap">
		<h2 class="section-heading text-center">Frequently Asked Questions</h2>
		<div class="mwb_hub_content">
			<h3>How do I use this addon or what are the dependencies for this?</h3>
			<p>Before using this addon, please make sure that you are ready for the setup of one of our HubSpot WooCommerce Integration plugin either PRO version or basic version( over wordpress.org ).</p>
		</div>

		<div class="mwb_hub_content">
			<h3>Whats the flow of this addon?</h3>
			<p>Admin has to map HubSpot Contact Properties with WordPress users fields. The mapped properties will be synced during the batch update over HubSpot by backend scheduler.</p>
		</div>

		<div class="mwb_hub_content">
			<h3>What things or points I should make sure while mapping the properties?</h3>
			<p>As the properties have their own field type and any mismatch in the options values can stop the syncing of users data to HubSpot. Please make sure that the properties you are mapping to users fields, will have relevant values to the options of Contact properties on your HubSpot account.</p>
		</div>
		<div class="mwb_abandoned_btn_wrap text-center mwb_faq-btn-color">
			<a class="button" href="https://makewebbetter.com/contact-us/">Contact Us</a>[mwb_product_trail_buynow]
		</div>
	</div>
</section>		

<section class="section section-pd-80 meeting-section">
	<div class="template-row">
		<div class="template-col"><a href="https://calendly.com/umakantsharma/15min" target="_blank" rel="noopener"><img class="aligncenter size-full wp-image-9917" src="https://makewebbetter.com/wp-content/uploads/2018/03/mautic-umakant-full.png" alt="hubspot field to field sync calendly" width="1000" height="300" /></a></div>
	</div>
</section>
</div>
</div>



</div>
<?php
}
}
get_footer();
?>