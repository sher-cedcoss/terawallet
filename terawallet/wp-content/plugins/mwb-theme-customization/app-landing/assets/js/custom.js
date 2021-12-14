/** 
* Template Name: Appro App Landing Page - Ultra Modern Responsive Bootstrap Educational Html5 Template
* Version: 1.0  
* Template Scripts
* Author: Appro App Landing Page template
**/

/*===============================
=  1. HEADER                    =
=================================*/
$(window).scroll(function(){
  if ($(this).scrollTop() > 200) {
   $('#js-header').addClass('header-smaller');
 } else {
   $('#js-header').removeClass('header-smaller');
 }
});

jQuery('a.page-scroll').click(function(e) {
  jQuery('html,body').animate({ scrollTop: jQuery(this.hash).offset().top-45}, 1000);
  return false;
  e.preventDefault();
});

/*===============================
=  1. ABOUT SLIDER              =
=================================*/
var $homeslide = $('#homeslide');
$homeslide.owlCarousel({
  merge: true,
  smartSpeed: 1000,
  loop: true,
  nav: false,
  autoplay: true,
  autoplayTimeout: 4000,
  margin: 0,
  responsiveClass: true,
  responsive: {
    0: {
      items: 1
    },
    600: {
      items: 1
    },
    1000: {
      items: 1
    },
    1200: {
      items: 1
    }
  }
});


/*===============================
=  2. SCREEN SHOT SLID          =
=================================*/
if($('.screenshort-slide').length>0){
  $('#screen-short-text').owlCarousel({
    loop:true,
    nav:true,
    items:1,
    mouseDrag:false,
    smartSpeed: 500,
    autoplay:true,
    autoplayTimeout:7000,
    autoplayHoverPause:false,
    responsive:{
      0:{
        items:1
      },
      600:{
        items:1
      },
      1000:{
        items:1
      }
    }
  })

  $('#screen-short-img').owlCarousel({
    loop:true,
    items:1,
    mouseDrag:false,
    smartSpeed: 500,
    autoplay:true,
    autoplayTimeout:7000,
    autoplayHoverPause:false,
    responsive:{
      0:{
        items:1
      },
      600:{
        items:1
      },
      1000:{
        items:1
      }
    }
  });

  $('.owl-item').click(function() {
    var id=$(this).children().attr('id');
    $('#screen-short-text').trigger('to.owl.carousel', id);
    $('#screen-short-img').trigger('to.owl.carousel', id);
    $('#screen-short-text').trigger('stop.owl.autoplay');
    $('#screen-short-img').trigger('stop.owl.autoplay');
  });

  $('.owl-next').click(function() {           
    var id=$('#screen-short-text').find('.active').children().attr('id');
    id=id.replace('d','');
    trigger_id=id;
    $('#screen-short-img').trigger('to.owl.carousel', trigger_id);
    $('#screen-short-text').trigger('stop.owl.autoplay');
    $('#screen-short-img').trigger('stop.owl.autoplay');
  });

  $('.owl-prev').click(function() { 
    var id=$('#screen-short-text').find('.active').children().attr('id');
    id=id.replace('d','');
    trigger_id=id;
    $('#screen-short-img').trigger('to.owl.carousel', trigger_id);
    $('#screen-short-text').trigger('stop.owl.autoplay');
    $('#screen-short-img').trigger('stop.owl.autoplay');
  });     
}



/*===============================
= 3. AOS ANIMATE                =
=================================*/
$(window).on('load', function() {
  AOS.init({
    offset: 200,
    duration: 600,
    mobile: 'false',
    once: 'false',
  });
});


/*===============================
= 4. Features                   =
=================================*/
$(document).ready(function(){
 $(".media-body-text").hover(function(){
   var a = $(this).data('id');
   $('.tab-pane').removeClass('in active');
   $('#'+a).addClass('in active');
 });
});