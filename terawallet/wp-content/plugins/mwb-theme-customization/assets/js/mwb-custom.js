jQuery(document).ready( function() {
    
    //Offer Popup on Whole site
    // if(!getCookie('mwb_regular_offer_popup_site_sale')){
    //     setTimeout(function(){
    //       jQuery(document).find('.mwb-offer-regular-overlay_sale').show();
    //     }, 4000);
    // }
    
    // jQuery(document).on('click', '.mwb-offer-regular-popup-site-sale__link', function(e){
    //   e.preventDefault();
    //   var offer_url = jQuery(this).data('href');
    //   jQuery(document).find('.mwb-offer-regular-overlay_sale').hide();
    //   if(offer_url){
    //       setCookie('mwb_regular_offer_popup_site_sale', 1, 1);
    //       setTimeout(function(){
    //           window.location.href = offer_url;
    //       }, 2000);
    //   }
    // });
    
    // jQuery(document).on('click', '.mwb-offer-regular-overlay-close', function(){
    //   jQuery(document).find('.mwb-offer-regular-overlay_sale').hide();
    //   setCookie('mwb_regular_offer_popup_site_sale', 1, 1);
    // });
    //End of Offer Popup on Whole site

    if(jQuery('.mwb_hubspot_services__certificate-list-single').length > 0){
    	jQuery('.mwb_hubspot_services__certificate-list-single').owlCarousel({
    		items:3,
    		loop:false,
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
    				items:3,
    				nav: false
    			}
    		}
    	});
    }

  	var gggtm = '<noscr'+'ipt><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MPZS4FF" height="0" width="0" style="display:none;visibility:hidden"></iframe></nosc'+'ript>';
  	jQuery('body').prepend(gggtm);
  
  
 	/****Read more js*****/
 
 	var showChar = 100;
	var ellipsestext = "...";
	var moretext = "Read More";
	var lesstext = "Read Less";
	jQuery('.mwb_more').each(function() {
		var content = jQuery(this).html();

		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			var h = content.substr(showChar, content.length - showChar);

			var html = c + '<span class="mwb_moreellipses">' + ellipsestext+ '&nbsp;</span><span class="mwb_morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="mwb_morelink">' + moretext + '</a></span>';

			jQuery(this).html(html);
		}

	});

	jQuery(".mwb_morelink").click(function(){
		if(jQuery(this).hasClass("less")) {
			jQuery(this).removeClass("less");
			jQuery(this).html(moretext);
		} else {
			jQuery(this).addClass("less");
			jQuery(this).html(lesstext);
		}
		jQuery(this).parent().prev().toggle();
		jQuery(this).prev().toggle();
		return false;
	});
 
	/**End Read More Js***/
	/* faq accordion */
	jQuery(document).on('click' , '.mwb_pro_accordion-title' , function(){
		if(jQuery(this).hasClass('active')) {
			jQuery(this).removeClass('active');
			jQuery(this).next().hide('slow');
		}
		else {
			jQuery('.mwb_pro_content').hide('slow');
			jQuery(this).addClass('active');
			jQuery(this).next().show('slow');
		}

	});

});

