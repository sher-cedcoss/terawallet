<?php
// Get header.
get_header();
global $wpdb, $post, $product;
$postid = $post->ID;

do_action('woocommerce_shop_loop');

$header_content = get_post_meta($postid, 'mwb_custom_header_content', true);
$header_css = get_post_meta($postid, 'mwb_template_wrapper_css', true);

$checkouturl = wc_get_checkout_url();
$mwb_youtube_video_link = get_post_meta($postid, 'mwb_youtube_video_link', true);
$mwb_plugin_documentation = get_post_meta($postid, 'mwb_plugin_documentation', true);
$mwb_plugin_front_end_demo = get_post_meta($postid, 'mwb_plugin_front_end_demo', true);
$mwb_plugin_download_count = get_post_meta($postid, 'mwb_plugin_download_count', true);

if (is_object($product)) {

	// $product = wc_get_product($post->ID);
	if ($product->is_type('variable')) {
		$children_ids = $product->get_children();
		$product_ids = $children_ids;
		$variation_id = $children_ids[0];
		$product_ids[] = $postid;

		$product_id = $variation_id;
	} else {
		$product_id = $postid;
		$product_ids = array($product->id);
	}

	$average_rating = $product->get_average_rating();
	$review_count = $product->get_review_count();
	$rating_count = $product->get_rating_count();
}
$query = "SELECT SUM( download_count ) AS count
                FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions
                WHERE product_id IN (" . implode(',', $product_ids) . ")";
$download_count = $wpdb->get_var($query);


