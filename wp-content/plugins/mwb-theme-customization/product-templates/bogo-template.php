<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
do_action( 'woocommerce_shop_loop' );
?>
<!--=====================================
=            HEADER SECTION            =
======================================-->
<?php
$product_id = get_the_ID();
$checkouturl = wc_get_checkout_url();
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		?>
<section id="mwb-bogo-page-header" class="mwb-bogo-page-header" role="banner">
	<div class="mwb-bogo-wrap">
		<div class="mwb-bogo-start-content mwb-bogo-wrapper">
			<h1><?php the_title(); ?></h1>
			<h4>Official WPML Compatible</h4>
			<div class="mwb-bogo-integration-content">
				<p><strong><u>Reconstruct</u></strong> your client base</p>
				<p> Create a <strong><u>buzz</u></strong>  in the market </p>
				<p class="mwb-bogo-price"><?php echo do_shortcode('[mwb_product_price]'); ?></p>
			</div>
			<div class="mwb-bogo-integration-button">
				<a class="bogo-button" href="<?php echo $checkouturl ; ?>?add-to-cart=<?php echo $product_id ; ?>">Purchase Now</a>
			</div>
		</div>
	</div>
</section>

<!--====  End of HEADER SECTION  ====-->

<div id="genesis-content" class="mwb_woocommerce_template">

<?php
		the_content();
		//
		// Post Content here
		//
		comments_template();
		?>

</div>

<?php
} // end while
} // end if
get_footer();
?>