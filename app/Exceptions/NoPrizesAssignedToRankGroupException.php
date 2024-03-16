<?php

namespace App\Exceptions;

class NoPrizesAssignedToRankGroupException extends \Exception
{
    public function __construct($message = 'No prizes assigned to rank group', $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
