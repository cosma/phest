<?php
namespace Cosma\Phest\Http;

use \Phalcon\Http\Response as OverwrittenResponse;

class Response extends OverwrittenResponse
{
    /**
     * @return $this
     */
    public function send()
    {
        return $this;
    }
}