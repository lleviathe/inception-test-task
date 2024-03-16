<?php

namespace App\Exceptions;

class UserDoesNotHaveRankGroupException extends \Exception
{
    public function __construct($message = 'User does not have rank group', $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
