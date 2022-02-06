<?php

namespace Emrad\Util;

class CustomResponse
{

    public $message = 'successful';
    public $status = 200;
    public $data = null;
    public $success = true;

    public function __construct($message = '', $data = null, $status = 500, $success = false){
        $this->message = $message;
        $this->data = $data;
        $this->status = $status;
        $this->success = $success;
    }

    static function success($data = null): CustomResponse
    {
        return new CustomResponse('successful', $data, 200, true);
    }

    static function failed($message, $data = null): CustomResponse
    {
        return new CustomResponse($message, $data, 200, false);
    }

    static function badRequest($message, $data = null): CustomResponse
    {
        return new CustomResponse($message, $data, 400);
    }

    static function serverError(\Exception $e = null, $message = 'an unknown server error occurred'): CustomResponse
    {
        return new CustomResponse($message, null, 500);
    }
}
