<?php

declare(strict_types=1);

namespace App\Component\Company\Infrastructure\Doctrine\Entity;

use App\Component\Company\Infrastructure\Doctrine\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[HasLifecycleCallbacks]
#[ORM\Table(name: 'company')]
class Company
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'boolean')]
    private bool $active;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: 'json')]
    private array $address;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): Company
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Company
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): Company
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getAddress(): array
    {
        return $this->address;
    }

    /**
     * @param array<string, string> $address
     */
    public function setAddress(array $address): Company
    {
        $this->address = $address;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): Company
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): Company
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
