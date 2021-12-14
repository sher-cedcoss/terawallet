(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function() {

		// On License form submit.
		$( 'form#mwb-woocommerce-shipping-map-license-form' ).on( 'submit', function(e) {

			e.preventDefault();

			$( 'div#mwb-woocommerce-shipping-map-ajax-loading-gif' ).css( 'display', 'inline-block' );

			var license_key =  $( 'input#mwb-woocommerce-shipping-map-license-key' ).val();

			mwb_woocommerce_shipping_map_license_request( license_key );		
		});

		// License Ajax request.
		function mwb_woocommerce_shipping_map_license_request( license_key ) {

			$.ajax({
		        type:'POST',
		        dataType: 'json',
	    		url: license_ajax_object.ajaxurl,
		        data: {
		        	'action': 'mwb_woocommerce_shipping_map_license',
		        	'mwb_woocommerce_shipping_map_purchase_code': license_key,
		        	'mwb-woocommerce-shipping-map-license-nonce': license_ajax_object.license_nonce,
		        },

		        success:function( data ) {

		        	$( 'div#mwb-woocommerce-shipping-map-ajax-loading-gif' ).hide();

		        	if ( false === data.status ) {

	                    $( "p#mwb-woocommerce-shipping-map-license-activation-status" ).css( "color", "#ff3333" );
	                }

	                else {

	                	$( "p#mwb-woocommerce-shipping-map-license-activation-status" ).css( "color", "#42b72a" );
	                }

		        	$( 'p#mwb-woocommerce-shipping-map-license-activation-status' ).html( data.msg );

		        	if ( true === data.status ) {

	                    setTimeout(function() {
	                    	window.location = license_ajax_object.reloadurl;
	                    }, 500);
	                }
		        }
			});
		}

	});

})( jQuery );
