<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\Query;

use App\Common\Application\Query\QueryInterface;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;
use Ramsey\Uuid\UuidInterface;

final readonly class GetSurveysByCreatorQuery implements QueryInterface
{
    public function __construct(
        public UuidInterface $creatorId,
        public ?SurveyStatus $status = null,
    ) {
    }
}