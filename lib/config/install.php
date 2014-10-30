<?php
$plugin_id = array('shop', 'wallet');
$app_settings_model = new waAppSettingsModel();
$app_settings_model->set($plugin_id, 'status', '1');
$app_settings_model->set($plugin_id, 'frontend_name', 'Кошелек');