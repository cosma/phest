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

use Cosma\Phest\TestCase\WebTestCase;

/**
 * @retry 6
 */
class WebTestCaseTest extends WebTestCase
{
    /**
     * @covers \Cosma\Phest\TestCase\WebTestCase::cleanUp()
     */
    public function testCleanUp()
    {
        $_SERVER['REQUEST_URI']      = 'REQUEST_URI';
        $_SERVER['REQUEST_METHOD']   = 'REQUEST_METHOD';
        $GLOBALS['_GET']             = [1, 2, 3];
        $GLOBALS['_POST']             = [1, 2, 3];
        $GLOBALS['_PUT']              = [1, 2, 3];
        $GLOBALS['_HEAD']             = [1, 2, 3];
        $GLOBALS['_PATCH']            = [1, 2, 3];
        $GLOBALS['_DELETE']           = [1, 2, 3];
        $GLOBALS['_OPTIONS']          = [1, 2, 3];
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
     * @covers \Cosma\Phest\TestCase\WebTestCase::mockService()
     */
    public function testMockService()
    {
        $di = Di::getDefault();
    }
}