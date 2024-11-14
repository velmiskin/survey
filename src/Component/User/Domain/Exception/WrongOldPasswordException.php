<?php

declare(strict_types=1);


namespace App\Component\User\Domain\Exception;

use DomainException;

class WrongOldPasswordException extends DomainException
{
    public const MESSAGE = 'Old password is wrong';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}