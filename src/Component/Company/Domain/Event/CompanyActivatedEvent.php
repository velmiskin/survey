<?php

declare(strict_types=1);


namespace App\Component\Company\Domain\Event;

use App\Common\Domain\EventInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class CompanyActivatedEvent implements EventInterface
{
    public function __construct(
        public UuidInterface $uuid
    ) {
    }
}