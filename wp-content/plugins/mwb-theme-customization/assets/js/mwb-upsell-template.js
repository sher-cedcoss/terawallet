jQuery(window).load(function(){

	// Preview respective template.
	jQuery(document).on('click', '.mwb-popup-link', function(e) {
		e.preventDefault();
		// Current template id.
		var template_id = jQuery(this).data( 'template-id' );
		jQuery('.mwb-offer-popup-wrapper-' + template_id ).addClass('active');
		jQuery('html').addClass('mwb_preview_body');

	});

   // Close Preview of respective template.
	jQuery(document).on('click', '.mwb-offer-preview-close', function(e) {
		jQuery('html').removeClass('mwb_preview_body');
		jQuery('.mwb-offer-popup-wrapper-one').removeClass('active');
		jQuery('.mwb-offer-popup-wrapper-two').removeClass('active');
		jQuery('.mwb-offer-popup-wrapper-three').removeClass('active');

	});

	jQuery(document).on('click', '.mwb_preview_body', function(e){
		e.preventDefault();
		jQuery('.mwb-offer-popup-wrapper-one').removeClass('active');
		jQuery('.mwb-offer-popup-wrapper-two').removeClass('active');
		jQuery('.mwb-offer-popup-wrapper-three').removeClass('active');
		jQuery('html').removeClass('mwb_preview_body');
	});
	jQuery(document).on('click', '.mwb-offer-popup-inner', function(e){
		e.stopPropagation();
		
	});


});