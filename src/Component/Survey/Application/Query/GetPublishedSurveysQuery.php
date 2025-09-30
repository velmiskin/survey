<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\Query;

use App\Common\Application\Query\QueryInterface;

final readonly class GetPublishedSurveysQuery implements QueryInterface
{
    public function __construct(
        public int $limit = 50,
        public int $offset = 0,
    ) {
    }
}