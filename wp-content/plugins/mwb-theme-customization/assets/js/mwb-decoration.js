jQuery(document).ready(function($){

	var mwb_upload_box = "";
	var mwb_upload_preview = "";
	jQuery(document).on('click', '.upload_festive_image', function() {
		mwb_upload_box = jQuery(this).prev('input');
		mwb_upload_preview = jQuery(this).next().children();
		// console.log(jQuery(this).next().children());

		tb_show('Upload Image', 'media-upload.php?referer=media_page&type=image&TB_iframe=true&post_id=0', false);
		return false;
	});
	window.send_to_editor = function(html) {
		var image_url = jQuery('img', html).attr('src');
		
		mwb_upload_box.val(image_url);
		mwb_upload_preview.attr('src' , image_url);
		tb_remove(); // calls the tb_remove() of the Thickbox plugin
		// $j('#submit_button').trigger('click');
	}


});