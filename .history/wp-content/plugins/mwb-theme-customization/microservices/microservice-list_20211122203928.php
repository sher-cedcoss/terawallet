<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
	'post_type' => 'product',
	'posts_per_page' => 12,
	'paged' => $paged,
	'tax_query' => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => 'services',
		),
	),
);
$microserviceslug = "";
$turnaroundtime = "";
$min_price = "";
$max_price = "";
$rate = "";
if (isset($_GET['submit'])) {
	if (isset($_GET['category']) && !empty($_GET['category'])) {
		$microserviceslug = $_GET['category'];
		$args['tax_query'][] = array(
			'taxonomy' => 'microservice',
			'field'    => 'slug',
			'terms'    => $microserviceslug,
		);
	}

	if (isset($_GET['time']) && !empty($_GET['time'])) {
		$args['meta_query']['relation'] = "AND";
		$turnaroundtime = $_GET['time'];
		$args['meta_query'][] = array(
			'key' => 'mwb_turnaround_time',
			'value'    => $turnaroundtime,
			'compare'    => "=",
		);
	}

	if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
		$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
		$max_price = $_GET['max_price'];
		$args['meta_query'][] = array(
			'key' => 'ew_hourly_rate',
			'value'    => array($min_price, $max_price),
			'type'    => 'numeric',
			'compare'    => "BETWEEN",
		);
	}

	if (isset($_GET['rate']) && !empty($_GET['rate'])) {
		$rate = $_GET['rate'];
		$args['meta_query'][] = array(
			'key' => 'ew_is_hourly',
			'value'    => $rate,
			'compare'    => "=",
		);
	}
}
get_header();

global $post;
$postid = $post->ID;
?>
<!-- <div class="site-inner"> -->
<section id="page-header" class="page-header" role="banner">
	<div class="wrap">
		<h1 class="archive-title"><?php the_title(); ?></h1>
		<p itemprop="description"><?php the_excerpt(); ?></p>
	</div>
