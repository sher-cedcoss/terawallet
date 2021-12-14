jQuery(document).ready(function(){

	jQuery(".mwb-woo-top-4-inner, .mwb_dma-top-4-inner").owlCarousel({
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
	
	jQuery(".mwb-woo-grid-wrap").owlCarousel({
		slideSpeed : 400,
		paginationSpeed : 400,
		items:3, 
		dots:false,
		margin: 0,
		loop: true,
		autoplay: true,
		autoplayTimeout: 2000,
		nav: false,
		responsive:{
			0:{
				items:1,
				nav:false,
				dots:false
			},
			600:{
				items:2,
				nav:false,
				dots:false
			},
			940:{
				items:3,
				nav:false,
				dots:false
			}
		}
	});

	/*==============================================
	=            mwb-woo-services-modal            =
	==============================================*/
	var elements = jQuery('.modal-overlay, .modal');
	
	jQuery(document).on('click', '.mwb-woo-services-btn, .services_button', function(){
		jQuery(document).find('#mwb_form_modal').addClass('active');          
		jQuery(document).find('#mwb_form_modal_inner').addClass('active');          
	});
	jQuery('.close-modal').click(function(){
		elements.removeClass('active');
	});
	jQuery(document).on('click','body',function(e){

		var divClick = e.target;

		if(jQuery(divClick).attr('id') == 'mwb_form_modal'){
			jQuery(document).find('#mwb_form_modal').removeClass('active');
			jQuery(document).find('#mwb_form_modal_inner').removeClass('active');
		}
	});
	

	/*=====  End of mwb-woo-services-modal  ======*/

});