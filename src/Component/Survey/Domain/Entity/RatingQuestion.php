<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Entity;

use App\Component\Survey\Domain\Exception\InvalidRatingScaleException;
use Ramsey\Uuid\UuidInterface;

final class RatingQuestion extends Question
{
    public function __construct(
        UuidInterface $id,
        string $text,
        bool $required = false,
        private int $minValue = 1,
        private int $maxValue = 5,
        private ?string $minLabel = null,
        private ?string $maxLabel = null,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {
        $this->validateScale($minValue, $maxValue);
        parent::__construct($id, $text, $required, $createdAt);
    }

    public function getType(): string
    {
        return 'rating';
    }

    public function getConfiguration(): array
    {
        return [
            'minValue' => $this->minValue,
            'maxValue' => $this->maxValue,
            'minLabel' => $this->minLabel,
            'maxLabel' => $this->maxLabel,
        ];
    }

    public function getMinValue(): int
    {
        return $this->minValue;
    }

    public function getMaxValue(): int
    {
        return $this->maxValue;
    }

    public function getMinLabel(): ?string
    {
        return $this->minLabel;
    }

    public function getMaxLabel(): ?string
    {
        return $this->maxLabel;
    }

    public function setScale(int $minValue, int $maxValue): void
    {
        $this->validateScale($minValue, $maxValue);
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
    }

    public function setLabels(?string $minLabel, ?string $maxLabel): void
    {
        $this->minLabel = $minLabel;
        $this->maxLabel = $maxLabel;
    }

    private function validateScale(int $minValue, int $maxValue): void
    {
        if ($minValue >= $maxValue) {
            throw new InvalidRatingScaleException('Minimum value must be less than maximum value');
        }

        if ($maxValue - $minValue < 1) {
            throw new InvalidRatingScaleException('Rating scale must have at least 2 values');
        }

        if ($maxValue - $minValue > 10) {
            throw new InvalidRatingScaleException('Rating scale cannot have more than 10 values');
        }
    }
}