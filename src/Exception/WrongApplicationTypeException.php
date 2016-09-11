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

namespace Cosma\Phest\Exception;

class WrongApplicationTypeException extends \Exception
{
    protected $message = 'Application variable $app must be of type \Phalcon\Mvc\Micro or \Phalcon\Mvc\Application';

}