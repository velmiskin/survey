<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\Query;

use App\Common\Application\Query\QueryInterface;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;

final readonly class GetSurveysByStatusQuery implements QueryInterface
{
    public function __construct(
        public SurveyStatus $status,
        public int $limit = 50,
        public int $offset = 0,
    ) {
    }
}