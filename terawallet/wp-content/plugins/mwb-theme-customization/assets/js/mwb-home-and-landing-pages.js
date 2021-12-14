jQuery(document).ready( function() {

	jQuery('.owl-carousel-review-content').owlCarousel({
	    center: false,
	    items: 1,
	    loop:true,
	    margin:10,
	    responsive:{
	        767:{
	            items: 2,
	            center: true,
	        }
	    }
	    // autoWidth:true
	});
	
	jQuery('.owl-carousel-review-new-landing-pages').owlCarousel({
	    center: false,
	    items: 1,
	    loop:true,
	    margin:10,
	    responsive:{
	        767:{
	            items: 2,
	            center: true,
	        }
	    }
	    // autoWidth:true
	});
	
});