<?php
/**
 * Plugin Name: MWB Theme Customization
 * Description: This plugin is basically to modify the default woocommerce layout and settings.
 * Version: 1.0.1
 * Author: Makewebbetter
 * Author URI: https://makewebbetter.com
 * Text Domain: mwb-theme-customization
 */

if(!defined('ABSPATH')){
  exit; //Exit if accessed directly
}

$activated = true;

if (function_exists('is_multisite') && is_multisite())
{
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) )
  {
    $activated = false;
  }
}
else
{
  if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
  {
    $activated = false;
  }
}
//$activated = false;
/**
 * Check if WooCommerce is active
 **/
if ($activated) 
{
  define('MWB_DIRPATH', plugin_dir_path( __FILE__ ));
	define('MWB_URL', plugin_dir_url( __FILE__ ));
	define('MWB_HOME_URL', home_url());

  register_nav_menu( 'mobile-menu' , 'Mobile Menu' );

  include_once MWB_DIRPATH.'includes/class-mwb-product-customization.php';
  include_once MWB_DIRPATH.'includes/class-mwb-woocommerce-microservice.php';
  include_once MWB_DIRPATH.'includes/Mobile_Detect.php';

  include_once MWB_DIRPATH.'paypal-express/mwb-paypal-express-checkout.php'; 
  include_once MWB_DIRPATH.'paypal-express/includes/paypal-subscription-table.php';
  include_once MWB_DIRPATH.'decoration/mwb-decoration.php'; 
}

/**
   * Output a list of variation attributes for use in the cart forms.
   *
   * @param array $args Arguments.
   * @since 2.4.0
   */
  if(!function_exists("wc_dropdown_variation_attribute_options"))
  {  

  function wc_dropdown_variation_attribute_options( $args = array() ) {
    $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
      'options'          => false,
      'attribute'        => false,
      'product'          => false,
      'selected'         => false,
      'name'             => '',
      'id'               => '',
      'class'            => '',
      'show_option_none' => __( 'Choose an option', 'woocommerce' ),
    ) );

    // Get selected value.
    if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
      $selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
      $args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( urldecode( wp_unslash( $_REQUEST[ $selected_key ] ) ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
    }

    $options               = $args['options'];
    $product               = $args['product'];
    $attribute             = $args['attribute'];
    $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
    $id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
    $class                 = $args['class'];
    $show_option_none      = (bool) $args['show_option_none'];
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
      $attributes = $product->get_variation_attributes();
      $options    = $attributes[ $attribute ];
    }

    $html  = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
    //$html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

    if ( ! empty( $options ) ) {
      if ( $product && taxonomy_exists( $attribute ) ) {
        // Get terms if this is a taxonomy - ordered. We need the names too.
        $terms = wc_get_product_terms( $product->get_id(), $attribute, array(
          'fields' => 'all',
        ) );

        foreach ( $terms as $term ) {
          if ( in_array( $term->slug, $options, true ) ) {
            $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
          }
        }
      } else {
        foreach ( $options as $option ) {
          // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
          $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
          $html    .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
        }
      }
    }

    $html .= '</select>';

    echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args ); // WPCS: XSS ok.
  }

}

    if ( !function_exists( 'wp_password_change_notification' ) ) {
        function wp_password_change_notification() {}
    }

  add_filter( 'wp_nav_menu_args', 'mwb_wp_nav_menu_args',10, 1);

  function mwb_wp_nav_menu_args($args)
  {
    $detect = new Mobile_Detect;
    if ( $detect->isMobile() ) {
      $args['theme_location'] = "amp-menu";
    }
     
    // Any tablet device.
    if( $detect->isTablet() ){
      $args['theme_location'] = "amp-menu";
    }
    return $args;
  }

  add_action( 'business_page_header', 'mwb_genesis_do_breadcrumbs', 5);

/**
 * Display Breadcrumbs above the Loop. Concedes priority to popular breadcrumb
 * plugins.
 *
 * @since 1.0.0
 *
 * @return void Return early if Genesis settings dictate that no breadcrumbs should show in current context.
 */
function mwb_genesis_do_breadcrumbs() {

  if (( is_singular( 'post' ) && genesis_get_option( 'breadcrumb_single' ) )) {
    
    $breadcrumb_markup_open = sprintf( '<div %s>', genesis_attr( 'breadcrumb' ) );

    if ( function_exists( 'bcn_display' ) ) {
      echo $breadcrumb_markup_open;
      bcn_display();
      echo '</div>';
    } elseif ( function_exists( 'breadcrumbs' ) ) {
      breadcrumbs();
    } elseif ( function_exists( 'crumbs' ) ) {
      crumbs();
    } elseif ( class_exists( 'WPSEO_Breadcrumbs' ) && genesis_get_option( 'breadcrumbs-enable', 'wpseo_titles' ) ) {
      yoast_breadcrumb( $breadcrumb_markup_open, '</div>' );
    } elseif ( function_exists( 'yoast_breadcrumb' ) && ! class_exists( 'WPSEO_Breadcrumbs' ) ) {
      yoast_breadcrumb( $breadcrumb_markup_open, '</div>' );
    } else {
      genesis_breadcrumb();
    }

   }
}
?>