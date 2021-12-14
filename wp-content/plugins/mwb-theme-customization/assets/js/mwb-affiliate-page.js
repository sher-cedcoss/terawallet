jQuery(document).ready( function() {
  /*affiliate js */
    (function(jQuery) {
    jQuery('.mwb-affiliate-page__content-right a[href^="#"]').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = jQuery(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                jQuery('html,body').animate({
                    scrollTop: target.offset().top - 120
                }, 1200);
                return false;
            }
        }
    });
})(jQuery);
jQuery(window).scroll(function() {
    var scrollDistance = jQuery(window).scrollTop() + 150;
    // Assign active class to nav links while scolling
    jQuery('.mwb-affiliate-page__section').each(function(i) {
      var sectiondistance = jQuery(this).position().top;
      
        if (sectiondistance <= scrollDistance) {
            jQuery('.mwb-affiliate-page__content-right ul li ul li a.active').removeClass('active');
            jQuery('.mwb-affiliate-page__content-right ul li ul li a').eq(i).addClass('active');
        }
    });
}).scroll();
/* affiliate js end */

});