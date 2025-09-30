<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Survey\Application\Command\UpdateQuestionCommand;
use App\Component\Survey\Application\Exception\SurveyNotFoundException;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;

final readonly class UpdateQuestionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(UpdateQuestionCommand $command): void
    {
        $survey = $this->surveyStorage->findById($command->surveyId);
        if (!$survey) {
            throw new SurveyNotFoundException($command->surveyId);
        }

        $survey->updateQuestion($command->questionId, $command->text, $command->options);

        $this->surveyStorage->store($survey);
        $this->eventBus->dispatchMany($survey->pullEvents());
    }
}