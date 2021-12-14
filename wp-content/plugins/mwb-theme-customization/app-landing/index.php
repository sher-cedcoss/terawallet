<?php 
get_header();
  define('MWB_DIRPATH', plugin_dir_path( __FILE__ ));
  define('MWB_URL', plugin_dir_url( __FILE__ ));
  define('MWB_HOME_URL', home_url());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Appro App Landing Page Template is a professional easy to use Appro App Landing Page template built on bootstrap">
  <meta name="keywords" content="Appro App Landing Page Template HTML,CSS,JavaScript">
  <meta name="author" content="makewebbetter">
  <title>MageNative WooCommerce App | MakeWebBetter</title>
<!-- Mobile Specific Metas
  ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Favicon -->
  <?php wp_head();?>
  <link rel="shortcut icon" type="image/icon" href="https://makewebbetter.com/wp-content/uploads/2017/10/cropped-mwb-512-32x32.png"/>
<!-- CSS
  ================================================== -->
  <!-- Bootstrap -->
  <link href="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
  <!-- themify-icons -->
  <link href="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/themify/css/themify-icons.css" rel="stylesheet" type="text/css" />
  <!-- font-awesome -->
  <link href="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
  <!-- animate -->
  <link href="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/css/animate.css" rel="stylesheet" type="text/css" />
  <!-- owl carousel -->
  <link href="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/owl.carousel/css/owl.carousel.css" rel="stylesheet" type="text/css" />
  <!-- animate_effect -->
  <link href="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/aos/css/aos.css" rel="stylesheet" type="text/css" />
  <!-- style -->
  <link href="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/css/style.css" rel="stylesheet" type="text/css" />
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body id="section01" data-spy="scroll" data-target=".navbar" data-offset="50" class="<?php if(is_user_logged_in()){ echo "logged-in"; } ?>">
  <main id="main">

  <?php
  the_content();
  ?>

<!--============================
=            BANNER            =
=============================-->

<section id="section01" class="banner">
  <div class="container">
    <div id="homeslide" class="owl-carousel owl-theme">
      <div class="row">
        <div class="item">
          <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            <div class="banner__widget-text mg-top-100">
              <h3 class="fs-45 white-color fw-700 uppercase">Create your own stunning App.</h3>
              <p class="mg-top-20 mg-bottom-20 white-color fs-20">Give height to your sale with MageNative WooCommerce App Feature such as inclusive marketing, great user experience, smooth Navigation etc.</p>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/magenative-mobile-app" class="btn-transparent">Get App</a>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/demo" class="btn-white">Live Demo</a>
            </div>
          </div>
          <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 mobile-display-none">
            <div class="banner__widget-img mg-top-50 bounce-up">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Header/iPhone-Header.png" class="img-responsive" alt="Stunning App">
            </div>
          </div>
        </div><!-- item 1 -->
      </div>

      <div class="row">
        <div class="item">
          <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 mobile-display-none">
            <div class="banner__widget-img mg-top-50 bounce-up">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Header/boost_bussiness.png" class="img-responsive" alt="Boost Your Business">
            </div>
          </div>
          <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            <div class="banner__widget-text mg-top-100">
              <h3 class="fs-45 white-color fw-700 uppercase">Maximize Your Customer Outreach, Boost Your Business.</h3>
              <p class="mg-top-20 mg-bottom-20 white-color fs-20">The MageNative app is designed to boost revenues with multiple payment gateways,multi-filter &amp; sorting, transparent pricing, faster checkout page feature.</p>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/magenative-mobile-app" class="btn-transparent">Get App</a>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/demo" class="btn-white">Live Demo</a>             
            </div>
          </div>
        </div>
      </div><!-- item 2-->

      <!---item3-->

      <div class="row">
        <div class="item">
          <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            <div class="banner__widget-text mg-top-100">
              <h3 class="fs-45 white-color fw-700 uppercase">Rapid,Reliable,Great Achievements &amp; Performance.</h3>
              <p class="mg-top-20 mg-bottom-20 white-color fs-20">Engage or interact your customer towards the mobile application with multiple feature like unlimited push notification, social login, advance search feature and many more.</p>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/magenative-mobile-app" class="btn-transparent">Get App</a>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/demo" class="btn-white">Live Demo</a>
            </div>
          </div>
          <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 mobile-display-none">
            <div class="banner__widget-img mg-top-50 bounce-up">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Header/fast_reiable.png" class="img-responsive" alt="Rapid, Reliable">
            </div>
          </div>
        </div><!-- item 1 -->
      </div><!-- item 3-->
    </div>
  </div>
