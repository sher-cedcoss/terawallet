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
<?php //the_content(); ?>
<div class="mwb_products_description-wrapper mwb_section">
	<h4 class="mwb_section_heading">What is WooCommerce Points and Rewards User Notification Add-On?</h4>
	<p>Usually, merchants find it difficult to increase the customer engagement rates at their eCommerce stores, consequently it leads to decrease in the overall sales, revenue and business development.</p>
<p>It helps  all those merchants and users to recognize rewards and adding points who have not experienced the benefits of points and rewards on their product purchase.</p>
<p><b>Note:</b> The Admin needs to install the main plugin so as to be able to work with all features of its addon. Without having WooCommerce Points and Rewards plugin, the notifications addon features will not work as per requirements.</p>
<p>WooCommerce Points and Rewards addon notifies both <b>login user</b> as well as <b>guest users</b> about how they can earn more points, <b>how much points they have already earned and how they can redeem it</b>. There are other options through which this addon will notify your login users and guest users about their gain. This is achieved by writing a review on some product or service, referring user to someone or simply earning points on behalf of somebody else’s purchase.</p>
	<!-- New benifit-section -->
</div>
<div class="mwb_products_2_function-section bg-white-color pointsreward-gain-section">
	<h4 class="mwb_section_heading mwb_products_2_benifit-heading">Gain Above</h4>
	<div class="mwb_products_2_function-section__community mwb_products_2_function-section__function1">
		<h5>Real time Updation On every step</h5>
		<p>Both Login and guest users will get notifications for their collected points very easily and get to know,how they can accelerate earning and redeeming.</p>
	</div>
	<div class="mwb_products_2_function-section__documentation mwb_products_2_function-section__function1">
		<h5>Enhance Client Base</h5>
		<p>WooCommerce points and rewards add-on will help merchants to enhance client base, and strengthen his client strata with more points, rewards, earning and redeeming excels.</p>
	</div>
	<div class="mwb_products_2_function-section__advanced-optios mwb_products_2_function-section__function1">
		<h5>Easy Process to Earn Points for Rewards</h5>
		<p>It comes up with quick and easy process to earn and redeem rewards which helps the users to satisfy their huge number of customers in a very short time.</p>
	</div>
	<div class="mwb_products_2_function-section__security mwb_products_2_function-section__function1">
		<h5>Foremost place for everything</h5>
		<p>It helps the users to recognize the value of redeem points for both guest and logged in users at a one place with common settings for both.</p>
	</div>
	<div class="mwb_products_2_btn"><a id="mwb-button3" class="btn btn-prmary mwb_products_2_hero-section__btn" href="https://demo.makewebbetter.com/woocommerce-points-and-rewards-user-notification-add-on">live demo</a>
		<a id="mwb-button2" class="btn btn-prmary mwb_products_2_hero-section__btn" href="https://docs.makewebbetter.com/woocommerce-points-reward-user-notification-addon/">documentation</a></div>
	</div>
	<div class="mwb_products_lastIcon mlr-15">
		<h4 class="mwb_section_heading">Grab WooCommerce Points and Rewards User Notification Add-On ​ Now​​ !!</h4>
		<div class="mwb_products_byNow"><a id="mwb-button2" class="btn btn-prmary mwb_products_2_hero-section__btn" href="https://makewebbetter.com/checkout/?add-to-cart=9320">BUY NOW</a></div>
	</div><section class="mwb_section mwb-points-rewards-features mlr-15" id="mwb_product_features">
	<div class="template-container">
		<div class="template-row">
			<div class="template-col text-center">
				<h3 class="mwb_section_heading">Package of Features and Benefits</h3>
			</div>
			<div class="template-col">
				<div class="mwb_hubspot-overview-cards">
					<div class="mwb_hubspot-overview-cards-heading">Instant Notification to Customers</div>
					<p class="mwb_hubspot-overview-cards-description">This feature of addon lets the customers at ecommerce store to know about points and rewards functionality enabled on the site via pop-ups. It therefore helps merchants to get more closer to their customers and nurture them further.</p>
				</div>
				
				<div class="mwb_hubspot-overview-cards">
					<div class="mwb_hubspot-overview-cards-heading">Effective Guidance to Earn More Points</div>
					<p class="mwb_hubspot-overview-cards-description">Notification addon of Points & rewards plugin guides the merchants about all ultimate and crucial steps required to be taken to earn more reward points quickly and magnify overall sales growth. It guides both guests as well as logged in users.</p>
				</div>
				<div class="mwb_hubspot-overview-cards">
					<div class="mwb_hubspot-overview-cards-heading">Real Time Notification For Collected Points</div>
					<p class="mwb_hubspot-overview-cards-description">This addon features helps the users to get immense confidence each second and that is what reward notifications fills them with. And also notifies both guest users and logged in users about <b>Gain Points and Redeem Points</b>.</p>
				</div>
				<div class="mwb_hubspot-overview-cards">
					<div class="mwb_hubspot-overview-cards-heading">Get Gain Points</div>
					<p class="mwb_hubspot-overview-cards-description">It helps the users to get loyalty reward points from guests users. The notifications occurs at sign up, referrals, spending money and reviews all made by the customers. This feature makes it a best referral WooCommerce Plugin.</p>
				</div>
				<div class="mwb_hubspot-overview-cards">
					<div class="mwb_hubspot-overview-cards-heading">Get Redeem Points</div>
					<p class="mwb_hubspot-overview-cards-description">This feature of points & rewards notification addon allows the merchants to make redeem points settings for purchase of some particular products, on Cart Subtotal and on converting into coupons.</p>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="mwb_products_lastIcon mlr-15">
	<h4 class="mwb_section_heading">This is not an end!!! Additionally it has ......</h4>
	<div class="mwb_products_amazing-features mwb_products-points-rewards-features">
		<ul>
			<li><i class="fa fa-check"></i>Compatible with WooCommerce</li>
			<li><i class="fa fa-check"></i>Assures instant notifications at every move.</li>
		</ul>
	</div>
	<div class="mwb_products_amazing-features mwb_products-points-rewards-features">
		<ul>
			<li><i class="fa fa-check"></i>Easy and quick admin Setting</li>
			<li><i class="fa fa-check"></i>One Step solution to get full guidance.</li>
		</ul>
	</div>
	<div class="mwb_products_amazing-features mwb_products-points-rewards-features">
		<ul>
			<li><i class="fa fa-check"></i>Useful for both Guests and Logged In users.</li>
			<li><i class="fa fa-check"></i>Enhances referrals and strengthen client base.</li>
		</ul>
	</div>
	<div class="mwb_products_amazing-features mwb_products_vertical-align mwb_products-points-rewards-features">
		<ul>
			<li><i class="fa fa-check"></i>Works with all WooCommerce compatible themes.</li>
			<li><i class="fa fa-check"></i>Central place for everything for everyone .</li>
		</ul>
	</div>
	<div class="mwb_products_byNow"><a id="mwb-button2" class="btn btn-prmary mwb_products_2_hero-section__btn" href="https://makewebbetter.com/checkout/?add-to-cart=9320">BUY NOW</a></div>
</div>
<div class="mwb_products_final-section mwb_section mlr-15">
	<h4 class="mwb_section_heading">All in One Convincing lines for WooCommerce Points &amp; Rewards User Add-On!!</h4>
	<h4>Just Pick It Up For your Store !!!</h4>
	<p>WooCommerce Points and Rewards User Add-On has emerged as an ultimate solution for all merchants to let their customers be highly closed and engaged to sites for being immensely attracted to brands they own, the products they make, and the services they render at best.</p>
	<p>The main aim of this plugin is to ensure an everlasting and cherishing shopping experience to customers which ultimately motivates merchants to work efficiently in the direction of more nurturing customers leading to huge growth and success.</p>
</div>
<!--====  End of DESCRIPTION-SECTION  ====-->
<!--====================================
=            REVIEW-SECTION            =
=====================================-->
<?php
$comments = get_approved_comments( get_the_ID() );
if($comments){
	?>
	<div class="mwb_products_reviews mwb_products_reviews-pointsrewards mwb_section" id="reviews">
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