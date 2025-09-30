<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\Query;

use App\Common\Application\Query\QueryInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class GetSurveyQuery implements QueryInterface
{
    public function __construct(
        public UuidInterface $surveyId,
    ) {
    }
}