<?php

declare(strict_types=1);

namespace App\Component\User\Application\Command;

use App\Common\Application\Command\CommandInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class ChangeUserPasswordCommand implements CommandInterface
{
    public function __construct(
        public UuidInterface $userId,
        public string $oldPassword,
        public string $newPassword,
    ) {
    }
}
