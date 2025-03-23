<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('profile', ['namespace' => '\App\Modules\Profile\Controllers'], static function ($routes) {
        $routes->get('/', 'ProfileController::index', ['as' => 'profile']);
        $routes->post('updateprofile', 'ProfileController::updateProfile', ['as' => 'profile.updateprofile']);
        $routes->post('updateprofilepicture', 'ProfileController::updateProfilePicture', ['as' => 'profile.updateprofilepicture']);
        $routes->post('changepassword', 'ProfileController::changePassword', ['as' => 'profile.changepassword']);
    });
});