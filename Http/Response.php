<?php
namespace Cosma\Http;


use \Phalcon\Http\Response as BaseResponse;

class Response extends BaseResponse
{
    /**
     * @return $this
     */
    public function send()
    {
        return $this;
    }
}