<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Event;

use App\Common\Domain\EventInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class QuestionRemovedEvent implements EventInterface
{
    public function __construct(
        public UuidInterface $surveyId,
        public UuidInterface $questionId,
        public \DateTimeImmutable $removedAt,
    ) {
    }
}