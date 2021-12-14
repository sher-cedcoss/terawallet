<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
do_action( 'woocommerce_shop_loop' );
?>
<section id="page-header" class="page-header hubspot-woo-integration mwb_icontact_banner" role="banner">
	<div class="wrap">
		<div class="mwb_hub_start_content hubspot-woo-integration__wrapper">
			<h2>Connect</h2>
			<h1>WooCommerce & iContact Pro</h1>
			<h4>Automate Your Marketing</h4>
			<div class="hubspot-woo-integration__content">
				<p>Increase Online <strong><u>Sales</u></strong></p>
				<p>Create <strong><u>Segments</u></strong> with <strong><u>Criteria</u></strong></p>
				<p><strong><u>Convert</u></strong> Abandoned Cart to Sales</p>
			</div>
			<div class="hubspot-woo-integration__button">
				<a class="button" href="https://docs.makewebbetter.com/icontact-pro-woocommerce-integration-pro/">Documentation</a>
				<a href="#hubspot-pricing" class="button">Pricing</a>
			</div>
		</div>
	</div>
</section>
<div id="genesis-content" class="mwb_woocommerce_template">
	
	
		<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		?>
		<div class="mwb_icontact_content_wrapper">
		<?php 
		the_content();
		?>
		</div>
		<?php
			comments_template();
	} // end while
} // end if
	?>

	

</div>
<?php
get_footer();
?>