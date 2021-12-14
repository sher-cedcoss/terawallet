jQuery(document).ready(function(){
	jQuery('#mwb_slider_popup').hide();
	jQuery(document).find('.comment-form-comment').wrap('<div id="mwb-product-comment"/>');
	jQuery(document).find('.comment-form-author').addClass('mwb-comment-data');
	jQuery(document).find('.comment-form-email').addClass('mwb-comment-data');
	jQuery(document).find('.comment-form-url').addClass('mwb-comment-data');

	jQuery(document).find('.mwb-comment-data').wrapAll('<div id="mwb-product-comment-data"/>');



	jQuery('.owl-carousel').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3
			},
			1000:{
				items:3
			},
			1280:{
				items:4
			}
		}
	}); 

	jQuery( ".owl-prev").html('<img src='+mwb_product_page_param.left_arrow+'>');
	jQuery( ".owl-next").html('<img src='+mwb_product_page_param.right_arrow+'>');
	
	jQuery(".cloned").each(function(){
		jQuery(this).has('p').remove();
	});
	
	jQuery(".owl-item").each(function(){
		jQuery(this).has('p').remove();
	});

	jQuery('.owl-item').on('click',function(){
		jQuery(document).find('#mwb_slider_popup').addClass('mwb_popup');
		var img_src = jQuery(this).find('img').attr('src');
		var img_html = '';
		img_html = '<img id="mwb_popup_wrapper" src="'+img_src+'"/>';
		img_html += '<div id="mwb_popup_close">X</div>';
		jQuery('#mwb_slider_popup').html(img_html);
		jQuery('#mwb_slider_popup').show();
	});
	// <div class="mwb_popup_wrap" id="mwb_popup_wrapper"></div>

	jQuery(document).on('click','#mwb_popup_close',function(){
		jQuery(document).find('#mwb_slider_popup').removeClass('mwb_popup');
		jQuery(document).find('#mwb_slider_popup').hide();
	});

	jQuery(document).on('click','#mwb_slider_popup',function(e){
		console.log(e.target.id);
		if(e.target.id != 'mwb_popup_wrapper'){
			jQuery(document).find('#mwb_slider_popup').removeClass('mwb_popup');
			jQuery(document).find('#mwb_slider_popup').hide();
		}
	});
});