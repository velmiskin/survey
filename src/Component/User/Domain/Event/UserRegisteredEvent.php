<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Event;

use App\Common\Domain\EventInterface;
use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\ValueObject\Role;
use Ramsey\Uuid\UuidInterface;

final readonly class UserRegisteredEvent implements EventInterface
{
    public function __construct(
        public UuidInterface $userId,
        public Email $email,
        public string $firstName,
        public string $lastName,
        public Role $role,
        public \DateTimeImmutable $createdAt,
    ) {
    }
}
