<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Entity;

use App\Common\Domain\AggregateRoot;
use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\Event\UserCreatedEvent;
use App\Component\User\Domain\Event\UserPasswordChangedEvent;
use App\Component\User\Domain\Exception\InvalidEmailException;
use App\Component\User\Domain\Exception\InvalidPasswordException;
use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Domain\ValueObject\Password;
use App\Component\User\Domain\ValueObject\Role;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final class User extends AggregateRoot
{
    /**
     * @throws InvalidEmailException
     * @throws InvalidPasswordException
     */
    public function __construct(
        private readonly UuidInterface $id,
        private readonly Email $email,
        private string $firstName,
        private string $lastName,
        private Password $password,
        private readonly Role $role,
        private readonly DateTimeImmutable $createdAt,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
    ) {
        $uniqueEmailSpecification->isUnique((string)$email);
        $this->record(new UserCreatedEvent($this->id, $this->email, $this->firstName, $this->lastName, $this->role, $this->createdAt));
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
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
    public function changePassword(Password $password): void
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
