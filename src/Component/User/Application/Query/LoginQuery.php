<?php

declare(strict_types=1);

namespace App\Component\User\Application\Query;

use App\Common\Application\Query\QueryInterface;

final readonly class LoginQuery implements QueryInterface
{
    public function __construct(
        public string $email,
        public string $password,
    )
    {
    }
}
