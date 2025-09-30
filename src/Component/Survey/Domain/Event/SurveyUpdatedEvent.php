<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Event;

use App\Common\Domain\EventInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class SurveyUpdatedEvent implements EventInterface
{
    public function __construct(
        public UuidInterface $surveyId,
        public string $title,
        public ?string $description,
        public \DateTimeImmutable $updatedAt,
    ) {
    }
}