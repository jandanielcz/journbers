<?php

namespace Journbers;

use Tracy\Debugger;

require_once 'vendor/autoload.php';

/* load config */
// TODO: use ENV if needed
$config = new Config();
$config->require('config/default.array.php');
$config->include('config/custom.array.php');

/* boot and setup */
$env = new Env($config->get('envPrefix'));
$config->add($env->variables());

Debugger::enable(Debugger::DETECT, $config->get('LOG_PATH'));

$session = new Session($config->get('sessionLifetime', 48 * 60 * 60));

$router = new Router($config->get('routes'));
$route  = $router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

/* search controller by route */
list($className, $method) = explode('::', $route[2]);
$wantedController = sprintf('\\Journbers\\Controller\\%s', ucfirst(strtolower($className)));
$wantedMethod     = strtolower($method);

if (!class_exists($wantedController)) {
    throw new \RuntimeException(sprintf('Controller %s not found.', $wantedController));
}

$request = new Request();
$user = new User($session);
$request->setUser($user);

$controller = new $wantedController($config, $request);
if (!method_exists($controller, $wantedMethod)) {
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

/* checks access */
if (call_user_func_array([$controller, 'checkAccess'], [$method]) === false) {
    $c = new Controller($config, $request);

    if ($request->user()->hasAnyRole()) {
        $c->messageAndRedirect('/', sprintf('You do not have access to %s.', $route[2]), 'error');
    } else {
        $c->messageAndRedirect('/login');
    }

};

foreach ($runOrder as $method) {
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], []);
    }
}
