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

namespace Cosma\Phest\TestCase;

use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Application;

abstract class WebTestCase extends UnitTestCase
{

    /**
     * @var array
     */
    protected static $availableRequestMethods = [
        'GET',
        'POST',
        'PUT',
        'HEAD',
        'PATCH',
        'DELETE',
        'OPTIONS'
    ];

    /**
     * @var Micro|Application
     */
    protected $app;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cleanUp();

        if (!$this->getDi()->has('_testApp')) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                '_testApp service of DI should be set'
            );
        }


        /** @var Micro|Application $app */
        $app = $this->getDi()->get('_testApp');

        if (!($app instanceof Micro || $app instanceof Application)) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                '_testApp service of DI should be set to a Phalcon\Mvc\Micro or Phalcon\Mvc\Application object'
            );
        }

        $this->setApp($app);
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        parent::__destruct();
        if (!($this->getApp() instanceof Micro || $this->getApp() instanceof Application)) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run Cosma\Phest\TestCase\WebTestCase::setUp()');
        }
    }

    /**
     * @param string $url
     * @param string $requestMethod
     * @param array $parameters
     * @param array $headers
     *
     * @return ResponseInterface
     */
    protected function sendRequest($url = '', $requestMethod = 'GET', $parameters = [], $headers = [])
    {
        $this->cleanUp();

        $requestMethod = strtoupper($requestMethod);

        $this->setUri($url);
        $this->setRequestMethod($requestMethod);
        $this->setParameters($requestMethod, $parameters);
        $this->setHeaders($headers);

        /** @var Response|boolean|string $response */
        $response = $this->getApp()->handle($url);

        if (isset($response->response)) {
            return $response->response;
        }
        return $response;
    }

    /**
     * @return Application|Micro
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param Application|Micro $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }

    /**
     * Main Clean Up function
     */
    protected function cleanUp()
    {
        $this->cleanUpUri();
        $this->cleanUpRequestMethod();
        $this->cleanUpParameters();
        $this->cleanUpHeaders();
    }

    /**
     * void
     */
    private function cleanUpUri()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '';
        }
    }

    /**
     * void
     */
    private function cleanUpRequestMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $_SERVER['REQUEST_METHOD'] = '';
        }
    }

    /**
     * void
     */
    private function cleanUpParameters()
    {
        array_walk(self::$availableRequestMethods, function ($item) {
            if (isset($GLOBALS['_' . $item])) {
                $GLOBALS['_' . $item] = [];
            }
        });
    }

    /**
     * void
     */
    private function cleanUpHeaders()
    {
        array_walk($_SERVER, function ($item, $key) {
            if (0 === strpos($key, 'HTTP_')) {
                unset($_SERVER[$key]);
            }
        });
    }

    /**
     * @param string $uri
     */
    private function setUri($uri)
    {
        $_SERVER['REQUEST_URI'] = $uri;
    }

    /**
     * @param string $requestMethod
     */
    private function setRequestMethod($requestMethod)
    {
        if (in_array($requestMethod, self::$availableRequestMethods)) {
            $_SERVER['REQUEST_METHOD'] = $requestMethod;
        } else {
            $_SERVER['REQUEST_METHOD'] = 'GET';
        }
    }

    /**
     * @param string $requestMethod
     * @param array $parameters
     */
    private function setParameters($requestMethod, array $parameters)
    {
        if (count($parameters) > 0) {
            if (
                isset($GLOBALS['_' . $requestMethod]) &&
                is_array($GLOBALS['_' . $requestMethod])
            ) {
                $GLOBALS['_' . $requestMethod] = array_merge($GLOBALS['_' . $requestMethod], $parameters);
            }
        }
    }

    /**
     * @param array $headers
     */
    private function setHeaders(array $headers)
    {
        array_walk($headers, function ($item, $key) {
            $_SERVER['HTTP_' . strtoupper($key)] = $item;
        });
    }
}