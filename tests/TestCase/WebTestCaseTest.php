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
     * @covers \Cosma\Phest\TestCase\UnitTestCase::getTestClassPath()
     */
    public function testGetTestClassPath()
    {
        $this->assertContains('/tests/TestCase/WebTestCaseTest', $this->getTestClassPath());
    }


}