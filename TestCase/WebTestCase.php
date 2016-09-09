<?php

namespace Cosma\Phest\TestCase;

use Phalcon\DiInterface;
use Phalcon\DI\ServiceInterface;
use Phalcon\Config;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

abstract class WebTestCase extends UnitTestCase
{
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
     * @param \Phalcon\DiInterface|null $di
     * @param \Phalcon\Config|null $config
     */
    protected function setUp(DiInterface $di = null, Config $config = null)
    {
        parent::setUp($di, $config);

        $this->cleanUp();
    }

    /**
     * Main Clean Up function
     */
    public function cleanUp()
    {
        $this->cleanUpUri();
        $this->cleanUpRequestMethod();
        $this->cleanUpParameters();
        $this->cleanUpHeaders();
    }

    /**
     * @param string $url
     * @param string $requestMethod
     * @param array $parameters
     * @param array $headers
     *
     * @return Response|ResponseInterface
     */
    protected function getResponse($url = '', $requestMethod = 'GET', $parameters = [], $headers = [])
    {
        $this->cleanUp();

        $requestMethod = strtoupper($requestMethod);

        $this->setUri($url);
        $this->setRequestMethod($requestMethod);
        $this->setParameters($requestMethod, $parameters);
        $this->setHeaders($headers);

        /** @var \Phalcon\Mvc\Micro $app */
        $app = DI::getDefault()->get('testApp');
        $app->handle($url);

        return $app->response;
    }

    /**
     * @param $serviceName
     * @param $mockedService
     * @return ServiceInterface
     */
    protected function mockService($serviceName, $mock)
    {
        return DI::getDefault()->set($serviceName, $mock);
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