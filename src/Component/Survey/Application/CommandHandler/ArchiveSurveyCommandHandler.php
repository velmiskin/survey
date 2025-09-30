<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Survey\Application\Command\ArchiveSurveyCommand;
use App\Component\Survey\Application\Exception\SurveyNotFoundException;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;

final readonly class ArchiveSurveyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(ArchiveSurveyCommand $command): void
    {
        $survey = $this->surveyStorage->findById($command->surveyId);
        if (!$survey) {
            throw new SurveyNotFoundException($command->surveyId);
        }

        $survey->archive();

        $this->surveyStorage->store($survey);
        $this->eventBus->dispatchMany($survey->pullEvents());
    }
}