</section>
<div class="wrap mwbwrap-banner">
	<main class="content" id="genesis-content">

		<article class="post-<?php echo $postid; ?> page type-page status-publish entry" itemscope="" itemtype="https://schema.org/CreativeWork">
			<div class="entry-content" itemprop="text">
				<div id="pl-<?php echo $postid; ?>" class="panel-layout">
					<form action="">
						<div id="pg-<?php echo $postid; ?>-1" class="panel-grid panel-no-style mwbmicroservicesfilter">

							<div id="pgc-<?php echo $postid; ?>-0-0" class="panel-grid-cell">
								<div id="panel-<?php echo $postid; ?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
									<div class="so-widget-sow-editor so-widget-sow-editor-base">
										<div class="siteorigin-widget-tinymce textwidget">
											<p style="text-align:center;">Service Type</p>
											<select id="filter_timeduration" class="select" name="rate">
												<option <?php echo $rate == '' ? "selected='selected'" : ""; ?> value=""> - ALL - </option>
												<option <?php echo $rate == 'no' ? "selected='selected'" : ""; ?> value="no">Fixed Rate</option>
												<option <?php echo $rate == 'yes' ? "selected='selected'" : ""; ?> value="yes">Hourly Rate</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div id="pgc-<?php echo $postid; ?>-0-0" class="panel-grid-cell">
								<div id="panel-<?php echo $postid; ?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
									<div class="so-widget-sow-editor so-widget-sow-editor-base">
										<div class="siteorigin-widget-tinymce textwidget">
											<p style="text-align:center;">Category</p>
											<select id="microservice_cat" name="category">
												<?php
												$terms = get_terms(array(
													'taxonomy' => 'microservice',
													'hide_empty' => true
												));
												if (isset($terms) && !empty($terms)) {
												?>
													<option value=""> - ALL - </option>
													<?php

													foreach ($terms as $key => $term) {
														$selected = "";
														if ($microserviceslug == $term->slug) {
															$selected = "selected='selected'";
														}
													?>
														<option <?php echo $selected; ?> value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
												<?php
													}
												}
												?>
											</select>

										</div>
									</div>
								</div>
							</div>
							<div id="pgc-<?php echo $postid; ?>-0-0" class="panel-grid-cell">
								<div id="panel-<?php echo $postid; ?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
									<div class="so-widget-sow-editor so-widget-sow-editor-base">
										<div class="siteorigin-widget-tinymce textwidget">
											<div class="mwb_price_filter" id="mwb_min_price">
												<p style="text-align:center;">Min Price ($)</p>
												<input id="price_filter_min" maxlength="4" name="min_price" value="<?php echo $min_price; ?>" placeholder="20" size="8" type="number" min="0">
											</div>
											<div class="mwb_price_filter" id="mwb_max_price">
												<p style="text-align:center;">Max Price ($)</p>
												<input id="price_filter_max" maxlength="4" name="max_price" value="<?php echo $max_price; ?>" placeholder="2000" size="8" type="number" min="10">
											</div>


										</div>
									</div>
								</div>
							</div>
							<div id="pgc-<?php echo $postid; ?>-0-0" class="panel-grid-cell">
								<div id="panel-<?php echo $postid; ?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
									<div class="so-widget-sow-editor so-widget-sow-editor-base">
										<div class="siteorigin-widget-tinymce textwidget">
											<p>Turnaround</p>

											<select id="filter_turnaround_time" class="select" name="time">
												<option <?php echo $turnaroundtime == '' ? "selected='selected'" : ""; ?> value=""> - ALL - </option>
												<option <?php echo $turnaroundtime == '1' ? "selected='selected'" : ""; ?> value="1">1 day or less</option>
												<option <?php echo $turnaroundtime == '2' ? "selected='selected'" : ""; ?> value="2">2 days or less</option>
												<option <?php echo $turnaroundtime == '3' ? "selected='selected'" : ""; ?> value="3">3 days or less</option>
												<option <?php echo $turnaroundtime == '4' ? "selected='selected'" : ""; ?> value="4">4 days or less</option>
												<option <?php echo $turnaroundtime == '5' ? "selected='selected'" : ""; ?> value="5">5 days or less</option>
												<option <?php echo $turnaroundtime == '6' ? "selected='selected'" : ""; ?> value="6">6 days or less</option>
												<option <?php echo $turnaroundtime == '7' ? "selected='selected'" : ""; ?> value="7">7 days or less</option>
												<option <?php echo $turnaroundtime == '8' ? "selected='selected'" : ""; ?> value="8">8 days or less</option>
												<option <?php echo $turnaroundtime == '9' ? "selected='selected'" : ""; ?> value="9">9 days or less</option>
												<option <?php echo $turnaroundtime == '10' ? "selected='selected'" : ""; ?> value="10">10 days or less</option>
												<option <?php echo $turnaroundtime == '11' ? "selected='selected'" : ""; ?> value="11">11 days or less</option>
												<option <?php echo $turnaroundtime == '12' ? "selected='selected'" : ""; ?> value="12">12 days or less</option>
												<option <?php echo $turnaroundtime == '13' ? "selected='selected'" : ""; ?> value="13">13 days or less</option>
												<option <?php echo $turnaroundtime == '14' ? "selected='selected'" : ""; ?> value="14">14 days or less</option>
											</select>

											<input type="submit" class="filter_price_submit" name="submit" value="FILTER"></span></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<?php
					$i = 0;

					$query = new WP_Query($args);
					if ($query->have_posts()) {
					?>
						<div id="pg-<?php echo $postid; ?>-0" class="panel-grid panel-no-style mwbmicroservices">
							<?php
							while ($query->have_posts()) {
								$query->the_post();

							?>
								<div id="pgc-<?php echo $postid; ?>-0-0" class="panel-grid-cell mwb_microservice_list_wrapper">
									<div id="panel-<?php echo $postid; ?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
										<div class="so-widget-sow-editor so-widget-sow-editor-base">
											<div class="siteorigin-widget-tinymce textwidget">
												<a href="<?php echo get_the_permalink() ?>">
													<?php
													$serviceid = get_the_ID();

													$ishourly = get_post_meta($serviceid, 'ew_is_hourly', true);
													$hourly_rate = get_post_meta($serviceid, 'ew_hourly_rate', true);
													$turnaround_time = get_post_meta($serviceid, 'mwb_turnaround_time', true);
													$serviceicon = get_post_meta($serviceid, "mwb_service_icon", true);

													$serviceimages = wp_get_attachment_image_src($serviceicon, 'full');
													if (isset($serviceimages[0])) {
														$serviceimage = $serviceimages[0];
													}
													?>
													<?php
													if (isset($serviceimage) && !empty($serviceimage)) {
													?>
														<img src="<?php echo $serviceimage; ?>" class="mwb_microservice_icon" alt="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>">
													<?php
													}
													?>
													<h2 class="mwbviewservicelisttitle">
														<?php echo get_the_title(); ?>
													</h2>
													<div class="mwbviewservicelistprice">
														<?php
														$product = new WC_Product($serviceid);
														if ($ishourly == "yes") {
															echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>' . number_format($hourly_rate, 2) . ' / Hour</span>';
														} else {
															echo $product->get_price_html();
														}
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
							echo paginate_links(array(
								'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
								'format' => '?paged=%#%',
								'prev_text' => __('&larr;'),
								'next_text' => __('&rarr;'),
								'type'         => 'list',
								'current' => max(1, get_query_var('paged')),
								'total' => $query->max_num_pages
							));
							echo '</nav>';
							wp_reset_postdata();
							?>
						</div>
					<?php
					} else {
					?>
						<div class="mwb-empty">
							<h2>No Services found on your search criteria</h2>

						</div>
						<div class="mwb-related">
							<p>You can also choose services from our featured services</p>

							<?php
							$args = array(
								'post_type' => 'product',
								'posts_per_page' => 4,
								'paged' => $paged,
								'orderby' => 'rand',
								'tax_query' => array(
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'slug',
										'terms'    => 'services',
									),
								),
							);
							$i = 0;

							$query = new WP_Query($args);
							if ($query->have_posts()) {
							?>
								<div id="pg-<?php echo $postid; ?>-0" class="panel-grid panel-no-style ">
									<?php
									while ($query->have_posts()) {
										$query->the_post();

									?>

										<div id="pgc-<?php echo $postid; ?>-0-0" class="panel-grid-cell mwb_microservice_list_wrapper">
											<div id="panel-<?php echo $postid; ?>-0-0-0" class="so-panel widget widget_sow-editor panel-first-child panel-last-child" data-index="0">
												<div class="so-widget-sow-editor so-widget-sow-editor-base">
													<div class="siteorigin-widget-tinymce textwidget">
														<a href="<?php echo get_the_permalink() ?>">
															<?php
															$serviceid = get_the_ID();

															$ishourly = get_post_meta($serviceid, 'ew_is_hourly', true);
															$hourly_rate = get_post_meta($serviceid, 'ew_hourly_rate', true);
															$turnaround_time = get_post_meta($serviceid, 'mwb_turnaround_time', true);
															$serviceicon = get_post_meta($serviceid, "mwb_service_icon", true);

															$serviceimages = wp_get_attachment_image_src($serviceicon, 'full');
															if (isset($serviceimages[0])) {
																$serviceimage = $serviceimages[0];
															}
															?>
															<?php
															if (isset($serviceimage) && !empty($serviceimage)) {
															?>
																<img src="<?php echo $serviceimage; ?>" class="mwb_microservice_icon" alt="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>">
															<?php
															}
															?>
															<h2 class="mwbviewservicelisttitle">
																<?php echo get_the_title(); ?>
															</h2>
															<div class="mwbviewservicelistprice">
																<?php
																$product = new WC_Product($serviceid);
																if ($ishourly == "yes") {
																	echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>' . number_format($hourly_rate, 2) . ' / Hour</span>';
																} else {
																	echo $product->get_price_html();
																}
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

									wp_reset_postdata();
									?>
								</div>
							<?php
							} ?>
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
get_footer();
?>