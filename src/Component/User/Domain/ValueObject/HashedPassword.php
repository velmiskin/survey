<?php

declare(strict_types=1);

namespace App\Component\User\Domain\ValueObject;

use App\Component\User\Domain\Exception\InvalidPasswordException;

class HashedPassword implements \Stringable
{
    private string $password = '';

    private function __construct()
    {
    }

    /**
     * @throws InvalidPasswordException
     */
    public static function createFormPlain(string $plainPassword): self
    {
        $instance = new self();

        if (!$instance->validatePassword($plainPassword)) {
            throw new InvalidPasswordException();
        }

        $instance->password = $instance->hash($plainPassword);

        return $instance;
    }

    private function validatePassword(string $password): bool
    {
        return strlen($password) >= 8;
    }

    private function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    public function __toString(): string
    {
        return $this->password;
    }

    public function equals(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
