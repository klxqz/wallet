<?php

class shopWalletPluginSettingsAction extends waViewAction
{
    protected $tmp_path = 'plugins/novelties/templates/Novelties.html';
    protected $plugin_id = array('shop', 'wallet');
    
    public function execute()
    {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get($this->plugin_id);
        $change_tpl = false;
        $template_path = wa()->getDataPath($this->tmp_path, false, 'shop', true);
        if(file_exists($template_path)) {
            $change_tpl = true;
        } else {
            $template_path = wa()->getAppPath($this->tmp_path,  'shop');
        }
        $template = file_get_contents($template_path);     
        $this->view->assign('settings', $settings);
        $this->view->assign('template', $template);
        $this->view->assign('change_tpl', $change_tpl);
        
        
        $model = new shopWalletPluginModel();
        $this->view->assign('instances', $model->listPlugins(shopWalletPluginModel::TYPE_PAYMENT, array('all' => true, )));
        $this->view->assign('plugins', waPayment::enumerate());
        $this->view->assign('installer', $this->getUser()->getRights('installer', 'backend'));
        
    }
}
