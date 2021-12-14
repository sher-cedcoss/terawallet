<?php 
if(!defined('ABSPATH')){
	exit;
}
get_header();
?>

<!--=====================================
=            hire-us-wrapper            =
======================================-->

<div class="mwb_hireus_wrapper-main">
	<div id="mwb-hire-us-wrapper">
	<div id="mwb-hire-us-banner">
		<div id="mwb-hire-us-text">
			<h2>We create stories by handling Big | Medium | Small Projects</h2>
			<h3>Hire Us to create your story</h3>	
		</div>
	</div>
	<?php // the_content(); ?>

 <!--==================================
 =            form-section            =
 ===================================-->
 <div class="mwb-hire-us-form-wrap" id="mwb-hire-us-form">
 	<!-- <h2 style="text-align: center;">Hire Us Form</h2> -->
 	<div class="mwb-hire-us-form">
 		<?php echo do_shortcode('[ninja_form id=9]'); ?>
 	</div>
 </div>

 
 <!--====  End of form-section  ====-->

 <!--=====================================
 =            meeting-section            =
 ======================================-->
 
 <div class="mwb_meet_wrap">
 	<h2>Meet our experts</h2>
 	<div class="mwb_meet_inner_wrap">
 		<a href="#">wordpress plugin expert</a>
 		<a href="#">theme expert</a>
 		<a href="#">integration market expert</a>
 	</div>
 </div>
 
 
 <!--====  End of meeting-section  ====-->
 
 

</div>

</div>


<!--====  End of hire-us-wrapper  ====-->

<?php 
get_footer();
?>