<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

final class TextQuestion extends Question
{
    public function __construct(
        UuidInterface $id,
        string $text,
        bool $required = false,
        private ?int $maxLength = null,
        private bool $multiline = false,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {
        parent::__construct($id, $text, $required, $createdAt);
    }

    public function getType(): string
    {
        return 'text';
    }

    public function getConfiguration(): array
    {
        return [
            'maxLength' => $this->maxLength,
            'multiline' => $this->multiline,
        ];
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function isMultiline(): bool
    {
        return $this->multiline;
    }

    public function setMaxLength(?int $maxLength): void
    {
        $this->maxLength = $maxLength;
    }

    public function enableMultiline(): void
    {
        $this->multiline = true;
    }

    public function disableMultiline(): void
    {
        $this->multiline = false;
    }
}