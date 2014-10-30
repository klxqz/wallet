<?php

class shopWalletPluginBackendPaymentSaveController extends waJsonController {

    public function execute() {
        if ($plugin = waRequest::post('payment')) {
            try {
                if (!isset($plugin['settings'])) {
                    $plugin['settings'] = array();
                }
                
                if(intval($plugin['id']) > 0) {
                    $this->response['action'] = 'update';
                } else {
                    $this->response['action'] = 'add';
                }
                $plugin = shopWalletPayment::savePlugin($plugin);
                $this->response['plugin'] = $plugin;
                $this->response['message'] = _w('Saved');
            } catch (waException $ex) {
                $this->setError($ex->getMessage());
            }
        }
    }

}
