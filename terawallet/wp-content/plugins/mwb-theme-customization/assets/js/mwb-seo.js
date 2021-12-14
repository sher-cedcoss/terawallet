jQuery(document).ready(function(){

	jQuery('.mwb-seo-practice-slider').owlCarousel({
	   loop:true,
	   margin:10,
	   nav:true,
	   responsive:{
	       0:{
	           items:1
	       },
	       600:{
	           items:1
	       },
	       1000:{
	           items:3
	       }
	   }
	});

	jQuery( ".owl-prev").html('<i class="fa fa-chevron-left"></i>');
	jQuery( ".owl-next").html('<i class="fa fa-chevron-right"></i>');

	
	});