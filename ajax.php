<?php

include_once(dirname(__FILE__) . '/../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../init.php');

if (Tools::getValue('ajax') == 1) {

    $pd_quote_customer_email        = pSQL(trim(Tools::getValue('pd_quote_customer_email', '')));
    $pd_quote_customer_phone        = pSQL(trim(Tools::getValue('pd_quote_customer_phone', '')));
    $pd_quote_customer_description  = pSQL(trim(Tools::getValue('pd_quote_customer_description', '')));

    $id_product                     = (int)trim(Tools::getValue('pd_quote_id_product', ''));
    $product                        = new Product($id_product);
    $pd_quote                       = Module::getInstanceByName('pd_quote');
    $success                        = 0;

    if (!empty($pd_quote_customer_email)) {

        $template = 'pd_quote';
        $template_vars = array(
            '{email}'           => $pd_quote_customer_email,
            '{phone}'           => $pd_quote_customer_phone,
            '{description}'     => $pd_quote_customer_description,
            '{product_name}'    => $product->reference.' - '.Product::getProductName($id_product),
            '{product_link}'    => Context::getContext()->link->getProductLink($id_product),
        );

        $email = Configuration::get('PD_QUOTE_EMAIL');

        $to = array(
            $email,
        );

        $send = Mail::Send(
            Configuration::get('PS_LANG_DEFAULT'),
            $template,
            $pd_quote->l('Request a quote', 'ajax'),
            $template_vars,
            $to,
            null,
            $pd_quote_customer_email,
            Configuration::get('PS_SHOP_DOMAIN'),
            null,
            null,
            dirname(__FILE__).'/mails/'
        );

        if ($send) {
            $success = 1;
        }
    }

    if ($success) {
        die(Tools::jsonEncode(array('success' => 1)));
    } else {
        die(Tools::jsonEncode(array('success' => 1)));
    }
}
