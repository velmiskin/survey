<?php

namespace App\Component\User\Domain\Exception;

class InvalidPasswordException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Password must be at least 8 characters long.');
    }
}
