<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopWalletPlugin extends shopPlugin {

    public static $plugin_id = array('shop', 'wallet');

    public function frontendMyNav() {
        if ($this->getSettings('status')) {
            return '<a href="' . wa()->getRouteUrl('shop/frontend/myWallet') . '">' . $this->getSettings('frontend_name') . '</a>';
        }
    }

}
