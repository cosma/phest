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

namespace Cosma\Phest\Tests\Http;

use Cosma\Phest\Http\Response;

/**
 * @covers \Cosma\Phest\Http\Response <extended>
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Cosma\Phest\Http\Response::send()
     */
    public function testSend(){
        $response = new Response();
        $result = $response->send();
        $this->assertInstanceOf('\Phalcon\Http\Response', $result);
    }
}