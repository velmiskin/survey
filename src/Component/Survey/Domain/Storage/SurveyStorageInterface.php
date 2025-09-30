<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Storage;

use App\Component\Survey\Domain\Entity\Survey;
use App\Component\Survey\Domain\ValueObject\SurveyStatus;
use Ramsey\Uuid\UuidInterface;

interface SurveyStorageInterface
{
    public function save(Survey $survey): void;

    public function findById(UuidInterface $id): ?Survey;

    public function findByIdOrFail(UuidInterface $id): Survey;

    /**
     * @return Survey[]
     */
    public function findByCreatedBy(UuidInterface $createdBy): array;

    /**
     * @return Survey[]
     */
    public function findByStatus(SurveyStatus $status): array;

    /**
     * @return Survey[]
     */
    public function findByCreatedByAndStatus(UuidInterface $createdBy, SurveyStatus $status): array;

    /**
     * @return Survey[]
     */
    public function findPublishedSurveys(): array;

    public function delete(Survey $survey): void;

    public function exists(UuidInterface $id): bool;
}