<?php
/**
 * This file is part of the "cosma/phest" project
 *
 * (c) Cosmin Voicu<cosmin.voicu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use Phalcon\Di;
use Cosma\Phest\Exception\UndefinedApplicationException;
use Cosma\Phest\Exception\WrongApplicationTypeException;
use Cosma\Phest\Http\Response;

/**
 * Check if $app in set and has the corect type
 */

/** @var \Phalcon\Mvc\Micro|\Phalcon\Mvc\Application $app */
if (!isset($app)) {
    throw new UndefinedApplicationException();
}

if (!($app instanceof \Phalcon\Mvc\Micro || $app instanceof \Phalcon\Mvc\Application)) {
    throw new WrongApplicationTypeException();
}

if (!isset($di) || !($di instanceof \Phalcon\DiInterface)) {
    $di = $app->getDI();
}

/**
 * Merge tests configs
 */
if (file_exists(__DIR__ . '/config.php')) {
    $libraryTestConfig = require_once __DIR__ . "/config.php";
    if ($libraryTestConfig instanceof Phalcon\Config) {
        $di->get('config')->merge($libraryTestConfig);
    }
}
if (defined('TEST_PATH') && file_exists(TEST_PATH . '/config.php')) {
    $testConfig = require_once TEST_PATH . "/config.php";
    if ($testConfig instanceof Phalcon\Config) {
        $di->get('config')->merge($testConfig);
    }
}

/**
 * Stop the actual sending of response by overwriting the \Phalcon\Http\Response
 */
$responseService = $di->getService('response');
$responseService->setDefinition(function () {
    return new Response();
});

/**
 * Set protected service _testApp as the application instance
 */
$di->set('_testApp', function () use ($app) {
    return $app;
});

/**
 * Set the dependency injection container of application as default
 */
Di::reset();
Di::setDefault($di);