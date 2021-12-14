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
                'label'   => __('Enable auto credit upon user registration', 'woo-wallet'),
                'default' => 'no',
            ),
            'amount' => array(
                'title'       => __('Amount', 'woo-wallet'),
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
            ),
            array(
                'name' => 'is_enable_cashback_reward_program',
                'label' => __('Cashback Reward Program', 'woo-wallet'),
                'desc' => __('Run cashback reward program on your store', 'woo-wallet'),
                'type' => 'checkbox',
            ),
            array(
                'name' => 'process_cashback_status',
                'label' => __('Process cashback', 'woo-wallet'),
                'desc' => __('Select order status to process cashback', 'woo-wallet'),
                'type' => 'select',
                'options' => apply_filters('woo_wallet_process_cashback_status', array('pending' => __('Pending payment', 'woo-wallet'), 'on-hold' => __('On hold', 'woo-wallet'), 'processing' => __('Processing', 'woo-wallet'), 'completed' => __('Completed', 'woo-wallet'))),
                'default' => array('processing', 'completed'),
                'size' => 'regular-text wc-enhanced-select',
                'multiple' => true
            ),
            array(
                'name' => 'cashback_rule',
                'label' => __('Cashback Rule', 'woo-wallet'),
                'desc' => __('Select Cashback Rule cart or product wise', 'woo-wallet'),
                'type' => 'select',
                'options' => apply_filters('woo_wallet_cashback_rules', array('cart' => __('Cart wise', 'woo-wallet'), 'product' => __('Product wise', 'woo-wallet'), 'product_cat' => __('Product category wise', 'woo-wallet'))),
                'size' => 'regular-text wc-enhanced-select'
            ),
            array(
                'name' => 'cashback_type',
                'label' => __('Cashback type', 'woo-wallet'),
                'desc' => __('Select cashback type percentage or fixed', 'woo-wallet'),
                'type' => 'select',
                'options' => array('percent' => __('Percentage', 'woo-wallet'), 'fixed' => __('Fixed', 'woo-wallet')),
                'size' => 'regular-text wc-enhanced-select'
            ),
            array(
                'name' => 'cashback_amount',
                'label' => __('Cashback Amount', 'woo-wallet'),
                'desc' => __('Enter cashback amount', 'woo-wallet'),
                'type' => 'number',
                'step' => '0.01'
            ),
            array(
                'name' => 'min_cart_amount',
                'label' => __('Minimum Cart Amount', 'woo-wallet'),
                'desc' => __('Enter applicable minimum cart amount for cashback', 'woo-wallet'),
                'type' => 'number',
                'step' => '0.01'
            ),
            array(
                'name' => 'max_cashback_amount',
                'label' => __('Maximum Cashback Amount', 'woo-wallet'),
                'desc' => __('Enter maximum cashback amount', 'woo-wallet'),
                'type' => 'number',
                'step' => '0.01'
            ),
            array(
                'name' => 'allow_min_cashback',
                'label' => __('Allow Minimum cashback', 'woo-wallet'),
                'desc' => __('If checked minimum cashback amount will be applied on product category cashback calculation.', 'woo-wallet'),
                'type' => 'checkbox',
                'default' => 'on'
            ),
            array(
                'name' => 'is_enable_gateway_charge',
                'label' => __('Payment gateway charge', 'woo-wallet'),
                'desc' => __('Charge customer when they add balance to their wallet?', 'woo-wallet'),
                'type' => 'checkbox',
            )
        ));
    }

    public function woo_wallet_new_user_registration_credit($user_id)
    {
        if ($this->is_enabled() && $this->settings['amount'] && apply_filters('woo_wallet_new_user_registration_credit', true, $user_id)) {
            $amount = apply_filters('woo_wallet_new_user_registration_credit_amount', $this->settings['amount'], $user_id);
            $transaction_id = woo_wallet()->wallet->credit($user_id, $amount, sanitize_textarea_field($this->settings['description']));
            if ($transaction_id) {
                do_action('woo_wallet_action_new_registration_credited', $transaction_id, $user_id, $this);
            }
        }
    }
}
