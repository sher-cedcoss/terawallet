jQuery(document).ready( function() {

  /* fix ppc sidebar tab */
  if( jQuery(".mwb-services-category-blogs").length > 0 )
  {
    var header = jQuery(".mwb-services-category-blogs");
    var footer = jQuery(".site-footer");
    var section_height = header.offset().top + 100;
    var section_footer = footer.offset().top - 400;
    
    jQuery(window).scroll(function() {    
      var scroll = jQuery(window).scrollTop();
      if (scroll >= section_height && scroll < section_footer)
      {
        header.addClass("mwb-services-blog-fixed");
      } else{
        header.removeClass("mwb-services-blog-fixed");
      }
    });
  }

  jQuery(".mwb-service-get-quote").click(function(){
     jQuery(".mwb-get-quote-form-show").slideToggle('slow');
  });

});