jQuery(window).on('load', function(){
	if(mwb_subscriptionV2.checkout_page){
		jQuery( 'input#billing_email' ).on( 'change', function() {
			var guest_user_email = jQuery( 'input#billing_email' ).val();
			
			var data = {
				'action':'mwb_save_guest_user_email_checkout',
				'email':guest_user_email
			}

			jQuery.ajax({
				url: mwb_subscriptionV2.ajaxurl, 
				method:"POST",
				data: data,
				dataType:"json",
				success: function(result){
					console.log(result);
				}
			});
		});
		
		jQuery(document).on('change', '.wc_payment_methods .input-radio', function(){
           var payment_method = jQuery(this).val();
           
           var data = {
    			'action':'mwb_payment_method_fields_update',
    			'payment_method' : payment_method,
    		}
    
    		jQuery.ajax({
    			url: mwb_subscriptionV2.ajaxurl, 
    			method:"POST",
    			data: data,
    			dataType:"json",
    			success: function(result){
    				if(result.response){
    				// 	jQuery( document.body ).trigger( 'update_checkout' );
    				    // location.reload(true);
    				    window.location.href = mwb_subscriptionV2.checkouturl;
    				}
    			}
    		});
           
        });
        
	}

	//faq for product page
	jQuery('.resources-faq-heading').click(function(){
		if (!jQuery(this).hasClass('active')) {
			jQuery('.resources-faq-heading').removeClass('active');
			jQuery('.resources-faq-desc').slideUp(300);
			jQuery(this).addClass('active');
			jQuery(this).next('.resources-faq-desc').slideDown(300);
		}
		else if (jQuery(this).hasClass('active')) {
			jQuery(this).removeClass('active');
			jQuery(this).next('.resources-faq-desc').slideUp(300);
		}
	});

	//product page load more screenshot
	jQuery('#resource-load-more-faq').click(function(){
		jQuery('#resource-load-more-faq-content').slideDown();
		jQuery(this).parent().slideUp();
	});
	jQuery('#resource-load-more-sreenshot').click(function(){
		jQuery('#resource-load-more-sreenshot-content').slideDown();
		jQuery(this).parent().slideUp();
	});


	// jQuery(document).find('.mwb-site-container button#genesis-mobile-nav-primary').remove();

	if(jQuery(".mwb_top_offer_bar").length > 0){

		jQuery('.mwb_top_offer_bar').slideDown(300, function() {
	    // Animation complete.
	    	if(jQuery(window).width() > 960 ){
	    		var ht = jQuery('.mwb_top_offer_bar').outerHeight();
	    		var height_nav = 0;
	    		if(jQuery(".site-header").is(":visible")){
	        	    height_nav = jQuery('.site-header').outerHeight();
	    		}
	        	var height_tot = parseInt(ht, 10) + parseInt(height_nav, 10);

	        	jQuery('.site-header').animate({marginTop: ht },300);
	    		jQuery('.site-inner').css({'cssText': 'margin-top:' + height_tot + 'px !important' });
	        	jQuery('.mwb-blog-header').animate({marginTop: height_tot },300);
	        	jQuery('.mwb-shop-header').animate({marginTop: height_tot },300);

	    	}
	  	});

// 		var allBoxes = jQuery("div.mwb_offers").children("div");
// 		transitionBox(null, allBoxes.first());
// 		function transitionBox(from, to) {
// 			function next() {
// 				var nextTo;
// 				if (to.is(":last-child")) {
// 					nextTo = to.closest(".mwb_offers").children("div").first();
// 				} else {
// 					nextTo = to.next();
// 				}
// 				to.fadeIn(500, function () {
// 					setTimeout(function () {
// 						transitionBox(to, nextTo);
// 					}, 5000);
// 				});
// 			}

// 			if (from) {
// 				from.fadeOut(500, next);
// 			} else {
// 				next();
// 			}
// 		}
	}
	
});

function close_notification_bar(){
    jQuery('#mwb_top_offer_bar').slideUp(300);

    var height_nav = 0;
	if(jQuery(".site-header").is(":visible")){
	    height_nav = jQuery('.site-header').outerHeight();
	}

    jQuery('.site-header').animate({marginTop: '0px'},300);
    jQuery('.mwb-shop-header').animate({marginTop: height_nav},300);
    jQuery('.mwb-blog-header').animate({marginTop: height_nav},300);
    jQuery('.site-inner').animate({marginTop: height_nav},300);

}

