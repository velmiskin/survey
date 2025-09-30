<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Event;

use App\Common\Domain\EventInterface;
use App\Component\Survey\Domain\Entity\Question;
use Ramsey\Uuid\UuidInterface;

final readonly class QuestionAddedEvent implements EventInterface
{
    public function __construct(
        public UuidInterface $surveyId,
        public Question $question,
        public \DateTimeImmutable $addedAt,
    ) {
    }
}