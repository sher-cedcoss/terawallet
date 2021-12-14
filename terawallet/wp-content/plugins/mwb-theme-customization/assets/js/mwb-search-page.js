/*=============================================
=            Search Page Accordian            =
=============================================*/
jQuery(document).ready(function() {
  jQuery(document).on('click', '.post-type-heading' ,function(){
    if (jQuery(this).next(".post-type-content, .mwb-no-result").is(':visible')) {
      jQuery(this).next(".post-type-content, .mwb-no-result").slideUp(300);
      jQuery(this).children(".plusminus").text('+');
    } else {
      jQuery(this).next(".post-type-content, .mwb-no-result").slideDown(300);
      jQuery(this).children(".plusminus").text('-');
    }
  });
});

/*=====  End of Search Page Accordian  ======*/