jQuery(document).ready(function(){

	jQuery(document).on('click', 'a[href^="#"]', function (event) {
	    event.preventDefault();

	    jQuery('html, body').animate({
	        scrollTop: jQuery(jQuery.attr(this, 'href')).offset().top - 90
	    }, 500);
	});

	jQuery(document).on("click" , ".mwbshowlogin" ,function(){
		jQuery(".mwb_login_form").show();
		jQuery(".mwb_login_form_overlay").show();
	});

	jQuery(document).on("click" , ".mwb_close_popup" , function(){
		jQuery(".mwb_login_form").hide();
		jQuery(".mwb_login_form_overlay").hide();
	});

	jQuery(document).on('click','.mwb_reset_filter',function(){
		mwbblock();
		var data = {
			'action':'mwb_reset_filter',
		}

		jQuery.ajax({
			url: mwb_subscriptionV2.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				if(result){
					unmwbblock();
					if(jQuery("#genesis-content ul.products").length > 0){
						jQuery("#genesis-content ul.products").remove();
					}
					if(jQuery("#genesis-content .woocommerce-pagination").length > 0){
						jQuery("#genesis-content .woocommerce-pagination").remove();
					}
					if(jQuery("#genesis-content .mwb-no-products-wrapper").length > 0){
						jQuery("#genesis-content .mwb-no-products-wrapper").remove();
					}

					if(jQuery("#genesis-content p.woocommerce-result-count").length > 0){
						jQuery("#genesis-content p.woocommerce-result-count").after(result);
					}
					else{
						jQuery("#genesis-content div.woocommerce-notices-wrapper").after(result);
					}

					jQuery("input[type=checkbox].checkbox_product_features:checked").each(function(){
						jQuery(this).prop('checked', false);
					});
				}
			}
		});
	});

	jQuery(document).on('click','.mwb_average_rating_filter',function(){
		var rating = jQuery(this).val();
		mwbblock();
		var data = {
			'action':'mwb_ajax_rating_filter',
			'rating':rating
		}

		jQuery.ajax({
			url: mwb_subscriptionV2.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				if(result){
					unmwbblock();
					jQuery("ul.products li").hide();
					jQuery("ul.products").html(result);
					jQuery(".woocommerce-pagination").hide();
				}
			}
		});
	});

	jQuery(document).on('click','.checkbox_product_features', function(){
		var values = new Array();
		jQuery("input[type=checkbox].checkbox_product_features:checked").each(function(){
			values.push(jQuery(this).val());
		});

		mwbblock();
		var term_id = jQuery(this).data('term_id');
		var term_name = jQuery(this).data('term_name');
		var taxonomy = jQuery(this).data('taxonomy');
		var operator = jQuery(this).data('operator');

		var data = {
			'action':'mwb_ajax_feature_filter',
			'term_id':term_id,
			'term_name':term_name,
			'taxonomy':taxonomy,
			'operator':operator,
			'terms':values
		}

		jQuery.ajax({
			url: mwb_subscriptionV2.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				if(result){
					unmwbblock();
					if(jQuery("#genesis-content ul.products").length > 0){
						jQuery("#genesis-content ul.products").remove();
					}
					if(jQuery("#genesis-content .woocommerce-pagination").length > 0){
						jQuery("#genesis-content .woocommerce-pagination").remove();
					}
					if(jQuery("#genesis-content .mwb-no-products-wrapper").length > 0){
						jQuery("#genesis-content .mwb-no-products-wrapper").remove();
					}

					if(jQuery("#genesis-content .mwb-shop-feature-filter-note").length > 0){
						jQuery("#genesis-content .mwb-shop-feature-filter-note").remove();
					}

					if(jQuery("#genesis-content p.woocommerce-result-count").length > 0){
						jQuery("#genesis-content p.woocommerce-result-count").after(result);
					}
					else{
						jQuery("#genesis-content div.woocommerce-notices-wrapper").after(result);
					}
					
					jQuery('html, body').animate({
                        scrollTop: jQuery('ul.products').offset().top
                    }, 'slow');
				}
			}
		});
	});

	jQuery(document).on('click','.mwb_ajax_pagination', function(){

		var values = new Array();
		jQuery("input[type=checkbox].checkbox_product_features:checked").each(function(){
			values.push(jQuery(this).val());
		});
		mwbblock();
		var term_id = jQuery(this).data('term_id');
		var term_name = jQuery(this).data('term_name');
		var taxonomy = jQuery(this).data('taxonomy');
		var operator = jQuery(this).data('operator');
		var paged = jQuery(this).data('paged');

		var data = {
			'action':'mwb_ajax_feature_filter',
			'term_id':term_id,
			'term_name':term_name,
			'taxonomy':taxonomy,
			'operator':operator,
			'terms':values,
			'paged':paged
		}

		jQuery.ajax({
			url: mwb_subscriptionV2.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				if(result){
					unmwbblock();
					if(jQuery("#genesis-content ul.products").length > 0){
						jQuery("#genesis-content ul.products").remove();
					}
					if(jQuery("#genesis-content .woocommerce-pagination").length > 0){
						jQuery("#genesis-content .woocommerce-pagination").remove();
					}
					if(jQuery("#genesis-content .mwb-no-products-wrapper").length > 0){
						jQuery("#genesis-content .mwb-no-products-wrapper").remove();
					}

					if(jQuery("#genesis-content .mwb-shop-feature-filter-note").length > 0){
						jQuery("#genesis-content .mwb-shop-feature-filter-note").remove();
					}

					if(jQuery("#genesis-content p.woocommerce-result-count").length > 0){
						jQuery("#genesis-content p.woocommerce-result-count").after(result);
					}
					else{
						jQuery("#genesis-content div.woocommerce-notices-wrapper").after(result);
					}

				}
		    }
		});
	});

    function mwbconfirmbox(title, msg, value1, value2, thisdata) { /*change*/
	    var $content =  "<div class='mwb-confirm-dialog-ovelay'>" +
	                    "<div class='mwb-confirm-dialog'><header>" +
	                     " <h3> " + title + " </h3> " +
	                     "<i class='fa fa-close'></i>" +
	                 "</header>" +
	                 "<div class='mwb-confirm-dialog-msg'>" +
	                     " <p> " + msg + " </p> " +
	                 "</div>" +
	                 "<footer>" +
	                     "<div class='mwb-confirm-controls'>" +
	                         " <button class='mwb-confirm-button mwb-confirm-button-danger mwbdoAction'>" + value1 + "</button> " +
	                         " <button class='mwb-confirm-button mwb-confirm-button-default mwbcancelAction'>" + value2 + "</button> " +
	                     "</div>" +
	                 "</footer>" +
	              "</div>" +
	            "</div>";
        jQuery('body').prepend($content);
      	jQuery('.mwbdoAction').click(function () {
      		jQuery(this).parents('.mwb-confirm-dialog-ovelay').fadeOut(500, function () {
	          jQuery(this).remove();
	        });
        	mwbblock();
			var profileid = thisdata.data('profileid');
			var orderid = thisdata.data('orderid');
			var data = {
				'action':'mwb_cancel_subscription',
				'profileid':profileid,
				'orderid':orderid,
			}
			jQuery.ajax({
				url: mwb_subscriptionV2.ajaxurl, 
				method:"POST",
				data: data,
				dataType:"json",
				success: function(result){
					if(result.success){
						unmwbblock();
						window.location.reload();
					}
				}
			});
	    });

	    jQuery('.mwbcancelAction, .fa-close').click(function () {
        	jQuery(this).parents('.mwb-confirm-dialog-ovelay').fadeOut(500, function () {
	          	return false;
	        });
	    });
    }

	jQuery(document).on('click','.mwb_cancel_profile', function(){
		var thisdata = jQuery(this);
		mwbconfirmbox('Subscription Cancel Request', 'Are you sure you want to cancel your subscription?', 'Yes', 'Cancel', thisdata);
	});	

	jQuery(document).on('click','.mwb-prev-next', function(){
		
		var values = new Array();
		jQuery("input[type=checkbox].checkbox_product_features:checked").each(function(){
			values.push(jQuery(this).val());
		});
		mwbblock();
		var term_id = jQuery(this).data('term_id');
		var term_name = jQuery(this).data('term_name');
		var taxonomy = jQuery(this).data('taxonomy');
		var operator = jQuery(this).data('operator');
		var paged = jQuery(this).data('paged');
		var page_type = jQuery(this).data('page_type');
		
		var data = {
			'action':'mwb_ajax_feature_filter',
			'term_id':term_id,
			'term_name':term_name,
			'taxonomy':taxonomy,
			'operator':operator,
			'terms':values,
			'paged':paged,
			'page_type':page_type
		}

		jQuery.ajax({
			url: mwb_subscriptionV2.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				if(result){
					unmwbblock();
					if(jQuery("#genesis-content ul.products").length > 0){
						jQuery("#genesis-content ul.products").remove();
					}
					if(jQuery("#genesis-content .woocommerce-pagination").length > 0){
						jQuery("#genesis-content .woocommerce-pagination").remove();
					}
					if(jQuery("#genesis-content .mwb-no-products-wrapper").length > 0){
						jQuery("#genesis-content .mwb-no-products-wrapper").remove();
					}

					if(jQuery("#genesis-content .mwb-shop-feature-filter-note").length > 0){
						jQuery("#genesis-content .mwb-shop-feature-filter-note").remove();
					}

					if(jQuery("#genesis-content p.woocommerce-result-count").length > 0){
						jQuery("#genesis-content p.woocommerce-result-count").after(result);
					}
					else{
						jQuery("#genesis-content div.woocommerce-notices-wrapper").after(result);
					}

				}
			}
		});
	});	

	jQuery(document).on('click','#submit', function(e){

		var valid = false;
		var comment = jQuery('#comment').val();
		var author = jQuery('#author').val();
		var email = jQuery('#email').val();
		var check = jQuery('#wp-comment-cookies-consent').is(':checked'); 

		var email_regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if(!mwb_subscriptionV2.logged_in_user){


			if (comment.length < 1) {
				valid = true;
				jQuery('#comment').addClass('mwb_required');
			}
			else
			{
				jQuery('#comment').removeClass('mwb_required');
			}	
			if (author.length < 1){
				valid = true;
				jQuery('#author').addClass('mwb_required');
			}
			else
			{
				jQuery('#author').removeClass('mwb_required');
			}
			if (email.length < 1 || !email_regex.test(email)){
				valid = true;
				jQuery('#email').addClass('mwb_required');
			}
			else
			{
				jQuery('#email').removeClass('mwb_required');
			}

			if(valid){
				e.preventDefault();
			}
		}
		else{
			if (comment.length < 1) {
				valid = true;
				jQuery('#comment').addClass('mwb_required');
			}
			else
			{
				jQuery('#comment').removeClass('mwb_required');
			}

			if(valid){
				e.preventDefault();
			}
		}

	});

	jQuery(document).on('click','#mwb-offer-user-register', function(e){

		var valid = false;
		var user_name = jQuery('#mwb-offer-user-name').val();
		var user_email = jQuery('#mwb-offer-user-email').val();

		console.log(user_name);
		console.log(user_email);

		var email_regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if (user_name.length < 1){
			valid = true;
			jQuery('#mwb-offer-user-name').addClass('mwb_required');
		}
		else
		{
			jQuery('#mwb-offer-user-name').removeClass('mwb_required');
		}
		if (user_email.length < 1 || !email_regex.test(user_email)){
			valid = true;
			jQuery('#mwb-offer-user-email').addClass('mwb_required');
		}
		else
		{
			jQuery('#mwb-offer-user-email').removeClass('mwb_required');
		}

		if(valid){
			e.preventDefault();
		}

	});


	if(mwb_subscriptionV2.cart_count > 0){
		var abondant_html = '<h5><span style="color: rgb(255, 255, 255);">Ahh.. You Have Something In Your Cart.</span></h5>';
		abondant_html += '<p style="margin: 15px 0px 0px 0px;"><a href="'+mwb_subscriptionV2.checkouturl+'" class="button" style="background-color: #3d6dc3;">Click Here to Continue Shopping</a></p>';	
		jQuery("#mwb_cart_exit_text").html(abondant_html);
	}

	if(!getCookie('mwb_gdpr_privacy_bar')){
		jQuery(".mwb-privacy-wrapper").show();
	}

	jQuery(document).on("click" , ".mwb-agreement" , function(){
		jQuery(".mwb-privacy-wrapper").hide();
		var data = {
			'action':'mwb_gdpr_privacy_bar',
		}

		jQuery.ajax({
			url: mwb_subscriptionV2.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				jQuery(".mwb-privacy-wrapper").hide();
			}
		});

	});


	jQuery('.mwb_support_cart').hide();
	jQuery(document).on("click" , "#mwb_extend_support", function(){
		var total = 0;
		jQuery('.mwb_support_cart').hide();
		jQuery('.mwb_normal_cart').show();
		if(jQuery(this).is(':checked')){
			total = total + parseInt(jQuery(this).val());
			jQuery('.mwb_normal_cart').hide();
			jQuery('.mwb_support_cart').show();
		}

		total = total + parseInt(jQuery("#mwb_product_price").val());
		var html = '<span class="woocommerce-Price-currencySymbol">$</span>'+total.toFixed(2);
		jQuery("ins .woocommerce-Price-amount").html(html);
	});


	jQuery(document).on("click" , ".microservice_extra_check" , function(){
		var total = 0;
		jQuery(".microservice_extra_check").each(function(){
			if(jQuery(this).is(':checked')){
				total = total + parseInt(jQuery(this).val());
			}
		});
		total = total + parseInt(jQuery("#mwb_extra_service_price").val());
		var html = '<span class="woocommerce-Price-currencySymbol">$</span>'+total.toFixed(2);
		jQuery(".mwb_microservice_total_wrapper .woocommerce-Price-amount").html(html);
	});

	jQuery(document).on('click', 'a.remove', function(){
		setTimeout(function(){ 
			mwb_cart_update_html();
		}, 4000);
	});

	jQuery(document).on('click', 'input[name="update_cart"]', function(){
		setTimeout(function(){ 
			mwb_cart_update_html();
		}, 4000);
	});

	jQuery(document).on('click', 'input[name="apply_coupon"]', function(){
		setTimeout(function(){ 
			mwb_cart_update_html();
		}, 4000);
	});

	jQuery(document).on('click', 'a.add_to_cart_button', function(){
		mwb_cart_update_html();
	});


	if ( jQuery('.mwb_pro_detail').length > 0 && jQuery('.site-footer').length > 0) {
		var delta =   jQuery('.mwb_pro_detail').offset().top + jQuery('.mwb_pro_detail').height();
		jQuery(window).scroll(function(){
			var topdistance = jQuery('.site-footer').offset().top - jQuery(window).scrollTop();
			if (jQuery(window).scrollTop() >= delta) {
				jQuery('.mwb_product_info').addClass('fixed-top');
			} 
			if (jQuery(window).scrollTop() < delta) {
				jQuery('.mwb_product_info').removeClass('fixed-top');
			} 
			if(topdistance < 800){
				jQuery('.mwb_product_info').removeClass('fixed-top');
			}
		});
	}
	
	jQuery(document).on('click' , '.accordion' , function(){
		jQuery(this).toggleClass("active");
		jQuery(this).next().slideToggle("slow");
	});

	jQuery("#mwb_trail_product").submit(function(e){
		e.preventDefault();
		mwbblock();
		var data = jQuery("#mwb_trail_product").serialize();
		
		jQuery.ajax({
			url: mwb_subscriptionV2.ajaxurl, 
			method:"POST",
			data: data,
			dataType:"json",
			success: function(result){
				unmwbblock();
				window.location.href = mwb_subscriptionV2.checkouturl;
			},
			error: function(response){
				unmwbblock();
				jQuery("#mwb_response").html('<ul class="woocommerce-error"><li>Something Going Wrong.</li></ul>');
				jQuery('html, body').animate({
					scrollTop: (jQuery(".site-inner").offset().top)
				},500);
			}
		});
	});	


	jQuery(document).on('click','.view_coupons',function(){
		jQuery(".mwb_coupon_wrapper").show();
	});

	jQuery(document).on('click','.mwb_popup_close', function(){
		jQuery(".mwb_coupon_wrapper").hide();
	});

	/**
	 * Close Contact Us popup for Microservice Hourly Rate
	 */

	 jQuery(document).on("click" , ".mwb_serviceclose", function(){
	 	jQuery(".mwb_microservice_contact_wrapper").hide();
	 });

	 jQuery(document).on("click" , "#mwb_apply_coupon" , function(){
	 	var coupon_code = jQuery("#coupon_code").val();
	 	if(coupon_code == null || coupon_code == ""){
	 		jQuery("#coupon_code").css("border", "1px solid #a00");
	 		return;
	 	}else{
	 		jQuery("#coupon_code").css("border", "1px solid #aaa");
	 	}
	 	mwbblock();
	 	
	 	var data = {
	 		'action':'mwb_apply_coupon_code',
	 		coupon_code:	coupon_code
	 	};

	 	jQuery.ajax({
	 		url: mwb_subscriptionV2.ajaxurl, 
	 		method:"POST",
	 		data: data,
	 		success: function(result){
	 			unmwbblock();
	 			jQuery("#coupon_code").val("");
	 			jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
	 			jQuery("#mwb_checkout_response").html(result);

	 		}
	 	});
	 });


	/**
	 * Open Contact Us popup for Microservice Hourly Rate
	 */

	 jQuery(document).on("click" , ".mwbmicroservicecontact" , function(){
	 	var productid = jQuery(this).data('productid');
	 	var hourlyrate = jQuery(this).data('hourlyrate');
	 	var product_title = jQuery(this).data('name');
	 	jQuery("#mwb_service_id").val(productid);
	 	jQuery("#mwb_service").val(product_title);
	 	jQuery("#mwb_hourly_rate").val(hourlyrate);
	 	jQuery(".mwb_microservice_contact_wrapper").show();
	 });

	/**
	 * Submit Contact Us popup for Microservice Hourly Rate
	 */

	 jQuery(document).on("click", ".mwb_microservicecpntactsubmit" , function(){
	 	jQuery(".mwb_job_submit_load").show();
	 	jQuery("#mwbmicroserviceresponse").html("");
	 	var productid = jQuery("#mwb_service_id").val();
	 	var product_title = jQuery("#mwb_service").val();
	 	var hourlyrate = jQuery("#mwb_hourly_rate").val();
	 	var email = jQuery("#mwb_email").val();
	 	var description = jQuery("#mwb_service_description").val();
	 	if (jQuery("#mwb_checkbox_microservices").is(":checked"))
	 	{
	 		var mwb_checkbox = jQuery("#mwb_checkbox_microservices").val();
	 	}else{
	 		var mwb_checkbox="";
	 	}
	 	var data = {
	 		'action':'mwb_submit_service',
	 		'productid':productid,
	 		'product_title':product_title,
	 		'hourlyrate':hourlyrate,
	 		'email':email,
	 		'description':description,
	 		'mwb_checkbox':mwb_checkbox,
	 		'register':'register'
	 	}
	 	jQuery.ajax({
	 		url: mwb_subscriptionV2.ajaxurl, 
	 		method:"POST",
	 		data: data,
	 		dataType:"json",
	 		success: function(result){
	 			jQuery(".mwb_job_submit_load").hide();
	 			jQuery("#mwbmicroserviceresponse").html(result.message);
	 			jQuery(".mwb_microservice_contact_fields").scrollTop(0);
	 			if(result.valid){
	 				jQuery("#mwb_email").val("");
	 				jQuery("#mwb_service_description").val("");
					//setTimeout(function(){ jQuery(".mwb_microservice_contact_wrapper").hide(); }, 2000);
				}
			}
		});	
	 });	
 });

