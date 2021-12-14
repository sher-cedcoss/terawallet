<?php 
if(!defined('ABSPATH')){
	exit;
}
get_header();
global $post;
$postid = $post->ID;

do_action( 'woocommerce_shop_loop' );
?>
<div class="mwb_products_wrapper">
<!--=====================================
=            	HAEDER-SECTION            =
======================================-->
<?php

if ( have_posts() )
{ 
	while (have_posts())
	{
		the_post(); 
		
		$product_id = get_the_ID();
		$checkouturl = wc_get_checkout_url();
		$post_thumbnail_id = get_post_thumbnail_id( $product_id );
		$imageurl = wp_get_attachment_image_url( $post_thumbnail_id, "full" );
		$codecanyon_link = get_post_meta($product_id, 'mwb_codcanyon_product_link', true);
		$mwb_codecanyon_id = get_post_meta($product_id, 'mwb_codecanyon_product_id', true);
		?>
		<div class="mwb_products_2_hero-section mwb_products_2_text-center">
			<div class="mwb_products_2_hero-section__wrapper">
				<h1><?php the_title(); ?></h1>
				<div class="mwb_products_icons mwb_icon-color">
					<?php
					if(isset($codecanyon_link) && !empty($codecanyon_link))
					{
				 		echo do_shortcode('[get_review_from_codecanyon item_id="'.$mwb_codecanyon_id.'"]');
			 		}
			 		else
		 			{
		 				echo do_shortcode('[mwb_product_rating]');
	 				}
	 				?>
				</div><p><?php the_excerpt(); ?></p><p class="mwb_products_2_price"><span><?php echo do_shortcode('[mwb_product_price]'); ?></span></p><a href="#mwb_product_features" class="btn btn-prmary mwb_products_2_hero-section__btn">Features</a>
				<?php
				if(isset($codecanyon_link) && !empty($codecanyon_link))
				{
				?>
					<form class="cart"  action="https://codecanyon.net/cart/add/<?php echo $mwb_codecanyon_id;?>" accept-charset="UTF-8" method="post" id="mwb_codecanyon_product_template_form">
					  <input name="utf8" type="hidden" value="✓">
					  <input type="hidden" value="regular" name="license">  
					  <input type="hidden" name="support" value="bundle_6month">
					  <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="button product_type_simple mwb_products_2_hero-section__btn">Buy Now</button>
					</form>
				<?php
				}
				else
				{
				?>
					<a href="<?php echo $checkouturl ; ?>?add-to-cart=<?php echo $product_id ; ?>" class="btn btn-prmary mwb_products_2_hero-section__btn">Get It Now</a>
					
					<?php
				} ?>

			</div>
			<?php
			$mwb_youtube_video_link = get_post_meta($product_id, 'mwb_youtube_video_link', true);
			?>
			<div class="mwb_products_2_hero-section_vedio">
				<iframe width="750" height="400" src="<?php echo $mwb_youtube_video_link ; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
			</div>
		</div>		
	</div>

	<!--====  End of HEADER-SECTION  ====-->

<!--========================================
=            QUICK INFO-SECTION            =
=========================================-->

<div class="mwb_products_2_sideBarInfo mwb_section">
	<div class="mwb_products_2_quickInfo">
	<?php
	$mwb_compatible_with=get_post_meta($product_id , 'mwb_compatible_with',true);
	$mwb_compatible_browsers=get_post_meta($product_id , 'mwb_compatible_browsers',true);
	$mwb_files_included=get_post_meta($product_id , 'mwb_files_included',true);
	$mwb_minimum_php_version=get_post_meta($product_id , 'mwb_minimum_php_version',true);
	$mwb_version=get_post_meta($product_id , 'mwb_version',true);
	$mwb_released=get_post_meta($product_id , 'mwb_released',true);
	$mwb_translation_ready=get_post_meta($product_id , 'mwb_translation_ready',true);
	$mwb_languages=get_post_meta($product_id , 'mwb_languages',true);

	?>
		<div class="mwb_products_2_quickInfo_content">
			<?php 
			if(isset($mwb_compatible_with) && !empty($mwb_compatible_with))
			{
		    ?>		
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">	
				<p><span class="mwb_product_bold">Compatible With </span><?php echo $mwb_compatible_with; ?></p> 
			</div>
			<?php
			}
			if(isset($mwb_minimum_php_version) && !empty($mwb_minimum_php_version))
			{
				?>
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">	
				<p><span class="mwb_product_bold">Minimum PHP version</span> <?php echo $mwb_minimum_php_version; ?></p> 
			</div>
			<?php
			}
			?>
			
		</div>

		<div class="mwb_products_2_quickInfo_content">
			<?php
			
			if(isset($mwb_compatible_browsers) && !empty($mwb_compatible_browsers))
			{
			?>
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">	
				<p><span class="mwb_product_bold">Compatible Browsers</span> <?php echo $mwb_compatible_browsers; ?></p> 
			</div>
			<?php
			}
			if(isset($mwb_version) && !empty($mwb_version))
			{
				?>
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">	
				<p><span class="mwb_product_bold">Version</span> <?php echo $mwb_version; ?></p>
			</div>
			<?php
			}
			?>
		</div>
		<div class="mwb_products_2_quickInfo_content">
			<?php
			if(isset($mwb_files_included) && !empty($mwb_files_included))
			{
			?>
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">	
				<p><span class="mwb_product_bold">Files Included</span> <?php echo $mwb_files_included; ?></p> 
			</div>
			<?php
			}
			if(isset($mwb_released) && !empty($mwb_released))
			{
			?>
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">
				<p><span class="mwb_product_bold">Released</span> <?php
				 echo date("M d, Y", strtotime($mwb_released)); ?></p> 		 
			</div>
			<?php
			}
			?>
		</div>
		<div class="mwb_products_2_quickInfo_content">
			<?php
			if(isset( $mwb_translation_ready) && !empty($mwb_translation_ready))
			{
			?>
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">	
				<p><span class="mwb_product_bold">Translation ready</span> <?php echo $mwb_translation_ready; ?></p> 
			</div>
			<?php
			}
			if(isset($mwb_languages) && !empty($mwb_languages))
			{
			?>
			<div class="mwb_products_2_quickInfo_img">
				<i class="fa fa-check"></i>
			</div>
			<div class="mwb_products_2_quickInfo_title">	
				<p><span class="mwb_product_bold">Languages</span> <?php echo $mwb_languages; ?> </p> 
			</div>
			<?php
			}
			?>
		</div>	
	</div>
</div>

<!--====  End of QUICK-INFO-SECTION  ====-->

<!--=====================================
=           DESCRIPTION-SECTION          =
======================================-->					
<?php the_content(); ?>

<!--====  End of DESCRIPTION-SECTION  ====-->



<!--====================================
=            REVIEW-SECTION            =
=====================================-->
<?php 
$comments = get_approved_comments( get_the_ID() ); 
if($comments){
	?>
	<div class="mwb_products_reviews mwb_section" id="reviews">
		<h4 class="mwb_section_heading">REVIEW</h4>
		<?php foreach($comments as $comment){ 
			// print_r($comment); 
			$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
			?>
			<div class="review">
				<h5><?php echo esc_html( $comment->comment_author ) ; ?></h5>
				<?php if($rating > 0){ ?>
				<div class="mwb_icon-color">
					<?php 
					echo '<div class="star-rating"><span style="width:' . ( $rating * 20 ) . '%">' . sprintf( __( '%s out of 5', 'woocommerce' ), $rating ) . '</span></div>'; 
					?>
				</div>
				<?php } ?>
				<p><?php echo strlen(wp_kses_data( $comment->comment_content ))>200 ?substr(wp_kses_data( $comment->comment_content ),0,200).'...' : wp_kses_data( $comment->comment_content ); ?></p>
			</div>
			<?php } ?>

		</div>
		<?php
	}
	?>
<!--====  End of REVIEW-SECTION  ====-->

<!--=====================================
=            LEAVE A REPLY SECTION            =
======================================-->
	<div class="mwb_products_reviews_comment_form mwb_section">
		<?php 
		if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
			$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . '</label><select name="rating" id="rating" aria-required="true" required>
			<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
			<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
			<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
			<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
			<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
			<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
		</select></div>';
	}
	$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea></p>';

	comment_form( $comment_form ); ?>
</div>
<!--====  End of LEAVE A REPLY SECTION  ====-->
<?php 
}
}
?>
</div>
<?php 
get_footer();
?>