jQuery(document).ready(function(){

	jQuery(".mwb_dma_slider_wrapper").owlCarousel({
       	slideSpeed : 400,
       	paginationSpeed : 400,
       	items:2 ,
       	dots:true,
       	margin: 0,
       	loop: true,
       	stagePadding: 0,
       	nav:false,
       	autoplay:true,
       	autoplayTimeout:2000,
       	responsive:{
	        0:{
	            items:1,
	            nav:false,
	            dots:true
	        },
	        768:{
	            items:2,
	            nav:false,
	            dots:true
	        }
	    }

   	});


	/*==========================================
	=            counter-scroll-dma            =
	==========================================*/
	if(jQuery('.mwb_dma_time_counter__wrapper').length > 0){

		jQuery('.mwb_dma_time_counter__wrapper').appear(function() {
			jQuery('.mwb_dma_time_counter__count').each(function () {
				jQuery(this).prop('counter',0).animate({
					counter: jQuery(this).text()
				}, {
					duration: 3000,
					easing: 'swing',
					step: function (now) {
						jQuery(this).text(Math.ceil(now));
					}
				});
			});
		});
		
	}
	/*=====  End of counter-scroll-dma  ======*/

	jQuery(".mwb_dma-top-4-inner").owlCarousel({
		slideSpeed : 400,
		paginationSpeed : 400,
		items:5, 
		dots:true,
		margin: 0,
		loop: true,
		autoplay: true,
		autoplayTimeout: 2000,
		nav:false,
		responsive:{
			0:{
				items:2,
				nav:false,
				dots:true
			},
			450:{
				items:3,
				nav:false,
				dots:true
			},
			650:{
				items:4,
				nav:false,
				dots:true
			},
			900:{
				items:5,
				nav:false,
				dots:true
			}
		}
	});

});