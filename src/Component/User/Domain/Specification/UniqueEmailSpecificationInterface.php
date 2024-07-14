<?php

namespace App\Component\User\Domain\Specification;

interface UniqueEmailSpecificationInterface
{
    public function isUnique(string $email): bool;
}
