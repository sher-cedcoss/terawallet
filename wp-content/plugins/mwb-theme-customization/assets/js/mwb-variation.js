jQuery(document).ready(function(){

	// console.log(jQuery(document).find('form#mwb_codecanyon_vm_template_form').prev());
	jQuery(document).find('form#mwb_codecanyon_vm_template_form').prev().remove();

	jQuery('.mwb_variation_slider').owlCarousel({
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
				items:1
			},
			1280:{
				items:1
			}
		}
	}); 

	jQuery( ".owl-prev").html('<i class="fa fa-chevron-left"></i>');
	jQuery( ".owl-next").html('<i class="fa fa-chevron-right"></i>');

	});
