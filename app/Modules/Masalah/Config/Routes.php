<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('masalah', ['namespace' => '\App\Modules\Masalah\Controllers'], static function ($routes) {
        $routes->get('/', 'MasalahController::index', ['as' => 'masalah']);
        $routes->post('listdatasubmasalah', 'MasalahController::listDataSubMasalah', ['as' => 'masalah.listdatasubmasalah']);
        $routes->post('listdatamainmasalah', 'MasalahController::listDataMainMasalah', ['as' => 'masalah.listdatamainmasalah']);
        $routes->post('store', 'MasalahController::store', ['as' => 'masalah.store']);
        $routes->get('getsubmasalah', 'MasalahController::getSubMasalah', ['as' => 'masalah.getsubmasalah']);
        $routes->get('getmainmasalah', 'MasalahController::getMainMasalah', ['as' => 'masalah.getmainmasalah']);
        $routes->post('update', 'MasalahController::update', ['as' => 'masalah.update']);
        $routes->get('delete', 'MasalahController::delete', ['as' => 'masalah.delete']);
        $routes->post('storemain', 'MasalahController::storeMain', ['as' => 'masalah.storemain']);
        $routes->get('getmainmasalah', 'MasalahController::getMainMasalah', ['as' => 'masalah.getmainmasalah']);
        $routes->post('updatemain', 'MasalahController::updateMain', ['as' => 'masalah.updatemain']);
        $routes->get('deletemain', 'MasalahController::deleteMain', ['as' => 'masalah.deletemain']);
        $routes->get('exportsubmasalah', 'MasalahController::exportSubMasalah', ['as' => 'masalah.exportsubmasalah']);
        $routes->get('exportmainmasalah', 'MasalahController::exportMainMasalah', ['as' => 'masalah.exportmainmasalah']);
        $routes->post('multipledeletesub', 'MasalahController::multipleDeleteSub', ['as' => 'masalah.multipledeletesub']);
        $routes->post('multipledeletemain', 'MasalahController::multipleDeleteMain', ['as' => 'masalah.multipledeletemain']);
        $routes->get('comboboxmainmasalah', 'MasalahController::comboboxMainMasalah', ['as' => 'masalah.comboboxmainmasalah']);
    });
});