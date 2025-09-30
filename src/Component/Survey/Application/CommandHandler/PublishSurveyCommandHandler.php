<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Survey\Application\Command\PublishSurveyCommand;
use App\Component\Survey\Application\Exception\SurveyNotFoundException;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;

final readonly class PublishSurveyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(PublishSurveyCommand $command): void
    {
        $survey = $this->surveyStorage->findById($command->surveyId);
        if (!$survey) {
            throw new SurveyNotFoundException($command->surveyId);
        }

        $survey->publish();

        $this->surveyStorage->store($survey);
        $this->eventBus->dispatchMany($survey->pullEvents());
    }
}