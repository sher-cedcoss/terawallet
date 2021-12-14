jQuery(document).ready( function() {

    var d = jQuery('.vardata').attr('data-var');
    console.log( d );

    jQuery(".mwb_hubspot_services__tabs-list li a").click(function(e){
       e.preventDefault();
    });

    jQuery(".mwb_hubspot_services__tabs-list li").click(function(){
       var tabid = jQuery(this).find("a").attr("href");
     jQuery(".mwb_hubspot_services__tabs-list li,.mwb_hubspot_services__tabs div.mwb_hubspot_services__tab").removeClass("active");   // removing active class from tab

     jQuery(".mwb_hubspot_services__tab").hide();   // hiding open tab
     jQuery(tabid).show();    // show tab
     jQuery(this).addClass("active"); //  adding active class to clicked tab

    });
    /* fix resources tab */
    jQuery(window).scroll(function() {
        if(jQuery(".mwb_hubspot_services__tabs").length > 0){
            var header = jQuery(".mwb_hubspot_services__tabs");
            var section_height = header.offset().top;

            var scroll = jQuery(window).scrollTop();
            if (scroll >= section_height) {
                header.addClass("mwb_hubspot_services__tabs-fixed");
            } else {
                header.removeClass("mwb_hubspot_services__tabs-fixed");
            }
        }
    });
      /* review section tab open js */
    jQuery(".mwb-product__view-more").click(function(){
       var tabid = jQuery(this).find("a").attr("href");
     jQuery(".mwb_hubspot_services__tabs-list li,.mwb_hubspot_services__tabs div.mwb_hubspot_services__tab").removeClass("active");   // removing active class from tab

     jQuery(".mwb_hubspot_services__tab").hide();   // hiding open tab
     jQuery(tabid).show();    // show tab
     jQuery('.mwb_hubspot_services__review-tab').addClass("active"); //  adding active class to clicked tab

    });

    // jQuery('.mwb-owl-benefit-slider').owlCarousel({
    //     loop:true,
    //     margin:0,
    //     nav:true,
    //      navText: ["<img src='https://makewebbetter.com/wp-content/uploads/2019/08/left-arrow.png'>","<img src='https://makewebbetter.com/wp-content/uploads/2019/08/right-arrow.png'>"],
    //     dots:false,
    //     responsive:{
    //         0:{
    //             items:1,
    //         },
    //         767:{
    //             items:3,
    //             center: true,
    //             nav: false,
    //         },
    //         1000:{
    //             items:3
    //         }
    //     }
    // });
    // jQuery('.mwb-owl-mb-slider').owlCarousel({
    //     loop:true,
    //     margin:20,
    //     nav:true,
    //     navText: ["<img src='https://makewebbetter.com/wp-content/uploads/2019/08/left-arrow.png'>","<img src='https://makewebbetter.com/wp-content/uploads/2019/08/right-arrow.png'>"],
    //     dots:false,
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         580:{
    //             items:2
    //         },
    //         767:{
    //             items:2
    //         },
    //         1000:{
    //             items:3
    //         }
    //     }
    // });
    // jQuery('.mwb-owl-mb-slider-tab').owlCarousel({
    //     loop:true,
    //     margin:20,
    //     nav:true,
    //     navText: ["<img src='https://makewebbetter.com/wp-content/uploads/2019/08/left-arrow.png'>","<img src='https://makewebbetter.com/wp-content/uploads/2019/08/right-arrow.png'>"],
    //     dots:false,
    //     responsive:{
    //         0:{
    //             items:2
    //         },
    //         767:{
    //             items:2
    //         },
    //         1000:{
    //             items:3
    //         }
    //     }
    // });


    jQuery(document).on('click','.mwb_variation_btn_form_submit', function(){
        var location = jQuery(this).attr('data-location');
        jQuery(document).find('#mwb_variations_form').attr('action',location);
        jQuery(document).find('#mwb_variations_form').submit();
    });

    jQuery(document).on('click','.mwb-product-woo__header-rating-wrap', function(){
        jQuery('.mwb-product-woo__header-rating-details-wrap').show();
        jQuery("body").click(function(){
            jQuery(".mwb-product-woo__header-rating-details-wrap").hide();
        });
    });


        jQuery('.mwb-product-woo__plan-tab ul li').click(function(){
          jQuery('.mwb-product-woo__plan-tab ul li').removeClass('active');
          jQuery(this).addClass('active');
          jQuery('.mwb-product-woo__rate').hide();

          var activeTab = jQuery(this).find('a').attr('href');
          jQuery(activeTab).show();
          return false;
        });

        jQuery('.mwb-product-woo__accordian-heading').click(function(e){
        e.preventDefault();
        if (!jQuery(this).hasClass('active')) {
            jQuery('.mwb-product-woo__accordian-heading').removeClass('active');
            jQuery('.mwb-product-woo__accordion-content').slideUp(500);
            jQuery(this).addClass('active');
            jQuery(this).next('.mwb-product-woo__accordion-content').slideDown(500);
        }
        else if (jQuery(this).hasClass('active')) {
            jQuery(this).removeClass('active');
            jQuery(this).next('.mwb-product-woo__accordion-content').slideUp(500);
        }
    });


});