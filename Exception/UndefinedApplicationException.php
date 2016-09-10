<?php

namespace Cosma\Phest\Exception;

class UndefinedApplicationException extends \Exception
{
    protected $message = 'Application variable $app must be set in boostrapTest.php';

}