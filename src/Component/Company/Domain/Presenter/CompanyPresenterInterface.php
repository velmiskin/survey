<?php

declare(strict_types=1);

namespace App\Component\Company\Domain\Presenter;

use App\Component\Company\Domain\Entity\Company;
use Ramsey\Uuid\UuidInterface;

interface CompanyPresenterInterface
{
    public function findById(UuidInterface $uuid): ?Company;

    /**
     * @return list<Company>
     */
    public function getAll(): array;
}
