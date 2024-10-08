<?php

declare(strict_types=1);

namespace App\Component\User\Domain\ValueObject;

use App\Component\User\Domain\Exception\InvalidPasswordException;

class Password
{
    private string $password;

    /**
     * @throws InvalidPasswordException
     */
    public function __construct(string $password)
    {
        if ($this->validatePassword($password)) {
            throw new InvalidPasswordException();
        }

        $this->password = $password;
    }

    private function validatePassword(string $password): bool
    {
        return strlen($password) < 8;
    }

    public function __toString(): string
    {
        return $this->password;
    }

    public function equals(Password $password): bool
    {
        return $this->password === (string)$password;
    }
}
