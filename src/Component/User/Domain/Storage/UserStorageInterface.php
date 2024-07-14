<?php

namespace App\Component\User\Domain\Storage;

use App\Component\User\Domain\Entity\User;

interface UserStorageInterface
{
    public function store(User $user): void;
}
