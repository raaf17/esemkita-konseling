<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
//     require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
// }

if (file_exists(APPPATH . 'Modules')) {
	$modulesPath = APPPATH . 'Modules/';
	$modules = scandir($modulesPath);

	foreach ($modules as $module) {
		if ($module === '.' || $module === '..') continue;
		if (is_dir($modulesPath) . '/' . $module) {
			$routesPath = $modulesPath . $module . '/Config/Routes.php';
			if (file_exists($routesPath)) {
				require($routesPath);
			} else {
				continue;
			}
		}
	}
}

if (file_exists(APPPATH . 'Client')) {
	$clientPath = APPPATH . 'Client/';
	$client = scandir($clientPath);

	foreach ($client as $module) {
		if ($module === '.' || $module === '..') continue;
		if (is_dir($clientPath) . '/' . $module) {
			$routesPath = $clientPath . $module . '/Config/Routes.php';
			if (file_exists($routesPath)) {
				require($routesPath);
			} else {
				continue;
			}
		}
	}
}
