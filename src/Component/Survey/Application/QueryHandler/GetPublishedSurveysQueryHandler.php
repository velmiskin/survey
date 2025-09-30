<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\QueryHandler;

use App\Common\Application\Query\QueryHandlerInterface;
use App\Component\Survey\Application\Query\GetPublishedSurveysQuery;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;

final readonly class GetPublishedSurveysQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
    ) {
    }

    /**
     * @return Survey[]
     */
    public function __invoke(GetPublishedSurveysQuery $query): array
    {
        return $this->surveyStorage->findByStatus(SurveyStatus::PUBLISHED, $query->limit, $query->offset);
    }
}