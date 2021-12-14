jQuery(document).ready( function() {
	
	jQuery(document).on('click', '.mwb_support_and_extend_checkbox', function(){
	       
		var prod_id = jQuery(this).data('prod_id');
		var cart_item_key = jQuery(this).data('cart_key');

		if(jQuery(this).is(':checked')){
			var checked_sau_option = 'yes';
		}
		else{
			var checked_sau_option = 'no';
		}
		
		var data = {
			'action':'mwb_support_and_extend_meta_add',
			'product_id' : prod_id,
			'cart_item_key' : cart_item_key,
			'checked_sau_option' : checked_sau_option, 
		}

		jQuery.ajax({
			url: mwb_cart_page.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				if(result.response){
					jQuery("input[name='update_cart']").trigger("click");
				}
			}
		});
	});
	
});