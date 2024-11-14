<?php

declare(strict_types=1);


namespace App\Component\User\Domain\Exception;

use DomainException;

class NonUniqueEmailException extends DomainException
{
    public const MESSAGE = 'User with this email already exists';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }

}