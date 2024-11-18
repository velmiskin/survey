<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Factory;

use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\Entity\User;
use App\Component\User\Domain\Enum\Role as RoleEnum;
use App\Component\User\Domain\Exception\InvalidEmailException;
use App\Component\User\Domain\Exception\InvalidPasswordException;
use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Domain\ValueObject\HashedPassword;
use App\Component\User\Domain\ValueObject\Role;
use Ramsey\Uuid\UuidInterface;

final readonly class UserFactory
{
    public function __construct(
        private UniqueEmailSpecificationInterface $uniqueEmailSpecification,
    ) {
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidPasswordException
     */
    public function register(
        UuidInterface $id,
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        string $role,
        \DateTimeImmutable $createdAt,
    ): User {
        $user = $this->create($id, $email, $password, $firstName, $lastName, $role, $createdAt);
        $user->register();

        return $user;
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidPasswordException
     */
    public function create(
        UuidInterface $id,
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        string $role,
        \DateTimeImmutable $createdAt,
    ): User {
        return new User(
            $id,
            new Email($email),
            $firstName,
            $lastName,
            HashedPassword::createFormPlain($password),
            new Role(RoleEnum::from($role)),
            $createdAt,
            $this->uniqueEmailSpecification
        );
    }
}
