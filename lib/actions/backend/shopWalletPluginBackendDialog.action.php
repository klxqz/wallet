<?php

class shopWalletPluginBackendDialogAction extends waViewAction {

    public function execute() {
        $this->view->assign('plugin_id', $plugin_id = waRequest::get('plugin_id'));
        $this->view->assign('plugin', $info = shopWalletPayment::getPluginInfo($plugin_id));

        $plugin = shopWalletPayment::getPlugin($info['plugin'], $plugin_id);
        $params = array(
            'namespace' => "payment[settings]",
            'value' => waRequest::post('shipping[settings]'),
        );
        $this->view->assign('settings_html', $plugin->getSettingsHTML($params));
        $this->view->assign('guide_html', $plugin->getGuide($params));

        $model = new shopPluginModel();
        $this->view->assign('shipping', $model->listPlugins(shopWalletPluginModel::TYPE_SHIPPING, array('payment' => $plugin_id, 'all' => true)));
    }

}
