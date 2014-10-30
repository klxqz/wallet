<?php

class shopWalletPluginFrontendWalletAmountAction extends shopFrontendAction {

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        if (!$app_settings_model->get(shopWalletPlugin::$plugin_id, 'status')) {
            throw new waException(_ws("Page not found"), 404);
        }
        $order = array();
        $order['id'] = wa()->getUser()->getId() . '_' . time();
        $order['params']['payment_id'] = waRequest::post('payment_id');
        $order['total'] = waRequest::post('amount', 0);
        $order['currency'] = wa('shop')->getConfig()->getCurrency(true);
        $order['contact_id'] = wa()->getUser()->getId();



        $payment = '';
        if (!empty($order['params']['payment_id'])) {
            try {
                /**
                 * @var waPayment $plugin
                 */
                $plugin = shopWalletPayment::getPlugin(null, $order['params']['payment_id']);
                print_r(shopWalletPayment::getOrderData($order, $plugin));
                $payment = $plugin->payment(waRequest::post(), shopWalletPayment::getOrderData($order, $plugin), true);
            } catch (waException $ex) {
                $payment = $ex->getMessage();
            }
        }


        $this->view->assign('payment', $payment);




        $this->view->assign('breadcrumbs', self::getBreadcrumbs());
        $this->getResponse()->setTitle($app_settings_model->get(shopWalletPlugin::$plugin_id, 'frontend_name'));
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
