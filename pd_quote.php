<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Pd_quote extends Module 
{
    public function __construct()
    {
        $this->name         = 'pd_quote';
        $this->tab          = 'front_office_features';
        $this->version      = '1.0.0';
        $this->bootstrap    = true;
        $this->author       = 'Prestademia';        
        $this->website      = "prestademia.com";


        parent::__construct();

        $this->displayName  = $this->l('Request quote');
        $this->description  = $this->l('Module to request quote on each product in the store using a modal form.');
        $this->psVersion    = round(_PS_VERSION_, 1);
        
        if (function_exists('curl_init') == false) {
            $this->warning  = $this->l('In order to use this module, activate cURL (PHP extension).');
        }
    }

    protected function getConfigurations()
    {
        $configurations = array(
            'PD_QUOTE_EMAIL' => '',
            'PD_QUOTE_BOX_WIDTH' => 230,
            'PD_QUOTE_BUTTON_COLOR' => '#ffffff',
            'PD_QUOTE_BUTTON_BACKGROUND' => '#2fb5d2',
            'PD_QUOTE_BUTTON_HOVER_BACKGROUND' => '#2592a9',
        );

        return $configurations;
    }

    public function install()
    {
        $configurations = $this->getConfigurations();

        foreach ($configurations as $name => $config) {
            Configuration::updateValue($name, $config);
        }

        return parent::install() &&
        $this->registerHook('displayHeader') &&
        $this->registerHook('displayProductAdditionalInfo') &&
        $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        $configurations = $this->getConfigurations();

        foreach (array_keys($configurations) as $config) {
            Configuration::deleteByName($config);
        }

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        $result = '';

        if ((bool)Tools::isSubmit('submitSettings')) {
            if (!$result = $this->preValidateForm()) {
                $output .= $this->postProcess();
                $output .= $this->displayConfirmation($this->l('Save all settings.'));
            } else {
                $output = $result;
                $output .= $this->renderTabForm();
            }
        }

        if (!$result) {
            $output .= $this->renderTabForm();
        }

        $out = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        return $out.$output;
    }

    protected function preValidateForm()
    {
        $errors = array();

        if (Tools::isEmpty(Tools::getValue('PD_QUOTE_EMAIL'))) {
            $errors[] = $this->l('Email is required.');
        } else {
            if (!Validate::isEmail(Tools::getValue('PD_QUOTE_EMAIL'))) {
                $errors[] = $this->l('Email format error');
            }
        }

        if (!Validate::isColor(Tools::getValue('PD_QUOTE_BUTTON_COLOR'))) {
            $errors[] = $this->l('"Color" format error.');
        } 
        
        if (!Validate::isColor(Tools::getValue('PD_QUOTE_BUTTON_BACKGROUND'))) {
            $errors[] = $this->l('"Background" format error.');
        }

        if (!Validate::isColor(Tools::getValue('PD_QUOTE_BUTTON_HOVER_BACKGROUND'))) {
            $errors[] = $this->l('"Background" format error.');
        }

        if (Tools::isEmpty(Tools::getValue('PD_QUOTE_BOX_WIDTH'))) {
            $errors[] = $this->l('Width is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('PD_QUOTE_BOX_WIDTH'))) {
                $errors[] = $this->l('Bad width format');
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFieldsValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function getConfigFieldsValues()
    {
        $fields = array();
        $configurations = $this->getConfigurations();

        foreach (array_keys($configurations) as $config) {
            $fields[$config] = Configuration::get($config);
        }

        return $fields;
    }

    protected function renderTabForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email:'),
                        'name' => 'PD_QUOTE_EMAIL',
                        'required' => true,
                        'col' => 2,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Button width:'),
                        'name' => 'PD_QUOTE_BOX_WIDTH',
                        'col' => 2,
                        'required' => true,
                        'suffix' => 'pixel',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Button color:'),
                        'name' => 'PD_QUOTE_BUTTON_COLOR',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Button background:'),
                        'name' => 'PD_QUOTE_BUTTON_BACKGROUND',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Button hover background:'),
                        'name' => 'PD_QUOTE_BUTTON_HOVER_BACKGROUND',
                    ),
                ),
                'submit' => array(
                   'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSettings';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function hookHeader()
    {
        
        Media::addJsDefL('pd_quote_url', $this->_path.'ajax.php');
        $this->context->controller->addJS($this->_path.'/views/js/pd_quote.js');
  

    }

    public function hookDisplayFooter()
    {
        $product        = new Product((int)Tools::getValue('id_product'));

        $this->context->smarty->assign(array(
            'pd_quote_id_product'            => (int)Tools::getValue('id_product'),
            'pd_quote_product_name'          => $product->name[$this->context->language->id],
            'pd_quote_customer_email'        => $this->context->cookie->pd_quote_customer_email,
            'pd_quote_customer_phone'        => $this->context->cookie->pd_quote_customer_phone,
            'pd_quote_customer_description'  => $this->context->cookie->pd_quote_customer_description,
        ));

        return $this->display(__FILE__, 'views/templates/hook/modal-quote.tpl');


        
    }

    public function hookDisplayProductAdditionalInfo()
    {
        $this->context->smarty->assign(array(
            'pd_quote_width'        => Configuration::get('PD_QUOTE_BOX_WIDTH'),
            'pd_quote_button_color' => Configuration::get('PD_QUOTE_BUTTON_COLOR'),
            'pd_quote_button'       => Configuration::get('PD_QUOTE_BUTTON_BACKGROUND'),
            'pd_quote_button_hover' => Configuration::get('PD_QUOTE_BUTTON_HOVER_BACKGROUND'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/button-quote.tpl');
    }
}