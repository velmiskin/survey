<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\QueryHandler;

use App\Common\Application\Query\QueryHandlerInterface;
use App\Component\Survey\Application\Query\GetSurveysByStatusQuery;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;

final readonly class GetSurveysByStatusQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
    ) {
    }

    /**
     * @return Survey[]
     */
    public function __invoke(GetSurveysByStatusQuery $query): array
    {
        return $this->surveyStorage->findByStatus($query->status, $query->limit, $query->offset);
    }
}