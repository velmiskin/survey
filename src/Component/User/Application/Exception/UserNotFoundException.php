<?php

declare(strict_types=1);


namespace App\Component\User\Application\Exception;

use DomainException;

class UserNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('User not found');
    }
}