function mwbblock(){
	jQuery("#genesis-content").addClass( 'processing' ).block( {
		message: null,
		overlayCSS: {
			background: '#fff',
			opacity: 0.6
		}
	} );
}


function unmwbblock(){
	jQuery("#genesis-content").removeClass( 'processing' ).unblock();
}

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function mwb_cart_update_html(){
	var data = {
		'action':'mwb_check_cart',
	}

	jQuery.ajax({
		url: mwb_subscriptionV2.ajaxurl, 
		method:"POST",
		data: data,
		dataType:"json",
		success: function(result){
			jQuery(".mwb_menu_cart_wrapper").html(result.html);
		}
	});
}

/*resources tab section*/
jQuery(document).ready(function(){
	jQuery('.resources-tabs-wrapper').find('.resources-tabs-link').click(function(){
		jQuery('.resources-tabs-link').removeClass('active');
		jQuery(this).addClass('active');
		var attr = jQuery(this).attr('data-attr');
		jQuery('.resources-tab-content').each(function(){
			if (jQuery(this).attr('data-tab-content') == attr) {
				jQuery(this).fadeIn(200);
				jQuery(this).addClass('show');
			}else{
				jQuery(this).fadeOut(200);
				jQuery(this).removeClass('show');
			}
		});
	});
	
});