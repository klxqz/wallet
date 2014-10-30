<?php

class shopWalletPluginFrontendMyWalletAction extends shopFrontendAction {

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        if (!$app_settings_model->get(shopWalletPlugin::$plugin_id, 'status')) {
            throw new waException(_ws("Page not found"), 404);
        }

        $wallet_model = new shopWalletModel();
        $id = wa()->getUser()->getId();
        $wallet = $wallet_model->getByField('contact_id', $id);
        print_r($wallet);
        if (!$wallet) {
            $balance = 0;
        } else {
            $balance = $wallet['balance'];
        }
        
        $this->view->assign('balance', $balance);
        $this->view->assign('breadcrumbs', self::getBreadcrumbs());
        $this->getResponse()->setTitle($app_settings_model->get(shopWalletPlugin::$plugin_id, 'frontend_name'));
    }

    public static function getBreadcrumbs() {
        return array(
            array(
                'name' => _w('My account'),
                'url' => wa()->getRouteUrl('/frontend/my'),
            ),
        );
    }

}
