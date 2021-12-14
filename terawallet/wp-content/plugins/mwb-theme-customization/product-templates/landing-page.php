<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

<?php
// Get header.
get_header();
global $wpdb, $post;
$postid = $post->ID;
$product = wc_get_product($postid);

do_action('woocommerce_shop_loop');

$header_content = get_post_meta($postid, 'mwb_custom_header_content', true);
$header_css = get_post_meta($postid, 'mwb_template_wrapper_css', true);

$checkouturl = wc_get_checkout_url();
$mwb_youtube_video_link = get_post_meta($postid, 'mwb_youtube_video_link', true);
$mwb_plugin_documentation = get_post_meta($postid, 'mwb_plugin_documentation', true);
$mwb_plugin_front_end_demo = get_post_meta($postid, 'mwb_plugin_front_end_demo', true);
$mwb_plugin_download_count = get_post_meta($postid, 'mwb_plugin_download_count', true);

// 4 top points
$point_1 = get_post_meta($postid, 'point_1', true);
$point_2 = get_post_meta($postid, 'point_2', true);
$point_3 = get_post_meta($postid, 'point_3', true);
$point_4 = get_post_meta($postid, 'point_4', true);


$prod_type = (wc_get_product($postid)->get_type());

if ($prod_type == "variable") {
	$children_ids = $product->get_children();
	$product_ids = $children_ids;
	$variation_id = $children_ids[0];
	$product_ids[] = $postid;

	$product_id = $variation_id;
} else {
	$product_id = $postid;
	$product_ids = array(wc_get_product($product_id));
}
$average_rating = $product->get_average_rating();
$review_count = $product->get_review_count();
$rating_count = $product->get_rating_count();

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
					<div class="mwb-product-woo__header-wrap">
						<div class="mwb-product-woo__header-text">
							<div class="mwb-product-woo__header-title">
								<h1><?php the_title(); ?></h1>
							</div>
							<div class="mwb-product-woo__header-rating-wrap">
								<div class="mwb-product-woo__header-rating-stars">
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
								</div>
								<span>4.8</span>
								<span>(18 reviews)</span>
								<div class="mwb-product-woo__header-rating-down">
									<i class="fas fa-sort-down"></i>
								</div>
								<div class="mwb-product-woo__header-rating-details-wrap">
									<div class="mwb-product-woo__header-rating-details">
										<div class="mwb-product-woo__header-rating">
											<p>5 stars</p>
											<div class="bar-container">
												<div class="bar-5" style="width: 60%;"></div>
											</div>
											<span>12</span>
										</div>
										<div class="mwb-product-woo__header-rating">
											<p>4 stars</p>
											<div class="bar-container">
												<div class="bar-4" style="width: 60%;"></div>
											</div>
											<span>5</span>
										</div>
										<div class="mwb-product-woo__header-rating">
											<p>3 stars</p>
											<div class="bar-container">
												<div class="bar-3" style="width: 60%;"></div>
											</div>
											<span>2</span>
										</div>
										<div class="mwb-product-woo__header-rating">
											<p>2 stars</p>
											<div class="bar-container">
												<div class="bar-2" style="width: 60%;"></div>
											</div>
											<span>1</span>
										</div>
										<div class="mwb-product-woo__header-rating">
											<p>1 stars</p>
											<div class="bar-container">
												<div class="bar-1" style="width: 60%;"></div>
											</div>
											<span>4</span>
										</div>
									</div>
									<div class="mwb-product-woo__header-all-rating">
										<a href="#" target="_blank">
											<p>All reviews</p>
											<i class="fa fa-angle-double-right"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="mwb-product-woo__header-para">
								<p><?php echo $header_content; ?></p>
							</div>
							<div class="mwb-product-woo__header-compatibility-benifits">
								<ul>
									<li><i class="fas fa-check-circle"></i><?php echo $point_1; ?></li>
									<li><i class="fas fa-check-circle"></i><?php echo $point_2; ?></li>
									<li><i class="fas fa-check-circle"></i><?php echo $point_3; ?></li>
									<li><i class="fas fa-check-circle"></i><?php echo $point_4; ?></li>
								</ul>
							</div>
							<div class="mwb-product-woo__header-cta">
								<a href="https://docs.makewebbetter.com/woocommerce-rma-return-refund-exchange/?utm_source=mwb-site&amp;utm_medium=doc-cta&amp;utm_campaign=rma-page" target="_blank"><i class="fas fa-play-circle"></i> Watch Video</a>
								<a href="https://demo.makewebbetter.com/woocommerce-rma-for-return-refund-and-exchange/?utm_source=mwb-site&amp;utm_medium=demo-cta&amp;utm_campaign=rma-page" target="_blank"><i class="fas fa-play"></i> Live Demo</a>
								<a href="https://docs.makewebbetter.com/woocommerce-rma-return-refund-exchange/?utm_source=mwb-site&amp;utm_medium=doc-cta&amp;utm_campaign=rma-page" target="_blank"><i class="far fa-file-alt"></i> Documentation</a>
							</div>
						</div>
						<div class="mwb-product-woo__header-plan">
							<span>Most Valued</span>
							<form method="post" action="" enctype="multipart/form-data" id="mwb_variations_form">
								<div class="mwb-product-woo__plan-tab">
									<ul>
										<?php
										if ($prod_type == "variable") {
											$i = 0;
											foreach ($children_ids as $children_id) {
												$variation = wc_get_product($children_id);
												$attribute = $variation->get_attributes();
												if ($i == 0) {
													$label = "1 site plan";
													$slug = '10-site';
													$name = '1 Site License';
												}
												if ($i == 1) {
													$label = "5 site plan";
													$slug = '5-site';
													$name = '5 Site License';
												}
												if ($i == 2) {
													$label = "10 site plan";
													$slug = 'single-site';
													$name = '10 Site License';
												}
										?>


												<li class="<?php if ($i == 0) echo "active"; ?>"><a class="vardata" data-var="<?php echo $children_id ?>" href="#tabs-<?php echo $i + 1 ?>"><?php echo $label; ?></a></li>

										<?php
												$i++;
											}
										}
										?>
									</ul>
								</div>
								<div class="mwb-product-woo__plan-wrap">
									<div class="mwb-product-woo__plan">
										<div class="mwb-product-woo__rate-wrap">
											<?php

											$i = 0;
											foreach ($children_ids as $children_id) {
												$variation = wc_get_product($children_id);

												$attribute = $variation->get_attributes();

												$sale_price = $variation->get_sale_price();
												$regular_price = $variation->get_regular_price();
												$offer_price = $regular_price - $sale_price;
											?>
												<div class="mwb-product-woo__rate" id="tabs-<?php echo $i + 1 ?>" style="<?php if ($i != 0) echo "display: none;" ?>">
													<span><?php echo $sale_price;
															?></span>
													<span><?php echo $regular_price;
															?></span>
													<span><?php echo $offer_price; ?></span>
													<span style=" color: red;"><?php if ($i == 1) echo "Most Valued" ?></span>
												</div>
											<?php
												$i++;
											}
											?>
										</div>

										<div class="mwb-product-woo__rate-cta">
											<div class="mwb-product-wrap__buy">


												<input type="hidden" name="variation_id" value="<?php echo $children_ids[0] ?>" id="var_id"> <!-- variation id -->
												<input type="hidden" class="mwb_parent_prod_id" name="product_id" value="<?php echo $postid; ?>"><input type="hidden" class="mwb_quantity" name="quantity" value="1"><input type="hidden" class="add-to-cart" name="add-to-cart" value="<?php echo $postid; ?>"><button class="button mwb_add_to_cart mwb_btn_fill mwb_variation_btn_form_submit" data-location="http://localhost/terawallet/checkout/">Buy Now</button>
											</div>
										</div>
							</form>
							<div class="mwb-product-woo__accordian-main-wrapper" id="mwb-product-woo__accordian-main-wrapper">
								<div class="mwb-product-woo__accordion-wrapper">
									<a class="mwb-product-woo__accordian-heading" href="#">Whatâs Included<i class="fas fa-sort-down"></i></a>
									<div class="mwb-product-woo__accordion-content" style="display: none;">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris, turpis eu pharetra, risus, nisl.</p>
										<div class="mwb-product-woo__accordion-content-cta">
											<a href="#" target="_blank"> Link 1</a>
											<a href="#" target="_blank"> Link 1</a>
											<a href="#" target="_blank"> Link 1</a>
										</div>
									</div>
								</div>
								<div class="mwb-product-woo__accordion-wrapper">
									<a class="mwb-product-woo__accordian-heading" href="#">Support<i class="fas fa-sort-down"></i></a>
									<div class="mwb-product-woo__accordion-content" style="display: none;">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris, turpis eu pharetra, risus, nisl.</p>
									</div>
								</div>
								<div class="mwb-product-woo__accordion-wrapper">
									<a class="mwb-product-woo__accordian-heading" href="#">Details and Compatibility<i class="fas fa-sort-down"></i></a>
									<div class="mwb-product-woo__accordion-content" style="display: none;">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris, turpis eu pharetra, risus, nisl.</p>
									</div>
								</div>
							</div>
							<div class="mwb-product-woo__guarantee-policy">
								<div class="mwb-product-woo__guarantee-policy-img">
									<img src="https://staging.makewebbetter.com/wp-content/uploads/2021/02/shield.png" alt="shield chevron">
								</div>
								<span>30 DAY MONEY BACK GUARANTEE</span>
								<div class="mwb-product-woo__refund-policy">
									<a href="https://makewebbetter.com/refund-policy/" target="_blank"> refund policy</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mwb-product-woo__featured-wrap">
				<div class="mwb-product-woo__featured-title">
					<h5>Featured In</h5>
				</div>
				<div class="mwb-product-woo__featured-brands">
					<div class="mwb-product-woo__featured-brands-img">
						<img src="https://staging.makewebbetter.com/wp-content/uploads/2021/02/brand-1.png" alt="brand-1">
					</div>
					<div class="mwb-product-woo__featured-brands-img">
						<img src="https://staging.makewebbetter.com/wp-content/uploads/2021/02/brand-2.png" alt="brand-2">
					</div>
					<div class="mwb-product-woo__featured-brands-img">
						<img src="https://staging.makewebbetter.com/wp-content/uploads/2021/02/brand-3.png" alt="brand-3">
					</div>
					<div class="mwb-product-woo__featured-brands-img">
						<img src="https://staging.makewebbetter.com/wp-content/uploads/2021/02/brand-4.png" alt="brand-4">
					</div>
					<div class="mwb-product-woo__featured-brands-img">
						<img src="https://staging.makewebbetter.com/wp-content/uploads/2021/02/brand-5.png" alt="brand-5">
					</div>
					<div class="mwb-product-woo__featured-brands-img">
						<img src="https://staging.makewebbetter.com/wp-content/uploads/2021/02/brand-6.png" alt="brand-6">
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