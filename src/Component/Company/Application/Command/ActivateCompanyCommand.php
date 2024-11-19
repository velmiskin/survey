<?php

declare(strict_types=1);

namespace App\Component\Company\Application\Command;

use App\Common\Application\Command\CommandInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class ActivateCompanyCommand implements CommandInterface
{
    public function __construct(
        public UuidInterface $uuid,
    ) {
    }
}
