<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Event;

use App\Common\Domain\EventInterface;
use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\ValueObject\Role;
use Ramsey\Uuid\UuidInterface;

final readonly class UserCreatedEvent implements EventInterface
{
    public function __construct(
        private UuidInterface $userId,
        private Email $email,
        private string $firstName,
        private string $lastName,
        private Role $role,
        private \DateTimeImmutable $createdAt
    ) {
    }
}
