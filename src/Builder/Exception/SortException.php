<?php

namespace App\Builder\Exception;

class SortException extends \Exception
{

    public function __construct()
    {
        parent::__construct("The sort flag must be either DESC or ASC");
    }
}