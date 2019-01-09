<?php

namespace Journbers;

use Tracy\Debugger;

require_once 'vendor/autoload.php';

/* boot */
Debugger::enable();

/* load config */
// TODO: use ENV if needed
$config = include 'config/default.array.php';

$router = new Router($config['routes']);
$route  = $router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

/* search controller by route */
Debugger::barDump($route);
list($className, $method) = explode('::', $route[2]);
Debugger::barDump($className);
$wantedController = sprintf('\\Journbers\\Controller\\%s', ucfirst(strtolower($className)));
$wantedMethod     = strtolower($method);
if ( ! class_exists($wantedController)) {
    throw new \RuntimeException(sprintf('Controller %s not found.', $wantedController));
}
$controller = new $wantedController;
if ( ! method_exists($controller, $wantedMethod)) {
    throw new \RuntimeException(sprintf('Method %s not found in %s.', $wantedMethod, $wantedController));
}

/* call controller */
$runOrder = [
    'start',
    sprintf('before%s', ucfirst($wantedMethod)),
    $wantedMethod,
    sprintf('after%s', ucfirst($wantedMethod)),
    'end'
];

foreach ($runOrder as $method) {
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], []);
    }
}
