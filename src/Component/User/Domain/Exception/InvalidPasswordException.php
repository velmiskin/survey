<?php

namespace App\Component\User\Domain\Exception;

class InvalidPasswordException extends \DomainException
{
    public const MESSAGE = 'Password must be at least 8 characters long.';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
