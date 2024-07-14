<?php

namespace App\Component\User\Domain\Exception;

class InvalidEmailException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid email');
    }
}
