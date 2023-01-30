<?php

namespace App\Builder\Exception;

class CrudException extends \Exception
{

    public function __construct($method,$message='')
    {
        $method = explode('::',$method)[1];
        parent::__construct($message == '' ? "The `$method parameter must contain a values" : str_replace(':method',$method,$message));
    }
}