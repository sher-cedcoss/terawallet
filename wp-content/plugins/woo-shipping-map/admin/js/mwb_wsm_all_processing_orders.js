
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


// show all orders on map

jQuery('.submit').css('display' , 'none');
function initMap() {
    var gmap;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
       
    };
                    
    // Display a google map on the web page
    gmap = new google.maps.Map(document.getElementById("map"), mapOptions);
    gmap.setTilt(45);
        
    // Multiple location Markers
    var myLocations = [];
    for (const [key, value] of Object.entries(object_name)) {
    
        myLocations.push(value);
      }
                        
    // Info Window Content
    var infoPopupWindowContent = [];
    var temp =''
    for( i = 0; i < myLocations.length; i++ ) {
        temp = ['<div class="info_content">' + '<h3><b> Order : </b> '+myLocations[i][6]+'</h3>' + '<p> <b>Shipping Address :  </b>'+myLocations[i][0]+","+myLocations[i][1]+ ' </p>' + '<p><b> County </b> :' +myLocations[i][4]+ ' ' + '</p><p> <b>Country : </b>'+myLocations[i][5] +'</p></p><a href="'+ myLocations[i][7] +'">' + 'View order' + '</a><b style="float:right">'+ myLocations[i][8] +'</b></div>'];
       infoPopupWindowContent.push(temp); 
   
  }  
    // Show multiple location markers on the google map  
    var infoPopupWindow = new google.maps.InfoWindow(), marker, i;
    
    // generate the markers & place each one on the google map  
    for( i = 0; i < myLocations.length; i++ ) {
        var position = new google.maps.LatLng(myLocations[i][2], myLocations[i][3]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: gmap,
            title: myLocations[i][0]
        });
        
    
        // Set each location marker to a infowindow    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            var address = infoPopupWindowContent[i][0];
            return function() {
                infoPopupWindow.setContent(address);
                
                infoPopupWindow.open(gmap, marker);
            }
        })(marker, i));
        // Ot Fitting Automatically center the screen
        gmap.fitBounds(bounds);
    }
    // Customize google map zoom level once fitBounds run
    var boundsListener = google.maps.event.addListener((gmap), '', function(event) {
        this.setZoom(12);
        google.maps.event.removeListener(boundsListener);
    });
    
}

initMap();

})( jQuery );