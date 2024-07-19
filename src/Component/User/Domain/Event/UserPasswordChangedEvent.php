<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Event;

use App\Common\Domain\EventInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class UserPasswordChangedEvent implements EventInterface
{
    public function __construct(
        public UuidInterface $userId,
    ) {
    }
}
