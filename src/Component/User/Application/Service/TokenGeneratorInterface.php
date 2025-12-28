<?php

declare(strict_types=1);

namespace App\Component\User\Application\Service;

use App\Component\User\Domain\Entity\User;

interface TokenGeneratorInterface
{
    /**
     * Generate authentication token for the given user
     */
    public function generate(User $user): string;
}
