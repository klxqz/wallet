<?php

return array(
    'shop_wallet_plugin' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'type' => array('varchar', 255, 'null' => 0),
        'plugin' => array('varchar', 255, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'description' => array('text', 'null' => 0),
        'logo' => array('text', 'null' => 0),
        'status' => array('int', 11, 'null' => 0),
        'sort' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
            'type' => 'type',
        ),
    ),
    'shop_wallet_plugin_settings' => array(
        'id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 64, 'null' => 0),
        'value' => array('text', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => array('id', 'name'),
        ),
    ),
    'shop_wallet' => array(
        'contact_id' => array('int', 11, 'null' => 0),
        'balance' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'contact_id',
        ),
    ),
);
