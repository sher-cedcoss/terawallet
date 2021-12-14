<?php
// Get header.
get_header();
global $post;
$postid = $post->ID;

do_action( 'woocommerce_shop_loop' );
?>
<div id="genesis-content" class="mwb_woocommerce_template">


	<?php

// if ( have_posts() ) {
// 	while ( have_posts() ) {
// 		the_post(); 
		the_content();
// 		//
// 		// Post Content here
// 		//
// 		comments_template();
// 	} // end while
// } // end if
	?>

</div>
<?php
get_footer();
?>