<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\Command;

use App\Common\Application\Command\CommandInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class ArchiveSurveyCommand implements CommandInterface
{
    public function __construct(
        public UuidInterface $surveyId,
    ) {
    }
}