<?php

declare(strict_types=1);

namespace App\Component\User\Infrastructure\Doctrine\Repository;

use App\Component\User\Domain\Entity\User;
use App\Component\User\Domain\Factory\UserFactory;
use App\Component\User\Domain\Presenter\UserPresenterInterface;
use App\Component\User\Domain\Storage\UserStorageInterface;
use App\Component\User\Infrastructure\Doctrine\Entity\User as DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<DoctrineUser>
 */
final class UserRepository extends ServiceEntityRepository implements UserStorageInterface, UserPresenterInterface
{
    public function __construct(
        private readonly UserFactory $userFactory,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, DoctrineUser::class);
    }

    public function store(User $user): void
    {
        $doctrineUser = $this->find($user->getId()->toString());

        if (!$doctrineUser) {
            $doctrineUser = (new DoctrineUser())
                ->setId($user->getId())
                ->setEmail((string) $user->getEmail())
                ->setCreatedAt($user->getCreatedAt());
        }

        $doctrineUser
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setPassword((string) $user->getPassword())
            ->setRole((string) $user->getRole());

        $this->getEntityManager()->persist($doctrineUser);
        $this->getEntityManager()->flush();
    }

    public function findById(UuidInterface $id): ?User
    {
        $user = $this->find($id->toString());

        if (null === $user) {
            return null;
        }

        return $this->userFactory->create(
            $user->getId(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getRole(),
            $user->getCreatedAt()
        );
    }

    /**
     * @return list<User>
     */
    public function getAll(): array
    {
        return array_map(function (DoctrineUser $user) {
            return $this->userFactory->create(
                $user->getId(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getRole(),
                $user->getCreatedAt()
            );
        }, $this->findAll());
    }
}
