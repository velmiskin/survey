<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

abstract class Question
{
    public function __construct(
        private readonly UuidInterface $id,
        private string $text,
        private bool $required = false,
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function changeText(string $text): void
    {
        $this->text = $text;
    }

    public function markAsRequired(): void
    {
        $this->required = true;
    }

    public function markAsOptional(): void
    {
        $this->required = false;
    }

    abstract public function getType(): string;

    abstract public function getConfiguration(): array;
}