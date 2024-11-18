<?php

namespace App\Component\User\Domain\Exception;

class InvalidEmailException extends \DomainException
{
    public const MESSAGE = 'Invalid email format';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
