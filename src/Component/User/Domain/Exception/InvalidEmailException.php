<?php

namespace App\Component\User\Domain\Exception;

use DomainException;

class InvalidEmailException extends DomainException
{
    public function __construct(?string $message)
    {
        parent::__construct($message ?? 'Invalid email format');
    }
}
