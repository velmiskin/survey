<?php

namespace App\Component\User\Domain\Exception;

use DomainException;

class InvalidEmailException extends DomainException
{
    public const MESSAGE = 'Invalid email format';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
