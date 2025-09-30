<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

final class MultipleChoiceQuestion extends Question
{
    /**
     * @param string[] $options
     */
    public function __construct(
        UuidInterface $id,
        string $text,
        bool $required = false,
        private array $options = [],
        private bool $allowMultipleSelections = false,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {
        parent::__construct($id, $text, $required, $createdAt);
    }

    public function getType(): string
    {
        return 'multiple_choice';
    }

    public function getConfiguration(): array
    {
        return [
            'options' => $this->options,
            'allowMultipleSelections' => $this->allowMultipleSelections,
        ];
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function allowsMultipleSelections(): bool
    {
        return $this->allowMultipleSelections;
    }

    public function addOption(string $option): void
    {
        if (!in_array($option, $this->options, true)) {
            $this->options[] = $option;
        }
    }

    public function removeOption(string $option): void
    {
        $this->options = array_values(array_filter(
            $this->options,
            static fn(string $existingOption): bool => $existingOption !== $option
        ));
    }

    /**
     * @param string[] $options
     */
    public function setOptions(array $options): void
    {
        $this->options = array_values(array_unique($options));
    }

    public function enableMultipleSelections(): void
    {
        $this->allowMultipleSelections = true;
    }

    public function disableMultipleSelections(): void
    {
        $this->allowMultipleSelections = false;
    }
}