<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
do_action( 'woocommerce_shop_loop' );
?>
<section id="page-header" class="page-header page-template-mautic" role="banner">
	<div class="wrap">
		<div class="mwb_hub_start_content hubspot-woo-integration__wrapper">
			<h2>Connect</h2>
			<h1>WooCommerce &amp; Mautic</h1>
			<h4>Automate Your Marketing</h4>
			<div class="hubspot-woo-integration__content">
				<p>Increase Online <strong><u>Sales</u></strong></p>
				<p>Personalize and <strong><u>Automate</u></strong> Emails</p>
				<p><strong><u>Convert</u></strong> Abandoned Cart to Sales</p>
				<p>Convert <strong><u>Delight and Repeat</u></strong> with Dynamic Coupons</p>
			</div>
			<div class="hubspot-woo-integration__button">
				<a class="button" href="https://docs.makewebbetter.com/mautic-woocommerce-marketing-automation/">Documentation</a>
				<a href="/checkout/?add-to-cart=9192" class="button">Purchase Now</a>
			</div>
		</div>
	</div>
</section>
<div id="genesis-content" class="mwb_woocommerce_template">
<?php
// if ( have_posts() ) {
// 	while ( have_posts() ) {
// 		the_post(); 
		the_content();
// 		//
// 		// Post Content here
// 		//
		comments_template();
// 	} // end while
// } // end if
?>







</div>
<?php
get_footer();
?>