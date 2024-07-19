<?php

declare(strict_types=1);


namespace App\Component\User\Infrastructure\Doctrine\Repository;

use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;

class SpecificationRepository implements UniqueEmailSpecificationInterface
{
    public function isUnique(string $email): bool
    {
        return true;
    }
}