</section>

<!--====  End of BANNER  ====-->

<!--===============================
=            WHYCHOOSE            =
================================-->

<section id="section02" class="whychoose site-content">
  <div class="container">
    <div class="section-title text-center mg-bottom-40">
      <h3 class="fs-30 site-common-color mg-bottom-20">Why Choose MAGENATIVE ? </h3>
      <p>MageNative Mobile app provides Mobile solutions for E-Commerce merchandisers to create a fully Customizable Native App and enhance their businesses. Increase sale with multiple features like great user experience, smooth navigation, inclusive marketing etc.</p>
    </div>
    <div class="section-wrap">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mob-full">
          <div class="whychoose__widget text-center mg-bottom-40">
            <div class="whychoose__icon">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/03.png" alt="icon01" class="img-responsive">
            </div>
            <div class="whychoose__text mg-top-20">
              <h4>EXCLUSIVE DESIGN</h4>
              <p>Mobile apps smoothly sinks with your side and provide modern rich design.</p>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/features">read more</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mob-full">
          <div class="whychoose__widget text-center mg-bottom-40">
            <div class="whychoose__icon">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/02.png" alt="icon02" class="img-responsive">
            </div>
            <div class="whychoose__text mg-top-20">
              <h4>REAL TIME</h4>
              <p>The execution of data in a short time period, providing instantaneous output and save valuable time of user.</p>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/features">read more</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mob-full">
          <div class="whychoose__widget text-center mg-bottom-40">
            <div class="whychoose__icon">
              <object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/12.svg"></object>
            </div>
            <div class="whychoose__text mg-top-20">
              <h4>SECURE DATA</h4>
              <p>Data is safe and secure and speed of app will be best with header hash key or API key.</p>
              <a href="https://magenative.cedcommerce.com/woocommerce-app/features">read more</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!--====  End of WHYCHOOSE  ====-->



<!--================================
=            SCREENSHOT            =
=================================-->

