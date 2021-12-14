<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Woocommerce_Shipping_Map
 * @subpackage Mwb_Woocommerce_Shipping_Map/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="mwb-woocommerce-shipping-map-license-sec">

	<h3><?php _e('Enter your License', 'mwb-woocommerce-shipping-map' ) ?></h3>

    <p>
    	<?php _e('This is the License Activation Panel. After purchasing extension from ', 'mwb-woocommerce-shipping-map' ); ?>
    	<span>
            <a href="https://makewebbetter.com/" target="_blank" ><?php _e('MakeWebBetter',  'mwb-woocommerce-shipping-map' ); ?></a>
        </span>&nbsp;

        <?php _e('you will get the purchase code of this extension. Please verify your purchase below so that you can use the features of this plugin.', 'mwb-woocommerce-shipping-map' ); ?>
    </p>

	<form id="mwb-woocommerce-shipping-map-license-form">

	    <label><b><?php _e('Purchase Code : ', 'mwb-woocommerce-shipping-map' )?></b></label>

	    <input type="text" id="mwb-woocommerce-shipping-map-license-key" placeholder="<?php _e('Enter your code here.', 'mwb-woocommerce-shipping-map' )?>" required="">

	    <div id="mwb-woocommerce-shipping-map-ajax-loading-gif"><img src="<?php echo 'images/spinner.gif'; ?>"></div>
	    
	    <p id="mwb-woocommerce-shipping-map-license-activation-status"></p>

	    <button type="submit" class="button-primary"  id="mwb-woocommerce-shipping-map-license-activate"><?php _e('Activate', 'mwb-woocommerce-shipping-map' )?></button>
	    
	    <?php wp_nonce_field( 'mwb-woocommerce-shipping-map-license-nonce-action', 'mwb-woocommerce-shipping-map-license-nonce' ); ?>

	</form>

</div>