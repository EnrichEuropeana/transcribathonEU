<?php
defined('ABSPATH') or die('Restricted access');
?><?php

class acyWoocommerce extends acyHook
{
    public function __construct()
    {
        add_filter('woocommerce_checkout_fields', [$this, 'addSubsciptionFieldWC']);
        add_action('woocommerce_checkout_order_processed', [$this, 'subscribeUserOnCheckoutWC'], 15, 3);
    }

    public function subscribeUserOnCheckoutWC($order_id, $posted_data, $order)
    {
        if (!$this->loadAcyLibrary()) return;

        $config = acym_config();
        if (!$config->get('woocommerce_sub', 0)) return;

        if (empty($posted_data['billing_email']) || empty($posted_data['acym_regacy_sub'])) return;


        $userClass = acym_get('class.user');

        $user = $userClass->getOneByEmail($posted_data['billing_email']);
        if (empty($user)) {
            $user = new stdClass();
            $user->email = $posted_data['billing_email'];
            $userName = [];
            if (!empty($posted_data['billing_first_name'])) $userName[] = $posted_data['billing_first_name'];
            if (!empty($posted_data['billing_last_name'])) $userName[] = $posted_data['billing_last_name'];
            if (!empty($userName)) $user->name = implode(' ', $userName);
            $user->source = 'woocommerce';
            $user->id = $userClass->save($user);
        }

        if (empty($user->id)) return;

        $listsToSubscribe = $config->get('woocommerce_autolists', '');
        if (empty($listsToSubscribe)) return;
        $hiddenLists = explode(',', $listsToSubscribe);
        $userClass->subscribe($user->id, $hiddenLists);

        return;
    }

    public function addSubsciptionFieldWC($fields)
    {
        if (!$this->loadAcyLibrary()) return $fields;
        $config = acym_config();
        if (!$config->get('woocommerce_sub', 0)) return $fields;

        $displayTxt = !empty($config->get('woocommerce_text')) ? $config->get('woocommerce_text') : acym_translation('ACYM_SUBSCRIBE_NEWSLETTER');
        $acyfield = [
            'type' => 'checkbox',
            'label' => $displayTxt,
            'required' => false,
            'class' => ['form-row-wide'],
        ];
        $fields['billing']['acym_regacy_sub'] = $acyfield;

        return $fields;
    }
}

$acyWoocommerce = new acyWoocommerce();

