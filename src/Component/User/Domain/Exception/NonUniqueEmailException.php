<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Exception;

class NonUniqueEmailException extends \DomainException
{
    public const MESSAGE = 'User with this email already exists';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
