jQuery(window).on('load',function(){
	setTimeout(function(){
		if(jQuery('.mwb_review_page_wrapper').length){
			var container = document.querySelector('.mwb_review_page_wrapper');
			var msnry = new Masonry( container, {
				itemSelector: '.mwb_client_review_section',
				columnWidth: '.mwb_client_review_section',
				horizontalOrder: true,
			});
		}
	}, 1);
});