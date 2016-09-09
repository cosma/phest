<?php

use Phalcon\DI;
use Cosma\Http\Response;

date_default_timezone_set('UTC');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$environment = getenv('ENV');
define('ENVIRONMENT', $environment ?: 'test');

define('ROOT_PATH', __DIR__);
define('PATH_SRC', __DIR__ . '/../src/');

/** @var \Phalcon\Mvc\Micro $app */
$app = require_once  'app.php';

if(file_exists('config.php')){
    $testConfig = include "config.php";
    if($testConfig instanceof Phalcon\Config ){
        $app->getDI()->get('config')->merge($testConfig);
    }

}


$di = DI::getDefault();

$responseService = $di->getService('response');
$responseService->setDefinition(function () {
    return new Response();
});

Di::reset();

$di->set('testApp', function () use ($di, $app) {

    $app->post('/test_endpoint', function () use ($app) {
        return ($app->request->getPost('test_var'));
    });

    $app->get('/test_endpoint', function () use ($app) {
        return json_encode($app->request->getHeaders());
    });

    return $app;
});

Di::setDefault($di);