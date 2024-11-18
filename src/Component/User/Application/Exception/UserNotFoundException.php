<?php

declare(strict_types=1);

namespace App\Component\User\Application\Exception;

class UserNotFoundException extends \DomainException
{
    public const MESSAGE = 'User not found';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
