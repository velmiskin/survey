<?php

declare(strict_types=1);

namespace App\Component\Company\Domain\Storage;

use App\Component\Company\Domain\Entity\Company;

interface CompanyStorageInterface
{
    public function store(Company $company): void;
}
