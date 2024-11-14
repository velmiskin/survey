<?php

declare(strict_types=1);


namespace App\Component\User\Infrastructure\Doctrine\Repository;

use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Infrastructure\Doctrine\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SpecificationRepository extends ServiceEntityRepository implements UniqueEmailSpecificationInterface
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, User::class);
    }

    public function isUnique(string $email): bool
    {
        return $this->findOneBy(['email' => $email]) === null;
    }
}