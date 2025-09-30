<?php

namespace App\Component\Survey\Infrastructure\Doctrine\Repository;

use App\Component\Survey\Domain\Entity\Survey;
use App\Component\Survey\Domain\Storage\SurveyStorageInterface;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;
use Ramsey\Uuid\UuidInterface;

class SurveyRepository implements SurveyStorageInterface
{
    public function save(Survey $survey): void
    {
        // TODO: Implement save() method.
    }

    public function findById(UuidInterface $id): ?Survey
    {
        // TODO: Implement findById() method.
    }

    public function findByIdOrFail(UuidInterface $id): Survey
    {
        // TODO: Implement findByIdOrFail() method.
    }

    public function findByCreatedBy(UuidInterface $createdBy): array
    {
        // TODO: Implement findByCreatedBy() method.
    }

    public function findByStatus(SurveyStatus $status): array
    {
        // TODO: Implement findByStatus() method.
    }

    public function findByCreatedByAndStatus(UuidInterface $createdBy, SurveyStatus $status): array
    {
        // TODO: Implement findByCreatedByAndStatus() method.
    }

    public function findPublishedSurveys(): array
    {
        // TODO: Implement findPublishedSurveys() method.
    }

    public function delete(Survey $survey): void
    {
        // TODO: Implement delete() method.
    }

    public function exists(UuidInterface $id): bool
    {
        // TODO: Implement exists() method.
    }

}