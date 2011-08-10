<?php
// Environment
define('_ENV_', !empty($_SERVER['APPLICATION_ENV']) ? $_SERVER['APPLICATION_ENV'] : 'production');

// Real paths
define('_ROOT_', realpath(dirname(__FILE__) . '/..'));
define('_APP_', _ROOT_ . '/app');
define('_PHBEE_', _ROOT_ . '/phbee');

define('_NOW_', time());

include _PHBEE_ . '/registry.php';
include _PHBEE_ . '/utils.php';
include _PHBEE_ . '/phbee.php';
include _PHBEE_ . '/view.php';
include _PHBEE_ . '/mysql.php';


/**
 * 
 * Convert request uri into a controller > action
 * 
 */
function findRoute ($routes) {
	$request = $_SERVER['REQUEST_URI'];

	// Remove host if needed (for proxies returning full host)
	$httpHost = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
	if (strpos($request, $httpHost) === 0) $request = substr($request, strlen($httpHost));

	// Remove query string if needed
	$queryString = strpos('?', $request);
	if ($queryString !== false) $request = substr($request, 0, $queryString);

	$result = false;
	foreach ($routes as $rule) {
		$match = preg_replace("#" . $rule[0] . "#", $rule[1], $request);
		if ($match && $match != $request) {
			$result = $match;
			break;
		}
	}

	$route = explode('/', $result ? $result : $request);

	$result = array();

	// Controller
	$result['controller'] = $route[0];
	array_shift($route);

	// Action (defaulted to 'index')
	if (!empty($route[0])) {
		$result['action'] = $route[0];
		array_shift($route);
	} else {
		$result['action'] = 'index';
	}
	
	// Parms
	$result['parms'] = !empty($route) ? $route : null;

	return $result;
}


/**
 * 
 * Bootstrap
 * 
 */
function bootStrap () {
	$reg = PHB_Registry::getInstance();

	include _APP_ . '/config.php';
	
	// Merge user environment with global config
	if (isset($environments[_ENV_])) {
		$environments['global'] = array_replace_recursive($environments['global'], $environments[_ENV_]);
	}
	
	// Save global config to the registry
	$reg->merge($environments['global']);

	// Load routes and find controller/action
	$reg->routes = $routes;
	$reg->merge(findRoute($reg->routes));

	// Configure database (no connection is done at this point)
	PHB_Mysql::config($reg->database);

	// Load controller
	if (!file_exists(_APP_ . '/controllers/' . $reg->controller . '.php')) {
		$reg->merge(array('controller' => 'Error', 'action' => 'index', 'parms' => null));
	}
	include _APP_ . '/controllers/' . $reg->controller . '.php';

	// Bootstrap
	$controller = ucfirst($reg->controller) . 'Controller';
	$phbee = new $controller;		// Load controller
	$phbee->init();					// Init controller
	$action = $reg->action;
	$phbee->$action();				// Load action
	$phbee->view->render();			// Output to the browser
}

// Execute!
bootStrap();
