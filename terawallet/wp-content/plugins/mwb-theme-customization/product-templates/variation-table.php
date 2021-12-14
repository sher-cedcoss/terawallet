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
									<h3 class="section-heading-md">Forecast Your Sales and Revenue</h3>
									<p>Hubspot provide simple forcasting about sales and revenue. Easy prediction of sale can be accomplished by using hubspot, you can perform a forecast that if you offer particular discount to specific customers what will be the outcome and how much profit will you gain from it.</p>
								</div>
								<div class="template-col feature-section__col feature-section__col--image feature-section__col--right"><a href="javascript:void(0);"><br />
									<img class="alignnone wp-image-10731 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/10/Forecast-Your-Sales-and-Revenue-1.jpg" alt="hubspot deal per order" width="1166" height="983" /><br />
								</a></div>
							</div>
						</div>
						<div class="template-row">
							<div class="flex-box">
								<div class="template-col feature-section__col feature-section__col--image feature-section__col--right"><a href="javascript:void(0);"><br />
									<img class="alignnone wp-image-10728 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/10/Proper-tracking-of-Orders-1.png" alt="hubspot deal per order" width="1043" height="466" /><br />
								</a></div>
								<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
									<h3 class="section-heading-md">Proper tracking of Orders</h3>
									<p>Using hubspot  proper tracking of orders become easy, you can easily track on which date the abandoned product is purchased, the date of shipping and the date of delievery, hubspot provide you simple ROI tracking of orders.</p>
								</div>
							</div>
						</div>
						<div class="template-row">
							<div class="flex-box">
								<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
									<h3 class="section-heading-md">Assign Tasks for your unpaid orders</h3>
									<p>It becomes necessary to send emails to the customers who yet not paid for the product, you can send them remainder that the payment of product you have purchased from our site is still pending. Hubspot will make the list of this type of customers and send them this email message.</p>
								</div>
								<div class="template-col feature-section__col feature-section__col--image feature-section__col--right"><img class="alignnone wp-image-10730 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/10/Assign-Tasks-for-your-unpaid-orders-1.jpg" alt="hubspot deal per order" width="1000" height="700" /></div>
							</div>
						</div>
						
					</section>



					<section class="mwb_hub_review_section mwb_hubspot_common_section">
						<div class="mwb_hub_review_wrap">
							<div class="mwb_hub_review">
								<h2 class="section-heading text-center">What People say</h2>
								<img src="https://makewebbetter.com/wp-content/uploads/2018/10/03.jpg" class="hubspot deal per order user review" alt="user-img">
								<h6>Jorgen Davidson</h6>
								<div class="mwb_hub_star_review">
									<span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt=""></span>
									<span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt=""></span>
									<span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt=""></span>
									<span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt=""></span>
									<span><img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/assets/images/star-lg.png" alt=""></span>
								</div>
								<h3>Some of the best customer service I've ever received!</h3>
								<p>These folks are all over it! Every question I had while I was determining whether I wanted the WooCommerce to HubSpot pro plugin they had an answer for. They were extremely responsive on chat and helped me me thoroughly through the entire installation and setup process. The product looks great so far. I would definitely recommend working with them.</p>

								<div class="mwb_review_redirect_section">

									<a target="_blank" href='https://www.capterra.com/reviews/179620/HubSpot-WooCommerce-Integration-PRO?utm_source=vendor&utm_medium=badge&utm_campaign=capterra_reviews_badge'><img border='0' src='https://assets.capterra.com/badge/12488b742415d32da70c34ac3ab82012.png?v=2125271&p=179620' /></a><script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script><div class="mwb_trustpilot_wrap">
									<h2>We are rated Excellent</h2>
									<div class="trustpilot-widget" data-locale="en-US" data-template-id="5419b6a8b0d04a076446a9ad" data-businessunit-id="5b9fbc0023057e0001acfbdd" data-style-height="60px" data-style-width="100%" data-theme="light"><a href="https://www.trustpilot.com/review/makewebbetter.com" target="_blank">Trustpilot</a></div></div>
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
												<p class="title">lifetime</p>
											</div>
											<ul class="check-list">
												<li class="check-list-item">Convert Shop orders as hubSpot Deals</li>
												<li class="check-list-item">Track overall revenue and Sales generated from your store</li>
												<li class="check-list-item"><b>24 * 7</b> Phone , Email & Skype support</li>
												<li class="check-list-item">Regular updates</li>
											</ul>
											<p><a class="button" href="https://makewebbetter.com/checkout/?add-to-cart=10878">Get Add-on</a></p>
										</div>

									</div>
								</div>
							</div>
						</div>
					</section>

					<!--=================================
					=            faq-section            =
					==================================-->

					<section class="mwb_hub_faq_section mwb_hubspot_common_section">
						<div class="mwb_hub_faq_content_wrap">
							<h2 class="section-heading text-center">Frequently asked questions</h2>
							<div class="mwb_hub_content">
								<h3 class="mwb-mautic-question"> Does this add-on creates any Sales Pipeline on HubSpot?</h3>
								<p>Yes, the add-on will create a pipeline named “WooCommerce Sales Pipeline” over HubSpot.</p>
							</div>
							<div class="mwb_hub_content">
								<h3 class="mwb-mautic-question">Does this add-on creates any new custom groups or properties on HubSpot ?</h3>
								<p>Yes, the add-on will create 2 groups and few properties for HubSpot Deals.</p>
							</div>
							<div class="mwb_hub_content">
								<h3 class="mwb-mautic-question">How do I use this addon or what are the dependencies for this?</h3>
								<p>Before using this addon, please make sure that you are ready for the setup of our HubSpot WooCommerce Integration plugin either PRO version .</p>
							</div>
							<div class="mwb_hub_content">
								<h3 class="mwb-mautic-question"> Can I export old orders as HubSpot deals?</h3>
								<p>Yes , you can export your old orders to HubSpot as deals in a single click.</p>
							</div>
									<div class="mwb_abandoned_btn_wrap text-center mwb_faq-btn-color">
								<a class="button" href="https://makewebbetter.com/contact-us/">Contact Us</a><?php echo do_shortcode('[mwb_product_trail_buynow]'); ?>
							</div>
						</div>
					</section>
					
					
					<!--====  End of faq-section  ====-->
					

					<section class="section section-pd-80 meeting-section">
						<div class="template-row">
							<div class="template-col"><a href="https://calendly.com/umakantsharma/15min" target="_blank" rel="noopener"><img class="aligncenter size-full wp-image-9917" src="https://makewebbetter.com/wp-content/uploads/2018/03/mautic-umakant-full.png" alt="mautic" width="1000" height="300" /></a></div>
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