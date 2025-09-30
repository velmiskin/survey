<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Survey\Application\Command\AddQuestionCommand;
use App\Component\Survey\Application\Exception\SurveyNotFoundException;
use App\Component\Survey\Domain\Entity\MultipleChoiceQuestion;
use App\Component\Survey\Domain\Entity\RatingQuestion;
use App\Component\Survey\Domain\Entity\TextQuestion;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;

final readonly class AddQuestionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SurveyStorageInterface $surveyStorage,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(AddQuestionCommand $command): void
    {
        $survey = $this->surveyStorage->findById($command->surveyId);
        if (!$survey) {
            throw new SurveyNotFoundException($command->surveyId);
        }

        $question = $this->createQuestion($command);
        $survey->addQuestion($question);

        $this->surveyStorage->store($survey);
        $this->eventBus->dispatchMany($survey->pullEvents());
    }

    private function createQuestion(AddQuestionCommand $command): TextQuestion|MultipleChoiceQuestion|RatingQuestion
    {
        return match ($command->type) {
            'text' => new TextQuestion(
                id: $command->questionId,
                text: $command->text,
                order: 1,
                required: $command->required,
                maxLength: $command->options['maxLength'] ?? null,
                multiline: $command->options['multiline'] ?? false,
            ),
            'multiple_choice' => new MultipleChoiceQuestion(
                id: $command->questionId,
                text: $command->text,
                order: 1,
                required: $command->required,
                options: $command->options['choices'] ?? [],
                allowMultiple: $command->options['allowMultiple'] ?? false,
            ),
            'rating' => new RatingQuestion(
                id: $command->questionId,
                text: $command->text,
                order: 1,
                required: $command->required,
                minValue: $command->options['minValue'] ?? 1,
                maxValue: $command->options['maxValue'] ?? 5,
                minLabel: $command->options['minLabel'] ?? null,
                maxLabel: $command->options['maxLabel'] ?? null,
            ),
            default => throw new \InvalidArgumentException(\sprintf('Unsupported question type: %s', $command->type)),
        };
    }
}