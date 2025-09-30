<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Event;

use App\Common\Domain\EventInterface;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;
use Ramsey\Uuid\UuidInterface;

final readonly class SurveyCreatedEvent implements EventInterface
{
    public function __construct(
        public UuidInterface $surveyId,
        public string $title,
        public ?string $description,
        public SurveyStatus $status,
        public UuidInterface $createdBy,
        public \DateTimeImmutable $createdAt,
    ) {
    }
}