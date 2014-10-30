<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'my/wallet/' => array(
        'module' => 'frontend',
        'plugin' => 'wallet',
        'action' => 'myWallet',
        'secure' => true,
    ),
    'my/wallet/payment/' => array(
        'module' => 'frontend',
        'plugin' => 'wallet',
        'action' => 'walletPayment',
        'secure' => true,
    ),
    'my/wallet/amount/' => array(
        'module' => 'frontend',
        'plugin' => 'wallet',
        'action' => 'walletAmount',
        'secure' => true,
    ),
);
