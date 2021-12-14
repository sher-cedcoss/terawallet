 jQuery(document).ready(function() {
    
        jQuery('.giftware-woocommerce-gift-card-carousel').owlCarousel({
            loop:true,
            margin:10,
            nav: true,
            dots: false,
            autoplay:true,
            autoplayTimeout:3000,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:4
                }
            }
        });
        jQuery( ".owl-prev").html('<i class="fa fa-angle-left"></i>');
        jQuery( ".owl-next").html('<i class="fa fa-angle-right"></i>');
    });
