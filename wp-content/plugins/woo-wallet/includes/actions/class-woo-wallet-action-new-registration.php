<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Action_New_Registration extends WooWalletAction
{

    public function __construct()
    {
        $this->id = 'new_registration';
        $this->action_title = __('New user registration', 'woo-wallet');
        $this->description = __('Set credit upon new user registration', 'woo-wallet');
        $this->init_form_fields();
        $this->init_settings();
        // Actions.
        add_action('user_register', array($this, 'woo_wallet_new_user_registration_credit'));
    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields()
    {

        $this->form_fields = apply_filters('woo_wallet_action_new_registration_form_fields', array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'woo-wallet'),
                'type'    => 'checkbox',
                'label'   => __('Enable auto credit upon first Purchase on user registration', 'woo-wallet'),      // first Purchase on
                'default' => 'no',
            ),
            'amount' => array(
                'title'       => __('Amount (in %)', 'woo-wallet'),         // (in %)
                'type'        => 'price',
                'description' => __('Enter amount which will be credited to the user wallet after registration.', 'woo-wallet'),
                'default'     => '10',
                'desc_tip'    => true
            ),
            'description' => array(
                'title'       => __('Description', 'woo-wallet'),
                'type'        => 'textarea',
                'description' => __('Wallet transaction description that will display as transaction note.', 'woo-wallet'),
                'default'     => __('Balance credited for becoming a member.', 'woo-wallet'),
                'desc_tip'    => true,
            )
        ));
    }

    public function woo_wallet_new_user_registration_credit($user_id)
    {
        if ($this->is_enabled() && $this->settings['amount'] && apply_filters('woo_wallet_new_user_registration_credit', true, $user_id)) {
            $amount = apply_filters('woo_wallet_new_user_registration_credit_amount', $this->settings['amount'], $user_id);
            // ---------------------------------------------------Customised-------------------------------------------------------------------------
            $subtotal = WC()->cart->subtotal;
            $refund_percent = ($subtotal * $amount) / 100;
            $transaction_id = woo_wallet()->wallet->credit($user_id, $refund_percent, sanitize_textarea_field($this->settings['description']));
            // --------------------------------------------------/Customised-------------------------------------------------------------------------
            if ($transaction_id) {
                do_action('woo_wallet_action_new_registration_credited', $transaction_id, $user_id, $this);
            }
        }
    }
}
