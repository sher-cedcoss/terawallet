<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;
?>
<section id="page-header" class="page-header" role="banner"><div class="wrap"><h1 class="archive-title"><?php the_title(); ?></h1><p itemprop="description"><?php the_excerpt(); ?></p></div></section>
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
					'orderby' => 'meta_value_num',
					'meta_key' => '_price',
					'order' => 'asc',
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => 'theme',
						),
					),
				);
				$the_query = new WP_Query( $args );

				if ( $the_query->have_posts() ) 
				{
					?>
					<div id="pg-<?php echo $postid;?>-0" class="panel-grid panel-no-style mwb-wp-layout-wrapper">
					<?php
					while ( $the_query->have_posts() ) 
					{
						$the_query->the_post();

						global $post;
						$product_id = get_the_ID();
						$post_thumbnail_id = get_post_thumbnail_id( $post );
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
						<div id="pgc-<?php echo $postid;?>-0-0" class="panel-grid-cell mwb-wp-layout-wrapper-div">
							<div id="panel-<?php echo $postid;?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
								<div class="so-widget-sow-editor so-widget-sow-editor-base">
									<div class="siteorigin-widget-tinymce textwidget clearfix mwb-wp-layout-wrapper-col">
										<div class="mwb-wp-layout__img">
											<a href="<?php echo get_the_permalink()?>">
											<img src="<?php echo $product_image; ?>" width="" height="" alt="" />
											</a>
										</div>
										<div class="mwb-wp-layout__content">
											<h2 class="mwb-wp-layout__title"><a href="<?php echo get_the_permalink()?>"><?php echo get_the_title();?></a></h2><p class="mwb-wp-layout__description"><?php echo get_the_excerpt(); ?></p>
											<div class="mwb-wp-layout__category-price clearfix">
												<div class="mwb-wp-layout__category">
												<?php
												$terms = get_the_terms( $product_id, 'theme_category' );
												$separator = ' , ';
												if (!empty($terms))
												{
													?>
													<?php
													$output = "";
												    foreach ($terms as $term)
												    {
												    	
												        $output .= $term->name . $separator;
												        
												    }
												    $output = trim($output, $separator);
												    ?>
													<span>Category: <?php echo $output; ?></span>
												    <?php
												}
												?>
												
												</div>
												<div class="mwb-wp-layout__price">
													<?php
													if($product_price == 0){
														echo '<span class="mwb_price_free"><strong>FREE</strong></span>';
													}
													else
													{
														echo '<span class="woocommerce-Price-amount amount">'.wc_price($product_price).'</span>';
													}
													?>

												</div>
											</div>
											<div class="mwb-wp-layout__details">
											<?php 
											$mwb_theme_feature_content = get_post_meta($product_id, 'mwb_theme_feature_content' , true); 
											echo $mwb_theme_feature_content;
											?>

											</div>
											<div class="mwb-wp-layout__button">
												<a href="<?php echo get_the_permalink()?>" class="button mwb-wp-layout__button-details">View Details</a>
												<?php $mwb_demo_preview = get_post_meta($product_id, 'mwb_plugin_front_end_demo' , true); ?>
												<a href="<?php echo $mwb_demo_preview; ?>" class="button mwb-wp-layout__button-preview" target="_blank">Preview</a>
												<a href="<?php echo home_url(); ?>/cart/?add-to-cart=<?php echo $product_id; ?>" class="button mwb-wp-layout__button-cart">
												<?php
												if($product_price == 0){
													echo '<i class="fa fa-download"></i>';
												}
												else
												{
													echo '<i class="fa fa-shopping-cart"></i>';
												}
												?></a>
											</div>
										</div>
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
						    'total' => $the_query->max_num_pages
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