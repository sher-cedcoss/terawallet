jQuery(document).ready(function(){

	/* gallery client about us page */
	jQuery('.mwb-about-client').owlCarousel({
	    loop:true,
	    margin:0,
	    nav:false,
	    autoplay:true,
	    dots:false,
	    responsive:{
	        0:{
	            items:1,
	            nav: false,
	        },
	        500:{
	            items:2,
	            nav: false,
	        },
	        767:{
	            items:3,
	            center: true,
	            nav: false,
	        },
	        1000:{
	            items:5
	        }
	    }
	});
	/* achievements about us slider */
	jQuery('.mwb-about-achievement').owlCarousel({
	    loop:true,
	    margin:10,
	    nav:true,
	    navText: ["<img src='https://makewebbetter.com/wp-content/uploads/2019/08/left-arrow.png'>","<img src='https://makewebbetter.com/wp-content/uploads/2019/08/right-arrow.png'>"],
	     autoplay:true,
	    dots:false,
	    responsive:{
	        0:{
	            items:1,
	        },
	         500:{
	            items:2,
	        },
	        767:{
	            items:3,
	        },
	        1000:{
	            items:5
	        }
	    }
	});

});