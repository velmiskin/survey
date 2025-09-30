<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\QueryHandler;

use App\Common\Application\Query\QueryHandlerInterface;
use App\Component\Survey\Application\Query\GetSurveysByCreatorQuery;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;

final readonly class GetSurveysByCreatorQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
    ) {
    }

    /**
     * @return Survey[]
     */
    public function __invoke(GetSurveysByCreatorQuery $query): array
    {
        return $this->surveyStorage->findByCreator($query->creatorId, $query->status);
    }
}