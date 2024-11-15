<?php

namespace App\Component\User\Domain\Presenter;

use App\Component\User\Domain\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface UserPresenterInterface
{
    public function findById(UuidInterface $id): ?User;

    /**
     * @return list<User>
     */
    public function getAll(): array;
}