<section id="section03" class="screenshort-slide site-content">
  <div class="container">
    <div class="section-title text-center mg-bottom-40">
      <h3 class="fs-30 site-common-color mg-bottom-20">Main Feature </h3>
      <p>MageNative WooCommerce App packs with some powerful feature which help you to take your business to next level.</p>
    </div>

    <div class="section-wrap">
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          <div id="screen-short-text" class="owl-carousel owl-theme">
            <div class="item data-slide" id="d0">
              <h4>Category Listing</h4>
              <p> Magenative WooCommerce app provides you to categorize your products into several different categories as per the cart preference of your buyer persona. The customer can select their product from a list of different product categories such as main category, sub-category etc.</p>
              <ul>
                <li>The customer can list out their products as per their requirement through Main Category like clothes, shoes, watch etc.</li>
                <li>Customers can further list out their products into sub-category like clothes:- men, women cloth and further categorized products it as per your preference.</li>
                <li>The products will be filtered according to their needs at the same point of time.</li>
              </ul>
            </div><!-- item 1 -->
            <div class="item data-slide" id="d1">
              <h4>Push Notification</h4>
              <p> Magenative WooCommerce App supports Push notifications that is very much beneficial for the customer as well as for the admin. The customer can be benefitted by getting an informative notification about the launch of new products, deals &amp; sale, festive offers, etc.</p>
              <ul>
                <li>With unlimited push notification engage your customers to your mobile store.</li>
                <li>Admin can send different types of push notifications like- Product, category, others.</li>
                <li>Products: Send the promotional notification regarding the product. For example- starting with special price, deals &amp; Sale, Auctions etc.</li>
                <li>Categories: If any sale or deals will be set at the category level then they can notify their customers.</li>
                <li>Send unlimited emails to customers through push notification.</li>
                
              </ul>
            </div><!-- item 1 -->
            <div class="item data-slide" id="d2">
              <h4>Shopping Cart Items</h4>
              <p>The MageNative WooCommerce app allows you to store all the things that you have labeled and products in your cart will be listed individually. You can manage them accordingly without returning back to the individual product category.</p>
              <ul>
                <li>A shopping cart allows you to store all the things that you have earmarked for purchasing.</li>
                <li>The shopping cart allows you to save the items you intend to buy.</li>
                <li>You can increase the quantity or remove the items from the cart.</li>
                <li>The listing of products that you wants to buy at checkout.</li>               
              </ul>
            </div><!-- item 1 -->
            <div class="item data-slide" id="d3">
              <h4>Multiple Payment Gateway</h4>
              <p>With the increase in globalization in the market, the need of having Online Payment Gateway has increased to a greater extent. Now with MageNative WooCommerce App, increase the capacity of your business with providing you all payment gateway support.</p>
              <ul>
                <li>Increase the scope of business with MageNative WooCommerce app.</li>
                <li>The app supports all the major e-commerce payment gateways.</li>
                <li>Payment methods like  Cheque, Bank Transfer, Cash On Delivery, PayU, Paypal Standard and many more.</li>
                <li>The app provides fast, easy and convenient payment to the user.</li>             
              </ul>
            </div><!-- item 1 -->
            <div class="item data-slide" id="d4">
              <h4>Wish List</h4>
              <p> MageNative WooCommerce app provides a wishlist feature, your customers can browse through your store and add items to their account's profile. This function itself serves as a way for your customers to select and track their desired items for purchase at a later date.</p>
              <ul>
                <li>Registered user can create a wish list, signifying interest without immediate intent to purchase.</li>
                <li>Customers to add the product or item on their wishlist and can manage the products individually.</li>
                <li>Allows User to select and track their desired items for purchase at a later date.</li>
                <li>It is an effective way to reduce shopping cart abandonment and fulfill sales from customers who showed intent but didn't end up purchasing.</li>

              </ul>
            </div><!-- item 1 -->
          </div> 
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
         <!--  <div class="mobile-fram-slide">
            <img src="assets/images/400x550/mobile-fram.png" alt="mobile-fram">
          </div> -->
          <div id="screen-short-img" class="owl-carousel owl-theme">
            <div class="item">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Screenshots/category.png" class="img-responsive" alt="Category Listing">
            </div><!-- item 1 -->
            <div class="item">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Screenshots/push-notification.png" class="img-responsive" alt="Push Notification">
            </div><!-- item 2 -->
            <div class="item">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Screenshots/Shipping-Cart.png" class="img-responsive" alt="Shopping Cart Items">
            </div><!-- item 3 -->
            <div class="item">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Screenshots/Payment-Method-Frame.png" class="img-responsive" alt="Multiple Payment Gateway">
            </div><!-- item 4 -->
            <div class="item">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Screenshots/Wishlist-Frame.png" class="img-responsive" alt="Wish List">
            </div><!-- item 5 -->
          </div>  
        </div>
      </div>
    </div>
  </div>
</section>

<!--====  End of SCREENSHORT  ====-->

