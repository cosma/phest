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

namespace Cosma\Phest\Tests\TestCase;

use Cosma\Phest\Http\Response;
use Cosma\Phest\TestCase\WebTestCase;
use Phalcon\DI;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Request;
use Phalcon\Http\Response\Headers;
use Phalcon\Mvc\Micro;

/**
 * @covers \Cosma\Phest\TestCase\WebTestCase <extended>
 *
 * @retry  shouldBeInteger
 */
class WebTestCaseTest extends WebTestCase
{
    protected function setUp()
    {
        DI::setDefault(new FactoryDefault());
        $responseService = DI::getDefault()->getService('response');
        $responseService->setDefinition(function () {
            return new Response();
        });
        DI::getDefault()->set('_testApp', new Micro());
        parent::setUp();
    }

    protected function tearDown()
    {
        $di  = new FactoryDefault();
        $app = new Micro();
        $di->set('_appTest', $app);
        $this->setDi($di);
        $this->setApp($app);

        parent::tearDown();
    }


    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::setUp()
     */
    public function testSetUp()
    {
        $this->assertInstanceOf('Phalcon\Mvc\Micro', $this->getDi()->get('_testApp'));
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::setUp()
     *
     * @expectedException \PHPUnit_Framework_IncompleteTestError
     * @expectedExceptionMessage _testApp service of DI should be set
     */
    public function testSetUp_ExceptionNotSet()
    {
        $this->getDi()->remove('_testApp');

        parent::setUp();
    }


    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::setUp()
     *
     * @expectedException \PHPUnit_Framework_IncompleteTestError
     * @expectedExceptionMessage _testApp service of DI should be set to a Phalcon\Mvc\Micro or Phalcon\Mvc\Application object
     */
    public function testSetUp_ExceptionWrongType()
    {
        $this->getDi()->set('_testApp', new \StdClass);

        parent::setUp();
    }

    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::__destruct()
     */
    public function testDestruct_Normal()
    {
        $this->__destruct();

    }

    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::__destruct()
     *
     * @expectedException \PHPUnit_Framework_IncompleteTestError
     * @expectedExceptionMessage Please run Cosma\Phest\TestCase\WebTestCase::setUp()
     */
    public function testDestruct_Exception()
    {
        $this->app = null;
        $this->__destruct();
    }


    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::cleanUp()
     */
    public function testCleanUp()
    {
        $_SERVER['REQUEST_URI']      = 'REQUEST_URI';
        $_SERVER['REQUEST_METHOD']   = 'REQUEST_METHOD';
        $GLOBALS['_GET']             = [1, 2, 3];
        $GLOBALS['_POST']            = [1, 2, 3];
        $GLOBALS['_PUT']             = [1, 2, 3];
        $GLOBALS['_HEAD']            = [1, 2, 3];
        $GLOBALS['_PATCH']           = [1, 2, 3];
        $GLOBALS['_DELETE']          = [1, 2, 3];
        $GLOBALS['_OPTIONS']         = [1, 2, 3];
        $_SERVER['HTTP_SOME_HEADER'] = 'some value';

        $this->cleanUp();

        $this->assertEmpty($_SERVER['REQUEST_URI']);
        $this->assertEmpty($_SERVER['REQUEST_METHOD']);
        $this->assertEmpty($GLOBALS['_GET']);
        $this->assertEmpty($GLOBALS['_POST']);
        $this->assertEmpty($GLOBALS['_PUT']);
        $this->assertEmpty($GLOBALS['_HEAD']);
        $this->assertEmpty($GLOBALS['_PATCH']);
        $this->assertEmpty($GLOBALS['_DELETE']);
        $this->assertEmpty($GLOBALS['_OPTIONS']);
        $this->assertArrayNotHasKey('HTTP_SOME_HEADER', $_SERVER);
    }

    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::sendRequest()
     */
    public function testSendRequest_MicroPOST()
    {


        $this->getApp()->post('/url_to_send', function () {

            $headers = new Headers();
            foreach ($this->getRequestHeaders() as $key => $value) {
                $headers->set($key, $value);
            }

            /** @var Request $request */
            $request  = new Request();

            $response = new Response();
            $response->setHeaders($headers);
            $response->setContent(json_encode($request->getPost()));
            return $response;
        });
        $response = $this->sendRequest(
            '/url_to_send',
            'POST',
            ['param1' => 'value1', 'param2' => 'value2'],
            ['header1' => 'headerValue1', 'header2' => 'headerValue2']
        );

        $this->assertInstanceOf('\Phalcon\Http\Response', $response);
        $this->assertNull($response->getContent());
        $this->assertEquals([], $response->getHeaders()->toArray());
    }

    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::sendRequest()
     */
    public function testSendRequest_UnknownRequestMethod()
    {
        $responseService = $this->getDi()->getService('response');
        $responseService->setDefinition(function () {
            return new Response();
        });

        /** @var Micro $app */
        $app = $this->getApp();

        $app->get('/url_to_send', function () {

            $headers = new Headers();
            foreach ($this->getRequestHeaders() as $key => $value) {
                $headers->set($key, $value);
            }

            $response = new Response();
            $response->setHeaders($headers);
            $response->setContent(json_encode($_GET));

            return $response;
        });
        $response = $this->sendRequest(
            '/url_to_send',
            'UNKNOWMETHOD',
            ['param1' => 'value1', 'param2' => 'value2'],
            ['header1' => 'headerValue1', 'header2' => 'headerValue2']
        );

        $this->assertInstanceOf('\Phalcon\Http\Response', $response);
        $this->assertNull($response->getContent());
        $this->assertEquals([], $response->getHeaders()->toArray());
    }

    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::sendRequest()
     */
    public function testSendRequest_PUTResponse()
    {
        $responseService = $this->getDi()->getService('response');
        $responseService->setDefinition(function () {
            return new Response();
        });

        /** @var Micro $app */
        /** @var Micro $app */
        $app = $this->getApp();

        $app->put('/url_to_send', function () {

            $headers = new Headers();
            foreach ($this->getRequestHeaders() as $key => $value) {
                $headers->set($key, $value);
            }

            $response = new Response();
            $response->setHeaders($headers);
            $response->setContent(json_encode($_POST));

            $object = new \stdClass();

            $object->response = $response;

            return $object;
        });
        $response = $this->sendRequest(
            '/url_to_send',
            'PUT',
            ['param1' => 'value1', 'param2' => 'value2'],
            ['header1' => 'headerValue1', 'header2' => 'headerValue2']
        );

        $this->assertInstanceOf('\Phalcon\Http\Response', $response);
        $this->assertNull($response->getContent());
        $this->assertEquals([], $response->getHeaders()->toArray());
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::runBare()
     *
     * @expectedException \Exception
     * @expectedExceptionMessage This test needs to fail even after 1 retry
     */
    public function testRunBare_RetryClass()
    {
        throw new \Exception('This test needs to fail even after 1 retry');
    }


    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::runBare()
     *
     * @retry  shouldBeInteger
     *
     * @expectedException \Exception
     * @expectedExceptionMessage This test needs to fail even after 1 retry
     */
    public function testRunBare_RetryMethod()
    {
        throw new \Exception('This test needs to fail even after 1 retry');
    }

    private function getRequestHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header           = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }

}