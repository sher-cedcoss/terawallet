jQuery(document).ready(function() {
	jQuery('.mwbhs-work__slider').owlCarousel({
		autoplay: true,
	    center: true,
	    items:2,
	    loop:true,
	    margin:0,
	    nav: false,
	    dots: false,
	    responsive:{
	    	0:{
            	items:1,
        	},
	        768:{
	            items:3
	        }
	    }
	});

	/* Accordion JS */
	jQuery('.mwbhs-sub-heading').click(function() {
		if(jQuery(this).parents('.mwbhs-faq__section').hasClass('active')) {
		}
		else {
			jQuery('.mwbhs-faq__text').hide('slow');
			jQuery('.mwbhs-faq__section').removeClass('active');
			jQuery(this).parents('.mwbhs-faq__section').addClass('active');
			jQuery(this).parents('.mwbhs-faq__section').children('.mwbhs-faq__text').show('slow');
		}
	});
});