<!--======================================
=            SPECIAL FEATURES            =
=======================================-->
<section id="section04" class="features site-content">
  <div class="container">
    <div class="section-title text-center mg-bottom-40">
      <h3 class="fs-30 site-common-color mg-bottom-20"> Additional Features</h3>
      <p> The apps have some extra credential feature through which expand your customer outreach and raise interest.</p>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="features__widget features-right-icon-text">
          <a href="#" class="media-body-text" data-id="feature1"><span class="media-body-img mob-show"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/08.svg"></object></span><div class="media-body-text-right"><h4>Products Types</h4><p>Supports  products like simple, downloadable and variable products.</p></div><span class="media-body-img"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/08.svg"></object></span></a><a href="#" class="media-body-text" data-id="feature2"><span class="media-body-img mob-show"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/11.svg"></object></span><div class="media-body-text-right"><h4>Multilingual and RTL Support</h4><p>Display your store in various languages and support also RTL languages  . </p></div><span class="media-body-img"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/11.svg"></object></span></a><a href="#" class="media-body-text" data-id="feature3"><span class="media-body-img mob-show"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/10.svg"></object></span><div class="media-body-text-right"><h4>QR Scanner:</h4><p>Supports QRcode,scan the barcode of any product and search it at your e-commerce store.</p></div><span class="media-body-img"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/10.svg"></object></span></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile-display-none-hover">
        <div class="features__widget text-center">
          <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/400x550/mobile-fram.png" class="img-responsive" alt="mobile-fram">
          <div class="screen_image tab-content">
            <div id="feature1" class="tab-pane fade in active">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Features/1.jpg" class="img-responsive" alt="Products Types">
            </div>
            <div id="feature2" class="tab-pane fade">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Features/2.jpg" class="img-responsive" alt="Multilingual and RTL Support">
            </div>
            <div id="feature3" class="tab-pane fade">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Features/3.jpg" class="img-responsive" alt="QR Scanner">
            </div>
            <div id="feature4" class="tab-pane fade">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Features/4.jpg" class="img-responsive" alt="Social Login">
            </div>
            <div id="feature5" class="tab-pane fade">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Features/5.jpg" class="img-responsive" alt="Product Filter">
            </div>
            <div id="feature6" class="tab-pane fade">
              <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/WooCommerce/Features/6.jpg" class="img-responsive" alt="Profile">
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="features__widget features-left-icon-text">
          <a href="#" class="media-body-text" data-id="feature4">
            <span class="media-body-img">
              <object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/06.svg"></object>
            </span>
            <div class="media-body-text-right">
              <h4>Social Login</h4>
              <p>Provides social login feature, support social accounts like Facebook, Google+ ,Twitter etc.</p>
            </div>
          </a>
          <a href="#" class="media-body-text" data-id="feature5">
            <span class="media-body-img">
              <object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/04.svg"></object>
            </span>
            <div class="media-body-text-right">
              <h4>Product Filter</h4>
              <p>Sort your products  with respect to Brand, price, color, size, cost and category</p>
            </div>
          </a>
          <a href="#" class="media-body-text" data-id="feature6">
            <span class="media-body-img">
              <object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/09.svg"></object>
            </span>
            <div class="media-body-text-right">
              <h4>Profile</h4>
              <p>Create your profile just filling some  basic information like email ID and update them later.</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!--====  End of SPECIAL FEATURES  ====-->




<!--=====================================
=         Section data-analytics        =
======================================-->

<section id="section05" class="data-analytics site-content">
  <div class="container">
    <div class="section-title text-center mg-bottom-40">
      <h3 class="fs-30 site-common-color mg-bottom-20">frequently asked questions</h3>
      <p>Know more with our wide range of FAQ which satiate your knowing with best of answers here!</p>
    </div>
    <div class="section-wrap">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="data-analytics__widget mg-top-50">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/14.svg"></object>Can I test the app before finally publishing it?</a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">Definitely, you can test the app before publishing it at respective stores.</div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/01.svg"></object>Which versions of Android and iOS does MageNative app support?</a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">The MageNative app supports:
                      Android: Version 4.4 and beyond
                      iOS: Version 10 and beyond</div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingfour">
                      <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/03.svg"></object>How much will it cost me for customizing app further? </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfour">
                      <div class="panel-body">We almost cover all your required features but for any further customizations than the features provided at the time of purchase would cost $25/per hour.</div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingfive">
                      <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false" aria-controls="collapsefour"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/07.svg"></object>How long will it take for my app to be approved on App Store and PlayStore? </a>
                      </h4>
                    </div>
                    <div id="collapsefour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfive">
                      <div class="panel-body">Once the app is developed, it can be submitted to the Play Store and the App Store. Following its submission to the stores, it will be reviewed by Google and Apple, and then published.</div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingsix">
                      <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefive" aria-expanded="false" aria-controls="collapsefive"><object type="image/svg+xml" data="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/icon/13.svg"></object>Is there 24/7 customer support? </a>
                      </h4>
                    </div>
                    <div id="collapsefive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingsix">
                      <div class="panel-body">We are provideing 24/7 support Over WhatsApp,skype ,Call and Mail
                      we are always here to slove you issue .
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="data-analytics__widget text-center">
                <img src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/images/450x700/02.png" class="img-responsive" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--====  End of Section data-analytics  ====-->

<!--===================================
=            PRICING TABLE            =
====================================-->

