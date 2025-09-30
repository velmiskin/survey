<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Survey\Application\Command\CreateSurveyCommand;
use App\Component\Survey\Domain\Entity\Survey;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;
use App\Component\Survey\Domain\ValueObject\QuestionCollection;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;
use Symfony\Component\Clock\ClockInterface;

final readonly class CreateSurveyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
        private ClockInterface $clock,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateSurveyCommand $command): void
    {
        $survey = new Survey(
            id: $command->uuid,
            title: $command->title,
            description: $command->description,
            status: SurveyStatus::DRAFT,
            creatorId: $command->creatorId,
            questions: new QuestionCollection(),
            createdAt: $this->clock->now(),
        );

        $this->surveyStorage->store($survey);
        $this->eventBus->dispatchMany($survey->pullEvents());
    }
}