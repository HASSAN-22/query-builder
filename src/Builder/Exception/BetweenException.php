<?php

namespace App\Builder\Exception;

class BetweenException extends \Exception
{

    public function __construct($method)
    {
        $method = explode('::',$method)[1];
        parent::__construct("The second parameter in `$method` must contain two values");
    }
}