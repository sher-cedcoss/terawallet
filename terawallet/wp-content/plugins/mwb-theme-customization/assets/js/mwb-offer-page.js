jQuery(document).ready(function(){	
	/*====================================================
	=            Offer Page Bannder Slider JS            =
	====================================================*/
	jQuery('.offer-banner--top').owlCarousel({
		loop:false,
		margin:10,
		nav:false,
		dots: true,
		items:1
	});
	jQuery('.deal-offer').owlCarousel({
		loop:false,
		nav: false,
		dots: true,
		margin:10,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
			},
			600:{
				items:2,
			},
			991:{
				items:3,
			}
		}
	});
	jQuery('.tp-row--mob').owlCarousel({
		loop:true,
		nav: true,
		dots: false,
		margin:10,
		navText:  ["<i class='fas fa-long-arrow-alt-left mwb-offer-icon-white'></i>","<i class='fas fa-long-arrow-alt-right mwb-offericon-white'></i>"],
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
			},
			500:{
				items:2,
			},
			700:{
				items:4,
			}
		}
	});
	
	/*=====  End of Offer Page Bannder Slider JS  ======*/
	

});