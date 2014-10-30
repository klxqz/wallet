<?php

class shopWalletPluginBackendPaymentDeleteController extends waJsonController {

    public function execute() {
        if ($plugin_id = waRequest::post('plugin_id')) {
            $model = new shopWalletPluginModel();
            if ($plugin = $model->getByField(array('id' => $plugin_id, 'type' => 'payment'))) {
                $settings_model = new shopWalletPluginSettingsModel();
                $settings_model->del($plugin['id'], null);
                $model->deleteById($plugin['id']);
            } else {
                throw new waException("Payment plugin {$plugin_id} not found", 404);
            }
        }
    }

}
