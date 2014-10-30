<?php

class shopWalletPluginFrontendWalletPaymentAction extends shopFrontendAction {

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        if (!$app_settings_model->get(shopWalletPlugin::$plugin_id, 'status')) {
            throw new waException(_ws("Page not found"), 404);
        }

        $this->sss();


        $this->view->assign('breadcrumbs', self::getBreadcrumbs());
        $this->getResponse()->setTitle($app_settings_model->get(shopWalletPlugin::$plugin_id, 'frontend_name'));
    }

    public function sss() {
        $plugin_model = new shopWalletPluginModel();

        if (waRequest::param('payment_id') && is_array(waRequest::param('payment_id'))) {
            $methods = $plugin_model->getById(waRequest::param('payment_id'));
        } else {
            $methods = $plugin_model->listPlugins('payment');
        }


        $disabled = array();


        $currencies = wa('shop')->getConfig()->getCurrencies();
        $selected = null;
        foreach ($methods as $key => $m) {
            $method_id = $m['id'];
            if (in_array($method_id, $disabled)) {
                unset($methods[$key]);
                continue;
            }
            $plugin = shopWalletPayment::getPlugin($m['plugin'], $m['id']);
            $plugin_info = $plugin->info($m['plugin']);
            $methods[$key]['icon'] = $plugin_info['icon'];
            $custom_fields = $this->getCustomFields($method_id, $plugin);
            $custom_html = '';
            foreach ($custom_fields as $c) {
                $custom_html .= '<div class="wa-field">' . $c . '</div>';
            }
            $methods[$key]['custom_html'] = $custom_html;
            $allowed_currencies = $plugin->allowedCurrency();
            if ($allowed_currencies !== true) {
                $allowed_currencies = (array) $allowed_currencies;
                if (!array_intersect($allowed_currencies, array_keys($currencies))) {
                    $methods[$key]['error'] = sprintf(_w('Payment procedure cannot be processed because required currency %s is not defined in your store settings.'), implode(', ', $allowed_currencies));
                }
            }
            if (!$selected && empty($methods[$key]['error'])) {
                $selected = $method_id;
            }
        }


        $this->view->assign('checkout_payment_methods', $methods);
        $this->view->assign('payment_id', $selected);

        
    }

    protected function getCustomFields($id, waPayment $plugin) {
        $contact = $this->getContact();
        $order_params = array();
        $payment_params = isset($order_params['payment']) ? $order_params['payment'] : array();
        foreach ($payment_params as $k => $v) {
            $order_params['payment_params_' . $k] = $v;
        }
        $order = new waOrder(array('contact' => $contact,
            'contact_id' => $contact ? $contact->getId() : null,
            'params' => $order_params
        ));
        $custom_fields = $plugin->customFields($order);
        if (!$custom_fields) {
            return $custom_fields;
        }

        $params = array();
        $params['namespace'] = 'payment_' . $id;
        $params['title_wrapper'] = '%s';
        $params['description_wrapper'] = '<br><span class="hint">%s</span>';
        $params['control_wrapper'] = '<div class="wa-name">%s</div><div class="wa-value">%s %s</div>';

        $selected = 0;

        $controls = array();
        foreach ($custom_fields as $name => $row) {
            $row = array_merge($row, $params);
            if ($selected && isset($payment_params[$name])) {
                $row['value'] = $payment_params[$name];
            }
            $controls[$name] = waHtmlControl::getControl($row['control_type'], $name, $row);
        }

        return $controls;
    }

    protected function getContact() {
        if (wa()->getUser()->isAuth()) {
            $contact = wa()->getUser();
        }
        return $contact ? $contact : new waContact();
    }

    public static function getBreadcrumbs() {
        $app_settings_model = new waAppSettingsModel();
        $result = shopWalletPluginFrontendMyWalletAction::getBreadcrumbs();
        $result[] = array(
            'name' => $app_settings_model->get(shopWalletPlugin::$plugin_id, 'frontend_name'),
            'url' => wa()->getRouteUrl('shop/frontend/myWallet'),
        );
        return $result;
    }

}
