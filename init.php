<?php

use Phalcon\Mvc\Micro;

/**
 * Read the configuration
 */
$config = include APP_PATH . "/config/config.php";

/**
 * auto-loader
 */
include APP_PATH . "/config/loader.php";


/**
 * Read services
 */
$di = include APP_PATH . "/config/services.php";


/**
 * Handle the request
 */
$application = new \Phalcon\Mvc\Micro($di);


$application->before(function () use ($application) {
    $middleware = new \middlewares\AuthorizationMiddleware();
    return $middleware->call($application);
});

/**
 * read routes
 */
include APP_PATH . "/config/routes.php";

return $application;