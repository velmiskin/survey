<?php

declare(strict_types=1);


namespace App\Component\User\Application\Command;

use App\Common\Application\Command\CommandInterface;
use Ramsey\Uuid\Uuid;

final readonly class ChangeUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private Uuid $userId,
        private string $newPassword
    ) {
    }
}