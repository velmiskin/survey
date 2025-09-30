<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\Command;

use App\Common\Application\Command\CommandInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class ReorderQuestionsCommand implements CommandInterface
{
    /**
     * @param UuidInterface[] $questionIds
     */
    public function __construct(
        public UuidInterface $surveyId,
        public array $questionIds,
    ) {
    }
}