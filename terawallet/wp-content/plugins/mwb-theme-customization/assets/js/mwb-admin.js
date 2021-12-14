jQuery(document).ready(function($){

	$("#mwb_microservice_addextra").click(function(){
		var html = '<tr><td><input type="text" name="mwb_microservice_extra[]"></td><td><input type="text" name="mwb_microservice_price[]"></td><td><a herf="javascript:void(0);" class="mwb_remove_microservice">X</a></td></tr>';
		$("#mwb_miroservice_extra_wrapper").append(html);
	});
	$(document).on('click', ".mwb_remove_microservice", function(){
		$(this).parent().parent().remove();
	});


	var expanded = false;
	jQuery(document).on('click','.selectcouponbox', function(){
	    if (!expanded) {
	    	jQuery('#checkboxes').css('display','block');
		    expanded = true;
	    } else {
	    	jQuery('#checkboxes').css('display','none');
		    expanded = false;
	    }
	});

	jQuery('.js-example-basic-multiple').select2();
	jQuery('#mySelect2').select2('data');

	// Blog Select
	jQuery(document).find('.blog_selectbox').select2({
		maximumSelectionLength: 3
	});


	jQuery(document).on('click','.mwb_cancel_profile', function(){
		var thisdata = jQuery(this);
		if(confirm('Are you sure you want to cancel your subscription?'))
		{
			var profileid = thisdata.data('profileid');
			var orderid = thisdata.data('orderid');
			var data = {
				'action':'mwb_cancel_subscription',
				'profileid':profileid,
				'orderid':orderid,
			}
			jQuery.ajax({
				url: mwb_admin_subscription.ajaxurl, 
				method:"POST",
				data: data,
				dataType:"json",
				success: function(result){
					if(result.success){
						window.location.reload();
					}
				}
			});
		}	
	});

});