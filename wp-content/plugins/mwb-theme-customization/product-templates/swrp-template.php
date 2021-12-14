<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
do_action( 'woocommerce_shop_loop' );
$codecanyon_link = get_post_meta($postid, 'mwb_codcanyon_product_link', true);
$mwb_codecanyon_id = get_post_meta($postid, 'mwb_codecanyon_product_id', true);
?>
<!--==================================
=            swrp-wrapper            =
===================================-->

<div class="mwb_swrp_main_wrapper">
	<section id="page-header" class="page-header hubspot-woo-integration mwb_swrp_header" role="banner">
		<div class="wrap">
			<div class="mwb_hub_start_content hubspot-woo-integration__wrapper">
				<h1>Smart WooCommerce Recommendation Product </h1>
				<h4>Recommendation is helping...your customers</h4>
				<div class="hubspot-woo-integration__content">
					<p>Why bother more in keeping forward ‘out of the box’ </p>
					<p>Make it happen with </p>
					<p>Smart WooCommerce Recommendation Product </p>
				</div>

				<div class="hubspot-woo-integration__button">
					<form class="cart"  action="https://codecanyon.net/cart/add/<?php echo $mwb_codecanyon_id;?>" accept-charset="UTF-8" method="post" id="mwb_codecanyon_swrp_template_form">
					  <input name="utf8" type="hidden" value="✓">
					  <input type="hidden" value="regular" name="license">  
					  <input type="hidden" name="support" value="bundle_6month">
					  <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="button product_type_simple mwb_products_2_hero-section__btn mwb-swrp-button">Purchase Now</button>
					</form>

				</div>
			</div>
		</div>
	</section>
	<div id="genesis-content" class="mwb_woocommerce_template">


<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		the_content();
		//
		// Post Content here
		//
		comments_template();
	} // end while
} // end if
?>




</div>


<?php
get_footer();
?>