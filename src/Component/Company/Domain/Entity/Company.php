<?php

declare(strict_types=1);

namespace App\Component\Company\Domain\Entity;

use App\Common\Domain\AggregateRoot;
use App\Common\Domain\ValueObject\Address;
use App\Component\Company\Domain\Event\CompanyActivatedEvent;
use App\Component\Company\Domain\Event\CompanyDeactivatedEvent;
use Ramsey\Uuid\UuidInterface;

final class Company extends AggregateRoot
{
    public function __construct(
        private readonly UuidInterface $uuid,
        private string $name,
        private bool $active,
        private Address $address,
        private readonly \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
    ) {
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function activate(): void
    {
        $this->active = true;
        $this->record(new CompanyActivatedEvent($this->uuid));
    }

    public function deactivate(): void
    {
        $this->active = false;
        $this->record(new CompanyDeactivatedEvent($this->uuid));
    }

    public function changeAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): Company
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
