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
	}
}
get_footer();
?>