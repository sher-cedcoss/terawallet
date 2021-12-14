<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;

$header_content = get_post_meta($postid, 'mwb_custom_header_content', true);
$header_css = get_post_meta($postid, 'mwb_template_wrapper_css', true);

do_action( 'woocommerce_shop_loop' );
?>
<section id="page-header" class="page-header <?php echo $header_css;?>" role="banner">
	<div class="wrap">
		<div class="mwb_hub_start_content hubspot-woo-integration__wrapper">
			<?php echo $header_content;?>
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