<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('settings', ['namespace' => '\App\Modules\Settings\Controllers'], static function ($routes) {
        $routes->get('/', 'SettingController::index', ['as' => 'settings']);
        $routes->get('general', 'SettingController::general', ['as' => 'settings.general']);
        $routes->post('updategeneralsetting', 'SettingController::updateGeneralSettings', ['as' => 'settings.updategeneralsetting']);
        $routes->post('updateschoolsetting', 'SettingController::updateSchoolSettings', ['as' => 'settings.updateschoolsetting']);
        $routes->post('updatelogosetting', 'SettingController::updateLogoSetting', ['as' => 'settings.updatelogosetting']);
        $routes->post('updatefaviconsetting', 'SettingController::updateFaviconSetting', ['as' => 'settings.updatefaviconsetting']);
    });
});