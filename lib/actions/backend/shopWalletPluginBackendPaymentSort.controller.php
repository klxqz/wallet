<?php

class shopWalletPluginBackendPaymentSortController extends waJsonController {

    public function execute() {
        $id = waRequest::post('module_id', 0, waRequest::TYPE_INT);
        $after_id = waRequest::post('after_id', 0, waRequest::TYPE_INT);
        $model = new shopWalletPluginModel();
        try {
            $model->move($id, $after_id, shopWalletPluginModel::TYPE_PAYMENT);
        } catch (waException $e) {
            $this->setError($e->getMessage());
        }
    }

}
