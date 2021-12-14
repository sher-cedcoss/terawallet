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
							?>
							<a class="button" href="<?php echo $checkouturl ; ?>?add-to-cart=<?php echo $product_id ; ?>">Purchase Now</a>
							<a href="<?php echo $mwb_plugin_front_end_demo; ?>" class="button">Live Demo</a>
							<?php
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


			<section class="mwb-product-compatibility" id="woocommerce-one-click-checkout">
	<div class="template-container">
		<div class="template-row">
			<div class="template-col text-center">

				<div class="mwb_products_2_sideBarInfo mwb_section"><div class="mwb_products_2_quickInfo"><div class="mwb_products_2_quickInfo_content"><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Compatible With </span>WooCommerce 3.3.3, Wordpress 4.9.4</p></div><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Minimum PHP version</span> 5.4</p></div></div><div class="mwb_products_2_quickInfo_content"><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Version</span> 1.0.0</p></div><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Compatible Browsers</span> IE9, IE10, IE11, Firefox, Safari, Opera, Chrome, Edge</p></div></div><div class="mwb_products_2_quickInfo_content"><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Files Included</span> JavaScript JS, CSS, PHP</p></div><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Released</span> Apr 13, 2018</p></div></div><div class="mwb_products_2_quickInfo_content"><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Translation ready</span> Yes</p></div><div class="mwb_products_2_quickInfo_img"> <i class="fa fa-check"></i></div><div class="mwb_products_2_quickInfo_title"><p><span class="mwb_product_bold">Languages</span> English</p></div></div></div></div></div></div></div>
				</section>
		<div class="hubspot-woocommerce-integration mwb_site_products_features woocommerce-one-click-checkout">
			<div class="template-wrapper">
				<section id="hubspot-features" class="section section-pd-80 feature-section">
					<div class="template-row">
						<div class="template-col">
							<h2 class="section-heading text-center">Featured Packed and Essential Attributes</h2>
						</div>
						<div class="flex-box">
							<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
								<h3 class="section-heading-md">Move your customers to the checkout page directly</h3>
								<p>WooCommerce One Click Checkout provides a feature that your customer can move directly to checkout page rather than follow the conventional online store process of placing an order.</p>
							</div>
							<div class="template-col feature-section__col feature-section__col--image feature-section__col--right">
								<img class="alignnone wp-image-10727 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/09/one_click1.jpg" alt="repositioning of the fields options" width="1000" height="648" />
							</div>
						</div>
					</div>
					<div class="template-row">
						<div class="flex-box">
							<div class="template-col feature-section__col feature-section__col--image feature-section__col--right">
								<img class="alignnone wp-image-10728 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/09/one_click2.jpg" alt="add multiple fields in shipping and billing form" width="1043" height="466" />
							</div>
							<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
								<h3 class="section-heading-md">Reduces Your Cart Abandonment</h3>
								<p>Don’t miss out on some of the easiest ways to reduces your cart abandoned, generate revenue and increase profitability for your online business.</p>
							</div>
						</div>
					</div>
					<div class="template-row">
						<div class="flex-box">
							<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
								<h3 class="section-heading-md">Easy And Customizable Settings</h3>
								<p>WooCommerce One Click Checkout comes with easy settings and customizable feature which works according to the admin requirements.</p>
							</div>
							<div class="template-col feature-section__col feature-section__col--image feature-section__col--right"><img class="alignnone wp-image-10730 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/09/one_click3.jpg" alt="easy enable or disable button for different checkout fields" width="1000" height="700" /></div>
						</div>
					</div>
					<div class="template-row">
						<div class="flex-box">
							<div class="template-col feature-section__col feature-section__col--image feature-section__col--right"><img class="alignnone wp-image-10729 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/09/one_click4.jpg" width="1000" height="700" alt="Modify the label’s name of billing and shipping fields" /></div>
							<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
								<h3 class="section-heading-md">Increase your E-Store profit</h3>
								<p>Have you lost your potential customers due to large process for placing an order, Use Woocommerce One Click Checkout that will empower your online store and increase your profit day by day.</p>
							</div>
						</div>
					</div>
					<div class="template-row">
						<div class="flex-box">
							<div class="template-col feature-section__col feature-section__col--content feature-section__col--left">
								<h3 class="section-heading-md">Show Buy Now Button as per your need</h3>
								<p>Want to show "Buy Now" button on specific products, do not worry Woocommerce One Click Checkout provides a feature that admin can able to place "Buy Now" button on specific products.</p>
							</div>
							<div class="template-col feature-section__col feature-section__col--image feature-section__col--right">
								<img class="alignnone wp-image-10727 size-full" src="https://makewebbetter.com/wp-content/uploads/2018/09/one_click5.jpg" alt="Different templates For checkout page" width="1000" height="648" />
							</div>
						</div>
					</div>
				</section>


				<section id="hubspot-sales-section" class="section section-pd-80 feature-section bg-gradient">
					<div class="template-container">
						<div class="template-row">
							<div class="template-col text-center">
								<h3 class="section-heading">Additionally, Our Core Features includes.</h3>
							</div>
							<div class="template-col">
								<div class="mwb_hubspot-overview-cards">
									<div class="mwb_hubspot-overview-cards-heading mwb_products_add">Focus on leveraging the time, the customers is spending on your online store.</div>

								</div>
								<div class="mwb_hubspot-overview-cards">
									<div class="mwb_hubspot-overview-cards-heading mwb_products_add">Reduce Cart Abandonment.</div>

								</div>
								<div class="mwb_hubspot-overview-cards">
									<div class="mwb_hubspot-overview-cards-heading mwb_products_add">Admin can exclude Products for Remove “BUY NOW” Button.</div>

								</div>
								<div class="mwb_hubspot-overview-cards">
									<div class="mwb_hubspot-overview-cards-heading mwb_products_add">Fully Customizable from Admin end.</div>

								</div>
								<div class="mwb_hubspot-overview-cards">
									<div class="mwb_hubspot-overview-cards-heading mwb_products_add">Works with all woocommerce compatible themes.</div>

								</div>
								<div class="mwb_hubspot-overview-cards">
									<div class="mwb_hubspot-overview-cards-heading mwb_products_add">Reduced number of Steps while placing the order.</div>

								</div>
							</div>
						</div>
					</div>
				</section>
				<section id="hubspot-partners" class="section section-pd-80 feature-section">
					<div class="template-container">
						<div class="template-row">
							<div class="template-col text-center">
								<h3 class="section-heading text-center">Wrapping up while picking WooCommerce One Click Checkout</h3>
							</div>
							<div class="template-col">
								<p><strong>WooCommerce Checkout Field Editor PRO </strong> provides an advanced solution for to possess back shopping carts which are going abandoned. WooCommerce checkout field editor helps to reduce the default checkout page present in the checkout form both for billing and shipping form. Well, it was quite troublesome to fill each and every field in the process and that’s why it stood an important point when your customers choose to leave their cart in between and abandoned.</p>
								</div>
								<div class="mwb-add-prevent-wrapper" id="woocommerce-one-click-checkout"><a class="button" href="https://makewebbetter.com/checkout/?add-to-cart=7662">Buy Now</a></div>
							</div>
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