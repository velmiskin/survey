<?php

declare(strict_types=1);

namespace App\Component\Company\Infrastructure\Doctrine\Repository;

use App\Common\Domain\ValueObject\Address;
use App\Component\Company\Domain\Entity\Company;
use App\Component\Company\Domain\Presenter\CompanyPresenterInterface;
use App\Component\Company\Domain\Storage\CompanyStorageInterface;
use App\Component\Company\Infrastructure\Doctrine\Entity\Company as DoctrineCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<DoctrineCompany>
 */
final class CompanyRepository extends ServiceEntityRepository implements CompanyStorageInterface, CompanyPresenterInterface
{
    public function __construct(
        ManagerRegistry $managerRegistry,
    ) {
        parent::__construct($managerRegistry, DoctrineCompany::class);
    }

    public function findById(UuidInterface $uuid): ?Company
    {
        try {
            $company = $this->getEntityManager()->find(DoctrineCompany::class, $uuid->toString());

            if (null === $company) {
                return null;
            }

            return $this->mapCompany($company);
        } catch (ORMException) {
            return null;
        }
    }

    public function getAll(): array
    {
        $companies = $this->findAll();

        return array_map(fn (DoctrineCompany $company) => $this->mapCompany($company), $companies);
    }

    public function store(Company $company): void
    {
        $doctrineCompany = $this->find($company->getUuid()->toString());

        if (!$doctrineCompany) {
            $doctrineCompany = (new DoctrineCompany())
                ->setId($company->getUuid())
                ->setName($company->getName())
                ->setActive($company->isActive())
                ->setAddress($company->getAddress()->toArray())
                ->setCreatedAt($company->getCreatedAt());
        }

        $doctrineCompany
            ->setName($company->getName())
            ->setActive($company->isActive())
            ->setAddress($company->getAddress()->toArray())
            ->setUpdatedAt($company->getUpdatedAt());

        $this->getEntityManager()->persist($doctrineCompany);
        $this->getEntityManager()->flush();
    }

    private function mapCompany(DoctrineCompany $company): Company
    {
        $address = $company->getAddress();

        return new Company(
            uuid: $company->getId(),
            name: $company->getName(),
            active: $company->isActive(),
            address: Address::createfromArray($address),
            createdAt: $company->getCreatedAt(),
            updatedAt: $company->getUpdatedAt()
        );
    }
}
