<?php

declare(strict_types=1);

namespace App\Component\Company\Application\Exception;

use DomainException;

final class CompanyNotFoundException extends DomainException
{
    public const string MESSAGE = 'Company not found';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
