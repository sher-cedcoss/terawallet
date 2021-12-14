<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
do_action( 'woocommerce_shop_loop' );
?>
<?php
$product_id = get_the_ID();
$checkouturl = wc_get_checkout_url();
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		?>
		<section id="page-header" class="page-header hubspot-woo-integration mwb_dokan_product_template mwb_dokan_common" role="banner">
			<div class="wrap">
				<div class="mwb_hub_start_content hubspot-woo-integration__wrapper">
					<h2>Fully Automated Refund & Exchange process is here! </h2>
					<h1><?php the_title(); ?></h1>
					<h4>Allow the vendor of the product to handle refund and exchange requests</h4>
					<div class="hubspot-woo-integration__content">
						<p>Streamline process between the admin-vendor-customer </p>
						
						<!-- <p>Make happy Refund and Exchange time with automation</p> -->
						<span class="mwb_price_wrap mwb_dokan_price_wrap">
							<?php echo do_shortcode('[mwb_product_price]'); ?>
							<span class="mwb-per-year-text"> / Year</span>
						</span>
					</div>
					<div class="hubspot-woo-integration__button">
						<a class="mwb-dokan-button mwb-dokan-button_color" target="_blank" href="https://wedevs.com/dokan/">Get Dokan</a>
						<a href="<?php echo $checkouturl ; ?>?add-to-cart=<?php echo $product_id ; ?>" class="mwb-dokan-button">Purchase Now</a>
					</div>
					<div class="">
						<p class="mwb_dokan_partner">Official
							<img src="https://makewebbetter.com/wp-content/uploads/2018/09/docken-3.png" alt=""><span> Partner</span>
						</p>
					</div>
				</div>
			</div>
		</section>


		<div id="genesis-content" class="mwb_woocommerce_template">
			<?php


the_content();

// Post Content here

comments_template();

	?>				

</div>
<?php
}
}
get_footer();
?>