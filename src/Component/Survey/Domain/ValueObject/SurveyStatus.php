<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\ValueObject;

enum SurveyStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case UNPUBLISHED = 'unpublished';
    case ARCHIVED = 'archived';

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function isPublished(): bool
    {
        return $this === self::PUBLISHED;
    }

    public function isUnpublished(): bool
    {
        return $this === self::UNPUBLISHED;
    }

    public function isArchived(): bool
    {
        return $this === self::ARCHIVED;
    }

    public function canBeModified(): bool
    {
        return $this === self::DRAFT;
    }

    public function canBePublished(): bool
    {
        return $this === self::DRAFT || $this === self::UNPUBLISHED;
    }

    public function canBeUnpublished(): bool
    {
        return $this === self::PUBLISHED;
    }

    public function canBeArchived(): bool
    {
        return $this === self::UNPUBLISHED;
    }
}