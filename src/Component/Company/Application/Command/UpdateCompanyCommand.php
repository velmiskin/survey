<?php

declare(strict_types=1);

namespace App\Component\Company\Application\Command;

use App\Common\Domain\ValueObject\Address;
use Ramsey\Uuid\UuidInterface;

final readonly class UpdateCompanyCommand
{
    public function __construct(public UuidInterface $uuid, public ?string $name, public ?Address $address)
    {
    }
}
