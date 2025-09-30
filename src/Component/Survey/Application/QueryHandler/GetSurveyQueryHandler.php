<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\QueryHandler;

use App\Common\Application\Query\QueryHandlerInterface;
use App\Component\Survey\Application\Exception\SurveyNotFoundException;
use App\Component\Survey\Application\Query\GetSurveyQuery;
use App\Component\Survey\Domain\Entity\Survey;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;

final readonly class GetSurveyQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
    ) {
    }

    public function __invoke(GetSurveyQuery $query): Survey
    {
        $survey = $this->surveyStorage->findById($query->surveyId);
        if (!$survey) {
            throw new SurveyNotFoundException($query->surveyId);
        }

        return $survey;
    }
}