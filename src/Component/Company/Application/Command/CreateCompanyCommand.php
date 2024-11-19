<?php

declare(strict_types=1);

namespace App\Component\Company\Application\Command;

use App\Common\Application\Command\CommandInterface;
use App\Common\Domain\ValueObject\Address;
use Ramsey\Uuid\UuidInterface;

final readonly class CreateCompanyCommand implements CommandInterface
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name,
        public Address $address,
    ) {
    }
}
