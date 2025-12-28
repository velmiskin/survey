<?php

declare(strict_types=1);

namespace App\Component\User\Application\DTO;

use App\Component\User\Domain\Entity\User;

final readonly class LoginResult
{
    public function __construct(
        public string $token,
        public User $user,
    ) {
    }
}