<section id="section06" class="pricing-table-mobile-app site-content">
  <div class="container">
    <div class="section-title text-center mg-bottom-40">
      <h3 class="fs-30 site-common-color mg-bottom-20">our pricing</h3>
      <p>Most such devices are sold with several apps bundled as pre-installed software, such as a web browser, email client, calendar, mapping program.</p>
    </div>
    <div class="section-wrap">
      <div class="row flex-wrap">        
        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 flex-div">
          <div class="pricing__widget">
            <div class="pricing__widget-header">
              <h2>Basic App </h2>
              <span class="price-cril">
                <small>$</small>
                <span>149</span>
              </span>
              <h3 class="packeg_typ">Lifetime Validity </h3>
            </div>
            <div class="pricing__widget-plan-list">
              <ul>
                <li>Push Notification </li>
                <li>Wishlist Support</li>
                <li>Get all Listed Features</li>
                <li>Get both Android &amp; iOS App</li>
                <li>Support all payment method </li>               
                <li>Support Simple and Variable Products</li>
                <li>Support &amp; Upgrade Free(3 Month) </li>
                 <li>Get Android and iOS Source Code ( <strong> + $499</strong>) </li>
                         
              </ul>
              <div class="price-btn">
                <a href="https://magenative.cedcommerce.com/woocommerce-app/magenative-mobile-app">buy now</a>
              </div>
            </div>
          </div>
        </div>
       
        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 flex-div">
          <div class="pricing__widget">
            <div class="pricing__widget-header">
              <h2>Enterprise App </h2>
              
              <span class="price-cril">               
                <small>Get</small>           
              </span>
              <h3 class="packeg_typ">Lifetime Validity</h3>
            </div>
            <div class="pricing__widget-plan-list">
              <ul>
                <li>Push Notification </li>      
                <li>Fully Customizable </li>                      
                <li>Get both Android &amp; iOS App</li>
                <li>Get Client App as well as Vender App </li>
                <li>Fully Compatible with Your Multivender<br> 
                Extension</li>  
                <li>Fully Support Dokan ,WC Vendors etc </li>                
                <li>Support &amp; Upgrade Free(3 Month) </li>
              </ul>
              <div class="price-btn">
             
              <button class="quote_popup" >Get Quote</button> 
                <!-- <a href="#"> Get Quote</a> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!--====  End of PRICING TABLE  ====-->


<!--==================================
=            DOWNLOAD APP            =
===================================-->

<section id="section07" class="app-download site-content">
  <div class="container">
    <div class="section-title text-center mg-bottom-40">
      <h3 class="fs-30 site-common-color mg-bottom-20">Get App Now </h3>
      <p>Download the App now to get the better experience of an online store with Magenative Woocommerce App </p>
    </div>
    <div class="section-wrap">
      <div class="app-btn">
        <div class="single-option">
          <a href="https://itunes.apple.com/us/app/magenative-app-for-woocommerce/id1355691033?ls=1&mt=8">
            <i class="fa fa-apple"></i>
            <div class="optino-txt">
              <span>Download App on </span>
              <h5>App Store</h5>
            </div>
          </a>
        </div>
        <div class="single-option">
          <a href="https://play.google.com/store/apps/details?id=com.magenative.wooapp&hl=en">
            <i class="fa fa-google"></i>
            <div class="optino-txt">
              <span>Download App on </span>
              <h5>Google Play store </h5>
            </div>
          </a>
        </div>
        <div class="single-option">
          <a href="https://magenative.cedcommerce.com/app-trial">
            <i class="fa fa-play"></i>
            <div class="optino-txt">
              <span>Get 30 days </span>
              <h5>Trial Now </h5>
            </div>
          </a>
        </div>
        <div class="single-option">
          <a href="https://magenative.cedcommerce.com/contacts">
            <i class="fa fa-envelope"></i>
            <div class="optino-txt">
              <span>Have any Query ?</span>
              <h5>Contact Us</h5>
            </div>
          </a>
        </div>       
      </div>
    </div>
  </div>
</section>  



<!-- CONTACT -->
</main>


<!-- JS
  ================================================== -->  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/bootstrap/js/bootstrap.js"></script>
  <!-- owl.carousel js -->
  <script src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/owl.carousel/js/owl.carousel.js"></script>
  <!-- aos -->
  <script src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/aos/js/aos.js"></script>
  <!-- SmoothScroll -->
  <script src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/smoothscroll/js/smoothscroll.min.js"></script>
  <!-- parallax-scroll -->
  <script src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/vendor/parallax/js/parallax-scroll.js"></script>
  <!-- custom-map -->
  <!-- <script src="assets/vendor/map/js/custom-map.js"></script> -->
  <!-- maps.googleapis -->
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmrxz1h-jvtVmyS5izFgHHjNiaFxce0-I"></script> -->
  <!-- custom js -->
  <script src="https://makewebbetter.com/wp-content/plugins/mwb-theme-customization/app-landing/assets/js/custom.js"></script>

<?php
get_footer();
?>