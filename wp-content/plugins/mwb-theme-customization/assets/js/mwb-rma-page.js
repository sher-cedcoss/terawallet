jQuery(document).ready(function(){
	
jQuery("#slideshow > div:gt(0)").hide();
setInterval(function() {
  jQuery('#slideshow > div:first')
  .fadeOut(1000)
  .next()
  .fadeIn(1000)
  .end()
  .appendTo('#slideshow');
}, 4000);
});