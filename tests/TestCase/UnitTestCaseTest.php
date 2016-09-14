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

use Cosma\Phest\TestCase\UnitTestCase;
use Phalcon\DI;
use Phalcon\Di\FactoryDefault;

/**
 * @covers \Cosma\Phest\TestCase\UnitTestCase <extended>
 *
 * @retry 6
 */
class UnitTestCaseTest extends UnitTestCase
{

    protected function setUp()
    {
        DI::setDefault(new FactoryDefault());

        parent::setUp();

    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        $this->setDi(new FactoryDefault());

        parent::tearDown();
    }

    /**
     * @type int
     */
    private static $counterFirstTest = 0;

    /**
     * @type int
     */
    private static $counterSecondTest = 0;

    /**
     * @type int
     */
    private static $counterThirdTest = 0;

    /**
     * @type int
     */
    private static $counterForthTest = 0;

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::setUp()
     */
    public function testSetUp()
    {
        $this->assertContains('/tests/TestCase/UnitTestCaseTest', $this->getTestClassPath());
        $this->assertInstanceOf('Phalcon\DiInterface', $this->getDi());
    }

    /**
     *
     * @covers \Cosma\Phest\TestCase\UnitTestCase::setUp()
     *
     * @expectedException \PHPUnit_Framework_IncompleteTestError
     * @expectedExceptionMessage Di::getDefault() should return a Phalcon\DiInterface object.
     */
    public function testSetUp_exception()
    {
        DI::reset();

        parent::setUp();
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::__destruct()
     */
    public function testDestruct_Normal()
    {
        $this->__destruct();

    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::__destruct()
     *
     * @expectedException \PHPUnit_Framework_IncompleteTestError
     * @expectedExceptionMessage Please run Cosma\Phest\TestCase\UnitTestCase::setUp()
     */
    public function testDestruct_Exception()
    {
        $this->di = null;
        $this->__destruct();
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::tearDown()
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage You have not declared any mocks yet
     */
    public function testTearDown()
    {
        $this->tearDown();

        \Mockery::self();
    }


    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::mockService()
     */
    public function testMockService()
    {
        $originalRouterService = $this->getDi()->get('router');
        $this->assertInstanceOf('\Phalcon\Mvc\Router', $originalRouterService);
        $this->assertEquals(
            [
                'namespace' => '',
                'module' => '',
                'controller' => '',
                'action' => '',
                'params' => []
            ],
            $originalRouterService->getDefaults()
        );

        $mock = $this->getMockBuilder('\Phalcon\Mvc\Router')
            ->disableOriginalConstructor()
            ->setMethods(['getDefaults'])
            ->getMock();
        $mock
            ->method('getDefaults')
            ->willReturn(['cosma' => 'test']);


        $this->mockService('router', $mock);

        $mockedRouterService = $this->getDi()->get('router');

        $this->assertInstanceOf('\Phalcon\Mvc\Router', $mockedRouterService);
        $this->assertEquals(['cosma' => 'test'], $mockedRouterService->getDefaults());

    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::getTestClassPath()
     */
    public function testGetTestClassPath()
    {
        $this->assertContains('/tests/TestCase/UnitTestCaseTest', $this->getTestClassPath());
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::runBare()
     *
     * @expectedException \Exception
     *
     * @expectedExceptionMessage This test needs at least 6 retries
     */
    public function testRunBare_NoRetry()
    {
        self::$counterFirstTest++;

        if (self::$counterFirstTest > 6) {
            $this->assertTrue(true);
        } else {
            throw new \Exception('This test needs at least 6 retries');
        }
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::runBare()
     *
     * @retry 4
     *
     * @expectedException \Exception
     *
     * @expectedExceptionMessage This test needs at least 6 retries
     */
    public function testRunBare_NotEnough()
    {
        self::$counterSecondTest++;

        if (self::$counterSecondTest > 6) {
            $this->assertTrue(true);
        } else {
            throw new \Exception('This test needs at least 6 retries');
        }
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::runBare()
     *
     * @retry  10
     */
    public function testRunBare_MethodRetry()
    {
        self::$counterThirdTest++;

        if (self::$counterThirdTest > 6) {
            $this->assertTrue(true);
        } else {
            throw new \Exception('This test needs at least 6 retries');
        }
    }

    /**
     * @covers \Cosma\Phest\TestCase\UnitTestCase::runBare()
     */
    public function testRunBare_ClassRetry()
    {
        self::$counterForthTest++;

        if (self::$counterForthTest > 6) {
            $this->assertTrue(true);
        } else {
            throw new \Exception('This test needs at least 6 retries');
        }
    }
}