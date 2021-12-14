(function( $ ) 
{
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

	 function initMap() 
	 {
	 	var mwb_store_latitude = parseFloat(object_name.store_latitude);
	 	
	 	var mwb_store_longitude = parseFloat(object_name.store_longitude);

	 	var mwb_store_lat_long = {lat: mwb_store_latitude , lng: mwb_store_longitude};

	 	var mwb_store_address = object_name.store_address;

	 	var mwb_store_city = object_name.store_city;

	 	var mwb_store_country = object_name.store_country;

	 	var mwb_shipping_latitude = parseFloat(object_name.shipping_latitude);
	 	
	 	var mwb_shipping_longitude = parseFloat(object_name.shipping_longitude);
	 	
	 	var mwb_shipping_url = {lat: mwb_shipping_latitude , lng: mwb_shipping_longitude};

	 	var mwb_shipping_address = object_name.shipping_address;

	 	var mwb_shipping_city = object_name.shipping_city;

	 	var mwb_shipping_country = object_name.shipping_country;
	 	
	 	// Functionality for different country.
	 	if(object_name.country_status!='same')
	 	{
	 		$("#map").show();
	 		
	 		$("#map_notice").show();
	 		
	 		var map = new google.maps.Map(document.getElementById('map'), {

	 			zoom: 5,

	 			minZoom: 1,

	 			center: {lat: mwb_store_latitude , lng: mwb_store_longitude},

	 			mapTypeId: 'terrain'

	 		});

	 		var mwb_store_address_title = "Store Address:"+mwb_store_address+" "+mwb_store_city+" "+mwb_store_country;

	 		var mwb_store_address = "Store Address:"+mwb_store_address+"<br>"+mwb_store_city+" "+mwb_store_country;

	 		var marker = new google.maps.Marker({position:mwb_store_lat_long, title: mwb_store_address_title, icon:'https://maps.google.com/mapfiles/ms/icons/red-dot.png'});

	 		marker.setMap(map);


	 		var infowindow = new google.maps.InfoWindow({

	 			content: mwb_store_address

	 		});

	 		infowindow.open(map,marker);
	 		
	 		var flightPlanCoordinates = [

	 		{lat: mwb_shipping_latitude , lng: mwb_shipping_longitude},
	 		
	 		{lat: mwb_store_latitude , lng: mwb_store_longitude}
	 		
	 		];
	 		
	 		var flightPath = new google.maps.Polyline({

	 			path: flightPlanCoordinates,

	 			geodesic: true,

	 			strokeColor: '#FF0000',

	 			strokeOpacity: 5,

	 			strokeWeight: 5

	 		});
	 		
	 		flightPath.setMap(map);

	 		var mwb_Shipping_address_title = "Shipping Address:"+mwb_shipping_address+" "+mwb_shipping_city+" "+mwb_shipping_country;
	 		
	 		var marker = new google.maps.Marker({position:mwb_shipping_url, title: mwb_Shipping_address_title, icon:'https://maps.google.com/mapfiles/ms/icons/green-dot.png'});

	 		marker.setMap(map);

	 		var mwb_Shipping_address = "Shipping Address:"+mwb_shipping_address+"<br>"+mwb_shipping_city+" "+mwb_shipping_country;

	 		var infowindow = new google.maps.InfoWindow({

	 			content: mwb_Shipping_address

	 		});

	 		infowindow.open(map,marker);
	 	}
	 	else
	 	{
	 		// Functionality for same country.

	 		var directionsDisplay;

	 		var directionsService = new google.maps.DirectionsService();

	 		var map;

	 		map = new google.maps.Map(document.getElementById('map'), mapOptions);
	 		
	 		$(document).find('#map').show();

	 		$("#map_notice").show();

	 		directionsDisplay = new google.maps.DirectionsRenderer();
	 		
	 		var mapOptions = {

	 			zoom: 13,

	 			center: mwb_store_lat_long

	 		};

	 		var mwb_store_address_title = "Store Address:"+mwb_store_address+" "+mwb_store_city+" "+mwb_store_country;

	 		var marker = new google.maps.Marker({position:mwb_store_lat_long, title: mwb_store_address_title, icon:'https://maps.google.com/mapfiles/ms/icons/red-dot.png'});

	 		marker.setMap(map);

	 		var mwb_Store_address = "Store Address:"+mwb_store_address+"<br>"+mwb_store_city+" "+mwb_store_country;

	 		var infowindow = new google.maps.InfoWindow({

	 			content: mwb_Store_address

	 		});

	 		infowindow.open(map,marker);

	 		directionsDisplay.setMap(map);
	 		
	 		directionsDisplay.setOptions( { suppressMarkers: true } );

	 		var mwb_Shipping_address = "Shipping Address:"+mwb_shipping_address+"<br>"+mwb_shipping_city+" "+mwb_shipping_country;

	 		var request = 

	 		{
	 			origin: mwb_store_lat_long,

	 			destination: mwb_shipping_url,

	 			travelMode: google.maps.TravelMode.DRIVING
	 		};

	 		var mwb_Shipping_address_title = "Shipping Address:"+mwb_shipping_address+" "+mwb_shipping_city+" "+mwb_shipping_country;

	 		var marker = new google.maps.Marker({position:mwb_shipping_url, title: mwb_Shipping_address_title, icon:'https://maps.google.com/mapfiles/ms/icons/green-dot.png'});

	 		marker.setMap(map);

	 		var infowindow = new google.maps.InfoWindow({

	 			content: mwb_Shipping_address

	 		});

	 		infowindow.open(map,marker);

	 		directionsService.route(request, function(response, status) 
	 		{
	 			if (status == google.maps.DirectionsStatus.OK) 
	 			{
	 				directionsDisplay.setDirections(response);

	 				directionsDisplay.setMap(map);
	 			} 
	 		});
	 	}
	 }

	 initMap();

	})( jQuery );