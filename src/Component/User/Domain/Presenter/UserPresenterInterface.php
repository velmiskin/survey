<?php

namespace App\Component\User\Domain\Presenter;

use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface UserPresenterInterface
{
    public function findById(UuidInterface $id): ?User;

    public function findByEmail(Email $email): ?User;

    /**
     * @return list<User>
     */
    public function getAll(): array;
}
