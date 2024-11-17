<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Entity;

use App\Common\Domain\AggregateRoot;
use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\Event\UserPasswordChangedEvent;
use App\Component\User\Domain\Event\UserRegisteredEvent;
use App\Component\User\Domain\Exception\InvalidEmailException;
use App\Component\User\Domain\Exception\NonUniqueEmailException;
use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Domain\ValueObject\HashedPassword;
use App\Component\User\Domain\ValueObject\Role;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final class User extends AggregateRoot
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly Email $email,
        private string $firstName,
        private string $lastName,
        private HashedPassword $password,
        private readonly Role $role,
        private readonly DateTimeImmutable $createdAt,
        private readonly UniqueEmailSpecificationInterface $uniqueEmailSpecification,
    ) {
    }

    public function getPassword(): HashedPassword
    {
        return $this->password;
    }

    public function register(): void
    {
        if (!$this->uniqueEmailSpecification->isUnique((string)$this->email)) {
            throw new NonUniqueEmailException();
        }

        $this->record(
            new UserRegisteredEvent(
                $this->getId(),
                $this->getEmail(),
                $this->getFirstName(),
                $this->getLastName(),
                $this->getRole(),
                $this->getCreatedAt()
            )
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @throws InvalidEmailException
     */
    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
        $this->record(new UserPasswordChangedEvent($this->id));
    }

    public function changeFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function changeLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
