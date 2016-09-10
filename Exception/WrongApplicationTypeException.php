<?php

namespace Cosma\Phest\Exception;

class WrongApplicationTypeException extends \Exception
{
    protected $message = 'Application variable $app must be of type \Phalcon\Mvc\Micro or \Phalcon\Mvc\Application';

}