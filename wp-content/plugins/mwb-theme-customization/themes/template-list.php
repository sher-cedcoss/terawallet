<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
?>
<section id="page-header" class="page-header" role="banner">
	<div class="wrap">
		<h1 class="archive-title">
			<?php the_title(); ?>
		</h1>
		<p itemprop="description"><?php the_excerpt(); ?></p>
	</div>
	<!-- <div class="page-header_images">
		<ul class="page-header_images-list">
			<li><img src="<?php //echo MWB_URL; ?>/assets/images/template-banner/1.jpg" alt="" width="" height="" /></li>
			<li><img src="<?php //echo MWB_URL; ?>/assets/images/template-banner/2.jpg" class="img-responsive" alt="banner-images" /></li>
			<li><img src="<?php //echo MWB_URL; ?>/assets/images/template-banner/3.jpg" class="img-responsive" alt="banner-images" /></li>
		</ul>
	</div> -->
</section>
<div class="wrap mwbwrap-banner">
	<main class="content" id="genesis-content">

		<article class="post-<?php echo $postid;?> page type-page status-publish entry" itemscope="" itemtype="https://schema.org/CreativeWork">
			<div class="entry-content" itemprop="text">
				<div id="pl-<?php echo $postid;?>" class="panel-layout">

					<?php
					$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
					$args = array(
						'post_type' => 'product',
						'posts_per_page' => '12',
						'paged' => $paged,
						'tax_query' => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => 'template',
							),
						),
					);
					$wp_query = new WP_Query( $args );

					if ( $wp_query->have_posts() ) 
					{
						?>
						<div id="pg-<?php echo $postid;?>-0" class="panel-grid panel-no-style mwbmicroservices">
							<?php
							while ( $wp_query->have_posts() ) 
							{
								$wp_query->the_post();

			// global $post;
								$product_id = get_the_ID();
								$post_thumbnail_id = get_post_thumbnail_id( $product_id );
								$image = wp_get_attachment_image_src($post_thumbnail_id, "full", false);
								if ( $image ) {
									list($src, $width, $height) = $image;
									$product_image = $src;
								}else{
									$product_image = wc_placeholder_img_src();
								}
								$product = new WC_Product($product_id);
								$product_price = $product->get_price();
								?>
								<div id="pgc-<?php echo $postid;?>-0-0" class="panel-grid-cell mwb_microservice_list_wrapper">
									<div id="panel-<?php echo $postid;?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
										<div class="so-widget-sow-editor so-widget-sow-editor-base">
											<div class="siteorigin-widget-tinymce textwidget">
												<a href="<?php echo get_the_permalink()?>">




													<img src="<?php echo $product_image; ?>" width="" height="" alt="" />

													<h2 class="mwbviewservicelisttitle"><?php echo get_the_title();?></h2>
													<div class="mwbviewservicelistprice">

														<?php 
														echo '<span class="woocommerce-Price-amount amount">'.wc_price($product_price).'</span>';

														?>
														<button class="mwbviewserviceview">View Details</button>
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						$big = 999999999; // need an unlikely integer
						echo '<nav class="woocommerce-pagination">';
						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'prev_text' => __('&larr;'),
							'next_text' => __('&rarr;'),
							'type'         => 'list',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $wp_query->max_num_pages
						) );
						echo '</nav>';
						wp_reset_postdata();
						?>
					</div>
					<?php
				} 
				else 
				{
					?>
					<div class="mwb-empty">
						<h2>No Products Found</h2>
					</div>	
					<?php
				}
				?>
			</div>
		</div>
	</article>
</main>
</div>
</div>
<?php
// Get footer.
get_footer(); ?>