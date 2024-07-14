<?php

declare(strict_types=1);


namespace App\Component\User\Infrastructure\Storage;

use App\Component\User\Domain\Entity\User;
use App\Component\User\Domain\Presenter\UserPresenterInterface;
use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Domain\Storage\UserStorageInterface;
use Ramsey\Uuid\UuidInterface;

class UserStorage implements UserStorageInterface, UniqueEmailSpecificationInterface, UserPresenterInterface
{
    public function store(User $user): void
    {
        // TODO: Implement store() method.
    }

    public function isUnique(string $email): bool
    {
        return true;
    }

    public function findById(UuidInterface $id): ?User
    {
        // TODO: Implement findById() method.
        return null;
    }


}