jQuery(document).ready( function() {
  jQuery('.mwb_hubspot_services__certificate-list').owlCarousel({
  	loop:true,
  	margin:10,
  	nav:true,
  	responsive:{
  		0:{
  			items:1,
  			nav: false
  		},
  		600:{
  			items:3,
  			nav: false
  		},
  		1000:{
  			items:4,
  			nav: false
  		}
  	}
  });
});