<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       cedcoss.com
 * @since      1.0.0
 *
 * @package    Shippin_address
 * @subpackage Shippin_address/admin/partials
 */
?>
<p id="map_notice" style="display: none;"><?php _e('Note: Green Marker indicates Shipping address and Red Marker indicates Store address','mwb-woocommerce-shipping-map'); ?></p>
<div id="map" style="display: none;"></div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->