?>
<section id="page-header" class="page-header <?php echo $header_css; ?>" role="banner">
	<div class="wrap">
		<div class="mwb_hub_start_content hubspot-woo-integration__wrapper">

			<div class="mwb-product-wrap">
				<div class="mwb_container">
					<div class="mwb-product-wrap__header text-left">
						<div class="clearfix">
							<div class="mwb-product-wrap__grid1">
								<h1><?php the_title(); ?></h1>
								<p><?php echo $header_content; ?></p>
								<?php
								if ($product_id == 27746) {
								?>
									<a href="https://woocommerce.com/cart/?add-to-cart=5079983&licence_type=single" target="_blank" rel="noopener noreferrer nofollow" class="button  mwb_btn_fill">Buy Now</a>
								<?php
								} else {
								?>
									<a href="<?php echo $checkouturl; ?>?add-to-cart=<?php echo $product_id; ?>" rel="noopener noreferrer nofollow" class="button mwb_btn_fill">Buy Now</a>
								<?php
								}

								if (isset($mwb_plugin_front_end_demo) && !empty($mwb_plugin_front_end_demo)) {
								?>
									<a href="<?php echo $mwb_plugin_front_end_demo; ?>" target="_blank" class="button  mwb_btn_fill">Live Demo</a>
								<?php
								} elseif (isset($mwb_youtube_video_link) && !empty($mwb_youtube_video_link)) {
								?>
									<a class="mwb-popup-youtube mwb_btn_fill" href="<?php echo $mwb_youtube_video_link; ?>" frameborder="0" autoplay="1" allowfullscreen="" ?iframe="true"><i class="fas fa-play-circle"></i> Watch Video</a>
								<?php
								}

								if (isset($mwb_plugin_documentation) && !empty($mwb_plugin_documentation)) {
								?>
									<a href="<?php echo $mwb_plugin_documentation; ?>" target="_blank"><i class="far fa-file-alt"></i> Documentation</a>
								<?php
								}

								if (isset($mwb_plugin_download_count) && !empty($mwb_plugin_download_count)) {
									$download_text = $mwb_plugin_download_count;
								} else {
									if (isset($download_count) && !empty($download_count)) {
										if ($download_count >= 100) {
											$download_text = $download_count;
										} else {
											$download_text = '150+';
										}
									} else {
										$download_text = '150+';
									}
								}
								?>
								<div class="mwb-col-6 no-space"><i class="fas fa-cloud-download-alt"></i> <?php echo $download_text; ?> Downloads</div>
								<div class="mwb-col-6 no-space"><i class="fab fa-osi"></i> 100% Open Source</div>
							</div>
							<div class="mwb-product-wrap__grid2 mwb-product-wrap__grid-center">
								<div class="mwb-product-wrap__payment clearfix">
									<h3>We are featured in:</h3>
									<div><i class="fas fa-check"></i> Trustpilot</div>
									<div><i class="fas fa-check"></i> G2Crowd</div>
									<div><i class="fas fa-check"></i> Capterra</div>
									<div><i class="fas fa-check"></i> GoodFirms</div>
									<div><i class="fas fa-check"></i> Software Suggest</div>
									<div><i class="fas fa-check"></i> DesignRush</div>
								</div>
								<!--<div class="mwb-product-wrap__offers">-->
								<!--                            <div class="mwb-product-wrap__offers-outer">-->
								<!--                                <div class="mwb-product-wrap__curve"></div>-->
								<!--                                <div class="mwb-product-wrap__offer">-->
								<!--                                    <div class="mwb-product__offer-condition" >Flat</div>-->
								<!--                                    <span>15</span>-->
								<!--                                    <div class="mwb-product-wrap__offer-amt">-->
								<!--                                        <span>%</span>-->
								<!--                                        <span>OFF*</span>-->
								<!--                                    </div>-->
								<!--                                </div>-->
								<!--                                <div class="mwb-product-wrap__coupon">-->
								<!--                                    <span>Easter Weekend</span>-->
								<!--                                    <span>SALE OFFER</span>-->
								<!--                                </div>-->
								<!--                            </div>-->
								<!--                        </div>-->
								<div class="mwb-product-wrap__security">
									<div><img src="https://makewebbetter.com/wp-content/uploads/2019/09/dmca.png" alt="DMCA security"></div>
									<div><img src="https://makewebbetter.com/wp-content/uploads/2019/09/gdpr-compliance.png" alt="GDPR Compliance"></div>
								</div>
								<div class="mwb-product-wrap__money-back">
									<img src="https://makewebbetter.com/wp-content/uploads/2019/09/30_days_moneyback_gaurantee.png" alt="30 day money back guarantee">
									<div class="mwb-product-wrap__money-back-text">
										<span>buyer protection</span>
										<p>30 day money back guarantee</p>
										<p style="text-transform: capitalize;">Additional <a href="https://makewebbetter.com/refund-policy/" target="_blank" rel="noopener noreferrer">Refund Policy</a></p>
									</div>
								</div>
							</div>
							<div class="mwb-product-wrap__grid3">
								<div class="mwb-product-wrap__subscription">
									<?php
									if ($product_id == 27746) {
									?>
										<h4><span class="mwb-product-wrap__check-icon"><i class="fas fa-check-circle"></i></span></h4>
									<?php
									} else {
									?>
										<h4>Select Your plan</h4>
									<?php
									}
									?>
									<div class="mwb-product-wrap__subscription-details">
										<?php
										if (isset($average_rating) && !empty($average_rating)) {
										?>

											<div class="woocommerce-product-rating">
												<?php echo wc_get_rating_html($average_rating, $rating_count); // WPCS: XSS ok.
												?>
												<?php if (comments_open()) : ?>
													<?php //phpcs:disable
													?>
													(<?php printf(_n('%s review', '%s reviews', $review_count, 'woocommerce'), '<span class="count">' . esc_html($review_count) . '</span>'); ?>)
													<?php // phpcs:enable
													?>
												<?php endif ?>
											</div>

											<!--<p class="text-right"><i class="fas fa-star"></i> <?php //echo $average_rating;
																									?> out of 5 Rating</p>-->
										<?php
										}

										if ($product_id == 27746) {
										?>
											<div class="mwb-price-section">$49.00</div>
										<?php
										}
										?>
										<form method="post" action="" enctype="multipart/form-data" id="mwb_variations_form">
											<?php
											if ($product_id !== 27746) {
											?>
												<div class="mwb-product-wrap__subscription-check">
													<?php
													if ($product->is_type('variable')) {
														$i = 0;
														foreach ($children_ids as $children_id) {
															$variation = wc_get_product($children_id);

															$attribute = $variation->get_attributes();


															if ($i == 0) {
																$slug = 'single-site';
																$name = '1 Site License';
															}
															if ($i == 1) {
																$slug = '5-site';
																$name = '5 Site License';
															}
															if ($i == 2) {
																if ($attribute['license-type'] == '25 site') {
																	$slug = '25-site';
																	$name = '25 Site License';
																}
																if ($attribute['license-type'] == '10 site') {
																	$slug = '10-site';
																	$name = '10 Site License';
																}
															}

													?>
															<div <?php if ($variation->is_on_sale() && $i == 2) {
																		echo 'class="mwb-sale-offer-strip-notification"';
																	}  ?>>
																<?php
																if ($variation->is_on_sale()) {
																	$pro_price = $variation->get_price();
																	$sale_price = $variation->get_sale_price();
																	$regular_price = $variation->get_regular_price();
																	$offer_price = $regular_price - $sale_price;

																	if ($i == 2) {
																?>
																		<p class="mwb-product-wrap__offer-notice">Most Valued Plan - save $<?php echo $offer_price; ?></p>
																<?php
																	}
																}
																?>
																<input type="radio" name="variation_id" value="<?php echo $children_id; ?>" id="<?php echo $slug; ?>" <?php if ($i == 0) {
																																											echo 'checked';
																																										} ?>><label for="<?php echo $slug; ?>"></label><span><?php echo $attribute['license-type']; ?></span> <span class="mwb_new_layout_price_html"><?php echo $variation->get_price_html(); ?> <?php if ($variation->is_on_sale()) { ?><span class="mwb_offer_save_price_msg">(Save $<?php echo $offer_price; ?>)</span><?php  } ?> </span>
															</div>
													<?php
															$i++;
														}
													}
													?>
												</div>
											<?php
											}
											?>
											<div class="mwb-product-wrap__points">
												<ul>
													<li>-No Recurring Payments</li>
													<li>-24x7 Customer Care</li>
													<li>-1 Year Free Support</li>

												</ul>
												<?php
												if ($product_id !== 27746) {
												?>
													<div><img src="https://makewebbetter.com/wp-content/uploads/2019/09/sectigo_trust_seal_sm.png" alt="Sectigo Trust seal">
													</div>
												<?php
												}
												?>
											</div>
											<?php
											if ($product_id == 27746) {
											?>
												<div class="mwb-product-wrap__buy">
													<button style="width:100%;" class="button mwb_add_to_cart mwb_btn_fill mwb_variation_btn_form_submit" data-location="https://woocommerce.com/cart/?add-to-cart=5079983&licence_type=single">Buy @ WooCommerce</button>
												</div>
											<?php
											} else {
											?>
												<div class="mwb-product-wrap__buy">
													<input type="hidden" class="mwb_parent_prod_id" name="product_id" value="<?php echo $postid; ?>"><input type="hidden" class="mwb_quantity" name="quantity" value="1"><input type="hidden" class="add-to-cart" name="add-to-cart" value="<?php echo $postid; ?>"><button class="button mwb_add_to_cart mwb_btn_outline mwb_variation_btn_form_submit" data-location="https://makewebbetter.com/cart/">Add to cart</button><button class="button mwb_add_to_cart mwb_btn_fill mwb_variation_btn_form_submit" data-location="https://makewebbetter.com/checkout/">Buy Now</button>
												</div>
											<?php
											}
											?>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<div id="genesis-content" class="mwb_woocommerce_template">
	<div class="mwb-product-wrap">

		<?php the_content(); ?>

		<div class="mwb-product-wrap__contact-wrap">
			<div class="mwb-product-wrap__contact flex-box">
				<div class="mwb-product-wrap__contact-img">
					<img src="https://makewebbetter.com/wp-content/uploads/2019/08/contact-img.png" alt="Contact Us">
				</div>
				<div class="mwb-product-wrap__contact-content">
					<div class="mwb-product-wrap__contact-cta">
						<h3 class="text-white">Any Confusions? Don't Scratch your heads. Let us help you put together pieces of the puzzle.</h3>
						<a href="https://makewebbetter.com/contact-us/" target="_blank" class="mwb_btn_shadow" rel="noopener noreferrer">Contact Us</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php //comments_template();
	?>
</div>

<?php
get_footer();
?>