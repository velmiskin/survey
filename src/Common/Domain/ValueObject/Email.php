<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use App\Component\User\Domain\Exception\InvalidEmailException;

class Email implements \Stringable
{
    private string $email;

    /**
     * @throws InvalidEmailException
     */
    public function __construct(string $email)
    {
        if (!$this->validateEmail($email)) {
            throw new InvalidEmailException();
        }
        $this->email = $email;
    }

    private function validateEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
