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

/**
 * @retry 6
 */
class UnitTestCaseTest extends UnitTestCase
{
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
     *
     *
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