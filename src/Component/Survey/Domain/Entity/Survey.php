<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Entity;

use App\Common\Domain\AggregateRoot;
use App\Component\Survey\Domain\Event\QuestionAddedEvent;
use App\Component\Survey\Domain\Event\QuestionRemovedEvent;
use App\Component\Survey\Domain\Event\QuestionUpdatedEvent;
use App\Component\Survey\Domain\Event\SurveyArchivedEvent;
use App\Component\Survey\Domain\Event\SurveyCreatedEvent;
use App\Component\Survey\Domain\Event\SurveyPublishedEvent;
use App\Component\Survey\Domain\Event\SurveyUnpublishedEvent;
use App\Component\Survey\Domain\Event\SurveyUpdatedEvent;
use App\Component\Survey\Domain\Exception\EmptySurveyCannotBePublishedException;
use App\Component\Survey\Domain\Exception\QuestionNotFoundException;
use App\Component\Survey\Domain\Exception\SurveyCannotBeArchivedException;
use App\Component\Survey\Domain\Exception\SurveyCannotBeModifiedException;
use App\Component\Survey\Domain\Exception\SurveyCannotBePublishedException;
use App\Component\Survey\Domain\Exception\SurveyCannotBeUnpublishedException;
use App\Component\Survey\Domain\ValueObject\QuestionCollection;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;
use Ramsey\Uuid\UuidInterface;

final class Survey extends AggregateRoot
{
    public function __construct(
        private readonly UuidInterface $id,
        private string $title,
        private ?string $description,
        private SurveyStatus $status,
        private readonly UuidInterface $createdBy,
        private readonly \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
        private ?QuestionCollection $questions = null,
        private ?\DateTimeImmutable $publishedAt = null,
        private ?\DateTimeImmutable $unpublishedAt = null,
        private ?\DateTimeImmutable $archivedAt = null,
    ) {
        $this->questions = $questions ?? new QuestionCollection();
    }

    public static function create(
        UuidInterface $id,
        string $title,
        ?string $description,
        UuidInterface $createdBy,
        ?\DateTimeImmutable $createdAt = null,
    ): self {
        $createdAt = $createdAt ?? new \DateTimeImmutable();

        $survey = new self(
            id: $id,
            title: $title,
            description: $description,
            status: SurveyStatus::DRAFT,
            createdBy: $createdBy,
            createdAt: $createdAt,
            updatedAt: $createdAt,
        );

        $survey->record(new SurveyCreatedEvent(
            surveyId: $id,
            title: $title,
            description: $description,
            status: SurveyStatus::DRAFT,
            createdBy: $createdBy,
            createdAt: $createdAt,
        ));

        return $survey;
    }

    // Getters
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStatus(): SurveyStatus
    {
        return $this->status;
    }

    public function getCreatedBy(): UuidInterface
    {
        return $this->createdBy;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getQuestions(): QuestionCollection
    {
        return $this->questions;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getUnpublishedAt(): ?\DateTimeImmutable
    {
        return $this->unpublishedAt;
    }

    public function getArchivedAt(): ?\DateTimeImmutable
    {
        return $this->archivedAt;
    }

    // Business methods
    public function updateDetails(string $title, ?string $description): void
    {
        if (!$this->status->canBeModified()) {
            throw new SurveyCannotBeModifiedException('Survey cannot be modified when not in draft status');
        }

        $this->title = $title;
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable();

        $this->record(new SurveyUpdatedEvent(
            surveyId: $this->id,
            title: $title,
            description: $description,
            updatedAt: $this->updatedAt,
        ));
    }

    public function addQuestion(Question $question): void
    {
        if (!$this->status->canBeModified()) {
            throw new SurveyCannotBeModifiedException('Questions cannot be added when survey is not in draft status');
        }

        $this->questions->addQuestion($question);
        $this->updatedAt = new \DateTimeImmutable();

        $this->record(new QuestionAddedEvent(
            surveyId: $this->id,
            question: $question,
            addedAt: $this->updatedAt,
        ));
    }

    public function removeQuestion(UuidInterface $questionId): void
    {
        if (!$this->status->canBeModified()) {
            throw new SurveyCannotBeModifiedException('Questions cannot be removed when survey is not in draft status');
        }

        if (!$this->questions->hasQuestion($questionId)) {
            throw new QuestionNotFoundException('Question not found in survey');
        }

        $this->questions->removeQuestion($questionId);
        $this->updatedAt = new \DateTimeImmutable();

        $this->record(new QuestionRemovedEvent(
            surveyId: $this->id,
            questionId: $questionId,
            removedAt: $this->updatedAt,
        ));
    }

    public function updateQuestion(Question $question): void
    {
        if (!$this->status->canBeModified()) {
            throw new SurveyCannotBeModifiedException('Questions cannot be updated when survey is not in draft status');
        }

        if (!$this->questions->hasQuestion($question->getId())) {
            throw new QuestionNotFoundException('Question not found in survey');
        }

        $this->questions->removeQuestion($question->getId());
        $this->questions->addQuestion($question);
        $this->updatedAt = new \DateTimeImmutable();

        $this->record(new QuestionUpdatedEvent(
            surveyId: $this->id,
            question: $question,
            updatedAt: $this->updatedAt,
        ));
    }

    public function publish(): void
    {
        if (!$this->status->canBePublished()) {
            throw new SurveyCannotBePublishedException(
                sprintf('Survey cannot be published from %s status', $this->status->value)
            );
        }

        if ($this->questions->isEmpty()) {
            throw new EmptySurveyCannotBePublishedException();
        }

        $this->status = SurveyStatus::PUBLISHED;
        $this->publishedAt = new \DateTimeImmutable();
        $this->updatedAt = $this->publishedAt;

        $this->record(new SurveyPublishedEvent(
            surveyId: $this->id,
            publishedAt: $this->publishedAt,
        ));
    }

    public function unpublish(): void
    {
        if (!$this->status->canBeUnpublished()) {
            throw new SurveyCannotBeUnpublishedException(
                sprintf('Survey cannot be unpublished from %s status', $this->status->value)
            );
        }

        $this->status = SurveyStatus::UNPUBLISHED;
        $this->unpublishedAt = new \DateTimeImmutable();
        $this->updatedAt = $this->unpublishedAt;

        $this->record(new SurveyUnpublishedEvent(
            surveyId: $this->id,
            unpublishedAt: $this->unpublishedAt,
        ));
    }

    public function archive(): void
    {
        if (!$this->status->canBeArchived()) {
            throw new SurveyCannotBeArchivedException(
                sprintf('Survey cannot be archived from %s status', $this->status->value)
            );
        }

        $this->status = SurveyStatus::ARCHIVED;
        $this->archivedAt = new \DateTimeImmutable();
        $this->updatedAt = $this->archivedAt;

        $this->record(new SurveyArchivedEvent(
            surveyId: $this->id,
            archivedAt: $this->archivedAt,
        ));
    }

    // Status checks
    public function isDraft(): bool
    {
        return $this->status->isDraft();
    }

    public function isPublished(): bool
    {
        return $this->status->isPublished();
    }

    public function isUnpublished(): bool
    {
        return $this->status->isUnpublished();
    }

    public function isArchived(): bool
    {
        return $this->status->isArchived();
    }

    public function canBeModified(): bool
    {
        return $this->status->canBeModified();
    }
}